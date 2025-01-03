<div class="header-bottom transparent-bar black-bg">
    <div class="container">
        <div class="row align-items-center">
            
            <div class="col-lg-12 col-md-12 col-sm-6 m-auto">
                <div class="main-menu">
                    <nav>
                        <ul class="d-flex justify-content-center">
                            <li><a href="<?php echo e(route('accueil')); ?>">Accueil</a></li>
                            <li><a href="<?php echo e(route('menu')); ?>">Menu du jour</a></li>
                            <?php $__currentLoopData = $menu_link; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><a href="<?php echo e(route('produit', $menu->id)); ?>">
                                        <?php if($menu->slug === 'bar'): ?>
                                            Nos boissons
                                        <?php elseif($menu->slug === 'cuisine-interne'): ?>
                                            Restaurant
                                        <?php else: ?>
                                            <?php echo e($menu->name); ?>

                                        <?php endif; ?>
                                    </a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <li><a href="<?php echo e(route('nous-contactez')); ?>">Nous contacter</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\restaurant\resources\views/site/layouts/menu_desktop/menu.blade.php ENDPATH**/ ?>