<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>API Settings - Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #667eea; }
        body { background: #f8fafc; font-family: 'Inter', sans-serif; }
        .sidebar { position: fixed; width: 280px; height: 100vh; background: linear-gradient(180deg, #667eea, #764ba2); color: white; overflow-y: auto; }
        .sidebar-header { padding: 2rem 1.5rem; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-menu { padding: 1.5rem 0; list-style: none; margin: 0; }
        .sidebar-menu a { display: flex; align-items: center; padding: 0.875rem 1.5rem; color: rgba(255,255,255,0.9); text-decoration: none; }
        .sidebar-menu a.active { background: rgba(255,255,255,0.1); color: white; }
        .sidebar-menu i { width: 24px; margin-right: 12px; }
        .main-content { margin-left: 280px; padding: 2rem; }
        .settings-card { background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); margin-bottom: 2rem; }
        .settings-tabs { display: flex; gap: 1rem; border-bottom: 2px solid #e5e7eb; margin-bottom: 2rem; }
        .tab-link { padding: 1rem 1.5rem; color: #64748b; text-decoration: none; border-bottom: 3px solid transparent; }
        .tab-link.active { color: var(--primary); border-bottom-color: var(--primary); }
        .save-btn { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 0.75rem 2rem; border-radius: 12px; border: none; }
        .api-key-display { background: #f8fafc; padding: 1rem; border-radius: 8px; font-family: monospace; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h3><i class="fas fa-crown"></i> SUPER ADMIN</h3></div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('super.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="{{ route('super.settings.general') }}" class="active"><i class="fas fa-cog"></i> Settings</a></li>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 1.5rem;">
            <li><a href="{{ route('super.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <form id="logout-form" action="{{ route('super.logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>

    <div class="main-content">
        <div class="settings-card">
            <h1><i class="fas fa-cog"></i> Platform Settings</h1>
            <p class="text-muted mb-0">Configure platform-wide settings</p>
        </div>

        <div class="settings-tabs">
            <a href="{{ route('super.settings.general') }}" class="tab-link">
                <i class="fas fa-sliders-h"></i> General
            </a>
            <a href="{{ route('super.settings.email') }}" class="tab-link">
                <i class="fas fa-envelope"></i> Email
            </a>
            <a href="{{ route('super.settings.payments') }}" class="tab-link">
                <i class="fas fa-credit-card"></i> Payments
            </a>
            <a href="{{ route('super.settings.api') }}" class="tab-link active">
                <i class="fas fa-code"></i> API
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="settings-card">
            <form action="{{ route('super.settings.save') }}" method="POST">
                @csrf
                <input type="hidden" name="category" value="api">

                <h4><i class="fas fa-key"></i> API Configuration</h4>
                
                <div class="mb-3 mt-3">
                    <label class="form-label">API Rate Limit (requests per minute)</label>
                    <input type="number" name="api_rate_limit" class="form-control" value="{{ $settings['api_rate_limit'] ?? 60 }}">
                </div>

                <div class="mb-3">
                    <label class="form-check-label">
                        <input type="checkbox" name="api_enabled" class="form-check-input" {{ ($settings['api_enabled'] ?? true) ? 'checked' : '' }}>
                        Enable API Access
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-check-label">
                        <input type="checkbox" name="api_require_auth" class="form-check-input" {{ ($settings['api_require_auth'] ?? true) ? 'checked' : '' }}>
                        Require Authentication
                    </label>
                </div>

                <div class="alert alert-info mt-4">
                    <h5><i class="fas fa-info-circle"></i> API Documentation</h5>
                    <p class="mb-0">API endpoint: <code>{{ url('/api') }}</code></p>
                    <p class="mb-0 mt-2">View full documentation at: <code>{{ url('/api/documentation') }}</code></p>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="save-btn">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
