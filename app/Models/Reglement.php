<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reglement extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', // code du reglement
        'vente_id', // id de la vente
        'user_id', // user qui confirme le reglement
        'montant_vente', // montant de la vente
        'montant_reglement', // montant du reglement
        'montant_restant', // montant restant
        'mode_paiement', // mode de paiement
    ];


    public $incrementing = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'reglements', 'length' => 10, 'prefix' =>
            mt_rand()]);
        });
    }



    // relations
    public function vente()
    {
        return $this->belongsTo(Vente::class, 'vente_id');
    }// fin de vente

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
