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
                    <form id="formSend" action="{{ route('produit.store') }}" method="POST" autocomplete="on"
                        class="needs-validation" novalidate enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-3 row">

                                            <div class="mb-3 col-md-2">
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

                                            <div class="col-md-3">
                                                <label class="form-label" for="meta-title-input">Libellé <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="nom" class="form-control" id="nomProduit"
                                                    required>
                                            </div>

                                            <div class="col-md-2 divVariante">
                                                <label class="form-label" for="meta-title-input">Variante<span
                                                        class="text-danger">*</span>
                                                </label>
                                                <select id="variante" class="form-control" name="variante_id" required>
                                                    <option value="" disabled selected>Selectionner</option>
                                                    @foreach ($data_variante as $variante)
                                                        <option value=" {{ $variante->id }} ">{{ $variante->libelle }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>



                                            {{-- <div class="col-md-2 mb-3 divValeurUnite">
                                                <label class="form-label" for="meta-title-input">valeur de l'unité
                                                    <i class="ri ri-information-line fs-6  text-warning p-1 rounded fw-bold"
                                                        data-bs-toggle="popover" data-bs-trigger="hover focus"
                                                        title="Information"
                                                        data-bs-content="exemple 1.5 L , utiliser un . ou , exemple 1,5"></i>
                                                </label>

                                                <input type="number" name="valeur_unite"
                                                    class="form-control customNumberInput" id="valeurUnite" step="0.01">
                                            </div>

                                            <div class="col-md-3 mb-3 divUniteProduit">
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
                                            </div> --}}


                                            <div class="col-md-3 mb-3 divUniteSortie ">
                                                <label class="form-label" for="meta-title-input">Unité de vente ou sortie
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="uniteSortie" class="form-control js-example-basic-single"
                                                    name="unite_sortie_id" required>
                                                    <option value="" selected>Choisir</option>
                                                    @foreach ($data_unite as $unite)
                                                        <option value="{{ $unite->id }}">{{ $unite->libelle }}
                                                            ({{ $unite->abreviation }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2 mb-3 divPrixVente">
                                                <label class="form-label" for="meta-title-input">Prix de vente
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" name="prix" class="form-control " id="prixVente"
                                                    required>
                                            </div>


                                            <div class="col-md-2 mb-3">
                                                <label class="form-label" for="meta-title-input">Stock alerte <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="number" name="stock_alerte" class="form-control"
                                                    id="stockAlerte" required>
                                            </div>


                                        </div>
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
                                                <div class="mb-3">
                                                    <label for="formFile" class="form-label">Image</label>
                                                    <input class="form-control" type="file" id="formFile"
                                                        name="imagePrincipale" accept="image/*">
                                                    <div class="mt-2 position-relative" style="display: inline-block;">
                                                        <img id="previewImage" src="#" alt="Aperçu"
                                                            style="max-width: 200px; display: none;" />
                                                        <button type="button" id="removeImageBtn"
                                                            class="btn btn-danger btn-sm"
                                                            style="position: absolute; top: 5px; right: 5px; display: none;">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
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



            // afficher les champs en fonction de la categorie selectionné
            //recuperer la categorie selectionné
            $('#categorie').change(function(e) {
                e.preventDefault();

                var categorieSelect = $(this).val()

                //filtrer pour recuperer la categorie selectionnée
                var categorieFilter = categorieData.filter(function(item) {
                    return item.id == categorieSelect
                })




                // si categorieFilter = restaurant , afficher les champs de restaurant
                if (categorieFilter[0].famille == 'restaurant') {
                    //supprimer le required du prix de vente
                 

                    //cacher les div 
                    $('.divVariante').hide();
                    // $('.divValeurUnite').hide();
                    // $('.divUniteProduit').hide();
                    $('.divPrixVente').hide();
                  
                    //mettre le required a false
                    $('#prixVente').prop('required', false);
                    $('#variante').prop('required', false);

                } else {
                  
                    //afficher variante
                    $('.divVariante').show();
                    // $('.divValeurUnite').show();
                    // $('.divUniteProduit').show();
                    $('.divPrixVente').show();
                    //mettre le required a true
                    $('#prixVente').prop('required', true);
                    $('#variante').prop('required', true);

                }


            });


            

            // Aperçu et suppression de l'image principale
            $('#formFile').on('change', function(e) {
                const [file] = this.files;
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#previewImage').attr('src', e.target.result).show();
                        $('#removeImageBtn').show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#previewImage').hide();
                    $('#removeImageBtn').hide();
                }
            });

            $('#removeImageBtn').on('click', function() {
                $('#formFile').val('');
                $('#previewImage').attr('src', '#').hide();
                $(this).hide();
            });
        </script>
    @endsection
@endsection
