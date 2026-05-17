<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {

         if (!Schema::hasTable('cron_task_frequencies')) {

            Schema::create('cron_task_frequencies', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('task_id');
                $table->string('label');
                $table->string('interval');
                $table->timestamps();
            });

        }

    }

    public function down()
    {
        if (Schema::hasTable('cron_task_frequencies')) {
            
            Schema::dropIfExists('cron_task_frequencies');

        }
    }
};
