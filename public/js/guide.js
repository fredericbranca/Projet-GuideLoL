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
    "menu-items-container": "/ensemble-items",
    "menu-competences-container": "/groupe-competences",
    "menu-runes-container": "/groupe-runes"
};

// Initialisation des variables pour suivre les indices
const blockCount = (className) => {
    let container = document.querySelector(className + ' .new-guide-builder__container');
    let blocks = container.querySelectorAll('.new-guide__block');
    return blocks.length;
};

let indexSortsInvocateur = blockCount(".new-guide-builder__sorts-invocateur-container");
let indexEnsembleItems = blockCount(".new-guide-builder__items-container");
let indexCompetences = blockCount(".new-guide-builder__competences-container");
let indexRunes = blockCount(".new-guide-builder__runes-container");

// Initialiser le statut des fetch à l'update
var fetchUpdate = {};
for (var key in mappingFetchURLs) {
    if (mappingFetchURLs.hasOwnProperty(key)) {
        fetchUpdate[key] = { fetchUpdate: false };
    }
}


var savedForm = [];
// Sauvegarde et update les forms pour les prochains chargement
function saveForm(id, form) {
    if (!savedForm[id]) {
        savedForm[id] = {
            html: form
        };
    }
}

// Initiatlisation de l'idGuide si la page est en mode édition
var pathname = window.location.pathname;

// Expression régulière : /guide/{int}/edit
var regex = /^\/guide\/(\d+)(\/edit)$/;

// Testez si le pathname correspond
if (regex.test(pathname)) {
    // id du guide à partir de l'URL
    var guideId = pathname.match(regex)[1];
}

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
function getSelectedChampionId(newChamp = null) {
    const selectedChampionRadio = document.querySelector('.new-guide-config__champion input[type="radio"]:checked');
    const containerCompetence = document.querySelectorAll('.new-guide-builder__competences-container .new-guide__block');

    if (newChamp && containerCompetence.length > 0) {
        containerCompetence.forEach(element => {
            element.remove();
        });
        if (savedForm['new-guide-builder__competences-container']) {
            delete savedForm['new-guide-builder__competences-container'];
        }
    }

    return selectedChampionRadio ? selectedChampionRadio.value : null;
}

/**
 * Fonction pour charger les conteneurs avec les données d'éditions
 * @param {string} spanId - L'id du span cliqué
 * @param {string} championId - L'id du champion (si nécessaire)
 */
