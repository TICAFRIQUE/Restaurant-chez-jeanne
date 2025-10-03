<?php

namespace App\Http\Controllers\backend\stock;

use Carbon\Carbon;
use App\Models\Unite;
use App\Models\Sortie;
use App\Models\Produit;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SortieController extends Controller
{
    //

    public function index()
    {
        try {
            $data_sortie = Sortie::with('produits')->orderBy('created_at', 'desc')->get();

            return view('backend.pages.stock.sortie.index', compact('data_sortie'));
        } catch (\Throwable $e) {
            return  $e->getMessage();
        }
    }


    public function show($id)
    {
        try {
            $sortie = Sortie::with('produits')->find($id);

            // Vérifier si la sortie existe
            if (!$sortie) {
                return redirect()->route('sortie.index')->with('error', "La sortie demandée n'existe pas.");
            }

            return view('backend.pages.stock.sortie.show', compact('sortie'));
        } catch (\Exception $e) {
            return redirect()->route('sortie.index')->with('error', "Une erreur s'est produite. Veuillez réessayer.");
        }
    }



    public function create(Request $request)
    {

        try {
            $data_produit = Produit::whereHas('categorie', function ($q) {
                $q->where('famille', 'restaurant');
            })
                ->with(['unite', 'uniteSortie'])
                ->active()
                ->get();

            $data_unite = Unite::all();

            // dd($data_produit->toArray());
            return view('backend.pages.stock.sortie.create', compact('data_produit', 'data_unite'));
        } catch (\Throwable $e) {
            return  $e->getMessage();
        }
    }


    public function store(Request $request)
    {

        try {


            // dd($request->all());
            // $request->validate([
            //     'produit_id.*' => 'required',
            //     'quantite_utilise.*' => 'required|numeric',
            //     'unite_id.*' => 'required',
            // ]);

            // enregistrer la sortie
            $sortie = new Sortie();
            $sortie->code = 'SO-' . strtoupper(Str::random(8));
            $sortie->date_sortie = $request->date_sortie;
            $sortie->user_id = Auth::id();
            $sortie->save();

            $cart = $request->input('cart');

            foreach ($cart as $item) {
                // Attachement des produits à la vente
                $sortie->produits()->attach($item['id'], [
                    'quantite_utilise' => $item['quantity'],
                    'quantite_existant' => $item['stock'],
                ]);

                // mettre la quantité utilisée dans le stock de chaque produit
                $produit = Produit::find($item['id']);
                $produit->stock -= $item['quantity'];
                $produit->stock_initial -= $item['quantity'];
                $produit->save();
            }

            // // enregistrer les produits de la sortie
            // foreach ($request->produit_id as $key => $produit_id) {
            //     // Trouver l'unité correspondante pour ce produit
            //     $produit = Produit::find($produit_id);

            //     // Attacher le produit à la sortie avec les informations associées
            //     $sortie->produits()->attach($produit_id, [
            //         'quantite_utilise' => $request->quantite_utilise[$key],
            //         'quantite_existant' => $produit->stock,
            //         'unite_id' => $request->unite_id[$key],
            //         'unite_sortie' => $request->unite_libelle[$key],
            //     ]);

            //     // Retirer la quantité utilisée dans le stock de chaque produit
            //     $produit->stock -= $request->quantite_utilise[$key];
            //     $produit->save();
            // }


            // retur response
            return response()->json([
                'message' => 'Sortie de stock enregistré avec succès.',
                'status' => 'success',
            ], 200);
        } catch (\Throwable $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'statut' => 'error',
            ], 500);
            // return $e->getMessage();
        }
    }
}
