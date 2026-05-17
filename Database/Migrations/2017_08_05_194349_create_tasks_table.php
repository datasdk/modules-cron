<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Opret tabel hvis den ikke findes
        if (!Schema::hasTable('cron_tasks')) {
            Schema::create('cron_tasks', function (Blueprint $table) {
                $table->increments('id');
                $table->string('description');
                $table->string('command');
                $table->string('parameters')->nullable();
                $table->string('expression')->nullable();
                $table->string('timezone')->default('UTC');
                $table->boolean('is_active')->default(true);
                $table->boolean('dont_overlap')->default(false);
                $table->boolean('run_in_maintenance')->default(false);
                $table->string('notification_email_address')->nullable();
                $table->timestamps();
            });
        }

        // Tilføj kolonner hvis de ikke allerede findes
        Schema::table('cron_tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('cron_tasks', 'notification_phone_number')) {
                $table->string('notification_phone_number')->nullable()->after('notification_email_address');
            }
            if (!Schema::hasColumn('cron_tasks', 'notification_slack_webhook')) {
                $table->string('notification_slack_webhook')->nullable()->after('notification_phone_number');
            }
            if (!Schema::hasColumn('cron_tasks', 'auto_cleanup_num')) {
                $table->integer('auto_cleanup_num')->default(0);
            }
            if (!Schema::hasColumn('cron_tasks', 'auto_cleanup_type')) {
                $table->string('auto_cleanup_type', 20)->nullable();
            }
            if (!Schema::hasColumn('cron_tasks', 'run_on_one_server')) {
                $table->boolean('run_on_one_server')->default(false);
            }
            if (!Schema::hasColumn('cron_tasks', 'run_in_background')) {
                $table->boolean('run_in_background')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('cron_tasks')) {
            Schema::table('cron_tasks', function (Blueprint $table) {
                if (Schema::hasColumn('cron_tasks', 'notification_phone_number')) {
                    $table->dropColumn('notification_phone_number');
                }
                if (Schema::hasColumn('cron_tasks', 'notification_slack_webhook')) {
                    $table->dropColumn('notification_slack_webhook');
                }
                if (Schema::hasColumn('cron_tasks', 'auto_cleanup_num')) {
                    $table->dropColumn('auto_cleanup_num');
                }
                if (Schema::hasColumn('cron_tasks', 'auto_cleanup_type')) {
                    $table->dropColumn('auto_cleanup_type');
                }
                if (Schema::hasColumn('cron_tasks', 'run_on_one_server')) {
                    $table->dropColumn('run_on_one_server');
                }
                if (Schema::hasColumn('cron_tasks', 'run_in_background')) {
                    $table->dropColumn('run_in_background');
                }
            });

            Schema::dropIfExists('cron_tasks');
        }
    }
};
