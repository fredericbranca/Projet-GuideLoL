// -----------------------
// CONSTANTES DE MAPPING
// -----------------------

// Mapping entre les spans et les contenus nav de la sidebar
const mappingSidebarNav = {
    "nav-config": ".new-guide-config",
    "nav-builder": ".new-guide-builder"
};

// Mapping entre les spans et les contenus du menu builder
const mappingBuilderMenu = {
    "menu-sorts-invocateur": ".new-guide-builder__sorts-invocateur-container",
    "menu-items": ".new-guide-builder__items-container",
    "menu-competences": ".new-guide-builder__competences-container",
    "menu-runes": ".new-guide-builder__runes-container"
};

// Mapping pour les URL à fetch en fonction de l'ID du span cliqué
const mappingFetchURLs = {
    "menu-sorts-invocateur": "/groupe-sorts-invocateur",
    "menu-items": "/groupe-items",
    "menu-competences": "/groupe-competences",
    "menu-runes": "/groupe-runes"
};

// -----------------------------
// SÉLECTION DES ÉLÉMENTS DOM
// -----------------------------

// Sélectionne les conteneurs des spans
let sidebar = document.querySelector(".new-guide-sidebar__nav");
let menu = document.querySelector(".new-guide-builder__menu");

// -----------------------------
// FONCTIONS UTILITAIRES
// -----------------------------

/**
 * Fonction pour charger le contenu dans le conteneur
 * @param {string} spanId - L'ID du span cliqué
 */
async function fetchContainer(spanId) {
    // Vérification si le contenu a déjà été chargé
    let container = document.querySelector(mappingBuilderMenu[spanId]);
    if (!container.querySelector('.new-guide__block')) {
        try {
            let response = await fetch(mappingFetchURLs[spanId]);
            if (!response.ok) {
                throw new Error('Erreur réseau lors de la tentative de récupération du contenu.');
            }
            let html = await response.text();
            container.innerHTML = html;
        } catch (error) {
            console.error("Il y a eu un problème avec l'opération fetch: ", error.message);
        }
    }
}

// Fonction pour reset les boutons radio d'un élément
function resetRadioButtons(element) {
    let radios = element.querySelectorAll('input[type="radio"]');
    radios.forEach(radio => radio.checked = false);
}

// Réinitialisation des arbres sélectionnés si l'utilisateur souhaite recommencer
function resetAllTrees() {
    if (clickedTrees.first) {
        toggleRunesOptions(clickedTrees.first, false);
    }
    if (clickedTrees.second) {
        toggleRunesOptions(clickedTrees.second, false);
    }
    clickedTrees.first = null;
    clickedTrees.second = null;
    document.querySelectorAll('.arbre.disabled').forEach(tree => tree.classList.remove('disabled'));
}

// -----------------------
// ÉCOUTEURS D'ÉVÉNEMENTS
// -----------------------

// Ajout d'un écouteur d'événement au click sur la sidebar
sidebar.addEventListener("click", function (event) {
    if (event.target.tagName === "SPAN") {
        // Supprime .current de tous les enfants
        for (let child of this.children) {
            child.classList.remove("current");
        }
        // Ajoute .current à l'élément cliqué
        event.target.classList.add("current");

        // Cache tous les contenus
        for (let key in mappingSidebarNav) {
            document.querySelector(mappingSidebarNav[key]).style.display = "none";
        }
        // Affiche le contenu correspondant à l'élément cliqué
        let id = event.target.id;
        if (mappingSidebarNav[id]) {
            document.querySelector(mappingSidebarNav[id]).style.display = "block";
        }

        // Fetch le container du menu .current
        let currentMenuSpan = menu.querySelector('span.current');
        if (currentMenuSpan) {
            fetchContainer(currentMenuSpan.id);
        }
    }
});

// Ajout d'un écouteur d'événement click sur le menu du builder
menu.addEventListener("click", function (event) {
    if (event.target.tagName === "SPAN") {
        // Supprime .current de tous les enfants
        for (let child of this.children) {
            child.classList.remove("current");
        }
        // Ajoute .current à l'élément cliqué
        event.target.classList.add("current");

        // Cache tous les contenus
        for (let key in mappingBuilderMenu) {
            document.querySelector(mappingBuilderMenu[key]).style.display = "none";
        }
        // Affiche le contenu correspondant à l'élément cliqué
        let id = event.target.id;
        if (mappingBuilderMenu[id]) {
            document.querySelector(mappingBuilderMenu[id]).style.display = "block";
            fetchContainer(id);
        }
    }
});


