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