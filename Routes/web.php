<?php

use Illuminate\Support\Facades\Route;



Route::group([
    'prefix' => 'cron',
    'as' => 'cron.',
], function () {
  
    Route::resource('tasks', "CronTaskController");
    

});