<table id="buttons-datatables" class="display table table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Produit</th>
            <th>Date de vente</th>
            <th>Quantite</th>
            <th>Prix unitaire</th>
            <th>total</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($vente as $key => $item)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $item['produit']['libelle'] != null ? $item['produit']['libelle'] : $item['produit']['nom'] }}
                    <b> {{ $item['produit']['valeur_unite'] ?? '' }}
                        {{ $item['produit']['unite']['abreviation'] ?? '' }}</b>
                </td>
                <td>{{ \Carbon\Carbon::parse($item['vente']['date_vente'])->format('d-m-Y à H:i') }}</td>
                {{-- <td><b> {{ $item['quantite_bouteille'] ?? 0 }} {{ $item['variante']['libelle'] ?? 'bouteille' }} </b> </td> --}}
                <td><b>

                        {{ $item['quantite_bouteille'] ?? 0 }}
                    </b> </td>

                <td>{{ number_format($item['prix_unitaire'], 0, ',', ' ') }} FCFA</td>
                <td>{{ number_format($item['total'], 0, ',', ' ') }}
                    FCFA</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">
                    <h6 class="text-center fw-bold text-warning">Aucune vente trouvé</h6>
                </td>
            </tr>
        @endforelse
    </tbody>


</table>
<tfoot>
    <tr>
        <th colspan="7">
            <div class="text-start">
                {{-- <div>Total pour {{ $famille }}</div> --}}
                <div>Quantité de bouteille vendue : <b>

                        {{ $vente->sum('quantite_bouteille') ?? 0 }}
                    </b></div>
                <div>Montant total :
                    <b> {{ number_format($vente->sum('total'), 0, ',', ' ') }} FCFA</b>
                </div>
            </div>
        </th>
    </tr>
</tfoot>
