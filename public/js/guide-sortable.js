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

function setupSortableItem() {
    const listes = document.querySelectorAll('.sortable-list');
    let container = document.querySelector('.new-guide-builder__items-container');
    listes.forEach(liste => {
        Sortable.create(liste, {
            group: 'group-lists',
            handle: '.handle',
            animation: 200,
            onEnd: function () {
                updateGroupIds(container);
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