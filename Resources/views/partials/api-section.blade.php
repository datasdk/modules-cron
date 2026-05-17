<!-- Cron Job Execution Panel -->
<div x-data="cronApi" class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-play-circle"></i> Execute Cron Jobs
        </h5>
    </div>
    <div class="card-body">
        <!-- API Endpoint -->
        <div class="mb-4">
            <label class="form-label fw-bold">API Endpoint</label>
            <div class="input-group">
                <input type="text" class="form-control" 
                       :value="endpoint" readonly>
                <button class="btn btn-outline-secondary ml-1" 
                        @click="copyEndpoint"
                        :disabled="copying">
                    Copy url
                </button>
            </div>
            <div class="form-text text-muted">
                Use this endpoint to manually trigger cron job execution via API.
            </div>
        </div>

        <!-- API Details -->
        <div class="mb-4">
            <h6 class="mb-3">API Details</h6>
            <table class="table table-sm table-bordered">
                <tbody>
                    <tr>
                        <td width="120"><strong>Method</strong></td>
                        <td><span class="badge bg-success">POST</span></td>
                    </tr>
                    <tr>
                        <td><strong>Content-Type</strong></td>
                        <td><code>application/json</code></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Execute Button -->
        <div class="d-grid">
            <button class="btn btn-success" 
                    @click="executeCron"
                    :disabled="executing">
                <template x-if="executing">
                    <span class="spinner-border spinner-border-sm me-2"></span>
                </template>
                <template x-if="!executing">
                    <i class="bi bi-play-fill me-2"></i>
                </template>
                <span x-text="executing ? 'Executing...' : 'Execute Now'"></span>
            </button>
        </div>

        <!-- Result Message -->
        <div x-show="message" x-transition class="mt-3">
            <div class="alert" :class="message.type === 'success' ? 'alert-success' : 'alert-danger'">
                <div class="d-flex justify-content-between align-items-center">
                    <span>
                        <i :class="message.type === 'success' ? 'bi bi-check-circle' : 'bi bi-x-circle'" 
                           class="me-2"></i>
                        <span x-text="message.text"></span>
                    </span>
                    <button type="button" class="btn-close" @click="message = null"></button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('cronApi', () => ({
        endpoint: '{{ route("api.cron.run-schedules") }}',
        copying: false,
        executing: false,
        message: null,

        copyEndpoint() {
            this.copying = true;
            
            navigator.clipboard.writeText(this.endpoint)
                .then(() => {
                    this.showMessage('Endpoint copied to clipboard!', 'success');
                })
                .catch(err => {
                    this.showMessage('Failed to copy to clipboard', 'error');
                })
                .finally(() => {
                    this.copying = false;
                });
        },

        async executeCron() {
            this.executing = true;
            this.message = null;

            try {
                const response = await fetch(this.endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    this.showMessage('Cron jobs executed successfully!', 'success');

                    location.reload();

                } else {
                    this.showMessage(data.message || `Failed to execute cron jobs (${response.status})`, 'error');
                }

            } catch (error) {
                let errorMessage = 'Failed to execute cron jobs';
                
                if (error.message) {
                    errorMessage = error.message;
                }

                this.showMessage(errorMessage, 'error');
                
            } finally {
                this.executing = false;
            }
        },

        showMessage(text, type = 'success') {
            this.message = { text, type };
            
            // Auto-hide success messages after 5 seconds
            if (type === 'success') {
                setTimeout(() => {
                    if (this.message && this.message.type === 'success') {
                        this.message = null;
                    }
                }, 5000);
            }
        }
    }));
});
</script>
@endpush