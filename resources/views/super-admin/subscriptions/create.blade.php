@extends('super-admin.layout')

@section('title', 'Create Subscription')
@section('page-title', 'Create New Subscription')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Create New Subscription</h5>
            <a href="{{ route('super.subscriptions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('super.subscriptions.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tenant_id" class="form-label">Tenant <span class="text-danger">*</span></label>
                            <select name="tenant_id" id="tenant_id" class="form-select @error('tenant_id') is-invalid @enderror" required>
                                <option value="">Select Tenant</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                        {{ $tenant->business_name }} ({{ $tenant->subdomain }})
                                    </option>
                                @endforeach
                            </select>
                            @error('tenant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="plan_id" class="form-label">Plan <span class="text-danger">*</span></label>
                            <select name="plan_id" id="plan_id" class="form-select @error('plan_id') is-invalid @enderror" required>
                                <option value="">Select Plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" data-monthly="{{ $plan->price_monthly }}" data-yearly="{{ $plan->price_yearly }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} - Rs. {{ number_format($plan->price_monthly, 0) }}/month
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="billing_cycle" class="form-label">Billing Cycle <span class="text-danger">*</span></label>
                            <select name="billing_cycle" id="billing_cycle" class="form-select @error('billing_cycle') is-invalid @enderror" required>
                                <option value="monthly" {{ old('billing_cycle') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ old('billing_cycle') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                            @error('billing_cycle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="trial" {{ old('status') == 'trial' ? 'selected' : '' }}>Trial</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="past_due" {{ old('status') == 'past_due' ? 'selected' : '' }}>Past Due</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="starts_at" class="form-label">Start Date</label>
                            <input type="datetime-local" name="starts_at" id="starts_at" class="form-control @error('starts_at') is-invalid @enderror" value="{{ old('starts_at') }}">
                            @error('starts_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave empty to use current date</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="ends_at" class="form-label">End Date</label>
                            <input type="datetime-local" name="ends_at" id="ends_at" class="form-control @error('ends_at') is-invalid @enderror" value="{{ old('ends_at') }}">
                            @error('ends_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave empty to auto-calculate based on billing cycle</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="trial_ends_at" class="form-label">Trial End Date</label>
                            <input type="datetime-local" name="trial_ends_at" id="trial_ends_at" class="form-control @error('trial_ends_at') is-invalid @enderror" value="{{ old('trial_ends_at') }}">
                            @error('trial_ends_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount (NPR) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="amount" step="0.01" min="0" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Subscription amount</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" name="auto_renew" id="auto_renew" class="form-check-input" value="1" {{ old('auto_renew') ? 'checked' : '' }}>
                                <label for="auto_renew" class="form-check-label">
                                    Auto Renew
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('super.subscriptions.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-update amount based on plan and billing cycle
    document.getElementById('plan_id')?.addEventListener('change', updateAmount);
    document.getElementById('billing_cycle')?.addEventListener('change', updateAmount);

    function updateAmount() {
        const planSelect = document.getElementById('plan_id');
        const cycleSelect = document.getElementById('billing_cycle');
        const amountInput = document.getElementById('amount');
        
        if (planSelect.value && cycleSelect.value) {
            const selectedOption = planSelect.options[planSelect.selectedIndex];
            const price = cycleSelect.value === 'yearly' 
                ? selectedOption.dataset.yearly 
                : selectedOption.dataset.monthly;
            
            if (price) {
                amountInput.value = parseFloat(price).toFixed(2);
            }
        }
    }

    // Auto-calculate end date based on start date and billing cycle
    document.getElementById('starts_at')?.addEventListener('change', function() {
        const startsAt = this.value;
        const cycle = document.getElementById('billing_cycle').value;
        const endsAtInput = document.getElementById('ends_at');
        
        if (startsAt && cycle && !endsAtInput.value) {
            const startDate = new Date(startsAt);
            const months = cycle === 'yearly' ? 12 : 1;
            startDate.setMonth(startDate.getMonth() + months);
            
            // Format for datetime-local input
            const year = startDate.getFullYear();
            const month = String(startDate.getMonth() + 1).padStart(2, '0');
            const day = String(startDate.getDate()).padStart(2, '0');
            const hours = String(startDate.getHours()).padStart(2, '0');
            const minutes = String(startDate.getMinutes()).padStart(2, '0');
            
            endsAtInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
        }
    });
</script>
@endpush
@endsection
