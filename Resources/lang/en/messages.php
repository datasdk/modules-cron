<?php

return [
    'success' => [
        'create' => 'Cron task created successfully.',
        'update' => 'Cron task updated successfully.',
        'delete' => 'Cron task deleted successfully.',
        'activate' => 'Cron task activated successfully.',
        'deactivate' => 'Cron task deactivated successfully.',
        'execute' => 'Cron task executed successfully.',
    ],
    
    'errors' => [
        'create' => 'Failed to create cron task.',
        'update' => 'Failed to update cron task.',
        'delete' => 'Failed to delete cron task.',
        'not_found' => 'Cron task not found.',
        'execute' => 'Failed to execute cron task.',
    ],
    
    'validation' => [
        'description_required' => 'Description is required.',
        'command_required' => 'Command is required.',
        'expression_required' => 'Cron expression is required.',
        'expression_invalid' => 'Cron expression is invalid.',
        'timezone_invalid' => 'Timezone is invalid.',
    ],
    
    'labels' => [
        'description' => 'Description',
        'command' => 'Command',
        'parameters' => 'Parameters',
        'expression' => 'Cron Expression',
        'timezone' => 'Timezone',
        'is_active' => 'Active',
        'dont_overlap' => "Don't Overlap",
        'run_in_maintenance' => 'Run in Maintenance Mode',
        'run_on_one_server' => 'Run on One Server',
        'run_in_background' => 'Run in Background',
        'notification_email_address' => 'Email Notification',
        'notification_phone_number' => 'SMS Notification',
        'notification_slack_webhook' => 'Slack Webhook',
        'auto_cleanup_type' => 'Auto Cleanup Type',
        'auto_cleanup_num' => 'Number to Keep',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'last_ran_at' => 'Last Ran',
        'average_runtime' => 'Average Runtime',
        'upcoming' => 'Next Run',
        'status' => 'Status',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'success' => 'Success',
        'failed' => 'Failed',
    ],
    
    'actions' => [
        'create' => 'Create Cron Task',
        'edit' => 'Edit Cron Task',
        'delete' => 'Delete Cron Task',
        'view' => 'View Cron Task',
        'execute' => 'Execute Cron Task',
        'activate' => 'Activate Cron Task',
        'deactivate' => 'Deactivate Cron Task',
        'back_to_list' => 'Back to List',
    ],
    
    'confirmation' => [
        'delete' => 'Are you sure you want to delete this cron task?',
        'execute' => 'Are you sure you want to execute this cron task now?',
        'activate' => 'Are you sure you want to activate this cron task?',
        'deactivate' => 'Are you sure you want to deactivate this cron task?',
    ],
    
    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'running' => 'Running',
        'completed' => 'Completed',
        'failed' => 'Failed',
        'pending' => 'Pending',
    ],
    
    'frequencies' => [
        'every_minute' => 'Every Minute',
        'every_five_minutes' => 'Every Five Minutes',
        'every_ten_minutes' => 'Every Ten Minutes',
        'every_fifteen_minutes' => 'Every Fifteen Minutes',
        'every_thirty_minutes' => 'Every Thirty Minutes',
        'hourly' => 'Hourly',
        'daily' => 'Daily',
        'weekly' => 'Weekly',
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
    ],
    
    'cleanup' => [
        'none' => 'None',
        'results' => 'Keep Last',
        'days' => 'Keep Days',
        'description' => 'Number of results/days to keep',
    ],
    
    'table' => [
        'no_tasks' => 'No cron tasks found.',
        'no_results' => 'No execution results.',
    ],
];