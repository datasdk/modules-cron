<?php

namespace Modules\Cron\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;
use Modules\Firebase\Models\Notifications;
use Modules\Cron\Contracts\CronJobInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Carbon\Carbon;
use Exception;

class BaseJobProcess implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $timeout = 60;
    public $tries = 10;
    public $backoff = [900, 3600, 10800, 18000];
    public $failOnTimeout = true;


    protected $model;


    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    public function uniqueId()
    {
        return uniqid();
    }


    public function handle()
    {

        try {

            $model = $this->model;

            if (!$model) {
                throw new Exception("Ingen model fundet.");
            }


            if (!$model instanceof CronJobInterface) {
                throw new Exception("Model af typen " . get_class($model) . " implementerer ikke CronJobInterface.");
            }


            $model = $model->fresh();


            if (!$model) {

                throw new Exception("Model findes ikke.");

            }


            /*
            if (method_exists($model, 'trashed') && $model->trashed()) {

                throw new Exception("Model med ID {$model->id} er markeret som slettet.");

            }
                */


            if (isset($model->send_after) && Carbon::parse($model->send_after)->gt(now())) {

                $delayInSeconds = Carbon::parse($model->send_after)->diffInSeconds(now());

           
                // Udskyd jobbet (kan evt. tilføje ->delay() i dispatch hvis nødvendigt)
                $this->release($delayInSeconds);
                return;

            }


            $model->markAsSent();

            $model->onJobSucceed($model);


        } catch (Throwable $exception) {

            Log::error("Job fejlede i handle(): " . static::class, [
                'model_id' => $this->model->id ?? null,
                'error' => $exception->getMessage(),
            ]);

            $this->fail($exception);
        }

    }


    public function failed(Throwable $ex): void
    {

        Log::error("Job failed() kaldt: " . static::class, [
            'model_id' => $this->model->id ?? null,
            'error' => $ex->getMessage(),
            'attempt' => $this->attempts(),
        ]);


        if ($this->attempts() < $this->tries) {
            // Release jobbet til senere forsøg
        //    $this->release(60); // f.eks. 1 minut forsinkelse
        }

    }


    public function middleware()
    {
        return [
        //    (new WithoutOverlapping())->releaseAfter(10)
        ];
    }
  

  

}
