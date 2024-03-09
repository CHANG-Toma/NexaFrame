import "grapesjs/dist/css/grapes.min.css";
import grapesjs from "grapesjs/dist/grapes.min.js";

// Créez une nouvelle instance de GrapesJS avec la configuration souhaitée
const editor = grapesjs.init({
  showOffsets: 1,
  noticeOnUnload: 0,
  container: "#gjs",
  height: "100%",
  fromElement: true,
  storageManager: { autoload: 0 },

  // Ajout de la gestion des plugins pour une meilleure extensibilité
  plugins: ["gjs-blocks-basic"],
  pluginsOpts: {
    "gjs-blocks-basic": {
      /* options */
    },
  },

  // Ajout de la gestion des composants pour une meilleure extensibilité
  panels: {
    defaults: [
      {
        id: "layers",
        el: ".panel__right",
        resizable: {
          maxDim: 350,
          minDim: 200,
          tc: 0,
          cl: 1,
          cr: 0,
          bc: 0,
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
      {
        id: "panel-devices",
        el: ".panel__devices",
        buttons: [
          {
            id: "device-desktop",
            label: "D",
            command: "set-device-desktop",
            active: true,
            togglable: false,
          },
          {
            id: "device-tablet",
            label: "T",
            command: "set-device-tablet",
            togglable: false,
          },
          {
            id: "device-mobile",
            label: "M",
            command: "set-device-mobile",
            togglable: false,
          },
        ],
      },
    ],
  },

  deviceManager: {
    devices: [
      {
        name: "Desktop",
        width: "",
        widthMedia: "1024px",
      },
      {
        name: "Tablet",
        width: "768px",
        widthMedia: "992px",
      },
      {
        name: "Mobile",
        width: "320px",
        widthMedia: "576px",
      },
    ],
  },

  selectorManager: {
    appendTo: ".styles-container",
  },

  layerManager: {
    appendTo: ".layers-container",
  },

  styleManager: {
    appendTo: ".styles-container",
    sectors: [
      {
        name: "General",
        open: false,
        buildProps: [
          "float",
          "display",
          "position",
          "top",
          "right",
          "left",
          "bottom",
        ],
      },
      {
        name: "Flex",
        open: false,
        buildProps: [
          "flex-direction",
          "flex-wrap",
          "justify-content",
          "align-items",
          "align-content",
          "order",
          "flex-basis",
          "flex-grow",
          "flex-shrink",
          "align-self",
        ],
      },
      {
        name: "Dimension",
        open: false,
        buildProps: [
          "width",
          "height",
          "max-width",
          "min-height",
          "margin",
          "padding",
        ],
      },
      {
        name: "Typography",
        open: false,
        buildProps: [
          "font-family",
          "font-size",
          "font-weight",
          "letter-spacing",
          "color",
          "line-height",
          "text-shadow",
        ],
      },
      {
        name: "Decorations",
        open: false,
        buildProps: [
          "border-radius-c",
          "background-color",
          "border-radius",
          "border",
          "box-shadow",
          "background",
        ],
      },
      {
        name: "Extra",
        open: false,
        buildProps: ["transition", "perspective", "transform"],
      },
    ],
  },

  blockManager: {
    appendTo: "#blocks",
    blocks: [
      {
        id: "section",
        label: "<b>Section</b>",
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
        select: true,
        content: { type: "image" },
        activate: true,
      },
    ],
  },
});

// Panels d'export et actions
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
      active: true,
      className: "btn-toggle-borders",
      label: "<u>B</u>",
      command: "sw-visibility",
    },
    {
      id: "export",
      className: "btn-open-export",
      label: "Exp",
      command: "export-template",
      context: "export-template",
    },
    {
      id: "show-json",
      className: "btn-show-json",
      label: "JSON",
      context: "show-json",
      command(editor) {
        editor.Modal.setTitle("Composants JSON")
          .setContent(
            `<textarea style="width:100%; height: 250px;">${JSON.stringify(
              editor.getComponents()
            )}</textarea>`
          )
          .open();
      },
    },
  ],
});

// Commandes personnalisées
editor.Commands.add("show-layers", {
  getRowEl(editor) {
    return editor.getContainer().closest(".editor-row");
  },
  getLayersEl(row) {
    return row.querySelector(".layers-container");
  },
  run(editor, sender) {
    this.getLayersEl(this.getRowEl(editor)).style.display = "";
  },
  stop(editor, sender) {
    this.getLayersEl(this.getRowEl(editor)).style.display = "none";
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
    this.getStyleEl(this.getRowEl(editor)).style.display = "";
  },
  stop(editor, sender) {
    this.getStyleEl(this.getRowEl(editor)).style.display = "none";
  },
});
editor.Commands.add("set-device-desktop", {
  run: (editor) => editor.setDevice("Desktop"),
});
editor.Commands.add("set-device-tablet", {
  run: (editor) => editor.setDevice("Tablet"),
});
editor.Commands.add("set-device-mobile", {
  run: (editor) => editor.setDevice("Mobile"),
});

editor.setDevice("Mobile");
