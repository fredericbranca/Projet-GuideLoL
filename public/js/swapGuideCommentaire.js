// Script pour afficher les boutons et container correspondant
document.addEventListener('DOMContentLoaded', function () {
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
});