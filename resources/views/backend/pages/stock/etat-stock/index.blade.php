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
            Gestion de stock
        @endslot
        @slot('title')
            État des stocks
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0 filter">Etat du stock
                        @if (request()->has('statut'))
                            - <b>{{ request('statut') }}</b>
                        @endif
                        @if (request()->has('filter'))
                            - <b>{{ request('filter') }}</b>
                        @endif
                    </h5>

                    <div class="dropdown">
                        {{-- <button class="btn btn-primary">Suivi de stock</button> --}}

                        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class=" ri ri-filter-2-fill"></i> Filtrer par categorie
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="/admin/etat-stock?filter=Restaurant">Restaurant</a></li>
                            <li><a class="dropdown-item" href="/admin/etat-stock?filter=Bar">Bar</a></li>
                            <li><a class="dropdown-item" href="/admin/etat-stock">Toutes les categories</a></li>
                        </ul>
                    </div>
                </div>


                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Nom</th>
                                    <th>Catégorie</th>
                                    <th>Stock actuel</th>
                                    <th>Stock alerte</th>
                                    <th>Statut du stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produits as $key => $produit)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>

                                            <img class="rounded avatar-sm"
                                                src="{{ $produit->hasMedia('ProduitImage') ? $produit->getFirstMediaUrl('ProduitImage') : asset('assets/img/logo/logo_Chez-jeanne.jpg') }}"
                                                width="50px" alt="{{ $produit['nom'] }}">
                                        </td>
                                        <td>{{ $produit->nom }}
                                            <p> {{ $produit['valeur_unite'] ?? '' }}
                                                {{ $produit['unite']['libelle'] ?? '' }}
                                                {{ $produit->unite ? '(' . $produit['unite']['abreviation'] . ')' : '' }}
                                            </p>
                                        </td>
                                        <td>{{ $produit->categorie->name }}</td>
                                        {{-- <td>
                                            {{ $produit->stock }} {{ $produit->uniteSortie?->libelle ?? '' }}
                                            {{ $produit->uniteSortie?->abreviation ? '(' . $produit->uniteSortie?->abreviation . ')' : '' }}
                                        </td> --}}


                                        @if ($produit->categorie->famille == 'bar')
                                            <td>
                                                <ol class="list-unstyled mb-0">
                                                    @php
                                                        $bouteille = $produit->variantes
                                                            ->where('libelle', 'Bouteille')
                                                            ->first();
                                                        $verre = $produit->variantes
                                                            ->where('libelle', 'Verre')
                                                            ->first();
                                                        $ballon = $produit->variantes
                                                            ->where('libelle', 'Ballon')
                                                            ->first();

                                                        $bouteilles_restantes = 0;
                                                        $verres_restants = 0;
                                                        $ballons_restants = 0;

                                                        if ($bouteille) {
                                                            $qte_disponible =
                                                                $bouteille->pivot->quantite_disponible ?? 0;
                                                            $bouteilles_restantes = floor($qte_disponible);
                                                            $partie_decimale = $qte_disponible - $bouteilles_restantes;

                                                            if ($verre) {
                                                                $verres_par_bouteille = $verre->pivot->quantite ?? 0;
                                                                $verres_restants = round(
                                                                    $partie_decimale * $verres_par_bouteille,
                                                                    2,
                                                                );
                                                            } elseif ($ballon) {
                                                                $ballons_par_bouteille = $ballon->pivot->quantite ?? 0;
                                                                $ballons_restants = round(
                                                                    $partie_decimale * $ballons_par_bouteille,
                                                                    2,
                                                                );
                                                            }
                                                        }
                                                    @endphp

                                                    <li>
                                                        @if ($bouteilles_restantes > 0)
                                                            {{ $bouteilles_restantes }} bouteille(s)
                                                        @endif

                                                        @if ($verres_restants > 0)
                                                            @if ($bouteilles_restantes > 0)
                                                                &
                                                            @endif
                                                            {{ floor($verres_restants) }} verre(s)
                                                        @endif

                                                        @if ($ballons_restants > 0)
                                                            @if ($bouteilles_restantes > 0 || $verres_restants > 0)
                                                                &
                                                            @endif
                                                            {{ floor($ballons_restants) }} ballon(s)
                                                        @endif

                                                        @if ($bouteilles_restantes == 0 && $verres_restants == 0 && $ballons_restants == 0)
                                                            0
                                                        @endif
                                                    </li>


                                                </ol>


                                            </td>
                                        @else
                                            <td><b>{{ $produit['stock'] }}</b>
                                                {{ $produit['uniteSortie']['abreviation'] ?? '' }}</td>
                                            </td>
                                        @endif

                                        <td>
                                            {{ $produit->stock_alerte }} {{ $produit->uniteSortie?->libelle ?? '' }}
                                            {{ $produit->uniteSortie?->abreviation ? '(' . $produit->uniteSortie?->abreviation . ')' : '' }}
                                        </td>

                                        <td>
                                            @if ($produit->stock <= $produit->stock_alerte)
                                                <span class="badge bg-danger">Alerte</span>
                                            @else
                                                <span class="badge bg-success">Normal</span>
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



    <script>
        $(document).ready(function() {
            // Détruire DataTable s’il existe déjà
            if ($.fn.DataTable.isDataTable('#buttons-datatables')) {
                $('#buttons-datatables').DataTable().destroy();
            }


            // Réinitialiser DataTable
            $('#buttons-datatables').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'print', 'pdf', 'csv', 'copy', 'excel'
                ],
                buttons: [{
                        extend: 'print',
                        text: 'Imprimer',
                        exportOptions: {
                            columns: [0, 2, 3, 4]
                        },
                        messageTop: function() {
                            return $('.filter').text().trim();
                        },
                        title: '',
                        customize: function(win) {
                            $(win.document.body).css('text-align', 'center');
                            $(win.document.body).find('h1').css('text-align',
                                'center');
                        }
                    },
                    // {
                    //     extend: 'pdf',
                    //     text: 'pdf',
                    //     exportOptions: {
                    //         columns: [0, 2, 3, 4]
                    //     },
                    //     messageTop: function() {
                    //         return $('.filter').text().trim();
                    //     },
                    //     title: '',
                    //     customize: function(win) {
                    //         $(win.document.body).css('text-align', 'center');
                    //         $(win.document.body).find('h1').css('text-align',
                    //             'center');
                    //     }
                    // },

                    {
                        extend: 'csv',
                        text: 'Csv',
                        exportOptions: {
                            columns: [0, 2, 3, 4]
                        },
                        messageTop: function() {
                            return $('.filter').text().trim();
                        },
                        title: '',
                        // customize: function(win) {
                        //     $(win.document.body).css('text-align', 'center');
                        //     $(win.document.body).find('h1').css('text-align',
                        //         'center');
                        // }
                    },

                    // {
                    //     extend: 'copy',
                    //     text: 'copier',
                    //     exportOptions: {
                    //         columns: [0, 2, 3, 4]
                    //     },
                    //     messageTop: function() {
                    //         return $('.filter').text().trim();
                    //     },
                    //     title: '',
                    //     customize: function(win) {
                    //         $(win.document.body).css('text-align', 'center');
                    //         $(win.document.body).find('h1').css('text-align',
                    //             'center');
                    //     }
                    // },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns: [0, 2, 3, 4]
                        },
                        messageTop: function() {
                            return $('.filter').text().trim();
                        },
                        title: '',
                        // customize: function(win) {
                        //     $(win.document.body).css('text-align', 'center');
                        //     $(win.document.body).find('h1').css('text-align',
                        //         'center');
                        // }
                    }

                ],
              
            });
          

        });
    </script>
@endsection
