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
            $table->integer('auto_cleanup_num')->default(0);
            $table->string('auto_cleanup_type', 20)->nullable();
        });
    }

    public function down()
    {
        if (Schema::hasTable('cron_tasks')) 
        Schema::table('cron_tasks', function (Blueprint $table) {
            $table->dropColumn('auto_cleanup_num');
            $table->dropColumn('auto_cleanup_type');
        });
    }
};
