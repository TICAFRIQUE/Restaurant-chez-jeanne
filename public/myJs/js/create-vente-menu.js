// popover
const popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
);
const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
});
// Fonction pour augmenter la quantité du plat
function increaseValue(button) {
    const input = button.parentElement.querySelector(".cart-plus-minus-box");
    let currentValue = parseInt(input.value, 10) || 0;
    input.value = currentValue + 1;
    triggerChange(input);
    updateComplementGarnitureLimits(input);

    // Met à jour le prix total
    updateTotalPrice();
    updatePlatPrice(input);
}

// Fonction pour diminuer la quantité du plat
function decreaseValue(button) {
    const input = button.parentElement.querySelector(".cart-plus-minus-box");
    let currentValue = parseInt(input.value, 10) || 0;

    if (currentValue > 1) {
        input.value = currentValue - 1;
        triggerChange(input);
    }

    ensureSelectionWithinLimits(input);
    updateComplementGarnitureLimits(input);

    // Met à jour le prix total
    updateTotalPrice();
    updatePlatPrice(input);
}

// Fonction pour mettre à jour le prix total d'un plat
function updatePlatPrice(input) {
    const parent = input.closest(".card-body");
    const platCheckbox = parent.querySelector(".plat-checkbox");
    const price = parseFloat(platCheckbox.dataset.price || 0);
    const quantity = parseInt(input.value, 10) || 0;
    const platTotal = price * quantity;

    // Afficher le prix total dans un élément spécifique si nécessaire
    const platPriceDisplay = parent.querySelector(".plat-price-display");
    if (platPriceDisplay) {
        platPriceDisplay.textContent =
            platTotal.toLocaleString("fr-FR") + " FCFA"; // Adapter selon votre devise
    }

    updateTotalPrice(); // Mettre à jour le total global
}

function updateTotalPrice() {
    const platCheckboxes = document.querySelectorAll(".plat-checkbox:checked");
    let total = 0;

    platCheckboxes.forEach((checkbox) => {
        const parent = checkbox.closest(".card-body");
        const quantityInput = parent.querySelector(".cart-plus-minus-box");
        const price = parseFloat(checkbox.dataset.price || 0);
        const quantity = parseInt(quantityInput.value, 10) || 0;

        total += price * quantity;
    });

    // Mettre à jour l'affichage dans le DOM
    const totalDisplay = document.getElementById("totalAmount");
    if (totalDisplay) {
        totalDisplay.textContent = total.toLocaleString("fr-FR") + " FCFA"; // Mise à jour
    }

    // Appeler updateGrandTotal
    // if (typeof window.updateGrandTotal === "function") {
    setTimeout(() => {
        const totalMenu = parseFloat(
            totalDisplay.textContent.replace(/\s/g, "").replace("FCFA", "") || 0
        );
        window.updateGrandTotal(totalMenu);
    }, 10);
    // } else {
    //     console.error("La fonction updateGrandTotal n'est pas définie.");
    // }
}

// Fonction pour gérer l'ajout ou le retrait des prix dans le tableau
function handlePlatSelection(checkbox) {
    const parent = checkbox.closest(".card-body");
    const quantityInput = parent.querySelector(".cart-plus-minus-box");
    const price = parseFloat(checkbox.dataset.price || 0);
    const quantity = parseInt(quantityInput.value, 10) || 0;

    if (checkbox.checked) {
        // Ajouter au total
        updateTotalPrice();
    } else {
        // Réinitialiser la quantité à 0 si décoché
        quantityInput.value = 1;
        updatePlatPrice(quantityInput);

        updateTotalPrice();
    }
}

// Ajout des événements nécessaires
document.addEventListener("DOMContentLoaded", function () {
    const platInputs = document.querySelectorAll(".cart-plus-minus-box");
    const platCheckboxes = document.querySelectorAll(".plat-checkbox");

    platInputs.forEach((input) => {
        input.addEventListener("change", function () {
            updatePlatPrice(input);
        });
    });

    platCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            handlePlatSelection(checkbox);
        });
    });

    // Mettre à jour le total au chargement initial
    updateTotalPrice();
});

