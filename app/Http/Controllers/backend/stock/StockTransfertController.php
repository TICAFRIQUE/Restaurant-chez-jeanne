<?php

namespace App\Http\Controllers\backend\stock;

use App\Models\Produit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockTransfertController extends Controller
{
    //creeate
    public function create($id)
    {
        //
        try {
            $produit_source = Produit::find($id);
            if (!$produit_source) {
                return redirect()->back()->with('error', "Le produit source n'existe pas.");
            }
            //si le produit source n'est pas une bouteille ou si son stock est inferieur ou egal a 0
            if ($produit_source->variante->slug != 'bouteille' || $produit_source->stock <= 0) {
                return redirect()->back()->with('error', "Le produit source " . $produit_source->libelle . " doit etre une bouteille avec un stock superieur a 0.");
            }

            $produit_destination = Produit::whereNom($produit_source->nom)
                ->whereHas('variante', function ($q) {
                    $q->where('libelle', 'verre');
                })
                ->with('variante')
                ->active()
                ->first();
            if (!$produit_destination) {
                return redirect()->back()->with('error', "Le produit destination " . $produit_source->nom . " en verre n'existe pas pour transferer le stock , veuillez le créer.");
            }

          

            return view('backend.pages.stock-transfert.create', compact('produit_source', 'produit_destination'));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }


    // store
    public function store(Request $request)
    {
        //
    }
}
