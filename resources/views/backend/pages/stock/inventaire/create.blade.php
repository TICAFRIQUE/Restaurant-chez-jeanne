@extends('backend.layouts.master')
@section('title')
    Inventaire
@endsection
@section('content')


    @component('backend.components.breadcrumb')
        <link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">

        @slot('li_1')
            Stock
        @endslot
        @slot('title')
            Faire un inventaire
        @endslot
    @endcomponent
    <style>
        form label {
            font-size: 11px
        }

        .form-duplicate {
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            background: white;
            padding: 10px;
        }

        .error-text {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: none;
            text-shadow: none;
            /* Cacher le texte par défaut */
        }

        .btn-custom-size {
            padding: 4px 8px;
            /* Ajuste la taille du bouton */
            font-size: 10px;
            /* Ajuste la taille du texte */
        }

        .btn-custom-size i {
            font-size: 14px;
            /* Ajuste la taille de l'icône */
        }




        .select-no-interaction {
            pointer-events: none;
            /* Empêche les interactions */
            cursor: not-allowed;
            /* Change le curseur en "non autorisé" */
            background-color: #d6d6d6;
            /* Change la couleur de fond pour indiquer que c'est désactivé */
        }
    </style>
    <!-- si le user a pour role developpeur on affiche les champs sinon on les cache-->
    @php
        $dNone = Auth::user()->role == 'developpeur' ? '' : 'd-none';
    @endphp

    <!--End  si le user a pour role developpeur on affiche les champs sinon on les cache-->

    @role('developpeur')
        <!-- START Afficher les legende-->

        <div class="alert alert-info alert-border-left alert-dismissible fade show material-shadow" role="alert">
            <i class="ri-airplay-line me-3 align-middle"></i> <strong>Signification des titre</strong>
            S.D.I
            A.D.I
            S.V.D.I ou S.U.D.I
            <ul>
                <li><strong> S.D.I</strong> : Stock du dernier inventaire </li>
                <li> <strong>A.D.I</strong> : Achat effectué apres dernier inventaire </li>
                <li> <strong> S.V.D.I</strong> ou S.U.D.I : Stock vendu ou utilisé apres dernier inventaire</li>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <!-- END Afficher les legende-->
    @endrole



    <div class="row">
        <div class="col-lg-12">
            {{-- <div class="card">
                <div class="card-body"> --}}


            {{-- @if ($inventaire_existe == true) --}}
            <!-- Danger Alert -->
            {{-- <div class="alert alert-danger alert-dismissible bg-danger text-white alert-label-icon fade show material-shadow"
                    role="alert">
                    <i class="ri-error-warning-line label-icon"></i><strong>Attention !</strong> - Vous avez
                    déjà effectué un inventaire pour le moi passé. <br> Le prochain inventaire prévu pour la fin
                    du moi en cours
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div> --}}
            {{-- @else --}}
            <form id="myForm" method="POST" action="{{ route('inventaire.store') }}" autocomplete="off" novalidate
                enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-lg-12">
                        {{-- <div class="card">
                                    <div class="card-body"> --}}


                        <div class="row mb-3">

                            <div id="form-container">
                                <!-- ========== Start form duplicate ========== -->
                                <!-- Formulaire modèle (caché) -->
                                <!-- ========== End form duplicate ========== -->
                            </div>

                            <!-- Bouton "Plus" -->
                            <div class="mb-3">
                                <button type="button" id="add-more" class="btn btn-primary">Ajouter
                                    un produit <produit class=""></produit> <i
                                        class="ri ri-add-circle-line"></i></button>
                            </div>

                        </div>



                    </div>
                    <!-- end col -->

                </div>
                <!-- end row -->
                <!-- end card -->
                <div class="text-end mb-3">
                    <button type="submit" id="save" class="btn btn-success w-lg btn-save"
                        disabled>Enregistrer</button>
                </div>
            </form>
            {{-- @endif --}}


            <!-- start form duplicate-->
            <div id="product-form-template" style="display: none;">
                <div class="row mb-3 form-duplicate">
                    <!-- Bouton pour supprimer ce bloc -->
                    <div class="col-md-12 text-end">
                        <button type="button" class="btn btn-success validate btn-custom-size"> <i
                                class="ri ri-checkbox-circle-fill fs-5 validate"></i> </button>
                        <button type="button" class="btn btn-primary edit btn-custom-size"> <i
                                class="ri ri-edit-box-fill fs-5 edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger remove-form btn-custom-size"> <i
                                class="ri ri-delete-bin-fill fs-5 remove-form"></i> </button>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="product-title-input">Famille de produit
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <select class="form-control famille" required>
                            <option disabled selected value>Selectionner
                            </option>
                            @foreach ($categorie_famille as $categorie)
                                <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-7 mb-3">
                        <label class="form-label" for="product-title-input">Produits
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <select class="form-control productSelected selectView" name="produit_id[]" required>
                            <option disabled selected value>Selectionner un produit
                            </option>
                            {{-- @foreach ($data_produit as $produit)
                                <option value="{{ $produit->id }}">{{ $produit->nom }}
                                    {{ $produit->valeur_unite ?? '' }} {{ $produit->unite->libelle ?? '' }}
                                    {{ $produit->unite ? '(' . $produit->unite->abreviation . ')' : '' }}

                                </option>
                            @endforeach --}}
                        </select>
                    </div>

                    <div class="col-md-2 mb-3 {{ $dNone }}">
                        <label class="form-label" for="stocks-input">S.D.I
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control stockLastInventaire" readonly>
                    </div>
                    <div class="col-md-2 mb-3 {{ $dNone }}">
                        <label class="form-label" for="stocks-input">A.D.I
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="stock_initial[]" class="form-control stockRecent" readonly>
                    </div>

                    <div class="col-md-2 mb-3 {{ $dNone }}">
                        <label class="form-label" for="stocks-input">Stock de la periode
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control stockActuel" readonly>
                    </div>

                    <div class="col-md-2 mb-3 {{ $dNone }} ">
                        <label class="form-label" for="stocks-input">S.V.D.I ou S.U.D.I
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="stock_vendu[]" class="form-control stockVendu" readonly>
                    </div>


                    <div class="col-md-2 mb-3 {{ $dNone }}">
                        <label class="form-label" for="stocks-input">Stock théorique
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="stock_theorique[]" class="form-control stockTheorique" readonly>
                    </div>


                    <div class="col-md-2 mb-3">
                        <label class="form-label" for="stocks-input">Stock physique
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <input type="number" name="stock_physique[]" class="form-control stockPhysique" required>
                    </div>


                    <div class="variante-inputs row mb-3"></div>

                    <div class="col-md-2 mb-3 {{ $dNone }}">
                        <label class="form-label" for="stocks-input">Rapport Ecart
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="ecart[]" class="form-control ecart" readonly>
                    </div>

                    <div class="col-md-4 mb-3 {{ $dNone }}">
                        <label class="form-label" for="stocks-input">Etat de stock
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="etat[]" class="form-control etatStock" readonly>
                    </div>

                    <div class="col-md-6 mb-3 {{ $dNone }}">
                        <label class="form-label" for="stocks-input">Observation
                        </label>
                        <input type="text" name="observation[]" class="form-control observation">
                    </div>
                </div>

            </div>
            <!-- end form duplicate-->



            <!-- end row -->
        </div><!-- end col -->
    </div>

    <!--end row-->

