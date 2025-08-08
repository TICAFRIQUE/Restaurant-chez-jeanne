@extends('backend.layouts.master')
@section('title')
    Offerts - Gestion des ventes
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
            Liste des offerts
        @endslot
        @slot('title')
            Gestion des offerts
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
            font-size: 1.5rem;
            font-weight: bold;
        }

        /**mettre des animations sur les cartes */
        .carte-vente-anim {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .carte-vente-anim:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .carte.active .card {
            background: #ffffff !important;
            /* jaune clair ou autre */
            color: #0751ba !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            transform: scale(1.05);
        }


        .carte.active .count {
            color: #0751ba !important;

        }



        /*css pour les boutons d'action*/
        .btn-group-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn-lg {
            padding: 0.7rem 1.2rem;
            font-size: 0.9rem;
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




        @php
            // $selectedPeriode = request('periode');
            $selectedDateDebut = request('date_debut');
            $selectedDateFin = request('date_fin');
            // $selectedCaisse = request('caisse');
            $selectedVente = request('vente');
            $selectedStatut = request('statut');


        @endphp

        <form action="{{ route('vente.index') }}" method="GET">
            <div class="row mb-3 d-flex justify-content-center">

               <!-- Filtre pour les utilisateurs non caisse -->
                @unless (auth()->user()->hasRole(['caisse', 'supercaisse']))
                    {{-- <div class="col-md-4">
                        <label for="periode" class="form-label">Période</label>
                        <select class="form-select" id="periode" name="periode">
                            <option value="">Toutes les périodes</option>
                            @foreach (['jour' => 'Jour', 'semaine' => 'Semaine', 'mois' => 'Mois', 'annee' => 'Année'] as $key => $label)
                                <option value="{{ $key }}" {{ $selectedPeriode === $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                    <div class="col-md-3">
                        <label for="date_debut" class="form-label">Date de début</label>
                        <input type="date" id="date_debut" name="date_debut" class="form-control"
                            value="{{ $selectedDateDebut }}">
                    </div>

                    <div class="col-md-3">
                        <label for="date_fin" class="form-label">Date de fin</label>
                        <input type="date" id="date_fin" name="date_fin" class="form-control"
                            value="{{ $selectedDateFin }}">
                    </div>

                     <div class="col-md-3">
                        <label for="date_fin" class="form-label">Code vente</label>
                        <input type="text"  name="vente" class="form-control"
                            value="{{ $selectedVente }}">
                    </div>

                    {{-- <div class="col-md-4">
                        <label for="caisse" class="form-label">Caisse</label>
                        <select class="form-select" id="caisse" name="caisse">
                            <option value="">Toutes les caisses</option>
                            @foreach ($caisses as $caisse)
                                <option value="{{ $caisse->id }}" {{ $selectedCaisse == $caisse->id ? 'selected' : '' }}>
                                    {{ $caisse->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}

                   


                    
                    <div class="col-md-2 d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                        <a href="{{ route('vente.index') }}" class="btn btn-outline-secondary w-100">Réinitialiser</a>
                    </div>
                @endunless

            </div>
        </form>

        <!-- ========== End filtre ========== -->




        <div class="card">
            <!-- ========== Start filter result libellé ========== -->
            <div class="card-header d-flex justify-content-between">


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
                    Liste des offerts
                    @if (request()->filled('statut_paiement') || request()->filled('periode') || request()->filled('date_debut') || request()->filled('date_fin') || request()->filled('caisse') || request()->filled('client') || request()->filled('statut_vente') || request()->filled('statut_reglement'))
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
                            -
                            @if (request('periode') === 'mois')
                                du mois en cours - {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                            @elseif (request('periode') === 'jour')
                                du jour - {{ \Carbon\Carbon::now()->translatedFormat('d/m/Y') }}
                            @elseif (request('periode') === 'semaine')
                                de la semaine en cours -
                                {{ \Carbon\Carbon::now()->startOfWeek()->translatedFormat('d/m/Y') }} au
                                {{ \Carbon\Carbon::now()->endOfWeek()->translatedFormat('d/m/Y') }}
                            @elseif (request('periode') === 'annee')
                                de l'année en cours - {{ \Carbon\Carbon::now()->translatedFormat('Y') }}
                            @else
                                - {{ request('periode') }}
                            @endif
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
                        de toutes les periodes
                    @endif
                </h5>


            </div>
            <!-- ========== End filter result libellé ========== -->

            <div class="card-body tableVente">
                <div class="table-responsive">
                    <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Statut</th>
                                <th>N° de vente</th>
                                <th>produit</th>
                                <th>Crée par</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_offerts as $key => $item)
                                <tr id="row_{{ $item['id'] }}">
                                    <td> {{ ++$key }} </td>
                                    <td>
                                        @if ($item['offert_statut'] === null)
                                            <span class="badge bg-warning">En attente</span>
                                        @elseif ($item['offert_statut'] === 1)
                                            <span class="badge bg-success">Approuvé</span>
                                            <br><small>par {{ $item['userApprouved']['first_name'] ?? '' }}</small>
                                        @elseif ($item['offert_statut'] === 0)
                                            <span class="badge bg-danger">Rejeté</span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('vente.show', $item['vente']['id']) }}"
                                            class="text-decoration-none">
                                            {{ $item['vente']['code'] }}
                                        </a>
                                    <td>
                                        {{ $item['produit']['nom'] }}

                                        * {{ $item['quantite'] }}
                                        {{ $item['variante']['libelle'] ?? '' }} de {{ $item['prix'] }} FCFA
                                    </td>

                                    <td>
                                        {{ $item['vente']['user']['first_name'] }}
                                        -{{ $item['vente']['caisse']['libelle'] ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($item['date_created'])->translatedFormat('d/m/Y H:i') }}
                                    </td>


                                    <td class="d-block">
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">

                                                @if ($item['offert_statut'] === null)
                                                    <li>
                                                        <a href="{{ route('offert.approuvedOffert', ['offert' => $item['id'], 'approuved' => 1]) }}"
                                                            class="dropdown-item remove-item-btn"
                                                            data-id={{ $item['id'] }}>
                                                            <i class="ri-check-line align-bottom me-2 text-muted"></i>
                                                            Approuver
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('offert.approuvedOffert', ['offert' => $item['id'], 'approuved' => 0]) }}"
                                                            class="dropdown-item remove-item-btn"
                                                            data-id={{ $item['id'] }}>
                                                            <i class="ri-close-line align-bottom me-2 text-muted"></i>
                                                            Rejeter
                                                        </a>
                                                    </li>
                                                @endif

                                                {{-- <li>
                                                    <a href="#" class="dropdown-item remove-item-btn delete"
                                                        data-id={{ $item['id'] }}>
                                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                        Supprimer
                                                    </a>
                                                </li> --}}
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center m-auto">Aucun offert trouvé dans cette session
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Vérifiez si la DataTable est déjà initialisée
            if ($.fn.DataTable.isDataTable('#buttons-datatables')) {
                $('#buttons-datatables').DataTable().destroy();
            }

            // Initialisation de la DataTable
            var table = $('#buttons-datatables').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'print'],
                drawCallback: function(settings) {
                    var route = "vente";
                    delete_row(route);
                }
            });

            // Table pour stocker les IDs déjà vus
            let lastIds = [];

            // Fonction pour vérifier les offerts



            function checkOfferts() {
                $.ajax({
                    url: '{{ route('offert.non_approuves') }}',
                    type: 'GET',
                    success: function(response) {
                        const offerts = response.offerts;

                        const newItems = offerts.filter(o => !lastIds.includes(o.id));

                        if (newItems.length > 0) {
                            Swal.fire({
                                position: 'center',
                                icon: 'warning',
                                title: 'Vous avez ' + newItems.length +
                                    ' offerts non approuvés',
                                showConfirmButton: false,
                                timer: 5000
                            });
                        }

                        // Ajouter les nouveaux offerts au tableau uniquement s'ils ne sont pas déjà dans le DOM
                        newItems.forEach(item => {
                            if (!document.getElementById('row_' + item.id)) {
                                // Génère l'URL de base via Laravel (sans paramètres)
                                const baseApproveUrl = "{{ route('offert.approuvedOffert') }}";

                              
                                const approveUrl =
                                    `${baseApproveUrl}?offert=${item.id}&approuved=1`;
                                const rejectUrl =
                                    `${baseApproveUrl}?offert=${item.id}&approuved=0`;

                           


                                $('#buttons-datatables tbody').prepend(`
                                        <tr id="row_${item.id}">
                                            <td></td>
                                            <td>
                                                ${item.offert_statut === null
                                                    ? '<span class="badge bg-warning">En attente</span>'
                                                    : item.offert_statut ===0
                                                        ? '<span class="badge bg-success">Approuvé</span>'
                                                        : '<span class="badge bg-danger">Rejeté</span>'
                                                }
                                            </td>
                                            <td>${item.vente.code}</td>
                                            <td>
                                                ${item.produit.nom} * ${item.quantite} ${item.variante.libelle} de ${item.prix}
                                            </td>
                                            <td>
                                                ${item.vente.user.first_name} - ${item.vente.caisse?.libelle ?? 'N/A'}
                                            </td>
                                            <td>
                                                ${item.date_created ? new Date(item.date_created).toLocaleDateString('fr-FR', {
                                                    day: '2-digit',
                                                    month: '2-digit',
                                                    year: 'numeric',
                                                    hour: '2-digit',
                                                    minute: '2-digit'
                                                }) : ''}
                                            </td>
                                            <td class="d-block">
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-fill align-middle"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        ${item.offert_statut === null ? `
                                                                                    <li>
                                                                                        <a href="${approveUrl}" class="dropdown-item remove-item-btn" data-id="${item.id}">
                                                                                            <i class="ri-check-line align-bottom me-2 text-muted"></i>
                                                                                            Approuver
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href="${rejectUrl}" class="dropdown-item remove-item-btn" data-id="${item.id}">
                                                                                            <i class="ri-close-line align-bottom me-2 text-muted"></i>
                                                                                            Rejeter
                                                                                        </a>
                                                                                    </li>
                                                                                ` : ''}
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    `);

                                lastIds.push(item.id);
                            }
                        });

                        // Réindexe les lignes après ajout
                        $('#buttons-datatables tbody tr').each(function(index) {
                            $(this).find('td:first').text(index + 1);
                        });
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('Erreur lors de la récupération des offerts:', textStatus, errorThrown);
                });
            }


            // Vérifie toutes les 10 secondes
            setInterval(checkOfferts, 10000);


        });
    </script>
@endsection
