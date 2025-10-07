<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Facades\CauserResolver;

class ActivityLogger
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * Logue une activité utilisateur
     *
     * @param string $message
     * @param array $properties
     * @param string $logName
     * @return void
     */
    public function log(string $message, array $properties = [], string $logName = 'system')
    {
        activity($logName)
            ->causedBy($this->user)
            ->withProperties(array_merge($properties, [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]))
            ->log($message);
    }
}
