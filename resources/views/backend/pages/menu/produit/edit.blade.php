@extends('backend.layouts.master')
@section('title')
    Menu
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        <link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">

        @slot('li_1')
            Plat
        @endslot
        @slot('title')
            Modifier un plat
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form id="formSend" autocomplete="off" class="needs-validation" novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card">

                                    <div class="card-body">
                                        <div class="mb-3 row">

                                            <div class="mb-3 col-md-8">
                                                <label class="form-label" for="product-title-input">Categorie principale <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-control js-example-basic-single" name="categorie"
                                                    required>
                                                    <option value="" disabled selected>Selectionner</option>

                                                    @foreach ($data_categorie as $categorie)
                                                        @include(
                                                            'backend.pages.menu.produit.partials.subCategorieOptionEdit',
                                                            ['category' => $categorie]
                                                        )
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="categorie-menu-input">Categorie menu <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-control js-example-basic-single" name="categorie_menu_id" >
                                                    <option value=""  selected>Selectionner</option>

                                                    @foreach ($data_categorie_menu as $categorie_menu)
                                                        <option value="{{ $categorie_menu->id }}" {{ $data_plat->categorie_menu_id == $categorie_menu->id ? 'selected' : '' }}>
                                                            {{ $categorie_menu->nom }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-8">
                                                <label class="form-label" for="meta-title-input">Libellé <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="nom" value="{{ $data_plat['nom'] }}"
                                                    class="form-control" id="nomProduit" required>
                                            </div>
                                          
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="meta-title-input">Prix <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="number" value="{{ $data_plat->prix }}"
                                                    name="prix" class="form-control" id="prix" required>
                                            </div>


                                        </div>
                                        <div>
                                            <label>Description</label>
                                            <textarea name="description" id="ckeditor-classic">
                                                {{ $data_plat['description'] }}
                                            </textarea>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <label class="form-check-label" for="customAff">Visibilité </label>

                                            <div class="form-check form-switch form-switch-lg col-md-2" dir="ltr">
                                                <input type="checkbox" name="statut" class="form-check-input"
                                                    id="customAff" {{$data_plat['statut'] =='active' ? 'checked' : ''}}>
                                            </div>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->

                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="formFile" class="form-label">Image</label>
                                                    <input class="form-control" type="file" id="formFile"
                                                        name="imagePrincipale" accept="image/*">
                                                    <div class="mt-2 position-relative" style="display: inline-block;">
                                                        <img id="previewImage"
                                                            src="{{ URL::asset($data_produit->getFirstMediaUrl('imagePrincipale')) }}"
                                                            alt="Aperçu" style="max-width: 200px; display: none;" />
                                                        <button type="button" id="removeImageBtn"
                                                            class="btn btn-danger btn-sm"
                                                            style="position: absolute; top: 5px; right: 5px; display: none;">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
                                                </div>
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
            //script for to send data 


            // product image
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


            //get gallery Image from controller for edit
            var getGalleryProduct = {{ Js::from($galleryProduit) }}

            for (let i = 0; i < getGalleryProduct.length; i++) {
                const element = getGalleryProduct[i];
                var image = ` <div class="col-12 d-flex justify-content-between border border-secondary rounded"><img src="data:image/jpeg;base64,${element}" class="img-thumbnail rounded float-start" width="50" height="100">
                                   <button type="button" class="btn btn-danger my-2 remove-image">Delete</button>
                                    </div>  `;
                console.log('edit:', image);
                $('#imageTableBody').append(image);
            }



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
                var productId = {{ Js::from($id) }} // product Id
                var formData = new FormData(this);

                $('#imageTableBody div').each(function() {
                    var imageFile = $(this).find('img').attr('src');
                    formData.append('images[]', imageFile)
                });

                $.ajax({
                    url: "/admin/plat/update/" + productId, // Adjust the route as needed
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#imageTableBody').empty();

                        if (response.message == 'operation reussi') {
                            Swal.fire({
                                title: 'Produit ajouté avec success!',
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
                            var url = "{{ route('plat.index') }}" // redirect route product list

                            window.location.replace(url);
                        } else if (response == 'The nom has already been taken.') {
                            Swal.fire({
                                title: 'Ce produit existe déjà ?',
                                text: $('#nomProduit').val(),
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
        </script>
    @endsection
@endsection
