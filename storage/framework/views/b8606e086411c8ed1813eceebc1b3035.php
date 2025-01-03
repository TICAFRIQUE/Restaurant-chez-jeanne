<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0 font-size-18">
               <?php if($previousUrl = url()->previous() ): ?>
               <a href="<?php echo e($previousUrl); ?>" class="btn btn-primary" id="goBack"> <i class="ri ri-arrow-left-fill"></i> Retour</a>
               <?php endif; ?>
                <?php echo e($title); ?>

            </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo e($li_1); ?></a></li>
                    <?php if(isset($title)): ?>
                        <li class="breadcrumb-item active"><?php echo e($title); ?></li>
                    <?php endif; ?>
                </ol>
            </div>
        </div>
    </div>
</div>
<script>
    // go to back
    document.getElementById('goBack').addEventListener('click', function() {
        window.history.back();
        setTimeout(function() {
            location.reload();
        }, 500);
    });
</script>
<!-- end page title -->
<?php /**PATH C:\laragon\www\restaurant\resources\views/backend/components/breadcrumb.blade.php ENDPATH**/ ?>