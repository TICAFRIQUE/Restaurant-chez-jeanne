<table id="buttons-datatables" class="display table table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Produit</th>
            <th>Date achat</th>
            <th>Quantité acheté</th>
            <th>Montant</th>
            {{-- <th>total</th> --}}
        </tr>
    </thead>
    <tbody>
        @forelse ($achat as $key => $item)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $item['produit']['libelle']!= null ? $item['produit']['libelle'] : $item['produit']['nom'] }}
                   <b> {{ $item['produit']['valeur_unite'] ?? '' }} {{ $item['produit']['unite']['abreviation'] ?? '' }}</b>

                </td>
                <td>{{ \Carbon\Carbon::parse($item['date_achat'])->format('d-m-Y à H:i') }}</td>
                <td><b> {{ $item['quantite_stocke'] ?? 0 }}
                        {{ $item['produit']['uniteSortie']['libelle'] ?? '' }} </b> </td>
                {{-- <td>{{ number_format($item['prix_unitaire'], 0, ',', ' ') }} FCFA</td> --}}
                <td>{{ number_format($item['prix_total_format'], 0, ',', ' ') }}
                    FCFA</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">
                    <h6 class="text-center fw-bold text-warning">Aucun achat enregistré</h6>
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
                <div>Quantité de bouteille acheté : <b>{{ $achat->sum('quantite_stocke') }}</b></div>
                <div>Montant total :
                    <b> {{ number_format($achat->sum('prix_total_format'), 0, ',', ' ') }} FCFA</b>
                </div>
            </div>
        </th>
    </tr>
</tfoot>
