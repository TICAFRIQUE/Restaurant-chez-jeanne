<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Vente;
use App\Models\Produit;
use App\Models\Setting;
use App\Models\Categorie;
use App\Models\Inventaire;
use App\Models\ProduitVente;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // Set the locale for Carbon
        \Carbon\Carbon::setLocale('fr');
        //
        Schema::defaultStringLength(191);

        //fonction qui récupère tous les produits de catégorie famille menu et met le stock à 100
        $this->app->booted(function () {
            if (Schema::hasTable('produits')) {
                Produit::whereHas('categorie', function ($query) {
                    $query->where('famille', 'menu');
                })->chunk(100, function ($produits) {
                    foreach ($produits as $produit) {
                        $produit->update(['stock' => 100]);
                    }
                });
            }
        });




        // $this->app->booted(function () {
        //     $permissions = \Spatie\Permission\Models\Permission::pluck('id')->toArray();

        //     $developpeurRole = \Spatie\Permission\Models\Role::where('name', 'developpeur')->first();
        //     $superadminRole = \Spatie\Permission\Models\Role::where('name', 'superadmin')->first();

        //     if ($developpeurRole) {
        //         $developpeurRole->permissions()->sync($permissions);
        //     }

        //     if ($superadminRole) {
        //         $superadminRole->permissions()->sync($permissions);
        //     }
        // });


        $this->app->booted(function () {
            try {
                if (Schema::hasTable('permissions') && Schema::hasTable('roles')) {
                    $permissions = Permission::pluck('id')->toArray();

                    $developpeurRole = Role::where('name', 'developpeur')->first();
                    $superadminRole = Role::where('name', 'superadmin')->first();

                    if ($developpeurRole) {
                        $developpeurRole->permissions()->sync($permissions);
                    }

                    if ($superadminRole) {
                        $superadminRole->permissions()->sync($permissions);
                    }
                }
            } catch (\Exception $e) {
                // Optionnel : log de l'erreur si besoin
                return back()->withErrors('Une erreur est survenue lors de la synchronisation des permissions.', 'Message d\'erreur:' . $e->getMessage());
            }
        });



        //get setting data
        if (Schema::hasTable('settings')) {
            $data_setting = Setting::first();
        }

       

        // get all categories parent with children
        if (Schema::hasTable('categories')) {
            $menu_link = Categorie::whereNull('parent_id')->with('children', fn($q) => $q->OrderBy('position', 'ASC'))->withCount('children')
                ->whereIn('type', ['menu', 'bar'])
                ->OrderBy('position', 'DESC')->get();

            $categories = Categorie::whereNull('parent_id')
                ->with('children')
                ->whereIn('type', ['menu', 'bar'])
                ->orderBy('position', 'DESC')
                ->get();
        }




        ##END

        view()->share([
            'setting' => $data_setting ?? null,
            'menu_link' => $menu_link ?? null,
            'categories' => $categories ?? null,
        ]);
    }
}
