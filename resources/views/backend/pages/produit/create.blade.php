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
                    <form id="formSend" autocomplete="off" class="needs-validation" novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card">
                                    <a href="{{ route('achat.create') }}" class="float-end text-decoration-underline">
                                        <i class="ri ri-arrow-left-line"></i>
                                        Faire un achat
                                    </a>
                                    <div class="card-body">
                                        <div class="mb-3 row">
                                            <div class="col-md-5">
                                                <label class="form-label" for="meta-title-input">Libellé <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="nom"  class="form-control" id="nomProduit"
                                                    required>
                                            </div>
                                            <div class="mb-3 col-md-5">
                                                <label class="form-label" for="product-title-input">Sélectionner une
                                                    categorie <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-control js-example-basic-single" name="categorie"
                                                    required>
                                                    <option value="" disabled selected>Selectionner</option>

                                                    @foreach ($data_categorie as $categorie)
                                                        @include(
                                                            'backend.pages.produit.partials.subCategorieOption',
                                                            ['category' => $categorie]
                                                        )
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2 mb-3">
                                                <label class="form-label" for="meta-title-input">Stock alerte <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="number" name="stock_alerte" class="form-control"
                                                    id="stockAlerte" required>
                                            </div>


                                        </div>
                                        <div>
                                            <label>Description</label>
                                            <textarea name="description" id="ckeditor-classic"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->

                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <h5 class="fs-14 mb-1">Image principale <span class="text-danger">*</span></h5>
                                            <div class="text-center">
                                                <div class="position-relative d-inline-block">
                                                    <div class="position-absolute top-100 start-100 translate-middle">
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
                                                        <input class="form-control d-none" id="product-image-input"
                                                            type="file" name="imagePrincipale" accept="image/*" required>
                                                        <div class="invalid-feedback">Ajouter une image</div>
                                                    </div>
                                                    <div class="avatar-lg">
                                                        <div class="avatar-title bg-light rounded">
                                                            <img src="" id="product-img" class="avatar-md h-auto" />
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
                var formData = new FormData(this);

                $('#imageTableBody div').each(function() {
                    var imageFile = $(this).find('img').attr('src');
                    formData.append('images[]', imageFile)
                });

                $.ajax({
                    url: "{{ route('produit.store') }}", // Adjust the route as needed
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
                            var url = "{{ route('achat.create') }}" // redirect route stock

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
