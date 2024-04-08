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