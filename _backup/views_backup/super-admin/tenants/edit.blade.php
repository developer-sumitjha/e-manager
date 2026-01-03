@extends('super-admin.layout')

@section('title', 'Edit Tenant')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Edit Tenant</h1>
            <p class="text-muted">Update tenant information</p>
        </div>
        <div>
            <a href="{{ route('super.tenants.show', $tenant) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('super.tenants.update', $tenant) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h5 class="mb-3">Business Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Business Name *</label>
                                <input type="text" name="business_name" class="form-control @error('business_name') is-invalid @enderror" 
                                    value="{{ old('business_name', $tenant->business_name) }}" required>
                                @error('business_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Business Email *</label>
                                <input type="email" name="business_email" class="form-control @error('business_email') is-invalid @enderror" 
                                    value="{{ old('business_email', $tenant->business_email) }}" required>
                                @error('business_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Business Phone</label>
                                <input type="text" name="business_phone" class="form-control @error('business_phone') is-invalid @enderror" 
                                    value="{{ old('business_phone', $tenant->business_phone) }}">
                                @error('business_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Business Address</label>
                                <textarea name="business_address" class="form-control @error('business_address') is-invalid @enderror" rows="2">{{ old('business_address', $tenant->business_address) }}</textarea>
                                @error('business_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Owner Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Owner Name *</label>
                                <input type="text" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror" 
                                    value="{{ old('owner_name', $tenant->owner_name) }}" required>
                                @error('owner_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Owner Email *</label>
                                <input type="email" name="owner_email" class="form-control @error('owner_email') is-invalid @enderror" 
                                    value="{{ old('owner_email', $tenant->owner_email) }}" required>
                                @error('owner_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Owner Phone *</label>
                                <input type="text" name="owner_phone" class="form-control @error('owner_phone') is-invalid @enderror" 
                                    value="{{ old('owner_phone', $tenant->owner_phone) }}" required>
                                @error('owner_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Plan & Limits</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current Plan *</label>
                                <select name="current_plan_id" class="form-select @error('current_plan_id') is-invalid @enderror" required>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ old('current_plan_id', $tenant->current_plan_id) == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} - Rs. {{ number_format($plan->price_monthly, 0) }}/month
                                        </option>
                                    @endforeach
                                </select>
                                @error('current_plan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Max Orders per Month *</label>
                                <input type="number" name="max_orders" class="form-control @error('max_orders') is-invalid @enderror" 
                                    value="{{ old('max_orders', $tenant->max_orders) }}" min="0" required>
                                @error('max_orders')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Max Products *</label>
                                <input type="number" name="max_products" class="form-control @error('max_products') is-invalid @enderror" 
                                    value="{{ old('max_products', $tenant->max_products) }}" min="0" required>
                                @error('max_products')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Max Users *</label>
                                <input type="number" name="max_users" class="form-control @error('max_users') is-invalid @enderror" 
                                    value="{{ old('max_users', $tenant->max_users) }}" min="1" required>
                                @error('max_users')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('super.tenants.show', $tenant) }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Current Status</h5>
                </div>
                <div class="card-body">
                    <p><strong>Tenant ID:</strong> {{ $tenant->tenant_id }}</p>
                    <p><strong>Subdomain:</strong> {{ $tenant->subdomain }}</p>
                    <p><strong>Status:</strong> 
                        @if($tenant->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($tenant->status == 'trial')
                            <span class="badge bg-warning">Trial</span>
                        @elseif($tenant->status == 'suspended')
                            <span class="badge bg-danger">Suspended</span>
                        @elseif($tenant->status == 'pending')
                            <span class="badge bg-secondary">Pending</span>
                        @endif
                    </p>
                    <p><strong>Created:</strong> {{ $tenant->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




