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
    "menu-sorts-invocateur-container": ".new-guide-builder__sorts-invocateur-container",
    "menu-items-container": ".new-guide-builder__items-container",
    "menu-competences-container": ".new-guide-builder__competences-container",
    "menu-runes-container": ".new-guide-builder__runes-container"
};

// Mapping pour les URL à fetch en fonction de l'ID du span cliqué
const mappingFetchURLs = {
    "menu-sorts-invocateur-container": "/groupe-sorts-invocateur",
    "menu-items-container": "/groupe-items",
    "menu-competences-container": "/groupe-competences",
    "menu-runes-container": "/groupe-runes"
};

// Initialisation des variables pour suivre les indices
let indexSortsInvocateur = 0;
let indexItems = 0;
let indexCompetences = 0;
let indexRunes = 0;

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
 * Récupère l'id du champion sélectionné dans le formulaire de configuration.
 * @return {string|null} L'id du champion ou null si aucun n'est sélectionné.
 */
function getSelectedChampionId() {
    const selectedChampionRadio = document.querySelector('.new-guide-config__champion input[type="radio"]:checked');
    return selectedChampionRadio ? selectedChampionRadio.value : null;
}

/**
 * Fonction pour charger le contenu dans le conteneur vide
 * @param {string} spanId - L'id du span cliqué
 */
async function fetchContainer(spanId) {
    // Récupère l'id du champion sélectionné si nécessaire
    let championId = spanId === "menu-competences-container" ? getSelectedChampionId() : null;
    if (championId === null && spanId === "menu-competences-container") {
        console.error("Aucun champion sélectionné pour charger les compétences.");
        return;
    }

    let fetchURL = spanId === "menu-competences-container" ? `${mappingFetchURLs[spanId]}/${championId}` : mappingFetchURLs[spanId];
    console.log(mappingBuilderMenu[spanId]);
    // Vérification si le contenu a déjà été chargé
    let container = document.querySelector(`${mappingBuilderMenu[spanId]} .new-guide-builder__container`);
    console.log("🚀 ~ file: guide.js:71 ~ fetchContainer ~ container:", container)
    if (!container.querySelector('.new-guide__block')) {
        try {
            if (spanId === "menu-sorts-invocateur-container") {
                fetchURL += `/${indexSortsInvocateur}`;
                indexSortsInvocateur++;
            } else if (spanId === "menu-runes-container") {
                fetchURL += `/${indexRunes}`;
                indexRunes++;
            } else if (spanId === "menu-competences-container") {
                fetchURL += `/${indexCompetences}`;
                indexCompetences++;
            }
            
            let response = await fetch(fetchURL);
            if (!response.ok) {
                throw new Error('Erreur réseau lors de la tentative de récupération du contenu.');
            }
            let html = await response.text();
            container.insertAdjacentHTML('afterbegin', html);
        } catch (error) {
            console.error("Il y a eu un problème avec l'opération fetch: ", error.message);
        }
    }
}

/**
 * Fonction pour charger le contenu dans le conteneur grâce au bouton
 * @param {string} spanId - L'id du span cliqué
 */
async function fetchContainerWithBtn(spanId) {
    // Récupère l'id du champion sélectionné si nécessaire
    let championId = spanId === "menu-competences-container" ? getSelectedChampionId() : null;
    if (championId === null && spanId === "menu-competences-container") {
        console.error("Aucun champion sélectionné pour charger les compétences.");
        return;
    }

    let fetchURL = spanId === "menu-competences-container" ? `${mappingFetchURLs[spanId]}/${championId}` : mappingFetchURLs[spanId];

    // Vérification si le contenu a déjà été chargé
    let container = document.querySelector(`${mappingBuilderMenu[spanId]} .new-guide-builder__container`);
    if (container.querySelector('.new-guide__block')) {
        try {
            if (spanId === "menu-sorts-invocateur-container") {
                fetchURL += `/${indexSortsInvocateur}`;
                indexSortsInvocateur++;
            } else if (spanId === "menu-runes-container") {
                fetchURL += `/${indexRunes}`;
                indexRunes++;
            } else if (spanId === "menu-competences-container") {
                fetchURL += `/${indexCompetences}`;
                indexCompetences++;
            }

            let response = await fetch(fetchURL);
            if (!response.ok) {
                throw new Error('Erreur réseau lors de la tentative de récupération du contenu.');
            }
            let html = await response.text();
            container.insertAdjacentHTML('beforeend', html);
        } catch (error) {
            console.error("Il y a eu un problème avec l'opération fetch: ", error.message);
        }
    }
}

// Sélectionne tous les boutons radio de champion
const championRadios = document.querySelectorAll('.new-guide-config__champion input[type="radio"]');

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

