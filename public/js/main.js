document.addEventListener('DOMContentLoaded', () => {
    // Sélectionne tous les messages flash
    const flashMessages = document.querySelectorAll('.flash-message');

    flashMessages.forEach((msg) => {
        setTimeout(function () {
            document.querySelector('.flash-message').classList.remove('active');
        }, 5000);

        setTimeout(() => {
            msg.remove();
        }, 6000);
    });
});

