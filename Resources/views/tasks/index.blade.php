@extends('cron::layouts.app')



@section('content')

<div id="root" class="container-fluid py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Scheduled Tasks</h4>
            <p class="text-muted mb-0">Manage your cron tasks and schedules</p>
        </div>
        <a class="btn btn-primary" href="{{ route('totem.task.create') }}">
            <i class="fas fa-plus me-1"></i> New Task
        </a>
    </div>

    <!-- Tasks Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr class="bg-light">
                            <th class="border-0">{!! \Studio\Totem\Helpers\columnSort('Description', 'description') !!}</th>
                            <th class="border-0">{!! \Studio\Totem\Helpers\columnSort('Avg Runtime', 'average_runtime') !!}</th>
                            <th class="border-0">{!! \Studio\Totem\Helpers\columnSort('Last Run', 'last_ran_at') !!}</th>
                            <th class="border-0">Next Run</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr is="task-row"
                                :data-task="{{$task}}"
                                showHref="{{ route('totem.task.view', ['totemTask' => $task]) }}"
                                executeHref="{{ route('totem.task.execute', ['totemTask' => $task]) }}"
                                class="align-middle">
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="mb-3">
                                            <i class="fas fa-tasks fa-3x text-light"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">No Tasks Found</h5>
                                        <p class="text-muted mb-0">Create your first scheduled task to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer Actions -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
            {{ $tasks->links('pagination::bootstrap-5') }}
        </div>
        <div class="d-flex gap-2">
            <import-button url="{{ route('totem.tasks.import') }}" class="mr-3">
                <i class="fas fa-upload me-1"></i> Import
            </import-button>
            <a href="{{ route('totem.tasks.export') }}">
                <i class="fas fa-download me-1"></i> Export
            </a>
        </div>
    </div>

</div>

@endsection