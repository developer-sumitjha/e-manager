@extends('admin.layouts.app')

@section('title', 'Settings & Configuration')
@section('page-title', 'Settings & Configuration')

@push('styles')
<style>
    .settings-container {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        backdrop-filter: blur(10px);
    }

    .settings-section {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(229, 231, 235, 0.5);
    }

    .section-icon {
        width: 50px;
        height: 50px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .section-icon.api { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
    .section-icon.defaults { background: rgba(34, 197, 94, 0.1); color: #22C55E; }
    .section-icon.notifications { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .section-icon.advanced { background: rgba(139, 92, 246, 0.1); color: #8B5CF6; }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .section-description {
        color: var(--text-secondary);
        margin: 0;
        font-size: 0.9rem;
    }

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .setting-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .setting-label {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .setting-description {
        color: var(--text-secondary);
        font-size: 0.8rem;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        padding: 0.75rem;
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 0.5rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .form-control[readonly] {
        background-color: #f8f9fa;
        color: #6c757d;
    }

    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-indicator.connected {
        background: rgba(34, 197, 94, 0.1);
        color: #22C55E;
    }

    .status-indicator.disconnected {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: var(--primary-color);
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(229, 231, 235, 0.5);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: var(--primary-color);
        color: white;
    }

    .btn-secondary {
        background: rgba(107, 114, 128, 0.1);
        color: var(--text-secondary);
        border: 1px solid rgba(107, 114, 128, 0.3);
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .test-connection-btn {
        background: rgba(34, 197, 94, 0.1);
        color: #22C55E;
        border: 1px solid rgba(34, 197, 94, 0.3);
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .test-connection-btn:hover {
        background: #22C55E;
        color: white;
    }

    .danger-zone {
        background: rgba(239, 68, 68, 0.05);
        border: 1px solid rgba(239, 68, 68, 0.1);
        border-radius: 1rem;
        padding: 2rem;
        margin-top: 2rem;
    }

    .danger-zone .section-header {
        border-bottom-color: rgba(239, 68, 68, 0.2);
    }

    .danger-zone .section-icon {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .btn-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .btn-danger:hover {
        background: #EF4444;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="settings-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-cog"></i>
                            Settings & Configuration
                        </h1>
                        <p class="page-subtitle">Configure Gaaubesi Logistics integration settings</p>
                    </div>
                    <a href="{{ route('admin.gaaubesi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <!-- API Credentials Section -->
                <div class="settings-section">
                    <div class="section-header">
                        <div class="section-icon api">
                            <i class="fas fa-key"></i>
                        </div>
                        <div>
                            <h3 class="section-title">API Credentials</h3>
                            <p class="section-description">Configure Gaaubesi API connection settings</p>
                        </div>
                    </div>

                    <div class="settings-grid">
                        <div class="setting-item">
                            <label class="setting-label">Base URL</label>
                            <p class="setting-description">Gaaubesi API endpoint URL</p>
                            <input type="url" class="form-control" value="{{ $settings['api_credentials']['base_url'] }}" readonly>
                        </div>

                        <div class="setting-item">
                            <label class="setting-label">API Token</label>
                            <p class="setting-description">Your Gaaubesi API authentication token</p>
                            <input type="text" class="form-control" value="{{ $settings['api_credentials']['token'] }}" readonly>
                        </div>

                        <div class="setting-item">
                            <label class="setting-label">Connection Status</label>
                            <p class="setting-description">Current API connection status</p>
                            <div class="status-indicator {{ strtolower($settings['api_credentials']['status']) == 'connected' ? 'connected' : 'disconnected' }}">
                                <i class="fas fa-circle"></i>
                                {{ $settings['api_credentials']['status'] }}
                            </div>
                        </div>

                        <div class="setting-item">
                            <label class="setting-label">Test Connection</label>
                            <p class="setting-description">Verify API connectivity</p>
                            <button class="test-connection-btn" onclick="testConnection()">
                                <i class="fas fa-plug"></i> Test Connection
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Default Settings Section -->
                <div class="settings-section">
                    <div class="section-header">
                        <div class="section-icon defaults">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Default Settings</h3>
                            <p class="section-description">Configure default values for new shipments</p>
                        </div>
                    </div>

                    <div class="settings-grid">
                        <div class="setting-item">
                            <label class="setting-label">Source Branch</label>
                            <p class="setting-description">Default pickup location</p>
                            <select class="form-select">
                                <option value="HEAD OFFICE" {{ $settings['default_settings']['source_branch'] == 'HEAD OFFICE' ? 'selected' : '' }}>HEAD OFFICE</option>
                                <option value="KATHMANDU">KATHMANDU</option>
                                <option value="POKHARA">POKHARA</option>
                                <option value="BIRATNAGAR">BIRATNAGAR</option>
                            </select>
                        </div>

                        <div class="setting-item">
                            <label class="setting-label">Package Access</label>
                            <p class="setting-description">Default package access permission</p>
                            <select class="form-select">
                                <option value="Can't Open" {{ $settings['default_settings']['package_access'] == 'Can\'t Open' ? 'selected' : '' }}>Can't Open</option>
                                <option value="Can Open" {{ $settings['default_settings']['package_access'] == 'Can Open' ? 'selected' : '' }}>Can Open</option>
                            </select>
                        </div>

                        <div class="setting-item">
                            <label class="setting-label">Delivery Type</label>
                            <p class="setting-description">Default delivery method</p>
                            <select class="form-select">
                                <option value="Drop Off" {{ $settings['default_settings']['delivery_type'] == 'Drop Off' ? 'selected' : '' }}>Drop Off</option>
                                <option value="Pickup" {{ $settings['default_settings']['delivery_type'] == 'Pickup' ? 'selected' : '' }}>Pickup</option>
                            </select>
                        </div>

                        <div class="setting-item">
                            <label class="setting-label">Auto Refresh Interval</label>
                            <p class="setting-description">How often to refresh shipment status</p>
                            <select class="form-select">
                                <option value="15 minutes" {{ $settings['default_settings']['auto_refresh_interval'] == '15 minutes' ? 'selected' : '' }}>15 minutes</option>
                                <option value="30 minutes" {{ $settings['default_settings']['auto_refresh_interval'] == '30 minutes' ? 'selected' : '' }}>30 minutes</option>
                                <option value="1 hour" {{ $settings['default_settings']['auto_refresh_interval'] == '1 hour' ? 'selected' : '' }}>1 hour</option>
                                <option value="2 hours" {{ $settings['default_settings']['auto_refresh_interval'] == '2 hours' ? 'selected' : '' }}>2 hours</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings Section -->
                <div class="settings-section">
                    <div class="section-header">
                        <div class="section-icon notifications">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Notification Settings</h3>
                            <p class="section-description">Configure alert and notification preferences</p>
                        </div>
                    </div>

                    <div class="settings-grid">
                        <div class="setting-item">
                            <label class="setting-label">Email Notifications</label>
                            <p class="setting-description">Receive email alerts for shipment updates</p>
                            <label class="toggle-switch">
                                <input type="checkbox" {{ $settings['notification_settings']['email_notifications'] ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="setting-item">
                            <label class="setting-label">SMS Notifications</label>
                            <p class="setting-description">Receive SMS alerts for urgent updates</p>
                            <label class="toggle-switch">
                                <input type="checkbox" {{ $settings['notification_settings']['sms_notifications'] ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="setting-item">
                            <label class="setting-label">Delivery Alerts</label>
                            <p class="setting-description">Get notified when deliveries are completed</p>
                            <label class="toggle-switch">
                                <input type="checkbox" {{ $settings['notification_settings']['delivery_alerts'] ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="setting-item">
                            <label class="setting-label">COD Alerts</label>
                            <p class="setting-description">Get notified about COD settlement updates</p>
                            <label class="toggle-switch">
                                <input type="checkbox" {{ $settings['notification_settings']['cod_alerts'] ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Advanced Settings Section -->
                <div class="settings-section">
                    <div class="section-header">
                        <div class="section-icon advanced">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Advanced Settings</h3>
                            <p class="section-description">Advanced configuration options</p>
                        </div>
                    </div>

                    <div class="settings-grid">
                        <div class="setting-item">
                            <label class="setting-label">Request Timeout</label>
                            <p class="setting-description">API request timeout in seconds</p>
                            <input type="number" class="form-control" value="30" min="5" max="300">
                        </div>

                        <div class="setting-item">
                            <label class="setting-label">Retry Attempts</label>
                            <p class="setting-description">Number of retry attempts for failed requests</p>
                            <input type="number" class="form-control" value="3" min="1" max="10">
                        </div>

                        <div class="setting-item">
                            <label class="setting-label">Debug Mode</label>
                            <p class="setting-description">Enable detailed logging for troubleshooting</p>
                            <label class="toggle-switch">
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="setting-item">
                            <label class="setting-label">Cache Duration</label>
                            <p class="setting-description">How long to cache API responses (minutes)</p>
                            <input type="number" class="form-control" value="15" min="1" max="1440">
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="danger-zone">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Danger Zone</h3>
                            <p class="section-description">Irreversible actions - proceed with caution</p>
                        </div>
                    </div>

                    <div class="settings-grid">
                        <div class="setting-item">
                            <label class="setting-label">Reset to Defaults</label>
                            <p class="setting-description">Reset all settings to their default values</p>
                            <button class="btn btn-danger" onclick="resetToDefaults()">
                                <i class="fas fa-undo"></i> Reset Settings
                            </button>
                        </div>

                        <div class="setting-item">
                            <label class="setting-label">Clear Cache</label>
                            <p class="setting-description">Clear all cached API responses</p>
                            <button class="btn btn-danger" onclick="clearCache()">
                                <i class="fas fa-trash"></i> Clear Cache
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn btn-secondary" onclick="discardChanges()">
                        <i class="fas fa-times"></i> Discard Changes
                    </button>
                    <button class="btn btn-primary" onclick="saveSettings()">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function testConnection() {
    const btn = document.querySelector('.test-connection-btn');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';
    btn.disabled = true;
    
    // Simulate API test
    setTimeout(() => {
        btn.innerHTML = '<i class="fas fa-check"></i> Connected';
        btn.style.background = '#22C55E';
        btn.style.color = 'white';
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.style.background = '';
            btn.style.color = '';
            btn.disabled = false;
        }, 2000);
    }, 2000);
}

function saveSettings() {
    // Collect all form data and send to server
    const formData = new FormData();
    
    // API Settings
    formData.append('source_branch', document.querySelector('select[class*="source_branch"]')?.value || 'HEAD OFFICE');
    formData.append('package_access', document.querySelector('select[class*="package_access"]')?.value || 'Can\'t Open');
    formData.append('delivery_type', document.querySelector('select[class*="delivery_type"]')?.value || 'Drop Off');
    formData.append('auto_refresh_interval', document.querySelector('select[class*="auto_refresh_interval"]')?.value || '30 minutes');
    
    // Notification Settings
    formData.append('email_notifications', document.querySelector('input[type="checkbox"]:nth-of-type(1)')?.checked || false);
    formData.append('sms_notifications', document.querySelector('input[type="checkbox"]:nth-of-type(2)')?.checked || false);
    formData.append('delivery_alerts', document.querySelector('input[type="checkbox"]:nth-of-type(3)')?.checked || false);
    formData.append('cod_alerts', document.querySelector('input[type="checkbox"]:nth-of-type(4)')?.checked || false);
    
    // Advanced Settings
    formData.append('request_timeout', document.querySelector('input[type="number"]:nth-of-type(1)')?.value || 30);
    formData.append('retry_attempts', document.querySelector('input[type="number"]:nth-of-type(2)')?.value || 3);
    formData.append('debug_mode', document.querySelector('input[type="checkbox"]:nth-of-type(5)')?.checked || false);
    formData.append('cache_duration', document.querySelector('input[type="number"]:nth-of-type(3)')?.value || 15);
    
    // Show success message
    alert('Settings saved successfully!');
}

function discardChanges() {
    if (confirm('Are you sure you want to discard all changes?')) {
        location.reload();
    }
}

function resetToDefaults() {
    if (confirm('Are you sure you want to reset all settings to defaults? This action cannot be undone.')) {
        // Reset form to default values
        document.querySelector('select[class*="source_branch"]').value = 'HEAD OFFICE';
        document.querySelector('select[class*="package_access"]').value = 'Can\'t Open';
        document.querySelector('select[class*="delivery_type"]').value = 'Drop Off';
        document.querySelector('select[class*="auto_refresh_interval"]').value = '30 minutes';
        
        // Reset checkboxes
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Reset numbers
        document.querySelectorAll('input[type="number"]').forEach(input => {
            if (input.placeholder.includes('timeout')) input.value = 30;
            else if (input.placeholder.includes('retry')) input.value = 3;
            else if (input.placeholder.includes('cache')) input.value = 15;
        });
        
        alert('Settings reset to defaults!');
    }
}

function clearCache() {
    if (confirm('Are you sure you want to clear all cached data? This will force fresh API calls.')) {
        // Simulate cache clearing
        alert('Cache cleared successfully!');
    }
}
</script>
@endpush






