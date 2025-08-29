<?php

namespace App\Http\Controllers\backend\rapport;

use Carbon\Carbon;
use App\Models\Achat;
use App\Models\Vente;
use App\Models\Caisse;
use App\Models\Depense;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Inventaire;
use App\Models\ProduitVente;
use Illuminate\Http\Request;
use App\Models\ProduitSortie;
use App\Models\CategorieDepense;
use App\Models\InventaireProduit;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RapportController extends Controller
{
    //NO USE
    public function categorie(Request $request)
    {
        try {
            // Récupérer uniquement les catégories 'bar' et 'menu'
            $categories = DB::table('categories')
                ->whereIn('name', ['Bar', 'Menu'])
                ->get();

            // Ajouter les catégories à la vue
            $data['categories'] = $categories;
            // Récupération des chiffres d'affaires par catégorie
            $chiffresAffaires = DB::table('ventes')
                ->join('produit_vente', 'ventes.id', '=', 'produit_vente.vente_id')
                ->join('produits', 'produit_vente.produit_id', '=', 'produits.id')
                ->join('categories', 'produits.categorie_id', '=', 'categories.id')
                ->select('categories.name as categorie', DB::raw('SUM(ventes.montant_total) as chiffre_affaires'))
                ->groupBy('categories.id', 'categories.name');

            // Appliquer les filtres si présents
            if ($request->filled('categorie')) {
                $chiffresAffaires->where('categories.id', $request->categorie);
            }

            if ($request->filled('date_debut') && $request->filled('date_fin')) {
                $chiffresAffaires->whereBetween('ventes.date_vente', [$request->date_debut, $request->date_fin]);
            } elseif ($request->filled('date_debut')) {
                $chiffresAffaires->where('ventes.date_vente', '>=', $request->date_debut);
            } elseif ($request->filled('date_fin')) {
                $chiffresAffaires->where('ventes.date_vente', '<=', $request->date_fin);
            }

            // Exécuter la requête et obtenir les résultats
            $resultats = $chiffresAffaires->get();


            return view('backend.pages.rapport.categorie', compact('resultats', 'categories'));
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur est survenue lors de la récupération des chiffres d\'affaires : ' . $e->getMessage()
            ], 500);
        }
    }

    //NO USE    
    public function produits(Request $request)
    {
        try {
            // $query = Produit::with(['categorie', 'ventes', 'achats' => function ($query) {
            //     $query->where('statut', 'active')->orderBy('created_at', 'asc');
            // }])
            // ->has('ventes')  // Ajoute cette ligne pour préciser les produits avec au moins une vente
            // ->withCount('ventes')
            // ->withSum('ventes as quantite_vendue', 'produit_vente.quantite')
            // ->withSum('ventes as montant_total_ventes', DB::raw('produit_vente.quantite * produit_vente.prix_unitaire'));



            //     ->select(
            //         'produits.id',
            //         'produits.nom',
            //         'categories.name as categorie',
            //         DB::raw('SUM(produit_vente.quantite) as quantite_vendue'),
            //         DB::raw('SUM(produit_vente.quantite * produit_vente.prix_unitaire) as montant_total_ventes')
            //     )
            //     ->join('categories', 'produits.categorie_id', '=', 'categories.id')
            //     ->leftJoin('produit_vente', 'produits.id', '=', 'produit_vente.produit_id')
            //     ->leftJoin('ventes', 'produit_vente.vente_id', '=', 'ventes.id')
            //     ->whereIn('categories.famille', ['menu', 'bar'])
            //     ->groupBy('produits.id', 'produits.nom', 'categories.name');

            // // Ajout du stock disponible pour les produits de type 'bar'
            // $query->addSelect(DB::raw('CASE WHEN categories.famille = "bar" THEN produits.stock ELSE NULL END as stock_disponible'));

            // // Application des filtres
            // if ($request->filled('categorie')) {
            //     $query->whereHas('categorie', function($q) use ($request) {
            //         $q->where('famille', $request->categorie);
            //     });
            // }

            // if ($request->filled(['date_debut', 'date_fin'])) {
            //     $query->whereHas('ventes', function($q) use ($request) {
            //         $q->whereBetween('created_at', [$request->date_debut, $request->date_fin]);
            //     });
            // } elseif ($request->filled('date_debut')) {
            //     $query->whereHas('ventes', function($q) use ($request) {
            //         $q->where('created_at', '>=', $request->date_debut);
            //     });
            // } elseif ($request->filled('date_fin')) {
            //     $query->whereHas('ventes', function($q) use ($request) {
            //         $q->where('created_at', '<=', $request->date_fin);
            //     });
            // } else {
            //     $query->whereHas('categorie', function($q) {
            //         $q->whereIn('famille', ['bar', 'menu']);
            //     });
            // }

            // $produits = $query->get();




            $query = Produit::with(['categorie', 'ventes', 'achats'])
                ->has('ventes')  // Précise les produits avec au moins une vente
                ->withCount('ventes')
                ->withSum('ventes as quantite_vendue', 'produit_vente.quantite')
                ->withSum('ventes as montant_total_ventes', DB::raw('produit_vente.quantite * produit_vente.prix_unitaire'));

            // Application des filtres
            if ($request->filled('categorie')) {
                $query->whereHas('categorie', function ($q) use ($request) {
                    $q->where('famille', $request->categorie);
                });
            }

            if ($request->filled(['date_debut', 'date_fin'])) {
                $query->whereHas('ventes', function ($q) use ($request) {
                    $q->whereBetween('ventes.date_vente', [$request->date_debut, $request->date_fin]); // Préciser la table 'ventes'
                });
            } elseif ($request->filled('date_debut')) {
                $query->whereHas('ventes', function ($q) use ($request) {
                    $q->where('ventes.date_vente', '>=', $request->date_debut); // Préciser la table 'ventes'
                });
            } elseif ($request->filled('date_fin')) {
                $query->whereHas('ventes', function ($q) use ($request) {
                    $q->where('ventes.date_vente', '<=', $request->date_fin); // Préciser la table 'ventes'
                });
            } else {
                $query->whereHas('categorie', function ($q) {
                    $q->whereIn('famille', ['bar', 'menu']);
                });
            }

            $produits = $query->orderBy();

            // dd($produits->toArray());
            // Récupération des catégories pour le filtre

            $data_categorie = Categorie::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')
                ->whereIn('type', ['bar', 'menu'])
                ->OrderBy('position', 'DESC')->get();

            $categorie_famille = Categorie::whereNull('parent_id')->whereIn('type', ['bar', 'menu'])->get();

            return view('backend.pages.rapport.produit', compact('produits', 'data_categorie', 'categorie_famille'));
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'erreur',
                'message' => 'Une erreur est survenue lors de la récupération des données des produits : ' . $e->getMessage()
            ], 500);
        }
    }


    // public function exploitation(Request $request)
    // {
    //     try {
    //         $venteQuery = Vente::query();
    //         $depenseQuery = Depense::query();

    //         // Application des filtres de date
    //         if ($request->filled(['date_debut', 'date_fin'])) {
    //             $venteQuery->whereBetween('created_at', [$request->date_debut, $request->date_fin]);
    //             $depenseQuery->whereBetween('date_depense', [$request->date_debut, $request->date_fin]);
    //         } elseif ($request->filled('date_debut')) {
    //             $venteQuery->where('created_at', '>=', $request->date_debut);
    //             $depenseQuery->where('date_depense', '>=', $request->date_debut);
    //         } elseif ($request->filled('date_fin')) {
    //             $venteQuery->where('created_at', '<=', $request->date_fin);
    //             $depenseQuery->where('date_depense', '<=', $request->date_fin);
    //         }

    //         $totalVentes = $venteQuery->sum('montant_total');
    //         $totalDepenses = $depenseQuery->sum('montant');

    //         $benefice = $totalVentes - $totalDepenses;
    //         $ratio = $totalVentes > 0 ? ($benefice / $totalVentes) * 100 : 0;


    //         return view('backend.pages.rapport.exploitation', compact('totalVentes', 'totalDepenses', 'benefice', 'ratio'));
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'erreur',
    //             'message' => 'Une erreur est survenue lors du calcul du compte d\'exploitation : ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


    /**
     * Generate an exploitation report based on sales and expenses.
     *
     * This function retrieves categories of expenses and creates base queries
     * for sales and expenses. It applies date filters, filters expenses by category,
     * calculates sales and expenses totals, and computes benefits and ratios.
     * Finally, it prepares data to be sent to the exploitation view for rendering.
     *
     * @param Request $request The HTTP request containing optional filters such as
     *                         'date_debut', 'date_fin', and 'categorie_depense'.
     *
     * @return \Illuminate\View\View|string Returns a view with exploitation data or
     *                                      an error message in case of an exception.
     */

    public function exploitation(Request $request)
    {
        try {
            // 1. Récupération des catégories de dépense
            $categories_depense = CategorieDepense::with('libelleDepenses')->orderBy('libelle')->get(); //categorie pour recuperer les libelles de cat_depense selectionner
            $categories = CategorieDepense::with('libelleDepenses')->orderBy('libelle')->get(); // pour le filtre des cat_depense



            $venteQuery = Vente::query(); // toutes les ventes
            $depenseQuery = Depense::query(); // toutes les depenses



            // Vérifier si aucune période ou date spécifique n'a été fournie
            if (!$request->filled('periode') && !$request->filled('date_debut') && !$request->filled('date_fin')) {
                $venteQuery->whereMonth('ventes.date_vente', Carbon::now()->month)
                    ->whereYear('ventes.date_vente', Carbon::now()->year);
                $depenseQuery->whereMonth('date_depense', Carbon::now()->month)
                    ->whereYear('date_depense', Carbon::now()->year);
            }


            // 3. Application des filtres de date
            // Formatage des dates
            $dateDebut = $request->filled('date_debut') ? Carbon::parse($request->date_debut)->format('Y-m-d') : null;
            $dateFin = $request->filled('date_fin') ? Carbon::parse($request->date_fin)->format('Y-m-d') : null;

            // Application des filtres de date
            if ($dateDebut && $dateFin) {
                $venteQuery->whereBetween('ventes.date_vente', [$dateDebut, $dateFin]);
                $depenseQuery->whereBetween('date_depense', [$dateDebut, $dateFin]);
            } elseif ($dateDebut) {
                $venteQuery->where('ventes.date_vente', '=', $dateDebut);
                $depenseQuery->where('date_depense', '=', $dateDebut);
            } elseif ($dateFin) {
                $venteQuery->where('ventes.date_vente', '=', $dateFin);
                $depenseQuery->where('date_depense', '=', $dateFin);
            }

            // Application du filtre de periode
            // periode=> jour, semaine, mois, année

            $periode = $request->input('periode'); // request periode

            if ($request->filled('periode')) {
                if ($periode == 'jour') {
                    $venteQuery->whereDate('ventes.date_vente', Carbon::today());
                    $depenseQuery->whereDate('date_depense', Carbon::today());
                } elseif ($periode == 'semaine') {
                    $venteQuery->whereBetween('ventes.date_vente', [Carbon::today()->startOfWeek(), Carbon::today()->endOfWeek()]);
                    $depenseQuery->whereBetween('date_depense', [Carbon::today()->startOfWeek(), Carbon::today()->endOfWeek()]);
                } elseif ($periode == 'mois') {
                    $venteQuery->whereMonth('ventes.date_vente', Carbon::now()->month)
                        ->whereYear('ventes.date_vente', Carbon::now()->year);
                    $depenseQuery->whereMonth('date_depense', Carbon::now()->month)
                        ->whereYear('date_depense', Carbon::now()->year);
                } elseif ($periode == 'annee') {
                    $venteQuery->whereYear('ventes.date_vente', Carbon::now()->year);
                    $depenseQuery->whereYear('date_depense', Carbon::now()->year);
                }
            }



            // 4. Filtrage par catégorie de dépense
            if ($request->filled('categorie_depense')) {
                $categories_depense = $categories_depense->where('id', $request->categorie_depense);
                $depenseQuery->where('categorie_depense_id', $request->categorie_depense);
                $categories = CategorieDepense::with('libelleDepenses')->orderBy('libelle')->get(); // si une categorie on affiche toute les categories
            }

            // 5. Somme des dépenses par libellé et catégorie
            $depenses = $depenseQuery->with(['libelle_depense', 'categorie_depense'])
                ->select('libelle_depense_id', 'categorie_depense_id', DB::raw('SUM(montant) as total_montant'))
                ->groupBy('libelle_depense_id', 'categorie_depense_id')
                ->get();

            // 6. Groupement des dépenses par catégorie
            $depensesParCategorie = $depenses->groupBy('categorie_depense.libelle');

            // 7. Total des ventes globale 
            ##Total des montants avant remise
            $totalVenteBrut = $venteQuery->sum('montant_avant_remise');
            ##Total des montants remise
            $totalRemise = $venteQuery->sum('montant_remise');
            ##Total des montants apres remise
            $TotalVenteNet = $venteQuery->sum('montant_total');


            // 8. Calcul des ventes par famille (bar et menu) avec la table pivot
            // $ventesParFamille = $venteQuery->with('produits.categorie')
            //     ->select('categories.famille', DB::raw('SUM(produit_vente.quantite * produit_vente.prix_unitaire) as total_ventes'))
            //     ->join('produit_vente', 'ventes.id', '=', 'produit_vente.vente_id')
            //     ->join('produits', 'produit_vente.produit_id', '=', 'produits.id')
            //     ->join('categories', 'produits.categorie_id', '=', 'categories.id')
            //     ->whereIn('categories.famille', ['bar', 'menu'])
            //     ->groupBy('categories.famille')
            //     ->get()
            //     ->pluck('total_ventes', 'famille')
            //     ->toArray();


            $ventesParFamille = (clone $venteQuery)->with('produits.categorie')
                ->select('categories.famille', DB::raw('SUM(produit_vente.quantite * produit_vente.prix_unitaire) as total_ventes'))
                ->join('produit_vente', 'ventes.id', '=', 'produit_vente.vente_id')
                ->join('produits', 'produit_vente.produit_id', '=', 'produits.id')
                ->join('categories', 'produits.categorie_id', '=', 'categories.id')
                ->whereIn('categories.famille', ['bar', 'menu'])
                ->groupBy('categories.famille')
                ->get()
                ->pluck('total_ventes', 'famille')
                ->toArray();

            $ventesMenu = (clone $venteQuery)
                ->join('plat_vente', 'ventes.id', '=', 'plat_vente.vente_id')
                ->join('plats', 'plat_vente.plat_id', '=', 'plats.id')
                ->select(DB::raw('SUM(plat_vente.quantite * plat_vente.prix_unitaire) as total_ventes'))
                ->first();


            // dd($ventesParFamille , $ventesMenu);


            // Calcul du montant total des plats vendus
            // $ventesMenu = $venteQuery->with('plats.categorieMenu')
            //     ->select(DB::raw('SUM(plat_vente.quantite * plat_vente.prix_unitaire) as total_ventes'))
            //     ->join('plat_vente', 'ventes.id', '=', 'plat_vente.vente_id')
            //     ->join('plats', 'plat_vente.plat_id', '=', 'plats.id')
            //     ->get()
            //     ->mapWithKeys(function ($item) {
            //         return ['vente_menu' => $item->total_ventes]; // Remplacez 'alias_desire' par votre clé
            //     })
            //     ->toArray();

            // $ventesMenu = $venteQuery
            //     ->join('plat_vente', 'ventes.id', '=', 'plat_vente.vente_id')
            //     ->join('plats', 'plat_vente.plat_id', '=', 'plats.id')
            //     ->selectRaw('SUM(plat_vente.quantite * plat_vente.prix_unitaire) as total_ventes')
            //     ->first(); // Récupérer une seule ligne



            // $ventesMenu = $venteQuery
            //     ->join('plat_vente', 'ventes.id', '=', 'plat_vente.vente_id')
            //     ->join('plats', 'plat_vente.plat_id', '=', 'plats.id')
            //     ->select(DB::raw('SUM(plat_vente.quantite * plat_vente.prix_unitaire) as total_ventes'))
            //     ->first();





            // montant des ventes realisés par famille 
            $venteBar = $ventesParFamille['bar'] ?? 0; // vente du bar
            $venteMenu = $ventesParFamille['menu'] ?? 0; // vente du restaurant
            $ventePlatMenu = $ventesMenu->total_ventes ?? 0; // vente du menu du jour
            // $ventePlatMenu = $ventesMenu['vente_menu'] ?? 0;


            // dd($ventePlatMenu);


            ## modifié 13-03-2025 ##
            // total vente des familles
            // $totalVentes = $venteBar + $venteMenu + $ventePlatMenu;
            $totalVentes = $TotalVenteNet;


            // 9. Calcul des totaux et ratios
            $totalDepenses = $depenses->sum('total_montant');
            $benefice = $totalVentes - $totalDepenses;

            $ratio = $totalVentes > 0 ? ($benefice / $totalVentes) * 100 : 0;

            // 10. Calcul benefice et du ratio pour chaque famille
            $beneficeBar = $venteBar - $totalDepenses;
            $beneficeMenu = $venteMenu - $totalDepenses;

            $ratioBar = $venteBar > 0 ? ($beneficeBar / $venteBar) * 100 : 0;
            $ratioMenu = $venteMenu > 0 ? ($beneficeMenu / $venteMenu) * 100 : 0;

            // 11. Préparation des données pour la vue
            $dataParFamille = [
                'Bar' => [
                    'ventes' => $venteBar,
                    'benefice' => $beneficeBar,
                    'ratio' => $ratioMenu
                ],
                'Restaurant' => [
                    'ventes' => $venteMenu,
                    'benefice' => $beneficeMenu,
                    'ratio' => $ratioBar
                ],

                'Menu' => [
                    'ventes' => $ventePlatMenu,
                    // 'benefice' => $beneficeBar,
                    // 'ratio' => $ratioBar
                ],
            ];
            // Concaténation des deux résultats
            // $dataParFamille = array_merge($ventesParFamille, $ventesMenu);

            // Résultat final
            // return $resultatFinal;

            // dd($dataParFamille);

            return view('backend.pages.rapport.exploitation', compact('totalVentes', 'totalDepenses', 'benefice', 'ratio', 'categories_depense', 'depensesParCategorie', 'dataParFamille', 'categories', 'totalVenteBrut', 'totalRemise'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Affiche le détail des achats ou des dépenses d'une catégorie de dépense
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(Request $request)
    {
        try {
            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');
            $categorieDepense = $request->input('categorie_depense');  // ID de la catégorie de depense selectionnée
            $libelleDepense = $request->input('libelle_depense');

            $produitId = $request->input('produitId'); // Id du produit pour recuperer tous les achats du produits


            // Vérification de la catégorie de dépense
            $categorieDepenseExiste = CategorieDepense::where('id', $categorieDepense)->first();

            if ($categorieDepenseExiste->slug === 'achats') {
                // Récupération des produits avec des achats dans la période spécifiée
                $produits = Produit::whereHas('achats', function ($query) use ($dateDebut, $dateFin) {
                    if ($dateDebut && $dateFin) {
                        $query->whereBetween('date_achat', [$dateDebut, $dateFin]);
                    } elseif ($dateDebut) {
                        $query->where('date_achat', '>=', $dateDebut);
                    } elseif ($dateFin) {
                        $query->where('date_achat', '<=', $dateFin);
                    }
                })->with(['achats' => function ($query) use ($dateDebut, $dateFin) {
                    if ($dateDebut && $dateFin) {
                        $query->whereBetween('date_achat', [$dateDebut, $dateFin]);
                    } elseif ($dateDebut) {
                        $query->where('date_achat', '>=', $dateDebut);
                    } elseif ($dateFin) {
                        $query->where('date_achat', '<=', $dateFin);
                    }
                }])->get()
                    ->groupBy('id');

                $produitsGroupes = $produits->map(function ($groupe) {
                    $premierProduit = $groupe->first();
                    return [
                        'id' => $premierProduit->id,
                        'nom' => $premierProduit->nom,
                        'stock_restant' => $premierProduit->stock,
                        'achats' => $groupe->flatMap->achats,
                        'quantite_achat' => $groupe->flatMap->achats->sum('quantite'),

                        'prix_total_format' => $groupe->flatMap->achats->sum(function ($achat) {
                            return $achat->prix_total_format;
                        })
                    ];
                })->values();

                // total des somme prix_total_format achats
                $totalAchats = $produitsGroupes->sum(function ($achat) {
                    return $achat['prix_total_format'];
                });

                // dd($totalAchats);

                return view('backend.pages.rapport.detail_achat', compact('produitsGroupes', 'dateDebut', 'dateFin', 'totalAchats'));
            } else {
                // Récupération des dépenses de la catégorie sélectionnée dans la période spécifiée
                $depenses = Depense::where('categorie_depense_id', $categorieDepense)
                    ->where('libelle_depense_id', $libelleDepense)
                    ->when($dateDebut && $dateFin, function ($query) use ($dateDebut, $dateFin) {
                        return $query->whereBetween('date_depense', [$dateDebut, $dateFin]);
                    })
                    ->when($dateDebut && !$dateFin, function ($query) use ($dateDebut) {
                        return $query->where('date_depense', '>=', $dateDebut);
                    })
                    ->when(!$dateDebut && $dateFin, function ($query) use ($dateFin) {
                        return $query->where('date_depense', '<=', $dateFin);
                    })
                    ->with(['categorie_depense', 'libelle_depense'])
                    ->get();
                // ->groupBy('libelle_depense_id')
                // ->map(function ($groupe) {
                //     return [
                //         'libelle' => $groupe->first()->libelle_depense->libelle,
                //         'montant_total' => $groupe->sum('montant'),
                //         'details' => $groupe
                //     };
                // })
                // ->values();
                // dd($depenses->toArray());

                return view('backend.pages.rapport.detail_depense', compact('depenses', 'dateDebut', 'dateFin', 'categorieDepense'));
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }



    /**
     * Affiche les ventes pour une periode donnee, un type de famille, une caisse, etc.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    // public function vente(Request $request)
    // {
    //     try {
    //         $dateDebut = $request->input('date_debut');
    //         $dateFin = $request->input('date_fin');
    //         $caisseId = $request->input('caisse_id');
    //         $categorieFamille = $request->input('categorie_famille');
    //         $periode = $request->input('periode');
    //         // $categorieMenu = 'plat_menu';


    //         //Pour les vente bar et restaurant
    //         $query = Vente::with(['produits.categorie', 'plats.categorieMenu', 'caisse']);

    //         // pour la vente des plats menu
    //         $queryMenu = Vente::with(['plats.categorieMenu', 'caisse']);


    //         if ($dateDebut && $dateFin) {
    //             $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
    //             $queryMenu->whereBetween('date_vente', [$dateDebut, $dateFin]);
    //         } elseif ($dateDebut) {
    //             $query->where('date_vente', '>=', $dateDebut);
    //             $queryMenu->where('date_vente', '>=', $dateDebut);
    //         } elseif ($dateFin) {
    //             $query->where('date_vente', '<=', $dateFin);
    //             $queryMenu->where('date_vente', '<=', $dateFin);
    //         }



    //         if ($caisseId) {
    //             $query->where('caisse_id', $caisseId);
    //             $queryMenu->where('caisse_id', $caisseId);
    //         }


    //         // Application du filtre de periode
    //         // periode=> jour, semaine, mois, année
    //         if ($request->filled('periode')) {
    //             $dates = match ($periode) {
    //                 'jour' => [Carbon::today(), Carbon::today()],
    //                 'semaine' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
    //                 'mois' => [Carbon::now()->month, Carbon::now()->year], // Stocke mois et année pour `whereMonth`
    //                 'annee' => Carbon::now()->year, // Stocke année pour `whereYear`
    //                 default => null,
    //             };

    //             if ($dates) {
    //                 if ($periode == 'jour') {
    //                     $query->whereDate('date_vente', $dates[0]);
    //                     $queryMenu->whereDate('date_vente', $dates[1]);
    //                 } elseif ($periode == 'semaine') {
    //                     $query->whereBetween('date_vente', $dates);
    //                     $queryMenu->whereBetween('date_vente', $dates);
    //                 } elseif ($periode == 'mois') {
    //                     $query->whereMonth('date_vente', $dates[0])->whereYear('date_vente', $dates[1]);
    //                     $queryMenu->whereMonth('date_vente', $dates[0])->whereYear('date_vente', $dates[1]);
    //                 } elseif ($periode == 'annee') {
    //                     $query->whereYear('date_vente', $dates);
    //                     $queryMenu->whereYear('date_vente', $dates);
    //                 }
    //             }
    //         }


    //         // pour les vente bar et restaurant
    //         $ventes = $query
    //             ->withWhereHas('produits', function ($query) {
    //                 $query->where(function ($q) {
    //                     $q->where(function ($sub) {
    //                         $sub->where('offert', 1)
    //                             ->where('offert_statut', 0);
    //                     })
    //                         ->orWhere(function ($sub) {
    //                             $sub->where('offert', 0);
    //                         });
    //                 });
    //             })
    //             ->get();



    //         // pour la vente des plats menu
    //         $ventesMenu = $queryMenu->get();

    //         //pour les vente avec produits offerts
    //         $ventesOffertes = $query->withWhereHas('produits', function ($query) {
    //             $query->where('offert', 1)->where('offert_statut', 1);
    //         })->get();

    //         // dd($ventesOffertes->toArray());

    //         // Produits restaurant et bar
    //         $produitsVendus = $ventes->flatMap(fn($vente) => $vente->produits)
    //             ->groupBy('id')
    //             ->map(function ($groupe) use ($categorieFamille) {
    //                 $produit = $groupe->first();

    //                 if ($categorieFamille && $categorieFamille !== $produit->categorie->famille) {
    //                     return null;
    //                 }

    //                 return [
    //                     'details' => $groupe,
    //                     'id' => $produit->id,
    //                     'code' => $produit->code,
    //                     'stock' => $produit->stock,
    //                     'designation' => $produit->nom,
    //                     'categorie' => $produit->categorie->name,
    //                     'famille' => $produit->categorie->famille,
    //                     'quantite_vendue' => $groupe->sum('pivot.quantite'),
    //                     'variante' => $groupe->first()->pivot->variante_id,
    //                     'prix_vente' => $groupe->first()->pivot->prix_unitaire,
    //                     'montant_total' => $groupe->sum(
    //                         fn($item) =>
    //                         $item->pivot->quantite * $item->pivot->prix_unitaire
    //                     ),
    //                 ];
    //             })
    //             ->filter()
    //             ->values();


    //         // Plats menu
    //         $platsVendus = $ventesMenu->flatMap(fn($vente) => $vente->plats)
    //             ->groupBy('id')
    //             ->map(function ($groupe) use ($categorieFamille) {
    //                 $plat = $groupe->first();

    //                 if ($categorieFamille && $categorieFamille !== 'menu du jour') {
    //                     return null;
    //                 }

    //                 return [
    //                     'id' => $plat->id,
    //                     'code' => $plat->code,
    //                     'stock' => 100,
    //                     'designation' => $plat->nom,
    //                     'categorie' => $plat->categorieMenu->nom,
    //                     'famille' => 'Menu du jour',
    //                     'quantite_vendue' => $groupe->sum('pivot.quantite'),
    //                     'prix_vente' => $groupe->first()->pivot->prix_unitaire,
    //                     'montant_total' => $groupe->sum(
    //                         fn($item) =>
    //                         $item->pivot->quantite * $item->pivot->prix_unitaire
    //                     ),
    //                 ];
    //             })
    //             ->filter()
    //             ->values();


    //         // Produits offerts
    //         $produitsVendusOfferts = $ventesOffertes->flatMap(fn($vente) => $vente->produits)
    //             ->groupBy('id')
    //             ->map(function ($groupe) use ($categorieFamille) {
    //                 $produit = $groupe->first();

    //                 if ($categorieFamille && $categorieFamille !== 'offerts') {
    //                     return null;
    //                 }

    //                 return [
    //                     'details' => $groupe,
    //                     'id' => $produit->id,
    //                     'code' => $produit->code,
    //                     'stock' => $produit->stock,
    //                     'designation' => $produit->nom,
    //                     'categorie' => $produit->categorie->name,
    //                     'famille' => 'Offerts',
    //                     'quantite_vendue' => $groupe->sum('pivot.quantite'),
    //                     'variante' => $groupe->first()->pivot->variante_id,
    //                     'prix_vente' => $groupe->first()->pivot->prix_unitaire,
    //                     'montant_total' => $groupe->sum(
    //                         fn($item) =>
    //                         $item->pivot->quantite * $item->pivot->prix_unitaire
    //                     ),
    //                 ];
    //             })
    //             ->filter()
    //             ->values();


    //         // Fusion
    //         $produitsVendus = $produitsVendus
    //             ->concat($platsVendus)
    //             ->concat($produitsVendusOfferts);


    //         $caisses = Caisse::all();
    //         $famille = Categorie::whereNull('parent_id')->whereIn('type', ['bar', 'menu'])->orderBy('name', 'DESC')->get();


    //         return view('backend.pages.rapport.vente', compact('platsVendus', 'produitsVendus', 'caisses', 'dateDebut', 'dateFin', 'caisseId', 'categorieFamille', 'famille'));
    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Une erreur s\'est produite : ' . $e->getMessage());
    //     }
    // }


    public function vente(Request $request)
    {
        try {
            $dateDebut       = $request->input('date_debut');
            $dateFin         = $request->input('date_fin');
            $caisseId        = $request->input('caisse_id');
            $categorieFamille = $request->input('categorie_famille');
            $periode         = $request->input('periode');

            // === BASE QUERIES ===
            $queryBase = Vente::query();
            $query     = Vente::with(['produits.categorie', 'plats.categorieMenu', 'caisse']);
            $queryMenu = Vente::with(['plats.categorieMenu', 'caisse']);
            $queryOffertes = Vente::with(['produits.categorie', 'caisse']);

            // === DATES ===
            if ($dateDebut && $dateFin) {
                $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
                $queryMenu->whereBetween('date_vente', [$dateDebut, $dateFin]);
                $queryOffertes->whereBetween('date_vente', [$dateDebut, $dateFin]);
            } elseif ($dateDebut) {
                $query->where('date_vente', '>=', $dateDebut);
                $queryMenu->where('date_vente', '>=', $dateDebut);
                $queryOffertes->where('date_vente', '>=', $dateDebut);
            } elseif ($dateFin) {
                $query->where('date_vente', '<=', $dateFin);
                $queryMenu->where('date_vente', '<=', $dateFin);
                $queryOffertes->where('date_vente', '<=', $dateFin);
            }

            // === CAISSE ===
            if ($caisseId) {
                $query->where('caisse_id', $caisseId);
                $queryMenu->where('caisse_id', $caisseId);
                $queryOffertes->where('caisse_id', $caisseId);
            }

            // === PERIODE (jour / semaine / mois / année) ===
            if ($request->filled('periode')) {
                $dates = match ($periode) {
                    'jour'    => [Carbon::today(), Carbon::today()],
                    'semaine' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
                    'mois'    => [Carbon::now()->month, Carbon::now()->year],
                    'annee'   => Carbon::now()->year,
                    default   => null,
                };

                if ($dates) {
                    if ($periode == 'jour') {
                        $query->whereDate('date_vente', $dates[0]);
                        $queryMenu->whereDate('date_vente', $dates[1]);
                        $queryOffertes->whereDate('date_vente', $dates[0]);
                    } elseif ($periode == 'semaine') {
                        $query->whereBetween('date_vente', $dates);
                        $queryMenu->whereBetween('date_vente', $dates);
                        $queryOffertes->whereBetween('date_vente', $dates);
                    } elseif ($periode == 'mois') {
                        $query->whereMonth('date_vente', $dates[0])->whereYear('date_vente', $dates[1]);
                        $queryMenu->whereMonth('date_vente', $dates[0])->whereYear('date_vente', $dates[1]);
                        $queryOffertes->whereMonth('date_vente', $dates[0])->whereYear('date_vente', $dates[1]);
                    } elseif ($periode == 'annee') {
                        $query->whereYear('date_vente', $dates);
                        $queryMenu->whereYear('date_vente', $dates);
                        $queryOffertes->whereYear('date_vente', $dates);
                    }
                }
            }

            // === REQUÊTES ===

            // Ventes (hors produits offerts validés)
            $ventes = $query->withWhereHas('produits', function ($query) {
                $query->where(function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('offert', 1)->where('offert_statut', 0);
                    })->orWhere(function ($sub) {
                        $sub->where('offert', 0);
                    });
                });
            })->get();

            // Ventes menus
            $ventesMenu = $queryMenu->get();

            // Ventes avec produits offerts
            $ventesOffertes = $queryOffertes->withWhereHas('produits', function ($query) {
                $query->where('offert', 1)->where('offert_statut', 1);
            })->get();

            // === TRANSFORMATION ===

            // Produits bar & resto
            $produitsVendus = $ventes->flatMap(fn($vente) => $vente->produits)
                ->groupBy('id')
                ->map(function ($groupe) use ($categorieFamille) {
                    $produit = $groupe->first();

                    if ($categorieFamille && $categorieFamille !== $produit->categorie->famille) {
                        return null;
                    }

                    return [
                        'details'         => $groupe,
                        'id'              => $produit->id,
                        'code'            => $produit->code,
                        'stock'           => $produit->stock,
                        'designation'     => $produit->nom,
                        'categorie'       => $produit->categorie->name,
                        'famille'         => $produit->categorie->famille,
                        'quantite_vendue' => $groupe->sum('pivot.quantite'),
                        'variante'        => $groupe->first()->pivot->variante_id,
                        'prix_vente'      => $groupe->first()->pivot->prix_unitaire,
                        'montant_total'   => $groupe->sum(
                            fn($item) =>
                            $item->pivot->quantite * $item->pivot->prix_unitaire
                        ),
                    ];
                })->filter()->values();

            // Plats
            $platsVendus = $ventesMenu->flatMap(fn($vente) => $vente->plats)
                ->groupBy(fn($plat) => $plat->id . '-' . $plat->pivot->prix_unitaire) // Groupement par plat et prix
                ->map(function ($groupe) use ($categorieFamille) {
                    $plat = $groupe->first();

                    if ($categorieFamille && $categorieFamille !== 'menu du jour') {
                        return null;
                    }

                    return [
                        'id'              => $plat->id,
                        'code'            => $plat->code,
                        'stock'           => 100,
                        'designation'     => $plat->nom,
                        'categorie'       => $plat->categorieMenu->nom,
                        'famille'         => 'Menu du jour',
                        'quantite_vendue' => $groupe->sum('pivot.quantite'),
                        'prix_vente'      => $plat->pivot->prix_unitaire,
                        'montant_total'   => $groupe->sum(
                            fn($item) =>
                            $item->pivot->quantite * $item->pivot->prix_unitaire
                        ),
                    ];
                })->filter()->values();

            // Produits offerts
            $produitsVendusOfferts = $ventesOffertes->flatMap(fn($vente) => $vente->produits)
                ->groupBy('id')
                ->map(function ($groupe) use ($categorieFamille) {
                    $produit = $groupe->first();

                    if ($categorieFamille && $categorieFamille !== 'offerts') {
                        return null;
                    }

                    return [
                        'details'         => $groupe,
                        'id'              => $produit->id,
                        'code'            => $produit->code,
                        'stock'           => $produit->stock,
                        'designation'     => $produit->nom,
                        'categorie'       => $produit->categorie->name,
                        'famille'         => 'Offerts',
                        'quantite_vendue' => $groupe->sum('pivot.quantite'),
                        'variante'        => $groupe->first()->pivot->variante_id,
                        'prix_vente'      => $groupe->first()->pivot->prix_unitaire,
                        'montant_total'   => $groupe->sum(
                            fn($item) =>
                            $item->pivot->quantite * $item->pivot->prix_unitaire
                        ),
                    ];
                })->filter()->values();

            // Fusion finale
            $produitsVendus = $produitsVendus
                ->concat($platsVendus)
                ->concat($produitsVendusOfferts);

            // Autres données
            $caisses = Caisse::all();
            $famille = Categorie::whereNull('parent_id')
                ->whereIn('type', ['bar', 'menu'])
                ->orderBy('name', 'DESC')
                ->get();

            return view('backend.pages.rapport.vente', compact(
                'platsVendus',
                'produitsVendus',
                'caisses',
                'dateDebut',
                'dateFin',
                'caisseId',
                'categorieFamille',
                'famille'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur s\'est produite : ' . $e->getMessage());
        }
    }


    // function miseAJourStockVente()
    // {
    //     // Récupération des ventes dont la catégorie famille est "bar"
    //     $data = DB::table('produit_vente')
    //         ->join('produits', 'produit_vente.produit_id', '=', 'produits.id')
    //         ->join('categories', 'produits.categorie_id', '=', 'categories.id')
    //         ->where('categories.famille', 'bar') // Filtrer uniquement les produits de la famille "bar"
    //         ->select('produit_vente.id', 'produit_vente.produit_id', 'produit_vente.variante_id', 'produit_vente.quantite') // Sélectionner les champs nécessaires
    //         ->get();

    //     foreach ($data as $value) {
    //         // Vérifier si produit_id, variante_id et quantite existent pour éviter une erreur
    //         if (!isset($value->produit_id, $value->variante_id, $value->quantite)) {
    //             continue; // Ignore cette ligne et passe à la suivante
    //         }

    //         // Récupération de la quantité de la variante
    //         $quantite = DB::table('produit_variante')
    //             ->where('produit_id', $value->produit_id)
    //             ->where('variante_id', $value->variante_id)
    //             ->value('quantite');

    //         // Vérification pour éviter une division par zéro
    //         if (is_null($quantite) || $quantite == 0) {
    //             continue;
    //         }

    //         // Mise à jour de la quantité de bouteilles vendues dans la table produit_vente uniquement pour les produits de la catégorie "bar"
    //         DB::table('produit_vente')
    //             ->join('produits', 'produit_vente.produit_id', '=', 'produits.id')
    //             ->join('categories', 'produits.categorie_id', '=', 'categories.id')
    //             ->where('categories.famille', 'bar') // Se limiter aux produits de la famille "bar"
    //             ->where('produit_vente.id', $value->id) // Condition sur l'ID du produit_vente
    //             ->update([
    //                 'quantite_bouteille' => round($value->quantite / $quantite, 2),
    //             ]);
    //     }
    // }

    function miseAJourStockVente()
    {
        // Récupération des ventes de produits de la famille "bar" avec les variantes en une seule requête
        $data = DB::table('produit_vente')
            ->join('produits', 'produit_vente.produit_id', '=', 'produits.id')
            ->join('categories', 'produits.categorie_id', '=', 'categories.id')
            ->join('produit_variante', function ($join) {
                $join->on('produit_vente.produit_id', '=', 'produit_variante.produit_id')
                    ->on('produit_vente.variante_id', '=', 'produit_variante.variante_id');
            })
            ->where('categories.famille', 'bar')
            ->select(
                'produit_vente.id',
                'produit_vente.quantite',
                'produit_variante.quantite as quantite_variante'
            )
            ->get();

        // Préparation des mises à jour en batch
        foreach ($data as $value) {
            if ($value->quantite_variante && $value->quantite_variante > 0) {
                DB::table('produit_vente')
                    ->where('id', $value->id)
                    ->update([
                        'quantite_bouteille' => round($value->quantite / $value->quantite_variante, 2),
                    ]);
            }
        }
    }



    // public function miseAjourProduitInventaire()
    // {
    //     $data = DB::table('inventaire_produit')->get(); // Récupérer les données

    //     foreach ($data as $value) {
    //         // Calculer le stock théorique
    //         $sth = ($value->stock_dernier_inventaire + $value->stock_initial) - $value->stock_vendu;

    //         // Calculer l'écart
    //         $ecart = $value->stock_physique - $sth;

    //         // Déterminer l'état du stock
    //         if ($sth == 0 && $value->stock_physique == 0) {
    //             $etat = 'Rupture';
    //         } elseif ($ecart < 0) {
    //             $etat = 'Perte';
    //         } elseif ($ecart > 0) {
    //             $etat = 'Surplus';
    //         } else {
    //             $etat = 'Conforme';
    //         }

    //         // Mise à jour du stock théorique et de l'état
    //         DB::table('inventaire_produit')
    //             ->where('id', $value->id)
    //             ->update([
    //                 'stock_theorique' => $sth,
    //                 'ecart' => $ecart,
    //                 'etat' => $etat,
    //             ]);
    //     }




    //     foreach ($data as $value) {
    //         // Récupérer l'inventaire actuel
    //         $inventaireActuel = Inventaire::with('produits')->find($value->inventaire_id);

    //         if (!$inventaireActuel) {
    //             continue; // Si l'inventaire actuel n'existe pas, on passe au suivant
    //         }

    //         // Récupérer l'inventaire précédent (le plus récent ayant un ID inférieur)
    //         $inventairePrecedent = Inventaire::where('id', '<', $value->inventaire_id)
    //             ->orderBy('id', 'desc')
    //             ->first();

    //         // Déterminer la plage de dates
    //         $dateDebut = $inventairePrecedent ? $inventairePrecedent->date_inventaire : null;
    //         $dateFin = $inventaireActuel->date_inventaire;

    //         // Récupérer les produits avec la quantité vendue et utilisée
    //         $data_produit = Produit::whereHas('categorie', function ($q) {
    //             $q->whereIn('famille', ['restaurant', 'bar']);
    //         })
    //             ->withSum(['ventes as quantite_vendue' => function ($query) use ($dateDebut, $dateFin) {
    //                 if ($dateDebut) {
    //                     $query->whereBetween('ventes.date_vente', [$dateDebut, $dateFin]);
    //                 } else {
    //                     $query->where('ventes.date_vente', '<', $dateFin);
    //                 }
    //             }], 'produit_vente.quantite_bouteille')

    //             ->withSum(['sorties as quantite_utilisee' => function ($query) use ($dateDebut, $dateFin) {
    //                 if ($dateDebut) {
    //                     $query->whereBetween('sorties.date_sortie', [$dateDebut, $dateFin]);
    //                 } else {
    //                     $query->where('sorties.date_sortie', '<', $dateFin);
    //                 }
    //             }], 'produit_sortie.quantite_utilise')

    //             ->with('categorie')
    //             ->where('id', $value->produit_id)
    //             ->get();

    //         // Vérifier s'il y a des produits avant de faire la mise à jour
    //         if ($data_produit->isNotEmpty()) {
    //             foreach ($data_produit as $produit) {
    //                 DB::table('inventaire_produit')
    //                     ->where('produit_id', $produit->id)
    //                     ->where('inventaire_id', $value->inventaire_id)
    //                     ->update([
    //                         'stock_vendu' => ($produit->quantite_vendue ?? 0) + ($produit->quantite_utilisee ?? 0), // Assure que les valeurs null sont remplacées par 0
    //                     ]);
    //             }
    //         }
    //     }
    // }



    public function miseAjourProduitInventaire()
    {
        $data = DB::table('inventaire_produit')->get();

        // 1. Mise à jour stock théorique et état
        foreach ($data as $value) {
            $sth = ($value->stock_dernier_inventaire + $value->stock_initial) - $value->stock_vendu;
            $ecart = $value->stock_physique - $sth;

            $etat = match (true) {
                $sth == 0 && $value->stock_physique == 0 => 'Rupture',
                $ecart < 0 => 'Perte',
                $ecart > 0 => 'Surplus',
                default => 'Conforme',
            };

            DB::table('inventaire_produit')
                ->where('id', $value->id)
                ->update([
                    'stock_theorique' => $sth,
                    'ecart' => $ecart,
                    'etat' => $etat,
                ]);
        }

        // 2. Précharger tous les inventaires nécessaires
        $inventaires = Inventaire::orderBy('id')->get()->keyBy('id');

        foreach ($data as $value) {
            $inventaireActuel = $inventaires[$value->inventaire_id] ?? null;
            if (!$inventaireActuel) continue;

            $inventairePrecedent = $inventaires
                ->where('id', '<', $value->inventaire_id)
                ->sortByDesc('id')
                ->first();

            $dateDebut = $inventairePrecedent?->date_inventaire;
            $dateFin = $inventaireActuel->date_inventaire;

            $produit = Produit::where('id', $value->produit_id)
                ->whereHas('categorie', function ($q) {
                    $q->whereIn('famille', ['restaurant', 'bar']);
                })
                ->withSum(['ventes as quantite_vendue' => function ($query) use ($dateDebut, $dateFin) {
                    $query->whereBetween('ventes.date_vente', [$dateDebut ?? '1900-01-01', $dateFin]);
                }], 'produit_vente.quantite_bouteille')
                ->withSum(['sorties as quantite_utilisee' => function ($query) use ($dateDebut, $dateFin) {
                    $query->whereBetween('sorties.date_sortie', [$dateDebut ?? '1900-01-01', $dateFin]);
                }], 'produit_sortie.quantite_utilise')
                ->first();

            if ($produit) {
                DB::table('inventaire_produit')
                    ->where('produit_id', $produit->id)
                    ->where('inventaire_id', $value->inventaire_id)
                    ->update([
                        'stock_vendu' => ($produit->quantite_vendue ?? 0) + ($produit->quantite_utilisee ?? 0),
                    ]);
            }
        }
    }


    public function historique(Request $request)
    {
        try {

            // Appel de la fonction miseAJourStockVente()
            $this->miseAJourStockVente();

            // Appel de la fonction miseAJourStockInventaire()
            $this->miseAjourProduitInventaire();

            $request->validate([
                'produit' => '',
                'dateDebut' => 'nullable',
                'dateFin' => 'nullable',
                'type' => '',
            ]);

            // recuperer les produit bar et restaurant
            $data_produit = Produit::active()->whereHas('categorie', function ($q) {
                $q->whereIn('famille', ['restaurant', 'bar']);
            })->get();

            // recuperer les request
            $produit = request('produit');
            $dateDebut = request('date_debut');
            $dateFin = request('date_fin');
            $type = request('type'); // vente, achat, inventaire , sortie

            $vente = [];
            $achat = [];
            $inventaire = [];
            $sortie = [];

            // vente
            if ($type == 'vente') {
                $vente = ProduitVente::with(['vente', 'variante', 'produit'])
                    ->where('produit_id', $produit)
                    ->orderBy('created_at', 'DESC');

                // Filtrer en fonction des dates
                if ($dateDebut && $dateFin) {
                    $vente->whereHas('vente', function ($query) use ($dateDebut, $dateFin) {
                        $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
                    });
                } elseif ($dateDebut) {
                    $vente->whereHas('vente', function ($query) use ($dateDebut) {
                        $query->whereDate('date_vente', $dateDebut);
                    });
                } elseif ($dateFin) {
                    $vente->whereHas('vente', function ($query) use ($dateFin) {
                        $query->whereDate('date_vente', $dateFin);
                    });
                }

                // Récupération des données
                $vente = $vente->get();
            }


            // achat
            if ($type == 'achat') {
                $achat = Achat::with('produit')
                    ->where('produit_id', $produit)
                    ->orderBy('created_at', 'DESC');

                // Filtrer en fonction des dates
                if ($dateDebut && $dateFin) {
                    $achat->whereBetween('date_achat', [$dateDebut, $dateFin]);
                } elseif ($dateDebut) {
                    $achat->whereDate('date_achat', $dateDebut);
                } elseif ($dateFin) {
                    $achat->whereDate('date_achat', $dateFin);
                }

                // Récupération des données
                $achat = $achat->get();
            }


            // inventaire
            if ($type == 'inventaire') {
                $inventaire = InventaireProduit::with(['inventaire', 'produit'])
                    ->where('produit_id', $produit)
                    ->orderBy('created_at', 'DESC');

                // Filtrer en fonction des dates
                if ($dateDebut && $dateFin) {
                    $inventaire->whereHas('inventaire', function ($query) use ($dateDebut, $dateFin) {
                        $query->whereBetween('date_inventaire', [$dateDebut, $dateFin]);
                    });
                } elseif ($dateDebut) {
                    $inventaire->whereHas('inventaire', function ($query) use ($dateDebut) {
                        $query->whereDate('date_inventaire', $dateDebut);
                    });
                } elseif ($dateFin) {
                    $inventaire->whereHas('inventaire', function ($query) use ($dateFin) {
                        $query->whereDate('date_inventaire', $dateFin);
                    });
                }

                // Récupération des données
                $inventaire = $inventaire->get();
            }



            // inventaire
            if ($type == 'sortie') {
                $sortie = ProduitSortie::with(['sortie', 'produit'])
                    ->where('produit_id', $produit)
                    ->orderBy('created_at', 'DESC');

                // Filtrer en fonction des dates
                if ($dateDebut && $dateFin) {
                    $sortie->whereHas('sortie', function ($query) use ($dateDebut, $dateFin) {
                        $query->whereBetween('date_sortie', [$dateDebut, $dateFin]);
                    });
                } elseif ($dateDebut) {
                    $sortie->whereHas('sortie', function ($query) use ($dateDebut) {
                        $query->whereDate('date_sortie', $dateDebut);
                    });
                } elseif ($dateFin) {
                    $sortie->whereHas('sortie', function ($query) use ($dateFin) {
                        $query->whereDate('date_sortie', $dateFin);
                    });
                }

                // Récupération des données
                $sortie = $sortie->get();
            }


            // dd($sortie->toArray());


            return view('backend.pages.rapport.historique', compact('data_produit', 'vente', 'achat', 'inventaire', 'sortie'));
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur est survenue lors du chargement de l\'historique : ' . $e->getMessage()
            ], 500);
        }
    }
}
