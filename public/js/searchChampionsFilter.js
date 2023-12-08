const searchInput = document.getElementById('guide-filtre-champion');
var searchTimer;
// Script pour rechercher des champions et afficher le résultat
document.addEventListener('DOMContentLoaded', function () {
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);

        searchTimer = setTimeout(() => {

            var searchTerm = searchInput.value;
            // Span qui affiche les résultats
            var span = document.querySelector('.search-result');

            if (searchTerm !== "") {

                fetch('/search?term=' + encodeURIComponent(searchTerm))
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        // Efface la recherche précédente
                        span.innerHTML = '';

                        // Ajoute chaque champion
                        // Ecouteur d'évènement sur un champion pour valider le form avec le filtre du champion
                        Object.keys(data.champions).forEach(function (championKey) {
                            var champion = data.champions[championKey];
                            var div = document.createElement('div');
                            div.innerHTML += `
                            <div class="image-wrapper">
                                <img src="${url}/champion/${champion.idChamp}.png.webp" alt="${champion.name}">
                            </div>
                            <div class="champion-name">${champion.name}</div>`;
                            div.classList.add('champion-div');
                            div.addEventListener('click', function () {
                                document.getElementById('guide_filtre_champion').value = champion.name; // Met à jour la valeur du champ
                                document.getElementById('form-guide-filter').submit(); // Soumet le formulaire
                            });
                            if (span !== '') {
                                span.appendChild(div);
                            }
                        });
                    })
                    .catch(function (error) {
                        // Gestion d'erreurs
                        console.error(error);
                    });

            } else {
                span.innerHTML = "";
                span.append();
            }

        }, 300);
    });
});

// Execute le formulaire au clic sur un role
divRoles = document.getElementById(`guide_filtre_role`);

divRoles.addEventListener('click', function (event) {
    // Vérifie si l'élément cliqué est un label
    if (event.target.tagName === 'IMG'|| event.target.tagName === 'LABEL') {
        // Temporisation pour permettre à l'input radio d'être coché
        setTimeout(function () {

            document.getElementById('form-guide-filter').submit();
        }, 10);
    }
});


// Efface le filtre des champions
spanClearChampion = document.getElementById('clear-champion-btn');
spanClearChampion.addEventListener('click', function () {
    document.getElementById('guide_filtre_champion').value = '';
    document.getElementById('form-guide-filter').submit();
});

// Affiche le nom du champion actuel dans le champ recherche
let hiddenInputChamp = document.getElementById('guide_filtre_champion');
if (hiddenInputChamp.value) {
    searchInput.value = hiddenInputChamp.value;
}

// Efface tout le formulaire
spanClear = document.getElementById('clear-btn');
spanClear.addEventListener('click', function () {
    document.getElementById('guide_filtre_champion').value = '';
    document.getElementById('guide_filtre_role_placeholder').checked = 'checked';
    document.getElementById('form-guide-filter').submit();
});