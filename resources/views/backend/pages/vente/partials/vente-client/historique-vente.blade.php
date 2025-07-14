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
            Liste des vente impayées clients
        @endslot
        @slot('title')
            Clients
        @endslot
    @endcomponent



    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 filter text-center">Historique des ventes impayées de {{ $client->first_name }}
                        {{ $client->last_name }}</h5>

                </div>
                <div class="card-body divPrint">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    {{-- <th>Statut de vente</th> --}}
                                    <th>N° de vente</th>
                                    <th>N° de Table</th>
                                    <th>Session vente</th>
                                    <th>Montant</th>
                                    <th>montant payé</th>
                                    <th>Montant restant</th>
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
                                @forelse ($ventes as $key => $item)
                                    <tr id="row_{{ $item['id'] }}">

                                        <td> {{ $loop->iteration }} <i
                                                class="ri ri-checkbox-blank-circle-fill text-success"></i> </td>
                                        {{-- <td> <span
                                            class="badge bg-{{ $item['statut'] == 'en attente' ? 'warning' : 'success' }}">{{ $item['statut'] }}</span>
                                    </td> --}}
                                        <td> <a class="fw-bold"
                                                href="{{ route('vente.show', $item->id) }}">#{{ $item['code'] }}</a> </td>

                                        <td> {{ $item['numero_table'] ?? 'non défini' }}</td>
                                        <td> {{ \Carbon\Carbon::parse($item['date_vente'])->format('d-m-Y') }}
                                            {{ $item['created_at']->format('à H:i') }} </td>
                                        <td> {{ number_format($item['montant_total'], 0, ',', ' ') }} FCFA </td>
                                        <td> {{ number_format($item['montant_recu'], 0, ',', ' ') }} FCFA </td>
                                        <td> {{ number_format($item['montant_restant'], 0, ',', ' ') }} FCFA </td>
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
                                                                <i
                                                                    class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
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
                                        <td colspan="6" class="text-center m-auto">Aucune vente trouvée dans cette
                                            session
                                        </td>
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
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>
    <!-- pdfmake (PDF export) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Vérifier si DataTable est déjà initialisé et le détruire
            // pour éviter les conflits lors de la réinitialisation
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
                            columns: [0, 1, 2, 3, 4, 5]
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
                    {
                        extend: 'pdf',
                        text: 'Pdf',
                        className: 'btn btn-danger',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                        },
                        messageTop: function() {
                            return $('.filter').text().trim();
                        },
                      
                    },

                    {
                        extend: 'csv',
                        text: 'Csv',
                        className: 'btn btn-danger',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
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
                            columns: [0, 1, 2, 3, 4, 5]
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
                            columns: [0, 1, 2, 3, 4, 5]
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
                    let route = "vente";
                    if (typeof delete_row === "function") {
                        delete_row(route);
                    }
                }
            });
        })
    </script>
@endsection
