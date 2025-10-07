<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLoggerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user();

            // Ignorer certaines routes (facultatif)
            $ignoredRoutes = ['debugbar', 'horizon', 'telescope'];
            $path = $request->path();
            if (collect($ignoredRoutes)->contains(fn($r) => str_contains($path, $r))) {
                return $response;
            }

            activity('Application')
                ->causedBy($user)
                ->withProperties([
                    'method' => $request->method(),
                    'route' => $request->fullUrl(),
                    'ip' => $request->ip(),
                    'agent' => $request->userAgent(),
                    'input' => $request->except(['password', '_token']), // éviter les données sensibles
                ])
                ->log("Action utilisateur : {$request->method()} sur {$path}");
        }

        return $response;
    }
}
