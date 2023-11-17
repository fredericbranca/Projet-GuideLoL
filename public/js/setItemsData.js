var scriptSetItemsDataDejaExecute = false;

function setDataGroupe() {
    if (scriptSetItemsDataDejaExecute) return;
    scriptSetItemsDataDejaExecute = true;

    ensemblesItemsContainer.querySelectorAll('.new-guide__block').forEach(ensemble => {
        ensemble.querySelectorAll('.groupe-item').forEach(groupe => {
            if (!groupe.hasAttribute('data-groupe')) {
                groupe.setAttribute('data-groupe', `groupeId-${uniqueGroupeId}`);
                uniqueGroupeId++; // Incrémenter l'ID unique pour le prochain groupe
            }
        });
    });
}

// Fonction pour observer les changements dans le DOM
function observerLesChangements() {
    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (!scriptSetItemsDataDejaExecute) {
                mutation.addedNodes.forEach(function (node) {
                    if (node.classList && node.classList.contains('new-guide__block')) {
                        setDataGroupe();
                    }
                });
            }
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

// Démarre l'observation lors du chargement de la page
window.addEventListener('DOMContentLoaded', observerLesChangements);