@extends('layouts.app')

@section('title')
Cron Tasks
@stop

@section('content')

    <!-- Cron Task Table -->

    <div class="pb-4 mb-5">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h5 class="mb-0">Cron Tasks</h5>

            <a href="{{ route('cron.tasks.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Create New Task
            </a>

        </div>

        <livewire:table 
            :config="Modules\Cron\Tables\CronTaskTable::class" 
        />

    </div>
    
    <!-- Include API Section -->
    @include('cron::partials.api-section')
@endsection