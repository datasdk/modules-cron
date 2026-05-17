<?php

namespace Modules\Cron\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class ScheduleController extends Controller
{
    public static function runSchedules()
    {
        $exitCode = Artisan::call('schedule:run');

        return [
            'exitCode' => $exitCode,
            'output'   => Artisan::output(),
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
