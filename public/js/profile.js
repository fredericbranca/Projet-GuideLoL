document.addEventListener('DOMContentLoaded', function () {
    const mappingMenu = {
        "menu_image": ".modifier-avatar",
        "menu_pseudo": ".change-pseudo",
        "menu_mail": ".change-email",
        "menu_mdp": ".change-password",
    };
    const menu = document.querySelector('.content .menu');

    Object.keys(mappingMenu).forEach(function (menuId) {
        const menuElement = document.getElementById(menuId);
        if (menuElement) {
            menuElement.addEventListener('click', function () {
                // Cache tous les conteneurs et le menu
                Object.values(mappingMenu).forEach(function (containerClass) {
                    const container = document.querySelector(containerClass);
                    if (container) {
                        container.style.display = 'none';
                    }
                });
                menu.style.display = 'none'; // Cache le menu

                // Affiche le conteneur correspondant
                const containerToShow = document.querySelector(mappingMenu[menuId]);
                if (containerToShow) {
                    containerToShow.style.display = 'flex';

                    // Vérifie si le bouton Annuler existe déjà
                    let btnAnnuler = containerToShow.querySelector('.btn-annuler');
                    if (!btnAnnuler) {
                        // Crée le bouton Annuler si besoin
                        btnAnnuler = document.createElement('span');
                        btnAnnuler.textContent = 'Annuler';
                        btnAnnuler.classList.add('btn-annuler');
                        containerToShow.appendChild(btnAnnuler);
                    }

                    // Ajoute un écouteur d'événement sur le btn Annuler
                    btnAnnuler.addEventListener('click', function () {
                        containerToShow.style.display = 'none';
                        menu.style.display = 'flex';
                    });
                }

            });
        }
    });
});

// Aperçu de l'image
document.addEventListener('DOMContentLoaded', function () {
    var fileInput = document.getElementById('avatar_avatar');
    var previewImage = document.getElementById('image-preview');

    fileInput.addEventListener('change', function (event) {
        var files = fileInput.files;
        if (files && files[0]) {
            // Efface la preview
            previewImage.src = '';

            // Efface les messages d'erreurs
            document.getElementById('avatar-form').querySelectorAll('.error-msg').forEach(message => {
                message.remove();
            })

            var reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
            };
            reader.readAsDataURL(files[0]);
        }
    });
});

// Fonction pour gérer les erreurs des formulaires en AJAX
function handleFormAjax(form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();

        form.querySelectorAll('.error-msg').forEach(message => {
            message.remove();
        });

        var formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                data.errors.forEach(error => {
                    let errorMsg = document.createElement('div');
                    errorMsg.classList.add('error-msg');
                    errorMsg.textContent = error;
                    form.appendChild(errorMsg);
                });
            } else {
                form.submit();
            }
        })
        .catch(error => console.error('Error:', error));
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const avatarForm = document.getElementById('avatar-form');
    const emailForm = document.getElementById('email-form');
    const pseudoForm = document.getElementById('pseudo-form');
    const passwordForm = document.getElementById('password-form');

    if (avatarForm) handleFormAjax(avatarForm);
    if (emailForm) handleFormAjax(emailForm);
    if (pseudoForm) handleFormAjax(pseudoForm);
    if (passwordForm) handleFormAjax(passwordForm);
});