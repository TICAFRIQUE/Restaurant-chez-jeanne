<?php

namespace App\Http\Controllers\backend\vente;

use Exception;
use Carbon\Carbon;
use App\Models\Menu;
use App\Models\User;
use App\Models\Vente;
use App\Models\Caisse;
use App\Models\Offert;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Reglement;
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
use App\Models\OffertNotification;

class VenteController extends Controller
{


    /**
     * Mettre à jour les quantités disponibles des variantes de produits de la famille "bar"
     *
     * @return void
     */
    // public function calculeQteVarianteProduit()
    // {
    //     // Récupérer les produits appartenant à la famille "bar"
    //     $data_produit_bar = Produit::withWhereHas('categorie', fn($q) => $q->where('famille', 'bar'))
    //         ->orderBy('created_at', 'DESC')
    //         ->get();

    //     foreach ($data_produit_bar as $produit) {
    //         // Mettre à zéro toutes les quantités disponibles des variantes du produit
    //         DB::table('produit_variante')
    //             ->where('produit_id', $produit->id)
    //             ->update(['quantite_disponible' => 0]);

    //         // Récupérer toutes les variantes associées au produit
    //         $variantes = DB::table('produit_variante')
    //             ->where('produit_id', $produit->id)
    //             ->get();

    //         foreach ($variantes as $variante) {
    //             // Calculer la nouvelle quantité disponible
    //             $nouvelle_quantite = $produit->stock * $variante->quantite;

    //             // Mettre à jour la quantité disponible
    //             DB::table('produit_variante')
    //                 ->where('produit_id', $produit->id)
    //                 ->where('variante_id', $variante->variante_id)
    //                 ->update(['quantite_disponible' => $nouvelle_quantite]);
    //         }
    //     }
    // }


