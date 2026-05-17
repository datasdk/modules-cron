<?php

namespace Modules\Cron\Contracts;

use Carbon\Carbon;

interface CronJobInterface
{

  

    public function markAsScheduled();

 
 
    public function markAsSent();



 
}