// Fonction pour mettre à jour les limites de sélection des compléments et garnitures
function updateComplementGarnitureLimits(input) {
    const platId = input
        .closest(".form-check")
        .querySelector(".plat-checkbox")?.value;

    if (!platId) {
        console.error("Plat ID introuvable");
        return;
    }

    const quantity = parseInt(input.value, 10);

    // Mettre à jour les compléments
    const complementCheckboxes = document.querySelectorAll(
        `.complement-checkbox[data-plat-id="${platId}"]`
    );
    manageSelectionLimits(complementCheckboxes, quantity);

    // Mettre à jour les garnitures
    const garnitureCheckboxes = document.querySelectorAll(
        `.garniture-checkbox[data-plat-id="${platId}"]`
    );
    manageSelectionLimits(garnitureCheckboxes, quantity);
}

// Fonction déclenchée lors de tout changement de quantité
function triggerChange(input) {
    const parent = input.closest(".card-body");
    const platQuantityInput = parent.querySelector(".quantityPlat");
    const platQuantity = parseInt(platQuantityInput.value) || 0;

    // Gestion des quantités des garnitures
    const garnitureQuantities = Array.from(
        parent.querySelectorAll(".quantityGarniture")
    ).reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);

    if (garnitureQuantities >= platQuantity) {
        adjustGarnitureQuantities(parent, platQuantity);
    } else {
        // Permettre de cocher d'autres options si platQuantity est inférieur
        parent.querySelectorAll(".garniture-checkbox").forEach((checkbox) => {
            checkbox.disabled = false;
        });
    }

    // Gestion des quantités des compléments
    const complementQuantities = Array.from(
        parent.querySelectorAll(".quantityComplement")
    ).reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);

    if (complementQuantities >= platQuantity) {
        adjustComplementQuantities(parent, platQuantity);
    } else {
        // Permettre de cocher d'autres options si platQuantity est inférieur
        parent.querySelectorAll(".complement-checkbox").forEach((checkbox) => {
            checkbox.disabled = false;
        });
    }
}

// Fonction pour ajuster les quantités des compléments
function adjustComplementQuantities(parent, maxQuantity) {
    const complementInputs = parent.querySelectorAll(".quantityComplement");
    let remainingQuantity = maxQuantity;

    complementInputs.forEach((input) => {
        const value = parseInt(input.value) || 0;

        if (value > remainingQuantity) {
            input.value = remainingQuantity;
            remainingQuantity = 0;
        } else {
            remainingQuantity -= value;
        }

        // Si la quantité tombe à 0 , décocher, désactiver et cacher
        if (parseInt(input.value) === 0) {
            const checkbox = input
                .closest(".form-check")
                .querySelector(".complement-checkbox");
            if (checkbox) {
                checkbox.checked = false;
                checkbox.disabled = true;
                toggleQuantityVisibility(checkbox, false);
            }
        }
    });
}

// Fonction pour ajuster les quantités des garnitures
function adjustGarnitureQuantities(parent, maxQuantity) {
    const garnitureInputs = parent.querySelectorAll(".quantityGarniture");
    let remainingQuantity = maxQuantity;

    garnitureInputs.forEach((input) => {
        const value = parseInt(input.value) || 0;

        if (value > remainingQuantity) {
            input.value = remainingQuantity;
            remainingQuantity = 0;
        } else {
            remainingQuantity -= value;
        }

        // Si la quantité tombe à 0  , décocher, désactiver et cacher
        if (parseInt(input.value) === 0) {
            const checkbox = input
                .closest(".form-check")
                .querySelector(".garniture-checkbox");
            if (checkbox) {
                checkbox.checked = false;
                checkbox.disabled = true;
                toggleQuantityVisibility(checkbox, false);
            }
        }
    });
}

// Fonction pour gérer la limite de sélection des compléments et garnitures
function manageSelectionLimits(checkboxes, limit) {
    let selectedCount = Array.from(checkboxes).filter(
        (box) => box.checked
    ).length;

    checkboxes.forEach((checkbox) => {
        checkbox.disabled = selectedCount >= limit && !checkbox.checked;

        checkbox.addEventListener("change", function () {
            selectedCount = Array.from(checkboxes).filter(
                (box) => box.checked
            ).length;
            checkboxes.forEach((box) => {
                box.disabled = selectedCount >= limit && !box.checked;
            });
        });
    });
}

