<?php

namespace App\Http\Controllers\backend\stock;

use App\Models\Unite;
use App\Models\Entree;
use App\Models\Format;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Fournisseur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class StockController extends Controller
{
    //

    public function index() {}

    public function create(Request $request)
    {

        try {
            $data_categorie = Categorie::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')->OrderBy('position', 'ASC')->get();
            $data_produit = Produit::with(['categorie.ancestors', 'media'])->get();
            $type_produit = Categorie::whereNull('parent_id')->whereIn('name', ['bar', 'restaurant'])->get();

            $data_format = Format::all();
            $data_unite = Unite::all();
            $data_fournisseur = Fournisseur::all();

            // dd($data_produit->toArray());
            return view('backend.pages.stock.entree.create', compact('type_produit', 'data_categorie', 'data_produit', 'data_format', 'data_unite', 'data_fournisseur'));
        } catch (\Throwable $e) {
            return  $e->getMessage();
        }

        // dd($data_categorie->toArray());

    }

    public function store(Request $request)
    {
        try {
            // $data = $request->all();
            // dd($data);

            //statut stock libelle active ? desactive
            $statut = '';
            if ($request['statut'] == 'on') {
                $statut = 'active';
            } else {
                $statut = 'desactive';
            }

            //recuperer le type entree : bar ?restaurant            
            $type_entree = $request['type_entree'];
            $type_entree = Categorie::whereId($type_entree)->first();
            if ($type_entree->name == 'restaurant') {
                $request->validate([
                    'produit_id' => 'required',
                    'quantite_format' => 'required',
                    'format_id' => 'required',
                    'unite_id' => 'required',
                    'quantite_unite_unitaire' => 'required', // valeur par unite
                    'quantite_unite_total' => 'required', // --qte stockable
                    'fournisseur_id' => 'required',
                    'prix_achat_unitaire' => 'required',
                    'prix_achat_total' => 'required',
                    'statut' => ''
                ]);


                $stock_entree = Entree::create([
                    'code' => 'SE-' . strtoupper(Str::random(8)),
                    'type_entree_id' => $type_entree->id,
                    'produit_id' => $request['produit_id'],
                    'format_id' => $request['format_id'],
                    'unite_id' => $request['unite_id'],
                    'fournisseur_id' => $request['fournisseur_id'],
                    'quantite_format' => $request['quantite_format'],
                    'quantite_unite_unitaire' => $request['quantite_unite_unitaire'],
                    'quantite_unite_total' => $request['quantite_unite_total'],
                    'prix_achat_unitaire' => $request['prix_achat_unitaire'],
                    'prix_achat_total' => $request['prix_achat_total'],
                    'statut' => $statut,
                    'user_id' => Auth::id(),

                ]);


                //mise a jour du stock dans la table produit si le statut est active
                if ($statut == 'active') {
                    $produit = Produit::find($request['produit_id']);
                    $produit->stock += $request['quantite_unite_total'];
                    $produit->save();
                }




                Alert::success('Opération réussi', 'Success Message');
                return back();
            }
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}