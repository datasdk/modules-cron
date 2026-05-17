<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {

        if (!Schema::hasTable('cron_frequency_parameters')) 
        Schema::create('cron_frequency_parameters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('frequency_id');
            $table->string('name');
            $table->string('value');
            $table->timestamps();
        });
    }

    public function down()
    {

        if (Schema::hasTable('cron_frequency_parameters')) 
        Schema::dropIfExists('cron_frequency_parameters');
    }
};
