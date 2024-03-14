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

document.addEventListener("DOMContentLoaded", function () {
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

      // Ouvrir une modale pour demander les informations supplémentaires
      const modalContent = `
    <div style="margin-bottom: 15px;">
      <label for="page-url" style="display: block; margin-bottom: 5px;">URL de la page :</label>
      <input required type="text" id="page-url" name="url" style="width: 100%;"/>
    </div>
    <div style="margin-bottom: 15px;">
      <label for="page-title" style="display: block; margin-bottom: 5px;">Titre de la page :</label>
      <input required type="text" id="page-title" name="title" style="width: 100%;" />
    </div>
    <div style="margin-bottom: 15px;">
      <label for="page-description" style="display: block; margin-bottom: 5px;">Description de la page :</label>
      <textarea required id="page-description" name="meta_description" style="width: 100%; height: 100px;"></textarea>
    </div>
    <button id="save-page-info" class="Button-back-office main-btn">Sauvegarder la page</button>
  `;

      const modal = editor.Modal;
      modal.setTitle("Informations de la page");
      modal.setContent(modalContent);
      modal.open();

      // Ajouter un gestionnaire d'événement pour le bouton de sauvegarde
      document
        .getElementById("save-page-info")
        .addEventListener("click", function () {
          const url = document.getElementById("page-url").value;
          const title = document.getElementById("page-title").value;
          const meta_description =
            document.getElementById("page-description").value;
          const html = editor.getHtml();
          const css = editor.getCss();

          const formData = new FormData();
          formData.append("url", url);
          formData.append("title", title);
          formData.append("html", html);
          formData.append("css", css);
          formData.append("meta_description", meta_description);

          // Envoie des données au serveur
          fetch("/dashboard/page-builder/create-page/save", {
            method: "POST",
            body: formData,
          })
            .then((response) => response.json())
            .then((data) => {
              console.log("Sauvegarde réussie", data);
              modal.close(); // Fermer la modale après la sauvegarde
            })
            .catch((err) => {
              console.error("Erreur lors de la sauvegarde", err);
            });
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

  if (localStorage.getItem("currentEditingId")) {
    editor.setComponents(localStorage.getItem("currentEditingHtml"));
    editor.setStyle(localStorage.getItem("currentEditingCss"));
  }
});

document.querySelectorAll(".Button-sm.update").forEach((button) => {
  button.addEventListener("click", function () {
    const id = this.getAttribute("data-id");
    const html = this.getAttribute("data-html");
    const css = this.getAttribute("data-css");

    //localStorage est une variable qui permet de 
    // stocker des données dans le navigateur

    localStorage.setItem("currentEditingId", id);
    localStorage.setItem("currentEditingHtml", html);
    localStorage.setItem("currentEditingCss", css);

    window.location.href = "/dashboard/page-builder/create-page";
  });
});

document
  .querySelectorAll(".Button-back-office.btn-create-page")
  .forEach((button) => {
    button.addEventListener("click", function () {
      localStorage.removeItem("currentEditingId");
      localStorage.removeItem("currentEditingHtml");
      localStorage.removeItem("currentEditingCss");
    });
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
