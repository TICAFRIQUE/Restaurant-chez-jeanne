<?php

namespace App\Http\Controllers\backend\offert;

use App\Models\Vente;
use App\Models\Caisse;
use App\Models\Offert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OffertController extends Controller
{
    //index
    public function index()
    {
        try {
            $data_offerts = Offert::with([
                'vente',
                'produit',
                'variante',
                'userApprouved',
                'vente.produits' => function ($query) {
                    $query->where('offert', true);
                }

            ])->get();


            // recuperer la liste des caisses
            $caisses = Caisse::all();

            return view('backend.pages.vente.offert.index', compact('data_offerts', 'caisses'));
        } catch (\Throwable $e) {
            # code...
            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement de la page.' . $e->getMessage());
        }
    }


    // get offert no approuved
    public function getOffertNoApprouved()
    {
        try {
            $offerts = Offert::whereNull('offert_statut')
                ->with([
                    'vente',
                    'produit',
                    'variante',
                    'vente.produits' => function ($query) {
                        $query->where('offert', true);
                    },

                    'vente.caisse',
                    'vente.user',
                    'userApprouved'


                ])
                ->get();



            return response()->json([
                'offerts' => $offerts
            ]);
        } catch (\Throwable $e) {
            # code...
            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement de la page.' . $e->getMessage());
        }
    }


    // approuved offert
    public function approuvedOffert(Request $request)
    {
        try {

            // recuperer les request parametres
            $id = $request->offert;
            $approuved = $request->approuved;


            // id offert
            $offert = Offert::find($id);
            // id vente offert
            $vente = Vente::find($offert->vente_id);
            // verifier si l'offert existe
            if (!$offert) {
                return redirect()->back()->with('error', 'L\'offert ou la vente n\'existe pas.');
            }

            // mettre a jour l'offert
            $offert->offert_statut = $approuved; // approuved ou non
            $offert->statut_view = true; // 
            $offert->user_approuved = auth()->user()->id; // l'utilisateur qui a approuvé ou rejeté l'offert
            $offert->date_approuved = now(); // l'utilisateur qui a approuvé ou rejeté l'offert
            $offert->save();


            // // mettre a jour le produit offert de la vente dans la table vente_produit
            $vente->produits()->where('produit_id', $offert->produit_id)
                ->where('offert', true)
                ->update([
                    'offert_statut' => $approuved
                ]);

            // si l'offert n'est pas approuvé, on va ajouter le total du produit dans la vente
            if ($approuved == 0) {
                // recuperer le produit de l'offert
                $produit = $vente->produits()
                    ->where('produit_id', $offert->produit_id)
                    ->where('offert', true)
                    ->whereNull('offert_statut')
                    ->first();
                $vente->total += $produit->total;
                $vente->save();
            }


            if (isset($approuved) && $approuved == 1) {
                return redirect()->back()->with('success', 'L\'offert a été approuvé avec succès.');
            } else {
                return redirect()->back()->with('success', 'L\'offert a été rejeté avec succès.');
            }
        } catch (\Throwable $e) {
            # code...
            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement de la page.' . $e->getMessage());
        }
    }
}
