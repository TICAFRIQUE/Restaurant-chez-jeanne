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
use Illuminate\Support\Facades\Auth;
use App\Services\convertToMajuscule;


class PlatController extends Controller
{
    //
    public function index()
    {

        $data_plat = Produit::whereHas('categorie', function ($query) {
            $query->where('famille', 'menu');
        })
            ->alphabetique()
            ->get();
        // dd($data_plat->toArray());
        return view('backend.pages.menu.produit.index', compact('data_plat'));
    }

    public function create(Request $request)
    {
        try {

            $data_categorie = Categorie::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')
                ->whereIn('famille', ['menu'])
                ->OrderBy('position', 'ASC')->get();

            $data_categorie_menu = CategorieMenu::get();


            // dd($data_categorie_menu->toArray());

            return view('backend.pages.menu.produit.create', compact('data_categorie', 'data_categorie_menu'));
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    public function store(Request $request)
    {
        try {
            //request validation
            $request->validate([
                'nom' => 'required',
                'description' => '',
                'categorie' => 'required',
                'categorie_menu_id' => '',
                'prix' => '',
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

            //verifier si le plat existe deja
            $platExist = Produit::where('categorie_id', $request['categorie'])
                ->where('type_id', $principaCat['id'])
                ->where('nom', ConvertToMajuscule::toUpperNoAccent($request['nom']))->first();
            if ($platExist) {
                return back()->with('error', 'Le plat existe déjà.');
            }

            $sku = 'PM-' . strtoupper(Str::random(8));
            $plat = Produit::firstOrCreate([
                'nom' => ConvertToMajuscule::toUpperNoAccent($request['nom']),
                'code' =>  $sku,
                'description' => $request['description'],
                'categorie_id' => $request['categorie'],
                'categorie_menu_id' => $request['categorie_menu_id'],
                'prix' => $request['prix'],
                'type_id' =>   $principaCat['id'], // type produit
                'statut' => $statut,
                'user_id' => Auth::id(),
            ]);

            if (request()->hasFile('imagePrincipale')) {
                $plat->addMediaFromRequest('imagePrincipale')->toMediaCollection('ProduitImage');
            }




            // si categorie_menu_id != null on ajoute comme un plat de menu du jour
            if ($request['categorie_menu_id'] != null) {

                $sku = 'PM-' . strtoupper(Str::random(8));
                $plat = Plat::firstOrCreate([
                    'nom' => $request['nom'],
                    'code' =>  $sku,
                    'description' => $request['description'],
                    'categorie_menu_id' => $request['categorie_menu_id'],
                    'prix' => $request['prix'],
                    'statut' => $statut,
                    'user_id' => Auth::id(),
                ]);
            }




            return redirect()->route('plat.index')->with('success', 'Le plat a été créé avec succès.');
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

            $data_categorie_menu = CategorieMenu::get();


            $data_plat = Produit::find($id);

            return view('backend.pages.menu.produit.edit', compact('data_plat', 'data_categorie','data_categorie_menu'));
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
                'categorie_menu_id' => '',

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
            //verifier si le plat existe deja
            $platExist = Produit::where('categorie_id', $request['categorie'])
                ->where('type_id', $principaCat['id'])
                ->where('nom', ConvertToMajuscule::toUpperNoAccent($request['nom']))->first();
            if ($platExist && $platExist->id != $id) {
                return back()->with('error', 'Le plat existe déjà.');
            }

            $plat = tap(Produit::find($id))->update([
                'nom' => ConvertToMajuscule::toUpperNoAccent($request['nom']),
                'description' => $request['description'],
                'categorie_id' => $request['categorie'],
                'categorie_menu_id' => $request['categorie_menu_id'],
                'prix' => $request['prix'],
                'type_id' =>   $principaCat['id'], // type produit
                'statut' => $statut,
                'user_id' => Auth::id(),
            ]);


            //on verifie si le produit se trouve dans la table plat si oui on le met a jour si non on le cree
            $platExist = Plat::where('nom', $request['nom'])->first();
            if ($platExist) {
                //on met a jour le plat
                tap(Plat::find($platExist->id))->update([
                    'nom' => $request['nom'],
                    'description' => $request['description'],
                    'categorie_menu_id' => $request['categorie_menu_id'],
                    'prix' => $request['prix'],
                    'statut' => $statut,
                    'user_id' => Auth::id(),
                ]);
            } else {
                // si categorie_menu_id != null on ajoute comme un plat de menu du jour
                if ($request['categorie_menu_id'] != null) {

                    $sku = 'PM-' . strtoupper(Str::random(8));
                    $plat = Plat::firstOrCreate([
                        'nom' => $request['nom'],
                        'code' =>  $sku,
                        'description' => $request['description'],
                        'categorie_menu_id' => $request['categorie_menu_id'],
                        'prix' => $request['prix'],
                        'statut' => $statut,
                        'user_id' => Auth::id(),
                    ]);
                }
            }






            if (request()->hasFile('imagePrincipale')) {
                $plat->clearMediaCollection('ProduitImage');
                $plat->addMediaFromRequest('imagePrincipale')->toMediaCollection('ProduitImage');
            }


            return back()->with('success', 'Le plat a été mis à jour avec succès.');
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
