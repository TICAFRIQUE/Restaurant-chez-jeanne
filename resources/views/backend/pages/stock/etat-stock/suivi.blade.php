@extends('backend.layouts.master')
@section('title')
    État des stocks
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
            Suivi de stock
        @endslot
        @slot('title')
            Suivi des stocks
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Suivi de stock depuis le dernier inventaire
                        [{{ \Carbon\Carbon::parse($date_dernier_inventaire)->format('d/m/Y') }} -
                        {{ $date_jour->format('d/m/Y') }}]
                        {{-- @if (request()->has('statut'))
                            - <b>{{ request('statut') }}</b>
                        @endif
                        @if (request()->has('filter'))
                            - <b>{{ request('filter') }}</b>
                        @endif --}}
                    </h5>

                    {{-- <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class=" ri ri-filter-2-fill"></i> Filtrer par categorie
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="/admin/etat-stock?filter=Restaurant">Restaurant</a></li>
                            <li><a class="dropdown-item" href="/admin/etat-stock?filter=Bar">Bar</a></li>
                            <li><a class="dropdown-item" href="/admin/etat-stock">Toutes les categories</a></li>
                        </ul>
                    </div> --}}
                </div>


                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Catégorie</th>
                                    <th>Stock existante</th>
                                    <th>Stock ajouté</th>
                                    <th>Stock total</th>
                                    <th>Qté vendu ou utilisée</th>
                                    <th>Stock restant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>

                                {{-- @php
                                    if (!function_exists('afficherStockConvertirAvecVariantes')) {
                                        function afficherStockConvertirAvecVariantes(
                                            $valeur,
                                            $unite = '',
                                            $variantes = null,
                                        ) {
                                            $signe = $valeur < 0 ? '-' : '';
                                            $valeur = abs($valeur);

                                            $entier = floor($valeur); // bouteilles pleines
                                            $decimal = $valeur - $entier; // partie décimale
                                            $texte = '';

                                            if ($decimal == 0) {
                                                if ($entier > 0) {
                                                    $texte .= $entier . ' ' . $unite;
                                                }
                                            } else {
                                                if ($entier > 0) {
                                                    $texte .= $entier . ' ' . $unite;
                                                }

                                                if ($variantes) {
                                                    $verre = $variantes->where('libelle', 'Verre')->first();
                                                    $ballon = $variantes->where('libelle', 'Ballon')->first();

                                                    if ($verre && ($verre->pivot->quantite ?? 0) > 0) {
                                                        $verres_par_bouteille = $verre->pivot->quantite;
                                                        $verres = round($decimal * $verres_par_bouteille, 2);
                                                        if (floor($verres) > 0) {
                                                            $texte .=
                                                                ($texte ? ' et ' : '') . floor($verres) . ' verre(s)';
                                                        }
                                                    } elseif ($ballon && ($ballon->pivot->quantite ?? 0) > 0) {
                                                        $ballons_par_bouteille = $ballon->pivot->quantite;
                                                        $ballons = round($decimal * $ballons_par_bouteille, 2);
                                                        if (floor($ballons) > 0) {
                                                            $texte .=
                                                                ($texte ? ' et ' : '') . floor($ballons) . ' ballon(s)';
                                                        }
                                                    }
                                                }
                                            }

                                            return $texte ? $signe . $texte : '0';
                                        }
                                    }
                                @endphp --}}
                                

                                @foreach ($data_produit as $key => $produit)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>

                                        <td>{{ $produit->nom }}
                                            <span class="badge bg-info">{{ $produit->variante ? $produit->variante->libelle  : '' }}</span>
                                            <p>
                                                {{ $produit['valeur_unite'] ?? '' }}
                                                {{ $produit->unite ? '(' . $produit->unite->abreviation . ')' : '' }}
                                            </p>
                                        </td>
                                        <td>{{ $produit->categorie->name }}</td>
                                        <td>
                                             {{ $produit->inventaires->first()->pivot->stock_physique ?? 0 }}
                                        </td>
                                        <td>
                                           {{ $produit->stock_initial }}
                                        </td>
                                        <td>
                                            @php
                                                // Calcul du stock total
                                                // Stock total = Stock physique (dernier inventaire) + Stock initial (ajouté depuis le dernier inventaire)
                                                $stock_total =
                                                    ($produit->inventaires->first()->pivot->stock_physique ?? 0) +
                                                    $produit->stock_initial;
                                            @endphp
                                             {{ $stock_total }}
                                        </td>
                                        <td>
                                            @php
                                                // Quantité vendue ou utilisée depuis le dernier inventaire
                                                // $quantite_vendue = $produit->quantite_vendue ?? ($produit->quantite_utilisee ?? 0);
                                                $quantite_vendue =
                                                    $produit->quantite_vendue ?? ($produit->quantite_utilisee ?? 0);
                                            @endphp
                                           {{ $quantite_vendue }}

                                        </td>
                                        <td>

                                            {{ $produit->stock }}
                                        </td>
                                        <td>

                                            @php
                                                $stock_physique =
                                                    $produit->inventaires->first()->pivot->stock_physique ?? 0;
                                                $quantite_utilisee =
                                                    $produit->quantite_vendue ?? ($produit->quantite_utilisee ?? 0);
                                                $stock_calcule =
                                                    $stock_physique + $produit->stock_initial - $quantite_utilisee;
                                                // Arrondir à 2 décimales pour éviter les problèmes de précision flottante
                                                $stock_calcule = round($stock_calcule, 2);
                                            @endphp

                                            @if ($produit->stock == $stock_calcule)
                                                <span class="badge bg-success">Normal</span>
                                            @else
                                                <span class="badge bg-danger">Alerte</span>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
