@extends('admin.layouts.app')

@section('title', 'Gaaubesi Settings')
@section('page-title', 'Gaaubesi Settings')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Gaaubesi Logistics Settings</h1>
            <p class="text-muted">Configure your Gaaubesi API credentials and default settings</p>
        </div>
        <a href="{{ route('admin.gaaubesi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('admin.gaaubesi.update-settings') }}" method="POST">
        @csrf
        
        <!-- API Credentials -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-key"></i> API Credentials</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">API Token *</label>
                        <input type="text" name="api_token" class="form-control @error('api_token') is-invalid @enderror" 
                               value="{{ old('api_token', $settings->api_token) }}" 
                               placeholder="Enter your Gaaubesi API token">
                        <small class="text-muted">Get your API token from Gaaubesi dashboard</small>
                        @error('api_token')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">API URL *</label>
                        <input type="url" name="api_url" class="form-control @error('api_url') is-invalid @enderror" 
                               value="{{ old('api_url', $settings->api_url) }}" 
                               placeholder="https://api.gaaubesi.com">
                        @error('api_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if($settings->api_token)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Current Token:</strong> {{ substr($settings->api_token, 0, 10) }}...{{ substr($settings->api_token, -4) }}
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>API Token Not Set!</strong> Please enter your Gaaubesi API token to use logistics features.
                </div>
                @endif
            </div>
        </div>

        <!-- Default Shipment Settings -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-cog"></i> Default Shipment Settings</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Package Access *</label>
                        <select name="default_package_access" class="form-select">
                            <option value="Accessible" {{ $settings->default_package_access == 'Accessible' ? 'selected' : '' }}>Accessible (Can Open)</option>
                            <option value="Not Accessible" {{ $settings->default_package_access == 'Not Accessible' ? 'selected' : '' }}>Not Accessible (Can't Open)</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Delivery Type *</label>
                        <select name="default_delivery_type" class="form-select">
                            <option value="Normal" {{ $settings->default_delivery_type == 'Normal' ? 'selected' : '' }}>Normal</option>
                            <option value="Express" {{ $settings->default_delivery_type == 'Express' ? 'selected' : '' }}>Express</option>
                            <option value="Same Day" {{ $settings->default_delivery_type == 'Same Day' ? 'selected' : '' }}>Same Day</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Insurance *</label>
                        <select name="default_insurance" class="form-select">
                            <option value="Not Insured" {{ $settings->default_insurance == 'Not Insured' ? 'selected' : '' }}>Not Insured</option>
                            <option value="Insured" {{ $settings->default_insurance == 'Insured' ? 'selected' : '' }}>Insured</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pickup Information -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Pickup Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pickup Branch</label>
                        <input type="text" name="pickup_branch" class="form-control" 
                               value="{{ old('pickup_branch', $settings->pickup_branch) }}" 
                               placeholder="e.g., HEAD OFFICE">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="pickup_contact_person" class="form-control" 
                               value="{{ old('pickup_contact_person', $settings->pickup_contact_person) }}" 
                               placeholder="Contact person name">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="pickup_contact_phone" class="form-control" 
                               value="{{ old('pickup_contact_phone', $settings->pickup_contact_phone) }}" 
                               placeholder="Phone number">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Pickup Address</label>
                        <textarea name="pickup_address" class="form-control" rows="3" 
                                  placeholder="Full pickup address">{{ old('pickup_address', $settings->pickup_address) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Settings -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-sliders-h"></i> Advanced Settings</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="auto_create_shipment" value="1" 
                                   {{ $settings->auto_create_shipment ? 'checked' : '' }}>
                            <label class="form-check-label">
                                <strong>Auto Create Shipment</strong><br>
                                <small class="text-muted">Automatically create Gaaubesi shipment when order is allocated to logistics</small>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="send_notifications" value="1" 
                                   {{ $settings->send_notifications ? 'checked' : '' }}>
                            <label class="form-check-label">
                                <strong>Send Notifications</strong><br>
                                <small class="text-muted">Receive email/SMS notifications for shipment updates</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="card">
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Test Connection -->
    @if($settings->api_token)
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-plug"></i> Test API Connection</h5>
        </div>
        <div class="card-body">
            <button type="button" class="btn btn-info" onclick="testConnection()">
                <i class="fas fa-check-circle"></i> Test Connection
            </button>
            <div id="connectionResult" class="mt-3"></div>
        </div>
    </div>
    @endif
</div>

<script>
function testConnection() {
    const resultDiv = document.getElementById('connectionResult');
    resultDiv.innerHTML = '<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> Testing connection...</div>';
    
    fetch('{{ route("admin.gaaubesi.locations") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.locations) {
                resultDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> <strong>Connection Successful!</strong> Found ' + data.locations.length + ' locations.</div>';
            } else {
                resultDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-times-circle"></i> <strong>Connection Failed!</strong> ' + (data.message || 'Please check your API token.') + '</div>';
            }
        })
        .catch(error => {
            resultDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-times-circle"></i> <strong>Error!</strong> ' + error.message + '</div>';
        });
}
</script>
@endsection


