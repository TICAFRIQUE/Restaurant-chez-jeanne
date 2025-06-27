<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Inventaire;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInventaireMensuel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $now = Carbon::now();

        $moisPrecedent = $now->copy()->subMonth();


        $inventaireExiste = Inventaire::where('mois_concerne', $moisPrecedent->month)
            ->where('annee_concerne', $moisPrecedent->year)
            ->exists();

        $estLePremier = $now->day === 1;
        $avant10h = $now->hour < 10;

        if (!$inventaireExiste && !($estLePremier && $avant10h)) {
            // return response()->json([
            //     'message' => 'L’inventaire du mois précédent doit être effectué avant toute vente.'
            // ], 403);


            // Mettre les mois en français
            Carbon::setLocale('fr');
            $moisNom = ucfirst($moisPrecedent->translatedFormat('F')); // Exemple : Juin

            return redirect()->back()->with('error', "L’inventaire du mois de $moisNom {$moisPrecedent->year} doit être effectué avant de continuer.");
        }

        return $next($request);
    }
}
