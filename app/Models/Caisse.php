<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Caisse extends Model
{
    use HasFactory;

    public $incrementing = false;


    protected $fillable = [
        'code',
        'libelle',
        'description',
        'statut',
        'session_date_vente',
        'id_session_caisse',
        'created_at',
        'updated_at'
    ];


    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'caisses', 'length' => 10, 'prefix' =>
            mt_rand()]);
        });
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    // recuperer l'historique des caisses
    public function historiqueCaisse(){
        return $this->hasMany(HistoriqueCaisse::class);
    }

//     // utilisateurs de la caisse active
//    public function scopeUserCaisseActive($query)
//     {
//        return $query->where('statut', 'active');
//    }


    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    // billetteries
    public function billetteries()
    {
        return $this->hasMany(Billetterie::class);
    }
}
