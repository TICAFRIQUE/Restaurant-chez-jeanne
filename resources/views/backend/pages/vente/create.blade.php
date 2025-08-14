@extends('backend.layouts.master')
@section('title')
    Vente
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            Vente
        @endslot
        @slot('title')
            Point de Vente
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills nav-custom nav-custom-light mb-3 w-100" role="tablist">
                        <li class="nav-item " style="width: 33%">
                            <a class="nav-link active w-100" data-bs-toggle="tab" href="#nav-vente-ordinaire" role="tab">
                                Vente Ordinaire
                            </a>
                        </li>
                        <li class="nav-item " style="width: 33%">
                            <a class="nav-link w-100" data-bs-toggle="tab" href="#nav-vente-menu" role="tab">
                                Vente Menu du jour
                            </a>
                        </li>

                        <li class="nav-item " style="width: 34%">
                            <a class="nav-link w-100" href="{{ route('commande.index', ['filter' => 'en attente']) }}">
                                Commande en ligne
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content text-muted">
                        <div class="tab-pane active" id="nav-vente-ordinaire" role="tabpanel">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <select name="produit_id"
                                                    class="form-select js-example-basic-single product-select">
                                                    <option value="">Sélectionnez un produit</option>
                                                    @foreach ($data_produit as $produit)
                                                        @if ($produit->stock <= 0 && $produit->categorie->famille == 'bar')
                                                            <option value="{{ $produit->id }}"
                                                                data-price="{{ $produit->prix }}"
                                                                data-stock="{{ $produit->stock }}" disabled>
                                                                {{ $produit->nom }} {{ $produit->valeur_unite ?? '' }}
                                                                {{ $produit->unite->libelle ?? '' }}
                                                                {{ $produit->unite ? '(' . $produit->unite->abreviation . ')' : '' }}
                                                                ({{ $produit->prix }} FCFA)
                                                                - <span style="color: red" class="text-danger">(Stock:
                                                                    {{ $produit->stock }})</span>
                                                            </option>
                                                        @else
                                                            <option value="{{ $produit->id }}"
                                                                data-price="{{ $produit->prix }}"
                                                                data-stock="{{ $produit->stock }}">
                                                                {{ $produit->nom }} {{ $produit->valeur_unite ?? '' }}
                                                                {{ $produit->unite->libelle ?? '' }}
                                                                {{ $produit->unite ? '(' . $produit->unite->abreviation . ')' : '' }}
                                                                ({{ $produit->prix }} FCFA)
                                                                {{-- - <span class="text-primary">(Stock: {{{$produit->stock}}})</span> --}}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        {{-- <h4 class="card-title mb-4">Sélection des produits</h4> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Panier</h4>
                                        <div class="table-responsive">
                                            <div class="alert alert-danger d-none" role="alert">
                                                <span id="errorMessage"></span>
                                            </div>
                                            <table class="table table-bordered" id="cart-table">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Mode de vente</th>
                                                        <th>Prix unitaire</th>
                                                        <th>Quantité</th>
                                                        <th>Total</th>
                                                        <th>Offert <i class="ri ri-gift-2-fill"></i></th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="nav-vente-menu" role="tabpanel">
                            @include('backend.pages.vente.menu.create')
                        </div>

                    </div>
                </div><!-- end card-body -->
            </div>
        </div>



        <!-- ========== Start Total  ========== -->
        <div class="col-4 ">
            <div class="p-3" style="background-color:rgb(240, 234, 234) ; position: top ; width: 400px;">
                <!-- Total geral, remise e montant depois da remise -->
                <div class=" mt-3">
                    <h6>Total ordinaire : <span id="grand-total">0</span> FCFA</h6>
                    <h6>Total menu : <span id="totalAmount">0</span> </h6>
                    <h6>Total Net : <span id="totalNet">0</span> FCFA</h6>


                    @can('voir-remise')
                        <h5>Remise: <span id="discount-amount">0</span> FCFA</h5>
                    @endcan
                    <h4>Total à payer : <span id="total-after-discount">0</span> FCFA</h4>
                </div>

                <!-- type de remise e remise -->
                @can('voir-remise')
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="discount-type">Type de remise</label>
                            <select id="discount-type" class="form-select" name="discount_type">
                                <option selected disabled value="">Selectionner</option>
                                <option value="percentage">Pourcentage</option>
                                <option value="amount">Montant fixe</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="total-discount">Valeur de la remise</label>
                            <input type="number" id="total-discount" name="total_discount" class="form-control" value="0"
                                min="0">
                        </div>
                    </div>
                @endcan


                <!-- Numéro de table et nombre de couverts -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="table-number">Numéro de table</label>
                        <select class="form-select" name="numero_table" id="table-number">
                            <option selected disabled value="">Selectionner</option>
                            @for ($i = 0; $i < 21; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        {{-- <input type="number" name="numero_table" id="table-number" class="form-control"
                        placeholder="Numéro de table" min="1"> --}}
                    </div>

                    <div class="col-md-6">
                        <label for="number-covers">Nombre de couverts</label>
                        <select class="form-select" name="nombre_couverts" id="number-covers">
                            <option selected disabled value="">Selectionner</option>
                            @for ($i = 1; $i < 21; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        {{-- <input type="number" name="nombre_couverts" id="number-covers" class="form-control"
                        value="1" min="1"> --}}
                    </div>
                </div>

                <!-- Informations de reglement et mode de reglement -->
                {{-- <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="payment-method">Mode du réglement</label>
                        <select id="payment-method" name="mode_paiement" class="form-select" required>
                            <option value="orange money">Orange Money</option>
                        <option value="moov money">Moov Money</option>
                        <option value="mtn money">MTN Money</option>
                        <option value="wave">Wave</option>
                        <option value="visa">Visa</option>
                        <option value="mastercard">MasterCard</option>
                            <option value="espece" selected>Espèce</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="received-amount">Montant réçu</label>
                        <input type="number" name="montant_recu" id="received-amount" class="form-control"
                            min="0" required>
                    </div>
                </div>

                <div class=" mt-3">
                    <h4>Monnaie rendu : <span id="change-amount">0</span> FCFA</h4>
                </div> --}}

                <!-- Bouton de validation -->
                <div class="mt-3">
                    <button type="button" id="validate-sale" class="btn btn-success w-100">Valider la vente</button>
                </div>

                <div class="mt-3">
                    {{-- <button type="button" id="validate-sale" class="btn btn-primary w-100">Mettre en attente</button> --}}
                    <button id="save-sale-in-local" class="btn btn-primary w-100">Mettre en attente</button>

                </div>


                {{-- <div class="mt-3">
                <button type="button" id="validate-sale" class="btn btn-primary w-100">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="sale-spinner"></span>
                    <span id="sale-text">Valider la vente</span>
                    
                </button>
                </div> --}}

            </div>
        </div>
        <!-- ========== End Total  ========== -->

    </div>
@endsection


@section('script')
    <script>
        $(document).ready(function() {

            let cart = []; // panier de vente ordinaire
            let totalDiscountType = 'percentage';
            let totalDiscountValue = 0;
            let grandTotal = 0;
            let dataProduct = @json($data_produit); // Données récupérées depuis le contrôleur

            $('.product-select').change(function() {

                let productId = $(this).val();
                let productName = $(this).find('option:selected').text();
                let productPrice = $(this).find('option:selected').data('price');
                let productStock = $(this).find('option:selected').data('stock');

                //recuperer les infos du produit selectionné 

                if (productId) {
                    addToCart(productId, productName, productPrice, productStock);
                    updateCartTable();
                    updateGrandTotal();
                    // verifyQty();

                    // Réinitialiser Select2 à l'option par défaut
                    $(this).val(null).trigger('change'); // Réinitialise Select2
                }

            });

            $('#discount-type').change(function() {
                totalDiscountType = $(this).val() || 0;
                updateGrandTotal();
            });

            $('#total-discount').on('input', function() {
                totalDiscountValue = parseFloat($(this).val() || 0);
                updateGrandTotal();
            });

            $('#received-amount').on('input', function() {
                updateChangeAmount();
            });



            function addToCart(id, name, price, stock, variante, varianteStock) {
                let existingItem = cart.find(item => item.id === id);
                selectedProd = dataProduct.find(dataItem => dataItem.id == id)

                // Cherche la valeur "offert" déjà présente
                let existingOffer = existingItem ? existingItem.offert : 0;

                if (existingItem && selectedProd.categorie.famille !== 'bar') {
                    existingItem.quantity += 1;
                    existingItem.selectedVariante = variante; // garde la variante sélectionnée
                    existingItem.offert = existingOffer; // garde la valeur offerte
                } else {
                    cart.push({
                        id: id,
                        name: name,
                        price: selectedProd.categorie.famille === 'bar' ? 0 : price,
                        stock: stock,
                        selectedVariante: variante ? variante : null,
                        varianteStock: variante ? variante.pivot.quantite_disponible : null,
                        quantity: 1,
                        discount: 0,
                        offert: 0 // valeur par défaut
                    });
                }
            }





            /* Fonction pour mettre à jour le tableau du panier*/
            // Met à jour le tableau du panier avec les produits ajoutés
            function updateCartTable() {
                let tbody = $('#cart-table tbody');
                tbody.empty();
                cart.forEach((item, index) => {
                    let selectedProduct = dataProduct.find(dataItem => dataItem.id == item.id);
                    let variantesOptions = '';
                    let varianteSelectHtml = '';



                    // Afficher que les quantité entieres
                    if (selectedProduct && selectedProduct.variantes) {
                        selectedProduct.variantes.forEach(variante => {
                            let isSelected = item.selectedVariante == variante.id ? 'selected' : '';
                            let isDisabled = variante.pivot.quantite_disponible < 1 ? 'disabled' :
                                '';
                            let isIndisponible = variante.pivot.quantite_disponible < 1 ?
                                ' - Indisponible' : '';
                            let quantiteDisponibleEntiere = parseInt(variante.pivot
                                .quantite_disponible);

                            variantesOptions += `
                            <option value="${variante.id}" data-qte="${quantiteDisponibleEntiere}" data-price="${variante.pivot.prix}" ${isSelected} ${isDisabled}>
                                ${variante.libelle} (${variante.pivot.prix} FCFA) (${quantiteDisponibleEntiere} Q)${isIndisponible}
                            </option>`;
                        });
                    }

                    // Affichage du champ select pour les variantes ou texte 'Plat entier'
                    if (selectedProduct && selectedProduct.categorie && selectedProduct.categorie
                        .famille === 'bar') {
                        //mettre le bouton increment en disabled
                        // disableQteInc = `<button class="btn btn-primary btn-sm decrease-qty" data-index="${index}" disabled>-</button>`
                        // disableQteDec = `<button class="btn btn-primary btn-sm increase-qty" data-index="${index}" disabled>+</button>`



                        varianteSelectHtml = `
                            <select   class="form-select form-control variante-select" data-index="${index}" required>
                            <option disabled value="" ${!item.selectedVariante ? 'selected' : ''}>Sélectionnez une variante</option>
                            
                                ${variantesOptions}
                            </select>`;



                    } else {
                        varianteSelectHtml = `<p>Plat entier</p>`;
                    }


                    // Ajoute une ligne pour chaque produit dans le tableau
                    tbody.append(`
                        <tr>
                            <td>
                                ${item.name} 
                                ${item.offert == 1 ? ' <span class="badge bg-success ms-2">Offert</span>' : ''}
                            </td>
                            <td>${varianteSelectHtml}</td>
                            <td class="price-cell">${item.price} FCFA</td>
                            <td class="d-flex justify-content-center align-items-center">
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-primary btn-sm decrease-qty" data-index="${index}">-</button>
                                    <input type="number" class="form-control quantity-input text-center mx-2" 
                                        value="${item.quantity}" min="0" step="any" style="width: 50px;" data-index="${index}">
                                    <button class="btn btn-secondary btn-sm increase-qty" data-index="${index}">+</button>
                                </div>
                            </td>
                            <td class="d-none">
                                <input type="number" class="form-control discount-input" value="${item.discount}" min="0" max="100" data-index="${index}">
                            </td>
                            <td class="total-cell">${calculateTotal(item)} FCFA
                                </td>

                            <!-- Nouvelle colonne "Offert" -->
                           <td class="text-center">
                                <select class="form-select form-select-sm offer-select" data-index="${index}">
                                    <option value="0" ${item.offert == 0 ? 'selected' : ''}>Non</option>
                                    <option value="1" ${item.offert == 1 ? 'selected' : ''}>Oui</option>
                                </select>
                                 
                           </td>

                            <!-- Colonne Action -->
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm remove-item" data-index="${index}">
                                    <i class="ri ri-delete-bin-2-fill"></i>
                                </button>
                            </td>
                        </tr>
                    `);

                });



                /*GESTION DES VARIANTES **/
                // Ajoute un événement de changement sur chaque select de variante pour mettre à jour la sélection
                tbody.find('.variante-select').change(function() {

                    // si une variante est choisie desactiver les boutons + et -
                    $(this).closest('tr').find('.increase-qty, .decrease-qty').prop('disabled', false);
                    let index = $(this).data('index');
                    let variantePrice = $(this).find('option:selected').data('price');
                    let varianteStock = $(this).find('option:selected').data('qte');
                    let selectedVarianteId = $(this).val();
                    let selectedProductId = cart[index].id;


                    // Vérifie si cette variante est déjà utilisée pour ce produit dans une autre ligne
                    let duplicateVariante = cart.some((item, i) => {
                        return (
                            i !== index && // Ne pas comparer avec la ligne actuelle
                            item.id === selectedProductId &&
                            item.selectedVariante == selectedVarianteId
                        );
                    });

                    // if (duplicateVariante) {
                    //     Swal.fire({
                    //         icon: 'warning',
                    //         title: 'Attention !',
                    //         text: 'Cette variante est déjà sélectionnée pour le même produit.' +
                    //             cart[index].name,
                    //     });
                    //     $(this).val(''); // Réinitialiser la sélection
                    //     cart[index].selectedVariante = null;
                    //     cart[index].price = 0;
                    //     cart[index].varianteStock = 0;

                    //     $(this).closest('tr').find('.price-cell').text('0 FCFA');
                    //     $(this).closest('tr').find('.total-cell').text('0 FCFA');
                    //     updateGrandTotal();
                    //     return; // Sortir pour éviter de continuer avec une valeur invalide
                    // }


                    if (variantePrice) {
                        // Met à jour le prix et la variante sélectionnée dans le panier
                        // cart[index].price = cart[index].offert === 1 ? 0 : variantePrice;
                        cart[index].price = variantePrice;
                        cart[index].selectedVariante = selectedVarianteId;
                        cart[index].varianteStock = varianteStock;

                        // Met à jour l'affichage des prix dans la ligne
                        $(this).closest('tr').find('.price-cell').text(variantePrice + ' FCFA');
                        $(this).closest('tr').find('.total-cell').text(calculateTotal(cart[index]) +
                            ' FCFA');
                        updateGrandTotal();
                        verifyQty();

                    }



                });




                function handleOfferChange(index, value) {
                    cart[index].offert = parseInt(value);

                    if (cart[index].offert === 0) {
                        // Si une variante est sélectionnée, on remet son prix
                        let selectedVariante = cart[index].selectedVariante;
                        if (selectedVariante) {
                            const varianteOption = $(
                                `.variante-select[data-index="${index}"] option[value="${selectedVariante}"]`);
                            const variantePrice = parseFloat(varianteOption.data('price'));

                            if (!isNaN(variantePrice)) {
                                cart[index].price = variantePrice;
                            }
                        }
                    }

                    updateCartTable(); // on met à jour le tableau dans tous les cas
                    updateGrandTotal();
                }



                // Gestion de l'événement de changement pour le select des offres
                $(document).on('change', '.offer-select', function() {
                    const index = $(this).data('index');
                    const value = $(this).val();
                    handleOfferChange(index, value);
                });


            }

            function calculateTotal(item) {
                if (item.offert === 1) {
                    return 0;
                }

                let discountAmount = (item.price * item.quantity) * (item.discount / 100);
                return (item.price * item.quantity) - discountAmount;
            }


            // function calculateTotal(item) {
            //     let discountAmount = (item.price * item.quantity) * (item.discount / 100);
            //     return (item.price * item.quantity) - discountAmount;
            // }





            function updateGrandTotal(totalAddPlatMenu = null) {

                // grand total est le total vente ordinaire
                grandTotal = cart.reduce((sum, item) => sum + calculateTotal(item), 0);
                let discountAmount = 0;

                // recuperer le total de menu
                let totalMenu = $('#totalAmount').text().replace(/\s/g, '');
                let totalMenuValue = parseFloat(totalMenu); // total des plats du menu



                // calculer le total net menu + ordinaire
                let totalNet = grandTotal + totalMenuValue;
                // afficher
                $('#totalNet').text(totalNet);



                // Calculer le montant de la remise
                if (totalDiscountType === 'percentage') {

                    if (totalDiscountValue > 100) {
                        $('#total-discount').val(100);
                        totalDiscountValue = 100;

                    }
                    discountAmount = (totalNet * totalDiscountValue) / 100;

                } else if (totalDiscountType === 'amount') {
                    discountAmount = totalDiscountValue;
                }

                // Si totalAddPlatMenu est null, on le considère comme 0
                totalAddPlatMenu = totalAddPlatMenu !== null ? totalAddPlatMenu : 0;

                // total apres reduction 
                let totalAfterDiscount = totalNet - discountAmount;
                totalAfterDiscount = totalAfterDiscount < 0 ? 0 : totalAfterDiscount;

                // Fonction pour extraire un nombre depuis une chaîne formatée
                //  function parseFormattedNumber(numberString) {
                //                 return parseFloat(numberString.replace(/\s/g, '').replace(',', '.')) || 0;
                //             }


                $('#grand-total').text(grandTotal); // total vente ordinaire
                $('#discount-amount').text(discountAmount);
                $('#total-after-discount').text(totalAfterDiscount);

                updateChangeAmount();
            }


            // Expose the function to the global scope
            window.updateGrandTotal = updateGrandTotal;


            // calcul du montant reçu
            function updateChangeAmount() {
                let receivedAmount = parseFloat($('#received-amount').val() || 0);
                let totalAfterDiscount = parseFloat($('#total-after-discount').text());
                let changeAmount = receivedAmount - totalAfterDiscount;

                $('#change-amount').text(changeAmount < 0 ? 0 : changeAmount);
            }

            //fonction pour verifier la quantité saisir
            function verifyQty() {
                var dataProduct = @json($data_produit);
                var allQuantitiesValid = true; // Pour suivre si toutes les quantités sont valides

                cart.forEach((item) => {
                    var product = dataProduct.find(dataItem => dataItem.id == item.id);
                    // console.log(item);

                    if (item.quantity > item.varianteStock && product.categorie.famille == 'bar') {
                        $('#errorMessage').text(
                            'La quantité entrée dépasse la quantité en stock pour le produit "' + item
                            .name + '"'
                        );
                        $('.alert').removeClass('d-none');

                        allQuantitiesValid =
                            false; // Marquer comme invalide si une quantité dépasse le stock

                    }
                    // si la quantité est égale au stock alors empecher d'augmenter
                    if (item.quantity == item.varianteStock && product.categorie.famille == 'bar') {
                        $('.increase-qty[data-index="' + cart.indexOf(item) + '"]').prop('disabled', true);
                    }

                });

                // Si toutes les quantités sont valides, masquer l'alerte
                if (allQuantitiesValid) {
                    $('.alert').addClass('d-none');
                }

                // Activer ou désactiver le bouton selon la validité des quantités
                $('#validate-sale').prop('disabled', !allQuantitiesValid);
            }



            $(document).on('click', '.increase-qty', function() {
                let index = $(this).data('index');
                let stock = cart[index].stock;
                cart[index].quantity += 1;
                updateCartTable();
                updateGrandTotal();
                // recuperer la quantité saisir 
                // verifyQty(cart[index].quantity);
                verifyQty();
            });

            $(document).on('click', '.decrease-qty', function() {
                let index = $(this).data('index');
                if (cart[index].quantity > 1) {
                    cart[index].quantity -= 1;
                    updateCartTable();
                    updateGrandTotal();
                    verifyQty();
                }
            });



            // changer la quantité manuellement
            $(document).on('input', '.quantity-input', function() {
                let index = $(this).data('index');
                let value = $(this).val();
                // console.log('Index:', index, 'Cart Item:', cart[index]);

                if (cart[index] && value > 0) { // Vérifie si cart[index] existe
                    cart[index].quantity = parseFloat(value);
                    // updateCartTable();
                    updateGrandTotal();
                    verifyQty();
                }
            });


            $(document).on('change', '.quantity-input', function() {
                let index = $(this).data('index');
                let newQuantity = parseInt($(this).val());
                if (newQuantity >= 1) {
                    cart[index].quantity = newQuantity;
                    updateCartTable();
                    updateGrandTotal();
                }
            });

            $(document).on('change', '.discount-input', function() {
                let index = $(this).data('index');
                let newDiscount = parseInt($(this).val());
                cart[index].discount = newDiscount;
                updateCartTable();
                updateGrandTotal();
            });




            $(document).on('click', '.remove-item', function() {
                let index = $(this).data('index');
                cart.splice(index, 1);
                updateCartTable();
                updateGrandTotal();
                verifyQty()
            });





            //################################### DEPLACEMENT DE LA VALIDATION DE LA VENTE(ANCIENNE FONCTION dans le vente.partial)
            //###################################GESTION DE LA VALIDATION DE LA VENTE
            //###################################OPTIMISATION DE LA VALIDATION DE LA VENTE


            // Fonction pour vérifier la validation de la vente
            // Vérifie si les plats du menu sont correctement sélectionnés et validés
            function checkSaleValidation() {
                let validationEchouee = false;
                let panier = [];

                const plats = document.querySelectorAll('.plat-checkbox:checked');

                plats.forEach((plat) => {
                    const platId = plat.value;
                    const platNom = plat.nextElementSibling.textContent.trim();
                    const platQuantite = parseInt(plat.closest('.form-check').querySelector('.quantityPlat')
                        .value);
                    const prixPlat = plat.getAttribute('data-price');

                    const complements = [];
                    const garnitures = [];
                    let complementManquant = false;
                    let garnitureManquante = false;

                    const complementCheckboxes = plat.closest('.card-body').querySelectorAll(
                        '.complement-checkbox');
                    let totalQuantiteComplements = 0;
                    complementCheckboxes.forEach((complement) => {
                        if (complement.checked) {
                            const quantite = parseInt(complement.closest('.form-check')
                                .querySelector('.quantityComplement').value);
                            totalQuantiteComplements += quantite;
                            complements.push({
                                id: complement.value,
                                nom: complement.nextElementSibling.textContent.trim(),
                                quantity: quantite
                            });
                        }
                    });

                    if (complementCheckboxes.length > 0 && complements.length === 0) {
                        validationEchouee = true;
                        Swal.fire({
                            icon: 'error',
                            title: 'Attention',
                            text: 'Veuillez sélectionner au moins un complément pour le plat : ' +
                                platNom
                        });
                        return;
                    }

                    const garnitureCheckboxes = plat.closest('.card-body').querySelectorAll(
                        '.garniture-checkbox');
                    let totalQuantiteGarnitures = 0;
                    garnitureCheckboxes.forEach((garniture) => {
                        if (garniture.checked) {
                            const quantite = parseInt(garniture.closest('.form-check')
                                .querySelector('.quantityGarniture').value);
                            totalQuantiteGarnitures += quantite;
                            garnitures.push({
                                id: garniture.value,
                                nom: garniture.nextElementSibling.textContent.trim(),
                                quantity: quantite
                            });
                        }
                    });

                    if (garnitureCheckboxes.length > 0 && garnitures.length === 0) {
                        validationEchouee = true;
                        Swal.fire({
                            icon: 'error',
                            title: 'Attention',
                            text: 'Veuillez sélectionner au moins une garniture pour le plat : ' +
                                platNom
                        });
                        return;
                    }

                    if (complements.length > 0 && totalQuantiteComplements !== platQuantite) {
                        validationEchouee = true;
                        Swal.fire({
                            icon: 'error',
                            title: 'Quantité invalide',
                            text: `La somme des quantités des compléments doit être égale à ${platQuantite} pour le plat : ${platNom}`
                        });
                        return;
                    }

                    if (garnitures.length > 0 && totalQuantiteGarnitures !== platQuantite) {
                        validationEchouee = true;
                        Swal.fire({
                            icon: 'error',
                            title: 'Quantité invalide',
                            text: `La somme des quantités des garnitures doit être égale à ${platQuantite} pour le plat : ${platNom}`
                        });
                        return;
                    }

                    panier.push({
                        plat: {
                            id: platId,
                            nom: platNom,
                            quantity: platQuantite,
                            price: prixPlat
                        },
                        complements,
                        garnitures,
                    });
                });

                // Vérification des variantes pour les produits du bar
                cart.forEach((item) => {
                    let data = dataProduct.find(dataItem => dataItem.id == item.id);
                    let famille = data.categorie.famille;
                    let name = data.nom;
                    if (item.selectedVariante === null && famille === 'bar') {
                        validationEchouee = true;
                        Swal.fire({
                            icon: 'error',
                            title: 'Attention',
                            text: 'Veuillez choisir une variante pour ' + name
                        });
                        return;
                    }
                });

                if (cart.length === 0 && panier.length === 0) {
                    validationEchouee = true;
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Vous devez ajouter au moins un produit au panier.'
                    });
                }

                return {
                    validationEchouee,
                    panier
                };
            }



            // Fonction pour valider la vente
            // Envoie les données du panier et des plats du menu au serveur pour validation
            function validateSale(panier) {
                let submitButton = $('#validate-sale');

                submitButton.prop('disabled', true).html(`
                    <span class="d-flex align-items-center">
                        <span class="spinner-border flex-shrink-0" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </span>
                        <span class="flex-grow-1 ms-2">Enregistrement en cours...</span>
                    </span>
                `);

                let montantVenteOrdinaire = parseFloat($('#grand-total').text() || 0);
                let montantVenteMenu = parseFloat($('#totalAmount').text() || 0);
                let montantNet = parseFloat($('#totalNet').text() || 0);
                let montantApresRemise = parseFloat($('#total-after-discount').text() || 0);
                let montantRemise = parseFloat($('#discount-amount').text() || 0);
                let typeRemise = $('#discount-type').val();
                let valeurRemise = $('#total-discount').val();
                let modePaiement = $('#payment-method').val();
                let montantRecu = parseFloat($('#received-amount').val() || 0);
                let montantRendu = parseFloat($('#change-amount').text() || 0);
                let numeroDeTable = $('#table-number').val();
                let nombreDeCouverts = $('#number-covers').val();

                $.ajax({
                    url: '{{ route('vente.store') }}',
                    type: 'POST',
                    data: {
                        cart: cart,
                        cartMenu: panier,
                        montantVenteOrdinaire,
                        montantVenteMenu,
                        montantAvantRemise: montantNet,
                        montantApresRemise,
                        montantRemise,
                        typeRemise,
                        valeurRemise,
                        modePaiement,
                        montantRecu,
                        montantRendu,
                        numeroDeTable,
                        nombreDeCouverts,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Vente validée avec succès !',
                            text: 'Passer au règlement de la vente',
                            icon: 'success',
                            confirmButtonText: 'Voir les détails de la vente',
                        }).then(() => {
                            cart = [];
                            updateCartTable();
                            updateGrandTotal();
                            $('#received-amount').val(0);
                            $('#table-number').val('');
                            $('#number-covers').val(1);

                            window.location.href = '{{ route('vente.show', ':idVente') }}'
                                .replace(':idVente', response.idVente);
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Erreur',
                            text: xhr.responseJSON.message ||
                                'Une erreur est survenue lors de la validation.',
                            icon: 'error',
                        });

                        submitButton.prop('disabled', false).html('Valider la vente');
                    }
                });
            }

            // Fonction pour vérifier la validation de la vente avant de soumettre
            // Affiche une alerte si la validation échoue
            $('#validate-sale').click(function(e) {
                e.preventDefault();

                const {
                    validationEchouee,
                    panier
                } = checkSaleValidation();

                if (validationEchouee) return;

                Swal.fire({
                    title: 'Voulez-vous valider la vente ?',
                    text: "Une vente validée ne peut plus être modifiée.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, valider la vente',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        validateSale(panier);
                    }
                });
            });



            $('#save-sale-in-local').click(function() {
                Swal.fire({
                    title: 'Mettre en attente ?',
                    text: 'Voulez-vous vraiment mettre cette vente en attente ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, mettre en attente',
                    cancelButtonText: 'Annuler',
                    reverseButtons: true
                }).then((confirmResult) => {
                    if (confirmResult.isConfirmed) {
                        // 🟢 Traitement ici
                        const plats = document.querySelectorAll('.plat-checkbox:checked');
                        let panier = [];

                        plats.forEach((plat) => {
                            const platId = plat.value;
                            const platNom = plat.nextElementSibling.textContent.trim();
                            const platQuantite = parseInt(plat.closest('.form-check')
                                .querySelector('.quantityPlat').value);
                            const prixPlat = plat.getAttribute('data-price');

                            const complements = [];
                            const garnitures = [];

                            // Compléments
                            const complementCheckboxes = plat.closest('.card-body')
                                .querySelectorAll('.complement-checkbox');
                            complementCheckboxes.forEach((complement) => {
                                if (complement.checked) {
                                    const quantite = parseInt(complement.closest(
                                        '.form-check').querySelector(
                                        '.quantityComplement').value);
                                    complements.push({
                                        id: complement.value,
                                        nom: complement.nextElementSibling
                                            .textContent.trim(),
                                        quantity: quantite,
                                    });
                                }
                            });

                            // Garnitures
                            const garnitureCheckboxes = plat.closest('.card-body')
                                .querySelectorAll('.garniture-checkbox');
                            garnitureCheckboxes.forEach((garniture) => {
                                if (garniture.checked) {
                                    const quantite = parseInt(garniture.closest(
                                        '.form-check').querySelector(
                                        '.quantityGarniture').value);
                                    garnitures.push({
                                        id: garniture.value,
                                        nom: garniture.nextElementSibling
                                            .textContent.trim(),
                                        quantity: quantite,
                                    });
                                }
                            });

                            panier.push({
                                plat: {
                                    id: platId,
                                    nom: platNom,
                                    quantity: platQuantite,
                                    price: prixPlat
                                },
                                complements,
                                garnitures,
                            });
                        });

                        const venteTemp = {
                            cart: cart,
                            cartMenu: panier,
                            tableNumber: $('#table-number').val() || '',
                            covers: $('#number-covers').val() || 0,
                            date: new Date().toISOString(),
                            montantAvantRemise: parseFloat($('#totalNet').text() || 0),
                            montantApresRemise: parseFloat($('#total-after-discount').text() ||
                                0),
                            montantRemise: parseFloat($('#discount-amount').text() || 0),
                            typeRemise: $('#discount-type').val(),
                            valeurRemise: $('#total-discount').val(),
                            montantRecu: parseFloat($('#received-amount').val() || 0),
                            montantRendu: parseFloat($('#change-amount').text() || 0),
                            modePaiement: $('#payment-method').val()
                        };

                        let ventes = JSON.parse(localStorage.getItem('ventes_en_attente')) || [];
                        ventes.push({
                            id: Date.now(),
                            data: venteTemp
                        });
                        localStorage.setItem('ventes_en_attente', JSON.stringify(ventes));

                        // Réinitialisation
                        cart = [];
                        panier = [];
                        updateCartTable();
                        updateGrandTotal();
                        $('#table-number').val('');
                        $('#number-covers').val('');

                        // 🔄 Nouvelle alerte de choix après mise en attente
                        Swal.fire({
                            icon: 'info',
                            title: 'Vente mise en attente',
                            text: 'Que souhaitez-vous faire ?',
                            showCancelButton: true,
                            confirmButtonText: 'Nouvelle vente',
                            cancelButtonText: 'Liste des ventes',
                            reverseButtons: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: 'Chargement...',
                                    html: 'Redirection vers une nouvelle vente',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                        setTimeout(() => {
                                            window.location.href =
                                                "{{ route('vente.create') }}";
                                        }, 1000);
                                    }
                                });
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                Swal.fire({
                                    title: 'Chargement...',
                                    html: 'Redirection vers la liste des ventes',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                        setTimeout(() => {
                                            window.location.href =
                                                "{{ route('vente.index') }}";
                                        }, 1000);
                                    }
                                });
                            }
                        });
                    }
                });
            });


            // ========== 4. Restaurer la vente en cours ==========
            // Recharger la vente en cours

            const data = localStorage.getItem('vente_en_cours');
            if (!data) return;

            const vente = JSON.parse(data);

            // ========== 1. Recharger les produits classiques (cart) ==========
            const cartData = vente.data.cart || [];
            cart = cartData; // tu réinjectes dans la variable globale cart
            updateCartTable();
            updateGrandTotal();

            // ========== 2. Recharger les plats menus (cartMenu) ==========
            const cartMenu = vente.data.cartMenu || [];
            panier = cartMenu; // injecter dans la variable globale "panier"

            cartMenu.forEach(item => {
                const platId = item.plat.id;
                const platQuantity = item.plat.quantity;

                // Coche le plat
                const platCheckbox = document.querySelector(`.plat-checkbox[value="${platId}"]`);
                if (platCheckbox) {
                    platCheckbox.checked = true;

                    // Quantité du plat
                    const quantityInput = platCheckbox.closest('.form-check').querySelector(
                        '.quantityPlat');
                    if (quantityInput) quantityInput.value = platQuantity;

                    // Compléments
                    item.complements.forEach(complement => {
                        const checkbox = document.querySelector(
                            `.complement-checkbox[data-plat-id="${platId}"][value="${complement.id}"]`
                        );
                        if (checkbox) {
                            checkbox.checked = true;
                            const input = checkbox.closest('.form-check').querySelector(
                                '.quantityComplement');
                            if (input) input.value = complement.quantity;
                        }
                    });

                    // Garnitures
                    item.garnitures.forEach(garniture => {
                        const checkbox = document.querySelector(
                            `.garniture-checkbox[data-plat-id="${platId}"][value="${garniture.id}"]`
                        );
                        if (checkbox) {
                            checkbox.checked = true;
                            const input = checkbox.closest('.form-check').querySelector(
                                '.quantityGarniture');
                            if (input) input.value = garniture.quantity;
                        }
                    });
                }
            });

            // ========== 3. Recharger les champs supplémentaires ==========
            $('#table-number').val(vente.data.tableNumber ?? '');
            $('#number-covers').val(vente.data.covers ?? 1);
            $('#discount-type').val(vente.data.typeRemise ?? '');
            $('#total-discount').val(vente.data.valeurRemise ?? 0);
            $('#discount-amount').text(vente.data.montantRemise ?? 0);
            $('#total-after-discount').text(vente.data.montantApresRemise ?? 0);
            $('#totalNet').text(vente.data.montantAvantRemise ?? 0);
            $('#payment-method').val(vente.data.modePaiement ?? '');
            $('#received-amount').val(vente.data.montantRecu ?? 0);
            $('#change-amount').text(vente.data.montantRendu ?? 0);

            // Nettoyer le localStorage après rechargement
            localStorage.removeItem('vente_en_cours');

            Swal.fire({
                icon: 'info',
                title: 'Vente rechargée',
                text: 'La vente a été restaurée avec succès.'
            });



            // ########################### Fonction pour déclencher le recalcul du menu depuis le stockage local
            // Cette fonction est appelée pour recalculer le menu en cas de changement dans les plats 
            function triggerMenuRecalculFromStorage() {
                document.querySelectorAll('.plat-checkbox:checked').forEach(cb => cb.dispatchEvent(new Event(
                    'change')));
                document.querySelectorAll('.quantityPlat').forEach(input => input.dispatchEvent(new Event(
                    'change')));

                document.querySelectorAll('.complement-checkbox:checked').forEach(cb => cb.dispatchEvent(new Event(
                    'change')));
                document.querySelectorAll('.quantityComplement').forEach(input => input.dispatchEvent(new Event(
                    'change')));

                document.querySelectorAll('.garniture-checkbox:checked').forEach(cb => cb.dispatchEvent(new Event(
                    'change')));
                document.querySelectorAll('.quantityGarniture').forEach(input => input.dispatchEvent(new Event(
                    'change')));

                if (typeof updateTotalPrice === 'function') updateTotalPrice();
            }

            // Déclencher le recalcul du menu
            setTimeout(() => {
                triggerMenuRecalculFromStorage();
            }, 200);

        });
    </script>
    <script src="{{ URL::asset('myJs/js/create-vente-menu.js') }}"></script>
@endsection
