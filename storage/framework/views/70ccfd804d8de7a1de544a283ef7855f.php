

<?php $__env->startSection('title', 'Produit detail /' . $produit->nom); ?>

<?php $__env->startSection('content'); ?>

    <style>
        .addCart {
            display: flex;
            /* Pour aligner l'icône et le texte horizontalement */
            align-items: center;
            font-size: 16px;
            color: #333;
            /* Ajuste la couleur selon tes préférences */
            text-decoration: none;
        }

        .addCart i {
            margin-right: 8px;
            /* Espace entre l'icône et le texte */
            font-size: 20px;
            /* Taille de l'icône */
        }

        .addCart:hover {
            color: #ff0000;
            /* Couleur au survol */
        }


        .product-img img {
            width: 100%;
            /* Adapter à la largeur du conteneur */
            height: 250px;
            /* Fixer une hauteur spécifique */
            object-fit: contain;
            /* Maintenir les proportions tout en remplissant la zone */
        }

        .category-sticker {
            position: absolute;
            top: 10px;
            /* Ajuster la position verticale */
            left: 10px;
            /* Ajuster la position horizontale */
            background-color: rgba(0, 0, 0, 0.7);
            /* Fond semi-transparent */
            color: white;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 5px;
            z-index: 10;
        }


        .produit-image-container {
            position: relative;
            display: inline-block;
        }

        .produit-image-container img {
            width: 100%;
            /* Ajuste la taille selon tes besoins */
        }

        .rupture-stock-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 0, 0, 0.7);
            /* Fond rouge avec opacité */
            color: white;
            padding: 10px 20px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            border-radius: 5px;
        }

        .product-content {
            text-align: center;
            text-transform: uppercase;
        }

        .product-price-wrapper span {
            font-weight: bold;
            color: rgba(255, 0, 0, 0.641)
        }
    </style>

    <div class="product-details pt-50 pb-90">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="shop-sidebar-wrapper gray-bg-7 shop-sidebar-mrg">
                        <div class="shop-widget">
                            <h4 class="shop-sidebar-title">MENU <i class="fa fa-angle-right"></i>
                                <small><?php echo e($categorieSelect->name); ?> </small></h4>
                            <div class="shop-catigory">
                                

                                <?php echo $__env->make('site.sections.categorie.categorieproduit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12">
                    <div class="product-images-slider position-relative">
                        <div class="main-image-container ratio ratio-4x3">
                            <img id="mainImage" src="<?php echo e($produit->getFirstMediaUrl('ProduitImage')); ?>"
                                alt="Image principale" class="product-image img-fluid w-100 h-100 object-fit-cover">
                        </div>

                        <div class="thumbnail-slider mt-3">
                            <div class="thumbnails-container d-flex flex-wrap justify-content-start">
                                <div class="thumbnail-wrapper ratio ratio-1x1 m-1" style="width: 80px;">
                                    <img src="<?php echo e($produit->getFirstMediaUrl('ProduitImage')); ?>" alt="Image principale"
                                        class="thumbnail active img-fluid w-100 h-100 object-fit-cover"
                                        onclick="changeImage('<?php echo e($produit->getFirstMediaUrl('ProduitImage')); ?>')">
                                </div>

                                <?php $__currentLoopData = $produit->getMedia('galleryProduit'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="thumbnail-wrapper ratio ratio-1x1 m-1" style="width: 80px;">
                                        <img src="<?php echo e($media->getUrl()); ?>" alt="<?php echo e($media->name); ?>"
                                            class="thumbnail img-fluid w-100 h-100 object-fit-cover"
                                            onclick="changeImage('<?php echo e($media->getUrl()); ?>')">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="product-details-content">
                        <h4 class="text-uppercase"><?php echo e($produit->nom); ?> </h4>

                        <span id="price" data-price=<?php echo e($produit->prix); ?>>
                            <?php echo e(number_format($produit->prix, 0, ',', ' ')); ?>

                            FCFA </span>

                        

                        <p> <?php echo $produit->description; ?> </p>

                        <?php if($produit->stock == 0 && $produit->categorie->famille == 'bar'): ?>
                            <span class="text-danger fw-bold">Rupture de stock</span>
                        <?php else: ?>
                            <div class="pro-details-cart-wrap d-flex">
                                <div class="product-quantity">
                                    <div class="cart-plus-minus">
                                        <input id="quantity" class="cart-plus-minus-box" type="text" name="quantity"
                                            value="1" readonly>
                                    </div>
                                </div>

                                <div class="mx-3">
                                    <button type="button" class="btn btn-danger addCart text-white"
                                        data-id="<?php echo e($produit->id); ?>"
                                        style="padding-top:18px; padding-bottom:20px ; border-radius: 10px">
                                        Ajouter au panier
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="pro-dec-categories">
                            <ul>
                                <li class="categories-title">Categories:</li>
                                <li><a href="#"><?php echo e($produit->categorie->getPrincipalCategory()->name); ?> ,</a></li>
                                <li><a href="#"> <?php echo e($produit->categorie->name); ?> </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php if($produit->description): ?>
        <div class="description-review-area pb-100">
            <div class="container">
                <div class="description-review-wrapper">
                    <div class="description-review-topbar nav text-center">
                        <a class="active" data-bs-toggle="tab" href="#des-details1">Description</a>
                        
                    </div>
                    <div class="tab-content description-review-bottom">
                        <div id="des-details1" class="tab-pane active">
                            <div class="product-description-wrapper">
                                <?php echo $produit->description; ?>

                            </div>
                        </div>
                        <div id="des-details2" class="tab-pane">
                            <div class="product-anotherinfo-wrapper">
                                <ul>
                                    <li><span>Tags:</span></li>
                                    <li><a href="#"> All,</a></li>
                                    <li><a href="#"> Cheesy,</a></li>
                                    <li><a href="#"> Fast Food,</a></li>
                                    <li><a href="#"> French Fries,</a></li>
                                    <li><a href="#"> Hamburger,</a></li>
                                    <li><a href="#"> Pizza</a></li>
                                </ul>
                            </div>
                        </div>
                        <div id="des-details3" class="tab-pane">
                            <div class="rattings-wrapper">
                                <div class="sin-rattings">
                                    <div class="star-author-all">
                                        <div class="ratting-star f-left">
                                            <i class="ion-star theme-color"></i>
                                            <i class="ion-star theme-color"></i>
                                            <i class="ion-star theme-color"></i>
                                            <i class="ion-star theme-color"></i>
                                            <i class="ion-star theme-color"></i>
                                            <span>(5)</span>
                                        </div>
                                        <div class="ratting-author f-right">
                                            <h3>tayeb rayed</h3>
                                            <span>12:24</span>
                                            <span>9 March 2022</span>
                                        </div>
                                    </div>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                        incididunt ut labore et dolore magna aliqua. Utenim ad minim veniam, quis nost rud
                                        exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Lorem ipsum
                                        dolor
                                        sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                                        et
                                        dolore magna aliqua. Utenim ad minim veniam, quis nost.</p>
                                </div>
                                <div class="sin-rattings">
                                    <div class="star-author-all">
                                        <div class="ratting-star f-left">
                                            <i class="ion-star theme-color"></i>
                                            <i class="ion-star theme-color"></i>
                                            <i class="ion-star theme-color"></i>
                                            <i class="ion-star theme-color"></i>
                                            <i class="ion-star theme-color"></i>
                                            <span>(5)</span>
                                        </div>
                                        <div class="ratting-author f-right">
                                            <h3>farhana shuvo</h3>
                                            <span>12:24</span>
                                            <span>9 March 2022</span>
                                        </div>
                                    </div>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                        incididunt ut labore et dolore magna aliqua. Utenim ad minim veniam, quis nost rud
                                        exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Lorem ipsum
                                        dolor
                                        sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                                        et
                                        dolore magna aliqua. Utenim ad minim veniam, quis nost.</p>
                                </div>
                            </div>
                            <div class="ratting-form-wrapper">
                                <h3>Add your Comments :</h3>
                                <div class="ratting-form">
                                    <form action="#">
                                        <div class="star-box">
                                            <h2>Rating:</h2>
                                            <div class="ratting-star">
                                                <i class="ion-star theme-color"></i>
                                                <i class="ion-star theme-color"></i>
                                                <i class="ion-star theme-color"></i>
                                                <i class="ion-star theme-color"></i>
                                                <i class="ion-star"></i>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="rating-form-style mb-20">
                                                    <input placeholder="Name" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="rating-form-style mb-20">
                                                    <input placeholder="Email" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="rating-form-style form-submit">
                                                    <textarea name="message" placeholder="Message"></textarea>
                                                    <input type="submit" value="add review">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="product-area pb-95">
        <?php if($produitsRelateds->count() > 0): ?>

            <div class="container">
                <div class="product-top-bar section-border mb-25">
                    <div class="section-title-wrap">
                        <h3 class="section-title section-bg-white">Produits similaires</h3>
                    </div>
                </div>
                <div class="related-product-active owl-carousel product-nav">
                    <?php $__currentLoopData = $produitsRelateds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="product-wrapper">
                            <div class="product-img">
                                <a href="<?php echo e(route('produit.detail', $produit->slug)); ?>">
                                    <div class="produit-image-container">
                                        <img src="<?php echo e($produit->getFirstMediaUrl('ProduitImage')); ?>"
                                            alt="<?php echo e($produit->nom); ?>">

                                        <?php if($produit->stock == 0 && $produit->categorie->famille == 'bar'): ?>
                                            <div class="rupture-stock-overlay">Rupture de stock</div>
                                        <?php endif; ?>
                                    </div>
                                </a>

                            </div>
                            <div class="product-content text-center">
                                <h4>
                                    <a href="#"> <?php echo e($produit->nom); ?> </a>
                                </h4>
                                <div class="product-price-wrapper">
                                    <span><?php echo e(number_format($produit->prix, 0, ',', ' ')); ?> FCFA</span>
                                    
                                </div>

                                <?php if($produit->stock == 0 && $produit->categorie->famille == 'bar'): ?>
                                    <span><span style="color: red" class="text-danger">Produit en rupture</span>
                                    <?php else: ?>
                                        <div class="mt-3 text-center" style="display: flex; justify-content: center">
                                            <button type="button" class="btn btn-danger addCart text-white"
                                                data-id="<?php echo e($produit->id); ?>" style="border-radius: 10px">
                                                <i class="fa fa-shopping-cart"></i> Commander
                                            </button>
                                        </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>
        <?php endif; ?>

    </div>



    <?php echo $__env->make('site.components.ajouter-au-panier', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script>
        var CartPlusMinus = $('.cart-plus-minus');

        // Ajouter les boutons - et +
        CartPlusMinus.prepend('<div class="dec qtybutton">-</div>');
        CartPlusMinus.append('<div class="inc qtybutton">+</div>');

        // Gestion de l'incrémentation et de la décrémentation
        $(".qtybutton").on("click", function() {
            var $button = $(this);
            var $input = $button.parent().find("input");
            var oldValue = parseFloat($input.val());
            var maxQuantity =
                <?php echo e($produit->achats->isNotEmpty() ? $produit->stock : 0); ?>; // S'assurer que maxQuantity est bien défini

            // Incrémentation
            if ($button.text() === "+") {
                var newVal = oldValue + 1;
                if ('<?php echo e($produit->categorie->famille); ?>' === 'bar') {
                    if (newVal > maxQuantity) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Attention',
                            text: 'La quantité demandée dépasse le stock disponible.',
                            confirmButtonText: 'OK'
                        });
                        newVal = maxQuantity;
                        $('.addCart').prop('disabled', true); // Désactiver le bouton si max atteint
                    } else {
                        $('.addCart').prop('disabled', false); // Activer le bouton si la quantité est valide
                    }
                }
            }
            // Décrémentation
            else {
                if (oldValue > 1) {
                    var newVal = oldValue - 1;
                } else {
                    newVal = 1; // Ne pas aller en dessous de 1
                }
                $('.addCart').prop('disabled', false); // Activer le bouton si la quantité est valide
            }

            // Mettre à jour la valeur de l'input
            $input.val(newVal);
        });



        $(document).ready(function() {
            $(".thumbnail-carousel").owlCarousel({
                items: 4, // Nombre de miniatures visibles
                margin: 10,
                loop: true,
                nav: false,
                dots: false,
                responsive: {
                    0: {
                        items: 2
                    },
                    600: {
                        items: 3
                    },
                    1000: {
                        items: 4
                    }
                }
            });
        });

        function changeImage(imageSrc) {
            document.getElementById("mainImage").src = imageSrc;
        }
    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('site.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\restaurant\resources\views/site/pages/produit-detail.blade.php ENDPATH**/ ?>