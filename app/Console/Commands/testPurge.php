<?php

namespace App\Console\Commands;

use ReflectionClass;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TestPurge extends Command
{
    protected $signature = 'backup:test-purge';
    protected $description = 'Teste la purge des sauvegardes';

    public function handle()
    {
        $this->info('Test de la purge des sauvegardes...');
        
        try {
            $appName = config('app.name');
            $this->info("App: {$appName}");

            // Lister les fichiers avant
            $filesBefore = collect(Storage::disk('local')->files($appName))
                ->filter(fn($file) => pathinfo($file, PATHINFO_EXTENSION) === 'zip')
                ->sortByDesc(fn($file) => Storage::disk('local')->lastModified($file))
                ->values();

            $this->info("Fichiers avant purge: " . $filesBefore->count());
            $filesBefore->each(function($file) {
                $this->line("- " . basename($file));
            });

            // Appeler la purge
            $kernel = app(\App\Console\Kernel::class);
            $reflection = new ReflectionClass($kernel);
            $method = $reflection->getMethod('purgerAnciennesSauvegardes');
            $method->setAccessible(true);
            $method->invoke($kernel);

            // Lister les fichiers après
            $filesAfter = collect(Storage::disk('local')->files($appName))
                ->filter(fn($file) => pathinfo($file, PATHINFO_EXTENSION) === 'zip')
                ->sortByDesc(fn($file) => Storage::disk('local')->lastModified($file))
                ->values();

            $this->info("Fichiers après purge: " . $filesAfter->count());
            $filesAfter->each(function($file) {
                $this->line("- " . basename($file));
            });

            $this->info('✅ Test terminé avec succès!');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
