@extends('backend.layouts.master')
@section('title')
    Produit
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
            Liste des produits
        @endslot
        @slot('title')
            produit
        @endslot
    @endcomponent



    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0 filter">Liste des produits
                        @if (request()->has('filter'))
                            - <b>{{ request('filter') }}</b>
                        @endif
                    </h5>


                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class=" ri ri-filter-2-fill"></i> Filtrer par categorie
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="/admin/produit?filter=Restaurant">Restaurant</a></li>
                            <li><a class="dropdown-item" href="/admin/produit?filter=Bar">Bar</a></li>
                            <li><a class="dropdown-item" href="/admin/produit">Toutes les categories</a></li>
                        </ul>
                    </div>

                    @can('creer-produit')
                        <a href="{{ route('produit.create') }}" type="button" class="btn btn-primary ">Créer
                            un produit</a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Statut</th>
                                    <th>Code</th>
                                    <th>Image</th>
                                    <th>Nom</th>
                                    <th>Categorie (Famille)</th>
                                    {{-- <th>Type de produit</th> --}}
                                    <th>Stock</th>
                                    <th>Stock alerte</th>
                                    <th>Date creation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_produit as $key => $item)
                                    <tr id="row_{{ $item['id'] }}">
                                        <td> {{ ++$key }} </td>
                                        <td>
                                            <span
                                                class="badge{{ $item->statut == 'desactive' ? ' bg-danger' : ' bg-success' }}">{{ $item['statut'] }}</span>
                                        </td>

                                        <td>{{ $item['code'] }}</td>
                                        <td>
                                            <img class="rounded avatar-sm"
                                                src="{{ $item->hasMedia('ProduitImage') ? $item->getFirstMediaUrl('ProduitImage') : asset('assets/img/logo/logo_Chez-jeanne.jpg') }}"
                                                width="50px" alt="{{ $item['nom'] }}">
                                        </td>
                                        <td>{{ $item['nom'] }}
                                            <p> {{ $item['valeur_unite'] ?? '' }}
                                                {{ $item['unite']['libelle'] ?? '' }}</p>

                                            {{-- <p>{{ $item['format']['libelle'] ?? '' }} de
                                                {{ $item['valeur_format'] ?? '' }}</p> --}}

                                        </td>
                                        <td>{{ $item['categorie']['famille'] ?? '' }}({{ $item['categorie']['name'] ?? '' }})
                                        </td>
                                        {{-- <td>{{ $item['typeProduit']['name'] }}  </td> --}}
                                        @if ($item->categorie->famille == 'bar')
                                            <td>
                                                {{-- <ol class="list-unstyled mb-0">
                                                    @foreach ($item->variantes->sortBy('libelle') as $variantes)
                                                        <li>{{ $variantes->libelle }} :
                                                            <b> {{ $variantes->pivot->quantite_disponible }}</b>
                                                        </li>
                                                    @endforeach
                                                </ol> --}}

                                                <ol class="list-unstyled mb-0">
                                                    @foreach ($item->variantes->sortBy('libelle') as $variante)
                                                        @php
                                                            $qte = $variante->pivot->quantite_disponible;
                                                            $entier = floor($qte);
                                                            $decimal = $qte - $entier;
                                                            $ml = intval($decimal * 1000);
                                                        @endphp
                                                        <li>
                                                            {{ $variante->libelle }} :
                                                            <b>
                                                                @if ($decimal == 0)
                                                                    {{ $entier }}
                                                                  
                                                                @elseif ($entier > 0)
                                                                    {{ $entier }}
                                                                     et
                                                                    {{ $ml }} ml
                                                                @else
                                                                    {{ $ml }} ml
                                                                @endif
                                                            </b>
                                                        </li>
                                                    @endforeach
                                                </ol>


                                            </td>
                                        @else
                                            <td><b>{{ $item['stock'] }}</b>
                                                {{ $item['uniteSortie']['abreviation'] ?? '' }}</td>
                                            </td>
                                        @endif



                                        {{-- <td>{{ $item['stock'] }} {{ $item['uniteSortie']['libelle'] ?? '' }}</td> --}}
                                        <td>{{ $item['stock_alerte'] }} {{ $item['uniteSortie']['libelle'] ?? '' }}</td>
                                        <td> {{ $item['created_at'] }} </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @can('voir-produit')
                                                        <li><a href="{{ route('produit.show', $item['id']) }}"
                                                                class="dropdown-item"><i
                                                                    class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                                Detail</a>
                                                        </li>
                                                    @endcan
                                                    @can('modifier-produit')
                                                        <li><a href="{{ route('produit.edit', $item['id']) }}" type="button"
                                                                class="dropdown-item edit-item-btn"><i
                                                                    class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                                Modifier</a></li>
                                                    @endcan
                                                    @can('supprimer-produit')
                                                        <li>
                                                            <a href="#" class="dropdown-item remove-item-btn delete"
                                                                data-id={{ $item['id'] }}>
                                                                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                                Supprimer
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    {{-- @include('backend.pages.produit.edit') --}}
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->

    {{-- @include('backend.pages.produit.create') --}}
@endsection
@section('script')
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>

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
    {{-- <script src="{{URL::asset('myJs/js/delete_row.js')}}"></script> --}}

    <script>
        $(document).ready(function() {

            // // Vérifiez si la DataTable est déjà initialisée
            // if ($.fn.DataTable.isDataTable('#buttons-datatables')) {
            //     // Si déjà initialisée, détruisez l'instance existante
            //     $('#buttons-datatables').DataTable().destroy();
            // }

            // // Initialisez la DataTable avec les options et le callback
            // var table = $('#buttons-datatables').DataTable(
            // {
            //     dom: 'Bfrtip',
            //     buttons: [
            //         'copy', 'csv', 'excel', 'print'
            //     ],

            //     // Utilisez drawCallback pour exécuter delete_row après chaque redessin
            //     drawCallback: function(settings) {
            //         var route = "produit";
            //         delete_row(route);
            //     }
            // });

            // Détruire DataTable s’il existe déjà
            if ($.fn.DataTable.isDataTable('#buttons-datatables')) {
                $('#buttons-datatables').DataTable().destroy();
            }

            // Réinitialiser DataTable
            $('#buttons-datatables').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'print'
                ],
                buttons: [{
                        extend: 'print',
                        text: 'Imprimer',
                        className: 'btn btn-danger',
                        exportOptions: {
                            columns: [0, 2, 4, 5, 6, 8]
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
                    //     text: 'Pdf',
                    //     className: 'btn btn-danger',
                    //     exportOptions: {
                    //         columns: [0, 1, 2, 3, 4, 5]
                    //     },
                    //     messageTop: function() {
                    //         return $('.filter').text().trim();
                    //     },
                    //     title: '',
                    //     // customize: function(win) {
                    //     //     $(win.document.body).css('text-align', 'center');
                    //     //     $(win.document.body).find('h1').css('text-align',
                    //     //         'center');
                    //     // }
                    // },

                    {
                        extend: 'csv',
                        text: 'Csv',
                        className: 'btn btn-danger',
                        exportOptions: {
                            columns: [0, 2, 4, 5, 6, 8]
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

                    {
                        extend: 'copy',
                        text: 'Copy',
                        className: 'btn btn-danger',
                        exportOptions: {
                            columns: [0, 2, 4, 5, 6, 8]
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
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn btn-danger',
                        exportOptions: {
                            columns: [0, 2, 4, 5, 6, 8]
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
                drawCallback: function(settings) {
                    let route = "produit";
                    if (typeof delete_row === "function") {
                        delete_row(route);
                    }
                }
            });



        });
    </script>
@endsection
