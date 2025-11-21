<div class="row">
    <div class="col-xxl-6">
        <div class="card">
            <div class="modal fade" id="createGarnitureModal" tabindex="-1" aria-labelledby="createGarnitureModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createGarnitureModalLabel">Créer un plat</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form  autocomplete="off" class="needs-validation formSend" novalidate
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="mb-3 row">
                                                                    <div class="mb-3 col-md-4">
                                                                        <label class="form-label"
                                                                            for="categorie-menu-input">Categorie menu
                                                                            <span class="text-danger">*</span>
                                                                        </label>
                                                                        <select class="form-control " name="categorie">
                                                                            @php
                                                                                $data_categorie = app\Models\CategorieMenu::whereSlug(
                                                                                    'garnitures',
                                                                                )->first();
                                                                            @endphp
                                                                            <option selected 
                                                                                value="{{ $data_categorie->id ?? '' }}">
                                                                                {{ $data_categorie->nom  ?? '' }}
                                                                            </option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label class="form-label"
                                                                            for="meta-title-input">Libellé <span
                                                                                class="text-danger">*</span>
                                                                        </label>
                                                                        <input type="text" name="nom"
                                                                            class="form-control" id="nom"
                                                                            required>
                                                                    </div>

                                                                    {{-- <div class="col-md-2 mb-3">
                                                                        <label class="form-label"
                                                                            for="meta-title-input">prix <span
                                                                                class="text-danger">*</span>
                                                                        </label>
                                                                        <input type="number" name="prix"
                                                                            class="form-control" id="prix"
                                                                            required>
                                                                    </div> --}}
                                                                    <div class="col-md-2">
                                                                        <label class="form-check-label"
                                                                            for="customAff">Visible <span>(activé par defaut
                                                                                )</span> </label>
    
                                                                        <div class="form-check form-switch form-switch-lg col-md-2"
                                                                            dir="ltr">
                                                                            <input type="checkbox" name="statut"
                                                                                class="form-check-input" id="customAff"
                                                                                checked>
                                                                        </div>
                                                                        <div class="valid-feedback">
                                                                            Looks good!
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                {{-- <div>
                                                                    <label>Description</label>
                                                                    <textarea name="description" id="ckeditor-classic"></textarea>
                                                                </div> --}}
                                                                
                                                            </div>
                                                        </div>
                                                        <!-- end card -->
                                                    </div>
                                                    <!-- end col -->

                                                    {{-- <div class="col-lg-4">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="mb-4">
                                                                    <h5 class="fs-14 mb-1">Image principale </h5>
                                                                    <div class="text-center">
                                                                        <div class="position-relative d-inline-block">
                                                                            <div
                                                                                class="position-absolute top-100 start-100 translate-middle">
                                                                                <label for="product-image-input"
                                                                                    class="mb-0"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-placement="right"
                                                                                    title="Select Image">
                                                                                    <div class="avatar-xs">
                                                                                        <div
                                                                                            class="avatar-title bg-light border rounded-circle text-muted cursor-pointer">
                                                                                            <i
                                                                                                class="ri-image-fill"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                </label>
                                                                                <input class="form-control d-none"
                                                                                    id="product-image-input"
                                                                                    type="file"
                                                                                    name="imagePrincipale"
                                                                                    accept="image/*">
                                                                                <div class="invalid-feedback">Ajouter
                                                                                    une image</div>
                                                                            </div>
                                                                            <div class="avatar-lg">
                                                                                <div
                                                                                    class="avatar-title bg-light rounded">
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
                                                                    <input type="file" id="imageInput"
                                                                        accept="image/*" class="form-control d-none"
                                                                        multiple>

                                                                    <div class="row" id="imageTableBody"></div>

                                                                    <div class="valid-feedback">
                                                                        Success!
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <!-- end card -->


                                                    </div> --}}
                                                </div>
                                                <!-- end row -->
                                                <!-- end card -->
                                                <div class="text-end mb-3">
                                                    <button type="submit"
                                                        class="btn btn-success w-lg btn-addPlat">Enregistrer</button>
                                                </div>
                                            </div>
                                        </div><!-- end row -->
                                    </div><!-- end col -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 
<script>
    $('#formSend').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
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
                    var url = "{{ route('plat-menu.index') }}" // redirect route stock

                    window.location.replace(url);
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
</script> --}}
