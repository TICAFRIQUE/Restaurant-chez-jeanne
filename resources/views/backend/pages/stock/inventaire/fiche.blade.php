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
            Fiche de produit Inventaire
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">
                        @if (request()->has('type'))
                            @if (request('type') == 'tous')
                                Tous les produits
                            @else
                                Produit: <strong>{{ ucfirst(request('type')) }}</strong>
                            @endif
                        @endif
                        <strong>#</strong>
                    </h5>
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
                <div class="card-body divPrint">
                    <div class="table-responsive">
                        <table id="exampleFiche" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Nom</th>
                                    @role('developpeur')
                                        <th>Stock</th>
                                    @endrole

                                    <th>Stock Physique</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                </tr>
                                @foreach ($produits as $key => $item)
                                    <tr id="row_{{ $item['id'] }}">
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $item['code'] }}</td>
                                        <td>{{ $item['libelle'] != null ? $item['libelle'] : $item['nom'] }} {{ $item['valeur_unite'] ?? '' }}</td>
                                        @role('developpeur')
                                            <td>{{ $item['stock'] }}</td>
                                        @endrole
                                      
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
        $(document).ready(function() {

            function imprimerRapport() {
                // Sauvegarder l'ID de la table
                var table = $('#exampleFiche');
                var originalId = table.attr('id');

                // Désactiver DataTable temporairement (pagination, recherche, etc.)
                if ($.fn.DataTable.isDataTable('#exampleFiche')) {
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
                <title style="text-align: center;">Fiche de produit</title>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 10px; }
                    table { width: 100%; border-collapse: collapse; font-size: 10px; }
                    th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; margin-top: 20px; }
                </style>
            </head>
            <body>
                <h2 style="text-align: center; font-size: 12px;">Fiche de produit 
                @if (request()->get('type') != 'tous')
                    <span>{{ request()->get('type') }}</span>
                @endif
                     pour inventaire</h2>
                
                <p style="text-align: center; font-size: 10px;">Réalisé le : {{ \Carbon\Carbon::now()->format('d-m-Y à H:i') }}</p>
                 <p style="text-align: center; font-size: 10px;">Réalisé Par : .............................................................</p>

                ${$('.divPrint').html()}
                <footer>
                    <p style="font-size: 8px;">Imprimé le : ${new Date().toLocaleString()} par {{ Auth::user()->first_name }}</p>
                    <!--<p style="font-size: 8px;">Page 1 / ${totalPages}</p>  Affichage de la page actuelle et du total -->
                </footer>
            </body>
        </html>
    `;

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
                .text('Imprimer la fiche')
                .addClass('btn btn-primary mt-3')
                .on('click', imprimerRapport);



        });
    </script>
@endsection
