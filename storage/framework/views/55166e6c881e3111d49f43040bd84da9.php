<div class="row">
    <div class="col-xxl-6">
        <div class="card">
            <!-- Default Modals -->
            <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
                style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalLabel">Créer une nouvelle unité de mesure </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">

                            <form class="row g-3 needs-validation" method="post" action="<?php echo e(route('unite.store')); ?>" novalidate>
                                <?php echo csrf_field(); ?>
                                <div class="col-md-6">
                                    <label for="validationCustom01" class="form-label">Libelle</label>
                                    <input type="text" name="libelle" class="form-control" id="validationCustom01" 
                                        required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="validationCustom01" class="form-label">Abréviation</label>
                                    <input type="text" name="abreviation" class="form-control" id="validationCustom01" 
                                        required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary ">Valider</button>
                        </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div><!-- end col -->
    </div><!-- end row -->
</div><!-- end col -->
</div>
<!--end row-->


<?php /**PATH /home1/maxisgwd/restaurant.maxisujets.net/resources/views/backend/pages/configuration/unite-de-mesure/create.blade.php ENDPATH**/ ?>