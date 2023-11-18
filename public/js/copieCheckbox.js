var orderOfSelection = [];
let uniqueGroupeId = 0;
ensemblesItemsContainer = document.querySelector('.new-guide-builder__items-container');

// Gestionnaire d'événements pour les changements des checkbox des groupes
ensemblesItemsContainer.addEventListener('change', function (event) {
    // Vérifie si l'élément modifié est une checkbox dans .groupe-item
    if (event.target.classList.contains('item-checkbox')) {
        var groupe = event.target.closest('.groupe-item');

        // Check si attribut data-groupe existe
        if (!groupe.hasAttribute('data-groupe')) {
            // Si non on le crée
            groupe.setAttribute('data-groupe', `groupeId-${uniqueGroupeId}`);
            uniqueGroupeId++; // Incrémente l'ID unique pour le prochain groupe
        }

        var groupeId = groupe.getAttribute('data-groupe');

        // Initialisation du tableau pour le groupe s'il n'existe pas encore
        if (!orderOfSelection[groupeId]) {
            orderOfSelection[groupeId] = [];
        }

        if (event.target.classList.contains('item-checkbox')) {
            var checkboxValue = event.target.value;
            var orderArray = orderOfSelection[groupeId];

            if (event.target.checked) {
                // 10 items max
                if (orderArray.length >= 10) {
                    alert("Vous pouvez sélectionner au maximum 10 items.");
                    event.target.checked = false; // Désélectionne la checkbox
                    return;
                }
                // Ajoute l'item
                orderArray.push(checkboxValue);
            } else {
                // Retire l'item
                var index = orderOfSelection[groupeId].indexOf(checkboxValue);
                if (index > -1) {
                    orderArray.splice(index, 1);
                }
            }
            updateOrderFields(groupe, groupeId);
            updateSelectedItem(groupe, groupeId);
        }
    }
});

// Gestionnaire d'événements délégué pour les clics droits sur les items copiés
ensemblesItemsContainer.addEventListener('contextmenu', function (event) {
    var clickedItem = event.target.closest('.copie-item');
    if (clickedItem) {
        event.preventDefault();
        var groupe = clickedItem.closest('.groupe-item');
        if (groupe) {
            var checkboxId = clickedItem.getAttribute('data-id');
            var checkbox = document.getElementById(checkboxId);
            if (checkbox && checkbox.checked) {
                checkbox.click();
            }
        }
    }
});


function updateOrderFields(groupe, groupeId) {
    orderOfSelection[groupeId].forEach(function (value, index) {
        var checkbox = groupe.querySelector('.item-checkbox[value="' + value + '"]');
        var itemDiv = checkbox ? checkbox.closest('.item') : null;
        var hiddenField = itemDiv ? itemDiv.querySelector('.ordre-item') : null;

        if (hiddenField) {
            hiddenField.value = index;
        }
    });

    // Réinitialiser la valeur des champs cachés des items non sélectionnés
    groupe.querySelectorAll('.item-checkbox:not(:checked)').forEach(function (checkbox) {
        var itemDiv = checkbox.closest('.item');
        var hiddenField = itemDiv ? itemDiv.querySelector('.ordre-item') : null;

        if (hiddenField) {
            hiddenField.value = '';
        }
    });
}

function updateSelectedItem(groupe) {
    var selectedItemDiv = groupe.querySelector('#selectedItem');
    var copieItems = selectedItemDiv.getElementsByClassName('copie-item');

    // Supprime les divs 'copie-item' existantes
    while (copieItems.length > 0) {
        selectedItemDiv.removeChild(copieItems[0]);
    }

    // Récupère tous les champs cachés pour les items sélectionnés
    var selectedHiddenFields = Array.from(groupe.querySelectorAll('.item-checkbox:checked')).map(function (checkbox) {
        return checkbox.closest('.item').querySelector('.ordre-item');
    });

    // Trie les champs cachés en fonction de leur valeur
    selectedHiddenFields.sort(function (a, b) {
        return a.value - b.value;
    });

    // Ajoute une div pour chaque item sélectionné dans l'ordre
    selectedHiddenFields.forEach(function (hiddenField) {
        var checkbox = groupe.querySelector('.item-checkbox[value="' + hiddenField.name.split('][').pop().slice(0, -1) + '"]');
        var label = checkbox.nextElementSibling;
        var img = label.querySelector('img');

        var copieItemDiv = document.createElement('div');
        copieItemDiv.className = 'copie-item';

        // Ajout de data-id à la div copie-item ou à l'img
        copieItemDiv.setAttribute('data-id', checkbox.id);


        var newImg = document.createElement('img');
        newImg.src = img.src;
        newImg.alt = img.alt;
        newImg.classList = 'handleItem'

        // Ajout data-ordre à copieItemDiv
        let itemDiv = checkbox.closest('.item');
        let ordreInput = itemDiv.querySelector('.ordre-item');
        copieItemDiv.setAttribute('data-ordre', ordreInput.id);

        copieItemDiv.appendChild(newImg);
        selectedItemDiv.appendChild(copieItemDiv);

    });

    // Ajoute des divs vides pour maintenir 3 divs 'copie-item'
    var currentCopieItemCount = selectedItemDiv.getElementsByClassName('copie-item').length;
    for (var i = currentCopieItemCount; i < 3; i++) {
        var emptyCopieItemDiv = document.createElement('div');
        emptyCopieItemDiv.className = 'copie-item';
        selectedItemDiv.appendChild(emptyCopieItemDiv);
    }
}
