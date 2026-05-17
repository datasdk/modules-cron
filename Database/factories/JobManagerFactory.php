<?php

namespace Modules\Cron\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Cron\Models\JobManager;

class JobManagerFactory extends Factory
{
    protected $model = JobManager::class;

    public function definition()
    {
        return [
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
