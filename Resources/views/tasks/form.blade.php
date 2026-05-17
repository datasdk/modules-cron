@extends('cron::layouts.app')

@section('content')

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0">{{ $task->exists ? 'Update' : 'Create'}} Task</h5>
    </div>
    
    <form method="POST" class="needs-validation" novalidate>
        @csrf
        
        <div id="root"> <!-- Vue root skal være inden i form for at Vue komponenter virker -->
            <div class="card-body">
                <div class="row">
                    

                    <!-- Right Column - Inputs -->
                    <div class="col-md-12">
                        <!-- Description -->
                        <div class="mb-4">
                            <div>Description</div>
                            <input type="text" class="form-control" id="description" name="description" placeholder="e.g. Daily Backups" value="{{ old('description', $task->description) }}">
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Command -->
                        <div class="mb-4">

                            <div>Select command</div>
                            <command-list command="{{ $task->command }}" :commands="{{ json_encode($commands) }}"></command-list>
                            @error('command')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Parameters -->
                        <div class="mb-4">
                            <div>Parameters</div>
                            <input type="text" class="form-control" id="parameters" name="parameters" placeholder="e.g. --type=all or name=John" value="{{ old('parameters', $task->parameters) }}">
                        </div>

                        <!-- Timezone -->
                        <div class="mb-4">
                            <div>Timezone</div>
                            <select name="timezone" id="timezone" class="form-control">
                                @foreach($timezones as $timezone)
                                    <option value="{{ $timezone }}" {{ old('timezone', $task->exists ? $task->timezone : config('app.timezone')) == $timezone ? 'selected' : '' }}>
                                        {{ $timezone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type (Expression / Frequencies) -->
                        <div class="mb-4">
                            <task-type inline-template current="{{ old('type', $task->expression ? 'expression' : 'frequency') }}" :existing="{{ old('frequencies') ? json_encode(old('frequencies')) : $task->frequencies }}">
                                <div>
                                    <div class="d-flex gap-3 mb-3">
                                       
                                        <div class="form-check pr-4">
                                            <input class="form-check-input" type="radio" name="type" v-model="type" value="frequency" id="type_frequency">
                                            <label class="form-check-label" for="type_frequency">Frequencies</label>
                                        </div>
                                         <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" v-model="type" value="expression" id="type_expression">
                                            <label class="form-check-label" for="type_expression">Cron Expression</label>
                                        </div>
                                    </div>

                                    <!-- Cron Expression -->
                                    <div class="border rounded p-3 mb-3" v-if="isCron">
                                        <label class="form-label fw-semibold mb-2">Cron Expression</label>
                                        <div class="text-muted small mb-2">Add a cron expression for your task</div>
                                        <input type="text" class="form-control" name="expression" placeholder="e.g * * * * *" value="{{ old('expression', $task->expression) }}">
                                        @error('expression')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Frequencies -->
                                    <div class="border rounded p-3" v-if="managesFrequencies">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <label class="form-label fw-semibold mb-0">Frequencies</label>
                                                <div class="text-muted small">Add frequencies to your task</div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-primary" @click.prevent="showModal = true">
                                                <i class="fas fa-plus me-1"></i> Add Frequency
                                            </button>
                                        </div>
                                        
                                        <!-- Include Frequency Modal -->
                                        @include('totem::dialogs.frequencies.add')
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Frequency</th>
                                                        <th>Parameters</th>
                                                        <th width="60px"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(frequency, index) in frequencies" :key="index">
                                                        <td class="align-middle">
                                                            @{{ frequency.label }}
                                                            <input type="hidden" :name="'frequencies[' + index + '][interval]'" v-model="frequency.interval">
                                                            <input type="hidden" :name="'frequencies[' + index + '][label]'" v-model="frequency.label">
                                                        </td>
                                                        <td class="align-middle">
                                                            <span v-if="frequency.parameters && frequency.parameters.length > 0">
                                                                <span v-for="(parameter, key) in frequency.parameters">
                                                                    @{{ parameter.value }}<span v-if="frequency.parameters.length > 1 && key < frequency.parameters.length - 1">,</span>
                                                                    <input type="hidden" :name="'frequencies[' + index + '][parameters][' + key +'][name]'" v-model="parameter.name">
                                                                    <input type="hidden" :name="'frequencies[' + index + '][parameters][' + key +'][value]'" v-model="parameter.value">
                                                                </span>
                                                            </span>
                                                            <span v-else class="text-muted">No Parameters</span>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <button type="button" class="btn btn-sm btn-link text-danger" @click="remove(index)" title="Remove">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr v-if="frequencies.length === 0">
                                                        <td colspan="3" class="text-center text-muted py-4">
                                                            <i class="fas fa-clock fa-2x mb-2 d-block"></i>
                                                            No frequencies added yet
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        @error('frequencies')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </task-type>
                        </div>

                        <!-- Notifications -->
                        <div class="border rounded p-3 mb-4">
                            <h6 class="fw-semibold mb-3">Notification Settings</h6>
                            
                            <!-- Email Notification -->
                            <div class="mb-3">
                                <label for="notification_email_address" class="form-label">Email Notification</label>
                                <input type="email" class="form-control" id="notification_email_address" name="notification_email_address" value="{{ old('notification_email_address', $task->notification_email_address) }}" placeholder="john.doe@example.com">
                                @error('notification_email_address')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                      

                         
                        </div>

                        <!-- Miscellaneous Options -->
                        <div class="border rounded p-3 mb-4">
                            <h6 class="fw-semibold mb-3">Miscellaneous Options</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="dont_overlap" id="dont_overlap" value="1" {{ old('dont_overlap', $task->dont_overlap) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dont_overlap">Don't Overlap</label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="run_in_maintenance" id="run_in_maintenance" value="1" {{ old('run_in_maintenance', $task->run_in_maintenance) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="run_in_maintenance">Run in maintenance mode</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="run_on_one_server" id="run_on_one_server" value="1" {{ old('run_on_one_server', $task->run_on_one_server) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="run_on_one_server">Run on a single server</label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="run_in_background" id="run_in_background" value="1" {{ old('run_in_background', $task->run_in_background) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="run_in_background">Run in the background</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cleanup Options -->
                        <div class="border rounded p-3">
                            <h6 class="fw-semibold mb-3">Cleanup Options</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="auto_cleanup_num" value="{{ old('auto_cleanup_num', $task->auto_cleanup_num ?? 0) }}" placeholder="Number">
                                        <span class="input-group-text bg-white">
                                            <div class="form-check form-check-inline me-3">
                                                <input class="form-check-input" type="radio" name="auto_cleanup_type" id="cleanup_days" value="days" {{ old('auto_cleanup_type', $task->auto_cleanup_type) !== 'results' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="cleanup_days">Days</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="auto_cleanup_type" id="cleanup_results" value="results" {{ old('auto_cleanup_type', $task->auto_cleanup_type) === 'results' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="cleanup_results">Results</label>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="card-footer bg-white border-top">
                <div class="">

                 
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Save Task
                    </button>
              

                
                    
                            <a href="{{ route('totem.tasks.all') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                    
                 
                    
                    
                </div>
            </div>
        </div> <!-- End Vue root -->
    </form>
</div>

@endsection

<style>
.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>