async function fetchUpdatedContainer(spanId, championId) {

    let fetchURL = spanId === "menu-competences-container" ? `${mappingFetchURLs[spanId]}/edit/${championId}/${guideId}` : `${mappingFetchURLs[spanId]}/edit/${guideId}`;
    let container = document.querySelector(`${mappingBuilderMenu[spanId]} .new-guide-builder__container`);
    try {
        let response = await fetch(fetchURL);
        if (!response.ok) {
            console.error(`Échec du chargement depuis l'URL : ${fetchURL}, statut : ${response.status}`);
            return;
        }
        let html = await response.text();
        container.insertAdjacentHTML('afterbegin', html);
    } catch (error) {
        console.error("Il y a eu un problème avec l'opération fetch: ", error.message);
    }
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

    let fetchURL = spanId === "menu-competences-container" ? `${mappingFetchURLs[spanId]}/create/${championId}` : `${mappingFetchURLs[spanId]}/create`;

    var hasTrue = Object.values(errors).some(value => value === true);

    if (guideId && fetchUpdate[spanId].fetchUpdate === false && Object.keys(formData).length < 1 && !hasTrue) {
        await fetchUpdatedContainer(spanId, championId);
        fetchUpdate[spanId].fetchUpdate = true;
    }

    // Vérification si le contenu a déjà été chargé
    let container = document.querySelector(`${mappingBuilderMenu[spanId]} .new-guide-builder__container`);
    let ensemble = container.parentNode;

    if (!container.querySelector('.new-guide__block')) {
        try {
            let html;
            let continu = false;

            if (savedForm[ensemble.className]) {
                html = savedForm[ensemble.className].html;
            } else {
                let response;
                if (formData && Object.keys(formData).length > 0) {
                    response = await fetch(fetchURL, {
                        method: 'POST',
                        body: JSON.stringify(formData),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });
                    continu = false;
                } else {
                    response = await fetch(fetchURL);
                    continu = true;
                }

                if (!response.ok) {
                    throw new Error('Erreur réseau lors de la tentative de récupération du contenu.');
                }
                html = await response.text();
                if (continu == true) {
                    saveForm(ensemble.className, html)
                }
            }

            container.insertAdjacentHTML('afterbegin', html);
            updateGroupIds(ensemble);

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

    let fetchURL = spanId === "menu-competences-container" ? `${mappingFetchURLs[spanId]}/create/${championId}` : `${mappingFetchURLs[spanId]}/create`;

    // Vérification si le contenu a déjà été chargé
    let container = document.querySelector(`${mappingBuilderMenu[spanId]} .new-guide-builder__container`);
    let ensemble = container.parentNode;

    if (container) {
        try {
            let html;

            if (savedForm[ensemble.className]) {
                html = savedForm[ensemble.className].html;
            } else {
                let response = await fetch(fetchURL);
                if (!response.ok) {
                    throw new Error('Erreur réseau lors de la tentative de récupération du contenu.');
                }
                html = await response.text();
                saveForm(ensemble.className, html)
            }

            container.insertAdjacentHTML('beforeend', html);

            updateGroupIds(ensemble);

        } catch (error) {
            console.error("Il y a eu un problème avec l'opération fetch: ", error.message);
        }
    }
}

/**
 * Fonction pour charger le groupe d'items dans le bon ensemble
 * @param {string} setContainer - L'id du container
 */
async function fetchGroupeItems(setContainer) {
    // Vérification si le container existe
    let container = document.querySelector(`#${setContainer}`);
    let insertIn = container.querySelector('.ensemble .sortable-list');
    let ensemble = document.querySelector('.new-guide-builder__items-container');


    if (insertIn) {
        try {
            let html;

            if (savedForm[insertIn.className]) {
                html = savedForm[insertIn.className].html;
            } else {
                let fetchURL = "/groupe-items/create";

                let response = await fetch(fetchURL);
                if (!response.ok) {
                    throw new Error('Erreur réseau lors de la tentative de récupération du contenu.');
                }
                html = await response.text();
                saveForm(insertIn.className, html)
            }

            insertIn.insertAdjacentHTML('beforeend', html);

            updateGroupIds(ensemble);

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

// Gestionnaire d'événements globaux pour la suppression de groupes et ensemble
let blockBuilder = document.querySelector('.new-guide-builder');
blockBuilder.addEventListener('click', function (event) {
    // Supprime l'élément parent du bouton cliqué
    if (event.target.classList.contains('supprimer-groupe')) {
        // Trouve le parent le plus proche qui commence par '.new-guide-builder'
        let ensemble = event.target.closest('[class^="new-guide-builder"]');

        // Si l'ensemble trouvé est '.new-guide-builder__container', remonte d'un niveau
        if (ensemble && ensemble.classList.contains('new-guide-builder__container')) {
            ensemble = ensemble.parentNode;
        }

        // Vérifie que l'ensemble existe
        if (ensemble) {
            event.target.parentNode.remove();
            updateGroupIds(ensemble);
        }
    }
    if (event.target.classList.contains('supprimer-itemsGroupe')) {
        // Trouve le parent le plus proche qui commence par '.new-guide-builder'
        let ensemble = event.target.closest('[class^="sortable-list"]');

        event.target.parentNode.remove();
        updateItemsGroupIds(ensemble);
    }


    // Vérifie si l'élément cliqué a la classe .titre-groupe ou .titre-groupe-item
    if (event.target.classList.contains('baniere-groupe') || event.target.classList.contains('baniere-groupe-items')) {
        var nextDiv = event.target.nextElementSibling;

        if (nextDiv && (nextDiv.classList.contains('groupe-content') || nextDiv.classList.contains('groupe-content-liste-items'))) {
            if (nextDiv.classList.contains('open')) {
                nextDiv.classList.remove('open');

                // Temps avant de display none
                setTimeout(function () {
                    nextDiv.style.display = 'none';
                }, 200);
            } else {
                nextDiv.style.display = 'flex';
                setTimeout(function () {
                    nextDiv.classList.add('open');
                }, 10);
            }
        }
    }

});

const updateGroupIds = (ensembleSelector) => {
    let groupes = ensembleSelector.querySelectorAll('.new-guide__block');

    groupes.forEach((group, index) => {
        // Mettre à jour l'ID du groupe
        group.id = group.id.replace(/\d+$/, index.toString());

        // Mettre à jour les IDs et noms des éléments enfants
        let childElements = group.querySelectorAll('[id^="guide_groupe"], [for^="guide_groupe"], [name^="guide[groupe"], [for^="guide[groupe"], [data-id^="guide_groupe"], [data-ordre^="guide_groupe"]');
        childElements.forEach(el => {
            if (el.id) {
                el.id = el.id.replace(/\d+/, index.toString());
            }
            if (el.name) {
                el.name = el.name.replace(/\d+/, index.toString());
            }
            if (el.htmlFor) {
                el.htmlFor = el.htmlFor.replace(/\d+/, index.toString());
            }
            if (el.getAttribute('data-id')) {
                let dataIdValue = el.getAttribute('data-id');
                el.setAttribute('data-id', dataIdValue.replace(/\d+/, index.toString()));
            }
            if (el.getAttribute('data-ordre')) {
                let dataOrdreValue = el.getAttribute('data-id');
                el.setAttribute('data-id', dataOrdreValue.replace(/\d+/, index.toString()));
            }
        });

        // Met à jour la valeur de l'input ordre en fonction de l'index
        let ordreInput = group.querySelector('.ordre');

        if (ordreInput) {
            ordreInput.value = parseInt(index, 10);
        }

        updateItemsGroupIds(group);
    });
};

const updateItemsGroupIds = (ensembleSelector) => {
    let groupes = ensembleSelector.querySelectorAll('.groupe-item');

    groupes.forEach((group, indexGroupe) => {
        // Mettre à jour l'ID du groupe
        let idParts = group.id.split(/(\d+)/);
        if (idParts.length > 3) {
            idParts[3] = indexGroupe.toString();
            group.id = idParts.join('');
        }

        // Mettre à jour les IDs et noms des éléments enfants
        let childElements = group.querySelectorAll('[id^="guide_groupe"], [for^="guide_groupe"], [name^="guide[groupe"], [for^="guide[groupe"], [data-id^="guide_groupe"], [data-ordre^="guide_groupe"]');
        childElements.forEach(el => {
            if (el.id) {
                let idParts = el.id.split(/(\d+)/);
                if (idParts.length > 3) {
                    idParts[3] = indexGroupe.toString();
                    el.id = idParts.join('');
                }
            }

            if (el.name) {
                // Logique pour name
                let nameParts = el.name.split(/(\[\d+\])/);
                if (nameParts.length > 1) {
                    nameParts[3] = `[${indexGroupe}]`;
                    el.name = nameParts.join('');
                }
            }

            if (el.htmlFor) {
                let htmlForParts = el.htmlFor.split(/(\d+)/);
                if (htmlForParts.length > 3) {
                    htmlForParts[3] = indexGroupe.toString();
                    el.htmlFor = htmlForParts.join('');
                }
            }

            if (el.getAttribute('data-id')) {
                let dataIdValue = el.getAttribute('data-id');
                let dataIdParts = dataIdValue.split(/(\d+)/);
                if (dataIdParts.length > 3) {
                    dataIdParts[3] = indexGroupe.toString();
                    el.setAttribute('data-id', dataIdParts.join(''));
                }
            }

            if (el.getAttribute('data-ordre')) {
                let dataOrdreValue = el.getAttribute('data-ordre');
                let dataOrdreParts = dataOrdreValue.split(/(\d+)/);
                if (dataOrdreParts.length > 3) {
                    dataOrdreParts[3] = indexGroupe.toString();
                    el.setAttribute('data-ordre', dataOrdreParts.join(''));
                }
            }
        });

        // Met à jour la valeur de l'input ordre groupe items en fonction de l'index
        let ordreInput = group.querySelector('.ordre-groupe-items');

        if (ordreInput) {
            ordreInput.value = parseInt(indexGroupe, 10);
        }
    });
};

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

// Ajout d'un écouteur d'évènement click sur le bouton d'ajout de groupe et celui pour l'ensemble d'items
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

// Ajout d'un écouteur d'évènement click sur le bouton d'ajout d'un groupe d'item
const itemsContainer = document.querySelector('.new-guide-builder__items-container');
itemsContainer.addEventListener('click', function (event) {
    // Vérifier si l'élément cliqué a la classe 'add-items'
    if (event.target.classList.contains('add-items')) {
        // Récupère l'id du parent pour déterminer dans quel ensemble ajouter
        let parentId = event.target.parentElement.id;

        // Appelle fetchContainer avec le type
        fetchGroupeItems(parentId);
    }
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
    runesOptions.style.display = shouldShow ? 'flex' : 'none';
    shouldShow ? tree.classList.add('arbre-click') : tree.classList.remove('arbre-click')
    if (!shouldShow) {
        runesOptions.classList.remove('arbre-1');
        runesOptions.classList.remove('arbre-2');
    }

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

let uniqueClickId = 0;
const runesContainer = document.querySelector('.new-guide-builder__runes-container');
// Gérer le clic sur les arbres
runesContainer.addEventListener('click', function (event) {
    // Trouver l'élément parent .new-guide__block le plus proche du clic
    var parentBlock = event.target.closest('.new-guide__block');
    if (parentBlock) {
        // Vérifier si data-click existe
        if (!parentBlock.hasAttribute('data-click')) {
            // Si non, crée data-click avec un id unique
            parentBlock.setAttribute('data-click', `clickId-${uniqueClickId}`);
            uniqueClickId++; // Incrémenter l'ID unique pour le prochain usage
        }

        // Utiliser la valeur de data-click
        var index = parentBlock.getAttribute('data-click');

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
            let runesOptions = toggleRunesOptions(parentBlock, treeName, event.target.nextElementSibling.style.display !== 'flex');
            clickedTrees.first = runesOptions.style.display === 'flex' ? treeName : null;
            (runesOptions.style.display === 'flex' && runesOptions.classList !== 'arbre-2') ? runesOptions.classList.add('arbre-1') : runesOptions.classList.remove('arbre-1');
            let firstDiv = runesOptions.querySelector('div');
            if (firstDiv) {
                firstDiv.style.display = 'flex';
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
            if (runesOptions.style.display === 'flex') {
                clickedTrees.second = treeName;
                let firstDiv = runesOptions.querySelector('div');
                if (firstDiv) {
                    firstDiv.style.display = 'none';
                }

                // Si le deuxième arbre est ouvert, on met à jour l'input caché en "Secondaire", sinon on le réinitialise
                updateHiddenInput(parentBlock, treeType, clickedTrees.second ? "Secondaire" : "");

                (runesOptions.style.display === 'flex' && runesOptions.classList !== 'arbre-1') ? runesOptions.classList.add('arbre-2') : runesOptions.classList.remove('arbre-2');

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
    let form = document.querySelector('#FormGuide');

    if (actionPublierButton && guideValiderButton) {
        actionPublierButton.addEventListener('click', async function () {
            // if (guideId) {
            for (var key in mappingFetchURLs) {
                if (mappingFetchURLs.hasOwnProperty(key)) {
                    await fetchContainer(key);
                }
            }
            // }
            guideValiderButton.click();
        });
    }

    if (form) {
        form.addEventListener("submit", function () {
            document.querySelectorAll('.ordre-item').forEach(item => {
                if (item.value == "") {
                    item.remove();
                }
            });
            return true;
        });
    }
});

// Responsive create guide
function adjustScale() {
    const windowWidth = window.innerWidth;
    const maxWidth = 1330;
    const tabletWidth = 1024;
    let scaleFactor;

    if (windowWidth < maxWidth) {
        scaleFactor = windowWidth / maxWidth;
    } else if (windowWidth < tabletWidth) {
        scaleFactor = windowWidth / tabletWidth;
    } else {
        scaleFactor = 1;
    }

    document.getElementById('new-guide').style.transform = `scale(${scaleFactor})`;
}

// Ajuste le scale lors du chargement initial et lors du redimensionnement de la fenêtre
window.addEventListener('load', adjustScale);
window.addEventListener('resize', adjustScale);

// Recherche d'un champion
document.getElementById('searchChampion').addEventListener('input', function (e) {
    let searchTerm = e.target.value.toLowerCase();
    let champions = document.querySelectorAll('.liste-champions div');
    let displayedCount = 0;

    champions.forEach((championDiv, index) => {
        let championValue = championDiv.querySelector('input').value.toLowerCase();

        if (searchTerm === "") {
            // Affiche les 14 premiers champions si la recherche est vide
            championDiv.style.display = index < 14 ? 'block' : 'none';
            if (index < 14) displayedCount++;
        } else if (championValue.startsWith(searchTerm)) {
            championDiv.style.display = 'block';
            displayedCount++;
        } else {
            championDiv.style.display = 'none';
        }
    });
});

// Ecouteur d'évènement sur les input radio des champions
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.liste-champions input[type="radio"]').forEach(input => {
        // Appelle la fonction pour l'état initial des inputs radio
        handleChampionSelection(input);

        // Ajoute l'écouteur d'évènements pour les changements futurs
        input.addEventListener('change', function () {
            handleChampionSelection(this);
        });
    });
});

function handleChampionSelection(input) {
    if (input.checked) {
        // Trouve l'élément image associé
        let img = input.nextElementSibling.querySelector('img');

        // Clone l'image
        let clonedImg = img.cloneNode(true);

        // Insère l'image clonée
        let emptySpan = document.querySelector('.champ-select .empty');
        emptySpan.innerHTML = '';
        emptySpan.appendChild(clonedImg);

        // Actualise groupe de compétence
        getSelectedChampionId(true);
    }
}