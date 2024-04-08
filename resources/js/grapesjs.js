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

  // bloc de texte personnalisé
  editor.on("load", () => {
    const panelEl = editor.Panels.getPanel("views-container").el;
    panelEl.style.backgroundColor = "#fff";
  });

  editor.Blocks.add("register", {
    label: "S'inscrire",
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
    category: "Utilisateur",
  });

  editor.Blocks.add("login", {
    label: "Se connecter",
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
    category: "Utilisateur",
  });

  editor.Blocks.add("forgotpwd", {
    label: "Mot de passe oublié",
    attributes: { class: "fa fa-key" }, // classe d'icône grapesJs (PageBuilder)
    content: `
      <form method="post" action="/user/forgot-password" >
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <button type="submit">Send email</button>
      </form>
    `,
    category: "Utilisateur",
  });

  editor.Blocks.add("resetpwd", {
    label: "Reset Password",
    attributes: { class: "fa fa-key" }, // classe d'icône grapesJs (PageBuilder)
    content: `
      <form method="post" action="/user/reset-password" >
        <label for="currentPassword">Mot de passe actuel :</label>
        <input type="password" id="currentPassword" name="currentPassword" required>
        <br>
        <label for="newPassword">Nouveau mot de passe:</label>
        <input type="password" id="newPassword" name="newPassword" required>
        <br>
        <label for="confirmPassword">confirmer votre mot de passe :</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required>
        <br>
        <button type="submit">Réinitialiser</button>
      </form>
    `,
    category: "Utilisateur",
  });
  // bloc d'image personnalisé
  editor.Blocks.add("image", {
    label: "Image",
    attributes: { class: "fa fa-image" }, // classe d'icône grapesJs (PageBuilder)
    content: {
      type: "image",
      style: { color: "black" },
      activeOnRender: 1,
    },
    category: "Autres",
  });

  editor.Blocks.add("articlesBlock", {
    label: "Articles",
    attributes: { class: "fa fa-newspaper-o" },
    content: {
      type: "articles",
      tagName: "div",
      style: { padding: "10px" },
      script: function () {
        fetch("/dashboard/article/block")
          .then((response) => response.json())
          .then((articles) => {
            if (articles.length > 0) {
              this.innerHTML = articles
                .map(
                  (article) => `
                  <div class="article">
                    <h1>${article.title}</h1>
                    <p>Publié le : ${article.published_at}</p>
                    <p>Catégorie : ${article.category}</p>
                    ${article.image ? `<img src="${article.image}" style="width: 100%; height: auto;">` : ''}
                    <h4>Contenue : ${article.content}</h4>
                    <text>${article.keywords}</text><br>
                    <form action="/dashboard/article/save-comment" method="POST">
                      <input type="hidden" name="articleId" value="${article.id}">
                      <input name="commentContent" placeholder="Votre commentaire"></input>
                      <br>
                      <button type="submit">Commenter</button>
                    </form>
                    <div class="comments">
                      ${article.comments && article.comments.length > 0 ? article.comments.map(
                        (comment) => `
                        <div class="comment">
                          <p>${comment.content}</p>
                          <p>Publié le : ${comment.created_at}</p>
                        </div>
                      `).join("") : "Aucun commentaire pour cet article"}
                    </div>
                  </div>`
                ).join("");
            }
          });
      },
    },
    category: "Autres",
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