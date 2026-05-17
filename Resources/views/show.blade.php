@extends('layouts.app')

@section('title')
Task Details
@stop

@section('content')
<div>
    <div class="content-header mb-3">
        <h1>Task Details</h1>
    </div>

    <!-- Basic Information -->
    <table class="table mb-4">
        <thead>
            <tr><th colspan="2">Basic Information</th></tr>
        </thead>
        <tbody>
            <tr>
                <td width="200" class="text-muted">Description</td>
                <td>{{ Str::limit($task->description, 80) }}</td>
            </tr>
            <tr>
                <td class="text-muted">Command</td>
                <td><code>{{ $task->command }}</code></td>
            </tr>
            <tr>
                <td class="text-muted">Parameters</td>
                <td>{{ $task->parameters ?? "N/A" }}</td>
            </tr>
            <tr>
                <td class="text-muted">Cron Expression</td>
                <td><code>{{ $task->expression }}</code></td>
            </tr>
            <tr>
                <td class="text-muted">Timezone</td>
                <td>{{ $task->timezone }}</td>
            </tr>
            <tr>
                <td class="text-muted">Created At</td>
                <td>{{ $task->created_at->toDateTimeString() }}</td>
            </tr>
            <tr>
                <td class="text-muted">Updated At</td>
                <td>{{ $task->updated_at->toDateTimeString() }}</td>
            </tr>
            <tr>
                <td class="text-muted">Next Run</td>
                <td>{{ $task->upcoming }}</td>
            </tr>
            <tr>
                <td class="text-muted">Average Runtime</td>
                <td>{{ number_format($task->average_runtime, 2) }} seconds</td>
            </tr>
        </tbody>
    </table>

    <!-- Status & Options -->
    <table class="table mb-4">
        <thead>
            <tr><th colspan="2">Status & Options</th></tr>
        </thead>
        <tbody>
            <tr>
                <td width="200" class="text-muted">Active Status</td>
                <td>
                    @if($task->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </td>
            </tr>
            @if($task->dont_overlap)
            <tr>
                <td class="text-muted">Overlap Protection</td>
                <td>
                    <span class="badge bg-info">Enabled</span>
                    Doesn't overlap with another instance of this task
                </td>
            </tr>
            @endif
            @if($task->run_in_maintenance)
            <tr>
                <td class="text-muted">Maintenance Mode</td>
                <td>
                    <span class="badge bg-info">Enabled</span>
                    Runs in maintenance mode
                </td>
            </tr>
            @endif
            @if($task->run_on_one_server)
            <tr>
                <td class="text-muted">Single Server</td>
                <td>
                    <span class="badge bg-info">Enabled</span>
                    Runs on a single server
                </td>
            </tr>
            @endif
            @if($task->run_in_background)
            <tr>
                <td class="text-muted">Background Execution</td>
                <td>
                    <span class="badge bg-info">Enabled</span>
                    Runs in the background
                </td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Notifications -->
    <table class="table mb-4">
        <thead>
            <tr><th colspan="2">Notifications</th></tr>
        </thead>
        <tbody>
            <tr>
                <td width="200" class="text-muted">Email Notification</td>
                <td>{{ $task->notification_email_address ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="text-muted">SMS Notification</td>
                <td>{{ $task->notification_phone_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="text-muted">Slack Webhook</td>
                <td>
                    @if($task->notification_slack_webhook)
                        <span class="text-truncate d-inline-block" style="max-width: 300px;">
                            {{ $task->notification_slack_webhook }}
                        </span>
                    @else
                        N/A
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Auto Cleanup -->
    @if($task->auto_cleanup_type)
    <table class="table mb-4">
        <thead>
            <tr><th colspan="2">Auto Cleanup</th></tr>
        </thead>
        <tbody>
            <tr>
                <td width="200" class="text-muted">Cleanup Type</td>
                <td>
                    Keep {{ $task->auto_cleanup_num }} {{ $task->auto_cleanup_type == 'results' ? 'results' : 'days' }}
                </td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Execution Results -->
    <table class="table mb-4">
        <thead>
            <tr><th colspan="3">Execution Results</th></tr>
            <tr>
                <th>Executed At</th>
                <th>Duration</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($results = $task->results()->orderByDesc('ran_at')->paginate(10) as $result)
            <tr>
                <td>{{ $result->ran_at->toDateTimeString() }}</td>
                <td>{{ number_format($result->duration / 1000, 2) }} seconds</td>
                <td>
                    <span class="badge bg-{{ $result->status == 'success' ? 'success' : 'danger' }}">
                        {{ $result->status }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center text-muted py-3">
                    Not executed yet.
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($results->hasPages())
        <tfoot>
            <tr>
                <td colspan="3" class="bg-light">
                    <div class="d-flex justify-content-center">
                        {{ $results->links() }}
                    </div>
                </td>
            </tr>
        </tfoot>
        @endif
    </table>

    <!-- Form Actions -->
    <div class="d-flex justify-content-between mt-4">
        <div>
            <a href="{{ route('cron.tasks.edit', $task->id) }}" class="btn btn-primary me-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <button type="button" class="btn btn-danger" onclick="if(confirm('Are you sure you want to delete this task?')) { document.getElementById('delete-form').submit(); }">
                <i class="bi bi-trash"></i> Delete
            </button>
        </div>
        <div>
            <a href="{{ route('cron.tasks.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

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
.table thead tr:first-child th {
    background-color: #e9ecef;
    font-size: 1.1rem;
}
.table tfoot td {
    background-color: #f8f9fa;
}
.text-muted {
    color: #6c757d !important;
}
code {
    color: #d63384;
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
}
.badge {
    font-size: 0.75em;
    padding: 0.35em 0.65em;
}
</style>
@endsection