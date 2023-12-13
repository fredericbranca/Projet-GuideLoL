// Script pour effacer les messages d'erreurs dans les inputs login/register au clic
document.addEventListener('DOMContentLoaded', function () {
    var loginContainer = document.querySelector('.login-container');

    loginContainer.addEventListener('click', function (event) {
        if (event.target.tagName === 'INPUT') {
            var parentDiv = event.target.closest('.champs');
            var errorMessages = parentDiv.querySelectorAll('.error-message');
            errorMessages.forEach(function (errorMessage) {
                errorMessage.style.display = 'none';
            });
        }
    });
});