@extends('backend.layouts.master')
@section('title')
   Activity Logs
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
            Paramètres
        @endslot
        @slot('title')
            Journaux d'activité
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Journaux d'activité</h5>
                    <button type="button" class="btn btn-danger" id="clearAllLogs">
                        <i class="ri-delete-bin-line"></i> Vider tous les logs
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>Description</th>
                                    <th>Utilisateur</th>
                                    <th>Sujet</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $key => $log)
                                    <tr id="row_{{ $log->id }}">
                                        <td>{{ ++$key }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($log->event == 'created') bg-success
                                                @elseif($log->event == 'updated') bg-warning
                                                @elseif($log->event == 'deleted') bg-danger
                                                @else bg-info
                                                @endif">
                                                {{ ucfirst($log->event) }}
                                            </span>
                                        </td>
                                        <td>{{ $log->description }}</td>
                                        <td>
                                            @if($log->causer)
                                                {{ $log->causer->first_name ?? 'Utilisateur inconnu' }}
                                            @else
                                                <span class="text-muted">Système</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->subject)
                                                {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="#" class="dropdown-item view-details" 
                                                           data-properties="{{ json_encode($log->properties) }}"
                                                           data-bs-toggle="modal" data-bs-target="#detailsModal">
                                                            <i class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                            Détails
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="dropdown-item remove-item-btn delete"
                                                            data-id="{{ $log->id }}">
                                                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                            Supprimer
                                                        </a>
                                                    </li>
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

    <!-- Modal Détails -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Détails de l'activité</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <pre id="activityDetails"></pre>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        $(document).ready(function(){
            var route = "activity-logs";
            delete_row(route);

            // Afficher les détails de l'activité
            $('.view-details').on('click', function() {
                var properties = $(this).data('properties');
                $('#activityDetails').text(JSON.stringify(properties, null, 2));
            });

            // Vider tous les logs
            $('#clearAllLogs').on('click', function() {
                if (confirm('Êtes-vous sûr de vouloir supprimer tous les logs ?')) {
                    $.ajax({
                        url: '{{ route("activity-logs.clear-all") }}',
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                location.reload();
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
