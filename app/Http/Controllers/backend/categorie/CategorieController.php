<?php

namespace App\Http\Controllers\backend\categorie;

use App\Models\Categorie;
use App\Models\TypeProduit;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\convertToMajuscule;
use RealRashid\SweetAlert\Facades\Alert;

class CategorieController extends Controller
{
    //

    public function create()
    {


        //create Categorie principal
        $data_categorie = Categorie::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')->OrderBy('position', 'ASC')->get();

        //type produit
        // $data_type_produit = TypeProduit::all();
        // dd($data_categorie->toArray());
        return view('backend.pages.categorie.create', compact('data_categorie'));
    }



    public function store(Request $request)
    {

        try {
            //request validation ......
            $request->validate([
                'name' => 'required|string|max:255',
                'type_produit' => 'nullable|exists:categories,id',
            ]);


            $famille = null;
            $parent_id = $request['type_produit'] ?? null;
            if ($parent_id) {
                $parent = Categorie::find($parent_id);
                $famille = $parent ? $parent->famille : null;
            }

            // Vérification de l'unicité sur name + parent_id + famille
            $exists = Categorie::where('name', $request['name'])
                ->where('parent_id', $parent_id)
                ->where('famille', $famille)
                ->exists();

            if ($exists) {
                return back()->withErrors(['name' => 'Ce nom existe déjà pour cette famille et ce parent.'])->withInput();
            }

            $data_count = Categorie::where('parent_id', $parent_id)->count();

            $data_categorie = Categorie::create([
                'name' => ConvertToMajuscule::toUpperNoAccent($request['name']),
                'status' => $request['status'],
                'url' => $request['url'],
                'parent_id' => $parent_id,
                'famille' => $famille,
                'position' => $data_count + 1,
            ]);

            Alert::success('Operation réussi', 'Success Message');

            return back();
        } catch (\Throwable $e) {
            Alert::error('Erreur', $e->getMessage());
            return back()->withInput();
        }
    }

    /**page view for add item */
    public function addSubCat(Request $request, $id)
    {
        try {
            //List Categorie
            $data_categorie = Categorie::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')->OrderBy('position', 'ASC')->get();

            $data_categorie_parent = Categorie::findOrFail($id);
            // dd( $data_categorie_parent->toArray());

            return view('backend.pages.categorie.categorie-item',  compact('data_categorie', 'data_categorie_parent'));
        } catch (\Throwable $e) {
            Alert::success($e->getMessage(), 'Une erreur s\'est produite');
        }
    }


    public function addSubCatStore(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'categorie_parent' => 'required|exists:categories,id',
            ]);

            $categorie_parent = Categorie::findOrFail($request['categorie_parent']);

            // Vérification de l'unicité sur name + parent_id + famille
            $exists = Categorie::where('name', $request['name'])
                ->where('parent_id', $categorie_parent->id)
                ->where('famille', $categorie_parent->famille)
                ->exists();

            if ($exists) {
                Alert::error('Opération échouée', 'Ce nom existe déjà pour cette famille et ce parent.');
                return back()->withInput();
            }

            $data_count = Categorie::where('parent_id', $categorie_parent->id)->count();

            $data_categorie = Categorie::create([
                'parent_id' => $categorie_parent->id,
                'name' => ConvertToMajuscule::toUpperNoAccent($request['name']),
                'famille' => $categorie_parent->famille,
                'status' => $request['status'] ?? 'active',
                'url' => $request['url'] ?? null,
                'position' => $data_count + 1,
            ]);

            Alert::success('Opération réussie', 'La sous-catégorie a été ajoutée avec succès.');
            return redirect()->route('categorie.create');
        } catch (\Throwable $e) {
            Alert::error('Erreur', $e->getMessage());
            return back()->withInput();
        }
    }


    public function edit(Request $request, $id)
    {
        try {
            //List Categorie
            $data_categorie = Categorie::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')->OrderBy('position', 'ASC')->get();

            $data_categorie_edit = Categorie::find($id);

            $data_count = Categorie::where('parent_id', $data_categorie_edit['parent_id'])->count();
            // dd($data_count);

            return view('backend.pages.categorie.categorie-edit',  compact('data_categorie', 'data_categorie_edit', 'data_count'));
        } catch (\Throwable $e) {
            Alert::success($e->getMessage(), 'Une erreur s\'est produite');
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $categorie = Categorie::findOrFail($id);

            // Vérification de l'unicité sur name + parent_id + famille, hors catégorie courante
            $exists = Categorie::where('name', $request['name'])
                ->where('parent_id', $categorie->parent_id)
                ->where('famille', $categorie->famille)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                Alert::error('Opération échouée', 'Ce nom existe déjà pour cette famille et ce parent.');
                return back()->withInput();
            }

            $categorie->update([
                'name' => ConvertToMajuscule::toUpperNoAccent($request['name']),
                'status' => $request['status'],
                'url' => $request['url'],
                'position' => $request['position'],
            ]);

            Alert::success('Opération réussie', 'La catégorie a été modifiée avec succès.');
            return redirect()->route('categorie.create');
        } catch (\Throwable $e) {
            Alert::error('Erreur', $e->getMessage());
            return back()->withInput();
        }
    }


    public function delete($id)
    {
        try {
            //reeorganiser l'ordre
            $data_categorie_edit = Categorie::find($id);
            $data_categorie = Categorie::where('parent_id', $data_categorie_edit['parent_id'])->get();
            foreach ($data_categorie as $key => $value) {
                Categorie::whereId($value['id'])->update([
                    'position' => $key + 1
                ]);
            }
            //supprimer
            $categorie = Categorie::find($id)->forceDelete();

            // DB::table('categories')->whereId($id)->delete();

            //delete categorie

            return response()->json([
                'status' => 200,
            ]);
        } catch (\Throwable $e) {
            Alert::success($e->getMessage(), 'Une erreur s\'est produite');
        }
    }
}
