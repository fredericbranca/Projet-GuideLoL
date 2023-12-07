const searchInput = document.getElementById('search-champion');
var searchTimer;
// Script pour rechercher des champions et afficher le résultat
document.addEventListener('DOMContentLoaded', function () {
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);

        searchTimer = setTimeout(() => {

            var searchTerm = searchInput.value;
            // div qui affiche les résultats
            var div = document.querySelector('.search-result');

            if (searchTerm !== "") {

                fetch('/search?term=' + encodeURIComponent(searchTerm))
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        // Efface la recherche précédente
                        div.innerHTML = '';

                        // Ajoute chaque champion
                        // Ecouteur d'évènement sur un champion pour valider le form avec le filtre du champion
                        Object.keys(data.champions).forEach(function (championKey) {
                            var champion = data.champions[championKey];
                            div.innerHTML += `
                            <a class="champion-link" href="/guides?champion=${champion.name}">
                                <div class="image-wrapper">
                                    <img src="${url}/champion/${champion.idChamp}.png.webp" alt="${champion.name}">
                                </div>
                                <div class="champion-name">${champion.name}</div>
                            </a>`;
                        });
                    })
                    .catch(function (error) {
                        // Gestion d'erreurs
                        console.error(error);
                    });

            } else {
                div.innerHTML = '';
                div.append();
            }

        }, 300);
    });
});

// Ecouteur d'év sur les scroll pour éviter que la propagation ce fasse sur les éléments parent
document.getElementById('scrollable-container').addEventListener('wheel', function (event) {
    var element = event.currentTarget;
    var scrollTop = element.scrollTop;
    var scrollHeight = element.scrollHeight;
    var height = element.clientHeight;
    var delta = event.deltaY;

    if ((delta > 0 && scrollTop + height >= scrollHeight) || (delta < 0 && scrollTop <= 0)) {
        event.preventDefault();
    }
});