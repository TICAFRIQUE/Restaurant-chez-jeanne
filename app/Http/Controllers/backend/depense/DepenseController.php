<?php

namespace App\Http\Controllers\backend\depense;

use Carbon\Carbon;
use App\Models\Depense;
use Illuminate\Http\Request;
use App\Models\LibelleDepense;
use App\Models\CategorieDepense;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class DepenseController extends Controller
{
    //
    //
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        try {
            $query = Depense::with(['libelle_depense', 'categorie_depense', 'user'])->OrderBy('date_depense', 'DESC');
            // $data_libelle_depense = LibelleDepense::OrderBy('created_at', 'ASC')->get();
            // $categorie_depense = CategorieDepense::whereNotIn('slug', ['achats'])->get();


            // Vérifier si aucune période ou date spécifique n'a été fournie
            if (!$request->filled('periode') && !$request->filled('date_debut') && !$request->filled('date_fin')) {
                $query->whereMonth('date_depense', Carbon::now()->month)
                    ->whereYear('date_depense', Carbon::now()->year);
            }

            // sinon on applique le filtre des date et categorie
            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');
            $categorie = $request->input('categorie');
            $periode = $request->input('periode');


            // Formatage des dates
            $dateDebut = $request->filled('date_debut') ? Carbon::parse($dateDebut)->format('Y-m-d') : null;
            $dateFin = $request->filled('date_fin') ? Carbon::parse($dateFin)->format('Y-m-d') : null;

            // Application des filtres de date
            if ($dateDebut && $dateFin) {
                $query->whereBetween('date_depense', [$dateDebut, $dateFin]);
            } elseif ($dateDebut) {
                $query->where('date_depense', '>=', $dateDebut);
            } elseif ($dateFin) {
                $query->where('date_depense', '<=', $dateFin);
            }

            // Application du filtre de statut
            if ($request->filled('categorie')) {
                $query->where('categorie_depense_id', $categorie);
            }

            // Application du filtre de periode
            // periode=> jour, semaine, mois, année
            if ($request->filled('periode')) {
                $periode = $request->periode; // Ajout de cette ligne pour récupérer la période

                if ($periode == 'jour') {
                    $query->whereDate('date_depense', Carbon::today());
                } elseif ($periode == 'semaine') {
                    $query->whereBetween('date_depense', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($periode == 'mois') {
                    $query->whereMonth('date_depense', Carbon::now()->month)
                        ->whereYear('date_depense', Carbon::now()->year); // Ajout pour éviter d'avoir des résultats de plusieurs années
                } elseif ($periode == 'annee') {
                    $query->whereYear('date_depense', Carbon::now()->year);
                }
            }

            $data_depense = $query->get();



            // dd($categorie_depense->toArray());
            return view('backend.pages.depense.index', compact('data_depense',));
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }


    public function getList(Request $request)
    {
        //
        try {
            $query = Depense::with(['libelle_depense', 'categorie_depense', 'user'])->OrderBy('created_at', 'DESC');
            $data_libelle_depense = LibelleDepense::OrderBy('created_at', 'ASC')->get();
            $categorie_depense = CategorieDepense::whereNotIn('slug', ['achats'])->get();

            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');
            $categorie = $request->input('categorie');
            $periode = $request->input('periode');


            // Formatage des dates
            $dateDebut = $request->filled('date_debut') ? Carbon::parse($dateDebut)->format('Y-m-d') : null;
            $dateFin = $request->filled('date_fin') ? Carbon::parse($dateFin)->format('Y-m-d') : null;

            // Application des filtres de date
            if ($dateDebut && $dateFin) {
                $query->whereBetween('created_at', [$dateDebut, $dateFin]);
            } elseif ($dateDebut) {
                $query->where('created_at', '>=', $dateDebut);
            } elseif ($dateFin) {
                $query->where('created_at', '<=', $dateFin);
            }

            // Application du filtre de statut
            if ($request->filled('categorie')) {
                $query->where('categorie_depense_id', $categorie);
            }

            // Application du filtre de periode
            // periode=> jour, semaine, mois, année
            if ($request->filled('periode')) {
                $periode = $request->periode; // Ajout de cette ligne pour récupérer la période

                if ($periode == 'jour') {
                    $query->whereDate('created_at', Carbon::today());
                } elseif ($periode == 'semaine') {
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($periode == 'mois') {
                    $query->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year); // Ajout pour éviter d'avoir des résultats de plusieurs années
                } elseif ($periode == 'annee') {
                    $query->whereYear('created_at', Carbon::now()->year);
                }
            }

            $data_depense = $query->orderBy('created_at', 'desc')->get();
            return response()->json([
                'depense' => $data_depense,
                'categorie_depense' => $categorie_depense,
                'libelle_depense' => $data_libelle_depense
            ]);



            // dd($categorie_depense->toArray());
            // return view('backend.pages.depense.index', compact('data_depense', 'categorie_depense', 'data_libelle_depense'));
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function create()
    {
        try {
            // $data_depense = Depense::OrderBy('created_at', 'DESC')->get();
            $data_libelle_depense = LibelleDepense::OrderBy('created_at', 'ASC')->get();
            $categorie_depense = CategorieDepense::with('libelleDepenses')->whereNotIn('slug', ['achats'])->get();

            // dd($categorie_depense->toArray());
            return view('backend.pages.depense.create', compact('categorie_depense', 'data_libelle_depense'));
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }


    public function store(Request $request)
    {
        try {
            // dd($request->all());

            $data_libelle = LibelleDepense::whereId($request->libelle)->first(); // LibelleDepense
            $data_categorie = CategorieDepense::whereId($request->categorie_depense)->first(); // CategorieDepense

            $libelle = '';
            $categorie = '';
            // dd($data_libelle->toArray());

            if ($data_libelle) {
                $libelle =  $data_libelle->id;
                $categorie =  $data_libelle->categorie_depense_id;
            } elseif ($data_categorie) {
                $categorie =  $data_categorie->id;
                $libelle = null;
            }

            $data = $request->validate([
                'libelle' => '',
                'categorie_depense' => 'required',
                'montant' => 'required',
                'description' => '',
                'date_depense' => 'required',
            ]);

            $data_count = Depense::count();

            $data_depense = Depense::firstOrCreate([
                'categorie_depense_id' => $categorie,
                'libelle_depense_id' => $libelle,
                'libelle' => $request->libelle,
                'montant' => $request->montant,
                'description' => $request->description,
                'date_depense' => $request->date_depense,
                'user_id' => Auth::id()
            ]);

            Alert::success('Operation réussi', 'Success Message');
            return back();
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        $data_depense = Depense::find($id);
        $libelle_depense = LibelleDepense::OrderBy('created_at', 'ASC')->get();
        $categorie_depense = CategorieDepense::with('libelleDepenses')->whereNotIn('slug', ['achats'])->get();
        // dd($categorie_depense->toArray());
        return view('backend.pages.depense.edit', compact('data_depense', 'categorie_depense', 'libelle_depense'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        try {
            //request validation ......
            $data = $request->validate([
                'libelle' => '',
                'categorie_depense_id' => '',
                'montant' => 'required',
                'description' => '',
                'date_depense' => 'required',

            ]);


            // verifier si c'est une categorie ou libelle on reçoit
            $data_libelle = LibelleDepense::whereId($request->categorie)->first();
            $data_categorie = CategorieDepense::whereId($request->categorie)->first();

            $libelle = '';
            $categorie = '';

            if ($data_libelle) {
                $libelle =  $data_libelle->id;
                $categorie =  $data_libelle->categorie_depense_id;
            } elseif ($data_categorie) {
                $categorie =  $data_categorie->id;
                $libelle = null;
            }


            // $data_libelle = LibelleDepense::whereId($request->libelle)->first();
            // $data_categorie = CategorieDepense::whereId($request->categorie_depense)->first();

            // $libelle = '';
            // $categorie = '';
            // // dd($data_categorie->toArray());

            // if ($data_libelle) {
            //     $libelle =  $data_libelle->id;
            //     $categorie =  $data_libelle->categorie_depense_id;
            // } elseif ($data_categorie) {
            //     $categorie =  $data_categorie->id;
            //     $libelle = null;
            // }


            $data_depense = Depense::find($id)->update(
                [
                    'categorie_depense_id' => $categorie,
                    'libelle_depense_id' => $libelle,
                    'libelle' => $request->libelle,
                    'montant' => $request->montant,
                    'description' => $request->description,
                    'date_depense' => $request->date_depense,
                    'user_id' => Auth::id()
                ]
            );

            Alert::success('Opération réussi', 'Success Message');
            return back();
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    public function delete($id)
    {

        try {
            Depense::find($id)->forceDelete();
            return response()->json([
                'status' => 200,
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}
