@extends('backend.layouts.master')
@section('title')
    {{-- @lang('translation.datatables') --}}
    Dépenses
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
            Dépenses
        @endslot
        @slot('title')
            Liste des dépenses
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('depense.index') }}" method="GET">
                @csrf
                <div class="row mx-3">
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="statut" class="form-label">Categorie</label>
                            <select class="form-select" id="categorie" name="categorie">
                                <option value="">Toutes les depenses</option>
                                @foreach (App\Models\CategorieDepense::get() as $key => $value)
                                    <option value="{{ $value->id }}"
                                        {{ request('categorie') == $value->id ? 'selected' : '' }}>
                                        {{ $value->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="date_debut" class="form-label">Date de début</label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut"
                                value="{{ request('date_debut') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="date_fin" class="form-label">Date de fin</label>
                            <input type="date" class="form-control" id="date_fin" name="date_fin"
                                value="{{ request('date_fin') }}">
                        </div>
                    </div>

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
                    <div class="col-md-2 mt-4">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                    </div>

                </div>

            </form>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 id="filter" class="card-title mb-0" style="text-align: center">Liste des dépenses

                        @if (request()->has('categorie') && request('categorie') != null)
                            -
                            <strong>{{ ucfirst(App\Models\CategorieDepense::find(request('categorie'))->libelle) }}</strong>
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
                    </h5>
                    <a href="{{ route('depense.create') }}" class="btn btn-primary">
                        Créer une dépense
                    </a>
                </div>
                <div class="card-body divPrint">

                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Catégorie</th>
                                    <th>Libellé</th>
                                    <th>Montant</th>
                                    <th>Créé par</th>
                                    <th>Date depense</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_depense as $key => $item)
                                    <tr id="row_{{ $item['id'] }}">
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $item['categorie_depense']['libelle'] ?? '' }}</td>
                                        <td>{{ $item['libelle_depense']['libelle'] ?? $item['categorie_depense']['libelle'] }}
                                        </td>
                                        <td>{{ number_format($item['montant'], 0, ',', ' ') }}</td>
                                        <td>{{ $item['user']['first_name'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item['date_depense'])->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">

                                                    @if ($item['categorie_depense']['slug'] != 'achats')
                                                        <li>
                                                            <a class="dropdown-item edit-item-btn" href="#"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#myModalEdit{{ $item['id'] }}">
                                                                <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                                Modifier
                                                            </a>
                                                        </li>
                                                    @endif

                                                    <li>
                                                        <a class="dropdown-item remove-item-btn delete" href="#"
                                                            data-id="{{ $item['id'] }}">
                                                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                            Supprimer
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @include('backend.pages.depense.edit')
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total des dépenses :</th>
                                    <th>{{ number_format($data_depense->sum('montant'), 0, ',', ' ') }}</th>
                                    <th colspan="3"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
            {{-- <button id="btnImprimer" class="w-100"><i class="ri ri-printer-fill"></i></button> --}}

        </div>
    </div>

    {{-- @include('backend.pages.depense.create') --}}
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
                buttons: [{
                    extend: 'print',
                    text: 'Imprimer',
                    // className: 'btn btn-danger',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    },
                    messageTop: function() {
                        return $('#filter').text().trim();
                    },
                    title: '',
                    customize: function(win) {
                        $(win.document.body)
                            .css('text-align', 'center');

                        $(win.document.body).find('h1')
                            .css('text-align', 'center');
                    }
                }],


                // Utilisez drawCallback pour exécuter delete_row après chaque redessin
                drawCallback: function(settings) {
                    var route = "depense"
                    delete_row(route);
                }
            });

            // Imprimer la liste des dépenses


            function imprimerRapport() {
                // Créer une nouvelle fenêtre pour l'impression
                var fenetreImpression = window.open('', '_blank');

                // Contenu à imprimer
                var contenuImprimer = `
                    <html>
                        <head>
                            <title style="text-align: center;">Rapport de Vente</title>
                            <style>
                                body { font-family: Arial, sans-serif; }
                                table { width: 100%; border-collapse: collapse; }
                                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                                th { background-color: #f2f2f2; }
                            </style>
                        </head>
                        <body>
                            <h2 style="text-align: center;">Rapport de Vente</h2>
                            ${$('.divPrint').html()}
                           
                        </body>
                    </html>
                `;

                // <footer style="position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px; margin-top: 20px;">
                //                 <p>Imprimé le : ${new Date().toLocaleString()} par {{ Auth::user()->first_name }}</p>
                //             </footer>

                // Écrire le contenu dans la nouvelle fenêtre
                fenetreImpression.document.write(contenuImprimer);

                // Fermer le document
                fenetreImpression.document.close();

                // Imprimer la fenêtre
                fenetreImpression.print();
            }

            // Ajouter un bouton d'impression
            $('#btnImprimer')
                .text('Imprimer le Rapport')
                .addClass('btn btn-primary mt-3')
                .on('click', imprimerRapport);
            // .appendTo('.divPrint');

        });
    </script>
@endsection
