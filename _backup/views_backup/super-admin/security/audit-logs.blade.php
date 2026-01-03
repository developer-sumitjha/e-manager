<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Security - Audit Logs - Super Admin</title>
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
        .content-card { background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .log-entry { border-bottom: 1px solid #e5e7eb; padding: 1rem 0; }
        .log-entry:last-child { border-bottom: none; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h3><i class="fas fa-crown"></i> SUPER ADMIN</h3></div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('super.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="{{ route('super.tenants.index') }}"><i class="fas fa-store"></i> Admins</a></li>
            <li><a href="{{ route('super.plans.index') }}"><i class="fas fa-layer-group"></i> Plans</a></li>
            <li><a href="{{ route('super.financial.index') }}"><i class="fas fa-chart-pie"></i> Financial</a></li>
            <li><a href="{{ route('super.system.monitor') }}"><i class="fas fa-heartbeat"></i> System Monitor</a></li>
            <li><a href="{{ route('super.communication.index') }}"><i class="fas fa-bullhorn"></i> Communication</a></li>
            <li><a href="{{ route('super.analytics') }}"><i class="fas fa-chart-line"></i> Analytics</a></li>
            <li><a href="{{ route('super.security.audit-logs') }}" class="active"><i class="fas fa-shield-alt"></i> Security</a></li>
            <li><a href="{{ route('super.settings.general') }}"><i class="fas fa-cog"></i> Settings</a></li>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 1.5rem;">
            <li><a href="{{ route('super.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <form id="logout-form" action="{{ route('super.logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>

    <div class="main-content">
        <div class="content-card mb-4">
            <h1><i class="fas fa-shield-alt"></i> Security & Audit Logs</h1>
            <p class="text-muted mb-0">Monitor system security and user activity</p>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="content-card text-center">
                    <h3 class="text-success">{{ $stats['successful_logins'] ?? 0 }}</h3>
                    <p class="text-muted mb-0">Successful Logins</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="content-card text-center">
                    <h3 class="text-danger">{{ $stats['failed_logins'] ?? 0 }}</h3>
                    <p class="text-muted mb-0">Failed Attempts</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="content-card text-center">
                    <h3 class="text-warning">{{ $stats['blocked_ips'] ?? 0 }}</h3>
                    <p class="text-muted mb-0">Blocked IPs</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="content-card text-center">
                    <h3 class="text-info">{{ $stats['active_sessions'] ?? 0 }}</h3>
                    <p class="text-muted mb-0">Active Sessions</p>
                </div>
            </div>
        </div>

        <div class="content-card">
            <h4><i class="fas fa-history"></i> Recent Activity</h4>
            <div class="mt-3">
                @forelse($auditLogs ?? [] as $log)
                <div class="log-entry">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $log->action ?? 'Action' }}</strong>
                            <span class="text-muted">by {{ $log->user ?? 'System' }}</span>
                        </div>
                        <small class="text-muted">{{ $log->created_at ?? now() }}</small>
                    </div>
                    <p class="text-muted small mb-0">{{ $log->description ?? 'Activity logged' }}</p>
                </div>
                @empty
                <p class="text-muted text-center py-4">No audit logs available</p>
                @endforelse
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
