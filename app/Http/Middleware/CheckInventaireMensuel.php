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
    // public function handle(Request $request, Closure $next): Response
    // {
    //     $now = Carbon::now();

    //     $moisPrecedent = $now->copy()->subMonth();


    //     $inventaireExiste = Inventaire::where('mois_concerne', $moisPrecedent->month)
    //         ->where('annee_concerne', $moisPrecedent->year)
    //         ->exists();

    //     $estLePremier = $now->day === 1;
    //     $avant10h = $now->hour < 10;

    //     if (!$inventaireExiste && !($estLePremier && $avant10h)) {
    //         // return response()->json([
    //         //     'message' => 'L’inventaire du mois précédent doit être effectué avant toute vente.'
    //         // ], 403);


    //         // Mettre les mois en français
    //         Carbon::setLocale('fr');
    //         $moisNom = ucfirst($moisPrecedent->translatedFormat('F')); // Exemple : Juin

    //         return redirect()->back()->with('error', "L’inventaire du mois de $moisNom {$moisPrecedent->year} doit être effectué avant de continuer.");
    //     }

    //     return $next($request);
    // }



    // public function handle(Request $request, Closure $next): Response
    // {
    //     $now = Carbon::now();
    //     $moisPrecedent = $now->copy()->subMonth();

    //     $inventaireExiste = Inventaire::where('mois_concerne', $moisPrecedent->month)
    //         ->where('annee_concerne', $moisPrecedent->year)
    //         ->exists();

    //     Carbon::setLocale('fr');
    //     $moisNom = ucfirst($moisPrecedent->translatedFormat('F'));

    //     // Si l’inventaire existe, tout va bien
    //     if ($inventaireExiste) {
    //         return $next($request);
    //     }

    //     // Si aujourd’hui c’est le 1er
    //     if ($now->day === 1) {
    //         if ($now->hour < 6) {
    //             // Tolérance : jusqu’à 6h du matin
    //             return $next($request);
    //         }

    //         if ($now->hour >= 6 && $now->hour < 10) {
    //             // Entre 6h et 10h => blocage temporaire
    //             return redirect()->back()->with('error', "⚠️ Vous devez effectuer l’inventaire du mois de $moisNom {$moisPrecedent->year} avant 10h.");
    //         }

    //         if ($now->hour >= 10) {
    //             // Après 10h => blocage strict
    //             return redirect()->back()->with('error', "⛔ L’inventaire du mois de $moisNom {$moisPrecedent->year} n’a pas été fait à temps. Accès bloqué.");
    //         }
    //     }

    //     // Pour tous les autres jours (2 à 31), laisser passer
    //     return $next($request);
    // }


    // public function handle(Request $request, Closure $next): Response
    // {
    //     $now = Carbon::now();
    //     $moisPrecedent = $now->copy()->subMonth();

    //     $inventaireExiste = Inventaire::where('mois_concerne', $moisPrecedent->month)
    //         ->where('annee_concerne', $moisPrecedent->year)
    //         ->exists();

    //     Carbon::setLocale('fr');
    //     $moisNom = ucfirst($moisPrecedent->translatedFormat('F'));

    //     if ($inventaireExiste) {
    //         // Si l'inventaire est fait, tout est autorisé
    //         return $next($request);
    //     }

    //     if ($now->day === 1) {
    //         if ($now->hour < 6) {
    //             // Tolérance de minuit à 6h du matin le 1er
    //             return $next($request);
    //         }

    //         if ($now->hour < 10) {
    //             // Blocage temporaire entre 6h et 10h
    //             return redirect()->back()->with('error', "⚠️ L’inventaire du mois de $moisNom {$moisPrecedent->year} doit être effectué avant 10h ce matin.");
    //         }

    //         // Après 10h le 1er → blocage strict
    //         return redirect()->back()->with('error', "⛔ L’inventaire du mois de $moisNom {$moisPrecedent->year} n’a pas été fait à temps. Accès bloqué.");
    //     }

    //     // Tous les autres jours (2 à 31), on bloque si l’inventaire n’est pas fait
    //     return redirect()->back()->with('error', "⛔ Vous ne pouvez pas continuer tant que l’inventaire du mois de $moisNom {$moisPrecedent->year} n’est pas effectué.");
    // }



    // public function handle(Request $request, Closure $next): Response
    // {
    //     $now = Carbon::now();

    //     // Cible : mois précédent (ex: juillet)
    //     $moisPrecedent = $now->copy()->subMonth();
    //     $mois = $moisPrecedent->month;
    //     $annee = $moisPrecedent->year;

    //     // Seuil de blocage = 1er du mois ACTUEL à 6h du matin
    //     $seuilBlocage = Carbon::create($moisPrecedent->year, $moisPrecedent->month, 1)
    //         ->addMonth() // on passe au 1er du mois suivant
    //         ->setTime(6, 0, 0); // à 6h

    //     $inventaireExiste = Inventaire::where('mois_concerne', $mois)
    //         ->where('annee_concerne', $annee)
    //         ->exists();

    //     Carbon::setLocale('fr');
    //     $moisNom = ucfirst($moisPrecedent->translatedFormat('F'));

    //     // Bloquer SEULEMENT si on est après la date seuil ET que l’inventaire n’est pas encore fait
    //     if (!$inventaireExiste && $now->greaterThanOrEqualTo($seuilBlocage)) {
    //         return redirect()->back()->with('error', "⛔ L’inventaire du mois de $moisNom $annee doit être effectué. Accès bloqué jusqu’à l'inventaire du mois de $moisNom {$moisPrecedent->year}.");
    //     }

    //     return $next($request);
    // }


    // version simplifiée

    public function handle(Request $request, Closure $next): Response
    {
        $now = now();

        // Mois précédent
        $moisPrecedent = $now->copy()->subMonth();

        // Vérifie si l’inventaire du mois précédent est fait
        $inventaireExiste = Inventaire::where('mois_concerne', $moisPrecedent->month)
            ->where('annee_concerne', $moisPrecedent->year)
            ->exists();

        // Date limite : 1er du mois suivant à 6h
        $dateLimite = $moisPrecedent->copy()->startOfMonth()->addMonth()->setTime(6, 0);

        if (!$inventaireExiste && $now->gte($dateLimite)) {
            $moisNom = ucfirst($moisPrecedent->translatedFormat('F'));
            return redirect()->back()->with('error', "⛔ L’inventaire du mois de $moisNom {$moisPrecedent->year} doit être effectué.");
        }

        return $next($request);
    }
}
