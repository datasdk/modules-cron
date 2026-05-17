<?php

namespace Modules\Cron\Contracts;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;  // Import Artisan
use Throwable;

abstract class QueueRetryCommand extends Command
{
    public function handle()
    {
        try {
            // Log besked i terminalen
            $this->info('Genforsøger alle fejlede queue-jobs...');
            
            // Kør Artisan kommandoen php artisan queue:retry all
            Artisan::call('queue:retry', ['id' => 'all']);

            // Bekræftelse i terminalen
            $this->info('Alle fejlede jobs er blevet genforsøgt.');
        } catch (Throwable $e) {
            // Håndter eventuelle fejl
            $this->error('Fejl ved genforsøg af jobs: ' . $e->getMessage());
        }
    }
}
