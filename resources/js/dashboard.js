/* GrapesJs */
import grapesjs from "grapesjs";
import "grapesjs/dist/css/grapes.min.css";
import gjsPresetWebpage from "grapesjs-preset-webpage";

import template1 from "../../app/Views/front-office/templates/template1.json";
import template2 from "../../app/Views/front-office/templates/template2.json";

const templates = {
  template1: template1,
  template2: template2,
};

const editor = grapesjs.init({
  container: "#gjs",
  fromElement: true,
  height: "939px",
  width: "auto",
  storageManager: { autoload: 0 },
  plugins: [gjsPresetWebpage],
  pluginsOpts: {
    [gjsPresetWebpage]: {},
  },
});

editor.Panels.addButton("options", [
  {
    id: "save-db",
    className: "fa fa-floppy-o", // classe d'icône grapesJs (PageBuilder)
    command: "save-db", // Commande à exécuter
    attributes: { title: "Save DB" },
  },
]);
editor.Panels.addButton("options", [
  {
    id: "load-project",
    className: "fa fa-download",
    command: "load-project",
    attributes: { title: "Load Project" },
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
editor.on("load", () => {
  const panelEl = editor.Panels.getPanel("views-container").el;
  panelEl.style.backgroundColor = "#fff";
});

editor.Commands.add("save-db", {
  run: function (editor, sender) {
    sender && sender.set("active", false); // Désactiver le bouton après l'avoir cliqué
    
    const url = "/leuia"; // TODO: Get this value from your UI    
    const title = "Leuia page"; // TODO: Get this value from your UI
    const html = editor.getHtml();
    const css = editor.getCss();
    const meta_description = "euurre mahore"; // TODO: Get this value from your UI

    // Create a FormData with the editor data
    const formData = new FormData();
    formData.append("url", url);
    formData.append("title", title);
    formData.append("html", html);
    formData.append("css", css);
    formData.append("meta_description", meta_description);

    fetch("/dashboard/page-builder/create-page/save", {
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

editor.Commands.add("load-project", {
  run: function (editor) {
    editor.runCommand("open-templates");
  },
});
editor.Commands.add("open-templates", {
  run: function (editor) {
    const modal = editor.Modal;
    const container = document.createElement("div");

    for (const templateName in templates) {
      const template = templates[templateName];
      const btn = document.createElement("button");
      btn.innerHTML = templateName;
      btn.addEventListener("click", () => loadTemplate(editor, template));
      container.appendChild(btn);
    }

    modal.setTitle("Select a Template");
    modal.setContent(container);
    modal.open();
  },
});

function loadTemplate(editor, template) {
  editor.setComponents(template.html);
  editor.setStyle(template.css);
  editor.Modal.close();
}

/* Fonction Js */

// input de recherche des pages
document.addEventListener("DOMContentLoaded", function () {
  let searchInput = document.querySelector("#searchInput");
  let table = document.querySelector("#pageTable");

  // Fonction pour filtrer les lignes de la table
  function filterRows() {
    let searchText = searchInput.value.toLowerCase();
    let rows = table.getElementsByTagName("tr");

    // Itérer sur chaque ligne de la table
    for (let i = 1; i < rows.length; i++) {
      // Commence à 1 pour ignorer l'en-tête de la table
      let firstCellText = rows[i].cells[0].textContent.toLowerCase(); // Prendre le texte de la première cellule (Nom de la page)
      let isVisible = firstCellText.indexOf(searchText) > -1; // Vérifier si le texte de recherche est présent
      rows[i].style.display = isVisible ? "" : "none"; // Afficher ou cacher la ligne
    }
  }

  // Écouter les entrées dans le champ de recherche
  searchInput.addEventListener("keyup", filterRows);
});

/* Sidebar */
document.addEventListener("DOMContentLoaded", function () {
  var sidebarToggle = document.querySelector(".sidebar_toggle");
  var body = document.querySelector("body");

  sidebarToggle.addEventListener("click", function () {
    document.querySelector(".l-sidebar").classList.toggle("active");
    body.classList.toggle("sidebar-active");
  });
});
