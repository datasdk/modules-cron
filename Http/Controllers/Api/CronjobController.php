<?php

namespace Modules\Cron\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Modules\Cron\Events\PrepareCronJobs;


use Settings;
use Exception;


class CronjobController extends Controller
{

    public static function runJobs(Request $req)
    {

        $scheduleExitCode = null;
        $exitCode = null;


        try {


            if ($req->boolean("prepare_schedules")) {
                $scheduleExitCode = Artisan::call('schedule:run');
           
            }


            $queuesPriority = [
                "very-high",
                "high",
                "medium",
                "default",
                "low",
                "very-low",
            ];


    
            $exitCode = Artisan::call('queue:work', [
                'connection' => 'database',
                '--queue' => implode(",", $queuesPriority),
                '--timeout' => 60,
                '--tries' => 3,
                '--stop-when-empty' => true,
            ]);


         


            Settings::set('public.cron.lastCronRun', now()->format('Y-m-d H:i:s'));


            return response()->json([
                'message' => 'Cron jobs dispatched',
                'models_count' => null, // fjern eller definér korrekt
                'output' => Artisan::output(),
                'exit_code' => $exitCode,
                'schedules' => $scheduleExitCode,
            ]);


        } catch (Exception $e) {


            Log::error("Error running cron jobs", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);


            return response()->json([
                'message' => 'Error running cron jobs',
                'error' => $e->getMessage(),
            ], 500);

        }

    }


    public static function log($jobs)
    {
        $result = [
            "msg" => "Cronjob run",
            "jobs" => $jobs,
            "date" => now()->toIso8601String()
        ];

        Log::notice($result);

        return $result;
    }

}