// Gérer l'état des arbres cliqués
let clickedTrees = {
    first: null,
    second: null
};

// Fonction pour gérer l'affichage des options des runes
function toggleRunesOptions(treeName, shouldShow) {
    let tree = document.querySelector(`.arbre[data-name="${treeName}"]`);
    let runesOptions = tree.nextElementSibling;
    runesOptions.style.display = shouldShow ? 'block' : 'none';
    if (!shouldShow) {
        resetRadioButtons(runesOptions);
    }
    return runesOptions;
}

// Fonction pour gérer la value de l'input hidden
function updateHiddenInput(treeName, value) {
    let hiddenInput = document.querySelector(`#${treeName}_typeArbre`);
    if (hiddenInput) {
        hiddenInput.value = value;
    }
}

// Gérer le clic sur les arbres
document.addEventListener('click', function (event) {
    // Réactive les clics pour tous les arbres si aucun n'est actif
    if (!clickedTrees.first && !clickedTrees.second) {
        document.querySelectorAll('.arbre.disabled').forEach(tree => tree.classList.remove('disabled'));
        clickedTrees.first = null;
        clickedTrees.second = null;
    }

    // Vérifier si l'élément cliqué ou un de ses parents à .disabled
    if (event.target.closest('.disabled')) {
        // Ignore l'événement de clic si l'élément est désactivé
        return;
    }

    if (event.target.classList.contains('arbre')) {
        const treeName = event.target.dataset.name;
        const treeType = event.target.parentElement.id;
        const inputsHidden = this.querySelectorAll('.type-arbre-hidden');

        // Ferme le deuxième arbre si on reclick sur le premier
        if (treeName === clickedTrees.first && clickedTrees.second) {
            toggleRunesOptions(clickedTrees.second, false);
            toggleRunesOptions(clickedTrees.first, false);
            clickedTrees.first = null;
            clickedTrees.second = null;
            inputsHidden.forEach(input => input.value = '');
            return;
        }

        // Gérer le premier arbre
        if (!clickedTrees.first || treeName === clickedTrees.first) {
            let runesOptions = toggleRunesOptions(treeName, event.target.nextElementSibling.style.display !== 'block');
            clickedTrees.first = runesOptions.style.display === 'block' ? treeName : null;
            let firstDiv = runesOptions.querySelector('div');
            if (firstDiv) {
                firstDiv.style.display = 'block';
            }
            // Si l'arbre est ouvert, on met à jour l'input caché en "Primaire", sinon on le réinitialise
            updateHiddenInput(treeType, clickedTrees.first ? "Primaire" : "");
            clickedTrees.second = null;
        }
        // Gérer le deuxième arbre, différent du premier
        else if (treeName !== clickedTrees.first) {
            // Fermer toutes les options précédemment ouvertes
            if (clickedTrees.second) {
                const inputHidden = document.querySelector('.type-arbre-hidden[data-arbre-type="' + clickedTrees.second + '"]');
                inputHidden.value = '';
                toggleRunesOptions(clickedTrees.second, false);
            }

            // Ouvrir le deuxième arbre
            let runesOptions = toggleRunesOptions(treeName, true);
            if (runesOptions.style.display === 'block') {
                clickedTrees.second = treeName;
                let firstDiv = runesOptions.querySelector('div');
                if (firstDiv) {
                    firstDiv.style.display = 'none';
                }

                // Si le deuxième arbre est ouvert, on met à jour l'input caché en "Secondaire", sinon on le réinitialise
                updateHiddenInput(treeType, clickedTrees.second ? "Secondaire" : "");

                // Désactive les clics sur le deuxième arbre sélectionné
                document.querySelector('.arbre[data-name="' + clickedTrees.second + '"]').classList.add('disabled');

                // Réactive les clics sur tous les autres arbres
                document.querySelectorAll('.arbre:not([data-name="' + clickedTrees.second + '"])').forEach(tree => {
                    tree.classList.remove('disabled');
                });
            } else {
                clickedTrees.second = null;
                // Réactive les clics sur tous les arbres
                document.querySelectorAll('.arbre.disabled').forEach(tree => {
                    tree.classList.remove('disabled');
                });
            }
        }
    }
});

// Ajout d'un écouteur d'événement sur le span Publier pour qu'il active le click sur le bouton qui valide le formulaire
document.addEventListener("DOMContentLoaded", function () {
    let actionPublierButton = document.getElementById('action-publier');
    let guideValiderButton = document.getElementById('guide_Valider');

    if (actionPublierButton && guideValiderButton) {
        actionPublierButton.addEventListener('click', function () {
            guideValiderButton.click();
        });
    }
});