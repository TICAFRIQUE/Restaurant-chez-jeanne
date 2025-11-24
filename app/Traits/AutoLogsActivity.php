<?php




namespace App\Traits;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

trait AutoLogsActivity
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName(strtolower(class_basename($this)))
            ->setDescriptionForEvent(fn(string $eventName) =>
                sprintf(
                    "%s %s par %s (%s)",
                    class_basename($this),
                    strtoupper($eventName),
                    auth()->user()?->first_name ?? 'Système',
                    now()->format('d/m/Y H:i:s')
                )
            );
    }
}
