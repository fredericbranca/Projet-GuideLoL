document.addEventListener('DOMContentLoaded', function () {

    // Script pour afficher les boutons et container correspondant
    const mappingMenu = {
        "menu_commentaires": "commentaire-container",
        "menu_guide": "guide",
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

    // Script pour compter le nombre de caractère du textarea
    const textarea = document.getElementById('commentaire_commentaire');
    const characterCount = document.getElementById('characterCount');

    textarea.addEventListener('input', function () {
        const currentLength = textarea.value.length;
        const maxLength = textarea.getAttribute('maxlength');
        characterCount.textContent = `${currentLength}/${maxLength}`;
    });

    // Confirmation de suppression d'un guide
    document.getElementById('menu_delete').addEventListener('click', function (e) {
        e.preventDefault();

        var result = confirm('Êtes-vous sûr de vouloir supprimer ce guide ?');
        if (result) {
            document.getElementById('delete-guide').submit();
        }
    })

    // Ouverture lien édition guide
    document.getElementById('menu_edit').addEventListener('click', function (e) {
        this.querySelector('a').click();
    })
});

// Gestion de la pagination avec une requête AJAX
const container = document.getElementById('commentaires-users');
const pagination = container.querySelector('.pagination');

container.addEventListener('click', function (event) {
    const target = event.target;

    if (target.tagName !== 'A' && target.closest('.pagination span:not(.current)')) {
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

// Gestion du formulaire avec une requete AJAX
const form = document.getElementById('commentaire-form');
const commentaireContainer = document.getElementById('commentaires-users');
const characterCount = document.getElementById('characterCount');
const textarea = document.getElementById('commentaire_commentaire');
const maxLength = textarea.getAttribute('maxlength');

form.addEventListener('submit', async function (event) {
    event.preventDefault();

    const formData = new FormData(form);
    const errorsContainer = document.getElementById('form-errors');
    errorsContainer.innerHTML = ''; // Efface les erreurs précédentes

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        // Vérifie le type de contenu de la réponse
        const contentType = response.headers.get('Content-Type');

        if (contentType && contentType.includes('application/json')) {
            const json = await response.json();
            if (json.errors) {
                json.errors.forEach(error => {
                    const errorElement = document.createElement('div');
                    errorElement.textContent = error;
                    errorsContainer.appendChild(errorElement);
                });

                // Masque les erreurs après 5s ec
                setTimeout(() => {
                    errorsContainer.innerHTML = '';
                }, 5000);

                return; // Arrête l'exécution en cas d'erreur
            }
        }

        // Si la réponse est OK et de type HTML
        if (contentType && contentType.includes('text/html')) {
            const html = await response.text();
            commentaireContainer.innerHTML = html;
            form.querySelector('#commentaire_commentaire').value = "";
            characterCount.textContent = `0/${maxLength}`;
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

// Signaler le message d'un utilisateur
const signal = document.getElementById('report-message');
signal.addEventListener('click', async function () {

    var result = confirm('Voulez-vous signaler ce message ?');
    if (result) {
        let id = signal.dataset.id;

        try {
            const response = await fetch('/report/message/' + id, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            // Vérifie le type de contenu de la réponse
            const contentType = response.headers.get('Content-Type');

            if (contentType && contentType.includes('application/json')) {
                const json = await response.json();
                const container = document.querySelector('.content');
                if (json.success) {
                    const element = document.createElement('div');
                    element.classList.add('flash-message', 'flash-success', 'active');
                    element.textContent = json.message;
                    container.appendChild(element);
                    displayMessage();
                    return;
                } else {
                    const element = document.createElement('div');
                    element.classList.add('flash-message', 'flash-error', 'active');
                    element.textContent = json.message;
                    container.appendChild(element);
                    displayMessage();
                    return;
                }
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
})