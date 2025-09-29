@extends('backend.layouts.master')
@section('title')
    Menu
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        <link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">

        @slot('li_1')
            Menu
        @endslot
        @slot('title')
            Modifier un menu
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('menu.update', $menu->id) }}" autocomplete="off" id="formSave"
                        class="needs-validation" novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3 row">
                                        <div class="alert alert-info alert-dismissible alert-label-icon label-arrow fade show material-shadow"
                                            role="alert">
                                            <i class="ri-airplay-line label-icon"></i><strong>Selectionnez les
                                                differents plats pour composer votre menu : </strong>

                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label" for="meta-title-input">Libellé
                                            </label>
                                            <input type="text" value="{{ $menu->libelle }}" name="libelle"
                                                class="form-control">
                                        </div>


                                        <div class="col-md-4 mb-3">
                                            <label class="form-label" for="meta-title-input">Date <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="datetime-local" id="currentDate" value="{{ $menu->date_menu }}"
                                                name="date_menu" class="form-control" required>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label" for="image-input">Image de fond </label>
                                            <input type="file" id="image-input" name="image" class="form-control">
                                        </div>

                                        <!-- ========== Start product menu for checked ========== -->
                                        {{-- @include('backend.pages.menu.partials.categorieProduct') --}}
                                        <!-- ========== End product menu for checked ========== -->


                                        <div class="container-fluid my-4 divVariante">
                                            <div id="variantes-container">
                                                <div class="row variante-row mb-4">

                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary mb-3" id="add-variante">Ajouter <i
                                                    class="mdi mdi-plus"></i>
                                            </button>

                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!-- end card -->

                            <!-- end col -->

                        </div>
                        <!-- end row -->
                        <!-- end card -->
                        <div class="text-end mb-3">
                            <button type="submit" id="btnSubmit" class="btn btn-success w-lg">Enregistrer</button>
                        </div>
                    </form>

                    @include('backend.pages.menu.plat-menu.partials.create-modal-plat', [
                        'data_categorie' => $categorie_menu,
                    ])

                    @include('backend.pages.menu.plat-menu.partials.create-modal-complement', [
                        'data_categorie' => $categorie_menu,
                    ])

                    @include('backend.pages.menu.plat-menu.partials.create-modal-garniture', [
                        'data_categorie' => $categorie_menu,
                    ])
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
            $(function() {

                // Vérifier si un plat est choisi ? bouton save active : disabled
                var checkedItems = []
                $('.produit').change(function() {
                    if ($(this).is(':checked')) {
                        checkedItems.push($(this).val());
                    } else {
                        var index = checkedItems.indexOf($(this).val());
                        if (index !== -1) {
                            checkedItems.splice(index, 1);
                        }
                    }

                    //disabled and enable button save 
                    if (checkedItems.length > 0) {
                        $('#btnSubmit').prop('disabled', false);
                    } else {
                        $('#btnSubmit').prop('disabled', true);
                    }
                });


                /////////////////////////////////////////////////################################################//////////////////////////////
                // Gestion des variantes

                // liste des plats from controller
                var plats = @json($plats)


                function loadAllOptions() {
                    // Sauvegarder les sélections actuelles
                    let platsSelected = [];
                    let complementsSelected = [];
                    let garnituresSelected = [];

                    $('.plats-select').each(function() {
                        platsSelected.push($(this).val());
                    });

                    $('.complements-select').each(function() {
                        complementsSelected.push($(this).val());
                    });

                    $('.garnitures-select').each(function() {
                        garnituresSelected.push($(this).val());
                    });

                    $.ajax({
                        url: "{{ route('menu.options') }}", // URL pour récupérer les données
                        method: "GET",
                        success: function(response) {
                            // Charger les plats
                            let platsOptions = `<option value="">Sélectionner un plat</option>`;
                            response.plats.forEach(item => {
                                platsOptions += `<option value="${item.id}">${item.nom}</option>`;
                            });
                            $('.plats-select').html(platsOptions);

                            // Charger les compléments
                            // let complementsOptions = `<option value="">Sélectionner un complément</option>`;
                            let complementsOptions
                            response.plats_complements.forEach(item => {
                                complementsOptions +=
                                    `<option value="${item.id}">${item.nom}</option>`;
                            });
                            $('.complements-select').html(complementsOptions);

                            // Charger les garnitures
                            // let garnituresOptions = `<option value="">Sélectionner une garniture</option>`;
                            let garnituresOptions
                            response.plats_garnitures.forEach(item => {
                                garnituresOptions +=
                                    `<option value="${item.id}">${item.nom}</option>`;
                            });
                            $('.garnitures-select').html(garnituresOptions);

                            // Réappliquer les sélections
                            $('.plats-select').each(function(index) {
                                $(this).val(platsSelected[index]).trigger('change');
                            });

                            $('.complements-select').each(function(index) {
                                $(this).val(complementsSelected[index]).trigger('change');
                            });

                            $('.garnitures-select').each(function(index) {
                                $(this).val(garnituresSelected[index]).trigger('change');
                            });



                            // Ajouter un événement pour filtrer les plats en fonction de la catégorie
                            $('.categorie').on('change', function() {
                                let categorieId = $(this).val();
                                let platsSelect = $(this).closest('.variante-row').find(
                                    '.plats-select');
                                platsSelect.html('');
                                platsSelect.append(
                                    `<option value="">Sélectionner un plat</option>`);
                                response.plats.forEach(item => {
                                    if (item.categorie_menu_id == categorieId) {
                                        platsSelect.append(
                                            `<option value="${item.id}">${item.nom}</option>`
                                        );
                                    }
                                });
                            });
                        },
                        error: function() {
                            alert("Erreur lors du chargement des données.");
                        }
                    });
                }

                // // Initialiser au chargement de la page
                $(document).ready(function() {
                    loadAllOptions();
                });

                /////#################### start POUR LA GESTION EDITER ########################//////////////

                // recuperation du menu depuis le controller
                // let dataMenu = @json($menu->plats)

                // let nombrePlats = dataMenu.length;

                // // afficher les lignes en fonction du nombre de plats
                // for (let i = 0; i < nombrePlats; i++) {



                //         const container = document.getElementById('variantes-container');
                //         const newRow = document.createElement('div');
                //         newRow.classList.add('row', 'variante-row', 'mb-4');

                //         newRow.innerHTML = `
        //                     <div class="col-2">
        //                     <label for="variante">Catégorie :</label>
        //                     <div class="d-flex">
        //                         <select name="plats[${i}][categorie_id]" class="form-control js-example-basic-single categorie" required>
        //                             <option value="" selected>Sélectionner</option>
        //                             @foreach ($categorie_menu as $categorie)
        //                                 <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
        //                             @endforeach
        //                         </select>
        //                     </div>
        //                 </div>

        //                 <div class="col-3">
        //                     <label for="variante">Libellé :</label>
        //                     <div class="d-flex">
        //                         <select name="plats[${i}][plat_selected]" class="form-control js-example-basic-single plats-select" required>
        //                             <option value="" selected> Sélectionner</option>
        //                             @foreach ($plats as $plat)
        //                                 <option value="{{ $plat->id }}">{{ $plat->nom }}</option>
        //                             @endforeach
        //                         </select>
        //                         <button type="button" class="btn btn-primary ml-2 btn-sm" data-bs-toggle="modal" data-bs-target="#createPlatModal">
        //                             <i class="mdi mdi-plus"></i>
        //                         </button>
        //                     </div>
        //                 </div>

        //                 <div class="col-3">
        //                     <label for="variante">Compléments :</label>
        //                     <div class="d-flex">
        //                         <select name="plats[${i}][complements][]" class="form-control js-example-basic-single complements-select" multiple>
        //                             <option value=""> Sélectionner</option>
        //                             @foreach ($plats_complements as $complement)
        //                                 <option value="{{ $complement->id }}">{{ $complement->nom }}</option>
        //                             @endforeach
        //                         </select>
        //                         <button type="button" class="btn btn-primary ml-2 btn-sm" data-bs-toggle="modal" data-bs-target="#createComplementModal">
        //                             <i class="mdi mdi-plus"></i>
        //                         </button>
        //                     </div>
        //                 </div>

        //                 <div class="col-3">
        //                     <label for="variante">Garnitures :</label>
        //                     <div class="d-flex">
        //                         <select name="plats[${i}][garnitures][]" class="form-control js-example-basic-single garnitures-select" multiple>
        //                             <option value=""> Sélectionner</option>
        //                             @foreach ($plats_garnitures as $garniture)
        //                                 <option value="{{ $garniture->id }}">{{ $garniture->nom }}</option>
        //                             @endforeach
        //                         </select>
        //                         <button type="button" class="btn btn-primary ml-2 btn-sm" data-bs-toggle="modal" data-bs-target="#createGarnitureModal">
        //                             <i class="mdi mdi-plus"></i>
        //                         </button>
        //                     </div>
        //                 </div>

        //                 <div class="col-1 mt-2">
        //                     <button type="button" class="btn btn-danger remove-variante mt-3">
        //                         <i class="mdi mdi-delete remove-variante"></i>
        //                     </button>
        //                 </div>

        //     `;

                //         container.appendChild(newRow);

                //         // Réinitialiser et appliquer Select2 sur les nouveaux champs ajoutés
                //         $(newRow).find('.js-example-basic-single').select2();

                //         // varianteIndex++;



                //     // document.getElementById('variantes-container').addEventListener('click', function(e) {
                //     //     if (e.target.classList.contains('remove-variante')) {
                //     //         e.target.closest('.variante-row').remove();
                //     //     }
                //     // });
                // }


                let dataMenu = @json($menu->plats);

                let container = document.getElementById('variantes-container');

                dataMenu.forEach((plat, i) => {
                    let newRow = document.createElement('div');
                    newRow.classList.add('row', 'variante-row', 'mb-4');

                    newRow.innerHTML = `
        <div class="col-2">
            <label for="variante">Catégorie :</label>
            <div class="d-flex">
                <select name="plats[${i}][categorie_id]" class="form-control js-example-basic-single categorie" required>
                    <option value="" disabled ${plat.categorie_menu ? "" : "selected"}>Sélectionner</option>
                    @foreach ($categorie_menu as $categorie)
                        <option value="{{ $categorie->id }}" ${plat.categorie_menu && plat.categorie_menu.id == {{ $categorie->id }} ? 'selected' : ''}>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-3">
            <label for="variante">Libellé :</label>
            <div class="d-flex">
                <select name="plats[${i}][plat_selected]" class="form-control js-example-basic-single plats-select" required>
                    <option value="" disabled ${plat.id ? "" : "selected"}>Sélectionner</option>
                    @foreach ($plats as $platOption)
                        <option value="{{ $platOption->id }}" ${plat.id == {{ $platOption->id }} ? 'selected' : ''}>
                            {{ $platOption->nom }}
                        </option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-primary ml-2 btn-sm" data-bs-toggle="modal" data-bs-target="#createPlatModal">
                    <i class="mdi mdi-plus"></i>
                </button>
            </div>
        </div>

        <div class="col-3">
            <label for="variante">Compléments :</label>
            <div class="d-flex">
                <select name="plats[${i}][complements][]" class="form-control js-example-basic-single complements-select" multiple>
                    @foreach ($plats_complements as $complement)
                        <option value="{{ $complement->id }}" ${plat.complements && plat.complements.some(c => c.id == {{ $complement->id }}) ? 'selected' : ''}>
                            {{ $complement->nom }}
                        </option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-primary ml-2 btn-sm" data-bs-toggle="modal" data-bs-target="#createComplementModal">
                    <i class="mdi mdi-plus"></i>
                </button>
            </div>
        </div>

        <div class="col-3">
            <label for="variante">Garnitures :</label>
            <div class="d-flex">
                <select name="plats[${i}][garnitures][]" class="form-control js-example-basic-single garnitures-select" multiple>
                    @foreach ($plats_garnitures as $garniture)
                        <option value="{{ $garniture->id }}" ${plat.garnitures && plat.garnitures.some(g => g.id == {{ $garniture->id }}) ? 'selected' : ''}>
                            {{ $garniture->nom }}
                        </option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-primary ml-2 btn-sm" data-bs-toggle="modal" data-bs-target="#createGarnitureModal">
                    <i class="mdi mdi-plus"></i>
                </button>
            </div>
        </div>

        <div class="col-1 mt-2">
            <button type="button" class="btn btn-danger remove-variante mt-3">
                <i class="mdi mdi-delete remove-variante"></i>
            </button>
        </div>
    `;

                    container.appendChild(newRow);

                    // Réinitialiser et appliquer Select2 sur les nouveaux champs ajoutés
                    $(newRow).find('.js-example-basic-single').select2();

                    if (i === 0) {
                        newRow.querySelector('.remove-variante').style.display = 'none';
                    }
                });


                /////#################### END POUR LA GESTION EDITER ########################//////////////




                /////#################### START Pour ajouter un nouvel element ########################//////////////
                let varianteIndex = parseInt(dataMenu.length);

                document.getElementById('add-variante').addEventListener('click', function() {
                    loadAllOptions();
                    const container = document.getElementById('variantes-container');
                    const newRow = document.createElement('div');
                    newRow.classList.add('row', 'variante-row', 'mb-4');

                    newRow.innerHTML = `
                                    <div class="col-2">
                                    <label for="variante">Catégorie :</label>
                                    <div class="d-flex">
                                        <select name="plats[${varianteIndex}][categorie_id]" class="form-control js-example-basic-single categorie" required>
                                            <option value="" selected>Sélectionner</option>
                                            @foreach ($categorie_menu as $categorie)
                                                <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <label for="variante">Libellé :</label>
                                    <div class="d-flex">
                                        <select name="plats[${varianteIndex}][plat_selected]" class="form-control js-example-basic-single plats-select" required>
                                            <option value="" selected> Sélectionner</option>
                                            @foreach ($plats as $plat)
                                                <option value="{{ $plat->id }}">{{ $plat->nom }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-primary ml-2 btn-sm" data-bs-toggle="modal" data-bs-target="#createPlatModal">
                                            <i class="mdi mdi-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <label for="variante">Compléments :</label>
                                    <div class="d-flex">
                                        <select name="plats[${varianteIndex}][complements][]" class="form-control js-example-basic-single complements-select" multiple>
                                            <option value=""> Sélectionner</option>
                                            @foreach ($plats_complements as $complement)
                                                <option value="{{ $complement->id }}">{{ $complement->nom }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-primary ml-2 btn-sm" data-bs-toggle="modal" data-bs-target="#createComplementModal">
                                            <i class="mdi mdi-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <label for="variante">Garnitures :</label>
                                    <div class="d-flex">
                                        <select name="plats[${varianteIndex}][garnitures][]" class="form-control js-example-basic-single garnitures-select" multiple>
                                            <option value=""> Sélectionner</option>
                                            @foreach ($plats_garnitures as $garniture)
                                                <option value="{{ $garniture->id }}">{{ $garniture->nom }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-primary ml-2 btn-sm" data-bs-toggle="modal" data-bs-target="#createGarnitureModal">
                                            <i class="mdi mdi-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-1 mt-2">
                                    <button type="button" class="btn btn-danger remove-variante mt-3">
                                        <i class="mdi mdi-delete remove-variante"></i>
                                    </button>
                                </div>

                    `;

                    container.appendChild(newRow);

                    // Réinitialiser et appliquer Select2 sur les nouveaux champs ajoutés
                    $(newRow).find('.js-example-basic-single').select2();

                    varianteIndex++;
                });


                document.getElementById('variantes-container').addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-variante')) {
                        e.target.closest('.variante-row').remove();
                    }
                });
                /////#################### END Pour ajouter un nouvel element ########################//////////////





                //////////////////////////////////Envoyer le menu au controller ///////////////////////////////////////////////

                /////////////////////////////////############### Ajouter les nouveaux enregistrement #########################///////////////////////////////////////////


                document.querySelector("#product-image-input").addEventListener("change", function() {
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

                //enregistrer le formulaire ==> nouveau plat entran
                $('.formSend').on('submit', function(e) {

                    e.preventDefault();

                    // on verifie si une image principale à éte inseré
                    // if ($('#product-image-input').val() === '') {
                    //     e.preventDefault();
                    // } else {
                    //     e.preventDefault();

                    // }

                    // var categorie = $('select[name="categorie"] option:selected').text();
                    // console.log(categorie);


                    var formData = new FormData(this);

                    $('#imageTableBody div').each(function() {
                        var imageFile = $(this).find('img').attr('src');
                        formData.append('images[]', imageFile)
                    });

                    $.ajax({
                        url: "{{ route('plat-menu.store') }}", // Adjust the route as needed
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#imageTableBody').empty();

                            if (response.message == 'operation reussi') {
                                Swal.fire({
                                    title: 'plat ajouté avec success!',
                                    // text: 'You clicked the button!',
                                    icon: 'success',
                                    showCancelButton: false,
                                    customClass: {
                                        confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                                        cancelButton: 'btn btn-danger w-xs mt-2',
                                    },
                                    buttonsStyling: false,
                                    showCloseButton: true
                                })

                                // // Ajouter la nouvelle valeur dans dans la liste
                                // let newOption = new Option(response.plat.nom, response.plat.id,
                                //     false, false);
                                // $('.js-example-basic-single').append(newOption).trigger('change');

                                $('.formSend')[0].reset();
                                loadAllOptions();
                                $('#createPlatModal').modal('hide'); // Fermer la modale
                                $('#createGarnitureModal').modal('hide'); // Fermer la modale
                                $('#createComplementModal').modal('hide'); // Fermer la modale



                            } else if (response == 'The nom has already been taken.') {
                                Swal.fire({
                                    title: 'Ce plat existe déjà ?',
                                    text: $('#nom').val(),
                                    icon: 'warning',
                                    customClass: {
                                        confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                                        cancelButton: 'btn btn-danger w-xs mt-2',
                                    },
                                    buttonsStyling: false,
                                    showCloseButton: true
                                })
                            }

                        },

                    });


                });



                //si je clique sur le boutton valider
                $('#btnSubmit').on('click', function(e) {
                    var form = document.getElementById('formSave');
                    var categories = document.querySelectorAll('select[name^="plats"][name*="[categorie_id]"]');
                    var plats = document.querySelectorAll('select[name^="plats"][name*="[plat_selected]"]');

                    categories.forEach(function(categorie) {
                        var categorieValue = categorie.options[categorie.selectedIndex].value;
                        if (categorieValue === '') {
                            e.preventDefault();
                            Swal.fire({
                                title: 'Erreur',
                                text: 'Verifier si toutes les categories sont choisies',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                                    cancelButton: 'btn btn-danger w-xs mt-2',
                                },
                                buttonsStyling: false,
                                showCloseButton: true
                            });
                        }
                    });

                    plats.forEach(function(plat) {
                        var platValue = plat.options[plat.selectedIndex].value;
                        if (platValue === '') {
                            e.preventDefault();
                            Swal.fire({
                                title: 'Erreur',
                                text: 'Verifier si tous les plats sont choisies',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                                    cancelButton: 'btn btn-danger w-xs mt-2',
                                },
                                buttonsStyling: false,
                                showCloseButton: true
                            });
                        }
                    });

                    if (categories.every(categorie => categorie.options[categorie.selectedIndex].value !==
                            '') && plats.every(plat => plat.options[plat.selectedIndex].value !== '')) {
                        form.submit();
                    }


                });



            });
        </script>
    @endsection

@endsection
