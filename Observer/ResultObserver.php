<?php

namespace Modules\Cron\Observer;

use Studio\Totem\Result;
use Illuminate\Support\Facades\Log;

class ResultObserver
{
    /**
     * Handle the Result "created" event.
     *
     * @param  \Studio\Totem\Result  $result
     * @return void
     */
    public function created(Result $result)
    {
        // Clean up old results for THIS specific task
        $this->cleanupOldResults($result->task_id);
    }

    /**
     * Cleanup old results for a specific task when total exceeds 100
     *
     * @param int $taskId
     * @return void
     */
    protected function cleanupOldResults(int $taskId)
    {
        try {
            // Count ONLY results for this specific task
            $totalCount = Result::where('task_id', $taskId)->count();
            
            // If we have more than 100 results for THIS task, delete the oldest ones
            if ($totalCount > 100) {
                // Calculate how many to delete (e.g., if 101, delete 1)
                $excessCount = $totalCount - 100;
                
                // Get IDs of oldest records for THIS task only
                $oldestIds = Result::where('task_id', $taskId)
                    ->orderBy('ran_at', 'asc') // Oldest first
                    ->orderBy('id', 'asc')     // For tie-breaking
                    ->limit($excessCount)
                    ->pluck('id');
                
                if ($oldestIds->isNotEmpty()) {
                    // Delete the oldest records
                    $deletedCount = Result::whereIn('id', $oldestIds)->delete();
                    
                
                }
            }
            
        } catch (\Exception $e) {
            Log::error("Failed to cleanup old cron results", [
                'task_id' => $taskId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle the Result "updated" event.
     *
     * @param  \Studio\Totem\Result  $result
     * @return void
     */
    public function updated(Result $result)
    {
        // Nothing needed on update
    }

    /**
     * Handle the Result "deleted" event.
     *
     * @param  \Studio\Totem\Result  $result
     * @return void
     */
    public function deleted(Result $result)
    {
        // Nothing needed on delete
    }

    /**
     * Handle the Result "forceDeleted" event.
     *
     * @param  \Studio\Totem\Result  $result
     * @return void
     */
    public function forceDeleted(Result $result)
    {
        // Nothing needed on force delete
    }
}