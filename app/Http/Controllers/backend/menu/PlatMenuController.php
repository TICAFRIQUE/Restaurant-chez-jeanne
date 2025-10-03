<?php

namespace App\Http\Controllers\backend\menu;

use App\Models\Plat;
use App\Models\Unite;
use App\Models\Format;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Fournisseur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CategorieMenu;
use App\Http\Controllers\Controller;
use App\Services\convertToMajuscule;
use Illuminate\Support\Facades\Auth;

class PlatMenuController extends Controller
{
    //
    public function index()
    {
        try {

            $data_plat = Plat::with('categorieMenu')
                ->orderBy('created_at', 'DESC')
                ->get();
            // dd($data_plat->toArray());
            return view('backend.pages.menu.plat-menu.index', compact('data_plat'));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function create(Request $request)
    {
        try {
            $data_categorie = CategorieMenu::orderBy('position', 'ASC')->get();
            // dd($data_categorie_menu->toArray());

            return view('backend.pages.menu.plat-menu.create', compact('data_categorie'));
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    public function store(Request $request)
    {
        try {

            // dd($request->all());
            //request validation
            $request->validate(
                [
                    'nom' => 'required',
                    'description' => '',
                    'categorie' => 'required',
                    'prix' => 'required',
                    'statut' => '',
                ],
                [
                    'nom.required' => 'Le nom du plat est obligatoire.',
                    'nom.unique' => 'Le nom du plat existe déjà.',
                    'categorie.required' => 'La catégorie est obligatoire.',
                    'prix.numeric' => 'Le prix doit être un nombre.',
                    'prix.required' => 'Le prix est obligatoire.',
                ]

            );


            $statut = $request['statut'] == 'on' ? 'active' : 'desactive';


            //get principal category of categorie request

            $sku = 'PM-' . strtoupper(Str::random(8));
            $plat = Plat::firstOrCreate(
                ['nom' => ConvertToMajuscule::toUpperNoAccent($request['nom'])],
                [
                    'code' =>  $sku,
                    'description' => $request['description'],
                    'categorie_menu_id' => $request['categorie'],
                    'prix' => $request['prix'],
                    'statut' => $statut,
                    'user_id' => Auth::id(),
                ]
            );

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
                'message' => 'operation reussi',
                'plat' => $plat,
                'exist' => false, // le plat n'existe pas
                'success' => true,


            ], 200);
        } catch (\Throwable $e) {
            return response([
                'message' => $e->getMessage(),
            ], 500);
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



            $data_categorie = CategorieMenu::orderBy('position', 'ASC')->get();


            $data_plat = Plat::find($id);

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
            return view('backend.pages.menu.plat-menu.edit', compact('data_plat', 'data_categorie', 'galleryProduit', 'id',));
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
            $statut = $request['statut'] == 'on' ? 'active' : 'desactive';



            $plat = tap(Plat::find($id))->update([
                'nom' => ConvertToMajuscule::toUpperNoAccent($request['nom']),
                'description' => $request['description'],
                'categorie_menu_id' => $request['categorie'],
                'prix' => $request['prix'],
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
                'data' => $plat

            ], 200);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    public function delete($id)
    {
        Plat::find($id)->forceDelete();
        return response()->json([
            'status' => 200,
        ]);
    }
}
