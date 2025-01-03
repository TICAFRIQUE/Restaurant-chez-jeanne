
<?php $__env->startSection('title'); ?>
    
    Admin
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
            Liste des adminisitrateurs
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Administrateurs
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>



    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Liste des administrateurs</h5>
                    <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#myModal">Créer
                        un administrateur</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Prenoms</th>
                                    <th>Telephone</th>
                                    <th>Email</th>
                                    <th>role</th>
                                    <th>Date creation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $data_admin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr id="row_<?php echo e($item['id']); ?>">
                                        <td> <?php echo e(++$key); ?> </td>
                                        <td><?php echo e($item['last_name']); ?></td>
                                        <td><?php echo e($item['first_name']); ?></td>
                                        <td><?php echo e($item['phone']); ?></td>
                                        <td><?php echo e($item['email']); ?></td>
                                        <td><?php echo e($item['roles'][0]['name'] ?? ''); ?></td>
                                        <td> <?php echo e($item['created_at']); ?> </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    
                                                    <li><a type="button" class="dropdown-item edit-item-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#myModalEdit<?php echo e($item['id']); ?>"><i
                                                                class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                            Modifier</a></li>
                                                    <li>
                                                        <a href="#" class="dropdown-item remove-item-btn delete"
                                                            data-id=<?php echo e($item['id']); ?>>
                                                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                            Supprimer
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php echo $__env->make('backend.pages.auth-admin.register.edit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
    <?php echo $__env->make('backend.pages.auth-admin.register.create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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

            var route = "register"
            delete_row(route);
        })
        // $(document).ready(function() {
        //     $('.delete').on("click", function(e) {
        //         e.preventDefault();
        //         var Id = $(this).attr('data-id');
        //         Swal.fire({
        //             title: 'Are you sure?',
        //             text: "You won't be able to revert this!",
        //             icon: 'warning',
        //             showCancelButton: true,
        //             confirmButtonText: 'Yes, delete it!',
        //             cancelButtonText: 'No, cancel!',
        //             customClass: {
        //                 confirmButton: 'btn btn-primary w-xs me-2 mt-2',
        //                 cancelButton: 'btn btn-danger w-xs mt-2',
        //             },
        //             buttonsStyling: false,
        //             showCloseButton: true
        //         }).then((result) => {
        //             if (result.isConfirmed) {
        //                 $.ajax({
        //                     type: "GET",
        //                     url: "/register/delete/" + Id,
        //                     dataType: "json",
        //                     // data: {
        //                     //     _token: '<?php echo e(csrf_token()); ?>',

        //                     // },
        //                     success: function(response) {
        //                         if (response.status == 200) {
        //                             Swal.fire({
        //                                 title: 'Deleted!',
        //                                 text: 'Your file has been deleted.',
        //                                 icon: 'success',
        //                                 customClass: {
        //                                     confirmButton: 'btn btn-primary w-xs mt-2',
        //                                 },
        //                                 buttonsStyling: false
        //                             })

        //                             $('#row_' + Id).remove();
        //                         }
        //                     }
        //                 });
        //             }
        //         });
        //     });
        // });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\restaurant\resources\views/backend/pages/auth-admin/register/index.blade.php ENDPATH**/ ?>