
<?php $__env->startSection('title'); ?>
    Détails de la commande
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('backend.components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Gestion des commandes
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Détails de la commande
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Produits de la commande
                        <strong>#<?php echo e($commande->code); ?> du <?php echo e($commande->date_commande); ?> </strong>
                    </h5>
                    <div class="d-flex justify-content-end mt-3">
                        

                        <button type="button" class="btn btn-info me-2" onclick="imprimerFacture()">
                            <i class="ri-printer-line align-bottom me-1"></i> Imprimer la facture
                        </button>
                        <select class="form-select w-auto" data-commande="<?php echo e($commande->id); ?>" 
                            onchange="changerStatut(this)"
                            <?php echo e($commande->statut == 'livrée' ? 'disabled' : ''); ?>>
                            <option value="">Changer le statut</option>
                            <?php if($commande->statut == 'annulée' || ($commande->statut != 'confirmée' && $commande->statut != 'livrée')): ?>
                                <option value="en attente" <?php echo e($commande->statut == 'en attente' ? 'selected' : ''); ?>>En attente</option>
                                <option value="confirmée" <?php echo e($commande->statut == 'confirmée' ? 'selected' : ''); ?>>Confirmée</option>
                                <option value="livrée" <?php echo e($commande->statut == 'livrée' ? 'selected' : ''); ?>>Livrée</option>
                                <option value="annulée" <?php echo e($commande->statut == 'annulée' ? 'selected' : ''); ?>>Annulée</option>
                            <?php elseif($commande->statut == 'confirmée' || $commande->statut == 'livrée'): ?>
                                <option value="livrée" <?php echo e($commande->statut == 'livrée' ? 'selected' : ''); ?>>Livrée</option>
                                <option value="annulée" <?php echo e($commande->statut == 'annulée' ? 'selected' : ''); ?>>Annulée</option>
                            <?php endif; ?>
                        </select>

                    </div>


                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Nom du produit</th>
                                    <th>Quantité</th>
                                    <th>Prix unitaire</th>
                                    <th>Montant total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $commande->produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr id="row_<?php echo e($item['id']); ?>">
                                        <td><?php echo e(++$key); ?></td>
                                        <td>
                                            <img class="rounded-circle" src="<?php echo e($item->getFirstMediaUrl('ProduitImage')); ?>"
                                                width="50px" alt="">
                                        </td>
                                        <td><?php echo e($item['nom']); ?></td>
                                        <td><?php echo e($item['pivot']['quantite']); ?></td>
                                        <td><?php echo e(number_format($item['pivot']['prix_unitaire'], 0, ',', ' ')); ?> FCFA</td>
                                        <td><?php echo e(number_format($item['pivot']['quantite'] * $item['pivot']['prix_unitaire'], 0, ',', ' ')); ?>

                                            FCFA</td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Total de la commande:</strong></td>
                                    <td><strong><?php echo e(number_format($commande->montant_total, 0, ',', ' ')); ?> FCFA</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="<?php echo e(URL::asset('build/js/pages/datatables.init.js')); ?>"></script>

    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>


    <script>
        function changerStatut(selectElement) {
            // Récupérer la valeur du statut sélectionné et l'ID de la commande
            var statut = $(selectElement).val();
            var commandeId = $(selectElement).data('commande');

            $.ajax({
                url: "<?php echo e(route('commande.statut')); ?>",
                type: 'POST',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>',
                    statut: statut,
                    commandeId: commandeId // Assurez-vous que ce paramètre est utilisé dans la route
                },
                success: function(response) {
                    if (response.success) {
                        // Mettre à jour l'interface utilisateur si nécessaire
                        Swal.fire({
                            title: 'Succès!',
                            text: 'Le statut a été mis à jour avec succès',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        location.reload(); // Recharger la page pour afficher les changements
                    } else {
                        alert('Une erreur est survenue lors de la mise à jour du statut');
                    }
                },
                error: function() {
                    alert('Une erreur est survenue lors de la communication avec le serveur');
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\restaurant\Restaurant-chez-jeanne\resources\views/backend/pages/vente/commande/show.blade.php ENDPATH**/ ?>