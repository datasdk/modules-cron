<?php


use Illuminate\Http\Request;

use Orion\Facades\Orion;



Route::group([
    "as" => "api.cron.",
    "prefix" => "cron"
],function(){

    Route::match(['GET','POST'],'/run-jobs',"Api\CronjobController@runJobs")->name("run-jobs");

    Route::match(['GET','POST'],'/run-schedules',"Api\ScheduleController@runSchedules")->name("run-schedules");


    /*

    Route::match(['GET','POST'],'/cron',"Api\CronjobController@runJobs")->name("run-jobs");

    Route::post('/cron/failed',"Api\CronjobController@runFailedJobs")->name("run-failed-jobs");

    Route::post('/cron/restart',"Api\CronjobController@runRestart")->name("run-failed-jobs");


    Route::group([
        'middleware' => 'auth:api'
    ], function ($router) {
    
        Rou te::get('/isCronRunning',"Api\CronjobController@isCronRunning")->name("is-running");

        Orion::resource("jobs","Api\JobManagerController");

        Orion::resource("failed_jobs","Api\FailedJobManagerController");   

    });
    */

});


