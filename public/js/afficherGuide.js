document.addEventListener('DOMContentLoaded', function () {

    // Script pour afficher les boutons et container correspondant
    const mappingMenu = {
        "menu_commentaires": "commentaire-container",
        "menu_guide": "guide",
        // Ajoutez d'autres mappages ici si nécessaire
    };

    const allContainers = Object.values(mappingMenu).map(id => document.getElementById(id));
    const menuElements = Object.keys(mappingMenu).map(id => document.getElementById(id));

    Object.keys(mappingMenu).forEach(function (menuId) {
        const menuElement = document.getElementById(menuId);
        if (menuElement) {
            menuElement.addEventListener('click', function () {
                // Masquer tous les conteneurs
                allContainers.forEach(container => {
                    if (container) {
                        container.style.display = 'none';
                    }
                });

                // Affiche le conteneur correspondant
                const containerToShow = document.getElementById(mappingMenu[menuId]);
                if (containerToShow) {
                    containerToShow.style.display = 'flex';
                }

                // Ajoute la class current aux autres menus et la retire de l'élément actuel
                menuElements.forEach(element => {
                    if (element) {
                        if (element === menuElement) {
                            element.classList.remove('current');
                        } else {
                            element.classList.add('current');
                        }
                    }
                });
            });
        }
    });

    // Script pour la pagination des sections

    document.querySelectorAll('.page-number').forEach(function (page) {
        page.addEventListener('click', function () {
            const pageNumber = parseInt(this.textContent) - 1; // index basé sur 0
            const sectionId = this.closest('section').id; // id de la section parente

            // Retire la classe current
            document.querySelectorAll(`#${sectionId} .page-number`).forEach(function (pageNum) {
                pageNum.classList.remove('current');
            });

            // Ajoute la classe 'current' au numéro de page cliqué
            this.classList.add('current');

            // Masque tous les groupes de cette section
            document.querySelectorAll(`#${sectionId} .groupe`).forEach(function (groupe) {
                groupe.style.display = 'none';
            });

            // Affiche le groupe correspondant au numéro de page
            const groupeToShow = document.querySelector(`#${sectionId} #groupe${sectionId}${pageNumber}`);
            if (groupeToShow) {
                groupeToShow.style.display = 'flex';
            }
        });
    });
});

// Gestion de la pagination avec une requête AJAX
const container = document.getElementById('commentaire-container');
const pagination = container.querySelector('.pagination');

container.addEventListener('click', function (event) {
    const target = event.target;

    if (target.tagName !== 'A') {
        target = target.closest('a');
    }

    if (target && target.closest('.pagination span:not(.current)')) {
        event.preventDefault();
        const url = target.getAttribute('href');

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            container.innerHTML = html;
        });
    }
});

