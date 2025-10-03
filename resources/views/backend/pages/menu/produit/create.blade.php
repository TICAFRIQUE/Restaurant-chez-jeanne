@extends('backend.layouts.master')
@section('title')
    Menu
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        <link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">

        @slot('li_1')
            plat menu
        @endslot
        @slot('title')
            Créer un nouveau plat
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form id="formSend" method="POST" action="{{ route('plat.store') }}" autocomplete="off" class="needs-validation" novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-3 row">

                                            <div class="mb-3 col-md-8">
                                                <label class="form-label" for="product-title-input">Categorie
                                                    principale<span class="text-danger">*</span>
                                                </label>
                                                <select class="form-control js-example-basic-single" name="categorie"
                                                    required>
                                                    <option value="" disabled selected>Selectionner</option>

                                                    @foreach ($data_categorie as $categorie)
                                                        @include(
                                                            'backend.pages.menu.produit.partials.subCategorieOption',
                                                            ['category' => $categorie]
                                                        )
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="categorie-menu-input">Categorie menu
                                                </label>
                                                <select class="form-control js-example-basic-single"
                                                    name="categorie_menu_id">
                                                    <option value="" selected>Selectionner</option>

                                                    @foreach ($data_categorie_menu as $categorie_menu)
                                                        <option value="{{ $categorie_menu->id }}">{{ $categorie_menu->nom }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-8">
                                                <label class="form-label" for="meta-title-input">Libellé <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="nom" class="form-control" required>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="meta-title-input">prix <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="number" name="prix" class="form-control" id="prix"
                                                    required>
                                            </div>


                                        </div>
                                        <div>
                                            <label>Description</label>
                                            <textarea name="description" id="ckeditor-classic"></textarea>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <label class="form-check-label" for="customAff">Visible <span>(activé par defaut
                                                    )</span> </label>

                                            <div class="form-check form-switch form-switch-lg col-md-2" dir="ltr">
                                                <input type="checkbox" name="statut" class="form-check-input"
                                                    id="customAff" checked>
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
                                    <input class="form-control" type="file" id="formFile" name="imagePrincipale"
                                        accept="image/*">
                                    <div class="mt-2 position-relative" style="display: inline-block;">
                                        <img id="previewImage" src="#" alt="Aperçu"
                                            style="max-width: 200px; display: none;" />
                                        <button type="button" id="removeImageBtn" class="btn btn-danger btn-sm"
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
