<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransfert extends Model
{
    use HasFactory;
 public $incrementing = false;

    protected $fillable = [
        'quantite_bouteille', // quantité en bouteille
        'quantite_verre', // quantité de verre qui peut être dans une bouteille
        'quantite_total', // quantité totale en unité de sortie
        'from_produit_id', // produit source
        'to_produit_id', // produit destination
        'date_transfert',
        'code',
        'commentaire',
        'user_id',
    ];

    public function fromProduit()
    {
        return $this->belongsTo(Produit::class , 'from_product_id');
    }
    public function toProduit()
    {
        return $this->belongsTo(Produit::class , 'to_product_id');
    }
    public function produit()
    {
        return $this->belongsTo(Produit::class , 'produit_id');
    }

}
