
<?php $__env->startSection('title'); ?>
    Categorie
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('backend.components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            categorie
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Gerer les categories
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">

    


        <div class="col-lg-6 d-none">
            <div class="card">

                <div class="card-body">
                    <form class="row g-3 needs-validation" method="post" action="<?php echo e(route('categorie.store')); ?>" novalidate>
                        <?php echo csrf_field(); ?>

                        
                        <div class="col-md-12">
                            <label for="validationCustom01" class="form-label">Ajouter une categorie principale </label>
                            <input type="text" name="name" class="form-control" id="validationCustom01"
                                placeholder="categorie1" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                       

                        <div class="col-md-8">
                            <label for="validationCustom01" class="form-label">Url</label>
                            <input type="text" name="url" class="form-control" id="validationCustom01"
                                placeholder="">
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        

                        <div class="col-md-4">
                            <label for="validationCustom01" class="form-label">Statut</label>
                            <select name="status" class="form-control">
                                <option value="active">Activé</option>
                                <option value="desactive">Desactivé</option>

                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>



                </div>
                <div class="">
                    <button type="submit" class="btn btn-primary w-100 ">Valider</button>
                </div>
                </form>
            </div>
        </div>

            <!-- ========== Start categorie list ========== -->
            <?php echo $__env->make('backend.pages.categorie.categorie-list', ['data_categorie' => $data_categorie], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <!-- ========== End categorie list ========== -->
        
        <!-- end row -->
    </div><!-- end col -->

    <!--end row-->

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/prismjs/prism.js')); ?>"></script>
    <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/modal.init.js')); ?>"></script>
    
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\restaurant\Restaurant-chez-jeanne\resources\views/backend/pages/categorie/create.blade.php ENDPATH**/ ?>