document.addEventListener('DOMContentLoaded', function() {
    // Initialisation de Sortable pour la zone de construction de la page
    var constructionArea = document.querySelector('.page-construction-area');
    new Sortable(constructionArea, {
        group: 'shared',
        animation: 150,
        filter: '.js-remove',
        onFilter: function (evt) {
            var item = evt.item,
                ctrl = evt.target;

            if (Sortable.utils.is(ctrl, ".js-remove")) {  // Clique sur l'icône de suppression
                item.parentNode.removeChild(item); // Supprime l'élément de la liste
            }
        },
        onEnd: function(evt) {
            // Ici, vous pouvez gérer l'événement de fin de glissement, par exemple pour sauvegarder l'état
        }
    });

    // Gestion du drag des composants vers la zone de construction
    var componentsPanel = document.querySelector('.components-panel');
    new Sortable(componentsPanel, {
        group: 'shared',
        animation: 150,
        sort: false // Empêche le tri dans la liste d'origine
    });
});
