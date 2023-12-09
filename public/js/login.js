// Script pour effacer les messages d'erreurs dans les inputs login/register au clic
document.addEventListener('DOMContentLoaded', function() {
    var loginContainer = document.querySelector('.login-container');

    loginContainer.addEventListener('click', function(event) {
        if (event.target.tagName === 'INPUT') {
            var errorMessage = event.target.nextElementSibling;
            if (errorMessage && errorMessage.classList.contains('error-message')) {
                errorMessage.style.display = 'none';
            }
        }
    });
});