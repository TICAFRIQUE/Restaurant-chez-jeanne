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
                                action="{{ route('reglement.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="text-center alert alert-info">
                                    <h4>N° de vente : {{ $vente->code }}</h4>
                                    <h4 class="text-primary " data-totalVente="{{ $vente->montant_total }}">Montant
                                        de la
                                        vente : {{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</h4>

                                    <h4 class="text-success " data-totalVente="{{ $vente->montant_total }}">Déjà
                                        payé:

                                        @php
                                            $dejà_paye = $vente->montant_total - $vente->montant_restant;
                                        @endphp

                                        {{ number_format($dejà_paye, 0, ',', ' ') }} FCFA

                                    </h4>

                                    <h4 class="text-info " data-totalVente="{{ $vente->montant_total }}">Reste à
                                        payer:



                                        {{ number_format($vente->montant_restant, 0, ',', ' ') }} FCFA

                                    </h4>



                                    <h5 class="text-danger">Statut : <span id="statutPaiement"></span> </h5>


                                </div>


                                <!-- ========== Start infos vente ========== -->
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Mode de paiement</label>
                                            <select id="modePaiement" name="mode_paiement" class="form-select" required>
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
                                                    <option value="{{ $item }}">
                                                        {{ $key }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <!-- Information de vente en hidden -->
                                        <div class="mb-3" hidden>
                                            <label for="username" class="form-label">Information de vente</label>
                                            <input type="text" value="{{ $vente->id }}" name="vente_id">
                                            <input type="number" value="{{ $vente->montant_total }}"
                                                name="montant_vente" id="montantTotalVente">

                                            <input type="number" value="{{ $vente->montant_restant }}"
                                                id="montantARegler">
                                        </div>

                                        <div class="mb-3">
                                            <label for="username" class="form-label">Montant récu</label>
                                            <input type="number" name="montant_reglement" class="form-control"
                                                id="montantRecu" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Montant rendu</label>
                                            <input type="number" value="{{ $vente->montant_rendu }}"
                                                name="montant_rendu" class="form-control" id="montantRendu" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Montant restant</label>
                                            <input type="number" value="{{ $vente->montant_restant }}"
                                                name="montant_restant" class="form-control" id="montantRestant"
                                                readonly>
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

                                <h4 class="fw-bold my-3 text-center ">Informations sur le client</h4>

                                @if ($vente->client)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Nom du client</label>
                                                <input type="text" name="client_name"
                                                    value="{{ $vente->client->first_name }} {{ $vente->client->last_name }}"
                                                    class="form-control" id="clientName" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Téléphone du client</label>
                                                <input type="text" name="client_phone"
                                                    value="{{ $vente->client->phone }}" class="form-control"
                                                    id="clientPhone" readonly>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-danger text-center">Aucun client associé à cette vente.</p>

                                    <div class="row" id="client">


                                        <!-- ========== Start selectionner un client si il existe ========== -->

                                        <div class="col-md-12 mb-4">
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Choisir un client
                                                    existant</label>
                                                <select id="username" class="form-select clientId" name="client_id" data-choices
                                                    data-choices-sorting-true data-choices-removeItem>
                                                    <option value="">Selectionner...</option>
                                                    @foreach ($client as $item)
                                                        <!-- ========== compter les commande impayé du client ========== -->
                                                        @php
                                                            $impayeCount = $item->ventes()
                                                                ->where('statut_paiement', 'impaye')
                                                                ->count();
                                                        @endphp

                                                        <option value="{{ $item->id }}">
                                                            {{ $item->first_name }} {{ $item->last_name }}  <span class="text-danger fw-bold d-{{ $impayeCount > 0 ? 'block' : 'none' }}">({{ $impayeCount }} vente impayée)</span></option>
                                                    @endforeach
                                                   
                                                </select>


                                            </div>
                                        </div>

                                        <!-- ========== End selectionner un client si il existe ========== -->

                                        <div class="col-md-12 d-flex justify-content-between mb-2 newClient">
                                            <hr class="text-primary w-50 fw-bold">
                                            <h4 class="fw-bold text-center my-3 text-danger">OU créer un nouveau client
                                            </h4>
                                            <hr class="text-primary w-50 fw-bold">
                                        </div>




                                        <!-- ========== Start Créer un nouveau client ========== -->

                                        <div class="col-md-4 newClient">
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Nom</label>
                                                <input type="text" name="last_name" class="form-control "
                                                    id="nomClient">
                                            </div>
                                        </div>

                                        <div class="col-md-4 newClient">
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Prenoms</label>
                                                <input type="text" name="first_name" class="form-control"
                                                    id="prenomClient">
                                            </div>
                                        </div>

                                        <div class="col-md-4 newClient">
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Telephone</label>
                                                <input type="number" name="phone" class="form-control "
                                                    id="telephoneClient">
                                            </div>
                                        </div>


                                        <!-- ========== End Créer un nouveau client ========== -->

                                    </div>

                                @endif
                                <!-- ========== End Client si impayé ========== -->
                                <div class="mt-3">
                                    <button class="btn btn-success w-100 saveReglement" type="submit">Valider</button>
                                </div>
                            </form>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            </div><!-- end col -->
        </div><!-- end row -->
    </div><!-- end col -->
</div>
