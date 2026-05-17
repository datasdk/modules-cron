<?php

namespace Modules\Cron\Contracts;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;  // Import Artisan
use Throwable;

abstract class QueueFlushCommand extends Command
{
    public function handle()
    {
        try {
            // Log besked i terminalen
            $this->info('Rydder alle fejlede queue-jobs...');
            
            // Kør Artisan kommandoen php artisan queue:flush
            Artisan::call('queue:flush');

            // Bekræftelse i terminalen
            $this->info('Alle fejlede jobs er blevet ryddet.');
        } catch (Throwable $e) {
            // Håndter eventuelle fejl
            $this->error('Fejl ved flush af fejlede jobs: ' . $e->getMessage());
        }
    }
}
