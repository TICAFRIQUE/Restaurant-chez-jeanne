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
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\convertToMajuscule;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class ProduitController extends Controller
{









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
            ->with(['variante', 'categorie'])
            ->get();

        // dd($data_produit->toArray());




        return view('backend.pages.produit.index', compact('data_produit'));
    }

    public function create(Request $request)
    {
        try {


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
            $data_variante = Variante::all();

            // dd($data_categorie->toArray());

            return view('backend.pages.produit.create', compact('data_categorie', 'categorieAll', 'data_unite',  'data_format', 'data_variante'));
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
            $data = $request->validate([
                'nom' => 'required',
                'description' => '',
                'categorie_id' => 'required',
                'stock' => '',
                'stock_alerte' => '',
                'statut' => '',
                'prix' => $categorie->famille == 'bar' ? 'required' : '',
                'variante_id' => $categorie->famille == 'bar' ? 'required' : '',

                'valeur_unite' => '',
                'unite_id' => '',
                'unite_sortie_id' => '',
                'imagePrincipale' => '',
            ]);

            // dd($request->all());

            //concatener la variante et le nom du produit pour le libellé du produit
            $variante_libelle = Variante::find($request['variante_id'])->libelle ?? '';
            $libelle = trim($request['nom']) . ' ' . $variante_libelle; // nom + variante

            // Vérifier si le produit existe déjà
            $existingProduct = Produit::where('nom', $request['nom']) // vérifier uniquement le nom sans variante
                ->where('variante_id', $request['variante_id'])
                // ->where('unite_id', $request['unite_id'])
                ->exists();

            if ($existingProduct) {
                return back()->with('error', 'Le produit existe deja');
            }

            // Générer un SKU unique
            $sku = 'PROD-' . strtoupper(Str::random(8));



            // Créer le produit
            $data_produit = Produit::firstOrCreate([
                'nom' => ConvertToMajuscule::toUpperNoAccent($request['nom']), // sans variante
                'libelle' => ConvertToMajuscule::toUpperNoAccent($libelle), // nom + variante
                'code' => $sku,
                'description' => $request['description'],
                'categorie_id' => $request['categorie_id'],
                'stock_alerte' => 10,
                'type_id' => $principaCat['id'], // Type produit
                'prix' => $categorie->famille == 'bar'  ? $request['prix'] : null,
                'valeur_unite' => $request['valeur_unite'],
                'unite_id' => $request['unite_id'],
                'unite_sortie_id' => $request['unite_sortie_id'],
                'variante_id' => $request['variante_id'],
                'statut' => 'active',
                'user_id' => Auth::id(),
            ]);



            // Si une image principale est présente, l'ajouter
            if ($request->hasFile('imagePrincipale')) {
                $media = $data_produit->addMediaFromRequest('imagePrincipale')->toMediaCollection('ProduitImage');
                // Optimiser l'image après l'ajout
                \Spatie\ImageOptimizer\OptimizerChainFactory::create()->optimize($media->getPath());
            }

            // // Ajouter d'autres images si elles sont présentes
            // if ($request->images) {
            //     foreach ($request->input('images') as $fileData) {
            //         // Décoder l'image base64
            //         $fileData = explode(',', $fileData);
            //         $fileExtension = explode('/', explode(';', $fileData[0])[0])[1];
            //         $decodedFile = base64_decode($fileData[1]);

            //         // Créer un fichier temporaire
            //         $tempFilePath = sys_get_temp_dir() . '/' . uniqid() . '.' . $fileExtension;
            //         file_put_contents($tempFilePath, $decodedFile);

            //         // Ajouter l'image à la collection de médias
            //         $media = $data_produit->addMedia($tempFilePath)->toMediaCollection('galleryProduit');

            //         // Optimiser l'image après l'ajout
            //         \Spatie\ImageOptimizer\OptimizerChainFactory::create()->optimize($media->getPath());
            //     }
            // }

            Alert::success('Succès', 'Produit ajouté avec succès');
            return back();
        } catch (\Throwable $e) {
            // En cas d'exception, retourner un message d'erreur
            return back()->with('error', 'Erreur lors de l\'ajout du produit: ' . $e->getMessage());
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

            $data_produit = Produit::findOrFail($id);
            // dd($data_produit->variante->toArray());


            $data_categorie = Categorie::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')
                ->whereIn('type', ['bar', 'restaurant'])
                ->OrderBy('position', 'ASC')->get();

            // children ctegories of famile select
            $data_categorie_edit = Categorie::where('parent_id', $data_produit->type_id)->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')
                // ->whereNotIn('parent_id' , [null])
                // ->whereIn('type', ['bar', 'restaurant'])
                ->OrderBy('position', 'ASC')->get();


            //recuperer les variante

            $categorieAll = Categorie::all();



            $data_unite = Unite::all();
            $data_format = Format::all();
            $data_variante = Variante::all();

            return view('backend.pages.produit.edit', compact('data_produit', 'data_categorie', 'data_categorie_edit', 'categorieAll',  'data_unite',   'data_format', 'data_variante'));
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }




    public function update(Request $request, $id)
    {
        try {
            $categorie = Categorie::find($request['categorie_id']);
            $principaCat = $categorie->getPrincipalCategory();

            // Validation des données
            $data = $request->validate([
                'nom' => 'required',
                'description' => '',
                'categorie_id' => 'required',
                'stock_alerte' => '',
                'prix' => $categorie->famille == 'bar' ? 'required' : '',
                'variante_id' => $categorie->famille == 'bar' ? 'required' : '',
                'valeur_unite' => '',
                'unite_id' => '',
                'unite_sortie_id' => '',
                'imagePrincipale' => '',
            ]);

            $data_produit = Produit::findOrFail($id);

            // Si la variante change, on modifie le nom, sinon on garde l'ancien
            $variante_libelle = Variante::find($request['variante_id'])->libelle ?? '';
            $libelle = trim($request['nom']) . ' ' . $variante_libelle;

            // Vérifier si le produit existe déjà (hors produit courant)
            $existingProduct = Produit::where('nom', $request['nom']) // vérifier uniquement le nom sans variante
                ->where('id', '!=', $id)
                ->where('variante_id', $request['variante_id'])
                ->exists();

            if ($existingProduct) {
                return back()->with('error', 'Le produit existe deja');
            }

            // Statut
            $statut = $request['statut'] == 'on' ? 'active' : 'desactive';

            // Mise à jour du produit
            $data_produit->update([
                'nom' => ConvertToMajuscule::toUpperNoAccent($request['nom']), // sans variante
                'libelle' => ConvertToMajuscule::toUpperNoAccent($libelle), // nom + variante
                'description' => $request['description'],
                'categorie_id' => $request['categorie_id'],
                'stock_alerte' => 10,
                'type_id' => $principaCat['id'],
                'prix' => $categorie->famille == 'bar' ? $request['prix'] : null,
                'valeur_unite' => $request['valeur_unite'],
                'unite_id' => $request['unite_id'],
                'unite_sortie_id' => $request['unite_sortie_id'],
                'variante_id' => $request['variante_id'],
                'statut' => $statut,
                'user_id' => Auth::id(),
            ]);

            // Image principale
            if ($request->hasFile('imagePrincipale')) {
                $data_produit->clearMediaCollection('ProduitImage');
                $media = $data_produit->addMediaFromRequest('imagePrincipale')->toMediaCollection('ProduitImage');
                \Spatie\ImageOptimizer\OptimizerChainFactory::create()->optimize($media->getPath());
            }

            Alert::success('Succès', 'Produit modifié avec succès');
            return redirect()->route('produit.index');
        } catch (\Throwable $e) {
            return response(['message' => 'Erreur : ' . $e->getMessage()], 500);
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
