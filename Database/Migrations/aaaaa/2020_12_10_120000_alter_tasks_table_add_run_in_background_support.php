<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('cron_tasks')) 
        Schema::table('cron_tasks', function (Blueprint $table) {
            $table->boolean('run_in_background')->default(false);
        });
    }

    public function down()
    {

        if (Schema::hasTable('cron_tasks')) 
        Schema::table('cron_tasks', function (Blueprint $table) {
            $table->dropColumn('run_in_background');
        });
    }
};
