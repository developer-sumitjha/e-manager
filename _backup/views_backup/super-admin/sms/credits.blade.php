<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SMS Credits - Super Admin</title>
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
        .content-card { background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); max-width: 700px; margin: 0 auto; }
        .credit-display { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 3rem; border-radius: 20px; text-align: center; margin-bottom: 2rem; }
        .credit-value { font-size: 4rem; font-weight: 800; }
        .stats-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin: 2rem 0; }
        .stat-box { background: #f8fafc; padding: 1.5rem; border-radius: 12px; text-align: center; }
        .stat-box strong { font-size: 1.5rem; color: var(--primary); }
        .btn-add { background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 0.75rem 2rem; border-radius: 12px; border: none; width: 100%; }
        .sms-submenu { padding-left: 2.5rem !important; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h3><i class="fas fa-crown"></i> SUPER ADMIN</h3></div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('super.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="{{ route('super.sms.index') }}"><i class="fas fa-sms"></i> SMS System</a></li>
            <li><a href="{{ route('super.sms.templates') }}" class="sms-submenu"><i class="fas fa-file-alt"></i> Templates</a></li>
            <li><a href="{{ route('super.sms.campaigns') }}" class="sms-submenu"><i class="fas fa-bullhorn"></i> Campaigns</a></li>
            <li><a href="{{ route('super.sms.send-single') }}" class="sms-submenu"><i class="fas fa-paper-plane"></i> Send SMS</a></li>
            <li><a href="{{ route('super.sms.logs') }}" class="sms-submenu"><i class="fas fa-list"></i> SMS Logs</a></li>
            <li><a href="{{ route('super.sms.credits') }}" class="sms-submenu active"><i class="fas fa-coins"></i> Credits</a></li>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 1.5rem;">
            <li><a href="{{ route('super.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <form id="logout-form" action="{{ route('super.logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>

    <div class="main-content">
        <div class="content-card">
            <h2 class="text-center mb-4"><i class="fas fa-coins"></i> SMS Credits Management</h2>

            @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check"></i> {{ session('success') }}</div>
            @endif

            <div class="credit-display">
                <div class="credit-value">{{ number_format($credits->balance) }}</div>
                <p class="mb-0" style="font-size: 1.25rem;">Available SMS Credits</p>
            </div>

            <div class="stats-row">
                <div class="stat-box">
                    <strong>{{ number_format($credits->total_purchased) }}</strong>
                    <p class="mb-0 text-muted">Total Purchased</p>
                </div>
                <div class="stat-box">
                    <strong>{{ number_format($credits->total_used) }}</strong>
                    <p class="mb-0 text-muted">Total Used</p>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Cost per SMS:</strong> Rs. {{ number_format($credits->cost_per_sms, 2) }}
            </div>

            <form action="{{ route('super.sms.add-credits') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Add Credits</label>
                    <input type="number" name="amount" class="form-control" min="1" placeholder="Enter amount" required>
                </div>
                <button type="submit" class="btn-add">
                    <i class="fas fa-plus"></i> Add Credits
                </button>
            </form>

            <div class="mt-4 p-3" style="background: #fef3c7; border-radius: 12px;">
                <strong>Quick Purchase:</strong>
                <div class="d-flex gap-2 mt-2">
                    <button class="btn btn-sm btn-warning" onclick="document.querySelector('input[name=amount]').value=100">100</button>
                    <button class="btn btn-sm btn-warning" onclick="document.querySelector('input[name=amount]').value=500">500</button>
                    <button class="btn btn-sm btn-warning" onclick="document.querySelector('input[name=amount]').value=1000">1000</button>
                    <button class="btn btn-sm btn-warning" onclick="document.querySelector('input[name=amount]').value=5000">5000</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
