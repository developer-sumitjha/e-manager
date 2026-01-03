<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Settings - Super Admin</title>
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
        .tab-link { padding: 1rem 1.5rem; color: #64748b; text-decoration: none; border-bottom: 3px solid transparent; transition: all 0.3s; }
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
            <a href="{{ route('super.settings.email') }}" class="tab-link">
                <i class="fas fa-envelope"></i> Email
            </a>
            <a href="{{ route('super.settings.payments') }}" class="tab-link active">
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
                <input type="hidden" name="category" value="payments">

                <h4><i class="fas fa-credit-card"></i> Payment Gateway Settings</h4>
                
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">eSewa Merchant Code</label>
                        <input type="text" name="esewa_merchant_code" class="form-control" value="{{ $settings['esewa_merchant_code'] ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">eSewa Secret Key</label>
                        <input type="password" name="esewa_secret_key" class="form-control" value="{{ $settings['esewa_secret_key'] ?? '' }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Khalti Public Key</label>
                        <input type="text" name="khalti_public_key" class="form-control" value="{{ $settings['khalti_public_key'] ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Khalti Secret Key</label>
                        <input type="password" name="khalti_secret_key" class="form-control" value="{{ $settings['khalti_secret_key'] ?? '' }}">
                    </div>
                </div>

                <h4 class="mt-4"><i class="fas fa-dollar-sign"></i> Commission Settings</h4>
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Platform Commission (%)</label>
                        <input type="number" name="platform_commission" class="form-control" step="0.01" value="{{ $settings['platform_commission'] ?? 0 }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Transaction Fee (Rs.)</label>
                        <input type="number" name="transaction_fee" class="form-control" step="0.01" value="{{ $settings['transaction_fee'] ?? 0 }}">
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
