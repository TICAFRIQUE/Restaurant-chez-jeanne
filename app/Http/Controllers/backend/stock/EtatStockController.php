<?php

namespace App\Http\Controllers\backend\stock;

use Carbon\Carbon;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Inventaire;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EtatStockController extends Controller
{
    public function index()
    {
        try {
            $filter = request('filter');
            $statut = request('statut'); // Alerte ou Normal

            $produits = Produit::with(['categorie', 'achats',])
                ->whereHas('categorie', function ($query) {
                    $query->whereIn('famille', ['bar', 'restaurant']);
                })
                ->when($filter, function ($query) use ($filter) {
                    return $query->withWhereHas('typeProduit', fn($q) => $q->where('type', $filter));
                })
                ->when($statut === 'alerte', function ($query) {
                    return $query->where('stock', '<=', 'stock_alerte');
                })
                ->get();




            // dd($produits->toArray());
            return view('backend.pages.stock.etat-stock.index', compact('produits'));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des données.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function suiviStock()
    {
        try {
            // Récupérer la date du dernier inventaire
            $date_dernier_inventaire = Inventaire::select('date_inventaire')
                ->orderBy('date_inventaire', 'desc')
                ->first();
            $date_dernier_inventaire = $date_dernier_inventaire ? $date_dernier_inventaire->date_inventaire : Carbon::now()->startOfDay();

            // Date du jour
            $date_jour = Carbon::now();

            // Récupérer les produits avec le nombre de ventes entre la date du dernier inventaire et la date du jour
            $data_produit = Produit::whereHas('categorie', function ($q) {
                $q->whereIn('famille', ['restaurant', 'bar']);
            })
                ->withSum(['ventes as quantite_vendue' => function ($query) use ($date_dernier_inventaire, $date_jour) {
                    // Filtrer les ventes entre la date du dernier inventaire et la date du jour
                    $query->whereBetween('ventes.date_vente', [$date_dernier_inventaire, $date_jour]);
                }], 'produit_vente.quantite') // Somme de la quantité vendue dans le pivot produit_vente


                ->withSum(['sorties as quantite_utilisee' => function ($query) use ($date_dernier_inventaire, $date_jour) {
                    // Filtrer les sorties entre la date du dernier inventaire et la date du jour
                    $query->whereBetween('sorties.date_sortie', [$date_dernier_inventaire, $date_jour]);
                }], 'produit_sortie.quantite_utilise') // Somme de la quantité utilisée dans le pivot produit_sortie

                ->with(['categorie' , 'inventaires'=>fn($q) => $q->where('date_inventaire', $date_dernier_inventaire)])
                ->get();


            // recuperer les familles de categories bar et restaurant
            $categorie_famille = Categorie::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')
                ->whereIn('type', ['bar', 'restaurant'])
                ->OrderBy('position', 'ASC')->get();

                       

            // dd($data_produit->toArray());
            return view('backend.pages.stock.etat-stock.suivi', compact('data_produit', 'categorie_famille' , 'date_dernier_inventaire', 'date_jour'));
        } catch (\Throwable $e) {
            return  $e->getMessage();
        }
    }
}
