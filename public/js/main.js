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

// Futur test
// function adjustScale() {
//     const scaleFactor = window.innerWidth / 1920; // 1920 est la largeur de base
//     document.getElementById('scale-container').style.transform = `scale(${scaleFactor})`;
// }

// // Ajuste le scale lors du chargement initial et lors du redimensionnement de la fenêtre
// window.addEventListener('load', adjustScale);
// window.addEventListener('resize', adjustScale);