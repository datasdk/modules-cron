<?php

namespace Modules\Cron\Contracts;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;  // Import Artisan
use Throwable;

abstract class QueueClearCommand extends Command
{
    protected $signature = 'queue:clear {queue}';

    public function handle()
    {
        try {
            $queueName = $this->argument('queue');
            // Log besked i terminalen
            $this->info('Rydder queue: ' . $queueName);
            
            // Kør Artisan kommandoen php artisan queue:clear
            Artisan::call('queue:clear', ['queue' => $queueName]);

            // Bekræftelse i terminalen
            $this->info('Queue ' . $queueName . ' er blevet ryddet.');
        } catch (Throwable $e) {
            // Håndter eventuelle fejl
            $this->error('Fejl ved rydning af queue ' . $queueName . ': ' . $e->getMessage());
        }
    }
}
