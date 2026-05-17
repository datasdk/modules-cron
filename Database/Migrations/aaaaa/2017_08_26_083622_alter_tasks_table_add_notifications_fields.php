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
            $table->string('notification_phone_number')->nullable()->after('notification_email_address');
            $table->string('notification_slack_webhook')->nullable()->after('notification_phone_number');
        });
    }

    public function down()
    {

        if (Schema::hasTable('cron_tasks')) 
        Schema::table('cron_tasks', function (Blueprint $table) {
            $table->dropColumn('notification_phone_number');
            $table->dropColumn('notification_slack_webhook');
        });
    }
};
