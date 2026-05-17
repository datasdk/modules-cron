<?php

namespace Modules\Cron\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \Modules\Cron\Events\PrepareCronJobs::class => [
         
        ],

        /*
            use:

            Event::listen(PrepareCronJobs::class, EmailCronJob::class);

        */
    ];


}
