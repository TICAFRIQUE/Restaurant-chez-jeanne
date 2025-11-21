<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class deleteBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //supprimer les anciennes sauvegardes selon la politique de rétention définie dans config/backup.php
        \Spatie\Backup\Tasks\Cleanup\CleanupJob::create()->run();

    }
}
