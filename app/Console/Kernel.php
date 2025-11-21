<?php

namespace App\Console;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Scheduling\Schedule;
use Spatie\Backup\Tasks\Cleanup\CleanupJob;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * Supprime les sauvegardes locales plus anciennes que 5.
     *
     * Appel  toutes les nuits  minuit.
     */
    private function purgerAnciennesSauvegardes()
    {
        $appName = config('app.name');

        $files = collect(Storage::disk('local')->files($appName))
            ->sortByDesc(function ($file) {
                return Storage::disk('local')->lastModified($file);
            })
            ->values();

        if ($files->count() > 5) {
            $files->slice(5)->each(function ($oldFile) {
                Storage::disk('local')->delete($oldFile);
            });
        }
    }



    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    // protected function schedule(Schedule $schedule)
    // {
    //     // $schedule->command('inspire')->hourly();

    //     // Planifier la sauvegarde tous les jours à 16h
    //     $schedule->command('backup:run')->dailyAt('16:00')
    //         ->after(function () {
    //             $this->purgerAnciennesSauvegardes();
    //         })
    //         ->onFailure(function () {
    //             Log::error('La sauvegarde a échoué.');
    //         });


    //     // // executer chaque minute
    //     // $schedule->command('backup:run')->everyMinute();
    // }

    // protected function schedule(Schedule $schedule)
    // {
    //     // Planifier la sauvegarde tous les jours à 16h
    //     $schedule->command('backup:run')->dailyAt('16:00')
    //         ->after(function () {
    //             // Nettoyer les anciens backups et garder seulement les 3 derniers
    //             Artisan::call('backup:clean');
    //         })
    //         ->onFailure(function () {
    //             Log::error('La sauvegarde a échoué.');
    //         });

    //     // Optionnel : exécuter chaque minute (pour tests rapides)
    //     // $schedule->command('backup:run')->everyMinute()
    //     //     ->after(function () {
    //     //         Artisan::call('backup:clean');
    //     //     });
    // }


    protected function schedule(Schedule $schedule)
{
    $schedule->command('backup:run')->everyMinute()
        ->after(function () {
            // Nettoyer les anciens backups directement via le service Spatie
            $cleanupJob = app(CleanupJob::class);
            $cleanupJob->run();

            Log::info('Backups nettoyés via Spatie CleanupJob.');
        });
}


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
