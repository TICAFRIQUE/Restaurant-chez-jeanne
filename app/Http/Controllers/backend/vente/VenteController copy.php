<?php

namespace App\Http\Controllers\backend\vente;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\User;
use App\Models\Vente;
use App\Models\Caisse;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Billetterie;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ClotureCaisse;
use App\Models\HistoriqueCaisse;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\RouteAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\backend\user\AdminController;

class VenteController extends Controller
{


    /**
     * Mettre à jour les quantités disponibles des variantes de produits de la famille "bar"
     *
     * @return void
     */
    public function calculeQteVarianteProduit()
    {
        // Récupérer les produits appartenant à la famille "bar"
        $data_produit_bar = Produit::withWhereHas('categorie', fn($q) => $q->where('famille', 'bar'))
            ->orderBy('created_at', 'DESC')
            ->get();

        foreach ($data_produit_bar as $produit) {
            // Mettre à zéro toutes les quantités disponibles des variantes du produit
            DB::table('produit_variante')
                ->where('produit_id', $produit->id)
                ->update(['quantite_disponible' => 0]);

            // Récupérer toutes les variantes associées au produit
            $variantes = DB::table('produit_variante')
                ->where('produit_id', $produit->id)
                ->get();

            foreach ($variantes as $variante) {
                // Calculer la nouvelle quantité disponible
                $nouvelle_quantite = $produit->stock * $variante->quantite;

                // Mettre à jour la quantité disponible
                DB::table('produit_variante')
                    ->where('produit_id', $produit->id)
                    ->where('variante_id', $variante->variante_id)
                    ->update(['quantite_disponible' => $nouvelle_quantite]);
            }
        }
    }


