<div class="card-body divPrint">
    <div class="table-responsive">
        <table id="example" class="display table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date d'inventaire</th>
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
                @forelse ($inventaire as $key => $item)
                    <tr id="row_{{ $item['id'] }}">
                        <td>{{ ++$key }}</td>
                        <td>{{ \Carbon\Carbon::parse($item['inventaire']['date_inventaire'])->format('d-m-Y à H:i') }}</td>
                        <td>{{ $item['produit']['libelle'] != null ? $item['produit']['libelle'] : $item['produit']['nom'] }} <b>{{ $item['produit'] ['valeur_unite'] ?? '' }} {{ $item['produit']['unite']['abreviation'] ?? '' }}</b> </td>
                        <td>{{ $item['stock_dernier_inventaire'] ?? '' }}</td>
                        <td>{{ $item['stock_initial'] }}
                            {{ $item['produit']['uniteSortie']['abreviation'] ?? '' }}</td>
                        <td>{{ $item['stock_dernier_inventaire'] + $item['stock_initial'] }}
                            {{ $item['produit']['uniteSortie']['abreviation'] ?? '' }}</td>
                        <td>{{ $item['stock_vendu'] }}
                            {{ $item['produit']['uniteSortie']['abreviation'] ?? '' }}</td>
                        <td>{{ $item['stock_theorique'] }}
                            {{ $item['produit']['uniteSortie']['abreviation'] ?? '' }}</td>
                        <td>{{ $item['stock_physique'] }}
                            {{ $item['produit']['uniteSortie']['abreviation'] ?? '' }}</td>
                        <td>{{ $item['ecart'] }}</td>
                        <td>{{ $item['etat'] }}</td>
                        <td>{{ $item['observation'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <h6 class="text-center fw-bold text-warning">Aucun inventaire trouvé</h6>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

