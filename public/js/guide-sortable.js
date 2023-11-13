function setupSortable() {
    const containers = document.querySelectorAll('.new-guide-builder__container');
    containers.forEach(container => {
        Sortable.create(container, {
            group: 'group-containers',
            onEnd: function (event) {
                updateGroupIds(container);
                updateItemsGroupIds(container);
            },
        });
    });
}

function setupSortableItem() {
    const listes = document.querySelectorAll('.sortable-list');
    listes.forEach(liste => {
        let container = liste.closest('.new-guide-builder__items-container');
        Sortable.create(liste, {
            group: 'group-lists',
            onEnd: function (event) {
                updateGroupIds(container);
                updateItemsGroupIds(container);
            },
        });
    });
}

// Fonction pour observer les changements dans le DOM
function observerLesChangements() {
    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            mutation.addedNodes.forEach(function (node) {
                if (node.classList && node.classList.contains('new-guide__block')) {
                    setupSortableItem();
                }
            });
        });
    });

    // Configuration de l'observateur
    var config = { childList: true, subtree: true };

    // Démarrer l'observation
    var container = document.querySelector('.new-guide-builder__items-container');
    if (container) {
        observer.observe(container, config);
    }
}

// Démarrer l'observation lors du chargement de la page
window.addEventListener('DOMContentLoaded', () => {
    setupSortable();
    setupSortableItem();
    observerLesChangements();
});