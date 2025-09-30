@extends('backend.layouts.master')

@section('content')
    @component('backend.components.breadcrumb')
        <link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">

        @slot('li_1')
            Produit
        @endslot
        @slot('title')
            Créer un nouveau produit
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form id="formSend" autocomplete="off" class="needs-validation" novalidate enctype="multipart/form-data"
                        novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-3 row">

                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="product-title-input">Famille<span
                                                        class="text-danger">*</span>
                                                </label>
                                                <select id="categorie" class="form-control " name="famille" required>
                                                    <option value="" disabled selected>Selectionner</option>
                                                    @foreach ($data_categorie as $categorie)
                                                        <option value=" {{ $categorie->id }} ">{{ $categorie->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3 col-md-5">
                                                <label class="form-label" for="product-title-input">
                                                    Categorie <span class="text-danger">*</span>
                                                </label>
                                                <select id="categorie-filter"
                                                    class="form-control js-example-basic-single categorie-filter"
                                                    name="categorie_id" required>
                                                    <option value="" disabled selected>Selectionner</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label" for="meta-title-input">Libellé <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="nom" class="form-control" id="nomProduit"
                                                    required>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label class="form-label" for="meta-title-input">valeur de l'unité
                                                    <i class="ri ri-information-line fs-6  text-warning p-1 rounded fw-bold"
                                                        data-bs-toggle="popover" data-bs-trigger="hover focus"
                                                        title="Information"
                                                        data-bs-content="exemple 1.5 L , utiliser un . ou , exemple 1,5"></i>
                                                </label>

                                                <input type="number" name="valeur_unite"
                                                    class="form-control customNumberInput" id="quantiteUnite"
                                                    step="0.01">
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label class="form-label" for="meta-title-input">Unité du produit
                                                </label>
                                                <select id="uniteProduit" class="form-control js-example-basic-single"
                                                    name="unite_id">
                                                    <option value="" selected>Choisir</option>
                                                    @foreach ($data_unite as $unite)
                                                        <option value="{{ $unite->id }}">{{ $unite->libelle }}
                                                            ({{ $unite->abreviation }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                           
                                            <div class="col-md-3 mb-3 divUniteSortie ">
                                                <label class="form-label" for="meta-title-input">Unité en sortie ou vente
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-control js-example-basic-single uniteSortie"
                                                    name="unite_sortie_id" required>
                                                    <option value="" selected>Choisir</option>
                                                    @foreach ($data_unite as $unite)
                                                        <option value="{{ $unite->id }}">{{ $unite->libelle }}
                                                            ({{ $unite->abreviation }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="col-md-3 mb-3 d-none">
                                                <label class="form-label" for="meta-title-input">Prix de vente
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" name="prix" class="form-control "
                                                    id="prixVenteHide">
                                            </div>


                                            <div class="col-md-3 mb-3">
                                                <label class="form-label" for="meta-title-input">Stock alerte <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="number" name="stock_alerte" class="form-control"
                                                    id="stockAlerte" required>
                                            </div>
                                        </div>


                                        <!-- ========== Start Variante ========== -->
                                        <div class="container my-4 divVariante">

                                            <div class="col-12 d-flex justify-content-center">
                                                <p>-------------------------------</p> <span class="fw-bold">Gestion des
                                                    prix et
                                                    variantes</span>
                                                <p> -----------------------------</p>
                                            </div>

                                            <div id="variantes-container">
                                                <div class="row variante-row mb-4">
                                                    <div class="col-2">
                                                        <label for="prix">Quantité :</label>
                                                        <input type="number" class="form-control"
                                                            name="variantes[0][quantite]" value="1" readonly>
                                                    </div>
                                                    <div class="col-4">
                                                        <label for="variante">Unite de vente(principale) :</label>
                                                        <select name="variantes[0][libelle]" class="form-control"
                                                            @readonly(true)>

                                                            @php
                                                                $variante = App\Models\Variante::all();
                                                                // recuperer la varianrte bouteille
                                                                $variante = $variante
                                                                    ->where('slug', 'bouteille')
                                                                    ->first();
                                                            @endphp
                                                            <option value="{{ $variante->id }}">
                                                                {{ $variante->libelle }}</option>

                                                        </select>
                                                    </div>
                                                    <div class="col-4">
                                                        <label for="prix">Prix unitaire de vente :</label>
                                                        <input type="number" step="0.01"
                                                            class="form-control prixVente" name="variantes[0][prix]"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary mb-3" id="add-variante">Ajouter
                                                une Variante</button>

                                        </div>

                                        <!-- ========== End Variante ========== -->



                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->

                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div>
                                                    <label>Description</label>
                                                    <textarea name="description" id="ckeditor-classic"></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                <div class="mb-4">
                                                    <h6 class="fs-14 mb-1">Image principale <span
                                                            class="text-danger">*</span>
                                                    </h6>
                                                    <div class="text-center">
                                                        <div class="position-relative d-inline-block">
                                                            <div
                                                                class="position-absolute top-100 start-100 translate-middle">
                                                                <label for="product-image-input" class="mb-0"
                                                                    data-bs-toggle="tooltip" data-bs-placement="right"
                                                                    title="Select Image">
                                                                    <div class="avatar-xs">
                                                                        <div
                                                                            class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                                            <i class="ri-image-fill"></i>
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                                <input class="form-control d-none"
                                                                    id="product-image-input" type="file"
                                                                    name="imagePrincipale" accept="image/*" required>
                                                                <div class="invalid-feedback">Ajouter une image</div>
                                                            </div>
                                                            <div class="avatar-lg">
                                                                <div class="avatar-title bg-light rounded">
                                                                    <img src="" id="product-img"
                                                                        class="avatar-md h-auto" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-12 mt-3">
                                                    <label for="imageInput" class="form-label col-12">
                                                        <div class="col-md-12 border border-dark rounded border-dashed text-center px-5 mt-4"
                                                            style=" cursor: pointer;">
                                                            <i class="ri ri-image-add-fill fs-1 "></i>
                                                            <h5>Ajouter des images</h5>
                                                        </div>
                                                    </label>
                                                    <input type="file" id="imageInput" accept="image/*"
                                                        class="form-control d-none" multiple>

                                                    <div class="row" id="imageTableBody"></div>

                                                    <div class="valid-feedback">
                                                        Success!
                                                    </div>
                                                </div>
                                            </div>



                                        </div>

                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                        </div>
                        <!-- end row -->
                        <!-- end card -->
                        <div class="text-end mb-3">
                            <button type="submit" class="btn btn-success w-lg">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div><!-- end row -->
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
            //cacher la div variante par defaut
            $('.divVariante').hide();
            //gestion des variantes
            let varianteIndex = 1;

            document.getElementById('add-variante').addEventListener('click', function() {
                const container = document.getElementById('variantes-container');
                const newRow = document.createElement('div');
                newRow.classList.add('row', 'variante-row', 'mb-4');
                newRow.innerHTML = `
                  <div class="col-2">
                    <label for="prix">Quantité :
                        
                            <small class="text-danger">
                             Nombre de quantité de variante pour atteindre une bouteille :
                             </small>                                                    
                        </label>
                        <input type="number" name="variantes[${varianteIndex}][quantite]" class="form-control"  required >
                        </div>
        <div class="col-4">
            <label for="variante">Variante de vente :</label>
            <select class="form-control" name="variantes[${varianteIndex}][libelle]" required>
                <option value="" selected>Choisir</option>
                @foreach ($data_variante as $variante)
                    <option value="{{ $variante->id }}">{{ $variante->libelle }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-4">
            <label for="prix">Prix unitaire par quantite :</label>
            <input type="number" step="0.01" class="form-control" name="variantes[${varianteIndex}][prix]" required>
        </div>
        <div class="col-2 mt-2">
            <button type="button" class="btn btn-danger remove-variante mt-3"> <i class="mdi mdi-delete remove-variante"></i></button>
        </div>
    `;
                container.appendChild(newRow);
                varianteIndex++;
            });

            document.getElementById('variantes-container').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-variante')) {
                    e.target.closest('.variante-row').remove();
                }
            });



            // Par defaut cacher la div de unite de sortie et required a false
            $('.divUniteSortie').hide();
            $('.uniteSortie').prop('required', false);


            // Afficher les champs en fonction de la categorie selectionné
            let categoryFamille;
            // var categorieData = @json($categorieAll) // from product controller
            var categorieFilter =
                @json($data_categorie) // from product controller--- categorie parent et leur sous categorie


            // remplissage des categorie en fonction de la categorie famille selectionnee

            // Données des catégories
            var categorieData = @json($categorieAll); // Toutes les catégories avec leurs enfants
            var categorieSelect = document.getElementById('categorie'); // Select Famille
            var categorieFilterSelect = document.getElementById('categorie-filter'); // Select Catégorie

            // Fonction pour récupérer toutes les sous-catégories récursivement
            function getRecursiveCategories(categories, parentId, level = 0) {
                const result = [];

                categories.forEach(category => {
                    if (category.parent_id === parentId) {
                        // Ajouter la catégorie actuelle avec une indentation selon le niveau
                        result.push({
                            id: category.id,
                            name: `${'--'.repeat(level)} ${category.name}`,
                        });

                        // Appeler la fonction récursivement pour ses enfants
                        const children = getRecursiveCategories(categories, category.id, level + 1);
                        result.push(...children);
                    }
                });

                return result;
            }

            // Fonction pour mettre à jour le select "Catégorie"
            function updateCategorieFilter(categories) {
                categorieFilterSelect.innerHTML =
                    '<option value="" disabled selected>Selectionner</option>'; // Réinitialiser le select

                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    categorieFilterSelect.appendChild(option);
                });
            }

            // Événement : Quand la "Famille" change
            categorieSelect.addEventListener('change', function() {
                const selectedFamilyId = parseInt(this.value, 10);

                if (selectedFamilyId) {
                    // Récupérer toutes les sous-catégories récursivement
                    const filteredCategories = getRecursiveCategories(categorieData, selectedFamilyId);

                    // Mettre à jour le select "Catégorie"
                    updateCategorieFilter(filteredCategories);
                } else {
                    // Réinitialiser si aucune famille n'est sélectionnée
                    categorieFilterSelect.innerHTML = '<option value="" disabled selected>Selectionner</option>';
                }
            });




            //recuperer la categorie selectionné
            $('#categorie').change(function(e) {
                e.preventDefault();

                var categorieSelect = $(this).val()



                //filtrer pour recuperer la categorie selectionnée
                var categorieFilter = categorieData.filter(function(item) {
                    return item.id == categorieSelect
                })


                console.log(categorieFilter);


                // si categorieFilter = restaurant , required false
                if (categorieFilter[0].famille == 'restaurant') {
                    $('.prixVente').prop('required', false)
                    $('.divVariante').hide();
                    var price = parseFloat($('.prixVente').val(''));
                    console.log(price);


                    $('.divUniteSortie').show();
                    $('.uniteSortie').prop('required', true);

                    // $('#quantiteUnite').prop('required', false)
                    // $('#quantiteUnite').prop('disabled', true)
                    // $('#quantiteUnite').val('')

                    // $('#uniteMesure').prop('required', false)
                    // $('#uniteMesure').prop('disabled', true)
                    // $('#uniteMesure').val('')
                } else {

                    $('.prixVente').prop('required', true)
                    $('.divVariante').show();

                    $('.divUniteSortie').hide();
                    $('.uniteSortie').prop('required', false);


                    // $('#quantiteUnite').prop('required', true)
                    // $('#quantiteUnite').prop('disabled', false)

                    // $('#uniteMesure').prop('required', true)
                    // $('#uniteMesure').prop('disabled', false)

                }

                // recuperer la famille de la categorie
                categoryFamille = categorieFilter[0];


            });



            //script for to send data 


            // product image
            document.querySelector("#product-image-input").addEventListener("change", function(event) {
                var preview = document.querySelector("#product-img");
                var file = document.querySelector("#product-image-input").files[0];
                var reader = new FileReader();
                reader.addEventListener("load", function() {
                    preview.src = reader.result;
                }, false);
                if (file) {
                    reader.readAsDataURL(file);
                }
            });


            $('#imageInput').on('change', function(e) {
                var files = e.target.files;
                for (var i = 0; i < files.length; i++) {
                    var reader = new FileReader();
                    reader.onload = function(e) {

                        var image = ` <div class="col-12 d-flex justify-content-between border border-secondary rounded"><img src="${e.target.result}" class="img-thumbnail rounded float-start" width="50" height="100">
                                   <button type="button" class="btn btn-danger my-2 remove-image">Delete</button>
                                    </div>  `;

                        $('#imageTableBody').append(image);
                    }
                    reader.readAsDataURL(files[i]);
                }
            });

            $(document).on('click', '.remove-image', function() {
                $(this).closest('div').remove();
            });

            $('#formSend').on('submit', function(e) {
                e.preventDefault();

                // recuperer le bouton de soumission
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


                // si la famille est bar  on recupere le prix de la premiere variante
                var prixVente = parseFloat($('.prixVente').val());
                var prixVenteHide = $('#prixVenteHide').val(prixVente);


                // Vérifier si un champ avec required est vide
                // var requiredFields = document.querySelectorAll('[required]');
                // for (var i = 0; i < requiredFields.length; i++) {
                //     var field = requiredFields[i];

                //     if (field.value === '') {
                //         var label = document.querySelector('label[for="' + field.id + '"]');
                //         var fieldName = label ? label.textContent.trim() : field.getAttribute('name');

                //         alert('Le champ "' + fieldName + '" est requis.');
                //         return;
                //     }
                // }
                // on verifie si une image principale à éte inseré
                if ($('#product-image-input').val() === '' && categoryFamille === 'bar') {

                    e.preventDefault();
                    submitButton.prop('disabled', false).html('Enregistrer'); // arreter le spinner

                } else {
                    var formData = new FormData(this);

                    $('#imageTableBody div').each(function() {
                        var imageFile = $(this).find('img').attr('src');
                        formData.append('images[]', imageFile)
                    });

                    $.ajax({
                        url: "{{ route('produit.store') }}", // Ajustez la route si nécessaire
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.success === true) {
                                // $('#imageTableBody').empty();

                                Swal.fire({
                                    title: 'Produit ajouté avec succès !',
                                    icon: 'success',
                                    showCancelButton: false,
                                    customClass: {
                                        confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                                        cancelButton: 'btn btn-danger w-xs mt-2',
                                    },
                                    buttonsStyling: false,
                                    showCloseButton: true
                                });

                                var url = "{{ route('produit.index') }}"; // Rediriger vers la route stock
                                window.location.replace(url);
                            }
                        },
                        error: function(xhr) {
                            // Gérer les erreurs
                            if (xhr.status === 409) {
                                // Produit déjà existant
                                Swal.fire({
                                    title: 'Ce produit a déjà été enregistré',
                                    text: $('#nomProduit').val(),
                                    icon: 'warning',
                                    customClass: {
                                        confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                                        cancelButton: 'btn btn-danger w-xs mt-2',
                                    },
                                    buttonsStyling: false,
                                    showCloseButton: true
                                });

                                submitButton.prop('disabled', false).html(
                                    'Enregistrer'); // arreter le spinner

                            } else {
                                // Autres types d'erreurs
                                Swal.fire({
                                    title: 'Erreur',
                                    text: 'Une erreur est survenue, veuillez réessayer.',
                                    icon: 'error',
                                    customClass: {
                                        confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                                        cancelButton: 'btn btn-danger w-xs mt-2',
                                    },
                                    buttonsStyling: false,
                                    showCloseButton: true
                                });
                                submitButton.prop('disabled', false).html(
                                    'Enregistrer'); // arreter le spinner
                            }
                        }
                    });


                }





            });
        </script>
    @endsection
@endsection
