<div class="header-top bg-danger d-none d-lg-block">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12 col-sm-12">
                <div class="welcome-area">
                    <p class="d-flex justify-content-around">
                     <span>   <i class="ion-ios-restaurant"> </i>  Bienvenue au restaurant CHEZ JEANNE</span>
                      <span> <a class="text-white" href="tel:<?php echo e($setting->phone1 ?? ''); ?>"> <i class="ion-ios-telephone"> </i>  <?php echo e($setting->phone1 ?? ''); ?></a></span>
                       <span> <a class="text-white" href="mailto:<?php echo e($setting->email1 ?? ''); ?>"><i class="ion-ios-email"> </i>  <?php echo e($setting->email1 ?? ''); ?> </a></span>
                        <span> <a href="<?php echo e($setting->google_maps ?? ''); ?>" target="_blank" rel="noopener noreferrer" class="text-white"><i class="ion-ios-location"> </i> <?php echo e($setting->localisation ?? ''); ?></a></span>
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\restaurant\resources\views/site/layouts/topbar1.blade.php ENDPATH**/ ?>