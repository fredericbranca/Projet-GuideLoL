function setupSortable() {
    const containers = document.querySelectorAll('.new-guide-builder__container');
    containers.forEach(container => {
        Sortable.create(container, {
            group: 'group-containers',
            handle: '.handle',
            animation: 200,
            onEnd: function () {
                updateGroupIds(container);
            },
        });
    });
}

// SortableJS (Drag-And-Drop): Fonction pour rendre manipulable les groupes d'items
function setupSortableItem() {
    // Sélectionne tous les éléments avec la classe 'sortable-list'
    const listes = document.querySelectorAll('.sortable-list');

    // Sélectionne le conteneur de la section Items
    let container = document.querySelector('.new-guide-builder__items-container');

    // Parcourt chaque liste 'sortable-list'
    listes.forEach(liste => {
        // Crée une instance de Sortable pour chaque liste (groupe d'items)
        Sortable.create(liste, {
            group: 'group-lists', // Définit le groupe pour le tri (les éléments peuvent être déplacés entre les listes du même groupe)
            handle: '.handle', // Spécifie quels éléments sont ciblable s
            animation: 200, // Durée de l'animation
            onEnd: function () { // Fonction qui s'exécute après qu'un élément a été déplacé
                updateGroupIds(container); // Met à jour les identifiants et la value de l'ordre des groupes
            },
        });
    });
}

function setupSortableOrdreItems() {
    let container = document.querySelector('.new-guide-builder__items-container');
    const itemsList = container.querySelectorAll('#selectedItem');
    itemsList.forEach(item => {
        Sortable.create(item, {
            group: {
                name: 'group-items',
                put: false
            },
            handle: '.handleItem',
            animation: 200,
            onEnd: function () {
                const groupe = item.closest('.groupe-item');
                let inputOrdre = item.querySelectorAll('.copie-item');
                inputOrdre.forEach((el, index) => {
                    let id = el.dataset.ordre;

                    if (groupe.querySelector(`#${id}`)) {
                        groupe.querySelector(`#${id}`).value = index;
                    }
                });
            },
        });
    });
}

// Fonction pour observer les changements dans le DOM
function observerLesChangements() {
    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            mutation.addedNodes.forEach(function (node) {
                if (node.classList && (node.classList.contains('new-guide__block') || node.classList.contains('groupe-item'))) {
                    setupSortableItem();
                    setupSortableOrdreItems();
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
    setupSortableOrdreItems();
});