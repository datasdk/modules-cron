<?php

namespace Modules\Cron\Contracts;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;  // Import Artisan
use Throwable;

abstract class QueueWorkCommand extends Command
{
    protected $signature = 'queue:work {--queue=} {--timeout=30}';

    public function handle()
    {
        try {
            $queue = $this->option('queue');
            $timeout = $this->option('timeout');
            // Log besked i terminalen
            $this->info('Starter queue:work...');

            // Kør Artisan kommandoen php artisan queue:work
            Artisan::call('queue:work', [
                '--queue' => $queue,
                '--timeout' => $timeout,
            ]);

            // Bekræftelse i terminalen
            $this->info('Queue workers kører.');
        } catch (Throwable $e) {
            // Håndter eventuelle fejl
            $this->error('Fejl ved arbejdet med queue: ' . $e->getMessage());
        }
    }
}
