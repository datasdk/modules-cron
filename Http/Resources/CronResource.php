<?php

namespace Modules\Cron\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;



class CronResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request){
       
       
        $job = json_decode($this->payload);
       

        $content = $command = unserialize($job->data->command); 
        $delay = optional($content->delay)->toDateTimeString();
       

        return [
            "id" => $this->id,
            "uuid" => $job->uuid,
            "queue" => $this->queue,

            "reserved_at" => $this->reserved_at,
            "available_at" => $this->available_at,
            "available_at_date" => 
            date(config('app.date_format')." ".config('app.time_format'),$this->available_at),

            "data" => optional($content)->data,
            
            "attempts" => $this->attempts,
            "maxTries" => $job->maxTries,
            "failOnTimeout" => $job->failOnTimeout,
            "timeout" => $job->timeout,
            "retryUntil" => $job->retryUntil,
            
            

        ];
        
  
    }

    //{"uuid":"3dfd147b-0b30-4d99-87d9-63ed8d67f4fd","displayName":"App\\Jobs\\ProcessSmtp","job":"Illuminate\\Queue\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"App\\Jobs\\ProcessSmtp","command":"O:20:\"App\\Jobs\\ProcessSmtp\":11:{s:4:\"data\";a:4:{s:2:\"to\";s:13:\"info@datas.dk\";s:8:\"template\";s:34:\"lyngemetoden-mindset-opgave-dag-46\";s:7:\"send_at\";i:1699052400;s:6:\"params\";a:1:{s:9:\"firstname\";s:6:\"Kasper\";}}s:3:\"job\";N;s:10:\"connection\";s:8:\"database\";s:5:\"queue\";s:5:\"email\";s:15:\"chainConnection\";N;s:10:\"chainQueue\";N;s:19:\"chainCatchCallbacks\";N;s:5:\"delay\";O:13:\"Carbon\\Carbon\":3:{s:4:\"date\";s:26:\"2023-11-03 23:00:00.000000\";s:13:\"timezone_type\";i:1;s:8:\"timezone\";s:6:\"+00:00\";}s:11:\"afterCommit\";N;s:10:\"middleware\";a:0:{}s:7:\"chained\";a:0:{}}"}}
 

}
