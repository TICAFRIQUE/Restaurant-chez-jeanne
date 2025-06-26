<script>
    document.addEventListener("DOMContentLoaded", function() {
        const typeMobileMoney = @json($type_mobile_money);
        const billetData = @json($billets);
        const pieceData = @json($pieces);
        const modes = @json($modes);

        let varianteIndex = 0;

        function updateGrandTotal() {
            let grandTotal = 0;
            document.querySelectorAll('.total-input').forEach(input => {
                grandTotal += parseFloat(input.value) || 0;
            });
            document.getElementById('grand-total').value = grandTotal.toFixed(0);
        }

        document.getElementById('add-monnaie').addEventListener('click', function() {
            const container = document.getElementById('variantes-container');
            const newRow = document.createElement('div');
            newRow.classList.add('row', 'variante-row', 'mb-4');
            newRow.innerHTML = `
                            <div class="col-2">
                                <label for="mode">Mode :</label>
                                <select class="form-select mode-select" name="variantes[${varianteIndex}][mode]" required>
                                    <option selected disabled value="">Sélectionner</option>
                                    ${Object.entries(modes).map(([key, mode]) => `<option value="${key}">${mode}</option>`).join('')}
                                </select>
                            </div>
                            <div class="col-2 espece-fields d-none">
                                <label for="type_monnaie">Type de monnaie :</label>
                                <select class="form-select type-monnaie-select" name="variantes[${varianteIndex}][type_monnaie]" required>
                                    <option selected disabled value="">Sélectionner</option>
                                    <option value="Billets">Billets</option>
                                    <option value="Pièces">Pièces</option>
                                </select>
                            </div>
                            <div class="col-2 espece-fields d-none">
                                <label for="quantite">Quantité :</label>
                                <select class="form-select quantite-select" name="variantes[${varianteIndex}][quantite]" required>
                                    <option selected disabled value="">Sélectionner</option>
                                    ${Array.from({ length: 500 }, (_, i) => `<option value="${i + 1}">${i + 1}</option>`).join('')}
                                </select>
                            </div>
                            <div class="col-2 espece-fields d-none">
                                <label for="valeur">Valeur: </label>
                                <select class="form-select valeur-select" name="variantes[${varianteIndex}][valeur]" required>
                                    <option selected disabled value="">Sélectionner</option>
                                </select>
                            </div>
                            <div class="col-2 mobile-money-fields d-none">
                                <label for="type_mobile_money">Type Mobile Money: </label>
                                <select class="form-select type-mobile-money-select" name="variantes[${varianteIndex}][type_mobile_money]" required>
                                    <option selected disabled value="">Sélectionner</option>
                                    ${Object.entries(typeMobileMoney).map(([key, type]) => `<option value="${key}">${type}</option>`).join('')}
                                </select>
                            </div>
                            <div class="col-2 mobile-money-fields d-none">
                                <label for="montant">Montant: </label>
                                <input type="number" class="form-control montant-input" name="variantes[${varianteIndex}][montant]" required>
                            </div>
                            <div class="col-2">
                                <label for="total">Total: </label>
                                <input type="number" style="background-color: #f1f4f7;" class="form-control total-input" name="variantes[${varianteIndex}][total]" readonly>
                            </div>
                            <div class="col-1 mt-2">
                                <button type="button" class="btn btn-danger remove-variante mt-3">
                                    <i class="mdi mdi-delete remove-variante"></i>
                                </button>
                            </div>
            `;
            container.appendChild(newRow);
            varianteIndex++;
        });

        document.getElementById('variantes-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-variante')) {
                e.target.closest('.variante-row').remove();
                updateGrandTotal();
            }
        });

        document.getElementById('variantes-container').addEventListener('change', function(event) {
            const row = event.target.closest('.variante-row');

            if (event.target.classList.contains('mode-select')) {
                const mode = event.target.value;
                const especeFields = row.querySelectorAll('.espece-fields');
                const mobileMoneyFields = row.querySelectorAll('.mobile-money-fields');

                // Réinitialisation des champs sauf celui du mode sélectionné
                row.querySelectorAll('.espece-fields input, .mobile-money-fields input, .espece-fields select, .mobile-money-fields select').forEach(input => input.value = '');
                row.querySelectorAll('.espece-fields').forEach(field => field.classList.add('d-none'));
                row.querySelectorAll('.mobile-money-fields').forEach(field => field.classList.add('d-none'));

                if (mode == 0) { // Espèce
                    especeFields.forEach(field => field.classList.remove('d-none'));
                } else if (mode == 1) { // Mobile money
                    mobileMoneyFields.forEach(field => field.classList.remove('d-none'));
                }

                row.querySelector('.total-input').value = '0';
                updateGrandTotal();
            }

            if (event.target.classList.contains('type-monnaie-select')) {
                const valeurSelect = row.querySelector('.valeur-select');
                const selectedType = event.target.value;
                const valeurs = selectedType === "Billets" ? billetData : pieceData;

                valeurSelect.innerHTML = '<option selected disabled value="">Sélectionner</option>';
                valeurs.forEach(valeur => {
                    valeurSelect.innerHTML += `<option value="${valeur}">${valeur}</option>`;
                });
            }

            // Mise à jour du total pour les espèces
            const quantite = parseFloat(row.querySelector('.quantite-select')?.value || 0);
            const valeur = parseFloat(row.querySelector('.valeur-select')?.value || 0);
            const montant = parseFloat(row.querySelector('.montant-input')?.value || 0);
            const total = quantite * valeur || montant;

            row.querySelector('.total-input').value = total.toFixed(0);
            updateGrandTotal();
        });

        document.getElementById('variantes-container').addEventListener('input', function(event) {
            if (event.target.classList.contains('montant-input')) {
                const row = event.target.closest('.variante-row');
                const montant = parseFloat(event.target.value || 0);

                row.querySelector('.total-input').value = montant.toFixed(0);
                updateGrandTotal();
            }
        });

        $('.btnCloturer').click(function(e) {
            e.preventDefault();
            // Récupérer le grand total
            const grandTotal = document.getElementById('grand-total').value;
            // Récupérer le total vente en caisse physique
            const totalVente = @json($totalVenteCaisse);
            // Vérifier si le grand total est inférieur au total vente
            if (grandTotal < totalVente || grandTotal > totalVente) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Le total de la billeterie doit être égal au total de vente de la caisse physique !',
                })
                return;
            } 


            else {
                                    // Envoi de la requête

                                                        
                                            // enregistrer la billeterie

                                            // Récupère le formulaire
                                        const form = document.getElementById('formSend');
                                        const formData = new FormData(form);

                                        // console.log(formData);
                            

                        Swal.fire({
                                    title: 'Confirmer la clôture de la caisse',
                                    text: "Vous êtes sur le point de clôturer la caisse. Cette action est irréversible.",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Oui, clôturer la caisse',
                                    cancelButtonText: 'Annuler'
                                }).then((result) => {
                        if (result.isConfirmed) {
                            // Afficher un chargement pendant le traitement
                            Swal.fire({
                                title: 'Traitement en cours...',
                                text: 'Veuillez patienter',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Exécution de la requête AJAX
                            $.ajax({
                                url: '{{ route("vente.billeterie-caisse-store") }}',
                                type: 'POST',
                                data: formData,
                                contentType: false,
                                processData: false,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Clôture réussie',
                                        text: 'Vous allez être redirigé vers le rapport de caisse.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.href = '{{ route("vente.rapport-caisse") }}';
                                    });
                                },
                                error: function(xhr) {
                                    console.error(xhr.responseText);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Erreur',
                                        text: 'Un problème est survenu lors de la clôture de la caisse.'
                                    });
                                }
                            });
                        }
                    });

            }
            
 
            
            
            
            // else {
            //     Swal.fire({
            //         title: 'Confirmer la clôture de la caisse',
            //         text: "Vous êtes sur le point de clôturer la caisse. Cette action est irréversible.",
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Oui, clôturer la caisse',
            //         cancelButtonText: 'Annuler'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             Swal.fire({
            //                 title: 'Caisse clôturée avec succès',
            //                 text: 'Déconnexion automatique.',
            //                 icon: 'success',
            //                 timer: 2000,
            //                 timerProgressBar: true,
            //                 didOpen: () => {
            //                     Swal.showLoading()
            //                 },
            //                 willClose: () => {
            //                     window.location.href = '{{ route('vente.cloture-caisse') }}';
            //                 }
            //             });
            //         }
            //     });
            // }
        });
    });
</script>