@extends('backend.layouts.master')
@section('title')
    Billeterie
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        <link href="{{ URL::asset('build/libs/dropzone/dropzone.css') }}" rel="stylesheet">

        @slot('li_1')
            Vente
        @endslot
        @slot('title')
            Billeterie
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form id="formSend" autocomplete="off" class="needs-validation" novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="container my-4 divVariante">
                                        {{-- <div class="col-12 d-flex justify-content-center">
                                            <p>-------------------------------</p>
                                            <span class="fw-bold">Gestion de la billetterie</span>
                                            <p>-------------------------------</p>
                                        </div> --}}
                                      <!-- Tableau récapitulatif billetterie -->
<!-- Tableau récapitulatif billetterie amélioré -->
<div class="table-responsive my-4">
    <table class="table table-bordered table-hover align-middle shadow-sm bg-white">
        <thead class="table-primary text-center fs-5">
            <tr>
                <th>Libellé</th>
                <th>Montant (FCFA)</th>
            </tr>
        </thead>
        <tbody class="fs-5">
            <tr>
                <td class="fw-semibold">💰 Total de la vente</td>
                <td class="text-end fw-bold text-dark">
                    {{ number_format($totalVente, 0, ',', ' ') }}
                </td>
            </tr>

            <tr>
                <td class="fw-semibold">❌ Total des impayés</td>
                <td class="text-end fw-bold text-danger">
                    {{ number_format($totalVenteImpayer, 0, ',', ' ') }}
                </td>
            </tr>

            <tr>
                <td class="fw-semibold">✅ Impayés réglés</td>
                <td class="text-end fw-bold text-success">
                    {{ number_format($reglementImpayes, 0, ',', ' ') }}
                </td>
            </tr>

            <tr class="table-info">
                <td class="fw-bold text-primary">🧾 Montant physique en caisse</td>
                <td class="text-end fw-bold text-primary">
                    {{ number_format($totalVenteCaisse, 0, ',', ' ') }}
                </td>
            </tr>

            {{-- <tr class="table-warning">
                <td class="fw-bold text-dark">⚖️ Différence (Caisse - Vente)</td>
                <td class="text-end fw-bold text-dark">
                    {{ number_format($totalVenteCaisse - $totalVente, 0, ',', ' ') }}
                </td>
            </tr> --}}
        </tbody>
    </table>
