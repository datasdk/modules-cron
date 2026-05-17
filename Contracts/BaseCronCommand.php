<?php

namespace Modules\Cron\Contracts;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;
use Modules\Cron\Services\RunCronService;


abstract class BaseCronCommand extends Command
{

    protected $modelClass;

    protected $jobClass;


    public function handle()
    {

        try {


            $this->info('Dispatching jobs...');

            $success = $this->runCronJobs();


            if ($success) {

                $this->info('Jobs dispatched and queue run successfully.');

            } else {
                
                $this->info('No jobs were dispatched.');

            }


        } catch (\Throwable $e) {


            $this->error('Error during job dispatch: ' . $e->getMessage());

            Log::error('Error during job dispatch: ' . $e->getMessage());

            
        }

    }


    public function runCronJobs(): bool
    {

        $modelClass = $this->modelClass;

        $jobClass = $this->jobClass;


        try {

            $service = app(RunCronService::class);


            // Fetch pending records
            $records = $service->getPendingRecords($modelClass);


            $this->info("[BaseCronCommand] Found {$records->count()} records to process.");


            if ($records->isEmpty()) {

                $this->info('[BaseCronCommand] No records found for processing.');

                return false;

            }


            foreach ($records as $record) {

                // Dispatch jobs for each record
                $service->dispatchJob($record, $jobClass);

                $this->info("Dispatched job ID: {$record->id} CLASS: " . get_class($record));

            }


            $this->info("[BaseCronCommand] Jobs dispatched to queue.");

            // Run worker
            $service->runQueueWorker();

            $this->info("[BaseCronCommand] queue:work completed.");


            return true;


        } catch (Throwable $e) {

            $this->error('[BaseCronCommand] Error during runCronJobs: ' . $e->getMessage());

            Log::error('[BaseCronCommand] Error during runCronJobs: ' . $e->getMessage());

            return false;
        }

    }
    
}
