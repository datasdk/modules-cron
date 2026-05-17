<?php

namespace Modules\Cron\Database\factories;

use Modules\Cron\Models\FailedJobManager;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FailedJobManagerFactory extends Factory
{
    protected $model = FailedJobManager::class;

    public function definition()
    {
        return [
            'uuid'       => Str::uuid(),
            'connection' => $this->faker->randomElement(['database', 'redis', 'sqs']),
            'queue'      => $this->faker->word(),
            'payload'    => json_encode(['job' => 'ExampleJob', 'data' => ['id' => $this->faker->randomNumber()]]),
            'exception'  => $this->faker->text(200),
            'failed_at'  => now(),
        ];
    }
}
