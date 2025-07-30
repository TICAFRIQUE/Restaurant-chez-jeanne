<?php

namespace App\Http\Controllers\backend\stock;

use Carbon\Carbon;
use App\Models\Unite;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Inventaire;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

Carbon::setLocale('fr'); // mettre en francais la date

class InventaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $data_inventaire = Inventaire::with('produits')->orderBy('created_at', 'desc')->get();

            return view('backend.pages.stock.inventaire.index', compact('data_inventaire'));
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        try {

            // verifier si un inventaire du mois precedent existe
            $moisPrecedent = Carbon::now()->subMonth();

            $inventaire_existe = Inventaire::where('mois_concerne', $moisPrecedent->month)
                ->where('annee_concerne', $moisPrecedent->year)
                ->exists();

            // dd($inventaire_existe);

            if ($inventaire_existe) {
                return back()->with('error', 'Un inventaire du mois précédent existe déjà');
            }

            // Récupérer la date du dernier inventaire
            $date_dernier_inventaire = Inventaire::select('date_inventaire')
                ->orderBy('date_inventaire', 'desc')
                ->first();


            $jour_mois_actuel = Carbon::now()->startOfMonth(); // 1er jour du mois actuel (1er avril)
            $jour_mois_precedent = $jour_mois_actuel->copy()->subMonth();    // 1er jour du mois précédent (1er mars)

            $debut_jour = $jour_mois_precedent;
            $dernier_jour = $jour_mois_actuel;




            // $data_produit = Produit::whereHas('categorie', function ($q) {
            //     $q->whereIn('famille', ['restaurant', 'bar']);
            // })
            //     ->withSum(['ventes as quantite_vendue' => function ($query) {
            //         // Filtrer les ventes entre la date du dernier inventaire et la date du jour
            //         $query->whereMonth('ventes.date_vente', Carbon::now()->month - 1);
            //     }], 'produit_vente.quantite_bouteille') // Somme de la quantité vendue dans le pivot produit_vente


            //     ->withSum(['sorties as quantite_utilisee' => function ($query) {
            //         // Filtrer les sorties entre la date du dernier inventaire et la date du jour
            //         $query->whereMonth('sorties.date_sortie', Carbon::now()->month - 1);
            //     }], 'produit_sortie.quantite_utilise') // Somme de la quantité utilisée dans le pivot produit_sortie

            //     ->with('categorie')
            //     ->get();




            $anneePrecedente = $moisPrecedent->year;
            $mois = $moisPrecedent->month;

            $data_produit = Produit::whereHas('categorie', function ($q) {
                $q->whereIn('famille', ['restaurant', 'bar']);
            })
                ->withSum(['ventes as quantite_vendue' => function ($query) use ($anneePrecedente, $mois) {
                    $query->whereYear('ventes.date_vente', $anneePrecedente)
                        ->whereMonth('ventes.date_vente', $mois);
                }], 'produit_vente.quantite_bouteille')

                ->withSum(['sorties as quantite_utilisee' => function ($query) use ($anneePrecedente, $mois) {
                    $query->whereYear('sorties.date_sortie', $anneePrecedente)
                        ->whereMonth('sorties.date_sortie', $mois);
                }], 'produit_sortie.quantite_utilise')
                ->with(['categorie', 'variantes', 'ventes'])
                ->alphabetique()
                ->get();


            // recuperer les familles de categories bar et restaurant
            $categorie_famille = Categorie::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')
                ->whereIn('type', ['bar', 'restaurant'])
                ->OrderBy('position', 'ASC')->get();




            // dd($data_produit->toArray());
            return view('backend.pages.stock.inventaire.create', compact('data_produit', 'categorie_famille'));
        } catch (\Throwable $e) {
            return  $e->getMessage();
        }
    }

    /**
     * Met à jour les informations de stock pour chaque produit dans l'inventaire.
     *
     * Cette méthode parcourt les enregistrements de la table `inventaire_produit` pour calculer
     * le stock théorique, l'écart et l'état du stock pour chaque produit. Elle met à jour ces
     * informations dans la base de données. Ensuite, elle récupère les données d'inventaires 
     * actuels et précédents pour déterminer les quantités vendues et utilisées de chaque produit,
     * et met à jour ces informations dans la base de données.
     *
     * @param Request $request La requête HTTP contenant les données nécessaires à la mise à jour.
     * @return void
     */

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



    /**
     * Mettre à jour le stock des variantes d'un produit
     *
     * @param int $id L'ID du produit
     *
     * @return void
     */
    //
    public function miseAJourStock($id)
    {
        $produit = Produit::find($id);

        if (!$produit) {
            return; // Arrête l'exécution si le produit n'existe pas
        }

        // Récupérer toutes les variantes associées au produit
        $variantes = DB::table('produit_variante')
            ->where('produit_id', $produit->id)
            ->get();

        foreach ($variantes as $variante) {
            // Récupérer la quantité disponible actuelle
            $quantite_disponible_actuelle = DB::table('produit_variante')
                ->where('produit_id', $produit->id)
                ->where('variante_id', $variante->variante_id)
                ->value('quantite_disponible');

            // Calculer la nouvelle quantité disponible
            $nouvelle_quantite = $quantite_disponible_actuelle + ($produit->stock * $variante->quantite);

            // Mettre à jour la quantité disponible
            DB::table('produit_variante')
                ->where('produit_id', $produit->id)
                ->where('variante_id', $variante->variante_id)
                ->update([
                    'quantite_disponible' => $nouvelle_quantite,
                ]);
        }
    }


    /**
     * Mettre à jour le stock des produits qui ne sont pas dans l'inventaire du mois à 0
     *
     * @return void
     */
    function productsNotInInventaire()
    {
        // ##START FONCTION POUR METTRE A JOUR LE STOCK DES PRODUITS QUI NE SONT PAS DANS L'INVENTAIRE DU MOIS


        // recuperer l'inventaire du mois 
        $inventaire_mois = Inventaire::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->first();

        // recuperer les produits de l'inventaire du mois
        $produits_inventaire = Produit::whereHas('inventaires', function ($query) use ($inventaire_mois) {
            $query->where('inventaires.id', $inventaire_mois->id);
        })->get();


        //recuperer les produits où la categorie famille est restaurant et  bar qui ne sont pas dans l'inventaire du mois
        $produits_not_inventaire = Produit::whereDoesntHave('inventaires', function ($query) use ($inventaire_mois) {
            $query->where('inventaires.id', $inventaire_mois->id);
        })->whereHas('categorie', function ($query) {
            $query->whereIn('famille', ['restaurant', 'bar']);
        })->get();

        // mettre le stock des produits qui ne sont pas dans l'inventaire du mois à 0
        foreach ($produits_not_inventaire as $produit) {
            $produit->update([
                'stock' => 0,
                'stock_dernier_inventaire' => 0,
                'stock_initial' => 0,
            ]);
        }

        // dd($produits_not_inventaire->toArray());

        ##END


    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // dd($request->all());
            $request->validate([
                'produit_id.*' => 'required',
                'stock_initial.*' => 'required|numeric',
                'stock_theorique.*' => 'required|numeric',
                'stock_physique.*' => 'required|numeric',
                'ecart.*' => 'required|numeric',
                'etat.*' => 'required',
                'observation.*' => 'nullable',
            ]);

            // enregistrer l'inventaire
            $moisPrecedent = Carbon::now()->subMonth();

            $inventaire = Inventaire::firstOrCreate(
                [
                    'mois_concerne' => $moisPrecedent->month,
                    'annee_concerne' => $moisPrecedent->year,
                ],
                [
                    'code' => 'IN-' . strtoupper(Str::random(8)),
                    'date_inventaire' => Carbon::now(), // date réelle de création
                    'user_id' => Auth::id(),
                ]
            );
            // enregistrer les produits de la sortie
            foreach ($request->produit_id as $key => $produit_id) {
                // Trouver l'unité correspondante pour ce produit
                $produit = Produit::find($produit_id);

                // enregistrer les variantes en json
                $varianteData = [];

                if (!empty($request->variantes[$produit_id])) {
                    foreach ($request->variantes[$produit_id] as $libelle => $quantite) {
                        // if ((int) $quantite > 0) {
                        $varianteData[$libelle] = (int) $quantite;
                        // }
                    }
                }

                // Attacher le produit à la sortie avec les informations associées
                $inventaire->produits()->attach($produit_id, [
                    'stock_dernier_inventaire' => $produit->stock_dernier_inventaire, // direct dans la table produit
                    'stock_initial' => $request->stock_initial[$key], // nouveau stock ajouté apres l'inventaire precedent
                    'stock_theorique' => $request->stock_theorique[$key],
                    'stock_physique' => $request->stock_physique[$key],
                    'stock_physique_json' => json_encode($varianteData), // stock hysique en json
                    'stock_vendu' => $request->stock_vendu[$key],
                    'ecart' => $request->ecart[$key],
                    'etat' => $request->etat[$key],
                    'observation' => $request->observation[$key],
                ]);

                // remplacer le stock  par le stock physique
                $produit->stock = $request->stock_physique[$key];

                // stock du dernier inventaire
                $produit->stock_dernier_inventaire = $request->stock_physique[$key];

                // mettre le stock initial a 0
                $produit->stock_initial = 0;

                $produit->save();

                // Vérifier si la catégorie famille est 'bar'
                if ($produit->categorie->famille === 'bar') {
                    // Mettre à jour la quantité disponible des variantes du produit à 0
                    DB::table('produit_variante')
                        ->where('produit_id', $produit_id)
                        ->update(['quantite_disponible' => 0]);

                    // Appeler la méthode miseAJourStock
                    $this->miseAJourStock($produit_id);
                }
            }

            //Appeler la methode miseAJourStockVariante
            // $this->miseAjourProduitInventaire();
            $this->productsNotInInventaire();  // mettre à jour le stock des produits qui ne sont pas dans l'inventaire du mois à 0



            // retur response
            return response()->json([
                'message' => 'Inventaire enregistré avec succès.',
                'statut' => 'success',
            ], 200);
            # code...
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        try {
            // Filtrer les produits en fonction de leur état de stock
            $etatFilter = request('filter_etat');

            // Récupérer l'inventaire précédent
            // $inventairePrecedent = Inventaire::with(['produits' , 'produits.produit_variante'])
            //     ->where('id', '<', $id)
            //     ->orderBy('id', 'desc')
            //     ->first();

            // recuperer la date de l'inventaire 
            $dateInventaire = Inventaire::where('id', $id)->first()->date_inventaire;
            // convertir la date en format francais
            $date = Carbon::parse($dateInventaire)->isoFormat('D MMMM YYYY');

            // recuperer le mois seulement
            $mois = Carbon::parse($dateInventaire)->isoFormat('MMMM');


            // recuperer le mois  -1 qui represente le mois de l'inventaire
            $moisInventaire = Carbon::parse($dateInventaire)->subMonth()->isoFormat('MMMM');


            // Récupérer l'inventaire actuel avec les produits
            $query = Inventaire::with('produits', 'produits.variantes')->where('id', $id);

            // Si un filtre d'état est défini, appliquer le filtrage sur les produits
            if ($etatFilter) {
                $query->with(['produits' => function ($query) use ($etatFilter) {
                    $query->wherePivot('etat', $etatFilter);
                }]);
            }

            $inventaire = $query->first();

            // Vérifier si l'inventaire existe
            if (!$inventaire) {
                return redirect()->route('inventaire.index')->with('error', "L'inventaire demandé n'existe pas.");
            }

            // dd($inventaire->toArray());

            return view('backend.pages.stock.inventaire.show', compact('inventaire',  'moisInventaire'));
        } catch (\Exception $e) {
            return redirect()->route('inventaire.index')->with('error', "Une erreur s'est produite. Veuillez réessayer.");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventaire $inventaire)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventaire $inventaire)
    {
        //

    }

    /**
     * Remove the specified resource from storage.
     */

    public function delete($id)
    {

        try {
            Inventaire::find($id)->forceDelete();
            return response()->json([
                'status' => 200,
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    // Fiche inventaire
    // public function ficheInventaire(Request $request)
    // { // alphabetique=> scope pour trier les produits par ordre alphabetique
    //     try {
    //         // recuperer tous les produit en les groupant par categorie bar et restaurant
    //         $type = $request->query('type'); // Récupère le paramètre 'type' de l'URL

    //         if ($type == 'bar') {
    //             $produits = Produit::alphabetique()->whereHas('categorie', function ($q) {
    //                 $q->where('famille', 'bar');
    //             })->get();
    //         } elseif ($type == 'restaurant') {
    //             $produits = Produit::alphabetique()->whereHas('categorie', function ($q) {
    //                 $q->where('famille', 'restaurant');
    //             })->get();
    //         } else {
    //             // Récupère les produits des familles 'bar' et 'restaurant' et les groupe par famille
    //             $produits = Produit::alphabetique()->with('categorie')
    //                 ->whereHas('categorie', function ($q) {
    //                     $q->whereIn('famille', ['bar', 'restaurant']);
    //                 })
    //                 ->get();
    //             // ->groupBy('categorie.famille'); // Groupe les produits par famille
    //         }

    //         dd($produits->toArray());

    //         return view('backend.pages.stock.inventaire.fiche', compact('produits'));
    //     } catch (\Throwable $e) {
    //         return $e->getMessage();
    //     }
    // }


    public function ficheInventaire(Request $request)
    {
        try {
            $type = $request->query('type'); // 'bar', 'restaurant' ou null

            $query = Produit::alphabetique()->active()->with(['categorie', 'variantes']);

            if (in_array($type, ['bar', 'restaurant'])) {
                $query->whereHas('categorie', fn($q) => $q->where('famille', $type));
            } else {
                $query->whereHas('categorie', fn($q) => $q->whereIn('famille', ['bar', 'restaurant']));
            }

            $produits = $query->get();

            // dd($produits->toArray()); // Pour test si nécessaire

            return view('backend.pages.stock.inventaire.fiche', compact('produits'));
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
