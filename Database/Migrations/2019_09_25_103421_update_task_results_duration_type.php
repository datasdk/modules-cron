<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * 1️⃣ Create cron_task_results table if it doesn't exist
         */
        if (!Schema::hasTable('cron_task_results')) {
            Schema::create('cron_task_results', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('task_id');
                $table->timestamp('ran_at')->useCurrent();
                $table->string('duration');
                $table->longText('result');
                $table->timestamps();
            });
        }

        /**
         * 2️⃣ Add indexes and foreign keys for cron_task_results
         */
        if (Schema::hasTable('cron_task_results')) {
            Schema::table('cron_task_results', function (Blueprint $table) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableIndexes($table->getTable());

                if (Schema::hasColumn('cron_task_results', 'task_id') && !array_key_exists('task_results_task_id_idx', $indexes)) {
                    $table->index('task_id', 'task_results_task_id_idx');
                }

                if (Schema::hasColumn('cron_task_results', 'ran_at') && !array_key_exists('task_results_ran_at_idx', $indexes)) {
                    $table->index('ran_at', 'task_results_ran_at_idx');
                }

                if (Schema::hasColumn('cron_task_results', 'task_id') && !array_key_exists('task_id_fk', $indexes)) {
                    $table->foreign('task_id', 'task_id_fk')
                          ->references('id')
                          ->on('cron_tasks');
                }

                if (Schema::hasColumn('cron_task_results', 'created_at') && !array_key_exists('cron_task_results_created_at_index', $indexes)) {
                    $table->index('created_at');
                }
            });
        }

        /**
         * 3️⃣ Add indexes and foreign keys for cron_task_frequencies
         */
        if (Schema::hasTable('cron_task_frequencies')) {
            Schema::table('cron_task_frequencies', function (Blueprint $table) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableIndexes($table->getTable());

                if (Schema::hasColumn('cron_task_frequencies', 'task_id') && !array_key_exists('task_frequencies_task_id_idx', $indexes)) {
                    $table->index('task_id', 'task_frequencies_task_id_idx');
                }

                if (Schema::hasColumn('cron_task_frequencies', 'task_id') && !array_key_exists('task_frequencies_task_id_fk', $indexes)) {
                    $table->foreign('task_id', 'task_frequencies_task_id_fk')
                          ->references('id')
                          ->on('cron_tasks');
                }
            });
        }

        /**
         * 4️⃣ Add indexes for cron_tasks safely
         */
        if (Schema::hasTable('cron_tasks')) {
            Schema::table('cron_tasks', function (Blueprint $table) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableIndexes($table->getTable());

                $indexMap = [
                    'is_active' => 'tasks_is_active_idx',
                    'dont_overlap' => 'tasks_dont_overlap_idx',
                    'run_in_maintenance' => 'tasks_run_in_maintenance_idx',
                    'run_on_one_server' => 'tasks_run_on_one_server_idx',
                    'auto_cleanup_num' => 'tasks_auto_cleanup_num_idx',
                    'auto_cleanup_type' => 'tasks_auto_cleanup_type_idx',
                ];

                foreach ($indexMap as $column => $name) {
                    if (Schema::hasColumn('cron_tasks', $column) && !array_key_exists($name, $indexes)) {
                        $table->index($column, $name);
                    }
                }
            });
        }

        /**
         * 5️⃣ Migrate duration column to decimal(24,14)
         */
        $this->migrateDurationValues(true);
    }

    public function down(): void
    {
        /**
         * Reverse duration migration
         */
        $this->migrateDurationValues(false);

        /**
         * Drop indexes and foreign keys safely
         */
        if (Schema::hasTable('cron_task_results')) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                Schema::table('cron_task_results', function (Blueprint $table) {
                    if (Schema::hasColumn('cron_task_results', 'task_id')) {
                        $table->dropForeign('task_id_fk');
                    }
                });
            }

            Schema::table('cron_task_results', function (Blueprint $table) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableIndexes($table->getTable());

                if (array_key_exists('task_results_task_id_idx', $indexes)) $table->dropIndex('task_results_task_id_idx');
                if (array_key_exists('task_results_ran_at_idx', $indexes)) $table->dropIndex('task_results_ran_at_idx');
                if (array_key_exists('cron_task_results_created_at_index', $indexes)) $table->dropIndex('cron_task_results_created_at_index');
            });
        }

        if (Schema::hasTable('cron_task_frequencies')) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                Schema::table('cron_task_frequencies', function (Blueprint $table) {
                    if (Schema::hasColumn('cron_task_frequencies', 'task_id')) {
                        $table->dropForeign('task_frequencies_task_id_fk');
                    }
                });
            }

            Schema::table('cron_task_frequencies', function (Blueprint $table) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableIndexes($table->getTable());

                if (array_key_exists('task_frequencies_task_id_idx', $indexes)) $table->dropIndex('task_frequencies_task_id_idx');
            });
        }

        if (Schema::hasTable('cron_tasks')) {
            Schema::table('cron_tasks', function (Blueprint $table) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableIndexes($table->getTable());

                $indexMap = [
                    'tasks_is_active_idx',
                    'tasks_dont_overlap_idx',
                    'tasks_run_in_maintenance_idx',
                    'tasks_run_on_one_server_idx',
                    'tasks_auto_cleanup_num_idx',
                    'tasks_auto_cleanup_type_idx',
                ];

                foreach ($indexMap as $name) {
                    if (array_key_exists($name, $indexes)) {
                        $table->dropIndex($name);
                    }
                }
            });
        }

        /**
         * Drop cron_task_results table
         */
        if (Schema::hasTable('cron_task_results')) {
            Schema::dropIfExists('cron_task_results');
        }
    }

    /**
     * Migrate duration column safely
     */
    private function migrateDurationValues(bool $toFloat = true): void
    {
        if (!Schema::hasTable('cron_task_results')) return;

        // Rename old column
        if (Schema::hasColumn('cron_task_results', 'duration')) {
            Schema::table('cron_task_results', function (Blueprint $table) {
                $table->renameColumn('duration', 'duration_old');
            });
        }

        // Create new column
        if (!Schema::hasColumn('cron_task_results', 'duration')) {
            Schema::table('cron_task_results', function (Blueprint $table) use ($toFloat) {
                if ($toFloat) {
                    $table->decimal('duration', 24, 14)->default(0);
                } else {
                    $table->string('duration')->default('');
                }
            });
        }

        // Copy values
        DB::table('cron_task_results')
            ->select('id', 'duration_old')
            ->chunkById(100, function ($rows) use ($toFloat) {
                foreach ($rows as $row) {
                    DB::table('cron_task_results')
                        ->where('id', $row->id)
                        ->update([
                            'duration' => $toFloat
                                ? (float) $row->duration_old
                                : (string) $row->duration_old,
                        ]);
                }
            });

        // Drop old column
        if (Schema::hasColumn('cron_task_results', 'duration_old')) {
            Schema::table('cron_task_results', function (Blueprint $table) {
                $table->dropColumn('duration_old');
            });
        }
    }
};
