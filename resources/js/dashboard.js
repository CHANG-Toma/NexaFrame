/* GrapesJs */
import grapesjs from "grapesjs";
import "grapesjs/dist/css/grapes.min.css";
import gjsPresetWebpage from "grapesjs-preset-webpage";

const templates = {
  template1: () =>
    import("../../app/Views/front-office/templates/template1.json"),
  template2: () =>
    import("../../app/Views/front-office/templates/template2.json"),
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

  // bouton de sauvegarde personnalisé
  editor.Panels.addButton("options", [
    {
      id: "save-db",
      className: "fa fa-floppy-o", // classe d'icône grapesJs (PageBuilder)
      command: "save-db", // Commande à exécuter
      attributes: { title: "Save DB" },
    },
  ]);
  // bouton de chargement des templates
  editor.Panels.addButton("options", [
    {
      id: "load-project",
      className: "fa fa-download", // classe d'icône grapesJs (PageBuilder)
      command: "load-project",
      attributes: { title: "Load Project" },
    },
  ]);

  // bloc d'image personnalisé
  editor.Blocks.add("image", {
    label: "Image",
    attributes: { class: "fa fa-image" }, // classe d'icône grapesJs (PageBuilder)
    content: {
      type: "image",
      style: { color: "black" },
      activeOnRender: 1,
    },
    category: "Image",
  });
  // bloc de texte personnalisé
  editor.on("load", () => {
    const panelEl = editor.Panels.getPanel("views-container").el;
    panelEl.style.backgroundColor = "#fff";
  });

  editor.Blocks.add("register", {
    label: "Register",
    attributes: { class: "fa fa-user-plus" }, // classe d'icône grapesJs (PageBuilder)
    content: `
      <form method="post" action="/user/register" >
        <label for="login">Login:</label>
        <input type="text" id="login" name="login" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="confirmPassword">Password Confirmation:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required>
        <br>
        <button type="submit">Register</button>
      </form>
    `,
    category: "User",
  });

  editor.Blocks.add("login", {
    label: "Login",
    attributes: { class: "fa fa-sign-in" }, // classe d'icône grapesJs (PageBuilder)
    content: `
      <form method="post" action="/user/login" >
        <label for="login">Login:</label>
        <input type="text" id="login" name="login" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Login</button>
        <a href="/user/forgot-password">Forgot password ?</a>
        <a href="/user/register">Register</a>
      </form>
    `,
    category: "User",
  });

  editor.Commands.add("save-db", {
    run: function (editor, sender) {
      sender && sender.set("active", false); // Désactive le bouton après l'avoir cliqué

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

      // Ajoute un gestionnaire d'événement pour le bouton de sauvegarde
      document
        .getElementById("save-page-info")
        .addEventListener("click", function () {
          const formData = new FormData();

          if (localStorage.getItem("currentEditingId")) {
            const id = localStorage.getItem("currentEditingId");
            formData.append("id", id);
          }

          const url = document.getElementById("page-url").value;
          const title = document.getElementById("page-title").value;
          const meta_description =
            document.getElementById("page-description").value;
          const html = editor.getHtml();
          const css = editor.getCss();

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
              modal.close();
            })
            .catch((err) => {
              modal.close();
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

    localStorage.clear();

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
      localStorage.clear();
    });
  });

function loadTemplate(editor, templatePromise) {
  templatePromise().then((template) => {
    editor.setComponents(template.default.html);
    editor.setStyle(template.default.css);
    editor.Modal.close();
  });
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

    // Itére sur chaque ligne de la table
    for (let i = 1; i < rows.length; i++) {
      // Commence à 1 pour ignorer l'en-tête de la table
      let firstCellText = rows[i].cells[0].textContent.toLowerCase(); // Prendre le texte de la première cellule (Nom de la page)
      let isVisible = firstCellText.indexOf(searchText) > -1; // Vérifie si le texte de recherche est présent
      rows[i].style.display = isVisible ? "" : "none"; // Afficher ou cacher la ligne
    }
  }

  // Écoute les entrées dans le champ de recherche
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
