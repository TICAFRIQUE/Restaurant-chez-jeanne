@extends('backend.layouts.master')
@section('title')
    Historiques
@endsection
@section('css')
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            Rapports
        @endslot
        @slot('title')
            Historiques
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filtres</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('rapport.historique') }}" method="GET">
                        @csrf
                        <div class="row mx-2">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="categorie" class="form-label">Produits</label>
                                    <select name="produit" class="form-control js-example-basic-single" required>
                                        <option value="">Sélectionnez un produit</option>
                                        @foreach ($data_produit as $produit)
                                            <option value="{{ $produit->id }}" @selected(request('produit') == $produit->id)>
                                                {{ $produit->libelle != null ? $produit->libelle : $produit->nom }}
                                                {{ $produit->valeur_unite ?? '' }}
                                                {{ $produit->unite ? '(' . $produit->unite->abreviation . ')' : '' }}

                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="categorie" class="form-label">Type</label>
                                    <select name="type" class="form-control" required>
                                        <option value="">Sélectionnez un type</option>
                                        <option value="vente" @selected(request('type') == 'vente')>Vente</option>
                                        <option value="sortie" @selected(request('type') == 'sortie')>Sortie</option>
                                        <option value="achat" @selected(request('type') == 'achat')>Achat</option>
                                        <option value="inventaire" @selected(request('type') == 'inventaire')>Inventaire</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="date_debut" class="form-label">Date de début</label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut"
                                        value="{{ request('date_debut') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="date_fin" class="form-label">Date de fin</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin"
                                        value="{{ request('date_fin') }}">
                                </div>
                            </div>
                            <div class="col-md-2 mt-4">
                                <button type="submit" class="btn btn-primary w-100"> <i class="ri-filter-3-line"></i>
                                    Filtrer</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    @if (request('type'))
                        <h5 class="card-title mb-0">{{ request('type') }} - Produit
                            {{ \App\Models\Produit::find(request('produit'))->libelle != null ? \App\Models\Produit::find(request('produit'))->libelle : \App\Models\Produit::find(request('produit'))->nom }}

                            @if (request('date_debut') && request('date_fin'))
                                du
                                {{ \Carbon\Carbon::parse(request('date_debut'))->format('d/m/Y') }}
                                au {{ \Carbon\Carbon::parse(request('date_fin'))->format('d/m/Y') }}
                            @endif
                        </h5>
                    @else
                        <h5 class="card-title mb-0">Produit en attente de filtre</h5>
                    @endif

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!-- Afficher les produits ici en fonction du type-->
                        @if (request('type') == 'vente')
                            @include('backend.pages.rapport.partials.historique.vente')
                        @elseif (request('type') == 'achat')
                            @include('backend.pages.rapport.partials.historique.achat')
                        @elseif (request('type') == 'sortie')
                            @include('backend.pages.rapport.partials.historique.sortie')
                        @elseif (request('type') == 'inventaire')
                            @include('backend.pages.rapport.partials.historique.inventaire')
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
