function displayMessage() {
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
}

displayMessage();


// Message temporaire
document.addEventListener('DOMContentLoaded', (event) => {
    let messageCookie = document.cookie.split('; ').find(row => row.startsWith('messagetempo='));
    if (messageCookie) {
        let message = decodeURIComponent(messageCookie.split('=')[1]);
        const element = document.createElement('div');
        element.classList.add('flash-message', 'flash-success', 'active');
        element.textContent = message;
        document.querySelector('.content').appendChild(element);
        displayMessage();
        document.cookie = "messagetempo=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/;";
    }
});