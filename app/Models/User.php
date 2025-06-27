<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPermissions, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    public $incrementing = false;

    protected $fillable = [
        'first_name', //prenom
        'last_name', //nom
        'phone', //contact
        'email',
        'password',
        'avatar',
        'role',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'users', 'length' => 10, 'prefix' =>
            mt_rand()]);
        });
    }

    public function caisse()
    {
        return $this->belongsTo(Caisse::class);
    }
    

    public function ventes()
    {
        return $this->hasMany(Vente::class , 'client_id');
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }


    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class);
    }


    public function achats() // BAR ? RESTAURANT
    {
        return $this->hasMany(Achat::class);
    }

    public function ajustements()
    {
        return $this->hasMany(Ajustement::class);
    }

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }


    public function libelleDepenses()
    {
        return $this->hasMany(LibelleDepense::class);
    }


    public function produit_menus()
    {
        return $this->hasMany(ProduitMenu::class);
    }


    public function menus()
    {
        return $this->hasMany(Menu::class);
    }


    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    public function sorties(): HasMany
    {
        return $this->hasMany(Sortie::class);
    }
    
    



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
