@extends('backend.layouts.master')
@section('title')
    Stock
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        <link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">

        @slot('li_1')
            Gestion de stock
        @endslot
        @slot('title')
            Créer un nouveau stock
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




        .select-no-interaction {
            pointer-events: none;
            /* Empêche les interactions */
            cursor: not-allowed;
            /* Change le curseur en "non autorisé" */
            background-color: #d6d6d6;
            /* Change la couleur de fond pour indiquer que c'est désactivé */
        }
    </style>



    <div class="row">
        <div class="col-lg-12">
            {{-- <div class="card">
                <div class="card-body"> --}}
            <form id="myForm" method="POST" action="{{ route('achat.store') }}" autocomplete="off" novalidate
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        {{-- <div class="card">
                                    <div class="card-body"> --}}
                        <div class="row mb-3">

                            <div id="static-input">
                                <div class="row">
                                    <span class="text-danger text-center" id="msgExist"></span>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label" for="meta-title-input">Type de facture
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="type" id="type" class="form-control" required>
                                            <option disabled selected value="">Choisir</option>
                                            <option value="facture">Facture</option>
                                            <option value="bon de sortie">Bon de sortie</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label class="form-label" for="meta-title-input">N° facture ou Bon
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="numero_facture" class="form-control" id="facture"
                                            required>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label" for="product-title-input">Fournisseur
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select id="fournisseur" class="form-control" name="fournisseur_id" required>
                                            <option disabled selected value="">Choisir</option>
                                            @foreach ($data_fournisseur as $fournisseur)
                                                <option value="{{ $fournisseur->id }}">
                                                    {{ $fournisseur->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label class="form-label" for="meta-title-input">Montant
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" name="montant" id="montant_facture" class="form-control"
                                            required>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label class="form-label" for="meta-title-input">Date <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="datetime-local" id="currentDate" value="<?php echo date('Y-m-d H:i:s'); ?>"
                                            name="date_achat" class="form-control" required>
                                    </div>



                                </div>
                            </div>


                            <div id="form-container">
                                <!-- ========== Start form duplicate ========== -->
                                <!-- Formulaire modèle (caché) -->


                                <!-- ========== End form duplicate ========== -->
                            </div>

                            <!-- Bouton "Plus" -->
                            <div class="mb-3">
                                <button type="button" id="add-more" class="btn btn-primary">Ajouter
                                    plus <i class="ri ri-add-circle-line"></i></button>
                            </div>

                            <div class="fw-bold">Total Dépensé: <span id="total_depense">0</span></div>





                        </div>
                        {{-- </div>
                                </div> --}}
                        <!-- end card -->
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


            <!-- start form duplicate-->
            <div id="product-form-template" style="display: none;">
                <div class="row mb-3 form-duplicate">
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="product-title-input">Produits
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <select class="form-control productSelected selectView" name="produit_id[]" required>
                            <option disabled selected value>Selectionner un produit
                            </option>
                            @foreach ($data_produit as $produit)
                                <option value="{{ $produit->id }}">{{ $produit->libelle != null ? $produit->libelle : $produit->nom   }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="col-md-4">
                        <label class="form-label" for="product-title-input">Magasin
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <select class="form-control selectView" name="magasin_id" required>
                            <option disabled selected value="">Choisir</option>
                            @foreach ($data_magasin as $magasin)
                                <option value="{{ $magasin->id }}">
                                    {{ $magasin->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}

                    <div class="col-md-2 mb-3">
                        <label class="form-label" for="stocks-input">Qté acquise
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <input type="number" name="quantite_format[]" class="form-control qteAcquise" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="product-title-input">Format
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <select class="form-control selectView format" id="format_id" name="format_id[]" required>
                            <option disabled selected value="">Choisir</option>
                            @foreach ($data_format as $format)
                                <option value="{{ $format->id }}">
                                    {{ $format->libelle }} ({{ $format->abreviation }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label" for="stocks-input">Qté dans le format
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <input type="number" name="quantite_in_format[]" class="form-control qteFormat" required>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label" for="stocks-input">Qté stocké
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <input type="number" name="quantite_stocke[]" class="form-control qteStockable" readonly>
                    </div>


                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="stocks-input">Unité de sortie ou vente
                        </label>
                        <input type="text" name="unite_sortie[]" class="form-control uniteSortie" readonly>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label" for="stocks-input">Prix
                            unitaire du format
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <input type="number" name="prix_unitaire_format[]" class="form-control prixUnitaireFormat"
                            required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="stocks-input">Total
                            dépensé
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <input type="number" name="prix_total_format[]" class="form-control prixTotalFormat" readonly>
                    </div>


                    {{-- <div class="col-md-3 mb-3">
                        <label class="form-label" for="meta-title-input">Unité de
                            sortie
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <select class="form-control selectView uniteSortie" name="unite_sortie[]" required>
                            <option value disabled selected>Choisir</option>
                            @foreach ($data_unite as $unite)
                                <option value="{{ $unite->id }}">
                                    {{ $unite->libelle }} ({{ $unite->abreviation }})
                                </option>
                            @endforeach
                        </select>
                    </div> --}}





                    <div class="col-md-3 mb-3 prixAchatUniteDiv">
                        <label class="form-label" for="stocks-input">Coût achat de
                            l'unité
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <input type="number" name="prix_achat_unitaire[]" class="form-control prixAchatUnite" readonly>
                    </div>



                    <div class="col-md-3 mb-3 prixVenteDiv">
                        <label class="form-label" for="stocks-input">Prix de
                            vente
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <input type="number" name="prix_vente_unitaire[]" class="form-control prixVente" readonly>
                    </div>

                    {{-- <div class="col-md-6">
                        <label class="form-check-label" for="customAff">Activer ou desactiver le produit <br> <span
                                class="text-danger">(Activé par defaut)</span></label>

                        <div class="form-check form-switch form-switch-lg col-md-2" dir="ltr">
                            <input type="hidden" name="statut[]" value="off">
                            <input type="checkbox" name="statut[]" class="form-check-input" id="customAff"
                                value="on" checked>
                        </div>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div> --}}

                    <!-- Bouton pour supprimer ce bloc -->
                    <div class="text-end">
                        <button type="button" class="btn btn-success validate">valider</button>
                        <button type="button" class="btn btn-success edit">modifier</button>

                        <button type="button" class="btn btn-danger remove-form">Supprimer</button>
                    </div>
                </div>
            </div>
            <!-- end form duplicate-->



            <!-- end row -->
        </div><!-- end col -->


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
            // Vérifier si le numéro et le fournisseur existent déjà
            function checkExistFacture() {
                var numero = $('#facture').val();
                var fournisseur = $('#fournisseur').val();

                if (numero && fournisseur) { // Assurez-vous que les deux champs ne sont pas vides
                    var url = "{{ route('achat.check-facture') }}"; // Définissez la bonne route pour la vérification

                    $.ajax({
                        type: "POST", // Utilisez POST pour des opérations sécurisées
                        url: url,
                        data: {
                            numero: numero,
                            fournisseur: fournisseur,
                            _token: '{{ csrf_token() }}' // Ajoutez le token CSRF nécessaire
                        },
                        success: function(response) {
                            if (response.exist == true) {
                                // Afficher un message si la facture existe déjà
                                //swal message error
                                Swal.fire({
                                    title: 'Attention',
                                    text: response.message,
                                    icon: 'warning',
                                    customClass: {
                                        confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                                        cancelButton: 'btn btn-danger w-xs mt-2',
                                    },
                                    buttonsStyling: false,
                                    showCloseButton: true
                                })

                                // vider les champs
                                $('#facture').val("");
                                $('#fournisseur').val("");
                            }

                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        }
                    });
                }
            }

            // Déclenche la vérification lorsque le fournisseur ou le numéro de facture change
            $(document).on('change input', '#fournisseur, #facture', function(e) {
                checkExistFacture();
            });


            //verifier si le montant de la facture VS montant depensé total




            document.addEventListener('DOMContentLoaded', function() {
                let formCount = 1;
                updateSelect2(); // Mettre à jour Select2 après suppression d'un formulaire
                // Fonction pour réinitialiser les champs dupliqués




                // Fonction pour calculer le total dépensé
                function calculerTotalDepense() {
                    let totalDepense = 0;
                    document.querySelectorAll('.prixTotalFormat').forEach(input => {
                        let valeur = parseFloat(input.value) || 0;
                        totalDepense += valeur;
                    });
                    // Mettre à jour le champ affichant le total dépensé
                    document.getElementById('total_depense').textContent = totalDepense;

                    // Comparer avec le montant de la facture
                    let montantFacture = parseFloat(document.getElementById('montant_facture').value) || 0;
                    if (totalDepense >= montantFacture) {
                        // Désactiver les boutons si le total dépasse la facture
                        document.getElementById('add-more').disabled = true;
                        // document.getElementById('save').disabled = true;


                        //swalfire
                        // Swal.fire({
                        //     title: 'Erreur',
                        //     text: 'Le total dépensé dépasse le montant de la facture !',
                        //     icon: 'error',
                        //     confirmButtonText: 'OK',
                        // });

                    } else {
                        // Réactiver les boutons si le total est inférieur ou égal à la facture
                        document.getElementById('add-more').disabled = false;
                        // document.getElementById('save').disabled = false;
                    }
                }



                // Fonction pour vérifier et activer/désactiver le bouton "Enregistrer"
                function toggleEnregistrerButton() {
                    let formDuplicates = document.querySelectorAll('.form-duplicate');

                    // Si il n'y a qu'un seul élément, désactiver le bouton
                    if (formDuplicates.length <= 1) {
                        document.getElementById('save').disabled = true;
                    } else {
                        document.getElementById('save').disabled = false;
                    }
                    calculerTotalDepense()
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



                    // // Réinitialise Select2 sur le nouveau champ
                    // $(clonedForm).find('.js-example-basic-single').select2();

                    // Initialiser Select2 pour le nouveau champ avec une classe unique
                    $(clonedForm).find('.js-example-basic-single-' + formCount).select2();


                    // Activer le 'required' pour les champs du formulaire cloné
                    setRequiredFields(clonedForm);

                    formCount++;

                    // Recalculer le total quand on ajoute un nouveau formulaire
                    calculerTotalDepense();

                    toggleEnregistrerButton(); // Vérifier si "Enregistrer" doit être actif
                }


                // Écouter les changements dans le champ du montant de la facture pour comparer
                $(document).on('input', '#montant_facture ', function() {
                    calculerTotalDepense();
                })
                // document.getElementById('montant_facture').addEventListener('input', calculerTotalDepense);

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
                    // on verifie si les champs sont differents de null avant de dupliquer
                    var type = $('#type').val();
                    var facture = $('#facture').val();
                    var fournisseur = $('#fournisseur').val();
                    var montant = $('#montant_facture').val();
                    var date = $('#currentDate').val();

                    //si les champs sont !=null on de sactive le bouton add plus
                    if (type != null && facture != null && fournisseur != null && montant != null && date !=
                        null) {
                        addNewForm();
                    }

                    calculerTotalDepense(); // Recalculer le total quand on ajoute un nouveau formulaire
                    toggleEnregistrerButton(); // Vérifier si "Enregistrer" doit être actif

                });



                //cacher le bouton modifier par defaut
                $('.edit').hide();

                // Utiliser la délégation d'événements pour le bouton "Supprimer"
                document.getElementById('form-container').addEventListener('click', function(e) {
                    if (e.target && e.target.classList.contains('remove-form')) {
                        e.target.closest('.form-duplicate').remove();
                        updateSelect2(); // Mettre à jour Select2 après suppression d'un formulaire
                        calculerTotalDepense(); // Recalculer le total quand on ajoute un nouveau formulaire
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



                // // Fonction pour verifier si un produit est selectionner 2 fois
                // function validateProductSelection() {
                //     let selectedProducts = [];

                //     $('.productSelected').each(function(index, element) {
                //         let produitId = $(element).val();

                //         // Vérifier si le produit a déjà été sélectionné
                //         if (selectedProducts.includes(produitId)) {
                //             Swal.fire({
                //                 title: 'Erreur',
                //                 text: 'Ce produit a déjà été sélectionné.',
                //                 icon: 'error',
                //                 confirmButtonText: 'OK',
                //             });


                //             // Réinitialiser le champ select pour éviter la sélection en double
                //             $(element).val(null).trigger('change.select2');

                //         } else {
                //             selectedProducts.push(produitId);

                //         }
                //     });
                // }

                // // Attacher l'événement de changement aux champs select des produits
                // $(document).on('change', '.productSelected', function() {
                //     validateProductSelection();
                // });


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

                // Attacher l'événement de changement aux champs select des produits
                $(document).on('change', '.productSelected', function() {
                    validateProductSelection();
                });



                //enregister le formulaire
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
                        // Vérifier si le champ est vide
                        if (!$(this).val()) {
                            hasError = true;
                            let fieldName = $(this).attr('name'); // Récupérer le nom du champ
                            let label = $(this).closest('div').find('label').text() ||
                                fieldName; // Trouver le label ou utiliser le nom du champ

                            // Ajouter le texte d'erreur sous le champ
                            // $(this).closest('div').find('.error-text').text(
                            //     `Le champ ${label} est obligatoire.`).show();
                            $(this).closest('div').find('.error-text').text('Champs obligatoire')
                                .show();


                            // Afficher une alerte avec SweetAlert
                            Swal.fire({
                                title: 'Erreur',
                                text: `Le champ ${label} est obligatoire.`,
                                icon: 'error',
                                confirmButtonText: 'OK',
                            });

                            return false; // Stopper l'itération et éviter l'envoi
                        } else {
                            // Cacher le message d'erreur si le champ est rempli
                            $(this).closest('div').find('.error-text').hide();
                        }
                    });


                    // Si une erreur a été trouvée, arrêter l'envoi
                    if (hasError) {
                        submitButton.prop('disabled', false).html('Enregistrer');
                        return false;
                    }

                    // Si tout est correct, soumettre le formulaire avec Ajax
                    let formData = $(this).serialize(); // Récupérer les données du formulaire
                    $.ajax({
                        url: $(this).attr('action'), // URL de la soumission du formulaire
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response, textStatus, xhr) {
                            let statusCode = xhr.status;

                            // Si la soumission est réussie
                            if (statusCode === 200) {
                                Swal.fire({
                                    title: 'Succès',
                                    text: response.message,
                                    icon: 'success',
                                });

                                // Rediriger vers la liste des factures
                                var url =
                                    "{{ route('achat.index') }}"; // Rediriger vers la route liste facture
                                window.location.replace(url);
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            let statusCode = xhr.status;

                            // Si une erreur serveur (500) est rencontrée
                            if (statusCode === 500) {
                                Swal.fire({
                                    title: 'Erreur',
                                    text: xhr.responseJSON ? xhr.responseJSON.message :
                                        'Une erreur est survenue.',
                                    icon: 'error',
                                    confirmButtonText: 'OK',
                                });

                                submitButton.prop('disabled', false).html('Enregistrer');


                            } else {
                                Swal.fire({
                                    title: 'Erreur',
                                    text: xhr.responseJSON ? xhr.responseJSON.message :
                                        'Une erreur est survenue.',
                                    icon: 'error',
                                    confirmButtonText: 'OK',
                                });
                                submitButton.prop('disabled', false).html('Enregistrer');
                            }
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

                $('#add-more').click(function(e) {

                    var type = $('#type').val();
                    var facture = $('#facture').val();
                    var fournisseur = $('#fournisseur').val();
                    var montant = $('#montant_facture').val();
                    var date = $('#currentDate').val();


                    if (type == null || facture == null || fournisseur == null || montant == "" || date ==
                        null) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Erreur',
                            text: 'Veuillez remplir tous les champs avant de continuer',
                            icon: 'error',
                            confirmButtonText: 'OK',
                        });
                    }

                });


                // Calculer la quantité stockable
                function qteStockable(form) {
                    var qte_acquise = form.find(".qteAcquise").val() || 0; // combien de format
                    var qte_format = form.find(".qteFormat").val() || 0; // combien dans le format
                    var qte_stockable = qte_acquise * qte_format;
                    form.find(".qteStockable").val(qte_stockable);

                    var dataProduct = {{ Js::from($data_produit) }}; // Données du contrôleur


                }

                // Calculer le total dépensé
                function prixTotalDepense(form) {
                    var qte_acquise = form.find(".qteAcquise").val() || 0; // combien de format
                    var pu_unitaire_format = form.find(".prixUnitaireFormat").val() || 0; // prix unitaire d'un format
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

                    calculerTotalDepense()
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
                $(document).on('input', '.qteAcquise, .qteFormat, .prixUnitaireFormat , #montant_facture', function() {
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


                // Fonction pour cacher les champs en fonction du type de produit selectionné
                function productAddField(form) {
                    var dataProduct = {{ Js::from($data_produit) }}; // Données du contrôleur
                    var dataCategory = {{ Js::from($data_categorie) }}; // Données du contrôleur

                    var productSelected = form.find('.productSelected').val(); // Récupère la valeur du select actuel
                    var filteredCatRestaurant = dataCategory.filter(function(item) {
                        return item.type == 'restaurant';
                    });

                    var filteredProduct = dataProduct.filter(function(item) {
                        return item.id == productSelected;
                    });

                    if (filteredProduct[0].type_id == filteredCatRestaurant[0].id) {

                        form.find('.prixAchatUniteDiv').hide()
                        form.find('.prixVenteDiv').hide()
                        form.find('.prixAchatUnite').val(0)
                        form.find('.prixVente').val(0)

                        form.find('.prixVente').prop('required', false)
                        form.find('.prixAchatUnite').prop('required', false)


                    } else {
                        form.find('.prixAchatUniteDiv').show()
                        form.find('.prixVenteDiv').show()
                        form.find('.prixVente').prop('required', true)
                        form.find('.prixAchatUnite').prop('required', true)



                    }

                }


                //recuperer les informations du produit pour les attribuers au formulaire
                function getProductInfo(form) {
                    var dataProduct = @json($data_produit);
                    var productSelected = form.find('.productSelected').val(); // Récupère la valeur du select actuel
                    var filteredProduct = dataProduct.filter(function(item) {
                        return item.id == productSelected;
                    })

                    form.find('.prixVente').val(filteredProduct[0].prix)
                    form.find('.uniteSortie').val(filteredProduct[0].unite_sortie.libelle)
                }

                $(document).on('change', '.productSelected', function() {
                    var form = $(this).closest('.row');
                    productAddField(form);
                    getProductInfo(
                        form);
                });





                //enregister le formulaire
                // $('#myForm').on('submit', function(event) {
                //     event.preventDefault(); // Empêcher le rechargement de la page

                //     calculerTotalDepense()

                //     let formData = $(this).serialize(); // Récupérer les données du formulaire

                //     $.ajax({
                //         url: $(this).attr('action'), // URL de la soumission du formulaire
                //         type: 'POST',
                //         data: formData,
                //         dataType: 'json',
                //         success: function(response) {
                //             // Si la soumission est réussie
                //             if (response.success) {
                //                 $('#successMessage').text(response.message)
                //                     .show(); // Afficher le message de succès
                //                 $('#nameError').hide(); // Cacher le message d'erreur
                //                 $('#myForm')[0].reset(); // Réinitialiser le formulaire
                //             }
                //         },
                //         error: function(xhr) {
                //             // Gérer l'erreur
                //             if (xhr.responseJSON && xhr.responseJSON.message) {
                //                 $('#nameError').text(xhr.responseJSON.message)
                //                     .show(); // Afficher le message d'erreur
                //             }
                //         }
                //     });
                // });



                // $(document).on('input', '.qteFormat, .prixAchatUnitaire', function() {
                //     var form = $(this).closest('.row');
                //     calculatePrixAchatTotal(form);
                // });




                //get product select and show detail of product selected
                // $('.productSelected').change(function(e) {
                //     var dataProduct = {{ Js::from($data_produit) }} // from controller

                //     e.preventDefault();
                //     var productSelected = $('.productSelected option:selected').val();

                //     var filteredProduct = dataProduct.filter(function(item) {
                //         return item.id == productSelected;
                //     });
                //     console.log(filteredProduct[0].media);


                //     //update stock , sku ,  category of product selected
                //     $('#stock').html(filteredProduct[0].stock)
                //     $('#stockAlerte').html(filteredProduct[0].stock_alerte)

                //     $('#sku').html(filteredProduct[0].code)
                //     $('#categorie').html(filteredProduct[0].categorie.name)

                //     var img = filteredProduct[0].media[0].original_url
                //     $('#product-img').html(`<img src="${img}"  class="avatar-md h-auto" />`)

                // });

            });
        </script>
    @endsection
@endsection
