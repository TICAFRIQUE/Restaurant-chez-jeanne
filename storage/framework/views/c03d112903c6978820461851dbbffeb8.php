<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('backend.components.breadcrumb'); ?>
        <link href="<?php echo e(URL::asset('build/libs/dropzone/dropzone.css')); ?>" rel="stylesheet">

        <?php $__env->slot('li_1'); ?>
            Produit
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Créer un nouveau produit
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form id="formSend" autocomplete="off" class="needs-validation" novalidate enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card">
                                    
                                    <div class="card-body">
                                        <div class="mb-3 row">

                                            <div class="mb-3 col-md-7">
                                                <label class="form-label" for="product-title-input">Sélectionner une
                                                    categorie <span class="text-danger">*</span>
                                                </label>
                                                <select id="categorie" class="form-control js-example-basic-single"
                                                    name="categorie" required>
                                                    <option value="" disabled selected>Selectionner</option>

                                                    <?php $__currentLoopData = $data_categorie; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php echo $__env->make(
                                                            'backend.pages.produit.partials.subCategorieOption',
                                                            ['category' => $categorie]
                                                        , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>

                                            <div class="col-md-5">
                                                <label class="form-label" for="meta-title-input">Libellé <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="nom" class="form-control" id="nomProduit"
                                                    required>
                                            </div>

                                            


                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="meta-title-input">Qté mesure<span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input type="number" name="quantite_unite"
                                                    class="form-control customNumberInput" id="quantiteUnite" required>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="meta-title-input">Unite mesure<span
                                                        class="text-danger">*</span>
                                                </label>
                                                <select id="uniteMesure" class="form-control js-example-basic-single"
                                                    name="unite_mesure" required>
                                                    <option value="" disabled selected>Choisir</option>
                                                    <?php $__currentLoopData = $data_unite; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($unite->id); ?>"><?php echo e($unite->libelle); ?>

                                                            (<?php echo e($unite->abreviation); ?>)
                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>

                                            <div class="col-md-4 mb-3">
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

    <?php $__env->startSection('script'); ?>
        <script src="<?php echo e(URL::asset('build/libs/prismjs/prism.js')); ?>"></script>
        <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script>
        <script src="<?php echo e(URL::asset('build/js/pages/modal.init.js')); ?>"></script>
        
        <script src="<?php echo e(URL::asset('build/tinymce/tinymce.min.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js')); ?>"></script>

        <script src="<?php echo e(URL::asset('build/libs/dropzone/dropzone-min.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('build/js/pages/ecommerce-product-create.init.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>

        <script>
            //Afficher les champs en fonction de la categorie selectionné
            let categoryFamille;
            var categorieData = <?php echo e(Js::from($categorieAll)); ?> // from product controller
            //recuperer la categorie selectionné
            $('#categorie').change(function(e) {
                e.preventDefault();
                var categorieSelect = $(this).val()

                //filtrer pour recuperer la categorie selectionnée
                var categorieFilter = categorieData.filter(function(item) {
                    return item.id == categorieSelect
                })


                // si categorieFilter = restaurant , required false
                if (categorieFilter[0].famille == 'restaurant') {
                    $('#quantiteUnite').prop('required', false)
                    $('#quantiteUnite').prop('disabled', true)
                    $('#quantiteUnite').val('')

                    $('#uniteMesure').prop('required', false)
                    $('#uniteMesure').prop('disabled', true)
                    $('#uniteMesure').val('')
                } else {
                    $('#quantiteUnite').prop('required', true)
                    $('#quantiteUnite').prop('disabled', false)

                    $('#uniteMesure').prop('required', true)
                    $('#uniteMesure').prop('disabled', false)

                }

                // recuperer la famille de la categorie
                categoryFamille = categorieFilter[0];


            });





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
                
                // on verifie si une image principale à éte inseré
                if ($('#product-image-input').val() === '' && categoryFamille === 'bar') {
                    e.preventDefault();
                } else {
                    var formData = new FormData(this);

                    $('#imageTableBody div').each(function() {
                        var imageFile = $(this).find('img').attr('src');
                        formData.append('images[]', imageFile)
                    });

                    $.ajax({
                        url: "<?php echo e(route('produit.store')); ?>", // Adjust the route as needed
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
                                var url = "<?php echo e(route('produit.index')); ?>" // redirect route stock

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

                }





            });
        </script>
    <?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/maxisgwd/restaurant.maxisujets.net/resources/views/backend/pages/produit/create.blade.php ENDPATH**/ ?>