    public function index(Request $request)
    {
        try {


            $caisses = Caisse::all();
            // client

            $clients = User::whereHas('roles', function ($query) {
                $query->where('name', 'client');
            })->get();


            // Récupérer les ventes avec les relations nécessaires
            $query = Vente::with(['produits', 'produitsOfferts', 'offerts'])
                ->whereStatut('confirmée')
                ->withCount([
                    'offerts as offerts_en_attente' => function ($query) {
                        $query->whereNull('offert_statut');
                    },
                    'offerts as offerts_approuved' => function ($query) {
                        $query->whereNotNull('offert_statut');
                    },
                ])
                ->orderBy('created_at', 'desc');



            // sinon on applique le filtre des date et caisse
            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');
            $caisse = $request->input('caisse');
            $periode = $request->input('periode');
            $statut_paiement = $request->input('statut_paiement'); // paye ou impaye
            $client = $request->input('client');

            // uniquement a la caisse
            $statut_reglement = $request->input('statut_reglement'); // 0 : non réglée, 1 : réglée
            $statut_vente = $request->input('statut_vente'); // confirmée, annulée, en attente



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

            // Application du filtre de statut de paiement
            if ($request->filled('statut_paiement')) {
                $query->where('statut_paiement', $request->statut_paiement);
            }

            // Application du filtre de statut de vente
            if ($request->filled('statut_vente')) {
                $query->where('statut', $request->statut_vente);
            }
            // Application du filtre de statut de règlement
            if ($request->filled('statut_reglement')) {
                $query->where('statut_reglement', $request->statut_reglement);
            }

            // Application du filtre de client
            if ($request->filled('client')) {
                $query->where('client_id', $request->client);
            }



            /**si l'utilisateur a le rôle 'caisse' ou 'supercaisse' on affiche les ventes de la caisse actuelle */
            $offertsEnAttente = 0; // Initialiser la variable pour les offerts en attente
            if ($request->user()->hasRole(['caisse', 'supercaisse'])) {
                $query->where('caisse_id', auth()->user()->caisse_id)
                    ->where('user_id', auth()->user()->id)
                    ->where('statut_cloture', false)
                    ->whereDate('date_vente', auth()->user()->caisse->session_date_vente); // ✅ Compare seulement la date

                // recuperer les offerts en attente
            }

            // retourne les données de vente filtrées
            $data_vente = $query->get();







            ## fin du filtre de recherche


            //Recuperer la session de la date vente manuelle et verifier si la caisse actuelle a effectuer des vente clotureé  a sa date de vente
            $sessionDate = null;
            $venteCaisseCloture = null;
            $venteCaisseNonCloture = null;
            $venteAucunReglement = null;
            $totalVentesCaisse = null;
            $offertsEnAttente = 0;

            $reglementImpayes = collect(); // Initialiser une collection vide pour les règlements impayés
            if ($request->user()->hasRole(['caisse', 'supercaisse'])) {

                //Recuperer la session de la date vente manuelle
                $sessionDate = Caisse::find(Auth::user()->caisse_id);
                $sessionDate = $sessionDate->session_date_vente;


                // recuperer les vente de la caisse actuelle qui sont cloturées
                $venteCaisseCloture = Vente::where('caisse_id', auth()->user()->caisse_id)
                    ->where('user_id', auth()->user()->id)
                    ->where('statut_cloture', true)
                    ->whereDate('date_vente', $sessionDate) // ✅ Compare seulement la date
                    ->count();


                // Verifier si il ya des vente non cloturer
                $venteCaisseNonCloture = Vente::where('caisse_id', auth()->user()->caisse_id)
                    ->where('user_id', auth()->user()->id)
                    ->where('statut_cloture', false)
                    ->whereDate('date_vente', $sessionDate) // ✅ Compare seulement la date
                    ->count();


                // recuperer les vente de la caisse actuelle qui nont aucun reglement
                $venteAucunReglement = Vente::where('caisse_id', auth()->user()->caisse_id)
                    ->where('user_id', auth()->user()->id)
                    ->where('statut_reglement', false)
                    ->whereDate('date_vente', $sessionDate) // ✅ Compare seulement la date
                    ->count();

                // recuperer le montant total des ventes de la caisse actuelle
                $totalVentesCaisse = Vente::where('caisse_id', auth()->user()->caisse_id)
                    ->where('user_id', auth()->user()->id)
                    ->where('statut_cloture', false)
                    ->whereDate('date_vente', $sessionDate)
                    ->sum('montant_total');

                // dd($totalVentesCaisse);


                /** Récupération des reglements impayés */

                // Récupération des ventes impayées autres que la date de session actuelle
                $venteImpayes = Vente::where('statut_paiement', 'impaye')
                    // ->whereDate('date_vente', '!=', auth()->user()->caisse->session_date_vente)
                    ->where('statut_cloture', true)
                    ->get();

                // Récupération des règlements faits aujourd'hui sur ces ventes impayées
                $reglementImpayes = Reglement::whereDate('date_reglement', $sessionDate)
                    ->whereIn('vente_id', $venteImpayes->pluck('id'))
                    ->where('user_id', auth()->user()->id)
                    ->get();


                    // dd($reglementImpayes);

                // recuperer les offerts en attente
                $offertsEnAttente = Vente::where('caisse_id', auth()->user()->caisse_id)
                    ->where('user_id', auth()->user()->id)
                    ->where('statut_cloture', false)
                    ->whereDate('date_vente', $sessionDate) // ✅ Compare seulement la date
                    ->whereHas('offerts', function ($query) {
                        $query->whereNull('offert_statut');
                    })->count();




                // dd($reglementImpayes , $venteImpayes->toArray());
            }






            return view('backend.pages.vente.index', compact('reglementImpayes', 'data_vente', 'caisses', 'sessionDate', 'venteCaisseCloture', 'venteCaisseNonCloture', 'venteAucunReglement', 'totalVentesCaisse', 'clients', 'offertsEnAttente'));
        } catch (\Exception $e) {
            Alert::error('Erreur', 'Une erreur est survenue lors du chargement des ventes : ' . $e->getMessage());
            return back();
        }
    }


