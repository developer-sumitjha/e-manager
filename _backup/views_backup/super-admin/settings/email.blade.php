<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Settings - Super Admin</title>
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
            <a href="{{ route('super.settings.email') }}" class="tab-link active">
                <i class="fas fa-envelope"></i> Email
            </a>
            <a href="{{ route('super.settings.payments') }}" class="tab-link">
                <i class="fas fa-credit-card"></i> Payments
            </a>
            <a href="{{ route('super.settings.api') }}" class="tab-link">
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
                <input type="hidden" name="category" value="email">

                <h4><i class="fas fa-server"></i> SMTP Configuration</h4>
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">SMTP Host</label>
                        <input type="text" name="mail_host" class="form-control" value="{{ $settings['mail_host'] ?? 'smtp.gmail.com' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">SMTP Port</label>
                        <input type="number" name="mail_port" class="form-control" value="{{ $settings['mail_port'] ?? 587 }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">SMTP Username</label>
                        <input type="text" name="mail_username" class="form-control" value="{{ $settings['mail_username'] ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">SMTP Password</label>
                        <input type="password" name="mail_password" class="form-control" value="{{ $settings['mail_password'] ?? '' }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">From Email</label>
                        <input type="email" name="mail_from_address" class="form-control" value="{{ $settings['mail_from_address'] ?? 'noreply@e-manager.com' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">From Name</label>
                        <input type="text" name="mail_from_name" class="form-control" value="{{ $settings['mail_from_name'] ?? 'E-Manager' }}">
                    </div>
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
