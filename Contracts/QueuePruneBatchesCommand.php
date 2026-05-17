<?php

namespace Modules\Cron\Contracts;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;  // Import Artisan
use Throwable;

abstract class QueuePruneBatchesCommand extends Command
{
    public function handle()
    {
        try {
            // Log besked i terminalen
            $this->info('Pruner gamle batches...');
            
            // Kør Artisan kommandoen php artisan queue:prune-batches
            Artisan::call('queue:prune-batches');

            // Bekræftelse i terminalen
            $this->info('Gamle batch-jobs er blevet ryddet.');
        } catch (Throwable $e) {
            // Håndter eventuelle fejl
            $this->error('Fejl ved prune af batch-jobs: ' . $e->getMessage());
        }
    }
}
