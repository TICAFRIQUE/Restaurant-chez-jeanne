<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OffertNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'offert_id',
        'vente_id',
        'message',
        'is_read',
    ];


    public $incrementing = false;



    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'offert_notifications', 'length' => 10, 'prefix' =>
            mt_rand()]);
        });
    }


    public function offert()
    {
        return $this->belongsTo(Offert::class);
    }

    public function vente()
    {
        return $this->belongsTo(Vente::class);
    }
}
