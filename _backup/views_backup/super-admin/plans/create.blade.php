@extends('super-admin.layout')

@section('title', 'Create Subscription Plan')
@section('page-title', 'Create New Plan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h5>Create New Subscription Plan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('super.plans.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Plan Name *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Description</label>
                                <input type="text" name="description" class="form-control" value="{{ old('description') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Monthly Price (Rs.) *</label>
                                <input type="number" name="price_monthly" class="form-control @error('price_monthly') is-invalid @enderror" 
                                       value="{{ old('price_monthly', 0) }}" min="0" step="0.01" required>
                                @error('price_monthly')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Yearly Price (Rs.)</label>
                                <input type="number" name="price_yearly" class="form-control" 
                                       value="{{ old('price_yearly', 0) }}" min="0" step="0.01">
                                <small class="text-muted">Optional - Leave 0 if not offering yearly billing</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Max Orders/Month *</label>
                                <input type="number" name="max_orders_per_month" class="form-control" 
                                       value="{{ old('max_orders_per_month', 10) }}" min="0" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Max Products *</label>
                                <input type="number" name="max_products" class="form-control" 
                                       value="{{ old('max_products', 50) }}" min="0" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Max Users *</label>
                                <input type="number" name="max_users" class="form-control" 
                                       value="{{ old('max_users', 1) }}" min="0" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Storage (GB) *</label>
                                <input type="number" name="max_storage_gb" class="form-control" 
                                       value="{{ old('max_storage_gb', 1) }}" min="0" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Trial Days *</label>
                                <input type="number" name="trial_days" class="form-control" 
                                       value="{{ old('trial_days', 14) }}" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" 
                                       value="{{ old('sort_order', 0) }}" min="0">
                            </div>
                        </div>

                        <hr>

                        <h6>Features</h6>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="has_inventory" value="1" 
                                           {{ old('has_inventory') ? 'checked' : '' }}>
                                    <label class="form-check-label">Inventory Management</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="has_manual_delivery" value="1"
                                           {{ old('has_manual_delivery') ? 'checked' : '' }}>
                                    <label class="form-check-label">Manual Delivery</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="has_logistics_integration" value="1"
                                           {{ old('has_logistics_integration') ? 'checked' : '' }}>
                                    <label class="form-check-label">Logistics Integration</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="has_accounting" value="1"
                                           {{ old('has_accounting') ? 'checked' : '' }}>
                                    <label class="form-check-label">Accounting Module</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="has_analytics" value="1"
                                           {{ old('has_analytics') ? 'checked' : '' }}>
                                    <label class="form-check-label">Analytics & Reports</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="has_api_access" value="1"
                                           {{ old('has_api_access') ? 'checked' : '' }}>
                                    <label class="form-check-label">API Access</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="has_multi_user" value="1"
                                           {{ old('has_multi_user') ? 'checked' : '' }}>
                                    <label class="form-check-label">Multi-user Support</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="has_custom_domain" value="1"
                                           {{ old('has_custom_domain') ? 'checked' : '' }}>
                                    <label class="form-check-label">Custom Domain</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="has_priority_support" value="1"
                                           {{ old('has_priority_support') ? 'checked' : '' }}>
                                    <label class="form-check-label">Priority Support</label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label">Active (visible to users)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label">Featured Plan (highlight on pricing page)</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('super.plans.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Plan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection





