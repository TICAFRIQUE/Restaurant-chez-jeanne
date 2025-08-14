@extends('backend.layouts.master')
@section('title')
    Rapport des ventes par catégorie
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
            Rapports
        @endslot
        @slot('title')
            Rapport des ventes
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filtres</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('rapport.vente') }}" method="GET">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="caisse_id" class="form-label">Caisse</label>
                                    <select class="form-select" id="caisse_id" name="caisse_id">
                                        <option value="">Toutes les caisses</option>
                                        @foreach ($caisses as $caisse)
                                            <option value="{{ $caisse->id }}"
                                                {{ request('caisse_id') == $caisse->id ? 'selected' : '' }}>
                                                {{ $caisse->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="date_debut" class="form-label">Famille</label>
                                <select class="form-select" id="categorie" name="categorie_famille">
                                    <option value="">Toutes les catégories</option>
                                    @foreach ($famille as $item)
                                        <option value="{{ $item->famille }}"
                                            {{ request('categorie_famille') == $item->famille ? 'selected' : '' }}>
                                            @if ($item->famille == 'menu')
                                                Cuisine interne
                                            @elseif($item->famille == 'bar')
                                                Boissons
                                            @else
                                                {{ $item->famille }}
                                            @endif

                                        </option>
                                    @endforeach
                                    <option value="plats du menu"
                                        {{ request('categorie_famille') == 'plats du menu' ? 'selected' : '' }}>Menu
                                    </option>
                                </select>
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
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="statut" class="form-label">Periodes</label>
                                    <select class="form-select" id="periode" name="periode">
                                        <option value="">Toutes les periodes</option>
                                        @foreach (['jour' => 'Jour', 'semaine' => 'Semaine', 'mois' => 'Mois', 'annee' => 'Année'] as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ request('periode') == $key ? 'selected' : '' }}>
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
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card divPrint">
                <div class="card-header">
                    <h5 class="card-title mb-0 " style="text-align: center";>
                        {{-- Rapport des ventes --}}
                        Vente
                        @if (request('caisse_id'))
                            <strong> {{ $caisses->find(request('caisse_id'))->libelle }}</strong>
                        @endif
                        @if (request('categorie_famille'))
                            @if (request('categorie_famille') == 'bar')
                                pour les Boissons
                            @elseif(request('categorie_famille') == 'menu')
                                pour la Cuisine interne
                            @else
                                pour {{ request('categorie_famille') }}
                            @endif
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
                        @if (request()->has('periode') && request('periode') != null)
                            -
                            <strong>{{ request('periode') }}</strong>
                        @endif
                    </h5>
                </div>
                <div class="card-body">

                    @php
                        // Ordre personnalisé pour trier les familles
                        $ordreFamilles = [
                            'menu' => 1, // Cuisine interne en premier
                            'bar' => 2, // Boissons en deuxième
                            'plat_du_menu' => 3, // Plat du menu

                            // Ajoute d'autres familles si nécessaire avec des numéros plus grands
];

// Trier les familles en fonction de l'ordre personnalisé
                        $produitsVendus = $produitsVendus
                            ->groupBy('famille')
                            ->sortBy(function ($produits, $famille) use ($ordreFamilles) {
                                return $ordreFamilles[$famille] ?? 999; // Si la famille n'est pas définie dans l'ordre, elle sera mise à la fin
                            });

                        // Groupe les produits par famille
                        $produitsVendus = $produitsVendus->map(function ($produits, $famille) {
                            return $produits;
                        });

                        // montant total globale de toutes les familles
                        $montantTotalVente = $produitsVendus->sum(function ($produits) {
                            return $produits->sum('montant_total');
                        });
                    @endphp
                    @foreach ($produitsVendus as $famille => $produits)
                        <h3 class="fw-semibold">
                            @if ($famille == 'menu')
                                Cuisine interne
                            @elseif($famille == 'bar')
                                Boissons
                            @else
                                {{ $famille }}
                            @endif
                        </h3>
                        <span>Nombre d'articles : <b>{{ $produits->sum('quantite_vendue') }}</b></span><br>
                        <span>Montant total :
                            <b> {{ number_format($produits->sum('montant_total'), 0, ',', ' ') }} FCFA</b>
                        </span>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        {{-- <th>Code</th> --}}
                                        <th>Designation</th>
                                        {{-- <th>Catégorie</th> --}}
                                        @if ($famille == 'bar')
                                            <th>Quantité vendue</th>
                                            <th>Montant total</th>
                                        @else
                                            <th>Quantité vendue</th>
                                            <th>Prix de vente</th>
                                            <th>Montant total</th>
                                        @endif

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($produits as $produit)
                                        <tr>
                                            {{-- <td>{{ $produit['code'] }}</td> --}}
                                            <td>{{ $produit['designation'] }}</td>
                                            {{-- <td>{{ $produit['categorie'] }}</td> --}}

                                            <!-- ========== Start si famille est bar on affiche les details quantité et variante ========== -->
                                            {{-- @if ($famille == 'bar')
                                                <td>
                                                    @foreach ($produit['details'] as $item)
                                                        {{ $item->pivot->quantite }}
                                                        {{ \App\Models\Variante::find($item->pivot->variante_id)->libelle ?? '' }}
                                                        :

                                                        {{ $item->pivot->prix_unitaire }}
                                                        <br>
                                                    @endforeach
                                                </td>
                                            @else
                                                <td>{{ $produit['quantite_vendue'] }} </td>
                                            @endif --}}

                                            @if ($famille == 'bar')
                                                @php
                                                    $details = $produit['details'];
                                                    $variantes = $details->pluck('pivot.variante_id')->unique();

                                                    // Préchargement facultatif pour éviter requêtes multiples
                                                    $varianteLibelles = \App\Models\Variante::whereIn('id', $variantes)
                                                        ->get()
                                                        ->keyBy('id');
                                                @endphp

                                                <td>
                                                    @php
                                                        // Regrouper les détails par variante_id et prix_unitaire
                                                        $groupes = $details->groupBy(function ($item) {
                                                            return $item->pivot->variante_id .
                                                                '_' .
                                                                $item->pivot->prix_unitaire;
                                                        });
                                                    @endphp

                                                    @foreach ($groupes as $groupe)
                                                        @php
                                                            $quantiteTotale = $groupe->sum('pivot.quantite');
                                                            $varianteId = $groupe->first()->pivot->variante_id;
                                                            $varianteNom =
                                                                $varianteLibelles[$varianteId]->libelle ?? '';
                                                            $prixUnitaire = $groupe->first()->pivot->prix_unitaire;
                                                        @endphp
                                                        {{ $quantiteTotale }} {{ $varianteNom }} x
                                                        {{ number_format($prixUnitaire, 0, ',', ' ') }} FCFA<br>
                                                    @endforeach
                                                </td>
                                                <td>{{ number_format($produit['montant_total'], 0, ',', ' ') }} FCFA</td>
                                            @else
                                                <!-- ========== End si famille est bar on affiche les details quantité et variante ========== -->
                                                <td>{{ $produit['quantite_vendue'] }} </td>
                                                <td>{{ number_format($produit['prix_vente'], 0, ',', ' ') }} FCFA</td>
                                                <td>{{ number_format($produit['montant_total'], 0, ',', ' ') }} FCFA</td>
                                            @endif

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Pas de produits vendus dans cette famille
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                {{-- <tfoot>
                                    <tr>
                                        <th colspan="7">
                                            <div class="text-end">
                                                <div>Nombre d'articles : {{ $produits->sum('quantite_vendue') }}</div>
                                                <div>Montant total :
                                                    {{ number_format($produits->sum('montant_total'), 0, ',', ' ') }} FCFA
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                </tfoot> --}}

                            </table>
                        </div>
                    @endforeach

                    {{-- <div class="mt-4">
                        <h3>Résumé global</h3>

                        <p>Montant total des ventes :
                            <span class="fw-bold fs-4">{{ number_format($montantTotalVente, 0, ',', ' ') }} FCFA</span>
                        </p>
                    </div> --}}

                    <!-- Total général -->
                    <div class="alert alert-dark mt-4 text-center fs-5">
                        <b>💰 Montant total de vente :</b>
                        <span class="fw-bold fs-4 text-primary">{{ number_format($montantTotalVente, 0, ',', ' ') }}
                            FCFA</span>
                    </div>
                </div>
            </div>

            <button id="btnImprimer" class="w-100 "><i class="ri ri-printer-fill"></i></button>

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


    {{-- <script>
        $(document).ready(function() {
            // Fonction pour imprimer le rapport
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
    </script> --}}

    <script>
        $(document).ready(function() {
            function imprimerRapport() {
                // Sauvegarder le contenu original de la page
                var contenuOriginal = $('body').html();

                // Récupérer uniquement la section à imprimer
                var contenuImprimer = `
                <html>
                    <head>
                        <title>Rapport de Vente</title>
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

                // Remplacer le contenu de la page par celui à imprimer
                $('body').html(contenuImprimer);

                // Lancer l'impression
                window.print();

                // Recharger la page pour retrouver l'affichage original
                location.reload(); // ou $('body').html(contenuOriginal); si tu veux éviter le reload
            }

            $('#btnImprimer')
                .text('Imprimer le Rapport')
                .addClass('btn btn-primary mt-3')
                .on('click', imprimerRapport);




        });
    </script>
@endsection
