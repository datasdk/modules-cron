<?php

namespace Modules\Cron\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Modules\Cron\Events\PrepareCronJobs;
use Modules\Cron\Jobs\BaseJobProcess;
//use Modules\Cron\Models\CronModels;
use Illuminate\Support\Facades\Schema;
use Modules\Cron\Services\TotemCommandService;

trait HasCronJob
{

    public static function registerCronJob(array $commands)
    {
     
        return TotemCommandService::addCommands($commands);

    }
        
    
    public function markAsSent()
    {
        $this->update([
            'status' => 'sent',
            'sent' => now(),
            'errors' => null
        ]);

        return $this;
    }


    public function markAsScheduled()
    {
        $this->update([
            'status' => 'scheduled',
            'sent' => null,
            'errors' => null
        ]);

        return $this;
    }


    public function markAsProcessing()
    {
        $this->update([
            'status' => 'processing',
            'sent' => null,
            'errors' => null
        ]);

        return $this;
    }


    public function markAsError(string $error)
    {
        $this->update([
            'status' => 'errors',
            'sent' => null,
            'errors' => $error
        ]);

        Log::alert("Processing failed: " . $error);

        return $this;
    }

}