    public function index(Request $request)
    {
        try {

            $caisses = Caisse::all();

            // ##Filtres de recherche
            // $query = Vente::with('produits')
            //     ->whereStatut('confirmée')
            //     ->orderBy('date_vente', 'desc');

            // // Filtre par date
            // if ($request->filled('date_debut') && $request->filled('date_fin')) {
            //     $query->whereBetween('date_vente', [$request->date_debut, $request->date_fin]);
            // } elseif ($request->filled('date_debut')) {
            //     $query->whereDate('date_vente', '>=', $request->date_debut);
            // } elseif ($request->filled('date_fin')) {
            //     $query->whereDate('date_vente', '<=', $request->date_fin);
            // }

            // // Filtre par caisse
            // if ($request->filled('caisse')) {
            //     $query->where('caisse_id', $request->caisse);
            // }

            // if ($request->user()->hasRole('caisse')) {
            //     $query->where('caisse_id', auth()->user()->caisse_id)
            //         ->where('user_id', auth()->user()->id)
            //         ->where('statut_cloture', false);
            // }

            // $data_vente = $query->get();
            // // dd($data_vente->toArray());






            $query = Vente::with('produits')
                ->whereStatut('confirmée')
                ->orderBy('created_at', 'desc');


            // Vérifier si aucune période ou date spécifique n'a été fournie
            if (!$request->filled('periode') && !$request->filled('date_debut') && !$request->filled('date_fin')) {
                $query->whereMonth('date_vente', Carbon::now()->month)
                    ->whereYear('date_vente', Carbon::now()->year);
            }

            // sinon on applique le filtre des date et caisse
            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');
            $caisse = $request->input('caisse');
            $periode = $request->input('periode');


            // Formatage des dates
            $dateDebut = $request->filled('date_debut') ? Carbon::parse($dateDebut)->format('Y-m-d') : null;
            $dateFin = $request->filled('date_fin') ? Carbon::parse($dateFin)->format('Y-m-d') : null;

            // Application des filtres de date
            if ($dateDebut && $dateFin) {
                $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
            } elseif ($dateDebut) {
                $query->where('date_vente', '>=', $dateDebut);
            } elseif ($dateFin) {
                $query->where('date_vente', '<=', $dateFin);
            }

            // Application du filtre de caisse
            if ($request->filled('caisse')) {
                $query->where('caisse_id', $request->caisse);
            }


            // Application du filtre de periode
            // periode=> jour, semaine, mois, année
            if ($request->filled('periode')) {
                $periode = $request->periode; // Ajout de cette ligne pour récupérer la période

                if ($periode == 'jour') {
                    $query->whereDate('date_vente', Carbon::today());
                } elseif ($periode == 'semaine') {
                    $query->whereBetween('date_vente', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($periode == 'mois') {
                    $query->whereMonth('date_vente', Carbon::now()->month)
                        ->whereYear('date_vente', Carbon::now()->year); // Ajout pour éviter d'avoir des résultats de plusieurs années
                } elseif ($periode == 'annee') {
                    $query->whereYear('date_vente', Carbon::now()->year);
                }
            }

            //si l'utilisateur a le rôle 'caisse' ou 'supercaisse' on affiche les ventes de la caisse actuelle
            if ($request->user()->hasRole(['caisse', 'supercaisse'])) {
                $query->where('caisse_id', auth()->user()->caisse_id)
                    ->where('user_id', auth()->user()->id)
                    ->where('statut_cloture', false)
                    ->whereDate('date_vente', auth()->user()->caisse->session_date_vente); // ✅ Compare seulement la date
            }


            $data_vente = $query->get();


            ## fin du filtre de recherche


            //Recuperer la session de la date vente manuelle et verifier si la caisse actuelle a effectuer des vente clotureé  a sa date de vente
            $sessionDate = null;
            $venteCaisseCloture = null;
            if ($request->user()->hasRole(['caisse', 'supercaisse'])) {

                //Recuperer la session de la date vente manuelle
                $sessionDate = Caisse::find(Auth::user()->caisse_id);
                $sessionDate = $sessionDate->session_date_vente;


                // verifier si la caisse actuelle a effectuer des vente clotureé  a sa date de vente
                $venteCaisseCloture = Vente::where('caisse_id', auth()->user()->caisse_id)
                    ->where('user_id', auth()->user()->id)
                    ->where('statut_cloture', true)
                    ->whereDate('date_vente', auth()->user()->caisse->session_date_vente) // ✅ Compare seulement la date
                    ->count();
            }



            return view('backend.pages.vente.index', compact('data_vente', 'caisses', 'sessionDate', 'venteCaisseCloture'));
        } catch (\Exception $e) {
            Alert::error('Erreur', 'Une erreur est survenue lors du chargement des ventes : ' . $e->getMessage());
            return back();
        }
    }



    public function create()
    {
        try {

            // appeler la fonction calculeQteVarianteProduit
            $this->calculeQteVarianteProduit(); // calcule la quantité de chaque produit variantes


            $data_produit = Produit::active()
                ->whereHas('categorie', function ($query) {
                    $query->whereIn('famille', ['bar', 'menu']);
                })
                ->with(['categorie', 'variantes' => function ($query) {
                    $query->orderBy('quantite', 'asc'); // Trier par quantité croissante
                }])
                ->get();


            // dd(Session::get('session_date'));

            $data_client = User::whereHas('roles', function ($query) {
                $query->where('name', 'client');
            })->get();




            ####################### // script pour la gestion de menu ##################
            // recuperer le menu du jour en session
            $cartMenu = Session::get('cartMenu');


            // Récupérer le menu du jour avec les produits, compléments et garnitures
            $menu = Menu::where('date_menu', Carbon::today()->toDateString())
                ->with([
                    'plats' => function ($query) {
                        $query->with([
                            'categorieMenu',  // Relation vers la catégorie de produit
                            'complements' => function ($query) {
                                $query->wherePivot('menu_id', function ($subQuery) {
                                    $subQuery->select('id')
                                        ->from('menus')
                                        ->where('date_menu', Carbon::today()->toDateString());
                                });
                            },
                            'garnitures' => function ($query) {
                                $query->wherePivot('menu_id', function ($subQuery) {
                                    $subQuery->select('id')
                                        ->from('menus')
                                        ->where('date_menu', Carbon::today()->toDateString());
                                });
                            }
                        ]);
                    },
                ])->first();

            // Vérifier s'il y a un menu
            if (!$menu) {
                return view('backend.pages.vente.create', ['menu' => null, 'categories' => [], 'cartMenu' => $cartMenu, 'data_produit' => $data_produit, 'data_client' => $data_client],);
            }

            // Grouper les produits par nom de catégorie et trier par position de catégorie
            $categories = $menu->plats
                ->groupBy(function ($plat) {
                    return $plat->categorieMenu->nom; // Grouper par le nom de la catégorie
                })
                ->sortBy(function ($group, $key) {
                    // Trier les groupes par la position des catégories
                    $categorie = $group->first()->categorieMenu;
                    return $categorie ? $categorie->position : 0; // Si une catégorie n'a pas de position, utiliser 0
                });



            // dd($menu->toArray);


            return view('backend.pages.vente.create', compact('data_produit', 'data_client', 'categories', 'menu', 'cartMenu'));
        } catch (\Exception $e) {
            // Gestion des erreurs
            return back()->with('error', 'Une erreur est survenue lors du chargement du formulaire de création : ' . $e->getMessage());
        }
    }




    /**
     * Mettre à jour le stock des variantes d'un produit
     *
     * @param int $id L'ID du produit
     *
     * @return void
     */
    // public function miseAJourStock($id) // Mise a jour des stock variante
    // {
    //     $produit = Produit::find($id);

    //     if (!$produit) {
    //         return; // Arrête l'exécution si le produit n'existe pas
    //     }

    //     // Récupérer toutes les variantes associées au produit
    //     $variantes = DB::table('produit_variante')
    //         ->where('produit_id', $produit->id)
    //         ->get();

    //     foreach ($variantes as $variante) {
    //         // Récupérer la quantité disponible actuelle
    //         $quantite_disponible_actuelle = DB::table('produit_variante')
    //             ->where('produit_id', $produit->id)
    //             ->where('variante_id', $variante->variante_id)
    //             ->value('quantite_disponible');

    //         // Calculer la nouvelle quantité disponible
    //         $nouvelle_quantite = $quantite_disponible_actuelle + ($produit->stock * $variante->quantite);

    //         // Mettre à jour la quantité disponible
    //         DB::table('produit_variante')
    //             ->where('produit_id', $produit->id)
    //             ->where('variante_id', $variante->variante_id)
    //             ->update([
    //                 'quantite_disponible' => $nouvelle_quantite,
    //             ]);
    //     }
    // }

    public function miseAJourStock($id)
    {
        $produit = Produit::find($id);

        if (!$produit) {
            return; // Le produit n'existe pas
        }

        // Récupérer toutes les variantes du produit
        $variantes = DB::table('produit_variante')
            ->where('produit_id', $produit->id)
            ->get();

        foreach ($variantes as $variante) {
            // Calculer directement la nouvelle quantité disponible
            $nouvelle_quantite = $produit->stock * $variante->quantite;

            // Mettre à jour la variante
            DB::table('produit_variante')
                ->where('id', $variante->id)
                ->update(['quantite_disponible' => round($nouvelle_quantite, 2)]);
        }
    }




    /**
     * Mettre à jour le stock des ventes uniquement pour les produits de la famille "bar"
     *
     * La quantité de bouteilles vendues est mise à jour en fonction de la quantité de la variante dans la table produit_vente.
     * La mise à jour est effectuée pour les ventes qui ont été créées avec des produits de la famille "bar".
     * La quantité de bouteilles vendues est calculée en divisant la quantité vendue par la quantité de la variante.
     * La valeur est arrondie à 2 décimales.
     *
     * @return void
     */
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
        // Récupérer tous les enregistrements nécessaires en une seule requête
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

        foreach ($data as $item) {
            if ($item->quantite_variante == 0) {
                continue; // éviter la division par zéro
            }

            $quantite_bouteille = round($item->quantite / $item->quantite_variante, 2);

            DB::table('produit_vente')
                ->where('id', $item->id)
                ->update(['quantite_bouteille' => $quantite_bouteille]);
        }
    }




    public function store(Request $request)
    {
        try {
            //recuperation des informations depuis ajax
            $cart = $request->input('cart');
            // dd($cart);
            $cartMenu = $request->input('cartMenu');

            $montantAvantRemise = $request->input('montantAvantRemise');
            $montantApresRemise = $request->input('montantApresRemise');
            $montantRemise = $request->input('montantRemise');
            $typeRemise = $request->input('typeRemise');
            $valeurRemise = $request->input('valeurRemise');
            $modePaiement = $request->input('modePaiement');
            $montantRecu = $request->input('montantRecu');
            $montantRendu = $request->input('montantRendu');
            $numeroDeTable = $request->input('numeroDeTable');
            $nombreDeCouverts = $request->input('nombreDeCouverts');


            // Création de la vente
            // Obtenir les deux premières lettres du nom de la caissière
            $initialesCaissiere = substr(auth()->user()->first_name, 0, 2);
            $initialesCaisse = substr(auth()->user()->caisse->libelle, 0, 2);

            // Obtenir le numéro d'ordre de la vente pour aujourd'hui
            $nombreVentes = Vente::count();
            $numeroOrdre = $nombreVentes + 1;

            // Obtenir la date et l'heure actuelles
            $dateHeure = now()->format('dmYHi');

            // Générer le code de vente
            $codeVente = strtoupper($initialesCaissiere) . '-' . strtoupper($initialesCaisse) . $numeroOrdre . $dateHeure;

            //session de la date manuelle
            $sessionDate = Caisse::find(Auth::user()->caisse_id);
            $sessionDate = $sessionDate->session_date_vente;

            $vente = Vente::create([
                'code' => $codeVente,
                // 'client_id' => $request->client_id,
                'caisse_id' => auth()->user()->caisse_id, // la caisse qui fait la vente
                'user_id' => auth()->user()->id, // l'admin qui a fait la vente
                'date_vente' =>  $sessionDate,
                'montant_total' => $montantApresRemise,
                'montant_avant_remise' => $montantAvantRemise,
                'montant_remise' => $montantRemise,
                'type_remise' => $typeRemise,
                'valeur_remise' => $valeurRemise,
                'mode_paiement' => $modePaiement,
                'montant_recu' => $montantRecu,
                'montant_rendu' => $montantRendu,
                'numero_table' => $numeroDeTable,
                'nombre_couverts' => $nombreDeCouverts,
                'statut' => 'confirmée',
                'type_vente' => 'normale'
            ]);

            // gestion des produits dans la vente
            if (!empty($cart)) {
                foreach ($cart as $item) {
                    // Attachement des produits à la vente
                    $vente->produits()->attach($item['id'], [
                        'quantite' => $item['quantity'],
                        'prix_unitaire' => $item['price'],
                        'total' => $item['price'] * $item['quantity'],
                        'variante_id' => $item['selectedVariante'] ?? null,
                    ]);

                    // Récupérer le produit
                    $produit = Produit::find($item['id']);

                    // Vérifier si le produit appartient à la famille "bar"
                    if ($produit && optional($produit->categorie)->famille == 'bar') {
                        // Mise à jour dans la table produit_variante
                        DB::table('produit_variante')
                            ->where('produit_id', $item['id'])
                            ->where('variante_id', $item['selectedVariante'])
                            ->update([
                                'quantite_vendu' => DB::raw('quantite_vendu + ' . $item['quantity']),
                            ]);

                        // Récupérer la quantité de la variante
                        $quantite_variante = DB::table('produit_variante')
                            ->where('produit_id', $item['id'])
                            ->where('variante_id', $item['selectedVariante'])
                            ->value('quantite');

                        // Vérifier la division par zéro
                        if ($quantite_variante && $quantite_variante > 0) {
                            $bouteille_vendu = round($item['quantity'] / $quantite_variante, 2);
                        } else {
                            $bouteille_vendu = 0;
                        }

                        // Mettre à jour le stock du produit
                        $produit->stock -= $bouteille_vendu;
                        $produit->save();

                        // Mettre à jour la quantité disponible pour la variante spécifique
                        DB::table('produit_variante')
                            ->where('produit_id', $produit->id)
                            ->where('variante_id', $item['selectedVariante'])
                            ->update(['quantite_disponible' => 0]);

                        // Mettre à jour le stock global
                        $this->miseAJourStock($produit->id);
                    }
                }



                // Mettre à jour le stock des ventes uniquement pour les produits de la famille "bar"
                $this->miseAJourStockVente();
            }


            // inserer les produits dans la vente
            if (!empty($cartMenu)) {
                foreach ($cartMenu as $item) {
                    $plat = $item['plat'];
                    $vente->plats()->attach($plat['id'], [
                        'quantite' => $plat['quantity'],
                        'prix_unitaire' => $plat['price'],
                        'total' => $plat['price'] * $plat['quantity'],
                        'garniture' => json_encode($item['garnitures'] ?? []),
                        'complement' => json_encode($item['complements'] ?? []),
                    ]);
                }
            }
            $idVente = $vente->id;

            // retur response
            return response()->json([
                'message' => 'Vente enregistrée avec succès.',
                'status' => 'success',
                'idVente' => $idVente,

            ], 200);
        } catch (\Throwable $th) {
            return $th->getMessage();
            // Alert::error('Erreur', 'Une erreur est survenue lors de la création de la vente : ' . $th->getMessage());
            return back();
        }
    }

    public function show($id)
    {
        try {
            $vente = Vente::findOrFail($id);
            return view('backend.pages.vente.show', compact('vente'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('vente.index')->with('error', "La vente demandée n'existe plus.");
        } catch (\Exception $e) {
            return redirect()->route('vente.index')->with('error', "Une erreur s'est produite. Veuillez réessayer.");
        }
    }



    /**
     * Cloture la caisse courante, enregistre l'historique et se déconnecte
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clotureCaisse(Request $request)
    {
        try {
            $user = Auth::user();
            $caisse = $user->caisse;

            // Calculer le montant total des ventes pour cette caisse
            $totalVentes = Vente::where('caisse_id', $caisse->id)->sum('montant_total');

            // Clôturer la caisse
            ClotureCaisse::create([
                'caisse_id' => $caisse->id,
                'user_id' => $user->id,
                'montant_total' => $totalVentes,
                'date_cloture' => now()
            ]);



            //mettre statut_cloture a true dans les ventes de la caisse
            Vente::where('caisse_id', $caisse->id)->update([
                'statut_cloture' => true,
            ]);

            // //desactive la caisse
            // $caisse->statut = 'desactive';
            // $caisse->save();

            // //deconnecter l'utilisateur et enregistrer l'historique caisse
            // // Si l'utilisateur a une caisse active, la désactiver
            // if ($user->caisse_id) {

            //     // niveau caisse
            //     $caisse = Caisse::find($user->caisse_id);
            //     $caisse->statut = 'desactive';
            //     $caisse->session_date_vente = null;
            //     $caisse->save();
            //     // mettre caisse_id a null
            //     User::whereId($user->id)->update([
            //         'caisse_id' => null,
            //     ]);

            //     //mise a jour dans historiquecaisse pour fermeture de caisse
            //     HistoriqueCaisse::where('user_id', $user->id)
            //         ->where('caisse_id', $user->caisse_id)
            //         ->whereNull('date_fermeture')
            //         ->update([
            //             'date_fermeture' => now(),
            //         ]);
            // }


            // Auth::logout();
            Alert::success('Succès', 'Caisse cloturée avec succès');
            return redirect()->route('vente.rapport-caisse');
        } catch (\Exception $e) {
            Alert::error('Erreur', 'Une erreur est survenue lors de la cloture de la caisse : ' . $e->getMessage());
            return back();
        }
    }


    /***Fonction pour fermer la caisse */
    public function fermerCaisse(Request $request)
    {
        try {
            $user = Auth::user();
            $caisse = $user->caisse;
            //desactive la caisse
            // $caisse->statut = 'desactive';
            // $caisse->save();

            //deconnecter l'utilisateur et enregistrer l'historique caisse
            // Si l'utilisateur a une caisse active, la désactiver
            if ($user->caisse_id) {

                // niveau caisse
                $caisse = Caisse::find($user->caisse_id);
                $caisse->statut = 'desactive';
                $caisse->session_date_vente = null;
                $caisse->save();

                // mettre caisse_id a null du user connecté
                User::whereId($user->id)->update([
                    'caisse_id' => null,
                ]);

                //mise a jour dans historiquecaisse pour fermeture de caisse
                HistoriqueCaisse::where('user_id', $user->id)
                    ->where('caisse_id', $user->caisse_id)
                    ->whereNull('date_fermeture')
                    ->update([
                        'date_fermeture' => now(),
                    ]);


                Auth::logout();
                Alert::success('Succès', 'Caisse Fermée avec succès');
                return Redirect()->route('admin.login');
            }
        } catch (\Exception $e) {
            return redirect()->route('vente.index')->with('error', "Une erreur s'est produite. Veuillez réessayer.");
        }
    }


    public function billeterieCaisse(Request $request)
    {
        try {

            $modes = [
                0 => 'Espèce',
                1 => 'Mobile money',
            ];

            $type_mobile_money = [
                0 => 'Wave',
                1 => 'Moov money',
                2 => 'Orange Money',
                3 => 'MTN money',
                4 => 'MasterCard',
                5 => 'Visa',
            ];

            $type_monnaies = [
                0 => 'Billets',
                1 => 'Pièces',
            ];

            $billets = [
                0 => 500,
                1 => 1000,
                2 => 2000,
                3 => 5000,
                4 => 10000,
            ];


            $pieces = [
                0 => 5,
                1 => 10,
                2 => 20,
                3 => 50,
                4 => 100,
                5 => 200,
                6 => 500,
            ];


            if ($request->user()->hasRole(['caisse', 'supercaisse'])) {
                $totalVente = Vente::where('caisse_id', auth()->user()->caisse_id)
                    ->where('user_id', auth()->user()->id)
                    ->where('statut_cloture', false)->sum('montant_total');
            }

            // dd($type_monnaies , $billets, $pieces, $totalVente);

            return view('backend.pages.vente.billeterie.create', compact('modes', 'type_monnaies', 'billets', 'pieces', 'totalVente', 'type_mobile_money'));
        } catch (\Throwable $th) {

            return $th->getMessage();
        }
    }

    public function storeBilleterie(Request $request)
    {
        try {
            $user = Auth::user();
            $caisse = $user->caisse;

            // enregistrer la billetterie
            foreach ($request->input('variantes', []) as $variante) {
                Billetterie::create([
                    'mode' => $variante['mode'],
                    'type_monnaie' => $variante['type_monnaie'] ?? null,
                    'quantite' => $variante['quantite'] ?? null,
                    'valeur' => $variante['valeur'] ?? null,
                    'type_mobile_money' => $variante['type_mobile_money'] ?? null,
                    'montant' => $variante['montant'] ?? null,
                    'total' => $variante['total'],
                    'caisse_id' => $caisse->id,
                    'user_id' => $user->id,
                    'date_save' => Auth::user()->caisse->session_date_vente,
                ]);
            }

            // si il il n'y a pas d'erreur lors de l'enregistrement de billetterie appel a fonction clotureCaisse
            return $this->clotureCaisse($request);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    //Rapport de vente de la caisse
    public function rapportVente(Request $request)
    {
        try {
            // $dateDebut = $request->input('date_debut');
            // $dateFin = $request->input('date_fin');
            // $caisseId = $request->input('caisse_id');
            $categorieFamille = $request->input('categorie_famille');
            // $periode = $request->input('periode');
            // $categorieMenu = 'plat_menu';


            //Pour les vente bar et restaurant
            $query = Vente::with(['produits.categorie', 'plats.categorieMenu', 'caisse']);

            // pour la vente des plats menu
            $queryMenu = Vente::with(['plats.categorieMenu', 'caisse']);


            // if ($dateDebut && $dateFin) {
            //     $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
            //     $queryMenu->whereBetween('date_vente', [$dateDebut, $dateFin]);
            // } elseif ($dateDebut) {
            //     $query->where('date_vente', '>=', $dateDebut);
            //     $queryMenu->where('date_vente', '>=', $dateDebut);
            // } elseif ($dateFin) {
            //     $query->where('date_vente', '<=', $dateFin);
            //     $queryMenu->where('date_vente', '<=', $dateFin);
            // }

            // if ($categorieFamille== 'plat_menu') {
            //     $queryMenu = Vente::with(['plats.categorieMenu', 'caisse']);
            // }elseif ($categorieFamille== 'bar' || $categorieFamille== 'menu') {
            //     $query = Vente::with(['plats.categorieMenu', 'caisse']);

            // }

            // if ($caisseId) {
            //     $query->where('caisse_id', $caisseId);
            //     $queryMenu->where('caisse_id', $caisseId);
            // }


            // Application du filtre de periode
            // periode=> jour, semaine, mois, année
            // if ($request->filled('periode')) {
            //     $dates = match ($periode) {
            //         'jour' => [Carbon::today(), Carbon::today()],
            //         'semaine' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            //         'mois' => [Carbon::now()->month, Carbon::now()->year], // Stocke mois et année pour `whereMonth`
            //         'annee' => Carbon::now()->year, // Stocke année pour `whereYear`
            //         default => null,
            //     };

            //     if ($dates) {
            //         if ($periode == 'jour') {
            //             $query->whereDate('date_vente', $dates[0]);
            //             $queryMenu->whereDate('date_vente', $dates[1]);
            //         } elseif ($periode == 'semaine') {
            //             $query->whereBetween('date_vente', $dates);
            //             $queryMenu->whereBetween('date_vente', $dates);
            //         } elseif ($periode == 'mois') {
            //             $query->whereMonth('date_vente', $dates[0])->whereYear('date_vente', $dates[1]);
            //             $queryMenu->whereMonth('date_vente', $dates[0])->whereYear('date_vente', $dates[1]);
            //         } elseif ($periode == 'annee') {
            //             $query->whereYear('date_vente', $dates);
            //             $queryMenu->whereYear('date_vente', $dates);
            //         }
            //     }
            // }


            // pour les vente bar et restaurant

            $ventes = $query
                ->where('caisse_id', auth()->user()->caisse_id)
                ->where('user_id', auth()->user()->id)
                ->where('statut_cloture', true)
                ->whereDate('date_vente', auth()->user()->caisse->session_date_vente) // ✅ Compare seulement la date
                ->get();


            // pour la vente des plats menu
            $ventesMenu = $queryMenu
                ->where('caisse_id', auth()->user()->caisse_id)
                ->where('user_id', auth()->user()->id)
                ->where('statut_cloture', true)
                ->whereDate('date_vente', auth()->user()->caisse->session_date_vente)
                ->get();


            // pour les produits restaurant et bar
            $produitsVendus = $ventes->flatMap(function ($vente) {
                return $vente->produits;
            })->groupBy('id')->map(function ($groupe) use ($categorieFamille) {
                $produit = $groupe->first();
                if ($categorieFamille && $produit->categorie->famille !== $categorieFamille) {
                    return null;
                }
                return [
                    'details' => $groupe, // recuperer les details groupés par produit
                    'id' => $produit->id,
                    'code' => $produit->code,
                    'stock' => $produit->stock,
                    'designation' => $produit->nom,
                    'categorie' => $produit->categorie->name,
                    'famille' => $produit->categorie->famille,
                    'quantite_vendue' => $groupe->sum('pivot.quantite'),
                    'variante' => $groupe->first()->pivot->variante_id,
                    'prix_vente' => $groupe->first()->pivot->prix_unitaire,
                    'montant_total' => $groupe->sum(function ($item) {
                        return $item->pivot->quantite * $item->pivot->prix_unitaire;
                    }),
                ];
            })->filter()->values();

            // dd($produitsVendus->toArray());


            //pour les plats menu
            $platsVendus = $ventesMenu->flatMap(function ($vente) {
                return $vente->plats;
            })->groupBy('id')->map(function ($groupe) {
                $plat = $groupe->first();

                return [
                    'id' => $plat->id,
                    'code' => $plat->code,
                    'stock' => 100,
                    'designation' => $plat->nom,
                    'categorie' => $plat->categorieMenu->nom,
                    'famille' => 'Menu',
                    'quantite_vendue' => $groupe->sum('pivot.quantite'),
                    'prix_vente' => $groupe->first()->pivot->prix_unitaire,
                    'montant_total' => $groupe->sum(function ($item) {
                        return $item->pivot->quantite * $item->pivot->prix_unitaire;
                    }),
                ];
            })->filter()->values();


            $produitsVendus =  $produitsVendus->concat($platsVendus);

            // dd($produitsVendus->toArray());


            /**Recuperer les billetteries */
            $billetterie = Billetterie::where('caisse_id', auth()->user()->caisse_id)
                ->where('user_id', auth()->user()->id)
                ->whereDate('date_save', auth()->user()->caisse->session_date_vente)
                ->get();


            // Tableau pour stocker les résultats
            $resultats = [
                'mode_0' => 0,
                'mode_1' => []
            ];

            // Regrouper et additionner les totaux
            foreach ($billetterie as $item) {
                if ($item->mode == 0) {
                    $resultats['mode_0'] += $item->total;
                } elseif ($item->mode == 1) {
                    $type = $item->type_mobile_money ?? 0;
                    if (!isset($resultats['mode_1'][$type])) {
                        $resultats['mode_1'][$type] = 0;
                    }
                    $resultats['mode_1'][$type] += $item->total;
                }
            }

            // Libellés
            $modes = [
                0 => 'Espèce',
                1 => 'Mobile money',
            ];

            $type_mobile_money = [
                0 => 'Wave',
                1 => 'Moov money',
                2 => 'Orange Money',
                3 => 'MTN money',
                4 => 'MasterCard',
                5 => 'Visa',
            ];

            /** End Afficher les billetteries */
            // dd($billetterie->toArray());

            $caisses = Caisse::all();
            $famille = Categorie::whereNull('parent_id')->whereIn('type', ['bar', 'menu'])->orderBy('name', 'DESC')->get();


            return view('backend.pages.vente.rapportVente', compact('platsVendus', 'produitsVendus', 'caisses', 'categorieFamille', 'famille', 'modes', 'type_mobile_money', 'resultats'));
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur s\'est produite : ' . $e->getMessage());
        }
    }







    ############################################VENTE AU NIVEAU MENU##############################################
    public function createVenteMenu()
    {
        try {
            // recuperer le menu du jour en session
            $cartMenu = Session::get('cartMenu');


            // Récupérer le menu du jour avec les produits, compléments et garnitures
            $menu = Menu::where('date_menu', Carbon::today()->toDateString())
                ->with([
                    'plats' => function ($query) {
                        $query->with([
                            'categorieMenu',  // Relation vers la catégorie de produit
                            'complements' => function ($query) {
                                $query->wherePivot('menu_id', function ($subQuery) {
                                    $subQuery->select('id')
                                        ->from('menus')
                                        ->where('date_menu', Carbon::today()->toDateString());
                                });
                            },
                            'garnitures' => function ($query) {
                                $query->wherePivot('menu_id', function ($subQuery) {
                                    $subQuery->select('id')
                                        ->from('menus')
                                        ->where('date_menu', Carbon::today()->toDateString());
                                });
                            }
                        ]);
                    },
                ])->first();

            // Vérifier s'il y a un menu
            if (!$menu) {
                return view('backend.pages.vente.menu.create', ['menu' => null, 'categories' => []]);
            }

            // Grouper les produits par nom de catégorie et trier par position de catégorie
            $categories = $menu->plats
                ->groupBy(function ($plat) {
                    return $plat->categorieMenu->nom; // Grouper par le nom de la catégorie
                })
                ->sortBy(function ($group, $key) {
                    // Trier les groupes par la position des catégories
                    $categorie = $group->first()->categorieMenu;
                    return $categorie ? $categorie->position : 0; // Si une catégorie n'a pas de position, utiliser 0
                });



            // dd($menu->toArray);

            return view('backend.pages.vente.menu.create', compact('categories', 'menu', 'cartMenu'));
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    /**
     * Stocke une vente au niveau menu
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeVenteMenu(Request $request)
    {
        try {
            //recuperation des informations depuis ajax
            $cart = $request->input('cart');
            // $montantAvantRemise = $request->input('montantAvantRemise');
            // $montantApresRemise = $request->input('montantApresRemise');
            // $montantRemise = $request->input('montantRemise');
            // $typeRemise = $request->input('typeRemise');
            // $valeurRemise = $request->input('valeurRemise');
            $modePaiement = $request->input('modePaiement');
            $montantRecu = $request->input('montantRecu');
            $montantRendu = $request->input('montantRendu');
            $montantAPayer = $request->input('montantAPayer');

            // Création de la vente
            // Obtenir les deux premières lettres du nom de la caissière
            $initialesCaissiere = substr(auth()->user()->first_name, 0, 2);
            $initialesCaisse = substr(auth()->user()->caisse->libelle, 0, 2);

            // Obtenir le numéro d'ordre de la vente pour aujourd'hui
            $nombreVentes = Vente::count();
            $numeroOrdre = $nombreVentes + 1;

            // Obtenir la date et l'heure actuelles
            $dateHeure = now()->format('dmYHi');

            // Générer le code de vente
            $codeVente = strtoupper($initialesCaissiere) . '-' . strtoupper($initialesCaisse) . $numeroOrdre . $dateHeure;

            //session de la date manuelle
            $sessionDate = Session::get('session_date', now()->toDateString());


            $vente = Vente::create([
                'code' => $codeVente,
                // 'client_id' => $request->client_id,
                'caisse_id' => auth()->user()->caisse_id, // la caisse qui fait la vente
                'user_id' => auth()->user()->id, // l'admin qui a fait la vente
                'montant_total' => $montantAPayer,
                'date_vente' =>  Carbon::parse($sessionDate),
                'mode_paiement' => $modePaiement,
                'montant_recu' => $montantRecu,
                'montant_rendu' => $montantRendu,
                'statut' => 'confirmée',
                'type_vente' => 'Menu du jour'
            ]);

            // inserer les produits dans la vente
            foreach ($cart as $item) {
                $plat = $item['plat'];
                $vente->plats()->attach($plat['id'], [
                    'quantite' => $plat['quantity'],
                    'prix_unitaire' => $plat['price'],
                    'total' => $plat['price'] * $plat['quantity'],
                    'garniture' => json_encode($item['garnitures'] ?? []),
                    'complement' => json_encode($item['complements'] ?? []),
                ]);
            }

            $idVente = $vente->id;

            return response()->json([
                'message' => 'Vente enregistrée avec succès.',
                'status' => 'success',
                'idVente' => $idVente,
            ], 200);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }






    // suprimer une vente
    // public function delete($id)
    // {
    //     // Récupérer tous les produits liés à la  ventes
    //     $vente = Vente::find($id);

    //     if ($vente->isEmpty()) {
    //         return response()->json(['message' => 'Aucune vente trouvé'], 404);
    //     }

    //     foreach ($vente->produits as $produit) {
    //         // Récupérer le produit lié à la vente
    //         $produitVente = Produit::find($produit->id);
    //         if (!$produitVente) {
    //             continue; // On saute si le produit n'existe pas
    //         } else {
    //             // Mettre à jour le stock du produit
    //             $produitVente->stock += $produit->pivot->quantite_bouteille;
    //             $produitVente->save();
    //         }

    //         // Mettre les variantes à 0 (si nécessaire)
    //         DB::table('produit_variante')
    //             ->where('produit_id', $produit->id)
    //             ->update(['quantite_disponible' => 0]);

    //         // Mise à jour du stock
    //         $this->miseAJourStock($produit->id);
    //     }

    //     // Supprimer la vente
    //     Vente::find($id)->forceDelete();

    //     return response()->json(['status' => 200]);
    // }


    public function delete($id)
    {
        // Récupérer la vente avec ses produits
        $vente = Vente::with('produits')->find($id);

        if (!$vente) {
            return response()->json(['message' => 'Vente non trouvée'], 404);
        }

        foreach ($vente->produits as $produit) {
            // Vérifier si le produit existe réellement
            $produitVente = Produit::find($produit->id);
            if (!$produitVente) {
                continue;
            }

            // Réajouter la quantité vendue au stock du produit
            $produitVente->stock += $produit->pivot->quantite_bouteille;
            $produitVente->save();

            // Remettre la quantité des variantes à 0 (optionnel selon ta logique métier)
            DB::table('produit_variante')
                ->where('produit_id', $produit->id)
                ->update(['quantite_disponible' => 0]);

            // Recalcul du stock (si nécessaire)
            $this->miseAJourStock($produit->id);
        }

        // Supprimer la vente
        $vente->forceDelete();

        return response()->json(['status' => 200]);
    }
}