</div>


                                        {{-- <a href="{{ route('vente.rapport-caisse') }}" class="btn btn-primary">Rapport de
                                            vente</a> --}}

                                        <div id="variantes-container">
                                            <div class="row variante-row mb-4">
                                                {{-- <div class="col-3">
                                                    <label for="mode" class="form-label">Mode</label>
                                                    <select class="form-select mode-select" name="variantes[0][mode]"
                                                        required>
                                                        <option selected disabled value="">Sélectionner</option>
                                                        @foreach ($modes as $key => $mode)
                                                            <option value="{{ $mode }}">{{ $mode }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-2">
                                                    <label for="quantite" class="form-label">Quantité</label>
                                                    <select class="form-select quantite-select"
                                                        name="variantes[0][quantite]" required>
                                                        <option selected disabled value="">Sélectionner</option>
                                                        @for ($i = 1; $i <= 500; $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-2">
                                                    <label for="type_monnaie" class="form-label">Type de monnaie</label>
                                                    <select class="form-select type-monnaie-select"
                                                        name="variantes[0][type_monnaie]" required>
                                                        <option selected disabled value="">Sélectionner</option>
                                                        @foreach ($type_monnaies as $key => $type_monnaie)
                                                            <option value="{{ $type_monnaie }}">{{ $type_monnaie }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-2">
                                                    <label for="valeur" class="form-label">Valeur</label>
                                                    <select class="form-select valeur-select" name="variantes[0][valeur]"
                                                        required>
                                                        <option selected disabled value="">Sélectionner</option>
                                                    </select>
                                                </div>
                                                <div class="col-2">
                                                    <label for="total" class="form-label">Total</label>
                                                    <input type="text" class="form-control total-input"
                                                        name="variantes[0][total]" readonly>
                                                </div> --}}
                                            </div>


                                        </div>
                                        <button type="button" class="btn btn-primary mb-3" id="add-monnaie"> Ajouter une
                                            ligne <i class="ri ri-add-circle-line"></i> </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="grand-total" class="form-label fw-bold">Total Général :</label>
                            <input type="text" id="grand-total" class="form-control w-25" readonly>
                        </div>
                        {{-- <div class="text-end mt-3">
                            <h4>Total Billeterie : <span id="grand-total">0</span> FCFA</h4>
                        </div> --}}
                        <div class="text-end mb-3">
                            <a href={{ route('vente.cloture-caisse') }} class="btn btn-success w-lg btnCloturer">Cloturer la
                                caisse</a>
                        </div>
                    </form>

                </div>
                <!-- end row -->
                <!-- end card -->

            </div>
        </div><!-- end row -->
    </div><!-- end col -->


    <!--end row-->

@section('script')
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script>
    <script src="{{ URL::asset('build/js/pages/modal.init.js') }}"></script>
    {{-- <script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script> --}}
    <script src="{{ URL::asset('build/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>

    <script src="{{ URL::asset('build/libs/dropzone/dropzone-min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/ecommerce-product-create.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const typeMobileMoney = @json($type_mobile_money);
            const billetData = @json($billets);
            const pieceData = @json($pieces);
            const modes = @json($modes);

            let varianteIndex = 1;

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
            <div class="col-3">
                <label for="mode">Mode :</label>
                <select class="form-select mode-select" name="variantes[${varianteIndex}][mode]" required>
                    <option selected disabled value="">Sélectionner</option>
                    ${Object.entries(modes).map(([key, mode]) => `<option value="${key}">${mode}</option>`).join('')}
                </select>
            </div>
            <div class="col-3 espece-fields d-none">
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
                    ${Array.from({ length: 500 }, (_, i) => ` < option value = "${i + 1}" > $ {
                    i + 1
                } < /option>`).join('')} < /
            select > <
                /div> <
            div class = "col-2 espece-fields d-none" >
            <
            label
            for = "valeur" > Valeur: < /label> <
            select class = "form-select valeur-select"
            name = "variantes[${varianteIndex}][valeur]"
            required >
                <
                option selected disabled value = "" > Sélectionner < /option> < /
            select > <
                /div> <
            div class = "col-3 mobile-money-fields d-none" >
            <
            label
            for = "type_mobile_money" > Type Mobile Money: < /label> <
            select class = "form-select type-mobile-money-select"
            name = "variantes[${varianteIndex}][type_mobile_money]"
            required >
                <
                option selected disabled value = "" > Sélectionner < /option>
            $ {
                Object.entries(typeMobileMoney).map(([key, type]) =>
                    `<option value="${key}">${type}</option>`).join('')
            } <
            /select> < /
            div > <
                div class = "col-2 mobile-money-fields d-none" >
                <
                label
            for = "montant" > Montant: < /label> <
            input type = "number"
            class = "form-control montant-input"
            name = "variantes[${varianteIndex}][montant]"
            required >
                <
                /div> <
            div class = "col-2" >
            <
            label
            for = "total" > Total: < /label> <
            input type = "number"
            style = "background-color: #f1f4f7;"
            class = "form-control total-input"
            name = "variantes[${varianteIndex}][total]"
            readonly >
                <
                /div> <
            div class = "col-1 mt-2" >
            <
            button type = "button"
            class = "btn btn-danger remove-variante mt-3" >
            <
            i class = "mdi mdi-delete" > < /i> < /
            button > <
                /div>
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
                                valeurSelect.innerHTML += ` < option value = "${valeur}" > $ {
                valeur
            } < /option>`;
            });
        }

        // Mise à jour du total pour les espèces
        const quantite = parseFloat(row.querySelector('.quantite-select')?.value || 0);
        const valeur = parseFloat(row.querySelector('.valeur-select')?.value || 0);
        const montant = parseFloat(row.querySelector('.montant-input')?.value || 0);
        const total = quantite * valeur || montant;

        row.querySelector('.total-input').value = total.toFixed(0); updateGrandTotal();
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
        //recuperer le grand total
        const grandTotal = document.getElementById('grand-total').value;
        //recuperer le total vente
        const totalVente = @json($totalVente);
        //verifier si le grand total est inferieur au total vente
        if (grandTotal < totalVente || grandTotal > totalVente) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Le total de la billeterie doit être égal au total de vente !',
            })
            return;
        } else {
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
                    Swal.fire({
                        title: 'Caisse cloturée avec succès',
                        text: 'Déconnexion automatique.',
                        icon: 'success',
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        willClose: () => {
                            window.location.href =
                                '{{ route('vente.cloture-caisse') }}';
                        }
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            console.log(
                                'Redirection automatique vers la page de connexion'
                            );
                        }
                    });
                }
            });
        }


        });

        });
    </script> --}}

    {{-- <script>
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
                        ${Array.from({ length: 500 }, (_, i) => ` < option value = "${i + 1}" > $ {
                        i + 1
                    } < /option>`).join('')} <
                /select> <
                /div> <
                div class = "col-2 espece-fields d-none" >
                <
                label
            for = "valeur" > Valeur: < /label> <
                select class = "form-select valeur-select"
            name = "variantes[${varianteIndex}][valeur]"
            required >
                <
                option selected disabled value = "" > Sélectionner < /option> <
                /select> <
                /div> <
                div class = "col-2 mobile-money-fields d-none" >
                <
                label
            for = "type_mobile_money" > Type Mobile Money: < /label> <
                select class = "form-select type-mobile-money-select"
            name = "variantes[${varianteIndex}][type_mobile_money]"
            required >
                <
                option selected disabled value = "" > Sélectionner < /option>
            ${Object.entries(typeMobileMoney).map(([key, type]) => `<option value="${key}">${type}</option>`).join('')  }
                   
          
                 <
            /select> <
            /div> <
            div class = "col-2 mobile-money-fields d-none" >
            <
            label
            for = "montant" > Montant: < /label> <
                input type = "number"
            class = "form-control montant-input"
            name = "variantes[${varianteIndex}][montant]"
            required >
                <
                /div> <
                div class = "col-2" >
                <
                label
            for = "total" > Total: < /label> <
                input type = "number"
            style = "background-color: #f1f4f7;"
            class = "form-control total-input"
            name = "variantes[${varianteIndex}][total]"
            readonly >
                <
                /div> <
                div class = "col-1 mt-2" >
                <
                button type = "button"
            class = "btn btn-danger remove-variante mt-3" >
            <
            i class = "mdi mdi-delete" > < /i> <
            /button> <
            /div>
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
                            valeurSelect.innerHTML += ` < option value = "${valeur}" > $ {
                valeur
            } < /option>`;
            });
        }

        // Mise à jour du total pour les espèces
        const quantite = parseFloat(row.querySelector('.quantite-select')?.value || 0);
        const valeur = parseFloat(row.querySelector('.valeur-select')?.value || 0);
        const montant = parseFloat(row.querySelector('.montant-input')?.value || 0);
        const total = quantite * valeur || montant;

        row.querySelector('.total-input').value = total.toFixed(0); updateGrandTotal();
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
        // Récupérer le total vente
        const totalVente = @json($totalVente);
        // Vérifier si le grand total est inférieur au total vente
        if (grandTotal < totalVente || grandTotal > totalVente) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Le total de la billeterie doit être égal au total de vente !',
            })
            return;
        } else {
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
                    Swal.fire({
                        title: 'Caisse clôturée avec succès',
                        text: 'Déconnexion automatique.',
                        icon: 'success',
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        },
                        willClose: () => {
                            window.location.href = '{{ route('vente.cloture-caisse') }}';
                        }
                    });
                }
            });
        }
        });
        });
    </script> --}}

    @include('backend.pages.vente.billeterie.script-js')
@endsection


@endsection
