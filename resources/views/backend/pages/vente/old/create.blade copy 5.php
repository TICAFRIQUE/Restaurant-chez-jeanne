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
                            <a class="nav-link w-100" href="{{route('commande.index' , ['filter'=>'en attente'])}}">
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
                                                <select name="produit_id" class="form-select js-example-basic-single product-select">
                                                    <option value="">Sélectionnez un produit</option>
                                                    @foreach ($data_produit as $produit)
                                                        @if ($produit->stock <= 0 && $produit->categorie->famille == 'bar')
                                                            <option value="{{ $produit->id }}" data-price="{{ $produit->prix }}"
                                                                data-stock="{{ $produit->stock }}" disabled>
                                                                {{ $produit->nom }} {{ $produit->valeur_unite ?? '' }}
                                                                {{ $produit->unite->libelle ?? '' }}
                                                                {{ $produit->unite ? '(' . $produit->unite->abreviation . ')' : '' }}
                                                                ({{ $produit->prix }} FCFA)
                                                                - <span style="color: red" class="text-danger">(Stock: {{{$produit->stock}}})</span>
                                                            </option>
                                                        @else
                                                            <option value="{{ $produit->id }}" data-price="{{ $produit->prix }}"
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
                                                        {{-- <th>Remise (%)</th> --}}
                                                        <th>Total</th>
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


                <h5>Remise : <span id="discount-amount">0</span> FCFA</h5>
                <h4>Total à payer : <span id="total-after-discount">0</span> FCFA</h4>
            </div>

            <!-- Seleção do tipo de remise e remise -->
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
                    <input type="number" id="total-discount" name="total_discount" class="form-control"
                        value="0" min="0">
                </div>
            </div>


            <!-- Numéro de table et nombre de couverts -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="table-number">Numéro de table</label>
                    <input type="number" name="numero_table" id="table-number" class="form-control"
                        placeholder="Numéro de table" min="1">
                </div>

                <div class="col-md-6">
                    <label for="number-covers">Nombre de couverts</label>
                    <input type="number" name="nombre_couverts" id="number-covers" class="form-control"
                        value="1" min="1">
                </div>
            </div>

            <!-- Informations de<|pad|>glement -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="payment-method">Mode du réglement</label>
                    <select id="payment-method" name="mode_reglement" class="form-select" required>
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
            </div>

            <!-- Bouton de validation -->
            <div class="mt-3">
                <button type="button" id="validate-sale"  class="btn btn-primary w-100">Valider la vente</button>
            </div>
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
            var dataProduct = @json($data_produit); // Données récupérées depuis le contrôleur

            $('.product-select').change(function() {
                
                let productId = $(this).val();
                let productName = $(this).find('option:selected').text();
                let productPrice = $(this).find('option:selected').data('price');
                let productStock = $(this).find('option:selected').data('stock');
                // let productVariante = $(this).find('option:selected').val();

                // console.log('id:', productId, 'nom:', productName, 'prix:', productPrice, 'stock:',
                //     productStock, 'variante:', productVariante);

                if (productId) {
                    addToCart(productId, productName, productPrice, productStock);
                    updateCartTable();
                    updateGrandTotal();
                    verifyQty();

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

            function addToCart(id, name, price, stock, variante , varianteStock) {
                let existingItem = cart.find(item => item.id === id);
                if (existingItem) {
                    existingItem.quantity += 1;
                    existingItem.selectedVariante = variante; // garde la variante sélectionnée
                } else {
                    cart.push({
                        id: id,
                        name: name,
                        price: 0,
                        stock: stock,
                        selectedVariante: variante ? variante : null, // ajoute la variante choisie ou choisi la variante dans le select
                        varianteStock: variante ? variante.pivot.quantite_disponible : null,
                        quantity: 1,
                        discount: 0
                    });
                }
                console.log('panier : ', cart);
            }




            function updateCartTable() {
                let tbody = $('#cart-table tbody');
                tbody.empty();

                cart.forEach((item, index) => {
                    let selectedProduct = dataProduct.find(dataItem => dataItem.id == item.id);
                    let variantesOptions = '';
                    let varianteSelectHtml = '';

                    if (selectedProduct && selectedProduct.variantes) {
                        selectedProduct.variantes.forEach(variante => {
                            // Garde la sélection de la variante dans le tableau affiché
                            let isSelected = item.selectedVariante == variante.id ? 'selected' :
                                '';
                            variantesOptions += `
                <option value="${variante.id}" data-qte="${variante.pivot.quantite_disponible}" data-price="${variante.pivot.prix}" ${isSelected}>
                    ${variante.libelle} (${variante.pivot.prix} FCFA) (${variante.pivot.quantite_disponible} Q)
                </option>`;

                        });

                    }

                    // Affichage du champ select pour les variantes ou texte 'Plat entier'
                    if (selectedProduct && selectedProduct.categorie && selectedProduct.categorie
                        .famille === 'bar') {
                        varianteSelectHtml = `
            <select  class="form-select form-control variante-select" data-index="${index}">
                  <option disabled value="" ${!item.selectedVariante ? 'selected' : ''}>Sélectionnez une variante</option>
              
                ${variantesOptions}
            </select>`;
                    } else {
                        varianteSelectHtml = `<p>Plat entier</p>`;
                    }

                    // Ajoute une ligne pour chaque produit dans le tableau
                    tbody.append(`
        <tr>
    <td>${item.name}</td>
    <td>${varianteSelectHtml}</td>
    <td class="price-cell">${item.price} FCFA</td>
    <td class="d-flex justify-content-center align-items-center">
    <div class="d-flex align-items-center">
        <button class="btn btn-primary btn-sm decrease-qty" data-index="${index}">-</button>
        <input readonly type="number" class="form-control quantity-input text-center mx-2" 
               value="${item.quantity}" min="1" style="width: 50px;">
        <button class="btn btn-secondary btn-sm increase-qty" data-index="${index}">+</button>
    </div>
</td>

    <td class="d-none">
        <input type="number" class="form-control discount-input" value="${item.discount}" min="0" max="100" data-index="${index}">
    </td>
    <td class="total-cell">${calculateTotal(item)} FCFA</td>
    <td>
        <button class="btn btn-danger btn-sm remove-item" data-index="${index}"> 
            <i class="ri ri-delete-bin-2-fill"></i> 
        </button>
    </td>
</tr>

    `);
                });

                // Ajoute un événement de changement sur chaque select de variante pour mettre à jour la sélection
                tbody.find('.variante-select').change(function() {
                    let index = $(this).data('index');
                    let variantePrice = $(this).find('option:selected').data('price');
                    let varianteStock = $(this).find('option:selected').data('qte');
                    let selectedVarianteId = $(this).val();

                    // console.log(variantePrice, varianteStock, selectedVarianteId);
                    

                    if (variantePrice) {
                        // Met à jour le prix et la variante sélectionnée dans le panier
                        cart[index].price = variantePrice;
                        cart[index].selectedVariante = selectedVarianteId;
                        cart[index].varianteStock = varianteStock;

                        // Met à jour l'affichage des prix dans la ligne
                        $(this).closest('tr').find('.price-cell').text(variantePrice + ' FCFA');
                        $(this).closest('tr').find('.total-cell').text(calculateTotal(cart[index]) +
                            ' FCFA');
                        updateGrandTotal();
                    }
                });
            }


            function calculateTotal(item) {
                let discountAmount = (item.price * item.quantity) * (item.discount / 100);
                return (item.price * item.quantity) - discountAmount;
            }
            

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

              
               

                if (totalDiscountType === 'percentage') {
                    discountAmount = (totalNet * totalDiscountValue) / 100 ;
                } else if (totalDiscountType === 'amount') {
                    discountAmount = totalDiscountValue;
                }

                 // Si totalAddPlatMenu est null, on le considère comme 0
                totalAddPlatMenu = totalAddPlatMenu !== null ? totalAddPlatMenu : 0;

                // total apres reduction 
                let totalAfterDiscount = totalNet - discountAmount   ;
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

          

            function verifyQty() {
                var dataProduct = @json($data_produit);
                var allQuantitiesValid = true; // Pour suivre si toutes les quantités sont valides

                cart.forEach((item) => {
                    var product = dataProduct.find(dataItem => dataItem.id == item.id);

                    if (item.quantity > item.varianteStock) {
                        $('#errorMessage').text(
                            'La quantité entrée dépasse la quantité en stock pour le produit "' + item
                            .name + '"'
                        );
                        $('.alert').removeClass('d-none');

                        allQuantitiesValid =
                            false; // Marquer comme invalide si une quantité dépasse le stock

                    }
                    // si la quantité est égale au stock alors empecher d'augmenter
                    if (item.quantity == item.varianteStock) {
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




//////###FONCTION POUR LA VALIDATION MENU  ##########/////

            //////### END FONCTION POUR LA VALIDATION MENU  ##########/////

            $('#validate-sale').click(function(e) {
               
                const plats = document.querySelectorAll('.plat-checkbox:checked');
             let panier = []; // panier vente menu

        let validationEchouee = false;

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

            // Compléments
            const complementCheckboxes = plat.closest('.card-body').querySelectorAll(
                '.complement-checkbox');
            let totalQuantiteComplements = 0;
            complementCheckboxes.forEach((complement) => {
                if (complement.checked) {
                    const quantite = parseInt(complement.closest('.form-check').querySelector(
                        '.quantityComplement').value);
                    totalQuantiteComplements += quantite;
                    complements.push({
                        id: complement.value,
                        nom: complement.nextElementSibling.textContent.trim(),
                        quantity: quantite,
                    });
                }
            });

            if (complementCheckboxes.length > 0 && complements.length === 0) {
                complementManquant = true;
            }

            // Garnitures
            const garnitureCheckboxes = plat.closest('.card-body').querySelectorAll(
                '.garniture-checkbox');
            let totalQuantiteGarnitures = 0;
            garnitureCheckboxes.forEach((garniture) => {
                if (garniture.checked) {
                    const quantite = parseInt(garniture.closest('.form-check').querySelector(
                        '.quantityGarniture').value);
                    totalQuantiteGarnitures += quantite;
                    garnitures.push({
                        id: garniture.value,
                        nom: garniture.nextElementSibling.textContent.trim(),
                        quantity: quantite,
                    });
                }
            });

            if (garnitureCheckboxes.length > 0 && garnitures.length === 0) {
                garnitureManquante = true;
            }

            // Vérification des compléments et garnitures manquants
            if (complementManquant || garnitureManquante) {
                validationEchouee = true;
                const message = complementManquant ?
                    'Veuillez sélectionner au moins un complément pour le plat : ' + platNom :
                    'Veuillez sélectionner au moins une garniture pour le plat : ' + platNom;

                Swal.fire({
                    icon: 'error',
                    title: 'Attention',
                    text: message,
                });
                return;
            }

            // Vérification des quantités des compléments et garnitures
            if (complements.length > 0 && totalQuantiteComplements !== platQuantite) {
                validationEchouee = true;
                Swal.fire({
                    icon: 'error',
                    title: 'Quantité invalide',
                    text: `La somme des quantités des compléments doit être égale à ${platQuantite} pour le plat : ${platNom}`,
                });
                return;
            }

            if (garnitures.length > 0 && totalQuantiteGarnitures !== platQuantite) {
                validationEchouee = true;
                Swal.fire({
                    icon: 'error',
                    title: 'Quantité invalide',
                    text: `La somme des quantités des garnitures doit être égale à ${platQuantite} pour le plat : ${platNom}`,
                });
                return;
            }

        
                        // Panier du Menu
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
                
        if (validationEchouee) {
            return; // Stopper l'exécution si une validation échoue
        }
        
                
                let montantVenteOrdinaire = parseFloat($('#grand-total').text() || 0); // montant  de vente ordinaire
                let montantVenteMenu = parseFloat($('#totalAmount').text() || 0); // montant  de vente menu
                let montantNet = parseFloat($('#totalNet').text() || 0); // montant  de vente menu
                let montantApresRemise = parseFloat($('#total-after-discount').text() || 0); // total apres remise
                let montantRemise = parseFloat($('#discount-amount').text() || 0);
                let typeRemise = $('#discount-type').val();
                let valeurRemise = $('#total-discount').val();
                let modePaiement = $('#payment-method').val();
                let montantRecu = parseFloat($('#received-amount').val() || 0);
                let montantRendu = parseFloat($('#change-amount').text() || 0);
                let numeroDeTable = $('#table-number').val();
                let nombreDeCouverts = $('#number-covers').val();

                if (cart.length === 0  && panier.length === 0  ) {
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Vous devez ajouter au moins un produit au panier.',
                        icon: 'error',
                    });
                    return;
                }

                if (montantRecu < montantApresRemise) {
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Le montant reçu est inférieur au montant à payer.',
                        icon: 'error',
                    });
                    return;
                }

                $.ajax({
                    url: '{{ route('vente.store') }}',
                    type: 'POST',
                    data: {
                        cart: cart,
                        cartMenu: panier,
                        montantVenteOrdinaire: montantVenteOrdinaire,
                        montantVenteMenu: montantVenteOrdinaire,
                        montantAvantRemise: montantNet,
                        montantApresRemise: montantApresRemise,
                        montantRemise: montantRemise,
                        typeRemise: typeRemise,
                        valeurRemise: valeurRemise,
                        modePaiement: modePaiement,
                        montantRecu: montantRecu,
                        montantRendu: montantRendu,
                        numeroDeTable: numeroDeTable,
                        nombreDeCouverts: nombreDeCouverts,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Vente validée avec succès !',
                            text: response.message,
                            icon: 'success',
                        }).then(() => {
                            // Réinitialiser le panier après la vente réussie
                            cart = []; // Réinitialiser le panier après validation
                                updateCartTable();
                                updateGrandTotal();
                                $('#received-amount').val(0); // Réinitialiser les champs
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
                                'Une erreur s\'est produite lors de la validation de la vente.',
                            icon: 'error',
                        });
                    }
                });
            });
        });
    </script>

<script src="{{ URL::asset('myJs/js/create-vente-menu.js') }}"></script>
@endsection
