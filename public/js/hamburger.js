// Liste déroulante
const menuButton = document.querySelector(".menu__btn");
if (menuButton) {
    dropDownFunc(menuButton);
}

// Fonction d'ouverture et de fermeture de la liste déroulante
function dropDownFunc(dropDown) {
  if (dropDown.classList.contains("menu__btn") === true) {
    dropDown.addEventListener("click", function (e) {
      e.preventDefault();
      if (this.nextElementSibling.classList.contains("active") === true) {
        // Ferme la liste déroulante cliquée
        this.parentElement.classList.remove("active");
      } else {
        // Ferme la liste déroulante
        closeDropdown();
        // ajout class à l'ouverture
        this.parentElement.classList.add("active");
      }
    });
  }
}

window.addEventListener("click", function (e) {
  // Ferme le menu si un clic se produit en dehors du menu
  if (e.target.closest(".menu") === null) {
    // Close the opend dropdown
    closeDropdown();
  }
});

// Clique pour Mobile
window.addEventListener("touchstart", function (e) {
  // Ferme le menu si un clic se produit en dehors du menu
  if (e.target.closest(".menu") === null) {
    // Ferme le menu déroulant
    closeDropdown();
  }
});

// Ferme la liste déroulante
function closeDropdown() {
  // supprime la classe ouverte
  document.querySelectorAll(".menu").forEach(function (container) {
    container.classList.remove("active");
  });
}