@section('script')
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script>
    <script src="{{ URL::asset('build/js/pages/modal.init.js') }}"></script>
    {{-- <script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script> --}}
    <script src="{{ URL::asset('build/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>

    <script src="{{ URL::asset('build/libs/dropzone/dropzone-min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/ecommerce-product-create.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let formCount = 1;
            updateSelect2(); // Mettre à jour Select2 après suppression d'un formulaire
            // Fonction pour réinitialiser les champs dupliqués

            // Fonction pour vérifier et activer/désactiver le bouton "Enregistrer"
            function toggleEnregistrerButton() {
                let formDuplicates = document.querySelectorAll('.form-duplicate');

                // Si il n'y a qu'un seul élément, désactiver le bouton
                if (formDuplicates.length <= 1) {
                    document.getElementById('save').disabled = true;
                } else {
                    document.getElementById('save').disabled = false;
                }

            }


            function resetFields(clonedForm, formCount) {
                let inputs = clonedForm.querySelectorAll('input, select');
                inputs.forEach(input => {
                    if (input.tagName === 'INPUT') {
                        input.value = ''; // Réinitialise les valeurs des inputs
                        input.id = input.id.replace(/_\d+$/, '_' + formCount);
                    } else if (input.tagName === 'SELECT') {
                        input.selectedIndex = 0; // Réinitialise la sélection des dropdowns
                        input.id = input.id.replace(/_\d+$/, '_' + formCount);
                    }
                });


                // Ajouter le formCount à la classe js-example-basic-single
                clonedForm.querySelectorAll('.selectView').forEach(function(select) {
                    // Ajoute un identifiant unique à la classe
                    select.classList.add('js-example-basic-single-' + formCount);
                });
            }

            // Fonction pour ajouter un nouveau formulaire
            function addNewForm() {

                let formContainer = document.getElementById('form-container');

                // Clone le formulaire modèle
                let templateForm = document.getElementById('product-form-template');
                let clonedForm = templateForm.cloneNode(true);

                // Affiche le formulaire cloné et met à jour l'ID
                clonedForm.style.display = 'block';
                clonedForm.id = 'product-form-' + formCount;

                // Réinitialise les champs du formulaire cloné
                resetFields(clonedForm, formCount);

                // Ajoute le formulaire cloné dans le conteneur
                formContainer.appendChild(clonedForm);

                // Initialiser Select2 pour le nouveau champ avec une classe unique
                $(clonedForm).find('.js-example-basic-single-' + formCount).select2();


                // Activer le 'required' pour les champs du formulaire cloné
                setRequiredFields(clonedForm);

                formCount++;

                toggleEnregistrerButton(); // Vérifier si "Enregistrer" doit être actif
            }


            // Initial check when the page loads
            toggleEnregistrerButton();

            // Fonction pour activer le 'required' uniquement pour les champs visibles
            function setRequiredFields(formBlock) {
                formBlock.querySelectorAll('input, select').forEach(function(input) {
                    if (input.hasAttribute('data-required')) {
                        input.setAttribute('required', 'true');
                    }
                });
            }

            // Fonction pour désactiver les champs 'required' pour le formulaire modèle
            function disableRequiredInTemplate() {
                let templateForm = document.getElementById('product-form-template');
                templateForm.querySelectorAll('input[required], select[required]').forEach(function(input) {
                    input.removeAttribute('required');
                    // Ajouter un attribut temporaire pour les champs initialement required
                    input.setAttribute('data-required', 'true');
                });
            }

            // Désactiver les champs 'required' dans le formulaire modèle au chargement
            disableRequiredInTemplate();



            // Fonction pour réinitialiser Select2 sur les formulaires dupliqués
            function updateSelect2() {
                // Compter le nombre de duplications dans le conteneur
                let totalForms = document.querySelectorAll('#form-container .row').length;

                // Boucler à travers chaque formulaire dupliqué
                for (let i = 1; i <= totalForms; i++) {
                    // Initialiser Select2 pour chaque champ dupliqué en utilisant la classe unique
                    $('.js-example-basic-single-' + i).select2();
                }
            }

            // Écouteur pour le bouton "Ajouter un produit"
            document.getElementById('add-more').addEventListener('click', function() {
                addNewForm();

                toggleEnregistrerButton(); // Vérifier si "Enregistrer" doit être actif

            });



            //cacher le bouton modifier par defaut
            $('.edit').hide();

            // Utiliser la délégation d'événements pour le bouton "Supprimer"
            document.getElementById('form-container').addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-form')) {
                    e.target.closest('.form-duplicate').remove();
                    updateSelect2(); // Mettre à jour Select2 après suppression d'un formulaire

                    toggleEnregistrerButton(); // Vérifier si "Enregistrer" doit être actif

                }



                // Au clic du bouton "valider"
                if (e.target && e.target.classList.contains('validate')) {
                    let row = e.target.closest('.form-duplicate');
                    row.querySelectorAll('input, select').forEach(input => {
                        if (input.tagName === 'INPUT') {
                            // Pour les éléments input
                            // input.readOnly = true;
                            input.classList.add('select-no-interaction');
                        } else if (input.tagName === 'SELECT') {
                            // Ajouter une classe empêchant les interactions sur Select2
                            input.classList.add('select-no-interaction');

                            // Récupérer le conteneur Select2 et désactiver les interactions
                            let select2Container = $(input).next('.select2-container');
                            select2Container.find('.select2-selection').css({
                                'pointer-events': 'none',
                                'cursor': 'not-allowed',
                                'background-color': '#d6d6d6',


                            });
                        }
                    });

                    // Cacher le bouton "valider" et afficher le bouton "modifier"
                    $(row).find('.validate').hide();
                    $(row).find('.edit').show();
                }


                // Au clic du bouton "modifier"
                if (e.target && e.target.classList.contains('edit')) {
                    let row = e.target.closest('.form-duplicate');
                    row.querySelectorAll('input, select').forEach(input => {
                        if (input.tagName === 'INPUT') {
                            // Pour les éléments input
                            // input.readOnly = false;
                            input.classList.remove('select-no-interaction');
                        } else if (input.tagName === 'SELECT') {
                            // Supprimer la classe empêchant les interactions sur Select2
                            input.classList.remove('select-no-interaction');

                            // Récupérer le conteneur Select2 et réactiver les interactions
                            let select2Container = $(input).next('.select2-container');
                            select2Container.find('.select2-selection').css({
                                'pointer-events': 'auto',
                                'cursor': 'default',
                                'background-color': '#fff',

                            });
                        }
                    });

                    // Cacher le bouton "modifier" et afficher le bouton "valider"
                    $(row).find('.edit').hide();
                    $(row).find('.validate').show();
                }


            });


            // fonction qui permet selectionné les produits en fonction de la famille choisie
            function selectProduitsByFamille(form) {
                var produits = @json($data_produit); // Données du contrôleur
                var famille = form.find('.famille').val(); // famille choisie par l'utilisateur bar ou restaurant
                var familleOption = form.find('.famille option:selected')
                    .text(); // famille choisie par l'utilisateur bar ou restaurant

                //readonly de stock physique lorque famille bar
                if (familleOption == 'Bar') {
                    form.find('.stockPhysique').prop('readonly', true);
                } else {
                    // sinon on reactive stock physique on efface les variantes du produit bar
                    form.find('.stockPhysique').prop('readonly', false);
                    form.find('.variante-inputs').html('');
                }

              



                // recuperer les produits de la famille choisie (options)
                var options = produits.filter(function(item) {
                    return item.type_id == famille;
                })

                // Mettre à jour le select produit
                var selectProduit = form.find('.productSelected');
                selectProduit.empty();
                selectProduit.append($('<option>', {
                    value: '',
                    text: 'Sélectionnez un produit'
                }));

                for (var i = 0; i < options.length; i++) {
                    selectProduit.append($('<option>', {
                        value: options[i].id,
                        text: options[i].nom +
                            (options[i].valeur_unite ? ' ' + options[i].valeur_unite : '') +
                            (options[i].unite ? ' ' + options[i].unite.libelle : '') +
                            (options[i].unite ? ' (' + options[i].unite.abreviation + ')' : '')
                    }));
                }


            }

            // attacher l'événement de changement aux champs famille 
            $(document).on('change', '.famille', function() {
                var form = $(this).closest('.row'); // Obtenir le formulaire parent
                selectProduitsByFamille(form);
            })





            // Fonction pour verifier si un produit est sélectionné 2 fois
            function validateProductSelection() {
                let selectedProducts = [];

                $('.productSelected').each(function(index, element) {
                    let produitId = $(element).val();

                    // Ignorer les champs qui n'ont pas encore de produit sélectionné
                    if (produitId === null || produitId === '') {
                        return; // Continuer à la prochaine itération sans valider ce champ
                    }

                    // Vérifier si le produit a déjà été sélectionné
                    if (selectedProducts.includes(produitId)) {
                        Swal.fire({
                            title: 'Erreur',
                            text: 'Ce produit a déjà été sélectionné.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                        });

                        // Réinitialiser le champ select pour éviter la sélection en double
                        $(element).val(null).trigger('change.select2');
                    } else {
                        selectedProducts.push(produitId);
                    }
                });
            }





            //fonction pour remplir les champs stock initial et stock restante
            function getProductInfo(form) {

                // vider les champs par defaut
                form.find('.stockPhysique').val('');
                form.find('.ecart').val('');
                form.find('.etatStock').val('');
                form.find('.observation').val('');


                //recuperer les infos du produit selectionné
                var dataProduct = @json($data_produit); // Données du contrôleur
                // var productId = form.find('.productSelected').val();
                // var product = dataProduct.find(function(item) {
                //     return item.id == productId;
                // });




                var productId = form.find('.productSelected').val();
                var product = dataProduct.find(function(item) {
                    return item.id == productId;
                });



                // On sélectionne l'élément où injecter les inputs
                var variantesContainer = form.find('.variante-inputs');
                variantesContainer.html(''); // Réinitialise l'affichage

                if (product && product.variantes && product.variantes.length > 0) {
                    product.variantes.forEach(function(variante) {
                        var inputHTML = `
                            <div class="col-md-2">
                                <label>${variante.libelle}</label>
                                <input type="number" name="variantes[${variante.id}]" data-quantite="${variante.pivot.quantite}" value="0" min="0" class="form-control variante-input" />
                            </div>
                        `;
                        variantesContainer.append(inputHTML);
                    });
                }
                // console.log('product selected', product.variantes);



                // cacher le champs  stock physique si famille bar
                if (product.categorie.famille == 'bar') {
                    form.find('.stockPhysique').prop('readonly', true);
                } else {
                    form.find('.stockPhysique').prop('readonly', false);
                }




                //############stock theorique deplacé ##############



                let stockActuel = (product.stock_dernier_inventaire || 0) + (product.stock_initial || 0);
                form.find('.stockActuel').val(stockActuel % 1 === 0 ? stockActuel : stockActuel.toFixed(2) ?? 0);



                var stockVendu;
                if (product.categorie.famille == 'bar') {
                    var stockVendu = product.quantite_vendue;
                } else {
                    var stockVendu = product.quantite_utilisee;
                }
                form.find('.stockVendu').val((stockVendu % 1 === 0 ? stockVendu : stockVendu.toFixed(2)) ??
                    0); // stock vendu



                // #############stock theorique deplacé içi ##############

                var stockTheorique = stockActuel - stockVendu; // stock restant du produit
                // if (product.categorie.famille == 'bar') {
                //     var stockTheorique = product.stock - product.quantite_vendue;
                // } else {
                //     var stockTheorique = product.stock - product.quantite_utilisee;
                // }


                form.find('.stockTheorique').val(stockTheorique) || 0; // stock restante
                form.find('.stockRecent').val(product.stock_initial) ||
                    0; // stock nouveau ajouté apres last inventaire
                form.find('.stockLastInventaire').val(product.stock_dernier_inventaire) ||
                    0; // stock disponible pendant le dernier inventaire

            }




            // Attacher l'événement de changement aux champs select des produits
            $(document).on('change', '.productSelected', function() {
                validateProductSelection();
                var form = $(this).closest('.row'); // Obtenir le formulaire parent
                getProductInfo(form);

            });




            function calculerStockPhysique(form) {
                var totalStock = 0;

                form.find('.variante-input').each(function() {
                    var saisie = parseFloat($(this).val()) || 0;
                    var quantite = parseFloat($(this).data('quantite')) || 1;

                    if (quantite > 0) {
                        totalStock += saisie / quantite;
                    }
                });

                form.find('.stockPhysique').val(totalStock);


            }

            $(document).on('input change', '.variante-input', function() {
                var form = $(this).closest('.form-duplicate');
                calculerStockPhysique(form);
                calculEcart(form);
                gestionEtatStock(form);
            });



            // calculer l'ecart de stock
            function calculEcart(form) {
                var stock_physique = parseFloat(form.find('.stockPhysique').val()) || 0;
                var stock_theorique = parseFloat(form.find('.stockTheorique').val()) || 0;

                // var ecart = stock_physique - stock_theorique;
                // form.find('.ecart').val(ecart.toFixed(2));
                var ecart = stock_physique - stock_theorique;
                form.find('.ecart').val(ecart % 1 === 0 ? ecart : ecart.toFixed(2));
            }


            // Attacher l'événement de changement aux champs select des produits
            $(document).on('input change', '.stockPhysique ', function() {
                var form = $(this).closest('.row');
                calculEcart(form);
                gestionEtatStock(form);

            });


            //gestion des etats de stock
            function gestionEtatStock(form) {
                var ecart = form.find('.ecart').val();
                var stock_physique = form.find('.stockPhysique').val();
                var stock_theorique = form.find('.stockTheorique').val();
                var etat = '';
                if (ecart < 0) {
                    etat = 'Perte';
                } else if (ecart > 0) {
                    etat = 'Surplus';
                } else if (ecart == 0) {
                    etat = 'Conforme';
                }

                if (stock_theorique == 0 && stock_physique == 0) {
                    etat = 'Rupture';
                }

                form.find('.etatStock').val(etat);
            }




            $('#myForm').on('submit', function(event) {
                event.preventDefault(); // Empêcher le rechargement de la page

                let hasError = false;
                let submitButton = $(this).find('button[type="submit"]');

                // Ajouter le spinner et désactiver le bouton
                submitButton.prop('disabled', true).html(`
                    <span class="d-flex align-items-center">
                        <span class="spinner-border flex-shrink-0" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </span>
                        <span class="flex-grow-1 ms-2">Envoi en cours...</span>
                    </span>
                    `);


                // Parcourir tous les champs ayant l'attribut `required`
                $(this).find('[required]').each(function() {
                    if (!$(this).val()) {
                        hasError = true;
                        let fieldName = $(this).attr('name');
                        let label = $(this).closest('div').find('label').text() || fieldName;

                        $(this).closest('div').find('.error-text').text('Champs obligatoire')
                            .show();

                        Swal.fire({
                            title: 'Erreur',
                            text: `Le champ ${label} est obligatoire.`,
                            icon: 'error',
                            confirmButtonText: 'OK',
                        });

                        return false; // Stopper l'itération
                    } else {
                        $(this).closest('div').find('.error-text').hide();
                    }
                });

                if (hasError) {
                    // Réactiver le bouton en cas d'erreur
                    submitButton.prop('disabled', false).html('Enregistrer');
                    return false;
                }

                // Envoyer le formulaire via Ajax
                let formData = $(this).serialize();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response, textStatus, xhr) {
                        let statusCode = xhr.status;

                        if (statusCode === 200) {
                            Swal.fire({
                                title: 'Succès',
                                text: response.message,
                                icon: 'success',
                            });

                            window.location.replace("{{ route('inventaire.index') }}");
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        let errorMessage = xhr.responseJSON ? xhr.responseJSON.message :
                            'Une erreur est survenue.';

                        Swal.fire({
                            title: 'Erreur',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'OK',
                        });

                        // Réactiver le bouton et remettre le texte initial
                        submitButton.prop('disabled', false).html('Envoyer');
                    }
                });
            });






            // script for quantity stock increase and dicrease
            function increaseValue() {
                var input = document.getElementById("qteStockable");
                var value = parseInt(input.value, 10);
                value = isNaN(value) ? 0 : value;
                value++;
                input.value = value;
            }

            function decreaseValue() {
                var input = document.getElementById("qteStockable");
                var value = parseInt(input.value, 10);
                value = isNaN(value) ? 0 : value;
                value < 1 ? value = 1 : '';
                if (value > 1) {
                    value--;
                }
                input.value = value;


            }



            // on verifie si les champs sont differents de null avant de dupliquer

            // Calculer la quantité stockable
            function qteStockable(form) {
                var qte_acquise = form.find(".qteAcquise").val() || 0; // combien de format
                var qte_format = form.find(".qteFormat").val() || 0; // combien dans le format
                var qte_stockable = qte_acquise * qte_format;
                form.find(".qteStockable").val(qte_stockable);

                var dataProduct = {{ Js::from($data_produit) }}; // Données du contrôleur

                console.log(dataProduct);

            }

            // Calculer le total dépensé
            function prixTotalDepense(form) {
                var qte_acquise = form.find(".qteAcquise").val() || 0; // combien de format
                var pu_unitaire_format = form.find(".prixUnitaireFormat").val() ||
                    0; // prix unitaire d'un format
                var montant_facture = $("#montant_facture").val();
                var total_depense = qte_acquise * pu_unitaire_format;
                form.find(".prixTotalFormat").val(total_depense);

                //on verifie si la montant depensé depasse le montant de la facture
                if (total_depense > montant_facture) {
                    $('#save').prop('disabled', true);

                    $('#add-more').prop('disabled', true);
                    Swal.fire({
                        title: 'Erreur',
                        text: 'Le total dépensé dépasse le montant de la facture !',
                        icon: 'error',
                        confirmButtonText: 'OK',
                    });
                } else {
                    $('#save').prop('disabled', false);
                    $('#add-more').prop('disabled', false);
                }


            }

            // Calculer le prix d'achat de l'unité
            function prixAchatUnite(form) {
                var qte_acquise = form.find(".qteAcquise").val() || 0;
                var pu_unitaire_format = form.find(".prixUnitaireFormat").val() || 0;
                var qte_stocke = form.find(".qteStockable").val() || 0;
                var prix_achat_unite = qte_acquise * pu_unitaire_format / qte_stocke;
                form.find(".prixAchatUnite").val(prix_achat_unite);
            }

            // Calculer le prix d'achat total
            function calculatePrixAchat(form) {
                var qte_format = form.find(".qteFormat").val() || 0;
                var prix_achat_total = form.find(".prixAchatTotal").val() || 0;
                var prixAchatUnitaire = prix_achat_total / qte_format;
                var prixAchatTotal = qte_format * prixAchatUnitaire;
                form.find(".prixAchatUnitaire").val(prixAchatUnitaire);
            }

            // Ajouter des écouteurs sur les champs dupliqués
            $(document).on('input', '.qteAcquise, .qteFormat, .prixUnitaireFormat , #montant_facture',
                function() {
                    var form = $(this).closest('.row');
                    qteStockable(form);
                    prixTotalDepense(form);
                    prixAchatUnite(form);

                });

            // Ajout d'écouteurs pour les champs qui influencent le calcul du prix d'achat
            $(document).on('input', '.qteFormat, .prixAchatTotal', function() {
                var form = $(this).closest('.row');
                calculatePrixAchat(form);
            });

        });
    </script>
@endsection
@endsection
