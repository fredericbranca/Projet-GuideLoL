// Mapping entre les spans et les contenus
const mapping = {
    "sidebar-config": ".new-guide-config",
    "sidebar-builder": ".new-guide-builder",
};

// Sélectionnez le conteneur principal des spans
let sidebar = document.querySelector(".new-guide-sidebar");

// Ajout d'un écouteur d'événement "click" sur le conteneur
sidebar.addEventListener("click", function(event) {
    if (event.target.tagName === "SPAN") {
        // Supprime .current de tous les enfants
        for (let child of this.children) {
            child.classList.remove("current");
        }
        // Ajoute .current à l'élément cliqué
        event.target.classList.add("current");
        
        // Cachez tous les contenus
        for (let key in mapping) {
            document.querySelector(mapping[key]).style.display = "none";
        }
        // Affichez le contenu correspondant à l'élément cliqué
        let id = event.target.id;
        if (mapping[id]) {
            document.querySelector(mapping[id]).style.display = "block";
        }
    }
});


async function loadGroupeSortsInvocateur(errors = []) {
    try {
        let response = await fetch('/load-groupe-sorts-invocateur');
        if (!response.ok) {
            throw new Error('Erreur réseau lors de la tentative de récupération du contenu.');
        }

        let html = await response.text();

        if (errors.length > 0) {
            html = `<div class="error-messages">${errors.join('<br>')}</div>` + html;
        }

        document.getElementById("groupeSortsInvocateurContainer").innerHTML = html;
    } catch (error) {
        console.error("Il y a eu un problème avec l'opération fetch: ", error.message);
    }
}

// Formulaire loadGroupeSortsInvocateur
document.getElementById("loadGroupeSortsInvocateur").addEventListener("click", function (event) {
    event.preventDefault();
    loadGroupeSortsInvocateur();
});

// Validation du formulaire Guide
document.addEventListener("DOMContentLoaded", function () {
    let form = document.querySelector('form');
    form.addEventListener('submit', function (event) {
        event.preventDefault();

        // Envoyez le formulaire à votre serveur
        fetch('/guide/new', {
            method: 'POST',
            body: new FormData(form),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    form.submit(); // Soumet le formulaire normalement
                } else {
                    // Si le formulaire est invalide, ouvre le formulaire et affichez les erreurs
                    loadGroupeSortsInvocateur(data.errors);
                }
            });
    });
});