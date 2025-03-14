@extends('backend.layouts.master')
@section('title')
    Vente Menu
@endsection


@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">


    @component('backend.components.breadcrumb')
        @slot('li_1')
            Vente
        @endslot
        @slot('title')
            Point de Vente
        @endslot
    @endcomponent
    <style>
        .menu-image {
            max-width: 100%;
            /* Adapte l'image à la largeur de la colonne */
            height: auto;
            /* Garde les proportions */
            border-radius: 8px;
            /* Ajoute des coins arrondis (optionnel) */


        }

        .product-quantity {
            width: 50px;
        }
    </style>

    <div class="shop-page-area pt-10 pb-100">
        <div class="container-fluid">
            @if (!$menu)
                <p class="text-center">Aucun menu disponible pour aujourd'hui.</p>
            @else
                <h1 class="text-center my-4">Menu du <span>{{ \Carbon\Carbon::parse($menu->date)->format('d/m/Y') }}</span>
                </h1>

                <?php $cartMenu = Session::get('cartMenu', []); ?>
                <div class="d-flex mt-4 ol-sm-12 col-md-12 col-lg-12 col-xl-12  m-auto">
                    <div class="col-12 col-md-12 col-lg-12 col-xl-8">
                        @foreach ($categories as $categorie => $plats)
                            <div class="card shadow col-12">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="m-0 text-white">{{ $categorie }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach ($plats as $platKey => $plat)
                                            <?php
                                            $cartItem = collect($cartMenu)->firstWhere('plat_id', $plat->id);
                                            $isPlatChecked = !is_null($cartItem);
                                            $platQuantity = $isPlatChecked ? $cartItem['quantity'] : 1;
                                            ?>
                                            <div class="col-md-6 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between mt-2">
                                                            <div class="form-check">
                                                                <input type="checkbox" data-price="{{ $plat->prix }}"
                                                                    id="plat_{{ $plat->id }}"
                                                                    class="form-check-input plat-checkbox" name="plats[]"
                                                                    value="{{ $plat->id }}"
                                                                    {{ $isPlatChecked ? 'checked' : '' }}>
                                                                <label for="plat_{{ $plat->id }}"
                                                                    class="form-check-label fw-bold text-capitalize fs-6">
                                                                    {{ $plat->nom }}
                                                                </label>
                                                                @if ($plat->complements->isNotEmpty() || $plat->garnitures->isNotEmpty())
                                                                    <i class="fa fa-info-circle text-warning fs-6"
                                                                        data-bs-toggle="popover" data-bs-placement="top"
                                                                        data-bs-trigger="hover"
                                                                        data-bs-content="Choisir les garnitures et complements en fonction de la quantité du plat ."></i>
                                                                @endif
                                                                <div class="product-quantity mb-0"
                                                                    data-product-id="{{ $plat->id }}">
                                                                    <div class="cart-plus-minus">
                                                                        <div class="dec qtybutton"
                                                                            onclick="decreaseValue(this)">-</div>
                                                                        <input id="quantity"
                                                                            class="cart-plus-minus-box quantityPlat text-danger"
                                                                            type="text" name="quantity"
                                                                            value="{{ $platQuantity }}" min="1"
                                                                            readonly>
                                                                        <div class="inc qtybutton"
                                                                            onclick="increaseValue(this)">+</div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <strong data-price="{{ $plat->prix }}"
                                                                class="price text-danger plat-price-display">
                                                                {{ number_format($plat->prix, 0, ',', ' ') }} FCFA
                                                            </strong>
                                                        </div>
                                                        <div class="row">
                                                            <div
                                                                class="{{ $plat->garnitures->isNotEmpty() ? 'col-6' : 'col-12' }}">
                                                                @if ($plat->complements->isNotEmpty())
                                                                    <p class="card-text fw-bold mt-3">Choisir des
                                                                        compléments :</p>
                                                                    <form class="complement-form">
                                                                        @foreach ($plat->complements as $complementKey => $complement)
                                                                            <?php
                                                                            $complementChecked = $isPlatChecked && collect($cartItem['complements'])->contains('id', $complement->id);
                                                                            $complementQuantity = $complementChecked ? collect($cartItem['complements'])->firstWhere('id', $complement->id)['quantity'] : 1;
                                                                            ?>
                                                                            <div class="form-check">
                                                                                <input type="checkbox"
                                                                                    id="complement_{{ $platKey }}_{{ $complementKey }}"
                                                                                    name="complements_{{ $platKey }}[]"
                                                                                    class="form-check-input complement-checkbox"
                                                                                    data-plat-id="{{ $plat->id }}"
                                                                                    value="{{ $complement->id }}"
                                                                                    {{ $complementChecked ? 'checked' : '' }}>
                                                                                <label
                                                                                    for="complement_{{ $platKey }}_{{ $complementKey }}"
                                                                                    class="form-check-label">
                                                                                    {{ $complement->nom }}
                                                                                </label>

                                                                                <div class="product-quantity mb-0"
                                                                                    data-product-id="{{ $complement->id }}">
                                                                                    <div class="cart-plus-minus">
                                                                                        <div class="dec qtybutton"
                                                                                            onclick="decreaseValue(this)">-
                                                                                        </div>
                                                                                        <input id="quantity"
                                                                                            class="cart-plus-minus-box quantityComplement text-danger"
                                                                                            type="text" name="quantity"
                                                                                            value="{{ $complementQuantity }}"
                                                                                            min="1" readonly>
                                                                                        <div class="inc qtybutton"
                                                                                            onclick="increaseValue(this)">+
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </form>
                                                                @endif
                                                            </div>
                                                            <div
                                                                class="{{ $plat->complements->isNotEmpty() ? 'col-6' : 'col-12' }}">
                                                                @if ($plat->garnitures->isNotEmpty())
                                                                    <p class="card-text fw-bold mt-3">Choisir des
                                                                        garnitures :</p>
                                                                    <form class="garniture-form">
                                                                        @foreach ($plat->garnitures as $garnitureKey => $garniture)
                                                                            <?php
                                                                            $garnitureChecked = $isPlatChecked && collect($cartItem['garnitures'])->contains('id', $garniture->id);
                                                                            $garnitureQuantity = $garnitureChecked ? collect($cartItem['garnitures'])->firstWhere('id', $garniture->id)['quantity'] : 1;
                                                                            ?>
                                                                            <div class="form-check">
                                                                                <input type="checkbox"
                                                                                    id="garniture_{{ $platKey }}_{{ $garnitureKey }}"
                                                                                    name="garnitures_{{ $platKey }}[]"
                                                                                    class="form-check-input garniture-checkbox"
                                                                                    data-plat-id="{{ $plat->id }}"
                                                                                    value="{{ $garniture->id }}"
                                                                                    {{ $garnitureChecked ? 'checked' : '' }}>
                                                                                <label
                                                                                    for="garniture_{{ $platKey }}_{{ $garnitureKey }}"
                                                                                    class="form-check-label">
                                                                                    {{ $garniture->nom }}
                                                                                </label>

                                                                                <div class="product-quantity mb-0"
                                                                                    data-product-id="{{ $garniture->id }}">
                                                                                    <div class="cart-plus-minus">
                                                                                        <div class="dec qtybutton"
                                                                                            onclick="decreaseValue(this)">-
                                                                                        </div>
                                                                                        <input id="quantity"
                                                                                            class="cart-plus-minus-box quantityGarniture text-danger"
                                                                                            type="text" name="quantity"
                                                                                            value="{{ $garnitureQuantity }}"
                                                                                            min="1" readonly>
                                                                                        <div class="inc qtybutton"
                                                                                            onclick="increaseValue(this)">+
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="image-container d-none d-lg-block d-md-block col-4 mx-2">
                        {{-- @if ($menu && $menu->hasMedia('images'))
                            <img src="{{ $menu->getFirstMediaUrl('images') }}" alt="Menu Image"
                                class="img-fluid menu-image">
                        @endif --}}
                        <div class="card" style="background-color:rgb(240, 234, 234) ; position: fixed ; width: 400px;">
                            <div class="card-body total-payment-container">
                                <h2 class="card-title fw-bold fs-3 mb-3">Total: <span id="totalAmount">0</span></h2>

                                <div class="payment-method mb-3">
                                    <label for="paymentMethod">Moyen de paiement:</label>
                                    <select id="payment-method" name="mode_reglement" class="form-select" required>
                                        <option value="espece" selected>Espèce</option>
                                        <option value="orange money">Orange Money</option>
                                        <option value="moov money">Moov Money</option>
                                        <option value="mtn money">MTN Money</option>
                                        <option value="wave">Wave</option>
                                        <option value="visa">Visa</option>
                                        <option value="mastercard">MasterCard</option>
                                    </select>
                                </div>
                                <div class="amount-received mb-3">
                                    <label for="amountReceived">Montant reçu:</label>
                                    <input type="number" id="amountReceived" class="form-control"
                                        placeholder="Entrez le montant reçu" required>
                                </div>
                                {{-- <div class="change-given">
                                    <label for="changeGiven">Monnaie rendue:</label>
                                    <input type="number" id="changeGiven" class="form-control"
                                        placeholder="Monnaie rendue" readonly>
                                </div> --}}
                                <div class=" mt-3">
                                    <h4>Monnaie rendu : <span id="changeGiven">0</span> FCFA</h4>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success addCart text-white w-100 "
                               >
                                <i class="fa fa-shopping-cart"></i> Commander
                            </button>
                        </div>



                        <script>
                            // Fonction pour extraire un nombre depuis une chaîne formatée
                            function parseFormattedNumber(numberString) {
                                return parseFloat(numberString.replace(/\s/g, '').replace(',', '.')) || 0;
                            }

                            // Fonction de recalcul automatique de la monnaie rendue
                            function updateChange() {
                                const totalText = document.getElementById('totalAmount').textContent;
                                const total = parseFormattedNumber(totalText); // Convertir le total formaté
                                const received = parseFloat(document.getElementById('amountReceived').value) || 0;
                                const change = received - total;
                                $('#changeGiven').text(change >= 0 ? change.toLocaleString('fr-FR') : '0');
                                // document.getElementById('changeGiven').value = change >= 0 ? change.toLocaleString('fr-FR') : '0';
                            }

                            // Écoute des changements dynamiques du total
                            const observer = new MutationObserver(updateChange);
                            observer.observe(document.getElementById('totalAmount'), {
                                childList: true,
                                characterData: true,
                                subtree: true
                            });

                            // Écoute des entrées du montant reçu
                            document.getElementById('amountReceived').addEventListener('input', updateChange);
                        </script>


                    </div>
                </div>
            @endif
        </div>
        @include('site.components.ajouter-au-panier-menu')
    </div>



@section('script')
    <script>
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
                platPriceDisplay.textContent = platTotal.toLocaleString("fr-FR") + " FCFA"; // Adapter selon votre devise
            }

            updateTotalPrice(); // Mettre à jour le total global
        }

        // Fonction pour mettre à jour le tableau des prix totaux
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

            // Mettre à jour l'affichage du total dans un élément spécifique
            const totalDisplay = document.getElementById("totalAmount");
            if (totalDisplay) {
                totalDisplay.textContent = total.toLocaleString("fr-FR") + " FCFA"; // Adapter selon votre devise
            }
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
                updateTotalPrice();
            }
        }

        // Ajout des événements nécessaires
        document.addEventListener("DOMContentLoaded", function() {
            const platInputs = document.querySelectorAll(".cart-plus-minus-box");
            const platCheckboxes = document.querySelectorAll(".plat-checkbox");

            platInputs.forEach((input) => {
                input.addEventListener("change", function() {
                    updatePlatPrice(input);
                });
            });

            platCheckboxes.forEach((checkbox) => {
                checkbox.addEventListener("change", function() {
                    handlePlatSelection(checkbox);
                });
            });

            // Mettre à jour le total au chargement initial
            updateTotalPrice();
        });






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

            if (garnitureQuantities >= platQuantity) {
                adjustGarnitureQuantities(parent, platQuantity);
            } else {

                // Permettre de cocher d'autres options si platQuantity est inférieur
                parent.querySelectorAll('.garniture-checkbox').forEach((checkbox) => {
                    checkbox.disabled = false;
                });
            }


            // Gestion des quantités des compléments
            const complementQuantities = Array.from(
                parent.querySelectorAll('.quantityComplement')
            ).reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);

            if (complementQuantities >= platQuantity) {
                adjustComplementQuantities(parent, platQuantity);
            } else {
                // Permettre de cocher d'autres options si platQuantity est inférieur
                parent.querySelectorAll('.complement-checkbox').forEach((checkbox) => {
                    checkbox.disabled = false;
                });
            }


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

                // Si la quantité tombe à 0 , décocher, désactiver et cacher
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

                // Si la quantité tombe à 0  , décocher, désactiver et cacher
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
@endsection



@endsection
