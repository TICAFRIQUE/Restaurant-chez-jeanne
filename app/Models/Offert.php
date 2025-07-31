<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offert extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'vente_id',
        'quantite',
        'variante_id', // Variante du produit, 
        'approuved_at', // Offre approuvée ou non
        'statut_view', // Statut de la vue de l'offre par le gestionnaire
        'user_approuved', // Utilisateur qui approuve l'offre
        'user_created', // Utilisateur qui crée l'offre
        'date_created', // Date de création de l'offre
        'date_approuved', // Date de création de l'offre
    ];

    public $incrementing = false;



     public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'offerts', 'length' => 10, 'prefix' =>
            mt_rand()]);
        });
    }
}
