<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Produit extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, sluggable;

    // public function registerMediaConversions(Media $media = null): void
    // {

    //     // $this->addMediaConversion('large-size')
    //     // ->width(570) // par exemple 300px de large
    //     // ->height(470) // 300px de hauteur
    //     // ->sharpen(10); // pour améliorer la qualité si besoin

    //     $this->addMediaConversion('standard-size')
    //         ->width(300) // par exemple 300px de large
    //         ->height(300) // 300px de hauteur
    //         ->sharpen(10); // pour améliorer la qualité si besoin


    //     $this->addMediaConversion('small-size')
    //         ->width(150) // par exemple 300px de large
    //         ->height(150) // 300px de hauteur
    //         ->sharpen(10); // pour améliorer la qualité si besoin
    // }

    public $incrementing = false;

    protected $fillable = [
        'code',
        'nom', // libellé produit
        'slug',
        'description',
        'prix', // prix de vente
        'stock_initial', // stock des nouveau achat apres inventaire
        'stock', //quantité
        'stock_dernier_inventaire', // stock du dernier inventaire
        'stock_alerte', // stock de securite
        'categorie_id',
        'categorie_menu_id',
        'type_id', // type produit
        'statut', // active , desactive
        'user_id',
        // 'magasin_id',
        'valeur_unite', // qté unite de mesure
        'unite_id', // unite du produit
        'format_id', // format du produit
        'valeur_format', // valeur du format
        'unite_sortie_id', // unite de sortie ou vente
    ];


    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'produits', 'length' => 10, 'prefix' =>
            mt_rand()]);
        });
    }


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nom'
            ]
        ];
    }



    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }
    public function categorieMenu() // categorie du menu
    {
        return $this->belongsTo(CategorieMenu::class, 'categorie_menu_id');
    }

    public function typeProduit()
    {
        return $this->belongsTo(Categorie::class, 'type_id');  // famille de produit Bar et restaurant
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unite()
    {
        return $this->belongsTo(Unite::class, 'unite_id');
    }

    public function format()
    {
        return $this->belongsTo(Format::class, 'format_id');
    }

    public function uniteSortie()
    {
        return $this->belongsTo(Unite::class, 'unite_sortie_id');
    }

    public function magasin()
    {
        return $this->belongsTo(Magasin::class);
    }

    public function achats() // 
    {
        return $this->hasMany(Achat::class);
    }


    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_produit')
            ->withPivot('categorie_menu_id')
            ->withTimestamps();
    }


    // Relation avec les compléments
    public function complements()
    {
        return $this->belongsToMany(Produit::class, 'produit_complement', 'produit_id', 'complement_id')
            ->withPivot('menu_id')
            ->withTimestamps();
    }


    public function commandes(): BelongsToMany
    {
        return $this->belongsToMany(Commande::class)->withPivot(['quantite', 'prix_unitaire', 'total'])->withTimestamps();
    }

    public function sorties()
    { // sortie de stock
        return $this->belongsToMany(Sortie::class)->withPivot(['quantite_existant', 'quantite_utilise'])->withTimestamps();
    }

    public function inventaires()
    {
        return $this->belongsToMany(Inventaire::class)->withPivot(['stock_initial', 'stock_vendu', 'stock_theorique', 'stock_physique', 'stock_physique_json', 'ecart', 'etat', 'observation', 'stock_dernier_inventaire'])->withTimestamps();
    }

    public function ventes()
    {
        return $this->belongsToMany(Vente::class, 'produit_vente')
            ->withPivot(
                'quantite',
                'quantite_bouteille',
                'prix_unitaire',
                'total',
                'offert', // Ajout de la colonne 'offert'
                'unite_vente_id',
                'variante_id'
            )
            ->withTimestamps();
    }


    public function variantes(): BelongsToMany
    {
        return $this->belongsToMany(Variante::class, 'produit_variante')->withPivot([
            'quantite',
            'prix',
            'total',
            // 'quantite_stocke',
            'quantite_disponible',
            'quantite_vendu',
            'bouteille_vendu'
        ])->withTimestamps();
    }



    // ScopeActive produits

    /**
     * Scope to retrieve only active products.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * @throws \Exception
     */
    public function scopeActive($query)
    {
        // Retrieve only products with statut equal to 'active'
        return $query->where('statut', 'active');
    }



    // scope pour trier les produits par ordre alphabétique
    public function scopeAlphabetique($query)
    {
        return $query->orderBy('nom', 'asc');
    }
}
