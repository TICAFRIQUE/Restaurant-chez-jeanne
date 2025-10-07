<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BaseModel extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // trace toutes les colonnes
            ->logOnlyDirty() // uniquement les champs modifiés
            ->setDescriptionForEvent(fn(string $eventName) =>
                class_basename($this) . " {$eventName} par " . auth()->user()?->name
            );
    }
}

