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
                'vente.produits' => function ($query) {
                    $query->where('offert', true);
                }

            ])->get();


            // recuperer la liste des caisses
            $caisses = Caisse::all();

            return view('backend.pages.vente.offert.index', compact('data_offerts' , 'caisses'));
        } catch (\Throwable $e) {
            # code...
            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement de la page.' . $e->getMessage());
        }
    }


    // get offert no approuved
    public function getOffertNoApprouved()
    {
        try {
            $offerts = Offert::whereNull('approuved_at')
                ->with(['vente', 'produit' , 'variante' , 'vente.produits' => function ($query) {
                    $query->where('offert', true);
                }, 'vente.client', 'vente.caisse', 'vente.user'
                
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



}
