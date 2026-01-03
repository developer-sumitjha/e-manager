@extends('admin.layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')

@push('styles')
<style>
    .settings-container {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        backdrop-filter: blur(10px);
    }

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .setting-card {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        text-decoration: none;
        display: block;
    }

    .setting-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        border-color: var(--primary-color);
    }

    .setting-icon {
        width: 60px;
        height: 60px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
    }

    .setting-icon.general { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .setting-icon.email { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }
    .setting-icon.payment { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; }
    .setting-icon.notification { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; }
    .setting-icon.shipping { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; }
    .setting-icon.tax { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); color: white; }
    .setting-icon.order { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333; }
    .setting-icon.security { background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%); color: white; }
    .setting-icon.api { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #333; }
    .setting-icon.system { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }

    .setting-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .setting-description {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .setting-count {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(139, 92, 246, 0.1);
        color: var(--primary-color);
        border-radius: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .quick-actions {
        display: flex;
        gap: 1rem;
    }

    .action-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .action-btn.primary {
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        color: white;
    }

    .action-btn.secondary {
        background: rgba(107, 114, 128, 0.1);
        color: var(--text-secondary);
        border: 1px solid rgba(107, 114, 128, 0.3);
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
    }

    @media (max-width: 768px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .quick-actions {
            width: 100%;
            flex-direction: column;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="settings-container">
                <div class="page-header">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-cog"></i>
                            System Settings
                        </h1>
                        <p class="page-subtitle">Configure your e-manager system settings</p>
                    </div>
                    <div class="quick-actions">
                        <form action="{{ route('admin.settings.clear-cache') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="action-btn secondary">
                                <i class="fas fa-broom"></i> Clear Cache
                            </button>
                        </form>
                        <form action="{{ route('admin.settings.optimize') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="action-btn primary">
                                <i class="fas fa-rocket"></i> Optimize System
                            </button>
                        </form>
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="settings-grid">
                    <!-- General Settings -->
                    <a href="{{ route('admin.settings.general') }}" class="setting-card">
                        <div class="setting-icon general">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3 class="setting-title">General Settings</h3>
                        <p class="setting-description">
                            Configure site name, business details, timezone, and basic information
                        </p>
                        <span class="setting-count">
                            <i class="fas fa-cog"></i> {{ $settingsGroups['general']->count() }} settings
                        </span>
                    </a>

                    <!-- Email Settings -->
                    <a href="{{ route('admin.settings.email') }}" class="setting-card">
                        <div class="setting-icon email">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3 class="setting-title">Email Configuration</h3>
                        <p class="setting-description">
                            Setup SMTP server, email templates, and notification preferences
                        </p>
                        <span class="setting-count">
                            <i class="fas fa-cog"></i> {{ $settingsGroups['email']->count() }} settings
                        </span>
                    </a>

                    <!-- Payment Settings -->
                    <a href="{{ route('admin.settings.payment') }}" class="setting-card">
                        <div class="setting-icon payment">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h3 class="setting-title">Payment Settings</h3>
                        <p class="setting-description">
                            Configure payment gateways, COD settings, and transaction options
                        </p>
                        <span class="setting-count">
                            <i class="fas fa-cog"></i> {{ $settingsGroups['payment']->count() }} settings
                        </span>
                    </a>

                    <!-- Notification Settings -->
                    <a href="{{ route('admin.settings.notification') }}" class="setting-card">
                        <div class="setting-icon notification">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3 class="setting-title">Notifications</h3>
                        <p class="setting-description">
                            Manage email, SMS, and push notification preferences
                        </p>
                        <span class="setting-count">
                            <i class="fas fa-cog"></i> {{ $settingsGroups['notification']->count() }} settings
                        </span>
                    </a>

                    <!-- Shipping Settings -->
                    <a href="{{ route('admin.settings.shipping') }}" class="setting-card">
                        <div class="setting-icon shipping">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h3 class="setting-title">Shipping Settings</h3>
                        <p class="setting-description">
                            Configure shipping methods, costs, and delivery options
                        </p>
                        <span class="setting-count">
                            <i class="fas fa-cog"></i> {{ $settingsGroups['shipping']->count() }} settings
                        </span>
                    </a>

                    <!-- Tax & Currency -->
                    <a href="{{ route('admin.settings.tax') }}" class="setting-card">
                        <div class="setting-icon tax">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <h3 class="setting-title">Tax & Currency</h3>
                        <p class="setting-description">
                            Setup tax rates, currency settings, and pricing options
                        </p>
                        <span class="setting-count">
                            <i class="fas fa-cog"></i> {{ $settingsGroups['tax']->count() }} settings
                        </span>
                    </a>

                    <!-- Order Settings -->
                    <a href="{{ route('admin.settings.order') }}" class="setting-card">
                        <div class="setting-icon order">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3 class="setting-title">Order Settings</h3>
                        <p class="setting-description">
                            Configure order numbering, statuses, and workflow options
                        </p>
                        <span class="setting-count">
                            <i class="fas fa-cog"></i> {{ $settingsGroups['order']->count() }} settings
                        </span>
                    </a>

                    <!-- Security Settings -->
                    <a href="{{ route('admin.settings.security') }}" class="setting-card">
                        <div class="setting-icon security">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="setting-title">Security Settings</h3>
                        <p class="setting-description">
                            Manage password policies, 2FA, and security configurations
                        </p>
                        <span class="setting-count">
                            <i class="fas fa-cog"></i> {{ $settingsGroups['security']->count() }} settings
                        </span>
                    </a>

                    <!-- API Configuration -->
                    <a href="{{ route('admin.settings.api') }}" class="setting-card">
                        <div class="setting-icon api">
                            <i class="fas fa-code"></i>
                        </div>
                        <h3 class="setting-title">API Configuration</h3>
                        <p class="setting-description">
                            Setup API keys, rate limits, and third-party integrations
                        </p>
                        <span class="setting-count">
                            <i class="fas fa-cog"></i> {{ $settingsGroups['api']->count() }} settings
                        </span>
                    </a>

                    <!-- System Settings -->
                    <a href="{{ route('admin.settings.system') }}" class="setting-card">
                        <div class="setting-icon system">
                            <i class="fas fa-server"></i>
                        </div>
                        <h3 class="setting-title">System Settings</h3>
                        <p class="setting-description">
                            Configure maintenance mode, backups, logs, and system options
                        </p>
                        <span class="setting-count">
                            <i class="fas fa-cog"></i> {{ $settingsGroups['system']->count() }} settings
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection





