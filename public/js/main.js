document.addEventListener('DOMContentLoaded', () => {
    // Sélectionne tous les messages flash
    const flashMessages = document.querySelectorAll('.flash-message');

    window.setTimeout(function () {
        document.querySelector('.flash-message').classList.remove('active');
    }, 5000);

    flashMessages.forEach((msg) => {
        setTimeout(() => {
            msg.remove();
        }, 6000);
    });
});

