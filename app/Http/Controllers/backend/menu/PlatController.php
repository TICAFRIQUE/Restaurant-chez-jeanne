<?php

namespace App\Http\Controllers\backend\menu;

use App\Models\Unite;
use App\Models\Format;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Fournisseur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PlatController extends Controller
{
    //
    public function index()
    {
        $categorie = Categorie::where('famille', 'menu')->first();

        $data_plat = Produit::where('type_id',  $categorie->id)->get();
        // dd($data_plat->toArray());
        return view('backend.pages.menu.produit.index', compact('data_plat'));
    }

    public function create(Request $request)
    {
        try {

            $data_categorie = Categorie::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')
                ->whereIn('famille', ['menu'])
                ->OrderBy('position', 'ASC')->get();

            // dd($plat->toArray());

            return view('backend.pages.menu.produit.create', compact('data_categorie'));
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    public function store(Request $request)
    {
        try {
            //request validation
            $request->validate([
                'nom' => 'required|unique:produits',
                'description' => '',
                'categorie' => 'required',
                'prix' => 'required',
                'statut' => '',
            ]);

            //statut stock libelle active ? desactive
            $statut = '';
            if ($request['statut'] == 'on') {
                $statut = 'active';
            } else {
                $statut = 'desactive';
            }

            //get principal category of categorie request
            $principaCat = Categorie::find($request['categorie']);
            $principaCat =  $principaCat->getPrincipalCategory();

            $sku = 'PM-' . strtoupper(Str::random(8));
            $plat = Produit::firstOrCreate([
                'nom' => $request['nom'],
                'code' =>  $sku,
                'description' => $request['description'],
                'categorie_id' => $request['categorie'],
                'prix' => $request['prix'],
                'type_id' =>   $principaCat['id'], // type produit
                'statut' => $statut,
                'user_id' => Auth::id(),
            ]);

            if (request()->hasFile('imagePrincipale')) {
                $plat->addMediaFromRequest('imagePrincipale')->toMediaCollection('ProduitImage');
            }


            if ($request->images) {

                foreach ($request->input('images') as $fileData) {
                    // Decode base64 file
                    $fileData = explode(',', $fileData);
                    $fileExtension = explode('/', explode(';', $fileData[0])[0])[1];
                    $decodedFile = base64_decode($fileData[1]);

                    // Create a temporary file
                    $tempFilePath = sys_get_temp_dir() . '/' . uniqid() . '.' . $fileExtension;
                    file_put_contents($tempFilePath, $decodedFile);

                    // Add file to media library
                    $plat->addMedia($tempFilePath)->toMediaCollection('galleryProduit');

                    // // Delete the temporary file
                    // unlink($tempFilePath);
                }
            }

            return response([
                'message' => 'operation reussi'
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    public function show($id)
    {
        try {
            $plat = Produit::find($id);
            return view('backend.pages.produit.show', compact('plat'));
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    public function edit($id)
    {
        try {

            $data_categorie = Categorie::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')
                ->whereIn('famille', ['menu'])
                ->OrderBy('position', 'ASC')->get();

            $data_plat = Produit::find($id);

            //get Image from database
            $galleryProduit = [];

            foreach ($data_plat->getMedia('galleryProduit') as $value) {
                // Read the file content
                $fileContent = file_get_contents($value->getPath());

                // Encode the file content to base64
                $base64File = base64_encode($fileContent);
                array_push($galleryProduit, $base64File);
            }

            // dd($galleryProduit);

            $id = $id;
            return view('backend.pages.menu.produit.edit', compact('data_plat', 'data_categorie', 'galleryProduit', 'id'));
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {


            //request validation
            $request->validate([
                'nom' => 'required',
                'description' => '',
                'categorie' => 'required',
                'prix' => 'required',
                'statut' => '',
            ]);

            //statut stock libelle active ? desactive
            $statut = '';
            if ($request['statut'] == 'on') {
                $statut = 'active';
            } else {
                $statut = 'desactive';
            }

            //get principal category of categorie request
            $principaCat = Categorie::find($request['categorie']);
            $principaCat =  $principaCat->getPrincipalCategory();

            $plat = tap(Produit::find($id))->update([
                'nom' => $request['nom'],
                'description' => $request['description'],
                'categorie_id' => $request['categorie'],
                'prix' => $request['prix'],
                'type_id' =>   $principaCat['id'], // type produit
                'statut' => $statut,
                'user_id' => Auth::id(),
            ]);


            if (request()->hasFile('imagePrincipale')) {
                $plat->clearMediaCollection('ProduitImage');
                $plat->addMediaFromRequest('imagePrincipale')->toMediaCollection('ProduitImage');
            }


            if ($request->images) {
                $plat->clearMediaCollection('galleryProduit');

                foreach ($request->input('images') as $fileData) {
                    // Decode base64 file
                    $fileData = explode(',', $fileData);
                    $fileExtension = explode('/', explode(';', $fileData[0])[0])[1];
                    $decodedFile = base64_decode($fileData[1]);

                    // Create a temporary file
                    $tempFilePath = sys_get_temp_dir() . '/' . uniqid() . '.' . $fileExtension;
                    file_put_contents($tempFilePath, $decodedFile);

                    // Add file to media library
                    $plat->addMedia($tempFilePath)->toMediaCollection('galleryProduit');

                    // // Delete the temporary file
                    // unlink($tempFilePath);
                }
            }



            return response([
                'message' => 'operation reussi',
                'data' => $principaCat


            ], 200);
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
