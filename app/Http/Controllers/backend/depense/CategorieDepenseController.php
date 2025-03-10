<?php

namespace App\Http\Controllers\backend\depense;

use Illuminate\Http\Request;
use App\Models\CategorieDepense;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class CategorieDepenseController extends Controller
{
    //
    //
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $data_categorie_depense = CategorieDepense::OrderBy('position', 'ASC')->get();
            return view('backend.pages.depense.categorie-depense.index', compact('data_categorie_depense'));
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }



    public function store(Request $request)
    {
        try {

            $request->validate([
                'libelle' => 'required',
                'position' => '',
                'statut' => '',
            ]);

            $data_count = CategorieDepense::count();

            $data_categorie_depense = CategorieDepense::firstOrCreate([
                'libelle' => $request['libelle'],
                'statut' => $request['statut'],
                'position' => $data_count + 1,
            ]);

            Alert::success('Operation réussi', 'Success Message');
            return back();
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function position(Request $request, $id)
    {

        try {
            $position = $request['position'];


            CategorieDepense::find($id)->update([
                'position' => $position,
            ]);

            Alert::success('Opération réussi', 'Success Message');
            return back();
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        try {
            //request validation ......
            $request->validate([
                'libelle' => 'required',
                'position' => '',
                'statut' => '',

            ]);

            $data_categorie_depense = CategorieDepense::find($id)->update([
                'libelle' => $request['libelle'],
                'statut' => $request['statut'],
            ]);

            Alert::success('Opération réussi', 'Success Message');
            return back();
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    public function delete($id)
    {

        try {
            CategorieDepense::find($id)->forceDelete();

            //modifier l'ordre des categories
            $data_categorie_depense = CategorieDepense::OrderBy('position', 'ASC')->get();
            foreach ($data_categorie_depense as $key => $value) {
                CategorieDepense::whereId($value['id'])->update([
                    'position' => $key + 1
                ]);
            }
            //

            return response()->json([
                'status' => 200,
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}
