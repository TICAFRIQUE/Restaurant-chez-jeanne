@extends('backend.layouts.master')

@section('content')


    @component('backend.components.breadcrumb')
        <link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">

        @slot('li_1')
            Stock
        @endslot
        @slot('title')
            Créer une sortie
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
            <form id="myForm" method="POST" action="{{ route('sortie.store') }}" autocomplete="off" novalidate
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


            <!-- start form duplicate-->
            <div id="product-form-template" style="display: none;">
                <div class="row mb-3 form-duplicate">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="product-title-input">Produits
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <select class="form-control productSelected selectView" name="produit_id[]" required>
                            <option disabled selected value>Selectionner un produit
                            </option>
                            @foreach ($data_produit as $produit)
                                <option value="{{ $produit->id }}">{{ $produit->nom }} {{ $produit->quantite_unite }}
                                    {{ $produit->unite->libelle ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-2 mb-3">
                        <label class="form-label" for="stocks-input">Qté utilisée
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <input type="number" name="quantite_utilise[]" class="form-control qteUtilise" required>
                    </div>


                    <div class="col-md-2 mb-3">
                        <label class="form-label" for="stocks-input">Unité de sortie
                            <span class="text-danger">*</span>
                        </label>
                        <span class="error-text">Champ obligatoire</span> <!-- Conteneur pour l'erreur -->
                        <input class="form-control uniteLibelle" type="text" name="unite_libelle[]" readonly>
                        <input class="form-control uniteId" type="text" name="unite_id[]" hidden>

                    </div>



                    <!-- Bouton pour supprimer ce bloc -->
                    <div class="col-md-2 mb-3 pt-4">
                        <button type="button" class="btn btn-success validate"> <i
                                class="ri ri-checkbox-circle-fill fs-4"></i> </button>
                        <button type="button" class="btn btn-primary edit"> <i class="ri ri-edit-box-fill fs-4"></i>
                        </button>
                        <button type="button" class="btn btn-danger remove-form"> <i
                                class="ri ri-delete-bin-fill fs-4"></i> </button>
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



                //Fonction pour  verifier la quantité entrée , elle ,e dois pas depasser la quantité en stock
                function verifyQty(form) {
                    var dataProduct = @json($data_produit); // Données du contrôleur

                    // Récupérer la quantité utilisée et l'ID du produit sélectionné
                    var qteStock = form.find('.qteUtilise').val(); // Assurez-vous que la classe est correcte
                    var productSelected = form.find('.productSelected')
                        .val(); // Assurez-vous que la classe est correcte

                    // Trouver le produit dans dataProduct basé sur l'ID sélectionné
                    var product = dataProduct.find(function(item) {
                        return item.id == productSelected;
                    });

                    if (qteStock > product.stock) {
                        //swalfire
                        Swal.fire({
                            title: 'Erreur',
                            text: 'La quantité entrée dépasse la quantité en stock',
                            icon: 'error',
                        });

                        //mettre le button save en disabled
                        $('#save').prop('disabled', true)

                    } else {
                        //mettre le button save en enable
                        $('#save').prop('disabled', false)
                    }
                }

                // Utiliser la délégation d'événements pour les champs dynamiques
                $(document).on('input change', '.qteUtilise , .productSelected', function() {
                    var form = $(this).closest('.row'); // Cibler le formulaire ou la ligne parent
                    verifyQty(form); // Appeler la fonction avec le formulaire
                });


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


                // Fonction pour récupérer les détails du produit sélectionné
                function getProductDetails(form) {
                    let produitId = form.find('.productSelected').val();

                    if (produitId) {
                        // Récupérer les données du produit
                        let produit = @json($data_produit).find(p => p.id == produitId);
                        if (produit && produit.achats && produit.achats.length > 0) {
                            // Récupérer l'unité de sortie du dernier achat
                            let uniteSortie = produit.achats[0].unite;
                            form.find('.uniteLibelle').val(uniteSortie.libelle)
                            form.find('.uniteId').val(uniteSortie.id)

                        }
                    }
                }

                // Appeler la fonction au chargement de la page
                $(document).on('change', '.productSelected', function() {
                    var form = $(this).closest('.row'); // Cibler le formulaire ou la ligne parent
                    getProductDetails(form);
                });




                $('#myForm').on('submit', function(event) {
                    event.preventDefault(); // Empêcher le rechargement de la page

                    let hasError = false;

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

                                // Rediriger vers la liste des sortie
                                var url =
                                    "{{ route('sortie.create') }}"; // Rediriger vers la route liste sortie
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
                            } else {
                                Swal.fire({
                                    title: 'Erreur',
                                    text: xhr.responseJSON ? xhr.responseJSON.message :
                                        'Une erreur est survenue.',
                                    icon: 'error',
                                    confirmButtonText: 'OK',
                                });
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

            });
        </script>
    @endsection
@endsection