<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventaire extends Model
{
    use HasFactory , SoftDeletes;
    
    public $incrementing = false;

    protected $fillable = [
        'code',
        'date_inventaire',
        'mois_concerne',
        'annee_concerne',
        'user_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'inventaires', 'length' => 10, 'prefix' =>
            mt_rand()]);
        });
    }

    public function produits() {
        return $this->belongsToMany(Produit::class)->withPivot(['stock_initial','stock_vendu','stock_theorique','stock_physique', 'stock_physique_json' ,'ecart' ,'etat' , 'observation' , 'stock_dernier_inventaire'])->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
}