    //recuperer les vente de la caisse actuelle
    public function getVenteCaisse(Request $request)
    {
        try {

            // Récupérer les ventes avec les relations nécessaires
            $query = Vente::with(['produits', 'produitsOfferts', 'offerts'])
                ->whereStatut('confirmée')
                ->withCount([
                    'offerts as offerts_en_attente' => function ($query) {
                        $query->whereNull('offert_statut');
                    },
                    'offerts as offerts_approuved' => function ($query) {
                        $query->whereNotNull('offert_statut');
                    },
                ])
                ->orderBy('created_at', 'desc');


            /**si l'utilisateur a le rôle 'caisse' ou 'supercaisse' on affiche les ventes de la caisse actuelle */
            if ($request->user()->hasRole(['caisse', 'supercaisse'])) {
                $query->where('caisse_id', auth()->user()->caisse_id)
                    ->where('user_id', auth()->user()->id)
                    ->where('statut_cloture', false)
                    ->whereDate('date_vente', auth()->user()->caisse->session_date_vente); // ✅ Compare seulement la date

            }

            // retourne les données de vente filtrées
            $ventes = $query->get();

            return response()->json([
                'ventes' => $ventes,
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement de la page.' . $e->getMessage());
        }
    }

    /**Historique de vente du client */
    public function historiqueVenteClient(Request $request)
    {
        try {
            // Récupérer l'ID du client depuis la requête
            $clientId = $request->input('client');
            $statutPaiement = $request->input('statut_paiement'); // paye ou impaye

            $client = User::findOrFail($clientId); // Récupérer le client par son ID

            $query = Vente::with('produits')
                ->whereStatut('confirmée')
                // ->where('statut_cloture', true)
                ->orderBy('created_at', 'desc');

            // Application du filtre de client
            if ($request->filled('client')) {
                $query->where('client_id', $clientId);
            }
            // Application du filtre de statut de paiement
            if ($request->filled('statut_paiement')) {
                $query->where('statut_paiement', $statutPaiement);
            }

            $ventes = $query->get();

            // dd($ventes->toArray());

            return view('backend.pages.vente.partials.vente-client.historique-vente', compact('client', 'ventes'));
        } catch (\Throwable $th) {
            Alert::error('Erreur', $th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    /*
    * Afficher la liste des ventes en attente
    */

    public function venteEnAttente()
    {
        try {

            return view('backend.pages.vente.partials.venteEnAttente.listeVenteAttente');
        } catch (\Exception $e) {
            Alert::error('Erreur', 'Une erreur est survenue lors du chargement des ventes en attente : ' . $e->getMessage());
            return back();
        }
    }



    public function create()
    {
        try {


            // appeler la fonction calculeQteVarianteProduit
            // $this->calculeQteVarianteProduit(); // calcule la quantité de chaque produit variantes


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

            $now = Carbon::now();

            $menu = Menu::where('date_menu', '<=', $now)             // Menu déjà commencé
                ->where('date_menu', '>=', $now->copy()->subDay()) // Menu pas plus vieux que 24h
                ->with([
                    'plats' => function ($query) use ($now) {
                        $query->with([
                            'categorieMenu',
                            'complements' => function ($query) use ($now) {
                                $query->wherePivot('menu_id', function ($subQuery) use ($now) {
                                    $subQuery->select('id')
                                        ->from('menus')
                                        ->where('date_menu', '<=', $now)
                                        ->where('date_menu', '>=', $now->copy()->subDay());
                                });
                            },
                            'garnitures' => function ($query) use ($now) {
                                $query->wherePivot('menu_id', function ($subQuery) use ($now) {
                                    $subQuery->select('id')
                                        ->from('menus')
                                        ->where('date_menu', '<=', $now)
                                        ->where('date_menu', '>=', $now->copy()->subDay());
                                });
                            }
                        ]);
                    },
                ])
                ->first();


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
     * Mettre à jour le stock des variantes du produit
     *
     * @param int $id L'ID du produit
     *
     * @return void
     */

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


            // GENERER LE CODE DE LA VENTE
            // Obtenir les deux premières lettres du nom de la caissière
            $initialesCaissiere = substr(auth()->user()->first_name, 0, 2);
            $initialesCaisse = substr(auth()->user()->caisse->libelle, 0, 2); //

            // Obtenir le numéro d'ordre de la vente pour aujourd'hui
            $nombreVentes = Vente::count();
            $numeroOrdre = $nombreVentes + 1;

            // Obtenir la date et l'heure actuelles
            $dateHeure = now()->format('dmYHi');



            // Générer le code de vente
            // $codeVente = strtoupper($initialesCaissiere) . '-' . strtoupper($initialesCaisse) . $numeroOrdre . $dateHeure;
            $codeVente =  $numeroOrdre . $dateHeure;

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


                'numero_table' => $numeroDeTable,
                'nombre_couverts' => $nombreDeCouverts,
                'statut' => 'confirmée',

                // informations de paiement
                'mode_paiement' => $modePaiement ?? 'espece',
                'montant_recu' => $montantRecu,
                'montant_rendu' => $montantRendu,

                'montant_restant' => $montantApresRemise - $montantRecu,
                'statut_paiement' => $montantRecu >= $montantApresRemise ? 'paye' : 'impaye',
                'statut_reglement' => $montantRecu >= $montantApresRemise ? 1 : 0, // 0 = non reglé, 1 = réglé
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
                        'offert' => $item['offert'] ?? false, // Ajout de la colonne 'offert'
                    ]);

                    // Récupérer le produit
                    $produit = Produit::find($item['id']);

                    // Vérifier si le produit appartient à la famille "bar"
                    if ($produit && optional($produit->categorie)->famille == 'bar') {
                        // retirer la quantité de bouteilles vendues du stock du produit
                        $produit->stock -= $item['quantity'];
                        $produit->save();
                    }
                }


                // ajouter les offerts dans la vente
                // 1. Vérifier s'il y a des produits offerts
                $offerts = array_filter($cart, function ($item) {
                    return isset($item['offert']) && $item['offert'] == true;
                });

                // 2. Si des produits sont offerts, enregistrer dans la table `offerts`
                if (!empty($offerts)) {
                    foreach ($offerts as $item) {
                        Offert::create([
                            'produit_id' => $item['id'],
                            'vente_id' => $vente->id,
                            'quantite' => $item['quantity'],
                            'prix' => $item['price'],
                            'statut_view' => false,
                            'offert_statut' => null,
                            'user_approuved' => null,
                            'user_created' => $vente->user_id,
                            'date_created' => Carbon::now(),
                            'date_approuved' => null,
                        ]);
                    }
                }
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




    public function show(Request $request, $id)
    {
        try {
            $vente = Vente::findOrFail($id);

            $client = User::whereHas('roles', function ($query) {
                $query->where('name', 'client');
            })->get();


            //Recuperer la session de la date vente manuelle
            $sessionDate = null;
            if ($request->user()->hasRole(['caisse', 'supercaisse'])) {
                $sessionDate = Caisse::whereId(Auth::user()->caisse_id)->value('session_date_vente');
            }

            // Vérifier si la vente a des produits offerts en attente
            $offertsEnAttente = $vente->offerts->whereNull('offert_statut')->count();


            // marquer notification de la vente offert comme vue
            if ($vente) {
                OffertNotification::where('vente_id', $vente->id)->update(['is_read' => true]);
            }


            return view('backend.pages.vente.show', compact('vente', 'client', 'sessionDate', 'offertsEnAttente'));
        } catch (Exception $e) {
            return back()->with('error', "La vente demandée n'existe plus." . $e->getMessage());
            // return redirect()->route('vente.index')->with('error', "La vente demandée n'existe plus." , $e->getMessage());
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
                    ->whereDate('date_vente', auth()->user()->caisse->session_date_vente) // ✅ Compare seulement la date
                    ->where('statut_cloture', false)->sum('montant_total');


                $totalVenteImpayer = Vente::where('caisse_id', auth()->user()->caisse_id)
                    ->where('user_id', auth()->user()->id)
                    ->whereDate('date_vente', auth()->user()->caisse->session_date_vente) // ✅ Compare seulement la date
                    ->where('statut_cloture', false)
                    ->where('statut_paiement', 'impaye')
                    ->sum('montant_restant');



                /** Récupération des reglements impayés */

                // Récupération des ventes impayées autres que la date de session actuelle
                $venteImpayes = Vente::where('statut_paiement', 'impaye')
                    // ->whereDate('date_vente', '!=', auth()->user()->caisse->session_date_vente)
                    ->where('statut_cloture', true)
                    ->get();

                // Récupération des règlements faits aujourd'hui sur ces ventes impayées
                $reglementImpayes = Reglement::whereDate('date_reglement', auth()->user()->caisse->session_date_vente)
                    ->where('user_id', auth()->user()->id)
                    ->whereIn('vente_id', $venteImpayes->pluck('id'))
                    ->sum('montant_reglement');

                    // dd($reglementImpayes);




                $totalVenteCaisse = ($totalVente + $reglementImpayes) - $totalVenteImpayer;
            }

            // dd($type_monnaies , $billets, $pieces, $totalVente);

            return view('backend.pages.vente.billeterie.create', compact(
                'modes',
                'type_monnaies',
                'billets',
                'pieces',
                'totalVente',
                'type_mobile_money',
                'totalVenteImpayer',
                'reglementImpayes', // les reglements des impayés des ventre autrement que la session de la caisse actuelle
                'totalVenteCaisse'
            ));
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
                    // 'montant_impaye' => $montantTotalImpaye ?? 0,
                    'total' => $variante['total'],
                    'caisse_id' => $caisse->id,
                    'user_id' => $user->id,
                    'date_save' => Auth::user()->caisse->session_date_vente,
                ]);
            }

            // si  il n'y a pas d'erreur lors de l'enregistrement de billetterie appel a fonction clotureCaisse
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



            // pour les vente bar et plats quotidiens

            $ventes = $query
                ->where('caisse_id', auth()->user()->caisse_id)
                ->where('user_id', auth()->user()->id)
                ->where('statut_cloture', true)
                ->whereDate('date_vente', auth()->user()->caisse->session_date_vente)
                ->withWhereHas('produits', function ($query) {
                    $query->where(function ($q) {
                        $q->where(function ($sub) {
                            $sub->where('offert', 1)
                                ->where('offert_statut', 0);
                        })
                            ->orWhere(function ($sub) {
                                $sub->where('offert', 0);
                            });
                    });
                })
                ->get();



            // pour la vente des plats menu du jour
            $ventesMenu = $queryMenu
                ->where('caisse_id', auth()->user()->caisse_id)
                ->where('user_id', auth()->user()->id)
                ->where('statut_cloture', true)
                ->whereDate('date_vente', auth()->user()->caisse->session_date_vente)
                ->get();


            // pour les produits restaurant et bar
            $produitsVendus = $ventes
                ->flatMap(fn($vente) => $vente->produits)
                ->groupBy('id')
                ->map(function ($groupe) use ($categorieFamille) {
                    $produit = $groupe->first();
                    $pivotFirst = $produit->pivot;

                    // Filtre par famille si demandé
                    if ($categorieFamille && $produit->categorie->famille !== $categorieFamille) {
                        return null;
                    }

                    // Calcul du montant total en filtrant les offerts
                    $montantTotal = $groupe->sum(function ($item) {
                        if ($item->pivot->offert_statut === 0 || is_null($item->pivot->offert_statut)) {
                            return $item->pivot->quantite * $item->pivot->prix_unitaire;
                        }
                        return 0;
                    });

                    return [
                        'details' => $groupe,
                        'id' => $produit->id,
                        'code' => $produit->code,
                        'stock' => $produit->stock,
                        'designation' => $produit->nom,
                        'variante' => $produit->variante->libelle ?? '',
                        'categorie' => $produit->categorie->name,
                        'famille' => $produit->categorie->famille,
                        'quantite_vendue' => $groupe->sum('pivot.quantite'),
                        'prix_vente' => $pivotFirst->prix_unitaire,
                        'montant_total' => $montantTotal,
                    ];
                })
                ->filter()
                ->values();


            // dd($produitsVendus->toArray());


            //pour les plats menu
            $platsVendus = $ventesMenu
                ->flatMap(fn($vente) => $vente->plats)
                ->groupBy(fn($plat) => $plat->id . '-' . $plat->pivot->prix_unitaire) // Groupement par plat et prix
                ->map(function ($groupe) {
                    $plat = $groupe->first();

                    return [
                        'id' => $plat->id,
                        'code' => $plat->code,
                        'stock' => 100, // À calculer si nécessaire
                        'designation' => $plat->nom,
                        'categorie' => $plat->categorieMenu->nom,
                        'famille' => 'menu du jour',
                        'quantite_vendue' => $groupe->sum('pivot.quantite'),
                        'prix_vente' => $plat->pivot->prix_unitaire, // ou avg si ça varie
                        'montant_total' => $groupe->sum(fn($item) => $item->pivot->quantite * $item->pivot->prix_unitaire),
                    ];
                })
                ->values();

            // dd($platsVendus->toArray());




            //RECUPERER LES PRODUITS VENDUS MAIS OFFERTS
            $queryProduitsOfferts = Vente::with(['offerts.produit'])
                ->where('caisse_id', auth()->user()->caisse_id) // Récupérer les ventes de la caisse actuelle
                ->whereDate('date_vente', auth()->user()->caisse->session_date_vente) // ✅ Compare seulement la date
                ->where('user_id', auth()->user()->id) // Récupérer les ventes de l'utilisateur actuel
                ->withWhereHas('produits', function ($query) {
                    $query->where('offert_statut', 1);
                    $query->where('offert', 1);
                })
                ->where('statut_cloture', true)
                ->get();


            // mapper les produits offerts
            $produitsOfferts = $queryProduitsOfferts->flatMap(function ($vente) {
                return $vente->produits;
            })->groupBy('id')->map(function ($groupe) {
                $produit = $groupe->first();

                return [
                    'details' => $groupe, // recuperer les details groupés par produit
                    'id' => $produit->id,
                    'code' => $produit->code,
                    'designation' => $produit->nom,
                    'categorie' => $produit->categorie->name,
                    'famille' => 'offerts',
                    'quantite_vendue' => $groupe->sum('pivot.quantite'),
                    'prix_vente' => $groupe->first()->pivot->prix_unitaire,
                    'montant_total' => $groupe->sum(function ($item) {
                        return $item->pivot->quantite * $item->pivot->prix_unitaire;
                    }),
                ];
            })->filter()->values();







            $produitsVendus =  $produitsVendus->concat($produitsOfferts)->concat($platsVendus);



            /**Recuperer les billetteries */
            $billetterie = Billetterie::where('caisse_id', auth()->user()->caisse_id)
                ->where('user_id', auth()->user()->id)
                ->whereDate('date_save', auth()->user()->caisse->session_date_vente)
                ->get();


            // Tableau pour stocker les résultats
            $resultats = [
                'mode_espece' => 0, // Total pour le mode 0 (Espèce)
                'mode_digital' => [], // Total pour le mode 1 (Mobile money) par type
                'mode_impaye' => 0, // Total pour le mode 0 (impayé)
                'mode_offert' => 0, // Total des ventes oferts


            ];

            // Regrouper et additionner les totaux
            foreach ($billetterie as $item) {
                if ($item->mode == 0) {
                    $resultats['mode_espece'] += $item->total;
                } elseif ($item->mode == 1) {
                    $type = $item->type_mobile_money ?? 0;
                    if (!isset($resultats['mode_digital'][$type])) {
                        $resultats['mode_digital'][$type] = 0;
                    }
                    $resultats['mode_digital'][$type] += $item->total;
                }
            }

            //calculer le montant total impayé des ventes de la caisse
            $montantTotalImpaye = Vente::where('caisse_id', auth()->user()->caisse_id)
                ->where('user_id', auth()->user()->id)
                ->where('statut_cloture', true)
                ->whereDate('date_vente', auth()->user()->caisse->session_date_vente) // ✅ Compare seulement la date
                ->sum('montant_restant');

            // Ajouter le montant impayé au résultat
            $resultats['mode_impaye'] = $montantTotalImpaye;
            // Ajouter le montant des ventes offerts au résultat
            $resultats['mode_offert'] = $produitsOfferts->sum('montant_total');


            // Libellés
            $modes = [
                0 => 'Espèce',
                1 => 'Mobile money',
                2 => 'Impayées',
                3 => 'Offerts',

            ];

            $type_mobile_money = [
                0 => 'Wave',
                1 => 'Moov money',
                2 => 'Orange Money',
                3 => 'MTN money',
                4 => 'MasterCard',
                5 => 'Visa',
            ];


            // recuperer les impayes de ventes


            /** End Afficher les billetteries */
            // dd($resultats);

            $caisses = Caisse::all();
            $famille = Categorie::whereNull('parent_id')->whereIn('type', ['bar', 'menu'])->orderBy('name', 'DESC')->get();




            /** Récupération des reglements impayés */

            // Récupération des ventes impayées autres que la date de session actuelle
            $venteImpayes = Vente::where('statut_paiement', 'impaye')
                // ->whereDate('date_vente', '!=', auth()->user()->caisse->session_date_vente)
                ->where('statut_cloture', true)
                ->get();

            // Récupération des règlements faits aujourd'hui sur ces ventes impayées
            $reglementImpayes = Reglement::whereDate('date_reglement', auth()->user()->caisse->session_date_vente)
                ->where('user_id', auth()->user()->id)
                ->whereIn('vente_id', $venteImpayes->pluck('id'))
                ->sum('montant_reglement');




            // dd($produitsOfferts->toArray());


            return view('backend.pages.vente.rapportVente', compact('reglementImpayes', 'platsVendus', 'produitsVendus', 'caisses', 'categorieFamille', 'famille', 'modes', 'type_mobile_money', 'resultats', 'produitsOfferts'));
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
