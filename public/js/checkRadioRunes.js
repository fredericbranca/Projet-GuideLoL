// Script pour checker les radio des runes pour le mode édition du Guide et pour ouvrir les arbres correspondant

var scriptCheckRadioDejaExecute = false;

function checkRadio() {
    if (scriptCheckRadioDejaExecute) return;
    scriptCheckRadioDejaExecute = true;

    infos_runes.forEach((groupe, indexGroupe) => {
        // Parcourir les runes "Primaire" et "Secondaire"
        ['Primaire', 'Secondaire'].forEach(type => {
            groupe[type]?.forEach(rune => {
                var id = `guide_groupeRunes_${indexGroupe}_${rune.runeArbre}_${rune.runeType}_${rune.id}`;
                var radioButton = document.getElementById(id);
                if (radioButton) {
                    radioButton.checked = true;
                }
            });

            // Cliquer sur l'image correspondante si des runes sont présentes
            if (groupe[type] && groupe[type].length > 0) {
                var imgToClick = document.querySelector(`div[id="guide_groupeRunes_${indexGroupe}"] img[data-name="${groupe[type][0].runeArbre}"]`);
                if (imgToClick) {
                    imgToClick.click();
                }
            }
        });

        // Parcourir les runes "Bonus"
        groupe['Bonus']?.forEach(bonus => {
            var id = `guide_groupeRunes_${indexGroupe}_bonusLine${bonus.bonus_line}_${bonus.id}`;
            var checkBox = document.getElementById(id);
            if (checkBox) {
                checkBox.checked = true;
            }
        });
    });
}

// Fonction pour observer les changements dans le DOM
function observerLesChangements() {
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (!scriptCheckRadioDejaExecute) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.classList && node.classList.contains('new-guide__block')) {
                        checkRadio();
                    }
                });
            }
        });
    });

    // Configuration de l'observateur
    var config = { childList: true, subtree: true };

    // Démarrer l'observation
    var container = document.querySelector('.new-guide-builder__runes-container');
    if (container) {
        observer.observe(container, config);
    }
}

// Démarrer l'observation lors du chargement de la page
window.addEventListener('DOMContentLoaded', observerLesChangements);