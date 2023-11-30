document.addEventListener('DOMContentLoaded', function () {
    const mappingMenu = {
        "menu_image": ".modifier-avatar",
        "menu_pseudo": ".change-pseudo",
    };
    const menu = document.querySelector('.menu');

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

let formAvatarStatut = false;
// Requete AJAX pour le form Avatar
if (!formAvatarStatut) {
    let avatarForm = document.getElementById('avatar-form');
    avatarForm.addEventListener('submit', function (event) {
        event.preventDefault();

        avatarForm.querySelectorAll('.error-msg').forEach(message => {
            message.remove();
        })

        var formData = new FormData(this);
        fetch(this.action, {
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
                        avatarForm.appendChild(errorMsg);
                    });

                } else {
                    avatarForm.submit();
                    formAvatarStatut = true;
                }
            })
            .catch(error => console.error('Error:', error));
    });
}