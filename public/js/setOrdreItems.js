// Script pour remettre l'ordre sur les items après soumission erroné + afficher dans la liste des items sélectionnées

var scriptSetOrdreItemsDejaExecute = false;

function setOrdreItems() {
    if (scriptSetOrdreItemsDejaExecute) return;
    scriptSetOrdreItemsDejaExecute = true;

    if (Object.keys(formData).length > 0) {
        if (formData.guide && formData.guide.groupeEnsemblesItems) {
            formData.guide.groupeEnsemblesItems.forEach((ensemble, ensembleIndex) => {
                ensemble.associationsEnsemblesItemsGroups.forEach((groupe, groupeIndex) => {
                    Object.entries(groupe.ordreItems)
                        .sort((a, b) => a[1] - b[1]) // Trie par ordreItem
                        .forEach(([valueItem, ordreItem]) => {
                            // Sélect l'ensemble et le groupe avec l'index
                            let ensembleElement = document.getElementById(`guide_groupeEnsemblesItems_${ensembleIndex}`);
                            let checkbox = ensembleElement.querySelector(`#guide_groupeEnsemblesItems_${ensembleIndex}_associationsEnsemblesItemsGroups_${groupeIndex}_choixItems_${valueItem}`);

                            checkbox.click();
                            checkbox.click();
                        });
                });
            });
        }
    } else {
        Object.entries(infos_items).forEach(([ensembleIndex, ensemble]) => {
            Object.entries(ensemble).forEach(([groupeIndex, groupe]) => {
                if (typeof groupe.ordreItems === 'object' && groupe.ordreItems !== null) {
                    Object.entries(groupe.ordreItems).sort((a, b) => a[1] - b[1]).forEach(([valueItem, ordreItem]) => {
                        let ensembleElement = document.getElementById(`guide_groupeEnsemblesItems_${ensembleIndex}`);
                        let checkbox = ensembleElement.querySelector(`#guide_groupeEnsemblesItems_${ensembleIndex}_associationsEnsemblesItemsGroups_${groupeIndex}_choixItems_${valueItem}`);

                        checkbox.click();
                        checkbox.click();
                    });
                }
            });
        });
    }
}
// Fonction pour observer les changements dans le DOM
function observerLesChangements() {
    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (!scriptSetOrdreItemsDejaExecute) {
                mutation.addedNodes.forEach(function (node) {
                    if (node.classList && node.classList.contains('new-guide__block')) {
                        setOrdreItems();
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

if (Object.keys(formData).length > 0 || infos_items.length > 0) {
    // Démarre l'observation lors du chargement de la page
    window.addEventListener('DOMContentLoaded', observerLesChangements);
}

