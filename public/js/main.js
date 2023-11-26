document.addEventListener('DOMContentLoaded', (event) => {
    // Sélectionne tous les messages flash
    const flashMessages = document.querySelectorAll('.flash-message');

    // Masque le message au bout de 5sec
    flashMessages.forEach((msg) => {
        setTimeout(() => {
            msg.style.display = 'none';
        }, 5000);
    });
});
