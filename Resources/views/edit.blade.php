@extends('layouts.app')

@section('title')
Edit Cron Task
@stop

@section('content')
<div>
    <div class="content-header mb-3">
        <h1>Edit Cron Task</h1>
    </div>

    <form method="POST" action="{{ route('cron.tasks.update', $task->id) }}">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <table class="table mb-4">
            <thead>
                <tr><th colspan="2">Basic Information</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td width="200"><label for="description" class="form-label mb-0">Description *</label></td>
                    <td>
                        <input type="text" class="form-control" id="description" name="description" 
                               value="{{ old('description', $task->description) }}" required>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td><label for="command" class="form-label mb-0">Command *</label></td>
                    <td>
                        <select class="form-control" id="command" name="command" required>
                            @foreach($commands as $command)
                                <option value="{{ $command['name'] }}" 
                                        {{ $task->command == $command['name'] ? 'selected' : '' }}>
                                    {{ $command['name'] }} - {{ $command['description'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('command')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td><label for="parameters" class="form-label mb-0">Parameters</label></td>
                    <td>
                        <input type="text" class="form-control" id="parameters" name="parameters" 
                               value="{{ old('parameters', $task->parameters) }}" 
                               placeholder="--option=value argument">
                        <div class="text-muted small mt-1">Command line arguments and options</div>
                    </td>
                </tr>
                <tr>
                    <td><label for="expression" class="form-label mb-0">Cron Expression *</label></td>
                    <td>
                        <input type="text" class="form-control" id="expression" name="expression" 
                               value="{{ old('expression', $task->expression) }}">
                        <div class="text-muted small mt-1">e.g. * * * * * (every minute)</div>
                        @error('expression')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <td><label for="timezone" class="form-label mb-0">Timezone</label></td>
                    <td>
                        <select class="form-control" id="timezone" name="timezone">
                            @foreach($timezones as $timezone)
                                <option value="{{ $timezone }}" 
                                        {{ $task->timezone == $timezone ? 'selected' : '' }}>
                                    {{ $timezone }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Frequencies -->
        <table class="table mb-4">
            <thead>
                <tr><th colspan="2">Frequencies</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">
                        <div class="row">
                            @foreach($frequencies as $frequency)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="frequencies[]" 
                                               value="{{ $frequency['interval'] }}" id="freq_{{ $frequency['interval'] }}"
                                               {{ in_array($frequency['interval'], $task->frequencies->pluck('interval')->toArray()) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="freq_{{ $frequency['interval'] }}">
                                            {{ $frequency['label'] }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Options -->
        <table class="table mb-4">
            <thead>
                <tr><th colspan="2">Options</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td width="200">Status</td>
                    <td>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1"
                                   {{ $task->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Task is active</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Overlap Protection</td>
                    <td>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="dont_overlap" id="dont_overlap" value="1"
                                   {{ $task->dont_overlap ? 'checked' : '' }}>
                            <label class="form-check-label" for="dont_overlap">Don't overlap with another instance of this task</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Maintenance Mode</td>
                    <td>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="run_in_maintenance" id="run_in_maintenance" value="1"
                                   {{ $task->run_in_maintenance ? 'checked' : '' }}>
                            <label class="form-check-label" for="run_in_maintenance">Run in maintenance mode</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Single Server</td>
                    <td>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="run_on_one_server" id="run_on_one_server" value="1"
                                   {{ $task->run_on_one_server ? 'checked' : '' }}>
                            <label class="form-check-label" for="run_on_one_server">Run on a single server</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Background Execution</td>
                    <td>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="run_in_background" id="run_in_background" value="1"
                                   {{ $task->run_in_background ? 'checked' : '' }}>
                            <label class="form-check-label" for="run_in_background">Run in the background</label>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Notifications -->
        <table class="table mb-4">
            <thead>
                <tr><th colspan="2">Notifications</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td width="200"><label for="notification_email_address" class="form-label mb-0">Email Notification</label></td>
                    <td>
                        <input type="email" class="form-control" id="notification_email_address" name="notification_email_address" 
                               value="{{ old('notification_email_address', $task->notification_email_address) }}">
                    </td>
                </tr>
                <tr>
                    <td><label for="notification_phone_number" class="form-label mb-0">SMS Notification</label></td>
                    <td>
                        <input type="text" class="form-control" id="notification_phone_number" name="notification_phone_number" 
                               value="{{ old('notification_phone_number', $task->notification_phone_number) }}">
                    </td>
                </tr>
                <tr>
                    <td><label for="notification_slack_webhook" class="form-label mb-0">Slack Webhook</label></td>
                    <td>
                        <input type="text" class="form-control" id="notification_slack_webhook" name="notification_slack_webhook" 
                               value="{{ old('notification_slack_webhook', $task->notification_slack_webhook) }}">
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Auto Cleanup -->
        <table class="table mb-4">
            <thead>
                <tr><th colspan="2">Auto Cleanup</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td width="200">Cleanup Type</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="auto_cleanup_type" id="cleanup_none" value=""
                                   {{ !$task->auto_cleanup_type ? 'checked' : '' }}>
                            <label class="form-check-label" for="cleanup_none">None</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="auto_cleanup_type" id="cleanup_results" value="results"
                                   {{ $task->auto_cleanup_type == 'results' ? 'checked' : '' }}>
                            <label class="form-check-label" for="cleanup_results">Keep last</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="auto_cleanup_type" id="cleanup_days" value="days"
                                   {{ $task->auto_cleanup_type == 'days' ? 'checked' : '' }}>
                            <label class="form-check-label" for="cleanup_days">Keep days</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><label for="auto_cleanup_num" class="form-label mb-0">Number to Keep</label></td>
                    <td>
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <input type="number" class="form-control" style="width: 100px;" id="auto_cleanup_num" name="auto_cleanup_num" 
                                       value="{{ old('auto_cleanup_num', $task->auto_cleanup_num) }}" min="0">
                            </div>
                            <div class="col">
                                <div class="text-muted small">Number of results/days to keep</div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

       
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Update Task
        </button> 
                
        <a href="{{ route('cron.tasks.index') }}" class="btn btn-secondary me-2">
            <i class="bi bi-arrow-left"></i> Tilbage
        </a>
          
    </form>

    <!-- Hidden Delete Form -->
    <form id="delete-form" action="{{ route('cron.tasks.destroy', $task->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<style>
.table {
    background-color: white;
    border: 1px solid #dee2e6;
}
.table td {
    vertical-align: middle;
    padding: 0.75rem;
}
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    padding: 0.75rem;
    border-bottom: 2px solid #dee2e6;
}
.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>

<script>
// Auto cleanup type change handler
document.querySelectorAll('input[name="auto_cleanup_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const cleanupNum = document.getElementById('auto_cleanup_num');
        if (this.value === '') {
            cleanupNum.disabled = true;
            cleanupNum.value = 0;
        } else {
            cleanupNum.disabled = false;
            if (!cleanupNum.value || cleanupNum.value == 0) {
                cleanupNum.value = 30;
            }
        }
    });
});

// Initialize cleanup number field state
document.addEventListener('DOMContentLoaded', function() {
    const cleanupType = document.querySelector('input[name="auto_cleanup_type"]:checked');
    const cleanupNum = document.getElementById('auto_cleanup_num');
    if (cleanupType && cleanupType.value === '') {
        cleanupNum.disabled = true;
    }
});
</script>
@endsection