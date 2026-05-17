<?php

namespace Modules\Cron\Contracts;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;  // Import Artisan
use Throwable;

abstract class QueueRestartCommand extends Command
{
    public function handle()
    {
        try {
            // Log besked i terminalen
            $this->info('Genstarter alle queue-workers...');
            
            // Kør Artisan kommandoen php artisan queue:restart
            Artisan::call('queue:restart');

            // Bekræftelse i terminalen
            $this->info('Alle queue-workers er blevet genstartet.');
        } catch (Throwable $e) {
            // Håndter eventuelle fejl
            $this->error('Fejl ved genstart af queue-workers: ' . $e->getMessage());
        }
    }
}
