// Script pour checker les checkbox des compéténces pour le mode édition du Guide

var scriptDejaExecute = false;

function cocherCases() {
    if (scriptDejaExecute) return;
    scriptDejaExecute = true;

    // Parcourir chaque groupe de compétences
    infos_sorts.forEach((groupe, indexGroupe) => {
        groupe.forEach(competence => {
            // Construire l'identifiant de base pour les cases à cocher de cette compétence
            var baseId = `guide_groupesCompetences_${indexGroupe}_competence_${competence.id}`;

            // Parcourir chaque niveau et cocher la case correspondante
            competence.niveaux.forEach(niveau => {
                var checkboxId = `${baseId}_${niveau - 1}`; // -1 si vos niveaux commencent à 1 mais les ID de checkbox à 0
                var checkbox = document.getElementById(checkboxId);
                if (checkbox) {
                    checkbox.checked = true;
                    checkbox.setAttribute('checked', 'checked'); // Ajoute l'attribut checked au DOM
                }
            });
        });
    });
}

// Fonction pour observer les changements dans le DOM
function observerLesChangements() {
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (!scriptDejaExecute) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.classList && node.classList.contains('new-guide__block')) {
                        cocherCases();
                    }
                });
            }
        });
    });

    // Configuration de l'observateur
    var config = { childList: true, subtree: true };

    // Démarrer l'observation
    var container = document.querySelector('.new-guide-builder__competences-container');
    if (container) {
        observer.observe(container, config);
    }
}

// Démarrer l'observation lors du chargement de la page
window.addEventListener('DOMContentLoaded', observerLesChangements);