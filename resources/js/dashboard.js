import "grapesjs/dist/css/grapes.min.css";
import grapesjs from "grapesjs";

// Créez une nouvelle instance de GrapesJS avec la configuration souhaitée
const editor = grapesjs.init({
  // Indiquez où initialiser l'éditeur. Vous pouvez également passer un élément HTMLElement
  container: "#gjs",
  // Obtenez le contenu du canvas directement à partir de l'élément
  // En alternative, nous pourrions utiliser: `components: '<h1>Hello World Component!</h1>'`,
  fromElement: true,
  // Taille de l'éditeur
  height: "300px",
  width: "auto",
  // Désactivez le gestionnaire de stockage pour le moment
  storageManager: false,

  // Blocs d'ajout
  blockManager: {
    appendTo: "#blocks",
    blocks: [
      {
        id: "section", // l'ID est obligatoire
        label: "<b>Section</b>", // Vous pouvez utiliser du HTML/SVG à l'intérieur des libellés
        attributes: { class: "gjs-block-section" },
        content: `<section>
                            <h1>Ceci est un titre simple</h1>
                            <div>Ceci est juste un texte Lorem : Lorem ipsum dolor sit amet</div>
                        </section>`,
      },
      {
        id: "text",
        label: "Texte",
        content: '<div data-gjs-type="text">Insérez votre texte ici</div>',
      },
      {
        id: "image",
        label: "Image",
        // Sélectionnez le composant une fois qu'il est déposé
        select: true,
        // Vous pouvez passer des composants sous forme de JSON au lieu d'une simple chaîne HTML,
        // dans ce cas, nous utilisons également un type de composant défini `image`
        content: { type: "image" },
        // Cela déclenche l'événement `active` sur les composants déposés et l'`image`
        // réagit en ouvrant l'AssetManager
        activate: true,
      },
    ],
  },

  layerManager: {
    appendTo: ".layers-container",
  },
  // We define a default panel as a sidebar to contain layers
  panels: {
    defaults: [
      {
        id: "layers",
        el: ".panel__right",
        // Make the panel resizable
        resizable: {
          maxDim: 350,
          minDim: 200,
          tc: 0, // Top handler
          cl: 1, // Left handler
          cr: 0, // Right handler
          bc: 0, // Bottom handler
          // Being a flex child we need to change `flex-basis` property
          // instead of the `width` (default)
          keyWidth: "flex-basis",
        },
      },
      {
        id: "panel-switcher",
        el: ".panel__switcher",
        buttons: [
          {
            id: "show-layers",
            active: true,
            label: "Layers",
            command: "show-layers",
            // Once activated disable the possibility to turn it off
            togglable: false,
          },
          {
            id: "show-style",
            active: true,
            label: "Styles",
            command: "show-styles",
            togglable: false,
          },
        ],
      },
    ],
  },

  selectorManager: {
    appendTo: ".styles-container",
  },
  styleManager: {
    appendTo: ".styles-container",
    sectors: [
      {
        name: "Dimension",
        open: false,
        // Use built-in properties
        buildProps: ["width", "min-height", "padding"],
        // Use `properties` to define/override single property
        properties: [
          {
            // Type of the input,
            // options: integer | radio | select | color | slider | file | composite | stack
            type: "integer",
            name: "The width", // Label for the property
            property: "width", // CSS property (if buildProps contains it will be extended)
            units: ["px", "%"], // Units, available only for 'integer' types
            defaults: "auto", // Default value
            min: 0, // Min value, available only for 'integer' types
          },
        ],
      },
      {
        name: "Extra",
        open: false,
        buildProps: ["background-color", "box-shadow", "custom-prop"],
        properties: [
          {
            id: "custom-prop",
            name: "Custom Label",
            property: "font-size",
            type: "select",
            defaults: "32px",
            // List of options, available only for 'select' and 'radio'  types
            options: [
              { value: "12px", name: "Tiny" },
              { value: "18px", name: "Medium" },
              { value: "32px", name: "Big" },
            ],
          },
        ],
      },
    ],
  },
});



// Panel d'export
editor.Panels.addPanel({
  id: "panel-top",
  el: ".panel__top",
});
editor.Panels.addPanel({
  id: "basic-actions",
  el: ".panel__basic-actions",
  buttons: [
    {
      id: "visibility",
      active: true, // activé par défaut
      className: "btn-toggle-borders",
      label: "<u>B</u>",
      command: "sw-visibility", // Commande intégrée
    },
    {
      id: "export",
      className: "btn-open-export",
      label: "Exp",
      command: "export-template",
      context: "export-template", // Pour regrouper le contexte des boutons du même panneau
    },
    {
      id: "show-json",
      className: "btn-show-json",
      label: "JSON",
      context: "show-json",
      command(editor) {
        editor.Modal.setTitle("Composants JSON")
          .setContent(
            `<textarea style="width:100%; height: 250px;">
                            ${JSON.stringify(editor.getComponents())}
                        </textarea>`
          )
          .open();
      },
    },
  ],
});

// commandes personnalisées
editor.Commands.add("show-layers", {
  getRowEl(editor) {
    return editor.getContainer().closest(".editor-row");
  },
  getLayersEl(row) {
    return row.querySelector(".layers-container");
  },

  run(editor, sender) {
    const lmEl = this.getLayersEl(this.getRowEl(editor));
    lmEl.style.display = "";
  },
  stop(editor, sender) {
    const lmEl = this.getLayersEl(this.getRowEl(editor));
    lmEl.style.display = "none";
  },
});
editor.Commands.add("show-styles", {
  getRowEl(editor) {
    return editor.getContainer().closest(".editor-row");
  },
  getStyleEl(row) {
    return row.querySelector(".styles-container");
  },

  run(editor, sender) {
    const smEl = this.getStyleEl(this.getRowEl(editor));
    smEl.style.display = "";
  },
  stop(editor, sender) {
    const smEl = this.getStyleEl(this.getRowEl(editor));
    smEl.style.display = "none";
  },
});
