<?php

use App\Models\Optimize;
use App\Models\Inventaire;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaieController;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\PosteController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\ReglementController;
use App\Http\Controllers\site\SiteController;
use App\Http\Controllers\site\PanierController;
use App\Http\Controllers\site\AuthUserController;
use App\Http\Controllers\site\PanierMenuController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\menu\MenuController;
use App\Http\Controllers\backend\menu\PlatController;
use App\Http\Controllers\backend\user\AdminController;
use App\Http\Controllers\backend\slide\SlideController;
use App\Http\Controllers\backend\stock\AchatController;
use App\Http\Controllers\backend\user\ClientController;
use App\Http\Controllers\backend\vente\VenteController;
use App\Http\Controllers\backend\stock\SortieController;
use App\Http\Controllers\backend\menu\PlatMenuController;
use App\Http\Controllers\backend\module\ModuleController;
use App\Http\Controllers\backend\offert\OffertController;
use App\Http\Controllers\backend\vente\CommandeController;
use App\Http\Controllers\backend\depense\DepenseController;
use App\Http\Controllers\backend\permission\RoleController;
use App\Http\Controllers\backend\produit\ProduitController;
use App\Http\Controllers\backend\rapport\RapportController;
use App\Http\Controllers\backend\stock\EtatStockController;
use App\Http\Controllers\backend\stock\AjustementController;
use App\Http\Controllers\backend\stock\InventaireController;
use App\Http\Controllers\backend\parametre\SettingController;
use App\Http\Controllers\backend\menu\CategorieMenuController;
use App\Http\Controllers\backend\categorie\CategorieController;
use App\Http\Controllers\backend\configuration\CaisseController;
use App\Http\Controllers\backend\configuration\FormatController;
use App\Http\Controllers\backend\stock\StockTransfertController;
use App\Http\Controllers\backend\configuration\MagasinController;
use App\Http\Controllers\backend\permission\PermissionController;
use App\Http\Controllers\backend\depense\LibelleDepenseController;
use App\Http\Controllers\backend\parametre\ActivityLogsController;
use App\Http\Controllers\backend\fournisseur\FournisseurController;
use App\Http\Controllers\backend\depense\CategorieDepenseController;
use App\Http\Controllers\backend\configuration\UniteMesureController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::fallback(function () {
    return view('backend.utility.auth-404-basic');
});

Auth::routes();
//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

// Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

// //Update User Details
// Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
// Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

// Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');


######################      START BACKEND ROUTE         ###########################################################

//login  for dashboard
Route::controller(AdminController::class)->prefix('admin')->group(function () {
    route::get('/login', 'login')->name('admin.login');
    route::post('/login', 'login')->name('admin.login');
    route::post('/logout', 'logout')->name('admin.logout');
});



