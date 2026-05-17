<?php

namespace Modules\Cron\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class DatabaseScheduleApplicationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->authorization();
    }

    protected function authorization()
    {
        $this->gate();
    }

    protected function gate()
    {
        
        Gate::define('viewDatabaseSchedule', function ($user) {
            return $user->isAdmin();
        });

    }
}
