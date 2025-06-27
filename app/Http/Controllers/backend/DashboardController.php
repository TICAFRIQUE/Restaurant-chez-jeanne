<?php

namespace App\Http\Controllers\backend;

use Carbon\Carbon;
use App\Models\Vente;
use App\Models\Depense;
use App\Models\Produit;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }




    //
    public function index(Request $request)
    {
        // Vérifier si l'utilisateur a le rôle 'caisse'
        if ($request->user()->hasRole(['caisse', 'supercaisse'])) {
            // Vérifier si l'utilisateur n'a pas sélectionné de caisse
            if (Auth::user()->caisse_id === null) {
                // Rediriger vers la page de sélection de caisse
                return redirect()->route('caisse.select')->with('warning', 'Veuillez sélectionner une caisse avant d\'accéder à l\'application.');
            }
            // Rediriger vers la page de vente si une caisse est sélectionnée
            return redirect()->route('vente.index');
        }

        ## statistique Liste des ventes
        // Liste des commandes en attente
        $commandesEnAttente = Commande::where('statut', 'en attente')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();


        //Liste des produit les plus vendus
        $produitsLesPlusVendus = Produit::withCount('ventes')
            ->withSum('ventes', 'produit_vente.prix_unitaire')
            ->orderBy('ventes_count', 'desc')
            ->having('ventes_count', '>', 0)
            ->take(10)
            ->get()
            ->map(function ($produit) {
                // Renommer l'attribut calculé dans la collection
                $produit->total_ventes = $produit->ventes_sum_produit_venteprix_unitaire;
                return $produit;
            });

        // statistique chiffre pour card
        // Nombre de commandes
        $nombreCommandes = Commande::count();

        // Montant total des ventes annee en cours
        $montantTotalVentes = Vente::whereYear('date_vente', Carbon::now()->year)
            ->sum('montant_total');

        // Montant total des dépenses annee en cours
        $montantTotalDepenses = Depense::whereYear('date_depense', Carbon::now()->year)
            ->sum('montant');

        /// Montant total des ventes mois en cours 
        $montantTotalVentesMois = Vente::whereMonth('date_vente', Carbon::now()->month)
            ->sum('montant_avant_remise');

        // Montant total des ventes annee en cours
        $montantTotalDepensesMois = Depense::whereMonth('date_depense', Carbon::now()->month)
            ->sum('montant');

        // Produits en alerte
        $produitsEnAlerte = Produit::where('stock', '=', 'stock_alerte')->get()->count();

        // dd($montantTotalVentes);

        // Chiffre d'affaire par mois avec apexchart
        // $chiffreAffaireParMois = Vente::selectRaw('EXTRACT(MONTH FROM date_vente) as mois, SUM(montant_total) as chiffre_affaire')
        //     ->groupBy('mois')
        //     ->pluck('chiffre_affaire', 'mois');
        // dd($chiffreAffaireParMois);

        // $revenus = DB::table('ventes')
        //     ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mois, SUM(montant_total) as total_revenu")
        //     ->groupBy('mois')
        //     ->orderBy('mois')
        //     ->get();

        $revenus = DB::table('ventes')
            ->selectRaw("MONTHNAME(date_vente) as mois, MONTH(date_vente) as mois_num, SUM(montant_total) as total_revenu")
            ->groupBy('mois', 'mois_num')
            ->orderBy('mois_num')
            ->get();


        // Recuperer les mois et Traduire les mois en français avec Carbon
        $labels = $revenus->map(function ($revenu) {
            return Carbon::create()->month($revenu->mois_num)->locale('fr')->translatedFormat('F');
        });
        $data = $revenus->pluck('total_revenu'); // Revenus correspondants

        // dd($labels, $data);



        // dd($produitsLesPlusVendus->toArray());
        return view('backend.pages.index', compact(
            'commandesEnAttente',
            'produitsLesPlusVendus',
            'nombreCommandes',
            'montantTotalVentes', // Montant total des ventes annee en cours
            'montantTotalDepenses', // Montant total des depenses annee en cours
            'montantTotalVentesMois', // Montant total des ventes mois en cours
            'montantTotalDepensesMois',// Montant total des depenses mois en cours
            'produitsEnAlerte',

            // 'chiffreAffaireParMois',
            'labels',
            'data'
        ));
    }
}
