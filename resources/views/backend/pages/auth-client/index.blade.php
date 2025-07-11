@extends('backend.layouts.master')
@section('title')
    {{-- @lang('translation.datatables') --}}
    Client
@endsection
@section('css')
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            Liste des clients
        @endslot
        @slot('title')
            Clients
        @endslot
    @endcomponent



    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <!-- ========== Start Filtre ========== -->
                <div class="m-4">
                    @php
                        $selectedPeriode = request('periode');
                        $selectedDateDebut = request('date_debut');
                        $selectedDateFin = request('date_fin');
                        $selectedCaisse = request('caisse');
                        $selectedClient = request('client');
                        $selectedStatut = request('statut_paiement');
                    @endphp
                    <form action="{{ route('client.index') }}" method="GET">
                        <div class="row mb-3 d-flex justify-content-center">

                            {{-- Filtres réservés aux rôles autres que caisse/supercaisse --}}
                            {{-- @unless (auth()->user()->hasRole(['caisse', 'supercaisse'])) --}}
                            {{-- <div class="col-md-3">
                                <label for="periode" class="form-label">Période</label>
                                <select class="form-select" id="periode" name="periode">
                                    <option value="">Toutes les périodes</option>
                                    @foreach (['jour' => 'Jour', 'semaine' => 'Semaine', 'mois' => 'Mois', 'annee' => 'Année'] as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ $selectedPeriode === $key ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div> --}}

                            <div class="col-md-3">
                                <label for="date_debut" class="form-label">Date de début</label>
                                <input type="date" id="date_debut" name="date_debut" class="form-control"
                                    value="{{ $selectedDateDebut }}">
                            </div>

                            <div class="col-md-3">
                                <label for="date_fin" class="form-label">Date de fin</label>
                                <input type="date" id="date_fin" name="date_fin" class="form-control"
                                    value="{{ $selectedDateFin }}">
                            </div>



                            {{-- Filtres visibles pour tous --}}
                            {{-- <div class="col-md-4">
                        <label for="client" class="form-label">Clients</label>
                        <select class="form-select" id="client" name="client">
                            <option value="">Tous les clients</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ $selectedClient == $client->id ? 'selected' : '' }}>
                                    {{ $client->first_name }} {{ $client->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}

                            <div class="col-md-3">
                                <label for="statutPaiement" class="form-label">Statut</label>
                                <select class="form-select" id="statutPaiement" name="statut_paiement">
                                    <option value="">Tous les statuts</option>
                                    <option value="paye" {{ $selectedStatut === 'paye' ? 'selected' : '' }}>Payé</option>
                                    <option value="impaye" {{ $selectedStatut === 'impaye' ? 'selected' : '' }}>Impayé
                                    </option>
                                </select>
                            </div>

                            {{-- Boutons d'action --}}
                            <div class="col-md-3 d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                                <a href="{{ route('client.index') }}"
                                    class="btn btn-outline-secondary w-100">Réinitialiser</a>
                            </div>
                            {{-- @endunless --}}

                        </div>
                    </form>
                </div>
                <!-- ========== End Filtre ========== -->


                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Liste des clients


                        @if (request('periode') || request('date_debut') || request('date_fin') || request('client') || request('statut_paiement'))
                            <span class="badge bg-info">Filtré
                                {{-- @if (request('periode'))
                                    - Période: {{ request('periode') }}
                                @endif --}}
                                @if (request('date_debut'))
                                    - Date de début: {{ request('date_debut') }}
                                @endif
                                @if (request('date_fin'))
                                    - Date de fin: {{ request('date_fin') }}
                                @endif
                                {{-- @if (request('client'))
                                    - Client: {{ $clients->find(request('client'))->first_name ?? 'Tous' }}
                                @endif --}}
                                @if (request('statut_paiement'))
                                    - Statut: {{ request('statut_paiement') }}
                                @endif
                            </span>
                        @endif
                    </h5>
                    <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#myModal">Créer
                        un client</button>
                </div>


                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Prenoms</th>
                                    <th>Telephone</th>
                                    <th>Montant Impayés</th>
                                    <th>Date creation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $key => $item)
                                    <tr id="row_{{ $item['id'] }}">
                                        <td> {{ ++$key }} </td>
                                        <td>{{ $item['last_name'] }}</td>
                                        <td>{{ $item['first_name'] }}</td>
                                        <td>{{ $item['phone'] }}</td>

                                        <td>
                                           {{ number_format($item['ventesClient']->sum('montant_total'), 0, ',', ' ') }} FCFA
                                            {{-- <span class="badge bg-info">{{ $item['ventes_paye'] }}</span> --}}
                                            {{-- <span class="badge bg-danger">{{ $item['ventes_impaye'] }}</span> --}}
                                            {{-- <span class="badge bg-warning">{{ $item['ventesClient']->count() }}</span> --}}
                                            {{-- <span class="badge bg-success">{{ $item['ventesClient']->count() }}</span> --}}
                                        </td>
                                        <td> {{ $item['created_at'] }} </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    {{-- <li><a href="#!" class="dropdown-item"><i
                                                                class="ri-lock-fill align-bottom me-2 text-muted"></i>
                                                            Change password</a>
                                                    </li> --}}
                                                    <li><a href="{{ route('vente.client', ['client' => $item['id'], 'statut_paiement' => 'impaye']) }}"
                                                            class="dropdown-item edit-item-btn"><i
                                                                class="ri-history-fill align-bottom me-2 text-muted"></i>
                                                            Vente impayées</a></li>
                                                    <li>
                                                    <li><a type="button" class="dropdown-item edit-item-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#myModalEdit{{ $item['id'] }}"><i
                                                                class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                            Modifier</a></li>
                                                    <li>
                                                        <a href="#" class="dropdown-item remove-item-btn delete"
                                                            data-id={{ $item['id'] }}>
                                                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                            supprimer
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @include('backend.pages.auth-client.edit')
                                @endforeach


                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
    @include('backend.pages.auth-client.create')
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            var route = "client"
            delete_row(route);
        })
    </script>
@endsection