// Fonction pour s'assurer que les sélections restent dans les limites
function ensureSelectionWithinLimits(input) {
    const platId = input
        .closest(".form-check")
        .querySelector(".plat-checkbox")?.value;

    if (!platId) {
        return;
    }

    const quantity = parseInt(input.value, 10);

    // Décocher les compléments en excès
    const complementCheckboxes = document.querySelectorAll(
        `.complement-checkbox[data-plat-id="${platId}"]`
    );
    deselectExcessItems(complementCheckboxes, quantity);

    // Décocher les garnitures en excès
    const garnitureCheckboxes = document.querySelectorAll(
        `.garniture-checkbox[data-plat-id="${platId}"]`
    );
    deselectExcessItems(garnitureCheckboxes, quantity);
}

// Fonction pour décocher les éléments en excès
function deselectExcessItems(checkboxes, limit) {
    let selectedCount = 0;
    checkboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            selectedCount++;
            if (selectedCount > limit) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false); // Masquer la quantité associée
            }
        }
    });
}

function toggleQuantityVisibility(checkbox, isVisible) {
    const parent = checkbox.closest(".form-check");
    const quantityWrapper = parent.querySelector(".product-quantity");
    const quantityInput = parent.querySelector(".cart-plus-minus-box");

    if (quantityWrapper && quantityInput) {
        // Afficher ou masquer la section quantité
        quantityWrapper.style.display = isVisible ? "block" : "none";

        if (checkbox.checked) {
            // Si l'élément est coché et que la quantité est vide, initialiser à 1
            if (!quantityInput.value || quantityInput.value == 0) {
                quantityInput.value = 1;
            }
        } else {
            // Si l'élément est décoché, la quantité devient 0
            quantityInput.value = 0;
        }
    }
}

// Initialisation des événements lors du chargement du document
document.addEventListener("DOMContentLoaded", function () {
    const platCheckboxes = document.querySelectorAll(".plat-checkbox");
    const complementCheckboxes = document.querySelectorAll(
        ".complement-checkbox"
    );
    const garnitureCheckboxes = document.querySelectorAll(
        ".garniture-checkbox"
    );
    const platInputs = document.querySelectorAll(".cart-plus-minus-box");

    // Mettre à jour les limites dès le chargement pour chaque plat
    platInputs.forEach((input) => {
        updateComplementGarnitureLimits(input);
    });

    // Vérifier l'état initial des cases à cocher et ajuster les champs de quantité
    complementCheckboxes.forEach((checkbox) => {
        toggleQuantityVisibility(checkbox, checkbox.checked); // Afficher si coché
        checkbox.addEventListener("change", function () {
            toggleQuantityVisibility(this, this.checked);
            // Coche automatiquement le plat correspondant si une garniture ou un complément est coché
            const platId = this.dataset.platId;
            const platCheckbox = document.querySelector(
                `.plat-checkbox[value="${platId}"]`
            );
            if (this.checked && platCheckbox && !platCheckbox.checked) {
                platCheckbox.checked = true;
                platCheckbox.dispatchEvent(new Event("change")); // Déclencher l'événement pour gérer l'état du plat
            }
        });
    });

    garnitureCheckboxes.forEach((checkbox) => {
        toggleQuantityVisibility(checkbox, checkbox.checked); // Afficher si coché
        checkbox.addEventListener("change", function () {
            toggleQuantityVisibility(this, this.checked);
            // Coche automatiquement le plat correspondant si une garniture ou un complément est coché
            const platId = this.dataset.platId;
            const platCheckbox = document.querySelector(
                `.plat-checkbox[value="${platId}"]`
            );
            if (this.checked && platCheckbox && !platCheckbox.checked) {
                platCheckbox.checked = true;
                platCheckbox.dispatchEvent(new Event("change")); // Déclencher l'événement pour gérer l'état du plat
            }
        });
    });

    // Gérer la désélection des garnitures et compléments lorsque le plat est décoché
    platCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            const platId = this.value;
            const complementCheckboxes = document.querySelectorAll(
                `.complement-checkbox[data-plat-id="${platId}"]`
            );
            const garnitureCheckboxes = document.querySelectorAll(
                `.garniture-checkbox[data-plat-id="${platId}"]`
            );

            if (!this.checked) {
                // Décocher et désactiver les garnitures et compléments associés
                complementCheckboxes.forEach((box) => {
                    box.checked = false;
                    toggleQuantityVisibility(box, false); // Masquer la quantité
                });

                garnitureCheckboxes.forEach((box) => {
                    box.checked = false;
                    toggleQuantityVisibility(box, false); // Masquer la quantité
                });
            }
        });
    });
});














