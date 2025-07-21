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
            Gestion de stock
        @endslot
        @slot('title')
            Détails de l'inventaire
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        Detail Inventaire {{ $inventaire->code }} du mois
                        <strong class="text-uppercase">
                            {{-- {{ ($inventairePrecedent?->created_at ?? $inventaire->created_at)->format('d-m-Y') }} --}}
                            {{ $moisInventaire }}
                        </strong>
                        {{-- au <strong>{{ $inventaire->created_at->format('d-m-Y') }}</strong> --}}
                    </h5>

                    <div class="d-flex align-items-center gap-2">

                        @php
                            $etat = ['Surplus', 'Perte', 'Conforme', 'Rupture'];
                        @endphp

                        <form action="{{ route('inventaire.show', $inventaire->id) }}" method="get"
                            class="d-flex align-items-center gap-2">
                            <select class="form-select" name="filter_etat">
                                <option value="">Tous les états</option>
                                @foreach ($etat as $item)
                                    <option value="{{ $item }}"
                                        {{ request()->get('filter_etat') == $item ? 'selected' : '' }}>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </select>

                            <button class="btn btn-primary">
                                Filtrer
                            </button>
                        </form>

                        <a href="{{ route('inventaire.create') }}" class="btn btn-success">Nouvel inventaire</a>

                    </div>
                </div>




                <div class="card-body divPrint">
                    <div class="table-responsive">
                        <table id="example" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="{{ Auth::user()->hasRole('developpeur') ? '' : 'd-none' }}">Code</th>
                                    <th>Nom</th>
                                    <th>stock dernier inventaire</th>
                                    <th>Achat après dernier inventaire</th>
                                    <th>Stock de la periode</th>
                                    <th>Vente après dernier inventaire</th>
                                    <th>Stock théorique</th>
                                    <th>Stock physique</th>
                                    <th>Écart</th>
                                    <th>Etat du stock</th>
                                    <th>Observation</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    if (!function_exists('afficherStockConvertir')) {
                                        function afficherStockConvertir($valeur, $unite = '')
                                        {
                                            $entier = floor($valeur);
                                            $decimal = $valeur - $entier;
                                            $ml = intval($decimal * 1000);
                                            $texte = '';

                                            if ($decimal == 0) {
                                                $texte .= $entier . ' ' . $unite;
                                            } elseif ($entier == 0 && $ml > 0) {
                                                $texte .= $ml . ' ml';
                                            } else {
                                                $texte .= $entier . ' ' . $unite . ' et ' . $ml . ' ml';
                                            }

                                            return $texte ?: '0';
                                        }
                                    }
                                @endphp

                                @foreach ($inventaire->produits as $key => $item)
                                    @php
                                        // recuperer le stock physique json
                                        $stockPhysique_json = json_decode($item['pivot']['stock_physique_json'], true);
                                    @endphp


                                    <tr id="row_{{ $item['id'] }}">
                                        <td>{{ ++$key }}</td>
                                        <td class="{{ Auth::user()->hasRole('developpeur') ? '' : 'd-none' }}">
                                            {{ $item['code'] }}
                                        </td>
                                        <td>{{ $item['nom'] }} <b>{{ $item['valeur_unite'] ?? '' }}</b>
                                            {{ $item['unite']['abreviation'] ?? '' }}</td>

                                        <td>{!! afficherStockConvertir(
                                            $item['pivot']['stock_dernier_inventaire'] ?? 0,
                                            $item['uniteSortie']['abreviation'] ?? '',
                                        ) !!}</td>
                                        <td>{!! afficherStockConvertir($item['pivot']['stock_initial'] ?? 0, $item['uniteSortie']['abreviation'] ?? '') !!}</td>
                                        <td>
                                            @php
                                                $stock_total =
                                                    ($item['pivot']['stock_dernier_inventaire'] ?? 0) +
                                                    ($item['pivot']['stock_initial'] ?? 0);
                                            @endphp
                                            {!! afficherStockConvertir($stock_total, $item['uniteSortie']['abreviation'] ?? '') !!}
                                        </td>
                                        <td>{!! afficherStockConvertir($item['pivot']['stock_vendu'] ?? 0, $item['uniteSortie']['abreviation'] ?? '') !!}</td>
                                        <td>{!! afficherStockConvertir($item['pivot']['stock_theorique'] ?? 0, $item['uniteSortie']['abreviation'] ?? '') !!}</td>
                                        <td>
                                            <ul class="mb-0">
                                                @foreach ($stockPhysique_json as $libelle => $quantite)
                                                    <li>{{ $libelle }} : {{ $quantite }}</li>
                                                @endforeach
                                            </ul>
                                            {!! afficherStockConvertir($item['pivot']['stock_physique'] ?? 0, $item['uniteSortie']['abreviation'] ?? '') !!}
                                        </td>
                                        <td>
                                            @php
                                                $ecart = $item['pivot']['ecart'];
                                                $entier = floor($ecart);
                                                $decimal = $ecart - $entier; //partie decimal
                                                $ml = intval($decimal * 1000); //convertir en millilitre
                                            @endphp


                                            @if ($decimal == 0)
                                                {{ $entier }} {{ $item['uniteSortie']['abreviation'] ?? '' }}
                                            @elseif ($entier == 0 && $ml > 0)
                                                {{ $ml }} ml
                                            @else
                                                {{ $entier }} {{ $item['uniteSortie']['abreviation'] ?? '' }}
                                                et
                                                {{ $ml }} ml
                                            @endif
                                        </td>
                                        <td>{{ $item['pivot']['etat'] }}</td>
                                        <td>{{ $item['pivot']['observation'] }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

                <button id="btnImprimer" class="w-100"><i class="ri ri-printer-fill"></i></button>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>


    <script>
        // $(document).ready(function() {
        //     // Fonction pour imprimer le rapport
        //     function imprimerRapport() {
        //         // $('.divPrint table').removeAttr('id');
        //         // Créer une nouvelle fenêtre pour l'impression
        //         var fenetreImpression = window.open('', '_blank');

        //         // Contenu à imprimer
        //         var contenuImprimer = `
    //             <html>
    //                 <head>
    //                     <title style="text-align: center;">Compte exploitation</title>
    //                     <style>
    //                         body { font-family: Arial, sans-serif; }
    //                         table { width: 100%; border-collapse: collapse; }
    //                         th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    //                         th { background-color: #f2f2f2; }
    //                     </style>
    //                 </head>
    //                 <body>
    //                     <h2 style="text-align: center;">Fiche Inventaire  </h2>
    //                     <p style="text-align: center;">Code : {{ $inventaire->code }}</p>
    //                     <p style="text-align: center;">Réalisé le : {{ $inventaire->created_at->format('d-m-Y à H:i') }}</p>
    //                     ${$('.divPrint').html()}
    //                     <footer style="position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px; margin-top: 20px;">
    //                         <p>Imprimé le : ${new Date().toLocaleString()} par {{ Auth::user()->first_name }}</p>
    //                     </footer>
    //                 </body>
    //             </html>
    //         `;

        //         // Écrire le contenu dans la nouvelle fenêtre
        //         fenetreImpression.document.write(contenuImprimer);

        //         // Fermer le document
        //         fenetreImpression.document.close();

        //         // Imprimer la fenêtre
        //         fenetreImpression.print();
        //     }

        //     // Ajouter un bouton d'impression
        //     $('#btnImprimer')
        //         .text('Imprimer le Rapport')
        //         .addClass('btn btn-primary mt-3')
        //         .on('click', imprimerRapport);
        //     // .appendTo('.divPrint');
        //     // Supprimer l'ID de la table avant l'impression

        // });

        $(document).ready(function() {
            // Fonction pour imprimer le rapport
            //     function imprimerRapport() {
            //         // Sauvegarder l'ID de la table
            //         var table = $('#example');
            //         var originalId = table.attr('id');

            //         // Désactiver DataTable temporairement (pagination, recherche, etc.)
            //         if ($.fn.DataTable.isDataTable('#example')) {
            //             // Détruire DataTables pour enlever la pagination, la barre de recherche, etc.
            //             table.DataTable().destroy();
            //         }

            //         // Supprimer l'ID avant l'impression pour éviter des conflits
            //         table.removeAttr('id');

            //         // Créer une nouvelle fenêtre pour l'impression
            //         var fenetreImpression = window.open('', '_blank');

            //         // Contenu à imprimer
            //         var contenuImprimer = `
        //     <html>
        //         <head>
        //             <title style="text-align: center;">Compte exploitation</title>
        //             <style>
        //                 body { font-family: Arial, sans-serif; }
        //                 table { width: 100%; border-collapse: collapse; }
        //                 th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        //                 th { background-color: #f2f2f2; }
        //             </style>
        //         </head>
        //         <body>
        //             <h2 style="text-align: center;">Fiche Inventaire  </h2>
        //             <p style="text-align: center;">Code : {{ $inventaire->code }}</p>
        //             <p style="text-align: center;">Réalisé le : {{ $inventaire->created_at->format('d-m-Y à H:i') }}</p>
        //             ${$('.divPrint').html()}
        //             <footer style="position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px; margin-top: 20px;">
        //                 <p>Imprimé le : ${new Date().toLocaleString()} par {{ Auth::user()->first_name }}</p>
        //             </footer>
        //         </body>
        //     </html>
        // `;

            //         // Écrire le contenu dans la nouvelle fenêtre
            //         fenetreImpression.document.write(contenuImprimer);

            //         // Fermer le document
            //         fenetreImpression.document.close();

            //         // Imprimer la fenêtre
            //         fenetreImpression.print();

            //         // Restaurer l'ID de la table après l'impression
            //         table.attr('id', originalId);

            //         // Réinitialiser DataTable après l'impression
            //         table.DataTable({
            //             paging: true,
            //             searching: true,
            //             // Réinitialiser ici les options de DataTables si nécessaires
            //         });
            //     }

            //     // Ajouter un bouton d'impression
            //     $('#btnImprimer')
            //         .text('Imprimer le Rapport')
            //         .addClass('btn btn-primary mt-3')
            //         .on('click', imprimerRapport);



            //         function imprimerRapport() {
            //             // Sauvegarder l'ID de la table
            //             var table = $('#example');
            //             var originalId = table.attr('id');

            //             // Désactiver DataTable temporairement (pagination, recherche, etc.)
            //             if ($.fn.DataTable.isDataTable('#example')) {
            //                 // Détruire DataTables pour enlever la pagination, la barre de recherche, etc.
            //                 table.DataTable().destroy();
            //             }

            //             // Supprimer l'ID avant l'impression pour éviter des conflits
            //             table.removeAttr('id');

            //             // Créer une nouvelle fenêtre pour l'impression
            //             var fenetreImpression = window.open('', '_blank');

            //             // Nombre total de pages (selon DataTable)
            //             var totalPages = Math.ceil(table.find('tbody tr').length / 20); // 20 lignes par page (exemple)

            //             // Contenu à imprimer
            //             var contenuImprimer = `
        //     <html>
        //         <head>
        //             <title style="text-align: center;">Compte exploitation</title>
        //             <style>
        //                 body { font-family: Arial, sans-serif; }
        //                 table { width: 100%; border-collapse: collapse; }
        //                 th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        //                 th { background-color: #f2f2f2; }
        //                 footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px; margin-top: 20px; }
        //             </style>
        //         </head>
        //         <body>
        //             <h2 style="text-align: center;">Fiche Inventaire</h2>
        //             <p style="text-align: center;">Code : {{ $inventaire->code }}</p>
        //             <p style="text-align: center;">Réalisé le : {{ $inventaire->created_at->format('d-m-Y à H:i') }}</p>
        //             ${$('.divPrint').html()}
        //             <footer>
        //                 <p>Imprimé le : ${new Date().toLocaleString()} par {{ Auth::user()->first_name }}</p>
        //                 <p>Page 1 / ${totalPages}</p> <!-- Affichage de la page actuelle et du total -->
        //             </footer>
        //         </body>
        //     </html>
        // `;

            //             // Écrire le contenu dans la nouvelle fenêtre
            //             fenetreImpression.document.write(contenuImprimer);

            //             // Fermer le document
            //             fenetreImpression.document.close();

            //             // Imprimer la fenêtre
            //             fenetreImpression.print();

            //             // Restaurer l'ID de la table après l'impression
            //             table.attr('id', originalId);

            //             // Réinitialiser DataTable après l'impression
            //             table.DataTable({
            //                 paging: true,
            //                 searching: true,
            //                 // Réinitialiser ici les options de DataTables si nécessaires
            //             });
            //         }

            //         // Ajouter un bouton d'impression
            //         $('#btnImprimer')
            //             .text('Imprimer le Rapport')
            //             .addClass('btn btn-primary mt-3')
            //             .on('click', imprimerRapport);



            //         function imprimerRapport() {
            //             // Sauvegarder l'ID de la table
            //             var table = $('#example');
            //             var originalId = table.attr('id');

            //             // Désactiver DataTable temporairement (pagination, recherche, etc.)
            //             if ($.fn.DataTable.isDataTable('#example')) {
            //                 // Détruire DataTables pour enlever la pagination, la barre de recherche, etc.
            //                 table.DataTable().destroy();
            //             }

            //             // Supprimer l'ID avant l'impression pour éviter des conflits
            //             table.removeAttr('id');

            //             // Créer une nouvelle fenêtre pour l'impression
            //             var fenetreImpression = window.open('', '_blank');

            //             // Nombre total de pages (selon DataTable)
            //             var totalPages = Math.ceil(table.find('tbody tr').length / 20); // 20 lignes par page (exemple)

            //             // Contenu à imprimer
            //             var contenuImprimer = `
        //     <html>
        //         <head>
        //             <title style="text-align: center;">Compte exploitation</title>
        //             <style>
        //                 body { font-family: Arial, sans-serif; font-size: 10px; } /* Taille de police réduite */
        //                 table { width: 100%; border-collapse: collapse; font-size: 10px; } /* Table avec police réduite */
        //                 th, td { border: 1px solid #ddd; padding: 5px; text-align: left; } /* Réduction du padding */
        //                 th { background-color: #f2f2f2; }
        //                 footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; margin-top: 20px; }
        //             </style>
        //         </head>
        //         <body>
        //             <h2 style="text-align: center; font-size: 12px;">Fiche Inventaire</h2> <!-- Réduire la taille du titre -->
        //             <p style="text-align: center; font-size: 10px;">Code : {{ $inventaire->code }}</p>
        //             <p style="text-align: center; font-size: 10px;">Réalisé le : {{ $inventaire->created_at->format('d-m-Y à H:i') }}</p>
        //             ${$('.divPrint').html()}
        //             <footer>
        //                 <p style="font-size: 8px;">Imprimé le : ${new Date().toLocaleString()} par {{ Auth::user()->first_name }}</p>
        //                 <p style="font-size: 8px;">Page 1 / ${totalPages}</p> <!-- Affichage de la page actuelle et du total -->
        //             </footer>
        //         </body>
        //     </html>
        // `;

            //             // Écrire le contenu dans la nouvelle fenêtre
            //             fenetreImpression.document.write(contenuImprimer);

            //             // Fermer le document
            //             fenetreImpression.document.close();

            //             // Imprimer la fenêtre
            //             fenetreImpression.print();

            //             // Restaurer l'ID de la table après l'impression
            //             table.attr('id', originalId);

            //             // Réinitialiser DataTable après l'impression
            //             table.DataTable({
            //                 paging: true,
            //                 searching: true,
            //                 // Réinitialiser ici les options de DataTables si nécessaires
            //             });
            //         }

            //         // Ajouter un bouton d'impression
            //         $('#btnImprimer')
            //             .text('Imprimer le Rapport')
            //             .addClass('btn btn-primary mt-3')
            //             .on('click', imprimerRapport);



            function imprimerRapport() {

                // Sauvegarder l'ID de la table
                var table = $('#example');
                var originalId = table.attr('id');

                // Désactiver DataTable temporairement (pagination, recherche, etc.)
                if ($.fn.DataTable.isDataTable('#example')) {
                    // Détruire DataTables pour enlever la pagination, la barre de recherche, etc.
                    table.DataTable().destroy();
                }

                // Supprimer l'ID avant l'impression pour éviter des conflits
                table.removeAttr('id');

                // Créer une nouvelle fenêtre pour l'impression
                var fenetreImpression = window.open('', '_blank');

                // Nombre total de lignes dans le tableau
                var totalLignes = table.find('tbody tr').length;

                // Calculer le nombre total de pages basé sur la taille de la police et la taille d'une ligne
                var lignesParPage =
                    20; // Ajustez ce chiffre si nécessaire, par exemple, si vous avez plus ou moins de lignes par page
                var totalPages = Math.ceil(totalLignes / lignesParPage);

                // Si vous avez des lignes incomplètes ou des ajustements à faire, ajustez le nombre de pages
                if (totalPages > 6) {
                    totalPages = 6; // Exemple d'ajustement manuel si vous avez trop de pages
                }

                // Contenu à imprimer
                var contenuImprimer = `
        <html>
            <head>
                <title style="text-align: center;">Fiche produit Inventaire</title>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 10px; }
                    table { width: 100%; border-collapse: collapse; font-size: 10px; }
                    th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; margin-top: 20px; }
                </style>
            </head>
            <body>
                <h2 style="text-align: center; font-size: 12px;">Fiche Inventaire</h2>
                <p style="text-align: center; font-size: 10px;">Code : {{ $inventaire->code }}</p>
                <p style="text-align: center; font-size: 10px;">Réalisé le : {{ $inventaire->created_at->format('d-m-Y à H:i') }}</p>
                ${$('.divPrint').html()}
               
            </body>
        </html>
    `;

                // <
                // footer >
                //     <
                //     p style = "font-size: 8px;" > Imprimé le: $ {
                //         new Date().toLocaleString()
                //     }
                // par {{ Auth::user()->first_name }} < /p> <
                //     !-- < p style = "font-size: 8px;" > Page 1 / $ {
                //         totalPages
                //     } < /p>  Affichage de la page actuelle et du total --> <
                //     /footer>

                // Écrire le contenu dans la nouvelle fenêtre
                fenetreImpression.document.write(contenuImprimer);

                // Fermer le document
                fenetreImpression.document.close();

                // Imprimer la fenêtre
                fenetreImpression.print();

                // Restaurer l'ID de la table après l'impression
                table.attr('id', originalId);

                // Réinitialiser DataTable après l'impression
                table.DataTable({
                    paging: true,
                    searching: true,
                });
            }

            // Ajouter un bouton d'impression
            $('#btnImprimer')
                .text('Imprimer le Rapport')
                .addClass('btn btn-primary mt-3')
                .on('click', imprimerRapport);
        });
    </script>
@endsection
