@extends('backend.layouts.master')
@section('title')
    Vente
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
            Liste des ventes
        @endslot
        @slot('title')
            Gestion des ventes
        @endslot
    @endcomponent


    <style>
        /* css pour les cartes */
        .card-custom {
            border: none;
            color: #fff;
            border-radius: 1rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff, #00c6ff);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745, #85e085);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107, #ffde59);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #fa5c6c, #f06548);
        }

        .bg-gradient-danger2 {
            background: linear-gradient(135deg, #a886ec, #655ce7);
        }


        .card-title {
            font-weight: bold;
            font-size: 1rem;
        }

        .card-value {
            font-size: 1.8rem;
            font-weight: bold;
        }


        /*css pour les boutons d'action*/
        .btn-group-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn-lg {
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease-in-out;
        }

        .btn-lg i {
            font-size: 1.2rem;
        }

        .btn-lg:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>



    <div class="row">

        <!-- ========== Start filtre ========== -->
        {{-- @if (!auth()->user()->hasRole(['caisse', 'supercaisse']))
            <form action="{{ route('vente.index') }}" method="GET">
                <div class="row mb-3">


                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="statut" class="form-label">Periodes</label>
                            <select class="form-select" id="periode" name="periode">
                                <option value="">Toutes les periodes</option>
                                @foreach (['jour' => 'Jour', 'semaine' => 'Semaine', 'mois' => 'Mois', 'annee' => 'Année'] as $key => $value)
                                    <option value="{{ $key }}" {{ request('periode') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label for="date_debut" class="form-label">Date de début</label>
                        <input type="date" value="{{ request('date_debut') }}" class="form-control" id="date_debut"
                            name="date_debut">
                    </div>
                    <div class="col-md-2">
                        <label for="date_fin" class="form-label">Date de fin</label>
                        <input type="date" value="{{ request('date_fin') }}" class="form-control" id="date_fin"
                            name="date_fin">
                    </div>


                    <div class="col-md-2">
                        <label for="caisse" class="form-label">Caisse</label>
                        <select class="form-select" id="caisse" name="caisse">

                            <option value= " ">Toutes les caisses</option>
                            @foreach ($caisses as $caisse)
                                <option value="{{ $caisse->id }}"
                                    {{ request('caisse') == $caisse->id ? 'selected' : '' }}>{{ $caisse->libelle }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="caisse" class="form-label">Clients</label>
                        <select class="form-select" id="client" name="client">

                            <option value= " ">Tout les clients</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}"
                                    {{ request('client') == $client->id ? 'selected' : '' }}>{{ $client->first_name }}
                                    {{ $client->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="caisse" class="form-label">Statut</label>
                        <select class="form-select" id="statutPaiement" name="statut_paiement">

                            <option value= "">Tout les statuts</option>
                            <option value="paye" {{ request('statut_paiement') == 'paye' ? 'selected' : '' }}>Payé
                            </option>
                            <option value="impaye" {{ request('statut_paiement') == 'impaye' ? 'selected' : '' }}>
                                Impayé</option>
                        </select>
                    </div>

                    <div class="col-md-2 mt-4">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                    </div>
                </div>
            </form>
        @endif --}}


        @php
            $selectedPeriode = request('periode');
            $selectedDateDebut = request('date_debut');
            $selectedDateFin = request('date_fin');
            $selectedCaisse = request('caisse');
            $selectedClient = request('client');
            $selectedStatut = request('statut_paiement');
        @endphp

        <form action="{{ route('vente.index') }}" method="GET">
            <div class="row mb-3 d-flex justify-content-center">

                {{-- Filtres réservés aux rôles autres que caisse/supercaisse --}}
                @unless (auth()->user()->hasRole(['caisse', 'supercaisse']))
                    <div class="col-md-4">
                        <label for="periode" class="form-label">Période</label>
                        <select class="form-select" id="periode" name="periode">
                            <option value="">Toutes les périodes</option>
                            @foreach (['jour' => 'Jour', 'semaine' => 'Semaine', 'mois' => 'Mois', 'annee' => 'Année'] as $key => $label)
                                <option value="{{ $key }}" {{ $selectedPeriode === $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="date_debut" class="form-label">Date de début</label>
                        <input type="date" id="date_debut" name="date_debut" class="form-control"
                            value="{{ $selectedDateDebut }}">
                    </div>

                    <div class="col-md-4">
                        <label for="date_fin" class="form-label">Date de fin</label>
                        <input type="date" id="date_fin" name="date_fin" class="form-control"
                            value="{{ $selectedDateFin }}">
                    </div>

                    <div class="col-md-4">
                        <label for="caisse" class="form-label">Caisse</label>
                        <select class="form-select" id="caisse" name="caisse">
                            <option value="">Toutes les caisses</option>
                            @foreach ($caisses as $caisse)
                                <option value="{{ $caisse->id }}" {{ $selectedCaisse == $caisse->id ? 'selected' : '' }}>
                                    {{ $caisse->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filtres visibles pour tous --}}
                    <div class="col-md-4">
                        <label for="client" class="form-label">Clients</label>
                        <select class="form-select" id="client" name="client">
                            <option value="">Tous les clients</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ $selectedClient == $client->id ? 'selected' : '' }}>
                                    {{ $client->first_name }} {{ $client->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="statutPaiement" class="form-label">Statut</label>
                        <select class="form-select" id="statutPaiement" name="statut_paiement">
                            <option value="">Tous les statuts</option>
                            <option value="paye" {{ $selectedStatut === 'paye' ? 'selected' : '' }}>Payé</option>
                            <option value="impaye" {{ $selectedStatut === 'impaye' ? 'selected' : '' }}>Impayé</option>
                        </select>
                    </div>

                    {{-- Boutons d'action --}}
                    <div class="col-md-2 d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                        <a href="{{ route('vente.index') }}" class="btn btn-outline-secondary w-100">Réinitialiser</a>
                    </div>
                @endunless





            </div>
        </form>

        <!-- ========== End filtre ========== -->


        <!-- ========== Start date session ========== -->
        @if (Auth::user()->hasRole(['caisse', 'supercaisse']))
            <div class="col-lg-12">
                <div class="alert alert-info alert-dismissible fade show d-flex justify-content-center align-items-center"
                    role="alert">
                    <div class="me-3">
                        <h5 class="card-title mb-0">
                            Date de vente actuelle :
                            <span id="heureActuelle">
                                {{ $sessionDate ? \Carbon\Carbon::parse($sessionDate)->format('d-m-Y') : 'non définie' }}
                            </span>
                        </h5>
                        <p class="fw-bold text-center text-dark ">Caisse actuelle :
                            {{ auth()->user()->caisse->libelle ?? 'non définie' }}</p>
                    </div>

                    @if ($data_vente->sum('montant_total') == 0)
                        <button type="button" class="btn btn-info ms-3" data-bs-toggle="modal"
                            data-bs-target="#dateSessionVenteModal">
                            {{ $sessionDate ? 'Modifier la date de la session vente' : 'Choisir une date pour la session vente' }}
                        </button>
                    @endif

                    <button type="button" class="btn-close ms-3" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            </div>
        @endif


        {{-- @if (Auth::user()->hasRole(['caisse', 'supercaisse']))
            <div class="col-lg-12">
                <div class="alert alert-info alert-dismissible fade show d-flex justify-content-center align-items-center"
                    role="alert">
                    <div class="me-3">

                        <h5 class="card-title mb-0">Date de vente actuelle : <span
                                id="heureActuelle">{{ $sessionDate != null ? \Carbon\Carbon::parse($sessionDate)->format('d-m-Y') : 'non defini' }}</span>
                        </h5>


                    </div>
                    @if ($data_vente->sum('montant_total') == 0)
                        <button type="button" class="btn btn-info ms-3" data-bs-toggle="modal"
                            data-bs-target="#dateSessionVenteModal">
                            {{ $sessionDate != null ? 'Modifier la date de la session vente' : ' Choisir une date pour la session vente' }}
                        </button>
                    @endif

                    <button type="button" class="btn-close ms-3" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
        @endif --}}

        <!-- ========== End date session ========== -->




        <div class="card">
            <!-- ========== Start filter result libellé ========== -->
            <div class="card-header d-flex justify-content-between">
                {{-- <h5 class="card-title mb-0 filter" style="text-align: center">Liste des ventes
                    @if (request('date_debut') || request('date_fin') || request('caisse') || request('periode') || request('statut_paiement'))


                        @if (request()->has('statut_paiement') && request('statut_paiement') != null)
                            -
                            <strong>{{ request('statut_paiement') }}</strong>
                        @endif
                        @if (request()->has('periode') && request('periode') != null)
                            -
                            <strong>{{ request('periode') }}</strong>
                        @endif

                        @if (request('date_debut') || request('date_fin'))
                            du
                            @if (request('date_debut'))
                                {{ \Carbon\Carbon::parse(request('date_debut'))->format('d/m/Y') }}
                            @endif
                            @if (request('date_fin'))
                                au {{ \Carbon\Carbon::parse(request('date_fin'))->format('d/m/Y') }}
                            @endif
                        @endif

                        @if (request()->has('caisse') && request('caisse') != null)
                            -
                            <strong>{{ ucfirst(App\Models\Caisse::find(request('caisse'))->libelle) }}</strong>
                        @endif
                    @else
                        du mois en cours - {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                    @endif

                </h5> --}}

                @php
                    use Carbon\Carbon;

                    $dateDebut = request('date_debut') ? Carbon::parse(request('date_debut'))->format('d/m/Y') : null;
                    $dateFin = request('date_fin') ? Carbon::parse(request('date_fin'))->format('d/m/Y') : null;
                    $caisseLabel = request('caisse')
                        ? optional(App\Models\Caisse::find(request('caisse')))->libelle
                        : null;
                    $client = request('client') ? optional(App\Models\User::find(request('client')))->first_name : null;
                @endphp

                <h5 class="card-title mb-0" style="text-align: center;">
                    Liste des ventes
                    @if (request()->filled('statut_paiement') ||
                            request()->filled('periode') ||
                            request()->filled('date_debut') ||
                            request()->filled('date_fin') ||
                            request()->filled('caisse') ||
                            request()->filled('client') ||
                            request()->filled('statut_vente') ||
                            request()->filled('statut_reglement'))
                        @if (request()->filled('statut_paiement'))
                            - {{ request('statut_paiement') == 'paye' ? 'Payées' : 'Impayées' }}
                        @endif
                        @if (request()->filled('statut_vente'))
                            - {{ request('statut_vente') }}
                        @endif
                        @if (request()->filled('statut_reglement'))
                            - {{ request('statut_reglement') == 0 ? 'Non réglée' : 'Réglée' }}
                        @endif

                        @if (request()->filled('periode'))
                            - {{ request('periode') }}
                        @endif

                        @if ($dateDebut || $dateFin)
                            - du
                            @if ($dateDebut)
                                {{ $dateDebut }}
                            @endif
                            @if ($dateFin)
                                au {{ $dateFin }}
                            @endif
                        @endif

                        @if ($caisseLabel)
                            - {{ ucfirst($caisseLabel) }}
                        @endif

                        @if ($client)
                            de {{ ucfirst($client) }}
                        @endif
                    @else
                        - du mois en cours - {{ Carbon::now()->translatedFormat('F Y') }}
                    @endif
                </h5>




            </div>
            <!-- ========== End filter result libellé ========== -->


            <!-- ========== Start button action ========== -->
            <div class="card-header mb-3 d-flex justify-content-center">
                {{-- @if (auth()->user()->hasRole(['caisse', 'supercaisse']))
                    <h5 class="card-title
                {{-- @if (auth()->user()->hasRole(['caisse', 'supercaisse']))
                    <h5 class="card-title
                {{-- @if (auth()->user()->hasRole(['caisse', 'supercaisse']))
                    <!-- ========== Start cloture caisse button ========== -->
                    @if ($data_vente->sum('montant_total') > 0)
                        <a href="{{ route('vente.billeterie-caisse') }}" class="btn btn-danger btn-lg "> 👍Clôturer la
                            caisse
                            <i class="ri ri-bill"></i></a>
                    @else
                        <button class="btn btn-danger btn-lg" disabled>Clôturer la caisse <i
                                class="ri ri-lock-line"></i></button>
                    @endif
                    <!-- ========== End cloture caisse button ========== -->


                    <!-- ========== Start rapport caisse button ========== -->
                    <!-- ========== Start Si il y a des ventes dejà realisé et cloturé par la caisse connecté ========== -->
                    @if ($venteCaisseCloture > 0)
                        <a href="{{ route('vente.rapport-caisse') }}" class="btn btn-success btn-lg">Voir le rapport de
                            caisse <i class="ri ri-file-list-3-line"></i></a>
                    @endif
                    <!-- ========== End Section ========== -->
                    <!-- ========== End rapport caisse button ========== -->

                    <!-- ========== Start nouvelle vente button ========== -->
                    @if ($sessionDate != null)
                        <a href="{{ route('vente.create') }}" type="button" class="btn btn-primary btn-lg">
                            Nouvelle vente 🛒</a>
                    @else
                        <button type="button" class="btn btn-info ms-2 btnChoiceDate btn-lg">
                            Nouvelle vente 🛒
                        </button>
                    @endif
                    <!-- ========== End nouvelle vente button ========== -->
                @endif --}}


                @if (auth()->user()->hasRole(['caisse', 'supercaisse']))
                    <div class="btn-group-actions">

                        {{-- Clôture caisse --}}
                        @if ($data_vente->sum('montant_total') > 0 && $venteAucunReglement == 0)
                            <a href="{{ route('vente.billeterie-caisse') }}" class="btn btn-danger btn-lg">
                                👍 Clôturer la caisse <i class="ri ri-bill"></i>
                            </a>
                        @else
                            <button class="btn btn-danger btn-lg" disabled>
                                Clôturer la caisse <i class="ri ri-lock-line"></i>
                            </button>
                        @endif

                        {{-- Rapport caisse --}}
                        @if ($venteCaisseCloture > 0)
                            <a href="{{ route('vente.rapport-caisse') }}" class="btn btn-success btn-lg">
                                📊 Voir le rapport de caisse <i class="ri ri-file-list-3-line"></i>
                            </a>
                        @endif

                        {{-- Nouvelle vente --}}
                        @if ($sessionDate != null)
                            <a href="{{ route('vente.create') }}" class="btn btn-primary btn-lg">
                                🛒 Nouvelle vente
                            </a>
                        @else
                            <button type="button" class="btn btn-info btn-lg btnChoiceDate">
                                🛒 Nouvelle vente
                            </button>
                        @endif

                    </div>
                @endif
            </div>
            <!-- ========== End button action ========== -->





            <!-- ========== Start statistique caisse ========== -->
            <div class="card-body">
                {{-- @if (auth()->user()->hasRole(['caisse', 'supercaisse']))
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Caisse actuelle</h5>
                                <p class="card-text h3 text-primary">
                                    {{ auth()->user()->caisse->libelle ?? 'Non définie' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body d-flex justify-content-around bg-success">
                                <h5 class="card-title text-white">Total des ventes du jour : <br> <strong
                                        class="fs-3">{{ number_format($data_vente->sum('montant_total'), 0, ',', ' ') }}
                                        FCFA</strong> </h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body d-flex justify-content-around">
                                <h5 class="card-title">Vente en attente : <br> <strong
                                        class="text-primary fs-3">5</strong> </h5>
                            </div>
                        </div>
                    </div>


                     <div class="col-md-3">
                        <div class="card">
                            <div class="card-body d-flex justify-content-around">
                                <h5 class="card-title">Vente sans réglement : <br> <strong
                                        class="text-danger fs-3">5</strong> </h5>
                            </div>
                        </div>
                    </div>
                </div>
            @endif --}}


                @if (auth()->user()->hasRole(['caisse', 'supercaisse']))
                    @php
                        //recuperer les ventes de la caisse connectée

                        $query = App\Models\Vente::query();
                        $query
                            ->where('caisse_id', auth()->user()->caisse_id)
                            ->where('user_id', auth()->user()->id)
                            ->where('statut_cloture', false)
                            ->whereDate('date_vente', auth()->user()->caisse->session_date_vente); // ✅ Compare seulement la date

                        $caisseVente = $query->get();
                    @endphp


                    <div class="row mb-3  d-flex flex-wrap justify-content-center">

                        {{-- Carte 1 : Total des ventes --}}
                          {{-- <div class="col-md-2">
                          <a href="{{ route('vente.index') }}" class="text-decoration-none">
                                <div class="card card-custom bg-gradient-primary text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Total ventes</h5>
                                        <p class="card-value">
                                            {{ $caisseVente->sum('montant_total') > 0 ? number_format($caisseVente->sum('montant_total'), 0, ',', ' ') : '0' }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div> --}}

                        {{-- Carte 2 : Ventes du jour --}}
                        <div class="col-md-3">
                            <a href="{{ route('vente.index') }}" class="text-decoration-none">
                                <div class="card card-custom bg-gradient-success text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Toutes les Ventes <span class="text-white">({{ $caisseVente->count() }})</span></h5>
                                        <p class="card-value">
                                            {{ $caisseVente->sum('montant_total') > 0 ? number_format($caisseVente->sum('montant_total'), 0, ',', ' ') : '0' }} FCFA
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- Carte 3 : Impayés --}}
                        <div class="col-md-3">
                            <a href="{{ route('vente.index', ['statut_paiement' => 'impaye']) }}"
                                class="text-decoration-none">
                                <div class="card card-custom bg-gradient-danger2 text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Ventes impayés 
                                         <span class="text-white ">   ({{ $caisseVente->where('statut_paiement', 'impaye')->count() }})</span></h5>
                                        <p class="card-value">
                                            {{ number_format($caisseVente->where('statut_paiement', 'impaye')->sum('montant_restant'), 0, ',', ' ') }}
                                            FCFA
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- Carte 4 : Ventes en attente --}}
                        {{-- <div class="col-md-2">
                            <a href="{{ route('vente.index', ['statut_vente' => 'en attente']) }}"
                                class="text-decoration-none">
                                <div class="card card-custom bg-gradient-warning text-dark">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Ventes en attente</h5>
                                        <p class="card-value">5</p>
                                    </div>
                                </div>
                            </a>
                        </div> --}}

                        {{-- Carte 5 : Ventes non réglées --}}
                        <div class="col-md-3">
                            <a href="{{ route('vente.index', ['statut_reglement' => 0]) }}" class="text-decoration-none">
                                <div class="card card-custom bg-gradient-danger text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Ventes non réglées <span class="text-white">({{ $venteAucunReglement }})</span></h5>
                                        <p class="card-value">{{ number_format($data_vente->where('statut_reglement', 0)->sum('montant_total'), 0, ',', ' ') }} FCFA</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                @endif

            </div>
            <!-- ========== End statistique caisse ========== -->






            <div class="card-body">
                <div class="table-responsive">
                    <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Statut de vente</th>
                                <th>N° de vente</th>
                                <th>N° de Table</th>
                                <th>Session vente</th>
                                <th>Montant</th>
                                <th>Vendu le</th>
                                <th>Vendu par</th>
                                <th>Caisse</th>
                                <th>Statut paiement vente</th>
                                <th>Statut réglement</th>

                                @if (auth()->user()->can('modifier-vente') || auth()->user()->can('supprimer-vente'))
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_vente as $key => $item)
                                <tr id="row_{{ $item['id'] }}">

                                    <td> {{ $loop->iteration }} </td>
                                    <td> <span
                                            class="badge bg-{{ $item['statut'] == 'en attente' ? 'warning' : 'success' }}">{{ $item['statut'] }}</span>
                                    </td>
                                    <td> <a class="fw-bold"
                                            href="{{ route('vente.show', $item->id) }}">#{{ $item['code'] }}</a> </td>

                                    <td> {{ $item['numero_table'] ?? 'non défini' }}</td>
                                    <td> {{ \Carbon\Carbon::parse($item['date_vente'])->format('d-m-Y') }}
                                        {{ $item['created_at']->format('à H:i') }} </td>
                                    <td> {{ number_format($item['montant_total'], 0, ',', ' ') }} FCFA </td>
                                    <td> {{ $item['created_at']->format('d-m-Y à H:i') }} </td>
                                    <td> {{ $item['user']['first_name'] }} {{ $item['user']['last_name'] }} </td>
                                    <td> {{ $item['caisse']['libelle'] ?? '' }} </td>

                                    <td> <span
                                            class="badge bg-{{ $item['statut_paiement'] == 'paye' ? 'success' : 'danger' }}">
                                            {{ $item['statut_paiement'] == 'paye' ? 'Payé' : ($item['statut_paiement'] == 'impaye' ? 'Impayé' : '') }}
                                        </span>
                                    </td>

                                    <td>
                                        <span
                                            class="badge bg-{{ $item['statut_reglement'] == 1 ? 'success' : 'danger' }}">
                                            {{ $item['statut_reglement'] == 0 ? 'non effectué' : ($item['statut_reglement'] == 1 ? 'effectué' : '') }}
                                        </span>
                                    </td>

                                    @if (auth()->user()->can('modifier-vente') || auth()->user()->can('supprimer-vente'))
                                        <td class="d-block">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="#" class="dropdown-item remove-item-btn delete"
                                                            data-id={{ $item['id'] }}>
                                                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                            Supprimer
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center m-auto">Aucune vente trouvée dans cette session</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!--end row-->

    @include('backend.pages.vente.dateSessionVente')
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
            $('.btnChoiceDate').click(function() {
                Swal.fire({
                    title: 'Veuillez choisir une date de session de vente avant d\'effectuer une vente',
                    // text: "Vous êtes sur le point de clôturer la caisse. Cette action est irréversible.",
                    icon: 'warning',
                    // showCancelButton: true,
                    // confirmButtonColor: '#3085d6',
                    // cancelButtonColor: '#d33',
                    // confirmButtonText: 'Oui, clôturer la caisse',
                    // cancelButtonText: 'Annuler'
                })
            })


            // Vérifiez si la DataTable est déjà initialisée
            if ($.fn.DataTable.isDataTable('#buttons-datatables')) {
                // Si déjà initialisée, détruisez l'instance existante
                $('#buttons-datatables').DataTable().destroy();
            }

            // Initialisez la DataTable avec les options et le callback
            var table = $('#buttons-datatables').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'print'
                ],

                // Utilisez drawCallback pour exécuter delete_row après chaque redessin
                drawCallback: function(settings) {
                    var route = "vente"
                    delete_row(route);
                }
            });



            // $('.btnCloturer').click(function(e) {
            //     e.preventDefault();
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
            //                 title: 'Caisse cloturée avec succès',
            //                 text: 'Déconnexion automatique.',
            //                 icon: 'success',
            //                 timer: 2000,
            //                 timerProgressBar: true,
            //                 didOpen: () => {
            //                     Swal.showLoading()
            //                 },
            //                 willClose: () => {
            //                     window.location.href =
            //                         '{{ route('vente.cloture-caisse') }}';
            //                 }
            //             }).then((result) => {
            //                 if (result.dismiss === Swal.DismissReason.timer) {
            //                     console.log(
            //                         'Redirection automatique vers la page de connexion');
            //                 }
            //             });
            //         }
            //     });
            // });
        })
    </script>
@endsection
