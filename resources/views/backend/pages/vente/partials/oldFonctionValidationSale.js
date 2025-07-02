
            // Fonction pour valider la vente
            // function validateSale() {
            //     //recuperer le bouton de soumission
            //     let submitButton = $(this);

            //     // Ajouter le spinner et désactiver le bouton
            //     submitButton.prop('disabled', true).html(`
        //         <span class="d-flex align-items-center">
        //             <span class="spinner-border flex-shrink-0" role="status">
        //                 <span class="visually-hidden">Loading...</span>
        //             </span>
        //             <span class="flex-grow-1 ms-2">Enregistrement en cours...</span>
        //         </span>
        //     `);



            //     const plats = document.querySelectorAll('.plat-checkbox:checked');
            //     let panier = []; // panier vente menu

            //     let validationEchouee = false;



            //     plats.forEach((plat) => {
            //         const platId = plat.value;
            //         const platNom = plat.nextElementSibling.textContent.trim();
            //         const platQuantite = parseInt(plat.closest('.form-check').querySelector(
            //                 '.quantityPlat')
            //             .value);
            //         const prixPlat = plat.getAttribute('data-price');

            //         const complements = [];
            //         const garnitures = [];
            //         let complementManquant = false;
            //         let garnitureManquante = false;

            //         // Compléments
            //         const complementCheckboxes = plat.closest('.card-body').querySelectorAll(
            //             '.complement-checkbox');
            //         let totalQuantiteComplements = 0;
            //         complementCheckboxes.forEach((complement) => {
            //             if (complement.checked) {
            //                 const quantite = parseInt(complement.closest('.form-check')
            //                     .querySelector(
            //                         '.quantityComplement').value);
            //                 totalQuantiteComplements += quantite;
            //                 complements.push({
            //                     id: complement.value,
            //                     nom: complement.nextElementSibling.textContent
            //                         .trim(),
            //                     quantity: quantite,
            //                 });
            //             }
            //         });

            //         if (complementCheckboxes.length > 0 && complements.length === 0) {
            //             complementManquant = true;
            //         }

            //         // Garnitures
            //         const garnitureCheckboxes = plat.closest('.card-body').querySelectorAll(
            //             '.garniture-checkbox');
            //         let totalQuantiteGarnitures = 0;
            //         garnitureCheckboxes.forEach((garniture) => {
            //             if (garniture.checked) {
            //                 const quantite = parseInt(garniture.closest('.form-check')
            //                     .querySelector(
            //                         '.quantityGarniture').value);
            //                 totalQuantiteGarnitures += quantite;
            //                 garnitures.push({
            //                     id: garniture.value,
            //                     nom: garniture.nextElementSibling.textContent
            //                         .trim(),
            //                     quantity: quantite,
            //                 });
            //             }
            //         });

            //         if (garnitureCheckboxes.length > 0 && garnitures.length === 0) {
            //             garnitureManquante = true;
            //         }

            //         // Vérification des compléments et garnitures manquants
            //         if (complementManquant || garnitureManquante) {
            //             validationEchouee = true;
            //             const message = complementManquant ?
            //                 'Veuillez sélectionner au moins un complément pour le plat : ' +
            //                 platNom :
            //                 'Veuillez sélectionner au moins une garniture pour le plat : ' +
            //                 platNom;

            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Attention',
            //                 text: message,
            //             });
            //             return;
            //         }

            //         // Vérification des quantités des compléments et garnitures
            //         if (complements.length > 0 && totalQuantiteComplements !== platQuantite) {
            //             validationEchouee = true;
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Quantité invalide',
            //                 text: `La somme des quantités des compléments doit être égale à ${platQuantite} pour le plat : ${platNom}`,
            //             });
            //             return;
            //         }

            //         if (garnitures.length > 0 && totalQuantiteGarnitures !== platQuantite) {
            //             validationEchouee = true;
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Quantité invalide',
            //                 text: `La somme des quantités des garnitures doit être égale à ${platQuantite} pour le plat : ${platNom}`,
            //             });
            //             return;
            //         }


            //         // Panier du Menu
            //         panier.push({
            //             plat: {
            //                 id: platId,
            //                 nom: platNom,
            //                 quantity: platQuantite,
            //                 price: prixPlat
            //             },
            //             complements,
            //             garnitures,

            //         });
            //     });

            //     // Parcourir le tableau si une varianteSelected est null envoyer un message d'erreur
            //     cart.forEach((item) => {
            //         //recuperer la famille du produit
            //         let data = dataProduct.find(dataItem => dataItem.id == item.id)
            //         let famille = data.categorie.famille;
            //         let name = data.nom;
            //         if (item.selectedVariante === null && famille === 'bar') {
            //             validationEchouee = true;
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Attention',
            //                 text: 'Veuillez choisir une variante  pour ' + name,
            //             });
            //             return;
            //         }
            //     });



            //     if (validationEchouee) {
            //         submitButton.prop('disabled', false).html('Valider la vente');
            //         return; // Stopper l'exécution si une validation échoue
            //     }


            //     let montantVenteOrdinaire = parseFloat($('#grand-total').text() ||
            //         0); // montant  de vente ordinaire
            //     let montantVenteMenu = parseFloat($('#totalAmount').text() || 0); // montant  de vente menu
            //     let montantNet = parseFloat($('#totalNet').text() || 0); // montant  de vente menu
            //     let montantApresRemise = parseFloat($('#total-after-discount').text() ||
            //         0); // total apres remise
            //     let montantRemise = parseFloat($('#discount-amount').text() || 0);
            //     let typeRemise = $('#discount-type').val();
            //     let valeurRemise = $('#total-discount').val();
            //     let modePaiement = $('#payment-method').val();
            //     let montantRecu = parseFloat($('#received-amount').val() || 0);
            //     let montantRendu = parseFloat($('#change-amount').text() || 0);
            //     let numeroDeTable = $('#table-number').val();
            //     let nombreDeCouverts = $('#number-covers').val();

            //     if (cart.length === 0 && panier.length === 0) {
            //         Swal.fire({
            //             title: 'Erreur',
            //             text: 'Vous devez ajouter au moins un produit au panier.',
            //             icon: 'error',
            //         });

            //         // Restaurer le bouton de soumission et arreter le spinner
            //         submitButton.prop('disabled', false).html('Valider la vente');

            //         return;
            //     }

            //     // verifier si le montant recu est inferieur au montant apres remise

            //     // if (montantRecu < montantApresRemise) {
            //     //     Swal.fire({
            //     //         title: 'Erreur',
            //     //         text: 'Le montant reçu est inférieur au montant à payer.',
            //     //         icon: 'error',
            //     //     });

            //     //     // Restaurer le bouton de soumission et arreter le spinner
            //     //     submitButton.prop('disabled', false).html('Valider la vente');
            //     //     return;
            //     // }

            //     $.ajax({
            //         url: '{{ route('vente.store') }}',
            //         type: 'POST',
            //         data: {
            //             cart: cart,
            //             cartMenu: panier,
            //             montantVenteOrdinaire: montantVenteOrdinaire,
            //             montantVenteMenu: montantVenteOrdinaire,
            //             montantAvantRemise: montantNet,
            //             montantApresRemise: montantApresRemise,
            //             montantRemise: montantRemise,
            //             typeRemise: typeRemise,
            //             valeurRemise: valeurRemise,
            //             modePaiement: modePaiement,
            //             montantRecu: montantRecu,
            //             montantRendu: montantRendu,
            //             numeroDeTable: numeroDeTable,
            //             nombreDeCouverts: nombreDeCouverts,
            //             _token: '{{ csrf_token() }}'
            //         },
            //         success: function(response) {
            //             Swal.fire({
            //                 title: 'Vente validée avec succès !',
            //                 text: 'Passer au reglement de la vente',
            //                 icon: 'success',
            //                 confirmButtonText: 'Voir les détails de la vente', // 👈 change "OK" en "Fermer"

            //             }).then(() => {
            //                 // Réinitialiser le panier après la vente réussie
            //                 cart = []; // Réinitialiser le panier après validation
            //                 updateCartTable();
            //                 updateGrandTotal();
            //                 $('#received-amount').val(0); // Réinitialiser les champs
            //                 $('#table-number').val('');
            //                 $('#number-covers').val(1);

            //                 window.location.href =
            //                     '{{ route('vente.show', ':idVente') }}'
            //                     .replace(':idVente', response.idVente);

            //                 // Restaurer le bouton de soumission et arreter le spinner
            //                 submitButton.prop('disabled', false).html(
            //                     'Valider la vente');
            //             });
            //         },
            //         error: function(xhr) {
            //             Swal.fire({
            //                 title: 'Erreur',
            //                 text: xhr.responseJSON.message ||
            //                     'Une erreur s\'est produite lors de la validation de la vente.',
            //                 icon: 'error',
            //             });

            //             // Restaurer le bouton de soumission et arreter le spinner
            //             submitButton.prop('disabled', false).html('Valider la vente');
            //         }
            //     });
            // }

            // $('#validate-sale').click(function(e) {
            //     // avertir que vous etes sur le point de valider la vente
            //     e.preventDefault(); // stop the form from submitting
            //     Swal.fire({
            //         title: 'Voulez-vous valider la vente ?',
            //         text: "Une vente validée ne peut plus etre modifiée.",
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Oui, valider la vente',
            //         cancelButtonText: 'Annuler'
            //     }).then((result) => {
            //         if (result.value) {
            //             // valider la vente
            //             validateSale();

            //         }
            //     })
            // })

