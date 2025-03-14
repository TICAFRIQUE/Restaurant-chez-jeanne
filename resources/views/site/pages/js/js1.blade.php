    {{-- <script>
        function increaseValue(button) {
            // Récupérer le parent le plus proche contenant le champ input
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value);
            input.value = currentValue + 1;
        }

        function decreaseValue(button) {
            // Récupérer le parent le plus proche contenant le champ input
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            // Coche automatiquement le plat si un complément ou une garniture est sélectionné
            function checkPlat(checkbox) {
                const platId = checkbox.getAttribute('data-plat-id');
                const platCheckbox = document.getElementById(`plat_${platId}`);
                if (platCheckbox) {
                    platCheckbox.checked = true;
                }
            }

            // Décoche les compléments et garnitures associés si un plat est décoché
            function uncheckPlatSelections(platId) {
                const complementCheckboxes = document.querySelectorAll(
                    `.complement-checkbox[data-plat-id="${platId}"]`);
                complementCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
                const garnitureCheckboxes = document.querySelectorAll(
                    `.garniture-checkbox[data-plat-id="${platId}"]`);
                garnitureCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            }

            const complementCheckboxes = document.querySelectorAll('.complement-checkbox');
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    checkPlat(this);
                });
            });

            const garnitureCheckboxes = document.querySelectorAll('.garniture-checkbox');
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    checkPlat(this);
                });
            });

            const platCheckboxes = document.querySelectorAll('.plat-checkbox');
            platCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const platId = checkbox.value;
                    if (!checkbox.checked) {
                        uncheckPlatSelections(platId);
                    }
                });
            });
        });
    </script> --}}

    {{-- <script>
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value);
            input.value = currentValue + 1;
        }

        function decreaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Coche automatiquement le plat si un complément ou une garniture est sélectionné
            function checkPlat(checkbox) {
                const platId = checkbox.getAttribute('data-plat-id');
                const platCheckbox = document.getElementById(`plat_${platId}`);
                if (platCheckbox) {
                    platCheckbox.checked = true;
                    toggleQuantityVisibility(platCheckbox, true);
                }
            }

            // Décoche les compléments et garnitures associés si un plat est décoché
            function uncheckPlatSelections(platId) {
                const complementCheckboxes = document.querySelectorAll(
                    `.complement-checkbox[data-plat-id="${platId}"]`
                );
                complementCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                    toggleQuantityVisibility(checkbox, false);
                });

                const garnitureCheckboxes = document.querySelectorAll(
                    `.garniture-checkbox[data-plat-id="${platId}"]`
                );
                garnitureCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                    toggleQuantityVisibility(checkbox, false);
                });
            }

            // Affiche ou masque l'input de quantité selon la sélection
            function toggleQuantityVisibility(checkbox, isVisible) {
                const parent = checkbox.closest(".form-check");
                const quantityWrapper = parent.querySelector(".product-quantity");
                if (quantityWrapper) {
                    quantityWrapper.style.display = isVisible ? "block" : "none";
                }
            }

            // Gestion des événements sur les compléments
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les garnitures
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les plats
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            platCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    const platId = checkbox.value;
                    toggleQuantityVisibility(checkbox, checkbox.checked);
                    if (!checkbox.checked) {
                        uncheckPlatSelections(platId);
                    }
                });
                // Masquer les quantités par défaut
                toggleQuantityVisibility(checkbox, false);
            });
        });
    </script> --}}


    {{-- <script>
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value);
            input.value = currentValue + 1;
        }

        function decreaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Masque ou affiche le champ de quantité selon la sélection
            function toggleQuantityVisibility(checkbox, isVisible) {
                const parent = checkbox.closest(".form-check");
                const quantityWrapper = parent.querySelector(".product-quantity");
                if (quantityWrapper) {
                    quantityWrapper.style.display = isVisible ? "block" : "none";
                }
            }

            // Gère l'état des compléments et garnitures lorsque leur checkbox est cochée
            function checkPlat(checkbox) {
                const platId = checkbox.getAttribute('data-plat-id');
                const platCheckbox = document.getElementById(`plat_${platId}`);
                if (platCheckbox) {
                    platCheckbox.checked = true;
                    toggleQuantityVisibility(platCheckbox, true);
                }
            }

            // Décoche les compléments et garnitures associés si un plat est décoché
            function uncheckPlatSelections(platId) {
                const complementCheckboxes = document.querySelectorAll(
                    `.complement-checkbox[data-plat-id="${platId}"]`
                );
                complementCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                    toggleQuantityVisibility(checkbox, false);
                });

                const garnitureCheckboxes = document.querySelectorAll(
                    `.garniture-checkbox[data-plat-id="${platId}"]`
                );
                garnitureCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                    toggleQuantityVisibility(checkbox, false);
                });
            }

            // Gestion des événements sur les compléments
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les garnitures
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les plats
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            platCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    const platId = checkbox.value;
                    toggleQuantityVisibility(checkbox, checkbox.checked);
                    if (!checkbox.checked) {
                        uncheckPlatSelections(platId);
                    }
                });
                // Masquer les quantités par défaut
                toggleQuantityVisibility(checkbox, false);
            });

            // Masquer toutes les quantités au chargement initial
            document.querySelectorAll(".product-quantity").forEach(function(quantityWrapper) {
                quantityWrapper.style.display = "none";
            });
        });
    </script> --}}

    {{-- 
    <script>
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value);
            input.value = currentValue + 1;

            updateComplementGarnitureLimits(input);
        }

        function decreaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }

            updateComplementGarnitureLimits(input);
        }

        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox").value;
            const quantity = parseInt(input.value);

            // Update complements
            const complementCheckboxes = document.querySelectorAll(
                `.complement-checkbox[data-plat-id="${platId}"]`
            );
            manageSelectionLimits(complementCheckboxes, quantity);

            // Update garnitures
            const garnitureCheckboxes = document.querySelectorAll(
                `.garniture-checkbox[data-plat-id="${platId}"]`
            );
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }

        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = 0;

            checkboxes.forEach((checkbox) => {
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;

                checkbox.addEventListener("change", function() {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Masque ou affiche le champ de quantité selon la sélection
            function toggleQuantityVisibility(checkbox, isVisible) {
                const parent = checkbox.closest(".form-check");
                const quantityWrapper = parent.querySelector(".product-quantity");
                if (quantityWrapper) {
                    quantityWrapper.style.display = isVisible ? "block" : "none";
                }
            }

            // Gère l'état des compléments et garnitures lorsque leur checkbox est cochée
            function checkPlat(checkbox) {
                const platId = checkbox.getAttribute("data-plat-id");
                const platCheckbox = document.getElementById(`plat_${platId}`);
                if (platCheckbox) {
                    platCheckbox.checked = true;
                    toggleQuantityVisibility(platCheckbox, true);
                }
            }

            // Décoche les compléments et garnitures associés si un plat est décoché
            function uncheckPlatSelections(platId) {
                const complementCheckboxes = document.querySelectorAll(
                    `.complement-checkbox[data-plat-id="${platId}"]`
                );
                complementCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                    toggleQuantityVisibility(checkbox, false);
                });

                const garnitureCheckboxes = document.querySelectorAll(
                    `.garniture-checkbox[data-plat-id="${platId}"]`
                );
                garnitureCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                    toggleQuantityVisibility(checkbox, false);
                });
            }

            // Gestion des événements sur les compléments
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les garnitures
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les plats
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            platCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    const platId = checkbox.value;
                    toggleQuantityVisibility(checkbox, checkbox.checked);
                    if (!checkbox.checked) {
                        uncheckPlatSelections(platId);
                    } else {
                        const input = checkbox.closest(".form-check").querySelector(
                            ".cart-plus-minus-box");
                        updateComplementGarnitureLimits(input);
                    }
                });
                // Masquer les quantités par défaut
                toggleQuantityVisibility(checkbox, false);
            });

            // Masquer toutes les quantités au chargement initial
            document.querySelectorAll(".product-quantity").forEach(function(quantityWrapper) {
                quantityWrapper.style.display = "none";
            });
        });
    </script> --}}

    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            /**
             * Augmente la valeur de la quantité d'un plat.
             * @param {HTMLElement} button - Bouton d'augmentation.
             */
            function increaseValue(button) {
                const input = button.parentElement.querySelector(".cart-plus-minus-box");
                if (!input) return;

                let currentValue = parseInt(input.value, 10) || 0;
                input.value = currentValue + 1;

                updateComplementGarnitureLimits(input);
            }

            /**
             * Diminue la valeur de la quantité d'un plat.
             * @param {HTMLElement} button - Bouton de diminution.
             */
            function decreaseValue(button) {
                const input = button.parentElement.querySelector(".cart-plus-minus-box");
                if (!input) return;

                let currentValue = parseInt(input.value, 10) || 0;
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                    updateComplementGarnitureLimits(input);
                }
            }

            /**
             * Met à jour les limites des compléments et garnitures.
             * @param {HTMLInputElement} input - Champ de quantité du plat.
             */
            function updateComplementGarnitureLimits(input) {
                const formCheck = input.closest(".form-check");
                if (!formCheck) return;

                const platId = formCheck.querySelector(".plat-checkbox")?.value;
                const quantity = parseInt(input.value, 10) || 0;

                if (!platId) return;

                // Mise à jour des limites pour les compléments
                const complementCheckboxes = document.querySelectorAll(
                    `.complement-checkbox[data-plat-id="${platId}"]`
                );
                manageComplementGarnitureSelection(complementCheckboxes, quantity);

                // Mise à jour des limites pour les garnitures
                const garnitureCheckboxes = document.querySelectorAll(
                    `.garniture-checkbox[data-plat-id="${platId}"]`
                );
                manageComplementGarnitureSelection(garnitureCheckboxes, quantity);
            }

            /**
             * Gère la sélection et les limites pour les compléments ou garnitures.
             * @param {NodeList} checkboxes - Liste des cases à cocher.
             * @param {number} limit - Quantité totale autorisée.
             */
            function manageComplementGarnitureSelection(checkboxes, limit) {
                let selectedTotal = 0;

                checkboxes.forEach((checkbox) => {
                    const associatedQuantity = checkbox.closest(".form-check").querySelector(
                        ".cart-plus-minus-box");

                    // Mise à jour de l'état des checkboxes en fonction du total sélectionné
                    checkbox.disabled = selectedTotal >= limit && !checkbox.checked;

                    checkbox.addEventListener("change", function() {
                        selectedTotal = 0;
                        checkboxes.forEach((box) => {
                            const quantityInput = box.closest(".form-check").querySelector(
                                ".cart-plus-minus-box");
                            const currentQuantity = parseInt(quantityInput?.value || 0, 10);

                            if (box.checked) {
                                selectedTotal += currentQuantity;
                            }
                        });

                        // Désactive les checkboxes si la limite est atteinte
                        checkboxes.forEach((box) => {
                            box.disabled = selectedTotal >= limit && !box.checked;
                        });
                    });

                    // Gestion des quantités associées
                    associatedQuantity?.addEventListener("change", function() {
                        const newQuantity = parseInt(this.value, 10) || 0;

                        selectedTotal = 0;
                        checkboxes.forEach((box) => {
                            const quantityInput = box.closest(".form-check").querySelector(
                                ".cart-plus-minus-box");
                            const currentQuantity = parseInt(quantityInput?.value || 0, 10);

                            if (box.checked) {
                                selectedTotal += currentQuantity;
                            }
                        });

                        // Désactive les checkboxes si la limite est atteinte
                        checkboxes.forEach((box) => {
                            box.disabled = selectedTotal >= limit && !box.checked;
                        });
                    });
                });
            }

            /**
             * Masque ou affiche la quantité associée à un plat.
             * @param {HTMLElement} checkbox - Checkbox du plat.
             * @param {boolean} isVisible - Affiche si true, sinon masque.
             */
            function toggleQuantityVisibility(checkbox, isVisible) {
                const parent = checkbox.closest(".form-check");
                const quantityWrapper = parent.querySelector(".product-quantity");
                if (quantityWrapper) {
                    quantityWrapper.style.display = isVisible ? "block" : "none";
                }
            }

            // Initialisation des événements
            document.querySelectorAll(".plat-checkbox").forEach((checkbox) => {
                checkbox.addEventListener("change", function() {
                    const platId = checkbox.value;
                    toggleQuantityVisibility(checkbox, checkbox.checked);

                    if (checkbox.checked) {
                        const input = checkbox.closest(".form-check").querySelector(
                            ".cart-plus-minus-box");
                        updateComplementGarnitureLimits(input);
                    }
                });

                // Masquer la quantité par défaut
                toggleQuantityVisibility(checkbox, false);
            });

            // Masquer toutes les quantités au chargement initial
            document.querySelectorAll(".product-quantity").forEach((quantityWrapper) => {
                quantityWrapper.style.display = "none";
            });
        });
    </script> --}}


    {{-- ############# 10-12-2024--16h23  ########### --}}

    {{-- <script>
                        // Fonction pour augmenter la quantité du plat
                        function increaseValue(button) {
                            const input = button.parentElement.querySelector(".cart-plus-minus-box");
                            let currentValue = parseInt(input.value, 10) || 0;
                            input.value = currentValue + 1;

                            updateComplementGarnitureLimits(input);
                        }

                        // Fonction pour diminuer la quantité du plat
                        function decreaseValue(button) {
                            const input = button.parentElement.querySelector(".cart-plus-minus-box");
                            let currentValue = parseInt(input.value, 10) || 0;
                            if (currentValue > 1) {
                                input.value = currentValue - 1;
                                updateComplementGarnitureLimits(input);
                            }
                        }

                        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
                        function updateComplementGarnitureLimits(input) {
                            const platId = input.closest(".form-check").querySelector(".plat-checkbox").value;
                            const quantity = parseInt(input.value);

                            // Mettre à jour les compléments
                            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
                            manageSelectionLimits(complementCheckboxes, quantity);

                            // Mettre à jour les garnitures
                            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
                            manageSelectionLimits(garnitureCheckboxes, quantity);
                        }

                        // Fonction pour gérer la limite de sélection des compléments et garnitures
                        function manageSelectionLimits(checkboxes, limit) {
                            let selectedCount = 0;

                            checkboxes.forEach((checkbox) => {
                                checkbox.disabled = selectedCount >= limit && !checkbox.checked;

                                checkbox.addEventListener("change", function() {
                                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                                    checkboxes.forEach((box) => {
                                        box.disabled = selectedCount >= limit && !box.checked;
                                    });
                                });
                            });
                        }

                        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
                        function toggleQuantityVisibility(checkbox, isVisible) {
                            const parent = checkbox.closest(".form-check");
                            const quantityWrapper = parent.querySelector(".product-quantity");
                            if (quantityWrapper) {
                                quantityWrapper.style.display = isVisible ? "block" : "none";
                            }
                        }

                        // Fonction pour vérifier si un plat est sélectionné et afficher la quantité
                        function checkPlat(checkbox) {
                            const platId = checkbox.getAttribute("data-plat-id");
                            const platCheckbox = document.getElementById(`plat_${platId}`);
                            if (platCheckbox) {
                                platCheckbox.checked = true;
                                toggleQuantityVisibility(platCheckbox, true);
                            }
                        }

                        // Fonction pour décocher les compléments et garnitures associés à un plat
                        function uncheckPlatSelections(platId) {
                            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
                            complementCheckboxes.forEach(function(checkbox) {
                                checkbox.checked = false;
                                toggleQuantityVisibility(checkbox, false);
                            });

                            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
                            garnitureCheckboxes.forEach(function(checkbox) {
                                checkbox.checked = false;
                                toggleQuantityVisibility(checkbox, false);
                            });
                        }

                        // Initialisation des événements lors du chargement du document
                        document.addEventListener("DOMContentLoaded", function() {
                            // Gestion des événements sur les compléments
                            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
                            complementCheckboxes.forEach(function(checkbox) {
                                checkbox.addEventListener("change", function() {
                                    checkPlat(this);
                                    toggleQuantityVisibility(this, this.checked);
                                });
                            });

                            // Gestion des événements sur les garnitures
                            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
                            garnitureCheckboxes.forEach(function(checkbox) {
                                checkbox.addEventListener("change", function() {
                                    checkPlat(this);
                                    toggleQuantityVisibility(this, this.checked);
                                });
                            });

                            // Gestion des événements sur les plats
                            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
                            platCheckboxes.forEach(function(checkbox) {
                                checkbox.addEventListener("change", function() {
                                    const platId = checkbox.value;
                                    toggleQuantityVisibility(checkbox, checkbox.checked);
                                    if (!checkbox.checked) {
                                        uncheckPlatSelections(platId);
                                    } else {
                                        const input = checkbox.closest(".form-check").querySelector(
                                            ".cart-plus-minus-box");
                                        updateComplementGarnitureLimits(input);
                                    }
                                });

                                // Masquer les quantités par défaut
                                toggleQuantityVisibility(checkbox, false);
                            });

                            // Masquer toutes les quantités au chargement initial
                            document.querySelectorAll(".product-quantity").forEach(function(quantityWrapper) {
                                quantityWrapper.style.display = "none";
                            });
                        });
                    </script> --}}


    {{-- ############# 10-12-2024--16h23  ########### --}}


    {{-- <script>
        // Fonction pour augmenter la quantité du plat
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;

            updateComplementGarnitureLimits(input);
        }

        // Fonction pour diminuer la quantité du plat
        function decreaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            if (currentValue > 1) {
                input.value = currentValue - 1;
                updateComplementGarnitureLimits(input);
            }
        }

        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            // Vérifier si platId est valide
            if (!platId) {
                console.error("Plat ID introuvable");
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Mettre à jour les compléments
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(complementCheckboxes, quantity);

            // Mettre à jour les garnitures
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }

        // Fonction pour gérer la limite de sélection des compléments et garnitures
        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = 0;

            checkboxes.forEach((checkbox) => {
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;

                checkbox.addEventListener("change", function() {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }

        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";
            }
        }

        // Fonction pour vérifier si un plat est sélectionné et afficher la quantité
        function checkPlat(checkbox) {
            const platId = checkbox.getAttribute("data-plat-id");
            const platCheckbox = document.getElementById(`plat_${platId}`);
            if (platCheckbox) {
                platCheckbox.checked = true;
                toggleQuantityVisibility(platCheckbox, true);
            }
        }

        // Fonction pour décocher les compléments et garnitures associés à un plat
        function uncheckPlatSelections(platId) {
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false);
            });

            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false);
            });
        }

        // Initialisation des événements lors du chargement du document
        document.addEventListener("DOMContentLoaded", function() {
            // Gestion des événements sur les compléments
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les garnitures
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les plats
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            platCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    const platId = checkbox.value;
                    toggleQuantityVisibility(checkbox, checkbox.checked);
                    if (!checkbox.checked) {
                        uncheckPlatSelections(platId);
                    } else {
                        const input = checkbox.closest(".form-check").querySelector(
                            ".cart-plus-minus-box");
                        updateComplementGarnitureLimits(input);
                    }
                });

                // Masquer les quantités par défaut
                toggleQuantityVisibility(checkbox, false);
            });

            // Masquer toutes les quantités au chargement initial
            document.querySelectorAll(".product-quantity").forEach(function(quantityWrapper) {
                quantityWrapper.style.display = "none";
            });
        });
    </script> --}}


    {{-- <script>
        // Fonction pour augmenter la quantité du plat
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;

            updateComplementGarnitureLimits(input);
        }

        // Fonction pour diminuer la quantité du plat
        function decreaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            if (currentValue > 1) {
                input.value = currentValue - 1;
                updateComplementGarnitureLimits(input);
            }
        }

        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            // Vérifier si platId est valide
            if (!platId) {
                console.error("Plat ID introuvable");
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Mettre à jour les compléments
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(complementCheckboxes, quantity);

            // Mettre à jour les garnitures
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }

        // Fonction pour gérer la limite de sélection des compléments et garnitures
        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = 0;

            checkboxes.forEach((checkbox) => {
                // Si le plat est sélectionné, activer les compléments et garnitures
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;

                checkbox.addEventListener("change", function() {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }

        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";
            }
        }

        // Fonction pour vérifier si un plat est sélectionné et afficher la quantité
        function checkPlat(checkbox) {
            const platId = checkbox.getAttribute("data-plat-id");
            const platCheckbox = document.getElementById(`plat_${platId}`);
            if (platCheckbox) {
                platCheckbox.checked = true;
                toggleQuantityVisibility(platCheckbox, true);
            }
        }

        // Fonction pour décocher les compléments et garnitures associés à un plat
        function uncheckPlatSelections(platId) {
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                checkbox.disabled = true; // Désactiver le complément
                toggleQuantityVisibility(checkbox, false);
            });

            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                checkbox.disabled = true; // Désactiver la garniture
                toggleQuantityVisibility(checkbox, false);
            });
        }

        // Fonction pour activer/désactiver les garnitures et compléments en fonction de la sélection du plat
        function handlePlatSelection(checkbox) {
            const platId = checkbox.value;

            // Si le plat est sélectionné, activer les compléments et garnitures
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);

            if (checkbox.checked) {
                // Activer les compléments et garnitures
                complementCheckboxes.forEach(checkbox => checkbox.disabled = false);
                garnitureCheckboxes.forEach(checkbox => checkbox.disabled = false);
                // Mettre à jour les limites des compléments et garnitures
                const input = checkbox.closest(".form-check").querySelector(".cart-plus-minus-box");
                updateComplementGarnitureLimits(input);
            } else {
                // Désactiver les compléments et garnitures
                complementCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true;
                    checkbox.checked = false; // Décocher également les cases
                });
                garnitureCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true;
                    checkbox.checked = false; // Décocher également les cases
                });
            }

            toggleQuantityVisibility(checkbox, checkbox.checked);
        }

        // Initialisation des événements lors du chargement du document
        document.addEventListener("DOMContentLoaded", function() {
            // Gestion des événements sur les compléments
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les compléments par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les garnitures
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les garnitures par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les plats
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            platCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    handlePlatSelection(
                        checkbox); // Gérer l'activation/désactivation des compléments et garnitures
                    if (!checkbox.checked) {
                        const platId = checkbox.value;
                        uncheckPlatSelections(
                            platId
                            ); // Décocher les compléments et garnitures lorsque le plat est décoché
                    }
                });

                // Masquer les quantités par défaut
                toggleQuantityVisibility(checkbox, false);
            });

            // Masquer toutes les quantités au chargement initial
            document.querySelectorAll(".product-quantity").forEach(function(quantityWrapper) {
                quantityWrapper.style.display = "none";
            });
        });
    </script> --}}

    {{-- ############# 10-12-2024--19h  ########### --}}
    {{-- <script>
        // Fonction pour augmenter la quantité du plat
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;

            updateComplementGarnitureLimits(input);
        }

        // Fonction pour diminuer la quantité du plat
        function decreaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            if (currentValue > 1) {
                input.value = currentValue - 1;

                // Mise à jour des compléments et garnitures lorsque la quantité est diminuée
                updateComplementGarnitureLimits(input);

                // Décoche et grise le dernier élément sélectionné parmi les compléments et garnitures
                uncheckAndDisableLastSelected(input);
            }
        }

        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            // Vérifier si platId est valide
            if (!platId) {
                console.error("Plat ID introuvable");
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Mettre à jour les compléments
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(complementCheckboxes, quantity);

            // Mettre à jour les garnitures
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }

        // Fonction pour gérer la limite de sélection des compléments et garnitures
        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = 0;

            checkboxes.forEach((checkbox) => {
                // Si le plat est sélectionné, activer les compléments et garnitures
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;

                checkbox.addEventListener("change", function() {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }

        // Fonction pour décocher et griser le dernier élément sélectionné parmi les compléments et garnitures
        function uncheckAndDisableLastSelected(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                return;
            }

            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);

            // Récupérer tous les éléments sélectionnés parmi les compléments et garnitures
            const selectedComplementCheckboxes = Array.from(complementCheckboxes).filter((checkbox) => checkbox.checked);
            const selectedGarnitureCheckboxes = Array.from(garnitureCheckboxes).filter((checkbox) => checkbox.checked);

            // Décoche et désactive le dernier élément sélectionné
            if (selectedComplementCheckboxes.length > 0) {
                const lastSelectedComplement = selectedComplementCheckboxes[selectedComplementCheckboxes.length - 1];
                lastSelectedComplement.checked = false;
                lastSelectedComplement.disabled = true;
                toggleQuantityVisibility(lastSelectedComplement, false); // Masquer la quantité
            }

            if (selectedGarnitureCheckboxes.length > 0) {
                const lastSelectedGarniture = selectedGarnitureCheckboxes[selectedGarnitureCheckboxes.length - 1];
                lastSelectedGarniture.checked = false;
                lastSelectedGarniture.disabled = true;
                toggleQuantityVisibility(lastSelectedGarniture, false); // Masquer la quantité
            }
        }

        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";
            }
        }

        // Fonction pour vérifier si un plat est sélectionné et afficher la quantité
        function checkPlat(checkbox) {
            const platId = checkbox.getAttribute("data-plat-id");
            const platCheckbox = document.getElementById(`plat_${platId}`);
            if (platCheckbox) {
                platCheckbox.checked = true;
                toggleQuantityVisibility(platCheckbox, true);
            }
        }

        // Fonction pour décocher les compléments et garnitures associés à un plat
        function uncheckPlatSelections(platId) {
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                checkbox.disabled = true; // Désactiver le complément
                toggleQuantityVisibility(checkbox, false);
            });

            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                checkbox.disabled = true; // Désactiver la garniture
                toggleQuantityVisibility(checkbox, false);
            });
        }

        // Fonction pour activer/désactiver les garnitures et compléments en fonction de la sélection du plat
        function handlePlatSelection(checkbox) {
            const platId = checkbox.value;

            // Si le plat est sélectionné, activer les compléments et garnitures
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);

            if (checkbox.checked) {
                // Activer les compléments et garnitures
                complementCheckboxes.forEach(checkbox => checkbox.disabled = false);
                garnitureCheckboxes.forEach(checkbox => checkbox.disabled = false);
                // Mettre à jour les limites des compléments et garnitures
                const input = checkbox.closest(".form-check").querySelector(".cart-plus-minus-box");
                updateComplementGarnitureLimits(input);
            } else {
                // Désactiver les compléments et garnitures
                complementCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true;
                    checkbox.checked = false; // Décocher également les cases
                });
                garnitureCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true;
                    checkbox.checked = false; // Décocher également les cases
                });
            }

            toggleQuantityVisibility(checkbox, checkbox.checked);
        }

        // Initialisation des événements lors du chargement du document
        document.addEventListener("DOMContentLoaded", function() {
            // Gestion des événements sur les compléments
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les compléments par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les garnitures
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les garnitures par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les plats
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            platCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    handlePlatSelection(
                        checkbox); // Gérer l'activation/désactivation des compléments et garnitures
                    if (!checkbox.checked) {
                        const platId = checkbox.value;
                        uncheckPlatSelections(
                            platId
                            ); // Décocher les compléments et garnitures lorsque le plat est décoché
                    }
                });

                // Masquer les quantités par défaut
                toggleQuantityVisibility(checkbox, false);
            });

            // Masquer toutes les quantités au chargement initial
            document.querySelectorAll(".product-quantity").forEach(function(quantityWrapper) {
                quantityWrapper.style.display = "none";
            });
        });
    </script> --}}
    {{-- ############# 10-12-2024--19h  ########### --}}

    {{-- 
    <script>
        // Fonction pour augmenter la quantité du plat
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;

            updateComplementGarnitureLimits(input);
        }

        // Fonction pour diminuer la quantité du plat
        function decreaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            if (currentValue > 1) {
                input.value = currentValue - 1;

                // Mise à jour des compléments et garnitures lorsque la quantité est diminuée
                updateComplementGarnitureLimits(input);

                // Décoche le dernier élément sélectionné parmi les compléments et garnitures
                uncheckLastSelected(input);
            }
        }

        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            console.log(platId);


            // Vérifier si platId est valide
            if (!platId) {
                console.error("Plat ID introuvable");
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Mettre à jour les compléments
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(complementCheckboxes, quantity);

            // Mettre à jour les garnitures
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }

        // Fonction pour gérer la limite de sélection des compléments et garnitures
        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = 0;

            checkboxes.forEach((checkbox) => {
                // Si le plat est sélectionné, activer les compléments et garnitures
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;

                checkbox.addEventListener("change", function() {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }

        // Fonction pour décocher le dernier élément sélectionné parmi les compléments et garnitures
        function uncheckLastSelected(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                return;
            }

            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);

            // Récupérer tous les éléments sélectionnés parmi les compléments et garnitures
            const selectedComplementCheckboxes = Array.from(complementCheckboxes).filter((checkbox) => checkbox.checked);
            const selectedGarnitureCheckboxes = Array.from(garnitureCheckboxes).filter((checkbox) => checkbox.checked);

            // Décoche le dernier élément sélectionné parmi les compléments
            if (selectedComplementCheckboxes.length > 0) {
                const lastSelectedComplement = selectedComplementCheckboxes[selectedComplementCheckboxes.length - 1];
                lastSelectedComplement.checked = false;
                toggleQuantityVisibility(lastSelectedComplement, false); // Masquer la quantité
            }

            // Décoche le dernier élément sélectionné parmi les garnitures
            if (selectedGarnitureCheckboxes.length > 0) {
                const lastSelectedGarniture = selectedGarnitureCheckboxes[selectedGarnitureCheckboxes.length - 1];
                lastSelectedGarniture.checked = false;
                toggleQuantityVisibility(lastSelectedGarniture, false); // Masquer la quantité
            }
        }

        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";
            }
        }

        // Fonction pour vérifier si un plat est sélectionné et afficher la quantité
        function checkPlat(checkbox) {
            const platId = checkbox.getAttribute("data-plat-id");
            const platCheckbox = document.getElementById(`plat_${platId}`);
            if (platCheckbox) {
                platCheckbox.checked = true;
                toggleQuantityVisibility(platCheckbox, true);
            }
        }

        // Fonction pour décocher les compléments et garnitures associés à un plat
        function uncheckPlatSelections(platId) {
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false);
            });

            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false);
            });
        }

        // Fonction pour activer/désactiver les garnitures et compléments en fonction de la sélection du plat
        function handlePlatSelection(checkbox) {
            const platId = checkbox.value;

            // Si le plat est sélectionné, activer les compléments et garnitures
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);

            if (checkbox.checked) {
                // Activer les compléments et garnitures
                complementCheckboxes.forEach(checkbox => checkbox.disabled = false);
                garnitureCheckboxes.forEach(checkbox => checkbox.disabled = false);
                // Mettre à jour les limites des compléments et garnitures
                const input = checkbox.closest(".form-check").querySelector(".cart-plus-minus-box");
                updateComplementGarnitureLimits(input);
            } else {
                // Désactiver les compléments et garnitures
                complementCheckboxes.forEach(checkbox => {
                    checkbox.checked = false; // Décocher également les cases
                });
                garnitureCheckboxes.forEach(checkbox => {
                    checkbox.checked = false; // Décocher également les cases
                });
            }

            toggleQuantityVisibility(checkbox, checkbox.checked);
        }

        // Initialisation des événements lors du chargement du document
        document.addEventListener("DOMContentLoaded", function() {
            // Gestion des événements sur les compléments
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les compléments par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les garnitures
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les garnitures par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les plats
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            platCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    handlePlatSelection(
                    checkbox); // Gérer l'activation/désactivation des compléments et garnitures
                    if (!checkbox.checked) {
                        const platId = checkbox.value;
                        uncheckPlatSelections(
                        platId); // Décocher les compléments et garnitures lorsque le plat est décoché
                    }
                });

                // Masquer les quantités par défaut
                toggleQuantityVisibility(checkbox, false);
            });

            // Masquer toutes les quantités au chargement initial
            document.querySelectorAll(".product-quantity").forEach(function(quantityWrapper) {
                quantityWrapper.style.display = "none";
            });
        });
    </script> --}}


    {{-- <script>
        // Fonction pour augmenter la quantité du plat
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;

            updateComplementGarnitureLimits(input);
        }

        // Fonction pour diminuer la quantité du plat
        function decreaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            if (currentValue > 1) {
                input.value = currentValue - 1;

                // Mise à jour des compléments et garnitures lorsque la quantité est diminuée
                updateComplementGarnitureLimits(input);

                // Décoche le dernier élément sélectionné parmi les compléments et garnitures
                uncheckLastSelected(input);
            }
        }

        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            // Vérifier si platId est valide
            if (!platId) {
                console.error("Plat ID introuvable");
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Mettre à jour les compléments
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(complementCheckboxes, quantity);

            // Mettre à jour les garnitures
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }

        // Fonction pour gérer la limite de sélection des compléments et garnitures
        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = 0;

            checkboxes.forEach((checkbox) => {
                // Si le plat est sélectionné, activer les compléments et garnitures
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;

                checkbox.addEventListener("change", function() {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }

        // Fonction pour décocher le dernier élément sélectionné parmi les compléments et garnitures
        function uncheckLastSelected(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                return;
            }

            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);

            // Récupérer tous les éléments sélectionnés parmi les compléments et garnitures
            const selectedComplementCheckboxes = Array.from(complementCheckboxes).filter((checkbox) => checkbox.checked);
            const selectedGarnitureCheckboxes = Array.from(garnitureCheckboxes).filter((checkbox) => checkbox.checked);

            // Décoche le dernier élément sélectionné parmi les compléments
            if (selectedComplementCheckboxes.length > 0) {
                const lastSelectedComplement = selectedComplementCheckboxes[selectedComplementCheckboxes.length - 1];
                lastSelectedComplement.checked = false;
                toggleQuantityVisibility(lastSelectedComplement, false); // Masquer la quantité
            }

            // Décoche le dernier élément sélectionné parmi les garnitures
            if (selectedGarnitureCheckboxes.length > 0) {
                const lastSelectedGarniture = selectedGarnitureCheckboxes[selectedGarnitureCheckboxes.length - 1];
                lastSelectedGarniture.checked = false;
                toggleQuantityVisibility(lastSelectedGarniture, false); // Masquer la quantité
            }
        }

        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";
            }
        }

        // Fonction pour vérifier si un plat est sélectionné et afficher la quantité
        function checkPlat(checkbox) {
            const platId = checkbox.getAttribute("data-plat-id");
            const platCheckbox = document.getElementById(`plat_${platId}`);
            if (platCheckbox) {
                platCheckbox.checked = true;
                toggleQuantityVisibility(platCheckbox, true);
            }
        }

        // Fonction pour décocher les compléments et garnitures associés à un plat
        function uncheckPlatSelections(platId) {
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false);
            });

            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false);
            });
        }

        // Fonction pour activer/désactiver les garnitures et compléments en fonction de la sélection du plat
        function handlePlatSelection(checkbox) {
            const platId = checkbox.value;

            // Récupérer les compléments et garnitures associés au plat
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);

            if (checkbox.checked) {
                // Activer les compléments et garnitures
                complementCheckboxes.forEach(checkbox => checkbox.disabled = false);
                garnitureCheckboxes.forEach(checkbox => checkbox.disabled = false);

                // Mettre à jour les limites des compléments et garnitures
                const input = checkbox.closest(".form-check").querySelector(".cart-plus-minus-box");
                updateComplementGarnitureLimits(input);
            } else {
                // Désactiver et décocher les compléments et garnitures
                complementCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true; // Désactiver la case
                    checkbox.checked = false; // Décocher la case
                    toggleQuantityVisibility(checkbox, false); // Masquer les quantités associées
                });

                garnitureCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true; // Désactiver la case
                    checkbox.checked = false; // Décocher la case
                    toggleQuantityVisibility(checkbox, false); // Masquer les quantités associées
                });
            }

            // Afficher ou masquer la quantité associée au plat
            toggleQuantityVisibility(checkbox, checkbox.checked);
        }

        // Initialisation des événements lors du chargement du document
        document.addEventListener("DOMContentLoaded", function() {
            // Gestion des événements sur les compléments
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les compléments par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les garnitures
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les garnitures par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les plats
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            platCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    handlePlatSelection(
                    checkbox); // Gérer l'activation/désactivation des compléments et garnitures
                    if (!checkbox.checked) {
                        const platId = checkbox.value;
                        uncheckPlatSelections(
                        platId); // Décocher les compléments et garnitures lorsque le plat est décoché
                    }
                });

                // Masquer les quantités par défaut
                toggleQuantityVisibility(checkbox, false);
            });

            // Masquer toutes les quantités au chargement initial
            document.querySelectorAll(".product-quantity").forEach(function(quantityWrapper) {
                quantityWrapper.style.display = "none";
            });
        });
    </script> --}}


    {{-- <script>
        // Fonction pour augmenter la quantité du plat
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;

            updateComplementGarnitureLimits(input);
        }

        // Fonction pour diminuer la quantité du plat
        function decreaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;

            // Réinitialiser à 1 si la quantité est supérieure à 1
            if (currentValue > 1) {
                input.value = 1; // Réinitialiser à 1

                // Mise à jour des compléments et garnitures lorsque la quantité est diminuée
                updateComplementGarnitureLimits(input);

                // Désactive tous les compléments et garnitures associés
                disableAllComplementGarnitureSelections(input);
            }
        }

        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            // Vérifier si platId est valide
            if (!platId) {
                console.error("Plat ID introuvable");
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Mettre à jour les compléments
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(complementCheckboxes, quantity);

            // Mettre à jour les garnitures
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }

        // Fonction pour gérer la limite de sélection des compléments et garnitures
        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = 0;

            checkboxes.forEach((checkbox) => {
                // Si le plat est sélectionné, activer les compléments et garnitures
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;

                checkbox.addEventListener("change", function() {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }

        // Fonction pour désactiver tous les compléments et garnitures lorsque la quantité du plat est diminuée
        function disableAllComplementGarnitureSelections(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                return;
            }

            // Désactive tous les compléments associés
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false; // Décocher le complément
                toggleQuantityVisibility(checkbox, false); // Masquer la quantité associée
            });

            // Désactive toutes les garnitures associées
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false; // Décocher la garniture
                toggleQuantityVisibility(checkbox, false); // Masquer la quantité associée
            });
        }

        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";
            }
        }

        // Fonction pour vérifier si un plat est sélectionné et afficher la quantité
        function checkPlat(checkbox) {
            const platId = checkbox.getAttribute("data-plat-id");
            const platCheckbox = document.getElementById(`plat_${platId}`);
            if (platCheckbox) {
                platCheckbox.checked = true;
                toggleQuantityVisibility(platCheckbox, true);
            }
        }

        // Fonction pour décocher les compléments et garnitures associés à un plat
        function uncheckPlatSelections(platId) {
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false);
            });

            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false);
            });
        }

        // Fonction pour activer/désactiver les garnitures et compléments en fonction de la sélection du plat
        function handlePlatSelection(checkbox) {
            const platId = checkbox.value;

            // Récupérer les compléments et garnitures associés au plat
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);

            if (checkbox.checked) {
                // Activer les compléments et garnitures
                complementCheckboxes.forEach(checkbox => checkbox.disabled = false);
                garnitureCheckboxes.forEach(checkbox => checkbox.disabled = false);

                // Mettre à jour les limites des compléments et garnitures
                const input = checkbox.closest(".form-check").querySelector(".cart-plus-minus-box");
                updateComplementGarnitureLimits(input);
            } else {
                // Désactiver et décocher les compléments et garnitures
                complementCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true; // Désactiver la case
                    checkbox.checked = false; // Décocher la case
                    toggleQuantityVisibility(checkbox, false); // Masquer les quantités associées
                });

                garnitureCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true; // Désactiver la case
                    checkbox.checked = false; // Décocher la case
                    toggleQuantityVisibility(checkbox, false); // Masquer les quantités associées
                });
            }

            // Afficher ou masquer la quantité associée au plat
            toggleQuantityVisibility(checkbox, checkbox.checked);
        }

        // Initialisation des événements lors du chargement du document
        document.addEventListener("DOMContentLoaded", function() {
            // Gestion des événements sur les compléments
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les compléments par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les garnitures
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les garnitures par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les plats
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            platCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    handlePlatSelection(
                    checkbox); // Gérer l'activation/désactivation des compléments et garnitures
                    if (!checkbox.checked) {
                        const platId = checkbox.value;
                        uncheckPlatSelections(
                        platId); // Décocher les compléments et garnitures lorsque le plat est décoché
                    }
                });

                // Masquer les quantités par défaut
                toggleQuantityVisibility(checkbox, false);
            });

            // Masquer toutes les quantités au chargement initial
            document.querySelectorAll(".product-quantity").forEach(function(quantityWrapper) {
                quantityWrapper.style.display = "none";
            });
        });
    </script> --}}


    {{-- <script>
        // Fonction pour augmenter la quantité du plat
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;
    
            updateComplementGarnitureLimits(input);
        }
    
        // Fonction pour diminuer la quantité du plat
        function decreaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
    
            // Réinitialiser à 1 si la quantité est supérieure à 1
            if (currentValue > 1) {
                input.value = 1; // Réinitialiser à 1
    
                // Mise à jour des compléments et garnitures lorsque la quantité est diminuée
                updateComplementGarnitureLimits(input);
    
                // Désactive tous les compléments et garnitures associés
                disableAllComplementGarnitureSelections(input);
            }
        }
    
        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;
    
            // Vérifier si platId est valide
            if (!platId) {
                console.error("Plat ID introuvable");
                return;
            }
    
            const quantity = parseInt(input.value, 10);
    
            // Mettre à jour les compléments
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(complementCheckboxes, quantity);
    
            // Mettre à jour les garnitures
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }
    
        // Fonction pour gérer la limite de sélection des compléments et garnitures
        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = 0;
    
            checkboxes.forEach((checkbox) => {
                // Si le plat est sélectionné, activer les compléments et garnitures
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;
    
                checkbox.addEventListener("change", function() {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }
    
        // Fonction pour désactiver tous les compléments et garnitures lorsque la quantité du plat est diminuée
        function disableAllComplementGarnitureSelections(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;
    
            if (!platId) {
                return;
            }
    
            // Désactive tous les compléments associés
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;  // Décocher le complément
                toggleQuantityVisibility(checkbox, false); // Masquer la quantité associée
            });
    
            // Désactive toutes les garnitures associées
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;  // Décocher la garniture
                toggleQuantityVisibility(checkbox, false); // Masquer la quantité associée
            });
        }
    
        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";
            }
        }
    
        // Fonction pour vérifier si un plat est sélectionné et afficher la quantité
        function checkPlat(checkbox) {
            const platId = checkbox.getAttribute("data-plat-id");
            const platCheckbox = document.getElementById(`plat_${platId}`);
            if (platCheckbox) {
                platCheckbox.checked = true;
                toggleQuantityVisibility(platCheckbox, true);
            }
        }
    
        // Fonction pour décocher les compléments et garnitures associés à un plat
        function uncheckPlatSelections(platId) {
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false);
            });
    
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false);
            });
        }
    
        // Fonction pour activer/désactiver les garnitures et compléments en fonction de la sélection du plat
        function handlePlatSelection(checkbox) {
            const platId = checkbox.value;
    
            // Récupérer les compléments et garnitures associés au plat
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
    
            if (checkbox.checked) {
                // Activer les compléments et garnitures
                complementCheckboxes.forEach(checkbox => checkbox.disabled = false);
                garnitureCheckboxes.forEach(checkbox => checkbox.disabled = false);
    
                // Mettre à jour les limites des compléments et garnitures
                const input = checkbox.closest(".form-check").querySelector(".cart-plus-minus-box");
                updateComplementGarnitureLimits(input);
            } else {
                // Désactiver et décocher les compléments et garnitures
                complementCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true; // Désactiver la case
                    checkbox.checked = false; // Décocher la case
                    toggleQuantityVisibility(checkbox, false); // Masquer les quantités associées
                });
    
                garnitureCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true; // Désactiver la case
                    checkbox.checked = false; // Décocher la case
                    toggleQuantityVisibility(checkbox, false); // Masquer les quantités associées
                });
            }
    
            // Afficher ou masquer la quantité associée au plat
            toggleQuantityVisibility(checkbox, checkbox.checked);
        }
    
        // Initialisation des événements lors du chargement du document
        document.addEventListener("DOMContentLoaded", function() {
            // Gestion des événements sur les compléments
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les compléments par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });
    
            // Gestion des événements sur les garnitures
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les garnitures par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });
    
            // Gestion des événements sur les plats
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            platCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    handlePlatSelection(checkbox); // Gérer l'activation/désactivation des compléments et garnitures
                    if (!checkbox.checked) {
                        const platId = checkbox.value;
                        uncheckPlatSelections(platId); // Décocher les compléments et garnitures lorsque le plat est décoché
                    }
                });
    
                // Masquer les quantités par défaut
                toggleQuantityVisibility(checkbox, false);
            });
    
            // Masquer toutes les quantités au chargement initial
            document.querySelectorAll(".product-quantity").forEach(function(quantityWrapper) {
                quantityWrapper.style.display = "none";
            });
        });
    </script> --}}


    {{-- <script>
        // Fonction pour augmenter la quantité du plat
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;

            updateComplementGarnitureLimits(input);
        }

        // Fonction pour diminuer la quantité du plat
        function decreaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;

            // Réinitialiser à 1 si la quantité est supérieure à 1
            if (currentValue > 1) {
                input.value = 1; // Réinitialiser à 1

                // Mise à jour des compléments et garnitures lorsque la quantité est diminuée
                updateComplementGarnitureLimits(input);

                // Désactive tous les compléments et garnitures associés
                disableAllComplementGarnitureSelections(input);
            }
        }

        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            // Vérifier si platId est valide
            if (!platId) {
                console.error("Plat ID introuvable");
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Mettre à jour les compléments
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(complementCheckboxes, quantity);

            // Mettre à jour les garnitures
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }

        // Fonction pour gérer la limite de sélection des compléments et garnitures
        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = 0;

            checkboxes.forEach((checkbox) => {
                // Si le plat est sélectionné, activer les compléments et garnitures
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;

                checkbox.addEventListener("change", function() {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }

        // Fonction pour désactiver tous les compléments et garnitures lorsque la quantité du plat est diminuée
        function disableAllComplementGarnitureSelections(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                return;
            }

            // Désactive tous les compléments associés
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false; // Décocher le complément
                toggleQuantityVisibility(checkbox, false); // Masquer la quantité associée
            });

            // Désactive toutes les garnitures associées
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false; // Décocher la garniture
                toggleQuantityVisibility(checkbox, false); // Masquer la quantité associée
            });
        }

        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";

                // Si la quantité est cachée, la réinitialiser à 1
                if (!isVisible) {
                    const quantityInput = parent.querySelector(".cart-plus-minus-box");
                    if (quantityInput) {
                        quantityInput.value = 1; // Réinitialiser à 1 lorsque cachée
                    }
                }
            }
        }

        // Fonction pour vérifier si un plat est sélectionné et afficher la quantité
        function checkPlat(checkbox) {
            const platId = checkbox.getAttribute("data-plat-id");
            const platCheckbox = document.getElementById(`plat_${platId}`);
            if (platCheckbox) {
                platCheckbox.checked = true;
                toggleQuantityVisibility(platCheckbox, true);
            }
        }

        // Fonction pour décocher les compléments et garnitures associés à un plat
        function uncheckPlatSelections(platId) {
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false);
            });

            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false);
            });
        }

        // Fonction pour activer/désactiver les garnitures et compléments en fonction de la sélection du plat
        function handlePlatSelection(checkbox) {
            const platId = checkbox.value;

            // Récupérer les compléments et garnitures associés au plat
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);

            if (checkbox.checked) {
                // Activer les compléments et garnitures
                complementCheckboxes.forEach(checkbox => checkbox.disabled = false);
                garnitureCheckboxes.forEach(checkbox => checkbox.disabled = false);

                // Mettre à jour les limites des compléments et garnitures
                const input = checkbox.closest(".form-check").querySelector(".cart-plus-minus-box");
                updateComplementGarnitureLimits(input);
            } else {
                // Désactiver et décocher les compléments et garnitures
                complementCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true; // Désactiver la case
                    checkbox.checked = false; // Décocher la case
                    toggleQuantityVisibility(checkbox, false); // Masquer les quantités associées
                });

                garnitureCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true; // Désactiver la case
                    checkbox.checked = false; // Décocher la case
                    toggleQuantityVisibility(checkbox, false); // Masquer les quantités associées
                });
            }

            // Afficher ou masquer la quantité associée au plat
            toggleQuantityVisibility(checkbox, checkbox.checked);
        }

        // Initialisation des événements lors du chargement du document
        document.addEventListener("DOMContentLoaded", function() {
            // Gestion des événements sur les compléments
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les compléments par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les garnitures
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les garnitures par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            // Gestion des événements sur les plats
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            platCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    handlePlatSelection(
                    checkbox); // Gérer l'activation/désactivation des compléments et garnitures
                    if (!checkbox.checked) {
                        const platId = checkbox.value;
                        uncheckPlatSelections(
                        platId); // Décocher les compléments et garnitures lorsque le plat est décoché
                    }
                });

                // Masquer les quantités par défaut
                toggleQuantityVisibility(checkbox, false);
            });

            // Masquer toutes les quantités au chargement initial
            document.querySelectorAll(".product-quantity").forEach(function(quantityWrapper) {
                quantityWrapper.style.display = "none";
            });
        });
    </script> --}}


    {{-- <script>
        // Fonction pour augmenter la quantité du plat
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;
    
            updateComplementGarnitureLimits(input);
        }
    
        // Fonction pour diminuer la quantité du plat
        function decreaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
    
            // Ne réinitialise la quantité que si elle est plus grande que 1
            if (currentValue > 1) {
                input.value = 1; // Réinitialiser à 1
    
                // Mise à jour des compléments et garnitures lorsque la quantité est diminuée
                updateComplementGarnitureLimits(input);
    
                // Désactive tous les compléments et garnitures associés
                disableAllComplementGarnitureSelections(input);
            }
        }
    
        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;
    
            // Vérifier si platId est valide
            if (!platId) {
                console.error("Plat ID introuvable");
                return;
            }
    
            const quantity = parseInt(input.value, 10);
    
            // Mettre à jour les compléments
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(complementCheckboxes, quantity);
    
            // Mettre à jour les garnitures
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }
    
        // Fonction pour gérer la limite de sélection des compléments et garnitures
        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = 0;
    
            checkboxes.forEach((checkbox) => {
                // Si le plat est sélectionné, activer les compléments et garnitures
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;
    
                checkbox.addEventListener("change", function() {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }
    
        // Fonction pour désactiver tous les compléments et garnitures lorsque la quantité du plat est diminuée
        function disableAllComplementGarnitureSelections(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;
    
            if (!platId) {
                return;
            }
    
            // Désactive tous les compléments associés
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;  // Décocher le complément
                toggleQuantityVisibility(checkbox, false); // Masquer la quantité associée
            });
    
            // Désactive toutes les garnitures associées
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;  // Décocher la garniture
                toggleQuantityVisibility(checkbox, false); // Masquer la quantité associée
            });
        }
    
        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";
    
                // Si la quantité est cachée et l'élément est décoché, la réinitialiser à 1
                if (!isVisible && !checkbox.checked) {
                    const quantityInput = parent.querySelector(".cart-plus-minus-box");
                    if (quantityInput) {
                        quantityInput.value = 1;  // Réinitialiser à 1 lorsque cachée
                    }
                }
            }
        }
    
        // Fonction pour vérifier si un plat est sélectionné et afficher la quantité
        function checkPlat(checkbox) {
            const platId = checkbox.getAttribute("data-plat-id");
            const platCheckbox = document.getElementById(`plat_${platId}`);
            if (platCheckbox) {
                platCheckbox.checked = true;
                toggleQuantityVisibility(platCheckbox, true);
            }
        }
    
        // Fonction pour décocher les compléments et garnitures associés à un plat
        function uncheckPlatSelections(platId) {
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false);
            });
    
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false);
            });
        }
    
        // Fonction pour activer/désactiver les garnitures et compléments en fonction de la sélection du plat
        function handlePlatSelection(checkbox) {
            const platId = checkbox.value;
    
            // Récupérer les compléments et garnitures associés au plat
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
    
            if (checkbox.checked) {
                // Activer les compléments et garnitures
                complementCheckboxes.forEach(checkbox => checkbox.disabled = false);
                garnitureCheckboxes.forEach(checkbox => checkbox.disabled = false);
    
                // Mettre à jour les limites des compléments et garnitures
                const input = checkbox.closest(".form-check").querySelector(".cart-plus-minus-box");
                updateComplementGarnitureLimits(input);
            } else {
                // Désactiver et décocher les compléments et garnitures
                complementCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true; // Désactiver la case
                    checkbox.checked = false; // Décocher la case
                    toggleQuantityVisibility(checkbox, false); // Masquer les quantités associées
                });
    
                garnitureCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true; // Désactiver la case
                    checkbox.checked = false; // Décocher la case
                    toggleQuantityVisibility(checkbox, false); // Masquer les quantités associées
                });
            }
    
            // Afficher ou masquer la quantité associée au plat
            toggleQuantityVisibility(checkbox, checkbox.checked);
        }
    
        // Initialisation des événements lors du chargement du document
        document.addEventListener("DOMContentLoaded", function() {
            // Gestion des événements sur les compléments
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les compléments par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });
    
            // Gestion des événements sur les garnitures
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les garnitures par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });
    
            // Gestion des événements sur les plats
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            platCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    handlePlatSelection(checkbox); // Gérer l'activation/désactivation des compléments et garnitures
                    if (!checkbox.checked) {
                        const platId = checkbox.value;
                        uncheckPlatSelections(platId); // Décocher les compléments et garnitures lorsque le plat est décoché
                    }
                });
    
                // Masquer les quantités par défaut
                toggleQuantityVisibility(checkbox, false);
            });
    
            // Masquer toutes les quantités au chargement initial
            document.querySelectorAll(".product-quantity").forEach(function(quantityWrapper) {
                quantityWrapper.style.display = "none";
            });
        });
    </script> --}}


    {{-- <script>
        // Fonction pour augmenter la quantité du plat
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;

            updateComplementGarnitureLimits(input);
        }

        // Fonction pour diminuer la quantité du plat
        function decreaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;

            if (currentValue > 1) {
                input.value = currentValue - 1; // Réduit la quantité mais ne réinitialise pas à 1.
            }

            updateComplementGarnitureLimits(input);
        }

        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                console.error("Plat ID introuvable");
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Mettre à jour les compléments
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(complementCheckboxes, quantity);

            // Mettre à jour les garnitures
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }

        // Fonction pour gérer la limite de sélection des compléments et garnitures
        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = 0;

            checkboxes.forEach((checkbox) => {
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;

                checkbox.addEventListener("change", function() {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }

        // Fonction pour désactiver tous les compléments et garnitures lorsque la quantité du plat est diminuée
        function disableAllComplementGarnitureSelections(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                return;
            }

            // Désactive tous les compléments associés
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false; // Décocher le complément
                toggleQuantityVisibility(checkbox, false); // Masquer la quantité associée
            });

            // Désactive toutes les garnitures associées
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false; // Décocher la garniture
                toggleQuantityVisibility(checkbox, false); // Masquer la quantité associée
            });
        }

        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";

                // Réinitialisation de la quantité à 1 si l'élément est décoché et la quantité est cachée
                if (!isVisible && !checkbox.checked) {
                    const quantityInput = parent.querySelector(".cart-plus-minus-box");
                    if (quantityInput) {
                        quantityInput.value = 1; // Réinitialiser à 1 lorsque l'élément est décoché
                    }
                }
            }
        }

        // Fonction pour vérifier si un plat est sélectionné et afficher la quantité
        function checkPlat(checkbox) {
            const platId = checkbox.getAttribute("data-plat-id");
            const platCheckbox = document.getElementById(`plat_${platId}`);
            if (platCheckbox) {
                platCheckbox.checked = true;
                toggleQuantityVisibility(platCheckbox, true);
            }
        }

        // Fonction pour décocher les compléments et garnitures associés à un plat
        function uncheckPlatSelections(platId) {
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false);
            });

            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
                toggleQuantityVisibility(checkbox, false);
            });
        }

        // Fonction pour activer/désactiver les garnitures et compléments en fonction de la sélection du plat
        function handlePlatSelection(checkbox) {
            const platId = checkbox.value;

            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);

            if (checkbox.checked) {
                // Activer les compléments et garnitures
                complementCheckboxes.forEach(checkbox => checkbox.disabled = false);
                garnitureCheckboxes.forEach(checkbox => checkbox.disabled = false);

                const input = checkbox.closest(".form-check").querySelector(".cart-plus-minus-box");
                updateComplementGarnitureLimits(input);
            } else {
                complementCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true; // Désactiver la case
                    checkbox.checked = false; // Décocher la case
                    toggleQuantityVisibility(checkbox, false); // Masquer les quantités associées
                });

                garnitureCheckboxes.forEach(checkbox => {
                    checkbox.disabled = true; // Désactiver la case
                    checkbox.checked = false; // Décocher la case
                    toggleQuantityVisibility(checkbox, false); // Masquer les quantités associées
                });
            }

            // Afficher ou masquer la quantité associée au plat
            toggleQuantityVisibility(checkbox, checkbox.checked);
        }

        // Initialisation des événements lors du chargement du document
        document.addEventListener("DOMContentLoaded", function() {
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les compléments par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les garnitures par défaut
                checkbox.addEventListener("change", function() {
                    checkPlat(this);
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            platCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    handlePlatSelection(
                    checkbox); // Gérer l'activation/désactivation des compléments et garnitures
                    if (!checkbox.checked) {
                        const platId = checkbox.value;
                        uncheckPlatSelections(
                        platId); // Décocher les compléments et garnitures lorsque le plat est décoché
                    }
                });

                // Masquer les quantités par défaut
                toggleQuantityVisibility(checkbox, false);
            });

            // Masquer toutes les quantités au chargement initial
            document.querySelectorAll(".product-quantity").forEach(function(quantityWrapper) {
                quantityWrapper.style.display = "none";
            });
        });
    </script> --}}


    {{-- <script>
        // Fonction pour augmenter la quantité du plat
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;

            updateComplementGarnitureLimits(input);
        }

        // Fonction pour diminuer la quantité du plat
        function decreaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;

            if (currentValue > 1) {
                input.value = currentValue - 1; // Réduit la quantité
            }

            // Décocher les compléments et garnitures en excès si nécessaire
            ensureSelectionWithinLimits(input);
            updateComplementGarnitureLimits(input);
        }

        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                console.error("Plat ID introuvable");
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Mettre à jour les compléments
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(complementCheckboxes, quantity);

            // Mettre à jour les garnitures
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }

        // Fonction pour gérer la limite de sélection des compléments et garnitures
        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;

            checkboxes.forEach((checkbox) => {
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;

                checkbox.addEventListener("change", function() {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }

        // Fonction pour s'assurer que les sélections restent dans les limites
        function ensureSelectionWithinLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Décocher les compléments en excès
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            deselectExcessItems(complementCheckboxes, quantity);

            // Décocher les garnitures en excès
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
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

        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";

                // Réinitialisation de la quantité à 1 si l'élément est décoché et la quantité est cachée
                if (!isVisible && !checkbox.checked) {
                    const quantityInput = parent.querySelector(".cart-plus-minus-box");
                    if (quantityInput) {
                        quantityInput.value = 1; // Réinitialiser à 1 lorsque l'élément est décoché
                    }
                }
            }
        }

        // Fonction pour gérer la sélection d'un plat
        function handlePlatSelection(checkbox) {
            const platId = checkbox.value;

            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);

            if (checkbox.checked) {
                // Activer les compléments et garnitures
                complementCheckboxes.forEach((checkbox) => (checkbox.disabled = false));
                garnitureCheckboxes.forEach((checkbox) => (checkbox.disabled = false));

                const input = checkbox.closest(".form-check").querySelector(".cart-plus-minus-box");
                updateComplementGarnitureLimits(input);
            } else {
                complementCheckboxes.forEach((checkbox) => {
                    checkbox.disabled = true; // Désactiver la case
                    checkbox.checked = false; // Décocher la case
                    toggleQuantityVisibility(checkbox, false); // Masquer les quantités associées
                });

                garnitureCheckboxes.forEach((checkbox) => {
                    checkbox.disabled = true; // Désactiver la case
                    checkbox.checked = false; // Décocher la case
                    toggleQuantityVisibility(checkbox, false); // Masquer les quantités associées
                });
            }

            // Afficher ou masquer la quantité associée au plat
            toggleQuantityVisibility(checkbox, checkbox.checked);
        }

        // Initialisation des événements lors du chargement du document
        document.addEventListener("DOMContentLoaded", function() {
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            complementCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les compléments par défaut
                checkbox.addEventListener("change", function() {
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            garnitureCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true; // Désactiver les garnitures par défaut
                checkbox.addEventListener("change", function() {
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            platCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    handlePlatSelection(
                        checkbox); // Gérer l'activation/désactivation des compléments et garnitures

                    if (!checkbox.checked) {
                        const platId = checkbox.value;
                        deselectExcessItems(
                            document.querySelectorAll(
                                `.complement-checkbox[data-plat-id="${platId}"]`),
                            0
                        );
                        deselectExcessItems(
                            document.querySelectorAll(
                                `.garniture-checkbox[data-plat-id="${platId}"]`),
                            0
                        );
                    }
                });

                // Masquer les quantités par défaut
                toggleQuantityVisibility(checkbox, false);
            });

            // Masquer toutes les quantités au chargement initial
            document.querySelectorAll(".product-quantity").forEach(function(quantityWrapper) {
                quantityWrapper.style.display = "none";
            });
        });
    </script> --}}



    {{-- ###################################### --}}


    {{-- <script>
        // Fonction pour augmenter la quantité du plat
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;

            updateComplementGarnitureLimits(input);
        }

        // Fonction pour diminuer la quantité du plat
        function decreaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;

            if (currentValue > 1) {
                input.value = currentValue - 1; // Réduit la quantité
            }

            // Décocher les compléments et garnitures en excès si nécessaire
            ensureSelectionWithinLimits(input);
            updateComplementGarnitureLimits(input);
        }

        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                console.error("Plat ID introuvable");
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Mettre à jour les compléments
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(complementCheckboxes, quantity);

            // Mettre à jour les garnitures
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }

        // Fonction pour gérer la limite de sélection des compléments et garnitures
        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;

            checkboxes.forEach((checkbox) => {
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;

                checkbox.addEventListener("change", function() {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }

        // Fonction pour s'assurer que les sélections restent dans les limites
        function ensureSelectionWithinLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Décocher les compléments en excès
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            deselectExcessItems(complementCheckboxes, quantity);

            // Décocher les garnitures en excès
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
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

        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";

                // Réinitialisation de la quantité à 1 si l'élément est décoché et la quantité est cachée
                if (!isVisible && !checkbox.checked) {
                    const quantityInput = parent.querySelector(".cart-plus-minus-box");
                    if (quantityInput) {
                        quantityInput.value = 1; // Réinitialiser à 1 lorsque l'élément est décoché
                    }
                }
            }
        }

        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";

                // Réinitialisation de la quantité à 1 si l'élément est décoché et la quantité est cachée
                if (!isVisible && !checkbox.checked) {
                    const quantityInput = parent.querySelector(".cart-plus-minus-box");
                    if (quantityInput) {
                        quantityInput.value = 1; // Réinitialiser à 1 lorsque l'élément est décoché
                    }
                }
            }
        }

        // Initialisation des événements lors du chargement du document
        document.addEventListener("DOMContentLoaded", function() {
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");

            // Vérifier l'état initial des cases à cocher et ajuster les champs de quantité
            complementCheckboxes.forEach((checkbox) => {
                toggleQuantityVisibility(checkbox, checkbox.checked); // Afficher si coché
                checkbox.addEventListener("change", function() {
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            garnitureCheckboxes.forEach((checkbox) => {
                toggleQuantityVisibility(checkbox, checkbox.checked); // Afficher si coché
                checkbox.addEventListener("change", function() {
                    toggleQuantityVisibility(this, this.checked);
                });
            });

            platCheckboxes.forEach((checkbox) => {
                toggleQuantityVisibility(checkbox, checkbox.checked); // Afficher si coché
                checkbox.addEventListener("change", function() {
                    // Gérer l'affichage des quantités pour les plats
                    const platId = this.value;
                    const complementCheckboxes = document.querySelectorAll(
                        `.complement-checkbox[data-plat-id="${platId}"]`);
                    const garnitureCheckboxes = document.querySelectorAll(
                        `.garniture-checkbox[data-plat-id="${platId}"]`);

                    if (this.checked) {
                        complementCheckboxes.forEach((box) => {
                            box.disabled = false;
                            toggleQuantityVisibility(box, box.checked);
                        });
                        garnitureCheckboxes.forEach((box) => {
                            box.disabled = false;
                            toggleQuantityVisibility(box, box.checked);
                        });
                    } else {
                        complementCheckboxes.forEach((box) => {
                            box.disabled = true;
                            box.checked = false;
                            toggleQuantityVisibility(box, false);
                        });
                        garnitureCheckboxes.forEach((box) => {
                            box.disabled = true;
                            box.checked = false;
                            toggleQuantityVisibility(box, false);
                        });
                    }
                });

                // Gérer les compléments et garnitures si un plat est déjà coché au chargement
                if (checkbox.checked) {
                    const platId = checkbox.value;
                    const complementCheckboxes = document.querySelectorAll(
                        `.complement-checkbox[data-plat-id="${platId}"]`);
                    const garnitureCheckboxes = document.querySelectorAll(
                        `.garniture-checkbox[data-plat-id="${platId}"]`);

                    complementCheckboxes.forEach((box) => {
                        box.disabled = false;
                        toggleQuantityVisibility(box, box.checked);
                    });
                    garnitureCheckboxes.forEach((box) => {
                        box.disabled = false;
                        toggleQuantityVisibility(box, box.checked);
                    });
                }
            });
        });
    </script> --}}



    {{-- bon code mais ne cache pas la quantité du plat au coche et decoche --}}
    {{-- <script>
        // Fonction pour augmenter la quantité du plat
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;
    
            updateComplementGarnitureLimits(input);
        }
    
        // Fonction pour diminuer la quantité du plat
        function decreaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
    
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
    
            ensureSelectionWithinLimits(input);
            updateComplementGarnitureLimits(input);
        }
    
        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;
    
            if (!platId) {
                console.error("Plat ID introuvable");
                return;
            }
    
            const quantity = parseInt(input.value, 10);
    
            // Mettre à jour les compléments
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(complementCheckboxes, quantity);
    
            // Mettre à jour les garnitures
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }
    
        // Fonction pour gérer la limite de sélection des compléments et garnitures
        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
    
            checkboxes.forEach((checkbox) => {
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;
    
                checkbox.addEventListener("change", function () {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }
    
        // Fonction pour s'assurer que les sélections restent dans les limites
        function ensureSelectionWithinLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;
    
            if (!platId) {
                return;
            }
    
            const quantity = parseInt(input.value, 10);
    
            // Décocher les compléments en excès
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            deselectExcessItems(complementCheckboxes, quantity);
    
            // Décocher les garnitures en excès
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
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
    
        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";
    
                // Réinitialisation de la quantité à 1 si l'élément est décoché et la quantité est cachée
                if (!isVisible && !checkbox.checked) {
                    const quantityInput = parent.querySelector(".cart-plus-minus-box");
                    if (quantityInput) {
                        quantityInput.value = 1;
                    }
                }
            }
        }
    
        // Initialisation des événements lors du chargement du document
        document.addEventListener("DOMContentLoaded", function () {
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
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
                    const platCheckbox = document.querySelector(`.plat-checkbox[value="${platId}"]`);
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
                    const platCheckbox = document.querySelector(`.plat-checkbox[value="${platId}"]`);
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
                    const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
                    const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
    
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
    </script> --}}



    {{-- Bon code gestion des somme quantite --}}

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction pour augmenter la quantité
            function increaseValue(button) {
                const input = button.previousElementSibling;
                const currentValue = parseInt(input.value) || 0;
                input.value = currentValue + 1;
                triggerChange(input);
            }

            // Fonction pour diminuer la quantité
            function decreaseValue(button) {
                const input = button.nextElementSibling;
                const currentValue = parseInt(input.value) || 0;
                if (currentValue > 0) {
                    input.value = currentValue - 1;
                    triggerChange(input);
                }
            }

            // Fonction déclenchée lors de tout changement de quantité
            function triggerChange(input) {
                const parent = input.closest('.card-body');
                const platQuantityInput = parent.querySelector('.quantityPlat');
                const platQuantity = parseInt(platQuantityInput.value) || 0;

                // Gestion des quantités des garnitures
                const garnitureQuantities = Array.from(
                    parent.querySelectorAll('.quantityGarniture')
                ).reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);

                if (garnitureQuantities > platQuantity) {
                    adjustGarnitureQuantities(parent, platQuantity);
                }

                // Gestion des quantités des compléments
                const complementQuantities = Array.from(
                    parent.querySelectorAll('.quantityComplement')
                ).reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);

                if (complementQuantities > platQuantity) {
                    adjustComplementQuantities(parent, platQuantity);
                }

                // Mise à jour des prix
                updateTotalPrice(parent);
            }

            // Fonction pour ajuster les quantités des garnitures
            function adjustGarnitureQuantities(parent, maxQuantity) {
                const garnitureInputs = parent.querySelectorAll('.quantityGarniture');
                let remainingQuantity = maxQuantity;

                garnitureInputs.forEach((input) => {
                    const value = parseInt(input.value) || 0;
                    if (value > remainingQuantity) {
                        input.value = remainingQuantity;
                        remainingQuantity = 0;
                    } else {
                        remainingQuantity -= value;
                    }
                });
            }

            // Fonction pour ajuster les quantités des compléments
            function adjustComplementQuantities(parent, maxQuantity) {
                const complementInputs = parent.querySelectorAll('.quantityComplement');
                let remainingQuantity = maxQuantity;

                complementInputs.forEach((input) => {
                    const value = parseInt(input.value) || 0;
                    if (value > remainingQuantity) {
                        input.value = remainingQuantity;
                        remainingQuantity = 0;
                    } else {
                        remainingQuantity -= value;
                    }
                });
            }

            // Fonction pour mettre à jour le prix total
            function updateTotalPrice(parent) {
                const platPrice = parseFloat(parent.querySelector('.price').getAttribute('data-price')) || 0;
                const platQuantity = parseInt(parent.querySelector('.quantityPlat').value) || 0;

                const complementPrice = Array.from(parent.querySelectorAll('.complement-checkbox'))
                    .filter((checkbox) => checkbox.checked)
                    .reduce((sum, checkbox) => {
                        const quantity = parseInt(
                            parent.querySelector(`.quantityComplement[data-product-id="${checkbox.value}"]`)
                            .value
                        ) || 0;
                        const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
                        return sum + quantity * price;
                    }, 0);

                const garniturePrice = Array.from(parent.querySelectorAll('.garniture-checkbox'))
                    .filter((checkbox) => checkbox.checked)
                    .reduce((sum, checkbox) => {
                        const quantity = parseInt(
                            parent.querySelector(`.quantityGarniture[data-product-id="${checkbox.value}"]`)
                            .value
                        ) || 0;
                        const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
                        return sum + quantity * price;
                    }, 0);

                const totalPrice = platQuantity * platPrice + complementPrice + garniturePrice;
                parent.querySelector('.price').textContent =
                    `${totalPrice.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ' ')} FCFA`;
            }

            // Attacher les événements pour les boutons + et -
            document.querySelectorAll('.inc').forEach((button) => {
                button.addEventListener('click', function() {
                    increaseValue(this);
                });
            });

            document.querySelectorAll('.dec').forEach((button) => {
                button.addEventListener('click', function() {
                    decreaseValue(this);
                });
            });

            // Attacher les événements pour les checkbox (plat, garniture, complément)
            document.querySelectorAll('.plat-checkbox').forEach((checkbox) => {
                checkbox.addEventListener('change', function() {
                    const parent = this.closest('.card-body');
                    const garnitureCheckboxes = parent.querySelectorAll('.garniture-checkbox');
                    const complementCheckboxes = parent.querySelectorAll('.complement-checkbox');

                    // Si le plat est coché, cocher les garnitures et compléments associés
                    if (this.checked) {
                        garnitureCheckboxes.forEach((garniture) => {
                            garniture.checked = true;
                            const input = parent.querySelector(
                                `.quantityGarniture[data-product-id="${garniture.value}"]`
                            );
                            input.style.display =
                                'inline-block'; // Afficher le champ de quantité
                            input.value = input.getAttribute(
                                'data-default-quantity'); // Utilise la valeur par défaut
                        });

                        complementCheckboxes.forEach((complement) => {
                            complement.checked = true;
                            const input = parent.querySelector(
                                `.quantityComplement[data-product-id="${complement.value}"]`
                            );
                            input.style.display =
                                'inline-block'; // Afficher le champ de quantité
                            input.value = input.getAttribute(
                                'data-default-quantity'); // Utilise la valeur par défaut
                        });
                    } else {
                        // Si le plat est décoché, décocher toutes les garnitures et compléments
                        garnitureCheckboxes.forEach((garniture) => {
                            garniture.checked = false;
                            const input = parent.querySelector(
                                `.quantityGarniture[data-product-id="${garniture.value}"]`
                            );
                            input.style.display = 'none'; // Cacher le champ de quantité
                            input.value = 0; // Réinitialiser la quantité
                        });

                        complementCheckboxes.forEach((complement) => {
                            complement.checked = false;
                            const input = parent.querySelector(
                                `.quantityComplement[data-product-id="${complement.value}"]`
                            );
                            input.style.display = 'none'; // Cacher le champ de quantité
                            input.value = 0; // Réinitialiser la quantité
                        });
                    }

                    updateTotalPrice(parent);
                });
            });

            // Attacher les événements pour les checkbox de garniture et complément
            document.querySelectorAll('.garniture-checkbox, .complement-checkbox').forEach((checkbox) => {
                checkbox.addEventListener('change', function() {
                    const parent = this.closest('.card-body');
                    const platCheckbox = parent.querySelector('.plat-checkbox');

                    // Si une garniture ou un complément est coché, cocher le plat associé et afficher les quantités
                    if (this.checked) {
                        platCheckbox.checked = true;
                        const input = parent.querySelector(
                            `.quantityGarniture[data-product-id="${this.value}"], .quantityComplement[data-product-id="${this.value}"]`
                        );
                        input.style.display = 'inline-block'; // Afficher le champ de quantité
                    }

                    // Si décoché, cacher le champ de quantité associé
                    if (!this.checked) {
                        const input = parent.querySelector(
                            `.quantityGarniture[data-product-id="${this.value}"], .quantityComplement[data-product-id="${this.value}"]`
                        );
                        input.style.display = 'none'; // Cacher le champ de quantité
                        input.value = 0; // Réinitialiser la quantité
                    }

                    updateTotalPrice(parent);
                });
            });
        });
    </script> --}}






    {{-- 
    <script>
        // fon
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        const popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
        // Fonction pour augmenter la quantité du plat
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;
            triggerChange(input);

            updateComplementGarnitureLimits(input);
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
        }

        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                console.error("Plat ID introuvable");
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Mettre à jour les compléments
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(complementCheckboxes, quantity);

            // Mettre à jour les garnitures
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }


        // Fonction déclenchée lors de tout changement de quantité
        function triggerChange(input) {
            const parent = input.closest('.card-body');
            const platQuantityInput = parent.querySelector('.quantityPlat');
            const platQuantity = parseInt(platQuantityInput.value) || 0;

            // Gestion des quantités des garnitures
            const garnitureQuantities = Array.from(
                parent.querySelectorAll('.quantityGarniture')
            ).reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);

            if (garnitureQuantities > platQuantity) {
                adjustGarnitureQuantities(parent, platQuantity);
            }

            // Gestion des quantités des compléments
            const complementQuantities = Array.from(
                parent.querySelectorAll('.quantityComplement')
            ).reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);

            if (complementQuantities > platQuantity) {
                adjustComplementQuantities(parent, platQuantity);
            }

            // Mise à jour des prix
            // updateTotalPrice(parent);
        }



        // Fonction pour ajuster les quantités des compléments
        function adjustComplementQuantities(parent, maxQuantity) {
            const complementInputs = parent.querySelectorAll('.quantityComplement');
            let remainingQuantity = maxQuantity;

            complementInputs.forEach((input) => {
                const value = parseInt(input.value) || 0;
                if (value > remainingQuantity) {
                    input.value = remainingQuantity;
                    remainingQuantity = 0;
                } else {
                    remainingQuantity -= value;
                }
            });
        }

        // Fonction pour mettre à jour le prix total
        // function updateTotalPrice(parent) {
        //     const platPrice = parseFloat(parent.querySelector('.price').getAttribute('data-price')) || 0;
        //     const platQuantity = parseInt(parent.querySelector('.quantityPlat').value) || 0;

        //     const complementPrice = Array.from(parent.querySelectorAll('.complement-checkbox'))
        //         .filter((checkbox) => checkbox.checked)
        //         .reduce((sum, checkbox) => {
        //             const quantity = parseInt(
        //                 parent.querySelector(`.quantityComplement[data-product-id="${checkbox.value}"]`)
        //                 .value
        //             ) || 0;
        //             const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
        //             return sum + quantity * price;
        //         }, 0);

        //     const garniturePrice = Array.from(parent.querySelectorAll('.garniture-checkbox'))
        //         .filter((checkbox) => checkbox.checked)
        //         .reduce((sum, checkbox) => {
        //             const quantity = parseInt(
        //                 parent.querySelector(`.quantityGarniture[data-product-id="${checkbox.value}"]`)
        //                 .value
        //             ) || 0;
        //             const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
        //             return sum + quantity * price;
        //         }, 0);

        //     const totalPrice = platQuantity * platPrice + complementPrice + garniturePrice;
        //     parent.querySelector('.price').textContent =
        //         `${totalPrice.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ' ')} FCFA`;
        // }

        // Attacher les événements pour les boutons + et -
        // document.querySelectorAll('.inc').forEach((button) => {
        //     button.addEventListener('click', function() {
        //         increaseValue(this);
        //     });
        // });

        // document.querySelectorAll('.dec').forEach((button) => {
        //     button.addEventListener('click', function() {
        //         decreaseValue(this);
        //     });
        // });

        // Fonction pour ajuster les quantités des garnitures
        function adjustGarnitureQuantities(parent, maxQuantity) {
            const garnitureInputs = parent.querySelectorAll('.quantityGarniture');
            let remainingQuantity = maxQuantity;

            garnitureInputs.forEach((input) => {
                const value = parseInt(input.value) || 0;
                if (value > remainingQuantity) {
                    input.value = remainingQuantity;
                    remainingQuantity = 0;
                } else {
                    remainingQuantity -= value;
                }
            });
        }

        // Fonction pour gérer la limite de sélection des compléments et garnitures
        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;

            checkboxes.forEach((checkbox) => {
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;

                checkbox.addEventListener("change", function() {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }

        // Fonction pour s'assurer que les sélections restent dans les limites
        function ensureSelectionWithinLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Décocher les compléments en excès
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            deselectExcessItems(complementCheckboxes, quantity);

            // Décocher les garnitures en excès
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
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

        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";

                // Réinitialisation de la quantité à 0 si l'élément est décoché et la quantité est cachée
                // ou à 1 si l'élément est coché
                if (!isVisible && !checkbox.checked) {
                    const quantityInput = parent.querySelector(".cart-plus-minus-box");
                    if (quantityInput) {
                        quantityInput.value = 0;
                    }
                } else {
                    const quantityInput = parent.querySelector(".cart-plus-minus-box");
                    if (quantityInput) {
                        quantityInput.value = 1;
                    }
                }
            }
        }

        // Initialisation des événements lors du chargement du document
        document.addEventListener("DOMContentLoaded", function() {
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            const platInputs = document.querySelectorAll(".cart-plus-minus-box");

            // Mettre à jour les limites dès le chargement pour chaque plat
            platInputs.forEach((input) => {
                updateComplementGarnitureLimits(input);
            });

            // Vérifier l'état initial des cases à cocher et ajuster les champs de quantité
            complementCheckboxes.forEach((checkbox) => {
                toggleQuantityVisibility(checkbox, checkbox.checked); // Afficher si coché
                checkbox.addEventListener("change", function() {
                    toggleQuantityVisibility(this, this.checked);
                    // Coche automatiquement le plat correspondant si une garniture ou un complément est coché
                    const platId = this.dataset.platId;
                    const platCheckbox = document.querySelector(
                        `.plat-checkbox[value="${platId}"]`);
                    if (this.checked && platCheckbox && !platCheckbox.checked) {
                        platCheckbox.checked = true;
                        platCheckbox.dispatchEvent(new Event(
                            "change")); // Déclencher l'événement pour gérer l'état du plat
                    }
                });
            });

            garnitureCheckboxes.forEach((checkbox) => {
                toggleQuantityVisibility(checkbox, checkbox.checked); // Afficher si coché
                checkbox.addEventListener("change", function() {
                    toggleQuantityVisibility(this, this.checked);
                    // Coche automatiquement le plat correspondant si une garniture ou un complément est coché
                    const platId = this.dataset.platId;
                    const platCheckbox = document.querySelector(
                        `.plat-checkbox[value="${platId}"]`);
                    if (this.checked && platCheckbox && !platCheckbox.checked) {
                        platCheckbox.checked = true;
                        platCheckbox.dispatchEvent(new Event(
                            "change")); // Déclencher l'événement pour gérer l'état du plat
                    }
                });
            });

            // Gérer la désélection des garnitures et compléments lorsque le plat est décoché
            platCheckboxes.forEach((checkbox) => {
                checkbox.addEventListener("change", function() {
                    const platId = this.value;
                    const complementCheckboxes = document.querySelectorAll(
                        `.complement-checkbox[data-plat-id="${platId}"]`);
                    const garnitureCheckboxes = document.querySelectorAll(
                        `.garniture-checkbox[data-plat-id="${platId}"]`);

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
    </script> --}}


    <script>
        // Fonction pour désactiver les compléments et garnitures si la somme totale atteint la quantité du plat
        function disableExcessItems(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Calculer la somme des compléments sélectionnés
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            const complementSum = Array.from(complementCheckboxes)
                .filter((checkbox) => checkbox.checked)
                .reduce((sum, checkbox) => sum + parseInt(
                    parent.querySelector(`.quantityComplement[data-product-id="${checkbox.value}"]`).value || 0), 0);

            // Calculer la somme des garnitures sélectionnées
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            const garnitureSum = Array.from(garnitureCheckboxes)
                .filter((checkbox) => checkbox.checked)
                .reduce((sum, checkbox) => sum + parseInt(
                    parent.querySelector(`.quantityGarniture[data-product-id="${checkbox.value}"]`).value || 0), 0);

            // Si la somme des compléments et garnitures atteint ou dépasse la quantité du plat, désactiver les éléments restants
            const totalSelection = complementSum + garnitureSum;
            if (totalSelection >= quantity) {
                complementCheckboxes.forEach((checkbox) => {
                    if (!checkbox.checked) {
                        checkbox.disabled = true;
                    }
                });

                garnitureCheckboxes.forEach((checkbox) => {
                    if (!checkbox.checked) {
                        checkbox.disabled = true;
                    }
                });
            } else {
                // Réactiver les compléments et garnitures si la somme est inférieure à la quantité du plat
                complementCheckboxes.forEach((checkbox) => {
                    checkbox.disabled = false;
                });

                garnitureCheckboxes.forEach((checkbox) => {
                    checkbox.disabled = false;
                });
            }
        }

        // Mise à jour de la fonction `triggerChange` pour inclure la gestion des désactivations
        function triggerChange(input) {
            const parent = input.closest('.card-body');
            const platQuantityInput = parent.querySelector('.quantityPlat');
            const platQuantity = parseInt(platQuantityInput.value) || 0;

            // Gestion des quantités des garnitures
            const garnitureQuantities = Array.from(
                parent.querySelectorAll('.quantityGarniture')
            ).reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);

            if (garnitureQuantities > platQuantity) {
                adjustGarnitureQuantities(parent, platQuantity);
            }

            // Gestion des quantités des compléments
            const complementQuantities = Array.from(
                parent.querySelectorAll('.quantityComplement')
            ).reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);

            if (complementQuantities > platQuantity) {
                adjustComplementQuantities(parent, platQuantity);
            }

            // Désactiver les compléments et garnitures si leur somme atteint ou dépasse la quantité du plat
            disableExcessItems(input);

            // Mise à jour des prix
            // updateTotalPrice(parent);
        }
    </script>
 {{-- <script>
        // fon
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        const popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
        // Fonction pour augmenter la quantité du plat
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;
            triggerChange(input);

            updateComplementGarnitureLimits(input);
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
        }

        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                console.error("Plat ID introuvable");
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Mettre à jour les compléments
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(complementCheckboxes, quantity);

            // Mettre à jour les garnitures
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }


        // Fonction déclenchée lors de tout changement de quantité
        function triggerChange(input) {
            const parent = input.closest('.card-body');
            const platQuantityInput = parent.querySelector('.quantityPlat');
            const platQuantity = parseInt(platQuantityInput.value) || 0;

            // Gestion des quantités des garnitures
            const garnitureQuantities = Array.from(
                parent.querySelectorAll('.quantityGarniture')
            ).reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);

            if (garnitureQuantities > platQuantity) {
                adjustGarnitureQuantities(parent, platQuantity);
            }

            // Gestion des quantités des compléments
            const complementQuantities = Array.from(
                parent.querySelectorAll('.quantityComplement')
            ).reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);

            if (complementQuantities > platQuantity) {
                adjustComplementQuantities(parent, platQuantity);
            }

            // Mise à jour des prix
            // updateTotalPrice(parent);
        }



        // // Fonction pour ajuster les quantités des compléments
        // function adjustComplementQuantities(parent, maxQuantity) {
        //     const complementInputs = parent.querySelectorAll('.quantityComplement');
        //     let remainingQuantity = maxQuantity;

        //     complementInputs.forEach((input) => {
        //         const value = parseInt(input.value) || 0;
        //         if (value > remainingQuantity) {
        //             input.value = remainingQuantity;
        //             remainingQuantity = 0;
        //         } else {
        //             remainingQuantity -= value;
        //         }
        //     });
        // }


        // // Fonction pour ajuster les quantités des garnitures
        // function adjustGarnitureQuantities(parent, maxQuantity) {
        //     const garnitureInputs = parent.querySelectorAll('.quantityGarniture');
        //     let remainingQuantity = maxQuantity;

        //     garnitureInputs.forEach((input) => {
        //         const value = parseInt(input.value) || 0;
        //         if (value > remainingQuantity) {
        //             input.value = remainingQuantity;
        //             remainingQuantity = 0;
        //         } else {
        //             remainingQuantity -= value;
        //         }
        //     });
        // }

        // Fonction pour ajuster les quantités des compléments
        function adjustComplementQuantities(parent, maxQuantity) {
            const complementInputs = parent.querySelectorAll('.quantityComplement');
            let remainingQuantity = maxQuantity;

            complementInputs.forEach((input) => {
                const value = parseInt(input.value) || 0;

                if (value > remainingQuantity) {
                    input.value = remainingQuantity;
                    remainingQuantity = 0;
                } else {
                    remainingQuantity -= value;
                }

                // Si la quantité tombe à 0, décocher, désactiver et cacher
                if (parseInt(input.value) === 0) {
                    const checkbox = input.closest('.form-check').querySelector('.complement-checkbox');
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
            const garnitureInputs = parent.querySelectorAll('.quantityGarniture');
            let remainingQuantity = maxQuantity;

            garnitureInputs.forEach((input) => {
                const value = parseInt(input.value) || 0;

                if (value > remainingQuantity) {
                    input.value = remainingQuantity;
                    remainingQuantity = 0;
                } else {
                    remainingQuantity -= value;
                }

                // Si la quantité tombe à 0, décocher, désactiver et cacher
                if (parseInt(input.value) === 0) {
                    const checkbox = input.closest('.form-check').querySelector('.garniture-checkbox');
                    if (checkbox) {
                        checkbox.checked = false;
                        checkbox.disabled = true;
                        toggleQuantityVisibility(checkbox, false);
                    }
                }
            });
        }


       
        // Fonction pour masquer ou afficher le champ de quantité
        function toggleQuantityVisibility(checkbox, isVisible) {
            const quantityField = checkbox.closest('.form-check').querySelector('.quantityField');
            if (quantityField) {
                quantityField.style.display = isVisible ? 'block' : 'none';
            }
        }



        // Fonction pour gérer la limite de sélection des compléments et garnitures
        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;

            checkboxes.forEach((checkbox) => {
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;

                checkbox.addEventListener("change", function() {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }

        // Fonction pour s'assurer que les sélections restent dans les limites
        function ensureSelectionWithinLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Décocher les compléments en excès
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            deselectExcessItems(complementCheckboxes, quantity);

            // Décocher les garnitures en excès
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
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

        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";

                // Réinitialisation de la quantité à 0 si l'élément est décoché et la quantité est cachée
                // ou à 1 si l'élément est coché
                if (!isVisible && !checkbox.checked) {
                    const quantityInput = parent.querySelector(".cart-plus-minus-box");
                    if (quantityInput) {
                        quantityInput.value = 0;
                    }
                } else {
                    const quantityInput = parent.querySelector(".cart-plus-minus-box");
                    if (quantityInput) {
                        quantityInput.value = 1;
                    }
                }
            }
        }

        // Initialisation des événements lors du chargement du document
        document.addEventListener("DOMContentLoaded", function() {
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            const platInputs = document.querySelectorAll(".cart-plus-minus-box");

            // Mettre à jour les limites dès le chargement pour chaque plat
            platInputs.forEach((input) => {
                updateComplementGarnitureLimits(input);
            });

            // Vérifier l'état initial des cases à cocher et ajuster les champs de quantité
            complementCheckboxes.forEach((checkbox) => {
                toggleQuantityVisibility(checkbox, checkbox.checked); // Afficher si coché
                checkbox.addEventListener("change", function() {
                    toggleQuantityVisibility(this, this.checked);
                    // Coche automatiquement le plat correspondant si une garniture ou un complément est coché
                    const platId = this.dataset.platId;
                    const platCheckbox = document.querySelector(
                        `.plat-checkbox[value="${platId}"]`);
                    if (this.checked && platCheckbox && !platCheckbox.checked) {
                        platCheckbox.checked = true;
                        platCheckbox.dispatchEvent(new Event(
                            "change")); // Déclencher l'événement pour gérer l'état du plat
                    }
                });
            });

            garnitureCheckboxes.forEach((checkbox) => {
                toggleQuantityVisibility(checkbox, checkbox.checked); // Afficher si coché
                checkbox.addEventListener("change", function() {
                    toggleQuantityVisibility(this, this.checked);
                    // Coche automatiquement le plat correspondant si une garniture ou un complément est coché
                    const platId = this.dataset.platId;
                    const platCheckbox = document.querySelector(
                        `.plat-checkbox[value="${platId}"]`);
                    if (this.checked && platCheckbox && !platCheckbox.checked) {
                        platCheckbox.checked = true;
                        platCheckbox.dispatchEvent(new Event(
                            "change")); // Déclencher l'événement pour gérer l'état du plat
                    }
                });
            });

            // Gérer la désélection des garnitures et compléments lorsque le plat est décoché
            platCheckboxes.forEach((checkbox) => {
                checkbox.addEventListener("change", function() {
                    const platId = this.value;
                    const complementCheckboxes = document.querySelectorAll(
                        `.complement-checkbox[data-plat-id="${platId}"]`);
                    const garnitureCheckboxes = document.querySelectorAll(
                        `.garniture-checkbox[data-plat-id="${platId}"]`);

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
    </script> --}}


    
    <script>
        // fon
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        const popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
        // Fonction pour augmenter la quantité du plat
        function increaseValue(button) {
            const input = button.parentElement.querySelector(".cart-plus-minus-box");
            let currentValue = parseInt(input.value, 10) || 0;
            input.value = currentValue + 1;
            triggerChange(input);

            updateComplementGarnitureLimits(input);
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
        }

        // Fonction pour mettre à jour les limites de sélection des compléments et garnitures
        function updateComplementGarnitureLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                console.error("Plat ID introuvable");
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Mettre à jour les compléments
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(complementCheckboxes, quantity);

            // Mettre à jour les garnitures
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
            manageSelectionLimits(garnitureCheckboxes, quantity);
        }


        // Fonction déclenchée lors de tout changement de quantité
        function triggerChange(input) {
            const parent = input.closest('.card-body');
            const platQuantityInput = parent.querySelector('.quantityPlat');
            const platQuantity = parseInt(platQuantityInput.value) || 0;

            // Gestion des quantités des garnitures
            const garnitureQuantities = Array.from(
                parent.querySelectorAll('.quantityGarniture')
            ).reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);

            if (garnitureQuantities > platQuantity) {
                adjustGarnitureQuantities(parent, platQuantity);
            }

            // Gestion des quantités des compléments
            const complementQuantities = Array.from(
                parent.querySelectorAll('.quantityComplement')
            ).reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);

            if (complementQuantities > platQuantity) {
                adjustComplementQuantities(parent, platQuantity);
            }

            // Mise à jour des prix
            // updateTotalPrice(parent);
        }



        // Fonction pour ajuster les quantités des compléments
        function adjustComplementQuantities(parent, maxQuantity) {
            const complementInputs = parent.querySelectorAll('.quantityComplement');
            let remainingQuantity = maxQuantity;

            complementInputs.forEach((input) => {
                const value = parseInt(input.value) || 0;
                if (value > remainingQuantity) {
                    input.value = remainingQuantity;
                    remainingQuantity = 0;
                } else {
                    remainingQuantity -= value;
                }
            });
        }

        // Fonction pour mettre à jour le prix total
        // function updateTotalPrice(parent) {
        //     const platPrice = parseFloat(parent.querySelector('.price').getAttribute('data-price')) || 0;
        //     const platQuantity = parseInt(parent.querySelector('.quantityPlat').value) || 0;

        //     const complementPrice = Array.from(parent.querySelectorAll('.complement-checkbox'))
        //         .filter((checkbox) => checkbox.checked)
        //         .reduce((sum, checkbox) => {
        //             const quantity = parseInt(
        //                 parent.querySelector(`.quantityComplement[data-product-id="${checkbox.value}"]`)
        //                 .value
        //             ) || 0;
        //             const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
        //             return sum + quantity * price;
        //         }, 0);

        //     const garniturePrice = Array.from(parent.querySelectorAll('.garniture-checkbox'))
        //         .filter((checkbox) => checkbox.checked)
        //         .reduce((sum, checkbox) => {
        //             const quantity = parseInt(
        //                 parent.querySelector(`.quantityGarniture[data-product-id="${checkbox.value}"]`)
        //                 .value
        //             ) || 0;
        //             const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
        //             return sum + quantity * price;
        //         }, 0);

        //     const totalPrice = platQuantity * platPrice + complementPrice + garniturePrice;
        //     parent.querySelector('.price').textContent =
        //         `${totalPrice.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ' ')} FCFA`;
        // }

        // Attacher les événements pour les boutons + et -
        // document.querySelectorAll('.inc').forEach((button) => {
        //     button.addEventListener('click', function() {
        //         increaseValue(this);
        //     });
        // });

        // document.querySelectorAll('.dec').forEach((button) => {
        //     button.addEventListener('click', function() {
        //         decreaseValue(this);
        //     });
        // });

        // Fonction pour ajuster les quantités des garnitures
        function adjustGarnitureQuantities(parent, maxQuantity) {
            const garnitureInputs = parent.querySelectorAll('.quantityGarniture');
            let remainingQuantity = maxQuantity;

            garnitureInputs.forEach((input) => {
                const value = parseInt(input.value) || 0;
                if (value > remainingQuantity) {
                    input.value = remainingQuantity;
                    remainingQuantity = 0;
                } else {
                    remainingQuantity -= value;
                }
            });
        }

        // Fonction pour gérer la limite de sélection des compléments et garnitures
        function manageSelectionLimits(checkboxes, limit) {
            let selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;

            checkboxes.forEach((checkbox) => {
                checkbox.disabled = selectedCount >= limit && !checkbox.checked;

                checkbox.addEventListener("change", function() {
                    selectedCount = Array.from(checkboxes).filter((box) => box.checked).length;
                    checkboxes.forEach((box) => {
                        box.disabled = selectedCount >= limit && !box.checked;
                    });
                });
            });
        }

        // Fonction pour s'assurer que les sélections restent dans les limites
        function ensureSelectionWithinLimits(input) {
            const platId = input.closest(".form-check").querySelector(".plat-checkbox")?.value;

            if (!platId) {
                return;
            }

            const quantity = parseInt(input.value, 10);

            // Décocher les compléments en excès
            const complementCheckboxes = document.querySelectorAll(`.complement-checkbox[data-plat-id="${platId}"]`);
            deselectExcessItems(complementCheckboxes, quantity);

            // Décocher les garnitures en excès
            const garnitureCheckboxes = document.querySelectorAll(`.garniture-checkbox[data-plat-id="${platId}"]`);
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

        // Fonction pour afficher ou masquer la quantité en fonction de la sélection
        function toggleQuantityVisibility(checkbox, isVisible) {
            const parent = checkbox.closest(".form-check");
            const quantityWrapper = parent.querySelector(".product-quantity");
            if (quantityWrapper) {
                quantityWrapper.style.display = isVisible ? "block" : "none";

                // Réinitialisation de la quantité à 0 si l'élément est décoché et la quantité est cachée
                // ou à 1 si l'élément est coché
                if (!isVisible && !checkbox.checked) {
                    const quantityInput = parent.querySelector(".cart-plus-minus-box");
                    if (quantityInput) {
                        quantityInput.value = 0;
                    }
                } else {
                    const quantityInput = parent.querySelector(".cart-plus-minus-box");
                    if (quantityInput) {
                        quantityInput.value = 1;
                    }
                }
            }
        }

        // Initialisation des événements lors du chargement du document
        document.addEventListener("DOMContentLoaded", function() {
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");
            const complementCheckboxes = document.querySelectorAll(".complement-checkbox");
            const garnitureCheckboxes = document.querySelectorAll(".garniture-checkbox");
            const platInputs = document.querySelectorAll(".cart-plus-minus-box");

            // Mettre à jour les limites dès le chargement pour chaque plat
            platInputs.forEach((input) => {
                updateComplementGarnitureLimits(input);
            });

            // Vérifier l'état initial des cases à cocher et ajuster les champs de quantité
            complementCheckboxes.forEach((checkbox) => {
                toggleQuantityVisibility(checkbox, checkbox.checked); // Afficher si coché
                checkbox.addEventListener("change", function() {
                    toggleQuantityVisibility(this, this.checked);
                    // Coche automatiquement le plat correspondant si une garniture ou un complément est coché
                    const platId = this.dataset.platId;
                    const platCheckbox = document.querySelector(
                        `.plat-checkbox[value="${platId}"]`);
                    if (this.checked && platCheckbox && !platCheckbox.checked) {
                        platCheckbox.checked = true;
                        platCheckbox.dispatchEvent(new Event(
                            "change")); // Déclencher l'événement pour gérer l'état du plat
                    }
                });
            });

            garnitureCheckboxes.forEach((checkbox) => {
                toggleQuantityVisibility(checkbox, checkbox.checked); // Afficher si coché
                checkbox.addEventListener("change", function() {
                    toggleQuantityVisibility(this, this.checked);
                    // Coche automatiquement le plat correspondant si une garniture ou un complément est coché
                    const platId = this.dataset.platId;
                    const platCheckbox = document.querySelector(
                        `.plat-checkbox[value="${platId}"]`);
                    if (this.checked && platCheckbox && !platCheckbox.checked) {
                        platCheckbox.checked = true;
                        platCheckbox.dispatchEvent(new Event(
                            "change")); // Déclencher l'événement pour gérer l'état du plat
                    }
                });
            });

            // Gérer la désélection des garnitures et compléments lorsque le plat est décoché
            platCheckboxes.forEach((checkbox) => {
                checkbox.addEventListener("change", function() {
                    const platId = this.value;
                    const complementCheckboxes = document.querySelectorAll(
                        `.complement-checkbox[data-plat-id="${platId}"]`);
                    const garnitureCheckboxes = document.querySelectorAll(
                        `.garniture-checkbox[data-plat-id="${platId}"]`);

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
    </script>