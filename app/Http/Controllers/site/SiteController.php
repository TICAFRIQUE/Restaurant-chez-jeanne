<?php

namespace App\Http\Controllers\site;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Slide;
use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SiteController extends Controller
{
    /**Accueil du site */

    public function accueil()
    {
        try {
            //slider
            $data_slide = Slide::with('media')->orderBy('id', 'DESC')->get();

            // Récupérer les produits de type bar
            $produitsBar = Produit::active()->whereHas('categorie', function ($query) {
                $query->where('famille', 'bar');
            })
                // ->whereHas('achats', function($query) {
                //     $query->where('statut', 'active');
                // })
                ->take(10)
                ->get();

            // Récupérer les produits de type menu
            $produitsMenu = Produit::active()->whereHas('categorie', function ($query) {
                $query->where('famille', 'menu');
            })

                ->take(10)
                ->get();

            // dd($produitsMenu->toArray());

            // Combiner les produits bar et menu
            $produits = $produitsMenu->concat($produitsBar);
            // dd($produits->toArray());

            // Récupérer les produits les plus commandés
            $produitsLesPlusCommandes = Produit::active()->whereHas('categorie', function ($query) {
                $query->whereIn('famille', ['bar', 'menu']);
            })
                // ->withCount('commandes')
                ->withCount('ventes')
                // ->havingRaw('commandes_count > 1')
                ->havingRaw('ventes_count > 1')
                ->orderBy('ventes_count', 'desc')
                ->get();

            // dd($produitsLesPlusCommandes->toArray());
            return view('site.pages.accueil', compact(
                'data_slide',
                'produitsBar',
                'produitsMenu',
                'produits',
                'produitsLesPlusCommandes'
            ));

         
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }



    /**Liste des produit en fonction de la categorie
     * 
     * @param{slug-categorie}
     */
    public function produit(Request $request, $slug)
    {
        try {
            $categorieSelect = Categorie::whereSlug($slug)->first(); // recuperer les infos de la categorie a partir du slug

            if (!$categorieSelect) {
                return redirect()->route('accueil');
            }
            if ($categorieSelect->type) {
                $produits = Produit::active()->where('type_id', $categorieSelect->id)
                    ->paginate(8);
            }else{
                $produits = Produit::active()->where('categorie_id', $categorieSelect->id)
                    ->paginate(8);
            }
            // // retourner les achats du produits si type=bar
            // if ($categorieSelect->type == 'bar') {
            //     $produits = Produit::where('type_id', $categorieSelect->id)
            //         ->withWhereHas('achats', fn($q) => $q->whereStatut('active'))
            //         ->whereStatut('active')
            //         ->paginate(10);
            // } elseif ($categorieSelect->type == 'menu') {
            //     $produits = Produit::where('type_id', $categorieSelect->id)
            //         ->whereStatut('active')
            //         ->paginate(10);
            // } elseif ($categorieSelect->famille == 'bar') {
            //     $produits = Produit::where('categorie_id', $categorieSelect->id)
            //         ->withWhereHas('achats', fn($q) => $q->whereStatut('active'))
            //         ->whereStatut('active')
            //         ->paginate(10);
            // } elseif ($categorieSelect->famille == 'menu') {
            //     $produits = Produit::where('categorie_id', $categorieSelect->id)
            //         // ->withWhereHas('achats', fn($q) => $q->whereStatut('active'))
            //         ->whereStatut('active')
            //         ->paginate(10);
            // }

            // dd($produits->toArray());
            // $produits  =   $produits->achats;

            // $categorie = Categorie::with(['children' , 'parent'])
            //     ->withCount('children')->where('parent_id', $categorieSelect->id)->OrderBy('position', 'ASC')->get();  // categorie et ses souscategorie 

            $categories = Categorie::whereNull('parent_id')
                ->with('children')
                ->whereIn('type', ['menu', 'bar'])
                ->orderBy('position', 'DESC')
                ->get();
            // dd($categorie->toArray());

            return view('site.pages.produit', compact(
                'produits',
                'categories',
                'categorieSelect',
            ));
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    public function menu(Request $request)
    {
        try {
            $today = Carbon::today();

            // Récupérer le menu du jour avec les relations nécessaires
            $menu = Menu::with([
                'produits.achats',
                'produits.categorie' => function ($query) {
                    $query->with(['parent', 'children', 'descendants']); // Charger les sous-catégories
                }
            ])->where('date_menu', $today)->first();

            if ($menu) {
                // Récupérer toutes les catégories associées aux produits du menu
                $categories = $this->getCategoriesFromMenu($today);

                // Si une catégorie est passée dans la requête
                if ($request->has('categorie')) {
                    $categorieRequest = Categorie::where('slug', $request->categorie)->first();

                    if ($categorieRequest) {
                        // Filtrer les produits appartenant à la catégorie demandée ou ses descendants
                        $produitsFiltres = $menu->produits->filter(function ($produit) use ($categorieRequest) {
                            return $produit->categorie->id === $categorieRequest->id ||
                                $produit->categorie->parent_id === $categorieRequest->id ||
                                $produit->categorie->descendants->contains('id', $categorieRequest->id);
                        });

                        // Regrouper les produits filtrés par la catégorie principale
                        $produitsFiltres = $produitsFiltres->groupBy(function ($produit) {
                            return $produit->categorie->getPrincipalCategory()->type;
                        });

                        return view('site.pages.menu', compact('menu', 'produitsFiltres', 'categories', 'categorieRequest'));
                    }
                } else {
                    // Si aucune catégorie spécifique n'est demandée, on regroupe par catégorie principale
                    $produitsFiltres = $menu->produits->groupBy(function ($produit) {
                        return $produit->categorie->getPrincipalCategory()->type;
                    });

                    return view('site.pages.menu', compact('menu', 'produitsFiltres', 'categories'));
                }
            } else {
                // Si aucun menu n'est trouvé pour aujourd'hui
                $produitsFiltres = collect();
                $categories = [];


                return view('site.pages.menu', compact('menu', 'produitsFiltres', 'categories'));
            }
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }



    public function produitDetail($slug)
    {

        try {
            $produit = Produit::where('slug', $slug)->first();
            $produit = Produit::find($produit->id);
            // dd($produit->categorie->toArray());

            $produitsRelateds = Produit::where('categorie_id', $produit->categorie_id)->where('id', '!=', $produit->id)->get();
            // dd($produitsRelateds->toArray());

            return view('site.pages.produit-detail', compact('produit', 'produitsRelateds'));
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }



    // Controller

    public function getCategoriesFromMenu($today)
    {
        // Charger le menu avec les produits et les catégories
        $menu = Menu::with([
            'produits.categorie' => function ($query) {
                $query->with(['parent', 'children' => function ($q) {
                    $q->with('children'); // Charger les sous-catégories récursivement
                }]);
            }
        ])->where('date_menu', $today)->first();

        if (!$menu) {
            return collect(); // Retourner une collection vide si le menu n'existe pas
        }

        // Récupérer toutes les catégories des produits du menu
        $categories = collect();
        foreach ($menu->produits as $produit) {
            $categorie = $produit->categorie;
            if ($categorie) {
                $categories = $categories->merge($categorie->descendants->push($categorie));
            }
        }

        // Grouper les catégories par leur catégorie principale
        $groupedCategories = $categories->groupBy(function ($categorie) {
            return $categorie->getPrincipalCategory()->id; // Grouper par catégorie principale
        })->map(function ($categorieGroup) {
            return $categorieGroup->unique('id');
        });

        return $groupedCategories;
    }











    // public function
}
