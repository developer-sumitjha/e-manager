@extends('super-admin.layout')

@section('title', 'Edit Subscription')
@section('page-title', 'Edit Subscription')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Subscription: {{ $subscription->subscription_id }}</h5>
            <div>
                <a href="{{ route('super.subscriptions.show', $subscription) }}" class="btn btn-info">
                    <i class="fas fa-eye"></i> View Details
                </a>
                <a href="{{ route('super.subscriptions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('super.subscriptions.update', $subscription) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tenant</label>
                            <input type="text" class="form-control" value="{{ $subscription->tenant->business_name }} ({{ $subscription->tenant->subdomain }})" disabled>
                            <small class="text-muted">Tenant cannot be changed</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="plan_id" class="form-label">Plan <span class="text-danger">*</span></label>
                            <select name="plan_id" id="plan_id" class="form-select @error('plan_id') is-invalid @enderror" required>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" 
                                        data-monthly="{{ $plan->price_monthly }}" 
                                        data-yearly="{{ $plan->price_yearly }}"
                                        {{ $subscription->plan_id == $plan->id ? 'selected' : '' }}>
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
                                <option value="monthly" {{ $subscription->billing_cycle == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ $subscription->billing_cycle == 'yearly' ? 'selected' : '' }}>Yearly</option>
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
                                <option value="trial" {{ $subscription->status == 'trial' ? 'selected' : '' }}>Trial</option>
                                <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ $subscription->status == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="cancelled" {{ $subscription->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="past_due" {{ $subscription->status == 'past_due' ? 'selected' : '' }}>Past Due</option>
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
                            <input type="datetime-local" name="starts_at" id="starts_at" 
                                class="form-control @error('starts_at') is-invalid @enderror" 
                                value="{{ $subscription->starts_at ? $subscription->starts_at->format('Y-m-d\TH:i') : '' }}">
                            @error('starts_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="ends_at" class="form-label">End Date</label>
                            <input type="datetime-local" name="ends_at" id="ends_at" 
                                class="form-control @error('ends_at') is-invalid @enderror" 
                                value="{{ $subscription->ends_at ? $subscription->ends_at->format('Y-m-d\TH:i') : '' }}">
                            @error('ends_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="trial_ends_at" class="form-label">Trial End Date</label>
                            <input type="datetime-local" name="trial_ends_at" id="trial_ends_at" 
                                class="form-control @error('trial_ends_at') is-invalid @enderror" 
                                value="{{ $subscription->trial_ends_at ? $subscription->trial_ends_at->format('Y-m-d\TH:i') : '' }}">
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
                            <input type="number" name="amount" id="amount" step="0.01" min="0" 
                                class="form-control @error('amount') is-invalid @enderror" 
                                value="{{ old('amount', $subscription->amount) }}" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" name="auto_renew" id="auto_renew" 
                                    class="form-check-input" value="1" 
                                    {{ $subscription->auto_renew ? 'checked' : '' }}>
                                <label for="auto_renew" class="form-check-label">
                                    Auto Renew
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('super.subscriptions.show', $subscription) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Subscription
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
</script>
@endpush
@endsection
