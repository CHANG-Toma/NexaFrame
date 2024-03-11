import grapesjs from "grapesjs";
import "grapesjs/dist/css/grapes.min.css";
import gjsPresetWebpage from "grapesjs-preset-webpage";

const editor = grapesjs.init({
  container: "#gjs",
  fromElement: true,
  height: "920px",
  width: "auto",
  storageManager: { autoload: 0 },
  plugins: [gjsPresetWebpage],
  pluginsOpts: {
    [gjsPresetWebpage]: {},
  },
});

// Ajouter un bouton de sauvegarde au panneau
editor.Panels.addButton("options", [
  {
    id: "save-db",
    className: "fa fa-floppy-o", // classe d'icône grapesJs (PageBuilder)
    command: "save-db", // Commande à exécuter
    attributes: { title: "Save DB" },
  },
]);

// Ajouter un bloc d'image personnalisé
editor.Blocks.add("image", {
  label: "Image",
  attributes: { class: "fa fa-image" }, // classe d'icône grapesJs
  content: {
    type: "image",
    style: { color: "black" },
    activeOnRender: 1,
  },
  category: "Image",
});

// Définir la commande à exécuter lorsque le bouton de sauvegarde est cliqué
editor.Commands.add("save-db", {
  run: function (editor, sender) {
    sender && sender.set("active", false); // Désactiver le bouton après l'avoir cliqué
    // Code pour collecter les données de l'éditeur
    const html = editor.getHtml();
    const css = editor.getCss();
    // Créer un FormData avec les données de l'éditeur
    const formData = new FormData();
    formData.append("html", html);
    formData.append("css", css);

    // Envoyer les données au serveur via fetch API ou XMLHttpRequest
    fetch("chemin/vers/votre/controller.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        console.log("Sauvegarde réussie", data);
      })
      .catch((err) => {
        console.error("Erreur lors de la sauvegarde", err);
      });
  },
});

editor.on('load', () => {
  const panelEl = editor.Panels.getPanel('views-container').el;
  panelEl.style.backgroundColor = '#fff';
});
