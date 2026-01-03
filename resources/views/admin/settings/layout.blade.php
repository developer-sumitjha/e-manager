@extends('admin.layouts.app')

@section('title', '@yield("settings-title")')
@section('page-title', '@yield("settings-title")')

@push('styles')
<style>
    .settings-page-container {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        backdrop-filter: blur(10px);
    }

    .settings-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid rgba(229, 231, 235, 0.5);
    }

    .settings-header-icon {
        width: 70px;
        height: 70px;
        border-radius: 1rem;
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
    }

    .settings-header-content h1 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .settings-header-content p {
        color: var(--text-secondary);
        margin: 0;
        font-size: 0.95rem;
    }

    .settings-form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.95rem;
    }

    .form-description {
        color: var(--text-secondary);
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }

    .form-control, .form-select {
        padding: 0.75rem 1rem;
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 0.5rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .form-check-input {
        width: 50px;
        height: 26px;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        padding-top: 2rem;
        border-top: 1px solid rgba(229, 231, 235, 0.5);
    }

    .btn {
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

    .btn-primary {
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        color: white;
    }

    .btn-secondary {
        background: rgba(107, 114, 128, 0.1);
        color: var(--text-secondary);
        border: 1px solid rgba(107, 114, 128, 0.3);
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        color: #22C55E;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    @media (max-width: 768px) {
        .settings-form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
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
            <div class="settings-page-container">
                <div class="settings-header">
                    <div class="settings-header-icon">
                        <i class="fas fa-@yield('settings-icon')"></i>
                    </div>
                    <div class="settings-header-content">
                        <h1>@yield('settings-title')</h1>
                        <p>@yield('settings-description')</p>
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

                @yield('settings-content')
            </div>
        </div>
    </div>
</div>
@endsection







