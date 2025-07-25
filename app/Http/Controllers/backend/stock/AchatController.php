<?php

namespace App\Http\Controllers\backend\stock;

use App\Models\Achat;
use App\Models\Stock;
use App\Models\Unite;
use App\Models\Format;
use App\Models\Depense;
use App\Models\Facture;
use App\Models\Magasin;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Fournisseur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LibelleDepense;
use App\Models\CategorieDepense;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AchatController extends Controller
{
    //


    public function index()
    {
        try {
            $data_facture = Facture::with('achats')->orderBy('created_at', 'DESC')->get();
            // dd($data_facture->toArray());
            return view('backend.pages.stock.achat.index', compact('data_facture'));
        } catch (\Throwable $e) {
            return  $e->getMessage();
        }
    }

    public function show($id)
    {
        try {
            $facture = Facture::whereId($id)->first();

            // Vérifier si la facture existe
            if (!$facture) {
                return redirect()->route('achat.index')->with('error', "La facture demandée n'existe pas.");
            }

            $data_achat = Achat::where('facture_id', $id)->get();

            return view('backend.pages.stock.achat.show', compact('data_achat', 'facture'));
        } catch (\Exception $e) {
            return redirect()->route('factures.index')->with('error', "Une erreur s'est produite. Veuillez réessayer.");
        }
    }

    public function create(Request $request)
    {

        try {
            $data_categorie = Categorie::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')->OrderBy('position', 'ASC')->get();
            $data_produit = Produit::whereHas('categorie', function ($query) {
                $query->whereIn('famille', ['bar', 'restaurant']);
            })->with(['categorie.ancestors', 'media', 'unite', 'uniteSortie'])
                ->whereStatut('active')
                ->orderBy('nom', 'ASC')->get();
            $type_produit = Categorie::whereNull('parent_id')->whereIn('type', ['bar', 'restaurant'])->get();

            $data_format = Format::all();
            $data_unite = Unite::all();
            $data_fournisseur = Fournisseur::all();
            $data_magasin = Magasin::all();


            // dd($data_produit->toArray());
            return view('backend.pages.stock.achat.create', compact('type_produit', 'data_categorie', 'data_produit', 'data_format', 'data_unite', 'data_fournisseur', 'data_magasin'));
        } catch (\Throwable $e) {
            return  $e->getMessage();
        }

        // dd($data_categorie->toArray());

    }

    public function checkFactureExist(Request $request)
    {
        // Récupérer les valeurs envoyées par AJAX
        $numero = $request->input('numero'); // Récupère la valeur de 'numero'
        $fournisseur = $request->input('fournisseur'); // Récupère la valeur de 'fournisseur'

        // Vérifier dans la base de données si la facture existe
        $factureExistante = Facture::where('numero_facture', $numero)
            ->where('fournisseur_id', $fournisseur)
            ->exists();

        // si exist on retourne une response

        return response()->json(
            [
                'exist' => $factureExistante,
                'message' => $factureExistante == true ? 'Vous avez déjà enregistré cette facture' : ''
            ],
            200
        );
    }



    public function verifiyMontant(Request $request)
    {
        $montant_facture = 0;
        // Parcourir tous les éléments de prix_total_format
        foreach ($request->prix_total_format as $index => $prixTotal) {
            // Additionner chaque prix_total_format au montant total de la facture
            $montant_facture += $prixTotal;
        }

        // Vérifier si le montant total de la facture est égal au montant
        // de la facture
        if ($montant_facture == $request->montant_facture) {
            return response()->json(['message' => 'Montant facture correct', 'status' => true], 200);
        } else {
            return response()->json(['message' => 'Montant facture incorrect', 'status' => false], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            // dd($request->all());

            $montant_facture = 0;
            // Parcourir tous les éléments de prix_total_format
            foreach ($request->prix_total_format as $index => $prixTotal) {
                // Additionner chaque prix_total_format au montant total de la facture
                $montant_facture += $prixTotal;
            }


            //gestion des validations 
            // foreach ($request->produit_id as $index => $produitId) {
            //     $produit = Produit::find($produitId);
            //     $categorie = Categorie::find($produit->categorie_id); // catégorie du produit

            //     // récupérer le type de produit
            //     $type_produit = $categorie->famille;

            //     // Validation en fonction du type de produit
            //     if ($type_produit == 'restaurant') {
            //         // Validation pour un produit de type "restaurant"
            //         $request->validate([
            //             'type' => 'required', // type facture
            //             'numero_facture' => 'required',
            //             'montant' => 'required', // montant de la facture
            //             'date_achat' => 'required',
            //             // 'magasin_id.*' => 'required|exists:magasins,id',
            //             'produit_id.*' => 'required|exists:produits,id',
            //             'fournisseur_id.*' => 'required|exists:fournisseurs,id',
            //             'format_id.*' => 'required|exists:formats,id|min:1',
            //             'quantite_format.*' => 'required',
            //             'quantite_in_format.*' => 'required',
            //             'quantite_stocke.*' => 'required',
            //             'prix_unitaire_format.*' => 'required',
            //             'prix_total_format.*' => 'required',
            //             // Ces champs ne sont pas requis pour le restaurant
            //             'prix_achat_unitaire.*' => 'nullable',
            //             'prix_vente_unitaire.*' => 'nullable',
            //             // 'unite_sortie.*' => 'required',
            //         ]);
            //     } elseif ($type_produit == 'bar') {
            //         // Validation pour un produit de type "bar"
            //         $request->validate([
            //             'type' => 'required', // type facture
            //             'numero_facture' => 'required',
            //             'montant' => 'required', // montant de la facture
            //             'date_achat' => 'required',
            //             // 'magasin_id.*' => 'required|exists:magasins,id',
            //             'produit_id.*' => 'required|exists:produits,id',
            //             'fournisseur_id.*' => 'required|exists:fournisseurs,id',
            //             'format_id.*' => 'required|exists:formats,id',
            //             'quantite_format.*' => 'required',
            //             'quantite_in_format.*' => 'required',
            //             'quantite_stocke.*' => 'required',
            //             'prix_unitaire_format.*' => 'required',
            //             'prix_total_format.*' => 'required',
            //             // Ces champs sont requis pour le bar
            //             'prix_achat_unitaire.*' => 'required',
            //             'prix_vente_unitaire.*' => 'required',
            //             // 'unite_sortie.*' => 'required|exists:unites,id',
            //         ]);
            //     }
            // }

            // Gestion des validations
            $rules = [];
            $messages = [];

            foreach ($request->produit_id as $index => $produitId) {
                $produit = Produit::find($produitId);

                if (!$produit) {
                    $messages["produit_id.{$index}"] = "Le produit avec l'ID {$produitId} n'existe pas.";
                    continue;
                }

                $categorie = Categorie::find($produit->categorie_id);

                if (!$categorie) {
                    $messages["categorie_id.{$index}"] = "La catégorie pour le produit avec l'ID {$produitId} n'existe pas.";
                    continue;
                }

                // Récupérer le type de produit
                $type_produit = $categorie->famille;

                // Règles de validation communes
                $rules["type"] = 'required';
                $rules["numero_facture"] = 'required';
                $rules["montant"] = 'required';
                $rules["date_achat"] = 'required';
                $rules["produit_id.{$index}"] = 'required|exists:produits,id';
                $rules["fournisseur_id"] = 'required|exists:fournisseurs,id';
                $rules["format_id.{$index}"] = 'required|exists:formats,id';
                $rules["quantite_format.{$index}"] = 'required';
                $rules["quantite_in_format.{$index}"] = 'required';
                $rules["quantite_stocke.{$index}"] = 'required';
                $rules["prix_unitaire_format.{$index}"] = 'required';
                $rules["prix_total_format.{$index}"] = 'required';

                // Validation spécifique au type de produit
                if ($type_produit == 'restaurant') {
                    // Champs optionnels pour les produits de type restaurant
                    $rules["prix_achat_unitaire.{$index}"] = 'nullable';
                    $rules["prix_vente_unitaire.{$index}"] = 'nullable';
                } elseif ($type_produit == 'bar') {
                    // Champs obligatoires pour les produits de type bar
                    $rules["prix_achat_unitaire.{$index}"] = 'required';
                    $rules["prix_vente_unitaire.{$index}"] = 'required';
                }
            }

            // Valider les règles et les messages collectés
            $request->validate($rules, $messages);


            //on verifie le montant de la facture et le montant total des achats
            if ($request->montant != $montant_facture) {
                return response()->json(['message' => 'Montant facture incorrect', 'status' => false], 500);
            }

            //enregistrer la facture 
            $facture = new Facture();
            $facture->type = $request->type;
            $facture->numero_facture = $request->type == 'facture' ? 'FAC-' . $request->numero_facture : 'BS-' . $request->numero_facture;
            $facture->date_facture = $request->date_achat;
            $facture->fournisseur_id = $request->fournisseur_id;
            $facture->montant = $request->montant;
            $facture->user_id = Auth::id();
            $facture->save();



            // récupérer les infos de produit en tableau
            foreach ($request->produit_id as $index => $produitId) {

                $produit = Produit::find($produitId);
                $categorie = Categorie::find($produit->categorie_id); // catégorie du produit


                // définir le statut du produit
                // $statut = $request->statut[$index] == 'on' ? 'active' : 'desactive';

                // récupérer le type de produit
                $type_produit = $categorie->famille;



                // création de l'achat
                Achat::firstOrCreate([
                    'code' => 'SA-' . strtoupper(Str::random(8)),
                    'facture_id' => $facture->id,
                    'type_produit_id' => $categorie->id,
                    'numero_facture' => $request->numero_facture,
                    'date_achat' => $request->date_achat,
                    'produit_id' => $request->produit_id[$index],
                    'fournisseur_id' => $request->fournisseur_id,
                    'format_id' => $request->format_id[$index],
                    'quantite_format' => $request->quantite_format[$index],
                    'quantite_in_format' => $request->quantite_in_format[$index],
                    'quantite_stocke' => $request->quantite_stocke[$index],
                    'prix_unitaire_format' => $request->prix_unitaire_format[$index],
                    'prix_total_format' => $request->prix_total_format[$index],
                    'prix_achat_unitaire' => $type_produit == 'bar' ? $request->prix_achat_unitaire[$index] : null,
                    'prix_vente_unitaire' =>  $type_produit == 'bar' ? $request->prix_vente_unitaire[$index] : null,
                    // 'unite_id' => $request->unite_sortie[$index],
                    // 'magasin_id' => $request->magasin_id,
                    'statut' => 'active',
                    'user_id' => Auth::id(),
                ]);

                // mise à jour du stock dans la table produit si le statut est 'active'
                $produit->stock += $request->quantite_stocke[$index];
                $produit->stock_initial += $request->quantite_stocke[$index];
                $produit->save();
                // if ($statut == 'active') {
                //     $produit->stock += $request->quantite_stocke[$index];
                //     $produit->save();
                // }



                // //Mettre a jour la table produit_variantes en mettant à jour la quantité disponible de chaque variante

                if ($type_produit == 'bar') {

                    // Récupérer toutes les variantes associées au produit
                    $variantes = DB::table('produit_variante')
                        ->where('produit_id', $request->produit_id[$index])
                        ->get(); // Récupérer toutes les variantes du produit


                    foreach ($variantes as $variante) {
                        // Récupérer la quantité disponible actuelle
                        $quantite_disponible_actuelle = DB::table('produit_variante')
                            ->where('produit_id', $request->produit_id[$index])
                            ->where('variante_id', $variante->variante_id)
                            ->value('quantite_disponible'); // Récupère uniquement la colonne quantite_disponible

                        // Calculer la nouvelle quantité
                        $nouvelle_quantite = $quantite_disponible_actuelle + ($request->quantite_stocke[$index] * $variante->quantite);

                        // Mettre à jour la quantité disponible
                        DB::table('produit_variante')
                            ->where('produit_id', $request->produit_id[$index])
                            ->where('variante_id', $variante->variante_id)
                            ->update([
                                'quantite_disponible' => $nouvelle_quantite,
                            ]);
                    }
                }
            }

            //ajouter l'achat comme depense
            $categorie = CategorieDepense::whereSlug('achats')->first();
            $libelle_depense = LibelleDepense::whereSlug('marchandises')->first();

            $data_depense = Depense::firstOrCreate([
                'categorie_depense_id' => $categorie->id,
                'libelle_depense_id' => $libelle_depense->id,
                'montant' => $request->montant,
                'date_depense' => $request->date_achat,
                'facture_id' => $facture->id,
                'user_id' => Auth::id()
            ]);


            // retur response
            return response()->json([
                'message' => 'Facture enregistré avec succès.',
                'statut' => 'success',
            ], 200);




            // Alert::success('Opération réussie', 'Tous les produits ont été enregistrés avec succès.');
            // return back();



        } catch (\Throwable $e) {


            if ($e->getMessage() == "foreach() argument must be of type array|object, null given") {
                return response()->json([
                    'message' =>  'Veuillez ajouter un achat pour continuer.',
                    'statut' => 'error',
                ], 500);

                // Alert::error('Erreur', 'Veuillez ajouter un achat pour continuer.');
                // return back();
            } else {
                // Alert::error('Erreur', 'Une erreur s\'est produite lors de l\'en
                // registrement des achats.');
                // return back();
                return response()->json([
                    'message' => $e->getMessage(),
                    'statut' => 'error',
                ], 500);
            }
            // si une erreur se produit, afficher le message d'erreur
            // Alert::error('Erreur', 'Verifiez si u');
            // return back();
            // return $e->getMessage();
        }
    }


    public function edit($id)
    {
        try {
            $data_achat = Achat::find($id);

            $data_format = Format::all();
            $data_unite = Unite::all();
            $data_fournisseur = Fournisseur::all();
            return view('backend.pages.stock.achat.edit', compact('data_achat', 'data_format', 'data_unite', 'data_fournisseur'));
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data_achat = Achat::find($id);

            //statut stock libelle active ? desactive
            $statut = '';
            if ($request['statut'] == 'on') {
                $statut = 'active';
            } else {
                $statut = 'desactive';
            }

            $data_achat->update([
                'fournisseur_id' => $request['fournisseur_id'],
                'format_id' => $request['format_id'],
                'unite_id' => $request['unite_id'],
                'statut' => $statut,
            ]);

            //mise a jour du stock dans la table produit si le statut est active
            if ($statut == 'active') {
                $produit = Produit::find($data_achat['produit_id']);
                $produit->stock += $data_achat['quantite_stockable'];
                $produit->save();
            }
            //mise a jour du stock dans la table produit si le statut est desactive
            elseif ($statut == 'desactive') {
                $produit = Produit::find($data_achat['produit_id']);
                $produit->stock -= $data_achat['quantite_stockable'];
                $produit->save();
            }


            Alert::success('Opération réussi', 'Success Message');
            return back();
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    // public function delete($id)
    // {
    //     // recuperer l'achat liéà la facture
    //     $achat = Achat::where('facture_id', $id)->first();
    //     // recuperer le produit et la quantité
    //     $produit = Produit::find($achat->produit_id); // recuperer le produit lié à l'achat
    //     $quantite = $achat->quantite_stocke; // recuperer la quantité stockable de l'achat
    //     // mettre a jour le stock du produit
    //     $produit->stock -= $quantite;
    //     $produit->stock_initial -= $quantite;
    //     $produit->save();


    //     // //Enlever le stock dans la table ivot produit_variante
    //     // $variantes = DB::table('produit_variante')
    //     //     ->where('produit_id',  $produit)
    //     //     ->get(); // Récupérer toutes les variantes du produit

    //     // foreach ($variantes as $variante) {
    //     //     DB::table('produit_variante')
    //     //         ->where('produit_id',  $produit)
    //     //         ->where('variante_id', $variante->variante_id) // Vérifie que cette colonne existe
    //     //         ->update([
    //     //             'quantite_disponible' =>   $variante->quantite_disponible - $quantite
    //     //         ]);
    //     // }


    //     // A la suppression mettre à jour la quantité disponible des variantes du produit

    //     #### script pour mettre à jour la quantité disponible des variantes du produit

    //     // recuperer les produit de famille bar
    //     // Récupérer toutes les variantes associées au produit
    //     $variantes = DB::table('produit_variante')
    //         ->where('produit_id', $produit->id)
    //         ->get(); // Récupérer toutes les variantes du produit


    //     foreach ($variantes as $variante) {
    //         DB::table('produit_variante')
    //             ->where('produit_id', $produit->id)
    //             ->where('variante_id', $variante->variante_id)
    //             ->update([
    //                 'quantite_disponible' => 0,
    //             ]);



    //         // // Récupérer la quantité disponible actuelle
    //         // $quantite_disponible_actuelle = DB::table('produit_variante')
    //         //     ->where('produit_id', $produit->id)
    //         //     ->where('variante_id', $variante->variante_id)
    //         //     ->value('quantite_disponible'); // Récupère uniquement la colonne quantite_disponible

    //         // // Calculer la nouvelle quantité disponible
    //         // $nouvelle_quantite = $quantite_disponible_actuelle + ($produit->stock * $variante->quantite);

    //         // // Mettre à jour la quantité disponible
    //         // DB::table('produit_variante')
    //         //     ->where('produit_id', $produit->id)
    //         //     ->where('variante_id', $variante->variante_id)
    //         //     ->update([
    //         //         'quantite_disponible' => $nouvelle_quantite,
    //         //     ]);
    //     }


    //     // appeler la function miseAJourStock
    //     $this->miseAJourStock($achat->produit_id); // Appelle la fonction miseAJourStock avec l'id du produit

    //     Facture::find($id)->forceDelete();
    //     return response()->json([
    //         'status' => 200,
    //     ]);
    // }

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



    // public function delete($id)
    // {
    //     // Récupérer l'achat lié à la facture
    //     $achat = Achat::where('facture_id', $id)->first();

    //     if (!$achat) {
    //         return response()->json(['message' => 'Achat non trouvé'], 404);
    //     }

    //     // Récupérer le produit lié à l'achat
    //     $produit = Produit::find($achat->produit_id);
    //     if (!$produit) {
    //         return response()->json(['message' => 'Produit non trouvé'], 404);
    //     }

    //     // Récupérer la quantité stockable de l'achat
    //     $quantite = $achat->quantite_stocke;

    //     // Mettre à jour le stock du produit
    //     $produit->stock -= $quantite;
    //     $produit->stock_initial -= $quantite;
    //     $produit->save();

    //     // Mettre à jour la quantité disponible des variantes du produit
    //     DB::table('produit_variante')
    //         ->where('produit_id', $produit->id)
    //         ->update(['quantite_disponible' => 0]);

    //     // Appeler la fonction miseAJourStock
    //     $this->miseAJourStock($produit->id);

    //     // Supprimer la facture
    //     Facture::find($id)->forceDelete();

    //     return response()->json(['status' => 200]);
    // }


    public function delete($id)
    {
        // Récupérer tous les achats liés à la facture
        $achats = Achat::where('facture_id', $id)->get();

        // if ($achats->isEmpty()) {
        //     return response()->json(['message' => 'Aucun achat trouvé pour cette facture'], 404);
        // }

        foreach ($achats as $achat) {
            // Récupérer le produit lié à l'achat
            $produit = Produit::find($achat->produit_id);
            if (!$produit) {
                continue; // On saute si le produit n'existe pas
            }

            // Réduction du stock
            $quantite = $achat->quantite_stocke;
            $produit->stock -= $quantite;
            $produit->stock_initial -= $quantite;
            $produit->save();

            // Mettre les variantes à 0 (si nécessaire)
            DB::table('produit_variante')
                ->where('produit_id', $produit->id)
                ->update(['quantite_disponible' => 0]);

            // Mise à jour du stock
            $this->miseAJourStock($produit->id);
        }

        // Supprimer la facture (et potentiellement ses achats si liés par cascade)
        Facture::find($id)->forceDelete();

        return response()->json(['status' => 200]);
    }




    // public function miseAJourStock($id)
    // {
    //     // recuperer les produit de famille bar
    //     // $produit = Produit::withWhereHas('categorie', fn($q) => $q->where('famille', 'bar'))
    //     //     ->orderBy('created_at', 'DESC')->get();
    //     $produit = Produit::find($id);


    //     foreach ($produit as $index => $value) {
    //         // Récupérer toutes les variantes associées au produit
    //         $variantes = DB::table('produit_variante')
    //             ->where('produit_id', $value['id'])
    //             ->get(); // Récupérer toutes les variantes du produit


    //         foreach ($variantes as $variante) {
    //             // Récupérer la quantité disponible actuelle
    //             $quantite_disponible_actuelle = DB::table('produit_variante')
    //                 ->where('produit_id', $value['id'])
    //                 ->where('variante_id', $variante->variante_id)
    //                 ->value('quantite_disponible'); // Récupère uniquement la colonne quantite_disponible

    //             // Calculer la nouvelle quantité disponible
    //             $nouvelle_quantite = $quantite_disponible_actuelle + ($value['stock'] * $variante->quantite);

    //             // Mettre à jour la quantité disponible
    //             DB::table('produit_variante')
    //                 ->where('produit_id', $value['id'])
    //                 ->where('variante_id', $variante->variante_id)
    //                 ->update([
    //                     'quantite_disponible' => $nouvelle_quantite,
    //                 ]);
    //         }
    //     }
    // } //
}