Route::prefix('admin')->middleware(['admin'])->group(function () {

    //Dashboard
    Route::controller(DashboardController::class)->group(function () {
        route::get('', 'index')->name('dashboard.index'); // dashboard
    });

    //register client
    Route::prefix('client')->controller(ClientController::class)->group(function () {
        route::get('vente-impaye', 'clientVenteImpaye')->name('client.vente-impaye'); // liste des client des ventes impayées
        route::get('', 'index')->name('client.index'); // liste des client
        route::post('store', 'store')->name('client.store'); // ajouter client
        route::post('update/{id}', 'update')->name('client.update'); // modifier client
        route::get('delete/{id}', 'delete')->name('client.delete'); // supprimer client
        route::get('profil/{id}', 'profil')->name('client.profil');
        route::post('change-password', 'changePassword')->name('client.new-password');
    });


    //register admin
    Route::prefix('register')->controller(AdminController::class)->group(function () {
        route::get('', 'index')->name('admin-register.index');
        route::post('store', 'store')->name('admin-register.store');
        route::post('update/{id}', 'update')->name('admin-register.update');
        route::get('delete/{id}', 'delete')->name('admin-register.delete');
        route::get('profil/{id}', 'profil')->name('admin-register.profil');
        route::post('change-password', 'changePassword')->name('admin-register.new-password');
    });

    //role
    Route::prefix('role')->controller(RoleController::class)->group(function () {
        route::get('', 'index')->name('role.index');
        route::post('store', 'store')->name('role.store');
        route::post('update/{id}', 'update')->name('role.update');
        route::get('delete/{id}', 'delete')->name('role.delete');
    });

    //role
    Route::prefix('permission')->controller(PermissionController::class)->group(function () {
        route::get('', 'index')->name('permission.index');
        route::get('create', 'create')->name('permission.create');
        route::post('store', 'store')->name('permission.store');
        route::get('edit{id}', 'edit')->name('permission.edit');
        route::put('update/{id}', 'update')->name('permission.update');
        route::get('delete/{id}', 'delete')->name('permission.delete');
    });

    //module
    Route::prefix('module')->controller(ModuleController::class)->group(function () {
        route::get('', 'index')->name('module.index');
        route::post('store', 'store')->name('module.store');
        route::post('update/{id}', 'update')->name('module.update');
        route::get('delete/{id}', 'delete')->name('module.delete');
    });




    //Setting
    Route::prefix('setting')->controller(SettingController::class)->group(function () {
        route::get('', 'index')->name('setting.index');
        route::post('store', 'store')->name('setting.store');

        // download backua db
        Route::get('download-backup/{file}', 'downloadBackup')->name('setting.download-backup');
    });


    #############  SLIDER  #####################

    //slider of basic site
    Route::prefix('slide')->controller(SlideController::class)->group(function () {
        route::get('', 'index')->name('slide.index');
        route::post('store', 'store')->name('slide.store');
        route::post('update/{id}', 'update')->name('slide.update');
        route::get('delete/{id}', 'delete')->name('slide.delete');
    });





    #############  SETTING  #####################
    //optimize clear : cache:clear  , route:cache , config:cache , 'view:clear' , optimize:clear
    Route::get('/cache-clear', function () {
        Artisan::call('optimize:clear');
        Optimize::create([
            'type_clear' => 'cache',
        ]);
        return response()->json(['message' => 'cache clear', 'status' => 200], 200);
    })->name('setting.cache-clear');

    //maintenance mode up
    Route::get(
        '/disable-maintenance-mode',
        function () {
            Artisan::call('up');
            Maintenance::create([
                'type' => 'up',
            ]);
            return response()->json(['message' => 'mode maintenance desactivé', 'status' => 200], 200);
        }
    )->name('setting.maintenance-up');

    //maintenance mode down
    Route::get(
        '/enable-maintenance-mode',
        function () {
            Artisan::call('down', [
                '--secret' => 'admin@',
                '--render' => 'backend.pages.maintenance-mode-view',
            ]);
            Maintenance::create([
                'type' => 'down',
            ]);
            return response()->json(['message' => 'mode maintenance activé', 'status' => 200], 200);
        }
    )->name('setting.maintenance-down');

    //activity logs
    Route::prefix('activity-logs')->controller(ActivityLogsController::class)->group(function () {
        route::get('', 'index')->name('activity-logs.index');
        route::get('delete/{id}', 'delete')->name('activity-logs.delete');
        route::delete('clear', 'clearAll')->name('activity-logs.clear-all');
    });

    

    //rapport
    Route::prefix('rapport')->controller(RapportController::class)->group(function () {
        route::get('categorie', 'categorie')->name('rapport.categorie');
        route::get('produit', 'produits')->name('rapport.produit');
        route::get('vente', 'vente')->name('rapport.vente');
        route::get('exploitation', 'exploitation')->name('rapport.exploitation');
        route::get('detail', 'detail')->name('rapport.detail');  //detail exploitation en fonction de la categorie depense
        route::get('historique', 'historique')->name('rapport.historique'); // historique, vente, achats inventaire pour chaque produit


        route::get('caisse', 'caisses')->name('rapport.caisse');
        route::get('commande', 'commandes')->name('rapport.commande');
        route::get('depense', 'depenses')->name('rapport.depense');
    });


    //magasin
    Route::prefix('magasin')->controller(MagasinController::class)->group(function () {
        route::get('', 'index')->name('magasin.index');
        route::post('store', 'store')->name('magasin.store');
        route::post('update/{id}', 'update')->name('magasin.update');
        route::get('delete/{id}', 'delete')->name('magasin.delete');
    });


    //caisse
    Route::prefix('caisse')->middleware('activity.logger')->controller(CaisseController::class)->group(function () {
        route::get('', 'index')->name('caisse.index');
        route::post('store', 'store')->name('caisse.store');
        route::post('update/{id}', 'update')->name('caisse.update');
        route::get('select', 'selectCaisse')->name('caisse.select')->middleware('role:caisse|supercaisse');
        route::post('select', 'selectCaisse')->name('caisse.select.post')->middleware('role:caisse|supercaisse');
        route::post('session-date-vente', 'sessionDate')->name('vente.session-date')->middleware('role:caisse|supercaisse');  // definir manuellement une session date pour la vente


        // route::get('delete/{id}', 'delete')->name('caisse.delete');
    });

    //categorie
    Route::prefix('categorie')->controller(CategorieController::class)->group(function () {
        route::get('create', 'create')->name('categorie.create');
        route::post('store', 'store')->name('categorie.store');
        route::get('add-subCat/{id}', 'addSubCat')->name('categorie.add-subCat'); // add subCategorie
        route::post('add-subCat-store', 'addSubCatStore')->name('categorie.add-subCat-store'); // add subCategorie
        route::get('edit/{id}', 'edit')->name('categorie.edit');
        route::post('update/{id}', 'update')->name('categorie.update');
        route::get('delete/{id}', 'delete')->name('categorie.delete');
    });


    //fournisseur
    Route::prefix('fournisseur')->controller(FournisseurController::class)->group(function () {
        route::get('', 'index')->name('fournisseur.index');
        route::post('store', 'store')->name('fournisseur.store');
        route::post('update/{id}', 'update')->name('fournisseur.update');
        route::get('delete/{id}', 'delete')->name('fournisseur.delete');
    });

    // unite de mesure
    Route::prefix('unite')->controller(UniteMesureController::class)->group(function () {
        route::get('', 'index')->name('unite.index');
        route::post('store', 'store')->name('unite.store');
        route::post('update/{id}', 'update')->name('unite.update');
        route::get('delete/{id}', 'delete')->name('unite.delete');
    });

    // format
    Route::prefix('format')->controller(FormatController::class)->group(function () {
        route::get('', 'index')->name('format.index');
        route::post('store', 'store')->name('format.store');
        route::post('update/{id}', 'update')->name('format.update');
        route::get('delete/{id}', 'delete')->name('format.delete');
    });

    // produit
    Route::prefix('produit')->middleware('activity.logger')->controller(ProduitController::class)->group(function () {
        route::get('', 'index')->name('produit.index')->middleware('can:voir-produit'); // liste des produit
        route::get('create', 'create')->name('produit.create')->middleware('can:creer-produit'); // vue de la page de creation produit
        route::post('store', 'store')->name('produit.store')->middleware('can:creer-produit'); // ajouter produit
        route::get('show/{id}', 'show')->name('produit.show')->middleware('can:voir-produit'); // detail produit
        route::get('edit/{id}', 'edit')->name('produit.edit')->middleware('can:modifier-produit');
        route::post('update/{id}', 'update')->name('produit.update')->middleware('can:modifier-produit');
        route::get('delete/{id}', 'delete')->name('produit.delete')->middleware('can:supprimer-produit'); // supprimer produit
    });

    //etat stock
    Route::prefix('etat-stock')->controller(EtatStockController::class)->group(function () {
        route::get('', 'index')->name('etat-stock.index');
        route::get('suivi-stock', 'suiviStock')->name('etat-stock.suiviStock');
    });

    // stock -achat
    Route::prefix('achat')->middleware('activity.logger')->controller(AchatController::class)->group(function () {
        route::get('index', 'index')->name('achat.index');  // liste des facture
        route::get('show/{id}', 'show')->name('achat.show');
        route::get('create', 'create')->name('achat.create')->middleware('check.inventaire'); // 
        route::post('store', 'store')->name('achat.store');
        route::get('edit/{id}', 'edit')->name('achat.edit');
        route::post('update/{id}', 'update')->name('achat.update');
        route::get('delete/{id}', 'delete')->name('achat.delete');
        route::post('check-facture', 'checkFactureExist')->name('achat.check-facture');
        route::post('check-montant', 'verifiyMontant')->name('achat.check-montant');  // check montant facture

    });

    // stock -ajustement
    Route::prefix('ajustement')->controller(AjustementController::class)->group(function () {
        route::get('', 'index')->name('ajustement.index');
        route::get('create/{id}', 'create')->name('ajustement.create');
        route::post('store', 'store')->name('ajustement.store');
    });

    // stock -sortie
    Route::prefix('sortie')->middleware('activity.logger')->controller(SortieController::class)->group(function () {
        route::get('', 'index')->name('sortie.index');
        route::get('show/{id}', 'show')->name('sortie.show');
        route::get('create', 'create')->name('sortie.create')->middleware('check.inventaire');
        route::post('store', 'store')->name('sortie.store');
    });


    // stock -transfert
    Route::prefix('stock-transfert')->middleware('activity.logger')->controller(StockTransfertController::class)->group(function () {
        route::get('create/{id}', 'create')->name('stock-transfert.create');
        route::post('store', 'store')->name('stock-transfert.store');
    });


    // stock -inventaire
    Route::prefix('inventaire')->middleware('activity.logger')->controller(InventaireController::class)->group(function () {
        route::get('', 'index')->name('inventaire.index');
        route::get('show/{id}', 'show')->name('inventaire.show');
        route::get('create', 'create')->name('inventaire.create');
        route::post('store', 'store')->name('inventaire.store');
        route::get('fiche-inventaire', 'ficheInventaire')->name('inventaire.fiche-inventaire');
        route::get('delete/{id}', 'delete')->name('inventaire.delete');
    });


    // vente
    Route::prefix('vente')->middleware('activity.logger')->controller(VenteController::class)->group(function () {
        route::get('vente-en-attente', 'venteEnAttente')->name('vente.attente')->middleware('can:voir-vente'); // liste des vente en attente en localStorage js
        route::get('', 'index')->name('vente.index')->middleware('can:voir-vente');
        route::get('historique-vente-client', 'historiqueVenteClient')->name('vente.client')->middleware('can:voir-vente'); // Historique des ventes client

        route::get('show/{id}', 'show')->name('vente.show')->middleware('can:voir-vente'); // detail vente
        route::get('create', 'create')->name('vente.create')->middleware(['can:creer-vente', 'check.inventaire']); // vue de la page de creation vente
        route::post('store', 'store')->name('vente.store')->middleware('can:creer-vente'); // ajouter vente
        route::get('cloture-caisse', 'clotureCaisse')->name('vente.cloture-caisse')->middleware('can:voir-vente'); // cloture caisse
        route::get('billeterie-caisse', 'billeterieCaisse')->name('vente.billeterie-caisse')->middleware('can:voir-vente'); // billeterie caisse
        route::post('billeterie-caisse-store', 'storeBilleterie')->name('vente.billeterie-caisse-store')->middleware('can:voir-vente'); // billeterie caisse
        route::get('fermer-caisse', 'fermerCaisse')->name('vente.fermer-caisse')->middleware('can:voir-vente'); // cloture caisse

        route::get('rapport', 'rapportVente')->name('vente.rapport-caisse')->middleware('can:voir-vente'); // rapport de vente caisse

        ##vente menu
        route::get('create-menu', 'createVenteMenu')->name('vente.menu.create')->middleware('can:creer-vente'); //vue de la page de vente menu
        route::post('store-menu', 'storeVenteMenu')->name('vente.menu.store')->middleware('can:creer-vente'); // ajouter vente menu

        route::get('getVenteCaisse', 'getVenteCaisse')->name('vente.getVenteCaisse'); //Liste des ventes de la caisse actuelle en json

        ##supprimer une vente
        route::get('delete/{id}', 'delete')->name('vente.delete')->middleware('can:supprimer-vente'); // supprimer vente
    });

    // Réglement
    Route::prefix('reglement')->middleware('activity.logger')->controller(ReglementController::class)->group(function () {
        route::get('', 'index')->name('reglement.index');
        route::post('store', 'store')->name('reglement.store');
        route::get('show/{id}', 'show')->name('reglement.show');
        route::get('delete/{id}', 'delete')->name('reglement.delete');
    });

    //offert
    Route::prefix('offert')->controller(OffertController::class)->group(function () {
        route::get('', 'index')->name('offert.index'); // liste des offerts
        route::get('getOffertNoApprouved', 'getOffertNoApprouved')->name('offert.non_approuves'); // liste des offerts non approuvés  
        route::get('approuvedOffert', 'approuvedOffert')->name('offert.approuvedOffert'); // approuver offert oui ou non 

        //OFFERT NOTIFICATION
        Route::get('/notifications/check',  'checkNotifications')->name('notifications.check');
        Route::post('/notifications/read',  'markAsRead')->name('notifications.markAsRead');
    });

    // Commande
    Route::prefix('commande')->controller(CommandeController::class)->group(function () {
        route::get('', 'index')->name('commande.index');
        route::get('show/{id}', 'show')->name('commande.show');
        route::post('statut', 'changerStatut')->name('commande.statut');
    });


    Route::prefix('categorie-depense')->middleware('activity.logger')->controller(CategorieDepenseController::class)->group(function () {
        route::get('', 'index')->name('categorie-depense.index');
        route::get('create', 'create')->name('categorie-depense.create');
        route::post('store', 'store')->name('categorie-depense.store');
        route::get('edit/{id}', 'edit')->name('categorie-depense.edit');
        route::post('update/{id}', 'update')->name('categorie-depense.update');
        route::get('delete/{id}', 'delete')->name('categorie-depense.delete');
        route::post('position/{id}', 'position')->name('categorie-depense.position');
    });


    Route::prefix('libelle-depense')->middleware('activity.logger')->controller(LibelleDepenseController::class)->group(function () {
        route::get('', 'index')->name('libelle-depense.index');
        route::get('create', 'create')->name('libelle-depense.create');
        route::post('store', 'store')->name('libelle-depense.store');
        route::get('edit/{id}', 'edit')->name('libelle-depense.edit');
        route::post('update/{id}', 'update')->name('libelle-depense.update');
        route::get('delete/{id}', 'delete')->name('libelle-depense.delete');
        route::post('position/{id}', 'position')->name('libelle-depense.position');
    });

    Route::prefix('depense')->middleware('activity.logger')->controller(DepenseController::class)->group(function () {
        route::get('', 'index')->name('depense.index');
        route::get('getList', 'getList')->name('depense.getList');
        route::get('create', 'create')->name('depense.create')->middleware('check.inventaire');
        route::post('store', 'store')->name('depense.store');
        route::get('edit/{id}', 'edit')->name('depense.edit');
        route::post('update/{id}', 'update')->name('depense.update');
        route::get('delete/{id}', 'delete')->name('depense.delete');
    });


    // produit restaurant
    Route::prefix('plat')->middleware('activity.logger')->controller(PlatController::class)->group(function () {
        route::get('', 'index')->name('plat.index');
        route::get('create', 'create')->name('plat.create');
        route::post('store', 'store')->name('plat.store');
        route::get('show/{id}', 'show')->name('plat.show');
        route::get('edit/{id}', 'edit')->name('plat.edit');
        route::post('update/{id}', 'update')->name('plat.update');
        route::get('delete/{id}', 'delete')->name('plat.delete');
    });



    Route::prefix('plat-menu')->middleware('activity.logger')->controller(PlatMenuController::class)->group(function () {
        route::get('', 'index')->name('plat-menu.index');
        route::get('create', 'create')->name('plat-menu.create');
        route::post('store', 'store')->name('plat-menu.store');
        route::get('show/{id}', 'show')->name('plat-menu.show');
        route::get('edit/{id}', 'edit')->name('plat-menu.edit');
        route::post('update/{id}', 'update')->name('plat-menu.update');
        route::get('delete/{id}', 'delete')->name('plat-menu.delete');
    });


    //  menu
    Route::prefix('menu')->middleware('activity.logger')->controller(MenuController::class)->group(function () {
        route::get('', 'index')->name('menu.index');
        route::get('create', 'create')->name('menu.create');
        route::get('getOptions', 'getOptions')->name('menu.options'); // recuperer en temps reel les nouvel enregistrements plat , complemet .....
        route::post('store', 'store')->name('menu.store');
        route::get('show/{id}', 'show')->name('menu.show');
        route::get('edit/{id}', 'edit')->name('menu.edit');
        route::post('update/{id}', 'update')->name('menu.update');
        route::get('delete/{id}', 'delete')->name('menu.delete');
    });

    // categorie menu
    Route::prefix('categorie-menu')->middleware('activity.logger')->controller(CategorieMenuController::class)->group(function () {
        route::get('', 'index')->name('categorie-menu.index');
        route::post('store', 'store')->name('categorie-menu.store');
        route::post('update/{id}', 'update')->name('categorie-menu.update');
        route::get('delete/{id}', 'delete')->name('categorie-menu.delete');
    });


    //RH poste
    Route::prefix('poste')->controller(PosteController::class)->group(function () {
        route::get('', 'index')->name('poste.index');
        route::post('store', 'store')->name('poste.store');
        route::post('update/{id}', 'update')->name('poste.update');
        route::get('delete/{id}', 'delete')->name('poste.delete');
    });


    //RH employe
    Route::prefix('employe')->controller(EmployeController::class)->group(function () {
        route::get('', 'index')->name('employe.index');
        route::post('store', 'store')->name('employe.store');
        route::post('update/{id}', 'update')->name('employe.update');
        route::get('delete/{id}', 'delete')->name('employe.delete');
    });


    //RH paie
    Route::prefix('paie')->controller(PaieController::class)->group(function () {
        route::get('', 'index')->name('paie.index');
        route::post('store', 'store')->name('paie.store');
        route::post('update/{id}', 'update')->name('paie.update');
        route::get('delete/{id}', 'delete')->name('paie.delete');
    });
});

