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
    "menu-runes": ".new-guide-builder__runes-container",
    "menu-counters": ".new-guide-builder__counters-container",
    "menu-synergies": ".new-guide-builder__synergies-container"
};

// Mapping pour les URL à fetch en fonction de l'ID du span cliqué
const mappingFetchURLs = {
    "menu-sorts-invocateur": "/groupe-sorts-invocateur",
    "menu-items": "/groupe-items",
    "menu-competences": "/groupe-competences",
    "menu-runes": "/groupe-runes",
    "menu-counters": "/groupe-counters",
    "menu-synergies": "/groupe-synergies"
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
    for (let radio of radios) {
        radio.checked = false;
    }
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


// Ajout d'un écouteur d'événement click sur les arbres des runes
let firstClickedTree = null;
let secondClickedTree = null;

document.addEventListener('click', function (event) {
    if (event.target.classList.contains('arbre')) {
        let runesOptions = event.target.nextElementSibling;

        // Si on click sur le premier arbre alors qu'un 2ème est ouvert
        if (event.target === firstClickedTree && secondClickedTree) {
            secondClickedTree.nextElementSibling.style.display = 'none'; // Ferme le deuxième arbre ouvert
            resetRadioButtons(secondClickedTree.nextElementSibling);
            let firstDivSecondTree = secondClickedTree.nextElementSibling.querySelector('div');
            if (firstDivSecondTree) {
                firstDivSecondTree.style.display = 'block'; // Réinitialise le deuxième arbre
            }
            runesOptions.style.display = 'none'; // Ferme le premier arbre
            resetRadioButtons(runesOptions);
            let firstDivFirstTree = runesOptions.querySelector('div');
            if (firstDivFirstTree) {
                firstDivFirstTree.style.display = 'block'; // Réinitialise le premier arbre
            }
            firstClickedTree = null;
            secondClickedTree = null;
            return; // Sort de l'écouteur d'événements
        }

        // Si aucun arbre n'a été cliké ou si on reclick sur le premier arbre
        if (!firstClickedTree || firstClickedTree === event.target) {
            if (runesOptions.style.display === 'block') {
                resetRadioButtons(runesOptions);
                runesOptions.style.display = 'none';
                let firstDiv = runesOptions.querySelector('div');
                if (firstDiv) {
                    firstDiv.style.display = 'block';
                }
                firstClickedTree = null;
            } else {
                runesOptions.style.display = 'block';
                firstClickedTree = event.target;
            }
            secondClickedTree = null;
        }
        // Si on click sur un deuxième arbre différent du premier et que le premier a déjà été défini
        else if (firstClickedTree && !secondClickedTree && event.target !== firstClickedTree) {
            runesOptions.style.display = 'block';
            let firstDiv = runesOptions.querySelector('div');
            if (firstDiv) {
                firstDiv.style.display = 'none';
            }

            secondClickedTree = event.target;

            // Désactive les événements de clic pour tous les autres arbres
            let trees = document.querySelectorAll('.arbre');
            for (let tree of trees) {
                if (tree !== firstClickedTree && tree !== secondClickedTree) {
                    tree.removeEventListener('click', arguments.callee);
                }
            }
        }
        // Si on reclick sur le deuxième arbre 
        else if (secondClickedTree === event.target) {
            resetRadioButtons(runesOptions);
            runesOptions.style.display = 'none';
            let firstDiv = runesOptions.querySelector('div');
            if (firstDiv) {
                firstDiv.style.display = 'block';
            }
            secondClickedTree = null;
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