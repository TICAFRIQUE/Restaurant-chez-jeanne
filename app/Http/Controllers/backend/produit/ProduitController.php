<?php

namespace App\Http\Controllers\backend\produit;

use App\Models\Unite;
use App\Models\Format;
use App\Models\Magasin;
use App\Models\Produit;
use App\Models\Variante;
use App\Models\Categorie;
use App\Models\Fournisseur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProduitController extends Controller
{


    /**
     * Calcule et met à jour la quantité disponible pour chaque variante des produits de la famille "bar".
     *
     * Cette fonction récupère tous les produits appartenant à la catégorie "bar" et met à jour 
     * la quantité disponible de leurs variantes respectives en ajoutant le stock du produit multiplié 
     * par la quantité de la variante.
     *
     * @param Request $request L'objet de requête HTTP.
     *
     * @return void
     */

    // public function calculeQteVarianteProduit()
    // {
    //     // recuperer les produit de famille bar

    //     $data_produit_bar = Produit::withWhereHas('categorie', fn($q) => $q->where('famille', 'bar'))
    //         ->orderBy('created_at', 'DESC')->get();


    //     foreach ($data_produit_bar as $index => $value) {
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
    // }


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


    public function calculeQteVarianteProduit()
    {
        $produits = Produit::withWhereHas('categorie', fn($q) => $q->where('famille', 'bar'))
            ->orderByDesc('created_at')
            ->get();

        foreach ($produits as $produit) {
            // Réinitialiser les quantités à 0 en une seule requête
            DB::table('produit_variante')
                ->where('produit_id', $produit->id)
                ->update(['quantite_disponible' => 0]);

            // Préparation des mises à jour en une seule passe
            $variantes = DB::table('produit_variante')
                ->where('produit_id', $produit->id)
                ->get();

            foreach ($variantes as $variante) {
                $quantite = $produit->stock * $variante->quantite;

                // Éviter de faire plusieurs appels au même WHERE
                DB::table('produit_variante')
                    ->where([
                        ['produit_id', '=', $produit->id],
                        ['variante_id', '=', $variante->variante_id],
                    ])
                    ->update(['quantite_disponible' => $quantite]);
            }
        }
    }





    //
    public function index(Request $request)
    {
        $categorie = Categorie::whereIn('type', ['restaurant', 'bar'])->get();

        // filtrer les produits selon le type
        $filter = request('filter');

        $data_produit = Produit::withWhereHas('typeProduit', fn($q) => $q->whereIn('type', ['restaurant', 'bar']))
            ->when($filter, function ($query) use ($filter) {
                return $query->withWhereHas('typeProduit', fn($q) => $q->where('type', $filter));
            })->orderBy('created_at', 'DESC')
            ->with(['variantes','categorie'])
            ->get();
        // $data_produit = Produit::withWhereHas('typeProduit', fn($q) => $q->whereIn('type', ['restaurant', 'bar']))
        //     ->orderBy('created_at', 'DESC')->get();

        // dd($data_produit->toArray());


        // Appeler la fonction calculeQteVarianteProduit
        $this->calculeQteVarianteProduit();

        return view('backend.pages.produit.index', compact('data_produit'));
    }

    public function create(Request $request)
    {
        try {

            // $data_categorie = Categorie::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')
            //     ->whereIn('type', ['bar', 'restaurant'])
            //     ->OrderBy('position', 'ASC')->get();


            // $categorieAll = Categorie::all();


            $data_categorie = Categorie::whereNull('parent_id') // Catégories principales
                ->with('children', fn($q) => $q->orderBy('position', 'ASC')) // Récupérer les sous-catégories avec tri
                ->withCount('children') // Compter le nombre d'enfants pour chaque catégorie
                ->whereIn('type', ['bar', 'restaurant']) // Filtrer par type 'bar' ou 'restaurant'
                ->orderBy('position', 'ASC') // Trier les catégories principales par position
                ->get();

            // Récupérer toutes les catégories (avec leurs enfants, s'ils existent)
            $categorieAll = Categorie::with('children')->get();


            $data_unite = Unite::all();
            $data_format = Format::all();
            $data_magasin = Magasin::all();
            $data_variante = Variante::whereNotIn('slug', ['bouteille'])->get();


            // dd($data_categorie->toArray());

            return view('backend.pages.produit.create', compact('data_categorie', 'categorieAll', 'data_unite', 'data_magasin', 'data_format', 'data_variante'));
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function categoryFilter(Request $request)
    {

        $data_categorie = Categorie::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')
            ->whereIn('type', ['bar', 'restaurant'])
            ->OrderBy('position', 'ASC')->get();
    }


    public function store(Request $request)
    {
        try {

            // dd($request->all());
            // Récupérer la catégorie principale de la catégorie demandée
            $categorie = Categorie::find($request['categorie_id']);
            $principaCat = $categorie->getPrincipalCategory();

            // Validation des données de la requête
            $validator = Validator::make($request->all(), [
                'nom' => 'required',
                'description' => '',
                'categorie_id' => 'required',
                'stock' => '',
                'stock_alerte' => 'required',
                'statut' => '',
                'prix' => $categorie->famille == 'bar' ? 'required' : '',
                'valeur_unite' => '',
                'unite_id' => '',
                'unite_sortie_id' => $categorie->famille == 'restaurant' ? 'required' : '',
                'imagePrincipale' => $categorie->famille == 'bar' ? 'required' : '',

                // gestion des variantes
                'variantes.*.libelle' => $categorie->famille == 'bar' ? 'required' : '',
                'variantes.*.prix' => $categorie->famille == 'bar' ? 'required' : '',
                'variantes.*.quantite' => $categorie->famille == 'bar' ? 'required' : '',

            ]);

            // dd($request->all());
            // Vérifier si la validation échoue
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422); // 422 Unprocessable Entity
            }

            // Vérifier si le produit existe déjà
            $existingProduct = Produit::where('nom', $request['nom'])
                ->where('valeur_unite', $request['valeur_unite'])
                ->where('unite_id', $request['unite_id'])
                ->exists();

            if ($existingProduct) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce produit existe déjà',
                ], 409); // 409 Conflict
            }

            // Générer un SKU unique
            $sku = 'PROD-' . strtoupper(Str::random(8));
            // Créer le produit
            $data_produit = Produit::create([
                'nom' => $request['nom'],
                'code' => $sku,
                'description' => $request['description'],
                'categorie_id' => $request['categorie_id'],
                'stock_alerte' => $request['stock_alerte'],
                'type_id' => $principaCat['id'], // Type produit
                'prix' => $categorie->famille == 'bar'  ? $request['prix'] : null,
                'valeur_unite' => $request['valeur_unite'],
                'unite_id' => $request['unite_id'],
                'unite_sortie_id' => $categorie->famille == 'restaurant'  ? $request['unite_sortie_id'] : Unite::whereLibelle('Bouteille')->first()->id,
                'statut' => 'active',
                'user_id' => Auth::id(),
            ]);


            // Erengistrer les variantes dans la table pivot  ------*variantes represente les unite de vente associer au produit
            // famille est bar
            // dd($categorie->famille);
            if ($categorie->famille == 'bar') {
                if ($request->variantes) {
                    foreach ($request->variantes as  $variante) {
                        $data_produit->variantes()->attach(
                            $variante['libelle'],
                            [
                                'quantite' => $variante['quantite'],
                                'prix' => $variante['prix'],
                                'total' => $variante['quantite'] * $variante['prix']
                            ]
                        );
                    }
                }
            }

            // Si une image principale est présente, l'ajouter
            if ($request->hasFile('imagePrincipale')) {
                $media = $data_produit->addMediaFromRequest('imagePrincipale')->toMediaCollection('ProduitImage');
                // Optimiser l'image après l'ajout
                \Spatie\ImageOptimizer\OptimizerChainFactory::create()->optimize($media->getPath());
            }

            // Ajouter d'autres images si elles sont présentes
            if ($request->images) {
                foreach ($request->input('images') as $fileData) {
                    // Décoder l'image base64
                    $fileData = explode(',', $fileData);
                    $fileExtension = explode('/', explode(';', $fileData[0])[0])[1];
                    $decodedFile = base64_decode($fileData[1]);

                    // Créer un fichier temporaire
                    $tempFilePath = sys_get_temp_dir() . '/' . uniqid() . '.' . $fileExtension;
                    file_put_contents($tempFilePath, $decodedFile);

                    // Ajouter l'image à la collection de médias
                    $media = $data_produit->addMedia($tempFilePath)->toMediaCollection('galleryProduit');

                    // Optimiser l'image après l'ajout
                    \Spatie\ImageOptimizer\OptimizerChainFactory::create()->optimize($media->getPath());
                }
            }

            // Réponse en cas de succès
            return response()->json([
                'success' => true,
                'message' => 'Produit créé avec succès',
                'product' => $data_produit
            ], 201);
        } catch (\Throwable $e) {
            // En cas d'exception, retourner un message d'erreur
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }


    public function show($id)
    {
        try {
            $data_produit = Produit::find($id);
            return view('backend.pages.produit.show', compact('data_produit'));
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    public function edit($id)
    {
        try {

            // $data_produit = Produit::find($id);

            $data_produit = Produit::with(['variantes' => function ($query) {
                $query->orderBy('produit_variante.quantite', 'asc'); // Trier par quantité croissante
            }])->find($id);
            // dd($data_produit->variantes->toArray());


            $data_categorie = Categorie::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')
                ->whereIn('type', ['bar', 'restaurant'])
                ->OrderBy('position', 'ASC')->get();

            // children ctegories of famile select
            $data_categorie_edit = Categorie::where('parent_id', $data_produit->type_id)->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')
                // ->whereNotIn('parent_id' , [null])
                // ->whereIn('type', ['bar', 'restaurant'])
                ->OrderBy('position', 'ASC')->get();


            // dd($data_categorie->toArray());
            //recuperer les variante

            $categorieAll = Categorie::all();



            $data_unite = Unite::all();
            $data_format = Format::all();
            $data_magasin = Magasin::all();
            $data_variante = Variante::all();
            // $data_variante = Variante::whereNotIn('slug', ['bouteille'])->get();





            //get Image from database
            $galleryProduit = [];

            foreach ($data_produit->getMedia('galleryProduit') as $value) {
                // Read the file content
                $fileContent = file_get_contents($value->getPath());

                // Encode the file content to base64
                $base64File = base64_encode($fileContent);
                array_push($galleryProduit, $base64File);
            }

            // dd($galleryProduit);

            $id = $id;
            return view('backend.pages.produit.edit', compact('data_produit', 'data_categorie', 'data_categorie_edit', 'categorieAll',  'data_unite', 'data_magasin', 'galleryProduit', 'id', 'data_format', 'data_variante'));
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


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

    public function update(Request $request, $id)
    {
        try {

            //get principal category of categorie request
            $categorie = Categorie::find($request['categorie_id']);
            $principaCat =  $categorie->getPrincipalCategory();


            // dd($request->all());
            //request validation
            $request->validate([
                'nom' => 'required',
                'description' => '',
                'categorie_id' => 'required',
                'stock' => '',
                'stock_alerte' => 'required',
                // 'prix' => $categorie->famille == 'bar' ? 'required' : '',

                // 'magasin' => '',

                'valeur_unite' => '',
                'unite_id' => '',
                // 'format_id' => '',
                // 'valeur_format' => '',
                'unite_sortie_id' => $categorie->famille == 'restaurant' ? 'required' : '',
                'imagePrincipale' => '',

                // gestion des variantes
                'variantes.*.libelle' => $categorie->famille == 'bar' ? 'required' : '',
                'variantes.*.prix' => $categorie->famille == 'bar' ? 'required' : '',
                'variantes.*.quantite' => $categorie->famille == 'bar' ? 'required' : '',
            ]);

            // active  :  desactive le produit
            $statut = '';
            if ($request['statut'] == 'on') {
                $statut = 'active';
            } else {
                $statut = 'desactive';
            }


            $data_produit = tap(Produit::find($id))->update([
                'nom' => $request['nom'],
                'description' => $request['description'],
                'categorie_id' => $request['categorie_id'],
                'stock_alerte' => $request['stock_alerte'],
                'type_id' =>   $principaCat['id'], // type produit
                'prix' => $categorie->famille == 'bar'  ? $request->variantes[0]['prix'] :   null,
                'valeur_unite' => $request['valeur_unite'],
                'unite_id' => $request['unite_id'],
                // 'format_id' => $request['format_id'],
                // 'valeur_format' => $request['valeur_format'],
                'unite_sortie_id' => $categorie->famille == 'restaurant'  ? $request['unite_sortie_id'] : Unite::whereLibelle('Bouteille')->first()->id,
                'statut' => $statut,
                'user_id' => Auth::id(),
            ]);

            //supprimer les element pivot lié au produit
            DB::table('produit_unite')->where('produit_id', $id)->delete();

            // Erengistrer les variantes dans la table pivot
            if ($request->variantes) {
                // supprimer les element pivot lié au produit
                DB::table('produit_variante')->where('produit_id', $id)->delete(); // supprimer les variantes existantes

                // ajouter les nouvelles variantes
                foreach ($request->variantes as  $variante) {
                    $data_produit->variantes()->attach(
                        $variante['libelle'],
                        [
                            'quantite' => $variante['quantite'],
                            'prix' => $variante['prix'],
                            'total' => $variante['quantite'] * $variante['prix']

                        ]
                    );
                }
            }

            // mise a jour du stock disponible des variantes
            $this->miseAJourStock($id);

            // recuperer le prix du produit de la variante bouteille dans la table pivot
            // $variante = Variante::where('slug', 'bouteille')->first();
            // $prix_bouteille = DB::table('produit_unite')->where('produit_id', $id)->where('variante_id', $variante->id)->first();
            // modifier le prix du produit recuperer par la variante bouteille
            // Produit::where('id', $id)->update(['prix' => $prix_bouteille->prix]);



            if (request()->hasFile('imagePrincipale')) {
                $data_produit->clearMediaCollection('ProduitImage');
                $media = $data_produit->addMediaFromRequest('imagePrincipale')->toMediaCollection('ProduitImage');
                \Spatie\ImageOptimizer\OptimizerChainFactory::create()->optimize($media->getPath());
            }



            if ($request->images) {
                $data_produit->clearMediaCollection('galleryProduit');

                foreach ($request->input('images') as $fileData) {
                    // Decode base64 file
                    $fileData = explode(',', $fileData);
                    $fileExtension = explode('/', explode(';', $fileData[0])[0])[1];
                    $decodedFile = base64_decode($fileData[1]);

                    // Create a temporary file
                    $tempFilePath = sys_get_temp_dir() . '/' . uniqid() . '.' . $fileExtension;
                    file_put_contents($tempFilePath, $decodedFile);

                    // Add file to media library
                    // Add file to media library
                    $media = $data_produit->addMedia($tempFilePath)->toMediaCollection('galleryProduit');

                    // Optimiser l'image après l'ajout
                    \Spatie\ImageOptimizer\OptimizerChainFactory::create()->optimize($media->getPath());
                }
            }



            return response([
                'message' => 'operation reussi'
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    public function delete($id)
    {
        Produit::find($id)->forceDelete();
        return response()->json([
            'status' => 200,
        ]);
    }
}