######################      END BACKEND ROUTE         ###########################################################






######################      START FRONT ROUTE         ###########################################################
//User Login
Route::controller(AuthUserController::class)->group(function () {
    route::get('connexion', 'login')->name('user.login');
    route::post('connexion', 'login')->name('user.login.post');
    route::get('inscription', 'register')->name('user.register');
    route::post('inscription', 'register')->name('user.register.post');
    route::get('logout', 'logout')->name('user.logout');
    route::get('profile', 'profile')->name('user.profile');
    route::get('mes-commandes', 'commande')->name('user.commande');
});

//site
Route::controller(SiteController::class)->group(function () {
    route::get('', 'accueil')->name('accueil');
    route::get('nous-contacter', 'nousContacter')->name('nous-contactez');
    route::post('contacter-mail', 'sendMailContacter')->name('email-nous-contactez'); // envoie de email nous contacter


    route::get('/categorie/{id}', 'produit')->name('produit'); // get product of categorie selected
    route::get('/menu', 'menu')->name('menu');
    route::get('/produit/detail/{slug}', 'produitDetail')->name('produit.detail');

    //search
    route::get('/recherche', 'recherche')->name('recherche');
});

//panier
Route::controller(PanierController::class)->group(function () {
    route::get('panier', 'index')->name('panier');
    route::get('add/{id}', 'add')->name('cart.add');
    route::post('update', 'update')->name('cart.update');
    route::post('remove', 'remove')->name('cart.remove');
    route::get('caisse', 'checkout')->name('cart.checkout')->middleware('auth'); // caisse infos commande
    route::post('order', 'saveOrder')->name('cart.save-order')->middleware('auth'); // enregistrer la commande
    // route::get('clear', 'clear')->name('cart.clear');
});

// pânier Menu
Route::controller(PanierMenuController::class)->group(function () {
    // route::get('panier', 'index')->name('panier');
    route::post('add-menu', 'add')->name('cart.add-menu');
    route::post('update-menu', 'update')->name('cart.update-menu');
    route::post('remove-menu', 'remove')->name('cart.remove-menu');
    route::get('getInfoData', 'getCartDataMenu')->name('cart.getInfo-menu');
});



  
  

    


    ######################      END FRONT ROUTE         ###########################################################
