<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.datatables'); ?>
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
            Liste des achats
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Achat
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Liste des achats de la facture <strong>#<?php echo e($facture->numero_facture); ?></strong> </h5>
                    <a href="<?php echo e(route('achat.create')); ?>" type="button" class="btn btn-primary ">Faire
                        un achat</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Statut</th>
                                    <th>Produit</th>
                                    <th>code</th>
                                    <th>Magasin</th>
                                    <th>N°Facture</th>
                                    <th>fournisseur</th>
                                    <th>Format</th>
                                    <th>Qté format</th>
                                    <th>Qté dans format</th>
                                    <th>PU format</th>
                                    <th>Total depensé</th>
                                    <th>Qté stockée</th>
                                    <th>PU achat</th>
                                    <th>PU vente</th>
                                    <th>Unite de vente</th>
                                    <th>Crée par</th>
                                    <th>Date achat</th>
                                    <th class="d-none">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $data_achat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr id="row_<?php echo e($item['id']); ?>">
                                        <td> <?php echo e(++$key); ?> </td>
                                        <td><?php echo e($item['statut']); ?></td>

                                        <td>
                                            <img class="rounded-circle"
                                                src="<?php echo e($item->produit->getFirstMediaUrl('ProduitImage')); ?>" width="50px"
                                                alt="">

                                            <?php echo e($item['produit']['nom']); ?>

                                        </td>
                                        <td><?php echo e($item['code']); ?></td>
                                        <td><?php echo e($item['magasin']['libelle'] ?? 'N/A'); ?></td>
                                        <td><?php echo e($item['numero_facture'] ?? 'N/A'); ?></td>
                                        <td><?php echo e($item['fournisseur']['nom'] ?? 'N/A'); ?></td>
                                        <td><?php echo e($item['format']['libelle'] ?? 'N/A'); ?></td>
                                        <td> <?php echo e($item['quantite_format']); ?> </td>
                                        <td> <?php echo e($item['quantite_in_format']); ?> </td>
                                        <td> <?php echo e($item['prix_unitaire_format']); ?> </td>
                                        <td> <?php echo e($item['prix_total_format']); ?> </td>
                                        <td> <?php echo e($item['quantite_stocke']); ?> </td>
                                        <td> <?php echo e($item['prix_achat_unitaire']); ?> </td>
                                        <td> <?php echo e($item['prix_vente_unitaire']); ?> </td>
                                        <td> <?php echo e($item['unite']['libelle'] ?? 'N/A'); ?> </td>
                                        <td> <?php echo e($item['user']['first_name']); ?> </td>
                                        <td> <?php echo e($item['date_achat']); ?> </td>
                                        <td class="d-none">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a href="<?php echo e(route('ajustement.create', $item['id'])); ?>"
                                                            class="dropdown-item"><i
                                                                class=" ri-exchange-fill align-bottom me-2 text-muted"></i>
                                                            Ajustement</a>
                                                    </li>
                                                    <li><a href="#!" class="dropdown-item"><i
                                                                class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                            View</a>
                                                    </li>
                                                    <li><a href="<?php echo e(route('achat.edit', $item['id'])); ?>" type="button"
                                                            class="dropdown-item edit-item-btn"><i
                                                                class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                            Edit</a></li>
                                                    <li>
                                                        <a href="#" class="dropdown-item remove-item-btn delete"
                                                            data-id=<?php echo e($item['id']); ?>>
                                                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                            Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->

    
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
        $(document).ready(function() {
            var route = "depense"
            delete_row(route);
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/maxisgwd/restaurant.maxisujets.net/resources/views/backend/pages/stock/achat/index.blade.php ENDPATH**/ ?>