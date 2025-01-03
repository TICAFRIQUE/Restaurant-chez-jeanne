

<?php $__env->startSection('title', 'Caisse'); ?>


<?php $__env->startSection('content'); ?>
    <!-- checkout-area start -->
    <div class="checkout-area pb-80 pt-100">
        <div class="container">
            <div class="row">

                <div class="col-lg-8 m-auto">
                    <div class="checkout-wrapper">
                        <div id="faq" class="panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h5 class="panel-title"><span>1.</span> <a data-bs-toggle="collapse"
                                            data-bs-target="#payment-1">Informations</a></h5>
                                </div>
                                <div id="payment-1" class="panel-collapse collapse show" data-bs-parent="#faq">
                                    <div class="panel-body">
                                        <div class="row">

                                            <div class="col-lg-12">
                                                <div class="checkout-login">
                                                    <div class="title-wrap mb-2">
                                                        <h4 class="cart-bottom-title section-bg-white">Infos personnelles
                                                        </h4>
                                                    </div>

                                                    <span>Nom: <?php echo e(Auth::user()->first_name); ?> </span>
                                                    <span>Prenoms: <?php echo e(Auth::user()->last_name); ?> </span>
                                                    <span>Contact: <?php echo e(Auth::user()->phone); ?> </span>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="checkout-register mt-3">
                                                    <div class="title-wrap">
                                                        <h4 class="cart-bottom-title section-bg-white">Mode de livraison
                                                        </h4>
                                                    </div>
                                                    <div class="register-us">
                                                        <div class="total-shipping">
                                                            <h5>Livraison <small class="text-danger"> * Cocher une
                                                                    option</small> </h5>

                                                            <div class="mt-3 adressYango">
                                                                <label for="" class="form-label">Lieu de
                                                                    livraison</label>
                                                                <input type="text" id="adresseLivraison"
                                                                    name="adresse_livraison" class="form-control"
                                                                    placeholder="cocody saint jeane"
                                                                    aria-describedby="helpId" />
                                                            </div>
                                                            <ul class="list-unstyled">
                                                                <li>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="optionLivraison" value="yango"
                                                                            id="yango">
                                                                        <label class="form-check-label" for="yango">
                                                                            Yango Moto
                                                                        </label>
                                                                    </div>


                                                                </li>


                                                                <li>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="optionLivraison" value="recuperer"
                                                                            id="recuperer">
                                                                        <label class="form-check-label" for="recuperer">
                                                                            Je passe récupérer
                                                                        </label>
                                                                    </div>
                                                                </li>
                                                            </ul>


                                                        </div>
                                                    </div>


                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="checkout-register">
                                                    <div class="title-wrap mb-3">
                                                        <h4 class="cart-bottom-title section-bg-white">Mode de paiement
                                                        </h4>
                                                    </div>
                                                    <div class="billing-select">
                                                        <select name="payment_mode" id="paymentMode">
                                                            <option selected disabled value="">Selectionner</option>
                                                            <option value="orange money">Orange Money</option>
                                                            <option value="moov money">Moov Money</option>
                                                            <option value="mtn money">MTN Money</option>
                                                            <option value="wave">Wave</option>
                                                            <option value="visa">Visa</option>
                                                            <option value="mastercard">MasterCard</option>
                                                            <option value="espece">Espèce</option>
                                                        </select>
                                                    </div>



                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h5 class="panel-title"><span>2.</span> <a data-bs-toggle="collapse"
                                            data-bs-target="#payment-6">Facture de la commande</a></h5>
                                </div>
                                <?php if(session('cart')): ?>
                                <h5 class="text-center">Produit Ordinaire</h5>
                                    <div id="payment-6" class="panel-collapse collapse show" data-bs-parent="#faq">
                                        <div class="panel-body">
                                            <div class="order-review-wrapper">
                                                <div class="order-review">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>

                                                                <tr>
                                                                    <th class="width-1">Nom </th>
                                                                    <th class="width-2">Pu</th>
                                                                    <th class="width-3">Qté</th>
                                                                    <th class="width-4">sous-total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $__currentLoopData = session('cart'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="o-pro-dec">
                                                                                <p><?php echo e($details['title']); ?></p>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="o-pro-price">
                                                                                <p><?php echo e($details['price']); ?></p>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="o-pro-qty">
                                                                                <p><?php echo e($details['quantity']); ?></p>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="o-pro-subtotal">
                                                                                <p><?php echo e(number_format($details['price'] * $details['quantity'], 0, ',', ' ')); ?>

                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </tbody>
                                                            <tfoot>
                                                                
                                                                <tr>
                                                                    <td colspan="3">Total</td>
                                                                    <td colspan="1" class=" fw-bold">
                                                                        <?php echo e(number_format(session('totalPrice'), 0, ',', ' ')); ?>

                                                                        FCFA</td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>


                                <!-- ========== Start commande Menu ========== -->

                                <?php if(session('cartMenu')): ?>
                                <h5 class="text-center">Produit Menu</h5>
                                    <div id="payment-6" class="panel-collapse collapse show" data-bs-parent="#faq">
                                        <div class="panel-body">
                                            <div class="order-review-wrapper">
                                                <div class="order-review">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>

                                                                <tr>
                                                                    <th class="width-1">Nom </th>
                                                                    <th class="width-2">Pu</th>
                                                                    <th class="width-3">Qté</th>
                                                                    <th class="width-4">sous-total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $__currentLoopData = session('cartMenu'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cartKey => $detailsMenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="o-pro-dec">
                                                                                <p class="fw-bold text-capitalize"><?php echo e($detailsMenu['plat_name']); ?></p>
                                                                                <?php if($detailsMenu['complements']): ?>
                                                                                    <small class="fw-bold ms-2">Compléments:</small>
                                                                                    <ul class="ms-3">
                                                                                        <?php $__currentLoopData = $detailsMenu['complements']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $complement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <li class="text-capitalize ms-3"><?php echo e($complement['nom']); ?> (Qté: <?php echo e($complement['quantity']); ?>)</li>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                    </ul>
                                                                                <?php endif; ?>
                                                                                <?php if($detailsMenu['garnitures']): ?>
                                                                                    <small class="fw-bold">Garnitures:</small>
                                                                                    <ul class="ms-3">
                                                                                        <?php $__currentLoopData = $detailsMenu['garnitures']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $garniture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <li class="text-capitalize ms-3"><?php echo e($garniture['nom']); ?> (Qté: <?php echo e($garniture['quantity']); ?>)</li>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                    </ul>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="o-pro-price">
                                                                                <p><?php echo e($detailsMenu['price']); ?></p>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="o-pro-qty">
                                                                                <p><?php echo e($detailsMenu['quantity']); ?></p>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="o-pro-subtotal">
                                                                                <p><?php echo e(number_format($detailsMenu['price'] * $detailsMenu['quantity'], 0, ',', ' ')); ?>

                                                                                </p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </tbody>
                                                            <tfoot>
                                                                
                                                                <tr>
                                                                    <td colspan="3">Total</td>
                                                                    <td colspan="1" class=" fw-bold">
                                                                        <?php echo e(number_format(session('totalPriceMenu'), 0, ',', ' ')); ?>

                                                                        FCFA</td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!-- ========== End commande Menu ========== -->

                            </div>

                            <div class="billing-btn d-flex justify-content-between">
                                <?php if($urlPrevious = url()->previous()): ?>
                                    <a href="<?php echo e($urlPrevious); ?>">Retour à la page précédente</a>
                                <?php endif; ?>
                                <button id="btnSend" style="background-color:#015701d6 ; color:white">Valider ma
                                    commande</button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>


    <link href="
    https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css
    " rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script>
        // afficher le champs adresse de livraison yango si yango est coché
        $('.adressYango').hide();
        $('input[name="optionLivraison"]').change(function(e) {
            e.preventDefault();
            var livraisonValue = $('input[name="optionLivraison"]:checked').val();
            if (livraisonValue == "yango") {
                $('.adressYango').show(300);
            } else {
                $('.adressYango').hide(300);
                $('#adresseLivraison').val('');
            }
        });

        // envoi des informations au controllers  pour enregistrer la commande


        $('#btnSend').click(function(e) {
            e.preventDefault();

            // on verifie un mode de paiement à éte selectionné
            var paiementMode = $('#paymentMode').val();
            //on verifie si une option de livraison à éte selectionné
            var livraisonValue = $('input[name="optionLivraison"]:checked').val();
            var yangoValue = $('#adresseLivraison').val(); // adresse de livraison yango

            if (livraisonValue == null || livraisonValue == '') {
                Swal.fire({
                    title: 'Ouff',
                    text: 'Veuillez selectionner une option de livraison',
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            } else if ($('.adressYango').is(':visible') && yangoValue == '') {
                Swal.fire({
                    title: 'Ouff',
                    text: 'Veuillez entrer le lieu de livraison pour yango',
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            } else if (paiementMode == null || paiementMode == '') {
                Swal.fire({
                    title: 'Ouff',
                    text: 'Veuillez selectionner un mode de paiement',
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            } else {
                //on envoi les informations au controller pour enregistrer la commande
                $.ajax({
                    url: "<?php echo e(route('cart.save-order')); ?>",
                    method: "POST",
                    data: {
                        _token: "<?php echo e(csrf_token()); ?>",
                        optionLivraison: livraisonValue, //option livraison
                        adresseLivraison: yangoValue,
                        paiementMode: paiementMode
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            Swal.fire({
                                title: 'Félicitation',
                                text: 'Votre commande a été enregistrée avec succès',
                                icon: 'success',
                                showConfirmButton: false, // Masquer le bouton de confirmation
                                timer: 3000, // L'alerte disparaît après delai ms (en secondes)
                                timerProgressBar: true // Affiche une barre de progression pour le timer
                            }).then(() => {
                                // Redirection après la fermeture de l'alerte
                                setTimeout(function() {
                                        window.location.href = "<?php echo e(route('accueil')); ?>";
                                    },
                                    300
                                ); // On ajoute un léger délai pour garantir la redirection après la fermeture
                            });;
                        }
                    },

                });
            }
        });
    </script>




<?php $__env->stopSection(); ?>

<?php echo $__env->make('site.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\restaurant\resources\views/site/pages/caisse.blade.php ENDPATH**/ ?>