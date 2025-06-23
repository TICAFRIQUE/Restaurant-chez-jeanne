<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vente extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'date_vente',
        'montant_avant_remise', //montant ht avant remise',
        'montant_total', // montant ttc
        'type_remise',
        'valeur_remise',
        'montant_remise',
        'montant_recu', // montant donne par le client
        'montant_rendu', // montant rendu par le caissier
        // 'statut_paiement',
        'mode_paiement',
        'user_id',
        'client_id',
        'caisse_id',
        'statut', // confirmée , en attente , livrée , annulée  

        'numero_table',
        'nombre_couverts',
        'statut_cloture', // boolean true ou false
        'type_vente', // vente normale , commande , 
        'commande_id',
        
        'statut_paiement', // paye ou impaye
        'montant_restant', // montant restant de la vente
        'statut_reglement' // reglement effectué ou non [0: non, 1: oui]
    ];

    public $incrementing = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'ventes', 'length' => 10, 'prefix' =>
            mt_rand()]);
        });
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function caisse()
    {
        return $this->belongsTo(Caisse::class, 'caisse_id');
    }
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'produit_vente')
            ->withPivot('quantite', 'quantite_bouteille',  'prix_unitaire', 'total', 'unite_vente_id', 'variante_id')
            ->withTimestamps();
    }

    public function plats()
    {
        return $this->belongsToMany(Plat::class, 'plat_vente')
            ->withPivot('quantite', 'prix_unitaire', 'total', 'complement', 'garniture')
            ->withTimestamps();
    }

    public function billetteries()
    {
        return $this->hasMany(Billetterie::class);
    }
}
