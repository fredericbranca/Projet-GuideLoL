function placeHamburger() {
    const hamburger = document.querySelector('header .menu');
    const footer = document.querySelector('footer');
    const footerRect = footer.getBoundingClientRect();
    if (footerRect.top < window.innerHeight) {
        hamburger.style.bottom = (window.innerHeight - footerRect.top + 10) + 'px';
    } else {
        hamburger.style.bottom = '20px';
    }
}

document.addEventListener('DOMContentLoaded', (event) => {
    // Sélectionne tous les messages flash
    const flashMessages = document.querySelectorAll('.flash-message');

    // Masque le message au bout de 5sec
    flashMessages.forEach((msg) => {
        setTimeout(() => {
            msg.style.display = 'none';
        }, 5000);
    });

    if (window.innerWidth < 481) {
        window.addEventListener('load', placeHamburger);
        window.addEventListener('scroll', placeHamburger);
    }
});

