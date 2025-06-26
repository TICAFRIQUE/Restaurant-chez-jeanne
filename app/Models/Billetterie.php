<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Billetterie extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        // 'vente_id',
        'mode',
        'type_monnaie',
        'quantite',
        'valeur',
        'type_mobile_money',
        'montant',
        'total',
        'caisse_id',
        'user_id',
        'date_save',
        'montant_impaye', // Montant impayé de la vente
    ];



    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'billetteries', 'length' => 10, 'prefix' =>
            mt_rand()]);
        });
    }


    // public function vente()
    // {
    //     return $this->belongsTo(Vente::class);
    // }

    // caisses
    public function caisses()
    {
        return $this->hasMany(Caisse::class);
    }
}
