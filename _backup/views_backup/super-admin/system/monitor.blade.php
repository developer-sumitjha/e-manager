<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Monitor - Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #667eea; }
        body { background: #f8fafc; font-family: 'Inter', sans-serif; }
        .sidebar { position: fixed; top: 0; left: 0; width: 280px; height: 100vh; background: linear-gradient(180deg, #667eea, #764ba2); color: white; overflow-y: auto; }
        .sidebar-header { padding: 2rem 1.5rem; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-menu { padding: 1.5rem 0; list-style: none; margin: 0; }
        .sidebar-menu a { display: flex; align-items: center; padding: 0.875rem 1.5rem; color: rgba(255,255,255,0.9); text-decoration: none; transition: all 0.3s; border-left: 4px solid transparent; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: rgba(255,255,255,0.1); border-left-color: white; color: white; }
        .sidebar-menu i { width: 24px; margin-right: 12px; }
        .main-content { margin-left: 280px; padding: 2rem; }
        .page-header { background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); margin-bottom: 2rem; }
        .health-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; }
        .health-card { background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); text-align: center; }
        .health-icon { width: 80px; height: 80px; margin: 0 auto 1rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; }
        .health-icon.good { background: #d1fae5; color: #10b981; }
        .health-icon.warning { background: #fef3c7; color: #f59e0b; }
        .health-icon.critical { background: #fee2e2; color: #ef4444; }
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
            <li><a href="{{ route('super.system.monitor') }}" class="active"><i class="fas fa-heartbeat"></i> System Monitor</a></li>
            <li><a href="{{ route('super.communication.index') }}"><i class="fas fa-bullhorn"></i> Communication</a></li>
            <li><a href="{{ route('super.analytics') }}"><i class="fas fa-chart-line"></i> Analytics</a></li>
            <li><a href="{{ route('super.security.audit-logs') }}"><i class="fas fa-shield-alt"></i> Security</a></li>
            <li><a href="{{ route('super.settings.general') }}"><i class="fas fa-cog"></i> Settings</a></li>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 1.5rem;">
            <li><a href="{{ route('super.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <form id="logout-form" action="{{ route('super.logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>

    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-heartbeat"></i> System Monitor</h1>
            <p class="mb-0">Monitor system health, performance, and resources</p>
        </div>

        <div class="health-grid">
            <div class="health-card">
                <div class="health-icon good">
                    <i class="fas fa-server"></i>
                </div>
                <h4>Server Status</h4>
                <p class="text-success mb-0"><strong>Online</strong></p>
                <small class="text-muted">Uptime: 99.9%</small>
            </div>

            <div class="health-card">
                <div class="health-icon good">
                    <i class="fas fa-database"></i>
                </div>
                <h4>Database</h4>
                <p class="text-success mb-0"><strong>Healthy</strong></p>
                <small class="text-muted">{{ $stats['database_size'] ?? '0 MB' }}</small>
            </div>

            <div class="health-card">
                <div class="health-icon warning">
                    <i class="fas fa-hdd"></i>
                </div>
                <h4>Storage</h4>
                <p class="text-warning mb-0"><strong>75% Used</strong></p>
                <small class="text-muted">45GB / 60GB</small>
            </div>

            <div class="health-card">
                <div class="health-icon good">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <h4>Performance</h4>
                <p class="text-success mb-0"><strong>Fast</strong></p>
                <small class="text-muted">Avg: 120ms</small>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-6">
                <div style="background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                    <h4><i class="fas fa-chart-line"></i> System Metrics</h4>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-3"><strong>Total Records:</strong> {{ number_format($stats['total_records'] ?? 0) }}</li>
                        <li class="mb-3"><strong>Active Sessions:</strong> {{ $stats['active_sessions'] ?? 0 }}</li>
                        <li class="mb-3"><strong>Cache Hit Rate:</strong> {{ $stats['cache_hit_rate'] ?? 'N/A' }}</li>
                        <li class="mb-3"><strong>Queue Jobs:</strong> 0 pending</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-6">
                <div style="background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                    <h4><i class="fas fa-tools"></i> Quick Actions</h4>
                    <div class="d-grid gap-2 mt-3">
                        <form action="{{ route('super.system.clear-cache') }}" method="POST">
                            @csrf
                            <button class="btn btn-primary w-100"><i class="fas fa-broom"></i> Clear Cache</button>
                        </form>
                        <a href="{{ route('super.system.logs') }}" class="btn btn-outline-primary"><i class="fas fa-file-alt"></i> View Logs</a>
                        <a href="{{ route('super.system.database') }}" class="btn btn-outline-primary"><i class="fas fa-database"></i> Database Info</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
