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



    <div class="row">

        <!-- ========== Start filtre ========== -->
        @if (!auth()->user()->hasRole(['caisse', 'supercaisse']))
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

                    <div class="col-md-3">
                        <label for="date_debut" class="form-label">Date de début</label>
                        <input type="date" value="{{ request('date_debut') }}" class="form-control" id="date_debut"
                            name="date_debut">
                    </div>
                    <div class="col-md-3">
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

                    <div class="col-md-2 mt-4">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                    </div>
                </div>
            </form>
        @endif
        <!-- ========== End filtre ========== -->




        @if (Auth::user()->hasRole(['caisse', 'supercaisse']))
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
        @endif










        <div class="card">
            <div class="card-header d-flex justify-content-between">


                <!-- ========== Start filter result libellé ========== -->
                <h5 class="card-title mb-0 filter" style="text-align: center">Liste des ventes
                    @if (request('date_debut') || request('date_fin') || request('caisse') || request('periode'))



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

                </h5>
                <!-- ========== End filter result libellé ========== -->


                @if (auth()->user()->hasRole(['caisse', 'supercaisse']))
                    <!-- ========== Start cloture caisse button ========== -->
                    @if ($data_vente->sum('montant_total') > 0)
                        <a href="{{ route('vente.billeterie-caisse') }}" class="btn btn-danger btn-lg ">Clôturer la caisse
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
                @endif




            </div>

            @if (auth()->user()->hasRole(['caisse', 'supercaisse']))
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Caisse actuelle</h5>
                                <p class="card-text h3 text-primary">
                                    {{ auth()->user()->caisse->libelle ?? 'Non définie' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body d-flex justify-content-around">
                                <h5 class="card-title">Total des ventes du jour : <br> <strong
                                        class="text-primary fs-3">{{ number_format($data_vente->sum('montant_total'), 0, ',', ' ') }}
                                        FCFA</strong> </h5>
                                <p class="card-text h3 text-success">


                                    {{-- @if ($data_vente->sum('montant_total') > 0)
                                        <a href="{{ route('vente.billeterie-caisse') }}" class="btn btn-danger ">Procéder a
                                            la Clóturer
                                            la caisse</a>
                                    @else
                                        <button class="btn btn-danger" disabled>Procéder a la Clóturer la
                                            caisse</button>
                                    @endif --}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @include('backend.components.alertMessage')




            <div class="card-body">
                <div class="table-responsive">
                    <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>N° de vente</th>
                                {{-- <th>Type de vente</th> --}}
                                <th>N° de Table</th>
                                <th>Session vente</th>
                                <th>Montant</th>
                                <th>Vendu le</th>
                                <th>Vendu par</th>
                                <th>Caisse</th>
                                <th>Statut</th>
                                @if (auth()->user()->can('modifier-vente') || auth()->user()->can('supprimer-vente'))
                                    <th>Action</th>
                                @endif


                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_vente as $key => $item)
                                <tr id="row_{{ $item['id'] }}">
                                    <td> {{ $loop->iteration }} </td>
                                    <td> <a class="fw-bold"
                                            href="{{ route('vente.show', $item->id) }}">#{{ $item['code'] }}</a> </td>
                                    {{-- <td> {{ $item['type_vente'] }}

                                        @if ($item['type_vente'] == 'commande')
                                            <br> <a href="{{ route('commande.show', $item['commande_id']) }}"
                                                class="text-primary fw-bold">#{!! $item['commande']['code'] !!}</a>
                                        @endif
                                    </td> --}}
                                    <td> {{ $item['numero_table'] ?? 'non défini' }}</td>
                                    <td> {{ \Carbon\Carbon::parse($item['date_vente'])->format('d-m-Y') }}
                                        {{ $item['created_at']->format('à H:i') }} </td>
                                    <td> {{ number_format($item['montant_total'], 0, ',', ' ') }} FCFA </td>
                                    <td> {{ $item['created_at']->format('d-m-Y à H:i') }} </td>
                                    <td> {{ $item['user']['first_name'] }} {{ $item['user']['last_name'] }} </td>
                                    <td> {{ $item['caisse']['libelle'] ?? '' }} </td>
                                    <td> <span class="badge bg-{{ $item['statut_paiement'] == 'paye' ? 'success' : 'danger' }}">
                                        {{ $item['statut_paiement'] == 'paye' ? 'Payé' : ($item['statut_paiement'] == 'impaye' ? 'Impayé' : '')  }}
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
                                    <td colspan="6" class="text-center">Aucune vente trouvée</td>
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
