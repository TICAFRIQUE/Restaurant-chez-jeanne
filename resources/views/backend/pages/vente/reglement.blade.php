<div class="row">
    <div class="col-xxl-6">
        <div class="card">
            <!-- Default Modals -->
            <div id="reglementModal{{ $vente->id }}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalLabel">Réglement de la vente #{{ $vente->code }} </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">

                            <form class="needs-validation" novalidate method="POST"
                                action="{{ route('admin-register.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="text-center alert alert-info">
                                    <h4>N° de vente : {{ $vente->code }}</h4>
                                    <h3 class="text-primary mb-3" data-totalVente="{{ $vente->montant_total }}">Montant
                                        de la
                                        vente : {{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</h3>

                                    <h5 class="text-danger">Statut : <span id="statutPaiement"></span> </h5>


                                </div>


                                <!-- ========== Start infos vente ========== -->
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Mode de paiement</label>
                                            <select id="payment-method" name="mode_paiement" class="form-select"
                                                required>
                                                @php
                                                    $mode_reglement = [
                                                        'Espèces' => 'espece',
                                                        'Orange Money' => 'orange money',
                                                        'Moov Money' => 'moov money',
                                                        'MTN Money' => 'mtn money',
                                                        'Wave' => 'wave',
                                                        'Visa' => 'visa',
                                                        'MasterCard' => 'mastercard',
                                                        'Impayé' => 'impaye',
                                                    ];
                                                @endphp

                                                <option value="" selected>Selectionner...</option>
                                                @foreach ($mode_reglement as $key => $item)
                                                    <option value="{{ $item }}"
                                                        {{ $vente->mode_paiement == $item ? 'selected' : '' }}>
                                                        {{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">

                                        <div class="mb-3" hidden>
                                            <label for="username" class="form-label">Montant Total de la vente</label>
                                            <input type="number" value="{{ $vente->montant_total }}"
                                                name="montant_total" class="form-control" id="montantTotalVente">
                                        </div>

                                        <div class="mb-3">
                                            <label for="username" class="form-label">Montant récu</label>
                                            <input type="number" name="montant_recu" class="form-control"
                                                id="montantRecu" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Montant rendu</label>
                                            <input type="number" name="montant_total" class="form-control"
                                                id="montantRendu" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Montant restant</label>
                                            <input type="number" name="montant_restant" class="form-control"
                                                id="montantRestant" readonly>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Statut paiement</label>
                                            <select class="form-control" name="statut_paiement" id="" required>
                                                <option  selected value>Selectionner...</option>

                                                <option value="paye"
                                                    {{ $vente->statut_paiement == 'paye' ? 'selected' : '' }}>
                                                    Payé</option>
                                                <option value="partiel"
                                                    {{ $vente->statut_paiement == 'partiel' ? 'selected' : '' }}>
                                                    Partiel</option>
                                                <option value="impayé"
                                                    {{ $vente->statut_paiement == 'impaye' ? 'selected' : '' }}>
                                                    Impayé</option>

                                            </select>
                                        </div>
                                    </div> --}}

                                    {{-- <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Statut de paiement</label>
                                            <input type="text" name="statut_paiement" class="form-control"
                                                id="statutPaiement" readonly>
                                        </div>
                                    </div> --}}

                                </div>
                                <!-- ========== End infos vente ========== -->



                                <!-- ========== Start Client si impayé ========== -->




                                <div class="row" id="client">
                                    <h4 class="fw-bold my-3 text-center ">Informations sur le client</h4>


                                    <!-- ========== Start selectionner un client si il existe ========== -->

                                    <div class="col-md-9 mb-4">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Choisir un client existant</label>
                                            <select class="form-select" name="client_id" id="client_id" data-choices
                                                data-choices-sorting-true>
                                                <option value="" selected>Selectionner...</option>
                                                @foreach ($client as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->last_name }} {{ $item->first_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>




                                    <div class="col-md-3">
                                        <div class="mb-3">

                                            <button class="btn btn-primary mt-4 w-100" type="button"
                                                id="ajouterClient"><i class="ri-add-line"></i>
                                                Nouveau client</button>

                                        </div>
                                    </div>

                                    <!-- ========== End selectionner un client si il existe ========== -->

                                    <div class="col-md-12 d-flex justify-content-between mb-2">
                                        <hr class="text-primary w-50 fw-bold">
                                        <h4 class="fw-bold text-center my-3 text-danger">OU créer un nouveau client</h4>
                                        <hr class="text-primary w-50 fw-bold">
                                    </div>




                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Nom</label>
                                            <input type="text" name="last_name" class="form-control" id="nomClient">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Prenoms</label>
                                            <input type="text" name="first_name" class="form-control"
                                                id="prenomClient">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Telephone</label>
                                            <input type="number" name="phone" class="form-control"
                                                id="telephoneClient">
                                        </div>
                                    </div>

                                </div>
                                <!-- ========== End Client si impayé ========== -->
                                <div class="mt-3">
                                    <button class="btn btn-success w-100" type="submit">Valider</button>
                                </div>
                            </form>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            </div><!-- end col -->
        </div><!-- end row -->
    </div><!-- end col -->
</div>
