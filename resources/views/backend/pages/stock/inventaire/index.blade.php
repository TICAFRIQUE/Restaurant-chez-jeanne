@extends('backend.layouts.master')
@section('title')
    Inventaire
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
            Liste des inventaires
        @endslot
        @slot('title')
            Gestion de stock
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            @include('backend.components.alertMessage')
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Liste des inventaires</h5>
                    <div class="d-flex ms-3">
                        <!-- Dropdown Fiche de Produit -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Fiche de produit <i class="ri ri-printer-fill"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item"
                                        href="{{ route('inventaire.fiche-inventaire', ['type' => 'bar']) }}">Produit Bar</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('inventaire.fiche-inventaire', ['type' => 'restaurant']) }}">Produit
                                        Restaurant</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('inventaire.fiche-inventaire', ['type' => 'tous']) }}">Tous les
                                        Produits</a></li>
                            </ul>
                        </div>

                        <!-- Bouton Nouvel Inventaire -->
                        <a href="{{ route('inventaire.create') }}" class="btn btn-primary ms-2">
                            Nouvel inventaire
                        </a>
                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>N° d'inventaire</th>
                                    <th>Mois d'inventaire</th>
                                    <th>Date de création </th>
                                    <th>Crée par</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_inventaire as $key => $item)
                                  
                                    <tr id="row_{{ $item['id'] }}">
                                        <td> {{ ++$key }} </td>
                                        <td> <a class="fw-bold"
                                                href="{{ route('inventaire.show', $item->id) }}">#{{ $item['code'] }}</a>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($item['date_inventaire'])->subMonth()->isoFormat('MMMM YYYY') }}
                                        </td>
                                        <td> {{ \Carbon\Carbon::parse($item['created_at'])->format('d-m-Y à H:i') }} </td>
                                        <td> {{ $item['user']['first_name'] }} </td>
                                            <!-- Actions si nécessaire -->
                                        <td class="d-block">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    {{-- <li><a href="{{ route('ajustement.create', $item['id']) }}"
                                                            class="dropdown-item"><i
                                                                class=" ri-exchange-fill align-bottom me-2 text-muted"></i>
                                                            Ajustement</a>
                                                    </li> --}}
                                                    <li><a href="{{ route('inventaire.show', $item->id) }}"
                                                            class="dropdown-item"><i
                                                                class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                            Details</a>
                                                    </li>
                                                    {{-- <li><a href="{{ route('achat.edit', $item['id']) }}" type="button"
                                                            class="dropdown-item edit-item-btn"><i
                                                                class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                            Edit</a></li> --}}


                                                    @role('developpeur')
                                                        <li>
                                                            <a href="#" class="dropdown-item remove-item-btn delete"
                                                                data-id={{ $item['id'] }}>
                                                                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                                Supprimer
                                                            </a>
                                                        </li>
                                                    @endrole
                                                </ul>
                                            </div>
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

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
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
                    var route = "inventaire"
                    delete_row(route);
                }
            });



        })
    </script>
@endsection
