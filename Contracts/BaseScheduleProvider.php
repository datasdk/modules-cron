<?php

namespace Modules\Cron\Contracts;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

abstract class BaseScheduleProvider extends ServiceProvider
{
    /**
     * Boot schedules automatically.
     */
    public function boot()
    {
        $schedule = $this->app->make(Schedule::class);
        $this->schedule($schedule);
    }

    /**
     * Register commands with Artisan.
     */
    public function register()
    {
        $this->commands($this->commands());
    }

    /**
     * Return an array of commands for the module.
     *
     * @return array
     */
    abstract protected function commands(): array;

    /**
     * Define scheduled tasks.
     *
     * @param Schedule $schedule
     */
    abstract protected function schedule(Schedule $schedule);
}