// Ajout d'un écouteur d'évènement click sur le bouton d'ajout de groupe
const addGroupButtons = document.querySelectorAll('.add-group');
addGroupButtons.forEach(button => {
    button.addEventListener('click', function () {
        // Récupère la classe du parent pour déterminer quel type de groupe ajouter
        let parentClass = this.parentElement.classList[0];
        let type = parentClass.split('__')[1]; // extrait le type à partir de la classe

        // Appelle fetchContainer avec le type
        fetchContainerWithBtn(`menu-${type}`);
    });
});

// Gérer l'état des arbres cliqués par bloc
let clickedTreesByBlock = {};

// Initialiser les clics de l'arbre pour un bloc donné
function initializeClickedTreesForBlock(index) {
    if (!clickedTreesByBlock[index]) {
        clickedTreesByBlock[index] = {
            first: null,
            second: null
        };
    }
}

// Fonction pour gérer l'affichage des options des runes
function toggleRunesOptions(parentBlock, treeName, shouldShow) {
    let tree = parentBlock.querySelector(`.arbre[data-name="${treeName}"]`);
    let runesOptions = tree.nextElementSibling;
    runesOptions.style.display = shouldShow ? 'block' : 'none';
    if (!shouldShow) {
        resetRadioButtons(runesOptions);
    }
    return runesOptions;
}

// Fonction pour gérer la value de l'input hidden
function updateHiddenInput(parentBlock, treeName, value) {
    let hiddenInput = parentBlock.querySelector(`#${treeName}_typeArbre`);
    if (hiddenInput) {
        hiddenInput.value = value;
    }
}

const runesContainer = document.querySelector('.new-guide-builder__runes-container');
// Gérer le clic sur les arbres
runesContainer.addEventListener('click', function (event) {
    // Trouver l'élément parent .new-guide__block le plus proche du clic
    var parentBlock = event.target.closest('.new-guide__block');
    if (parentBlock) {
        var index = parentBlock.id.match(/\d+$/)[0];
        // Initialiser clickedTrees pour ce bloc
        initializeClickedTreesForBlock(index);
    } else {
        return;
    }

    // Référencer l'état clickedTrees spécifique à ce bloc
    let clickedTrees = clickedTreesByBlock[index];

    // Réactive les clics pour tous les arbres si aucun n'est actif
    if (!clickedTrees.first && !clickedTrees.second) {
        parentBlock.querySelectorAll('.arbre.disabled').forEach(tree => tree.classList.remove('disabled'));
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
            toggleRunesOptions(parentBlock, clickedTrees.second, false);
            toggleRunesOptions(parentBlock, clickedTrees.first, false);
            clickedTrees.first = null;
            clickedTrees.second = null;
            inputsHidden.forEach(input => input.value = '');
            return;
        }

        // Gérer le premier arbre
        if (!clickedTrees.first || treeName === clickedTrees.first) {
            let runesOptions = toggleRunesOptions(parentBlock, treeName, event.target.nextElementSibling.style.display !== 'block');
            clickedTrees.first = runesOptions.style.display === 'block' ? treeName : null;
            let firstDiv = runesOptions.querySelector('div');
            if (firstDiv) {
                firstDiv.style.display = 'block';
            }
            // Si l'arbre est ouvert, on met à jour l'input caché en "Primaire", sinon on le réinitialise
            updateHiddenInput(parentBlock, treeType, clickedTrees.first ? "Primaire" : "");
            clickedTrees.second = null;
        }
        // Gérer le deuxième arbre, différent du premier
        else if (treeName !== clickedTrees.first) {
            // Fermer toutes les options précédemment ouvertes
            if (clickedTrees.second) {
                const inputHidden = parentBlock.querySelector('.type-arbre-hidden[data-arbre-type="' + clickedTrees.second + '"]');
                inputHidden.value = '';
                toggleRunesOptions(parentBlock, clickedTrees.second, false);
            }

            // Ouvrir le deuxième arbre
            let runesOptions = toggleRunesOptions(parentBlock, treeName, true);
            if (runesOptions.style.display === 'block') {
                clickedTrees.second = treeName;
                let firstDiv = runesOptions.querySelector('div');
                if (firstDiv) {
                    firstDiv.style.display = 'none';
                }

                // Si le deuxième arbre est ouvert, on met à jour l'input caché en "Secondaire", sinon on le réinitialise
                updateHiddenInput(parentBlock, treeType, clickedTrees.second ? "Secondaire" : "");

                // Désactive les clics sur le deuxième arbre sélectionné
                parentBlock.querySelector('.arbre[data-name="' + clickedTrees.second + '"]').classList.add('disabled');

                // Réactive les clics sur tous les autres arbres
                parentBlock.querySelectorAll('.arbre:not([data-name="' + clickedTrees.second + '"])').forEach(tree => {
                    tree.classList.remove('disabled');
                });
            } else {
                clickedTrees.second = null;
                // Réactive les clics sur tous les arbres
                parentBlock.querySelectorAll('.arbre.disabled').forEach(tree => {
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