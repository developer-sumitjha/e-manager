<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Analytics - Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .chart-card { background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); margin-bottom: 2rem; }
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
            <li><a href="{{ route('super.analytics') }}" class="active"><i class="fas fa-chart-line"></i> Analytics</a></li>
            <li><a href="{{ route('super.security.audit-logs') }}"><i class="fas fa-shield-alt"></i> Security</a></li>
            <li><a href="{{ route('super.settings.general') }}"><i class="fas fa-cog"></i> Settings</a></li>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 1.5rem;">
            <li><a href="{{ route('super.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <form id="logout-form" action="{{ route('super.logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>

    <div class="main-content">
        <div class="chart-card">
            <h1><i class="fas fa-chart-line"></i> Analytics Dashboard</h1>
            <p class="text-muted">Comprehensive platform analytics and insights</p>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="chart-card">
                    <h4><i class="fas fa-users"></i> Admin Growth</h4>
                    <canvas id="tenantGrowthChart" height="100"></canvas>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="chart-card">
                    <h4><i class="fas fa-shopping-cart"></i> Order Trends</h4>
                    <canvas id="orderTrendsChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="chart-card">
            <h4><i class="fas fa-chart-bar"></i> Performance Metrics</h4>
            <div class="row text-center mt-4">
                <div class="col-md-3">
                    <h2 class="text-primary">{{ $stats['total_tenants'] ?? 0 }}</h2>
                    <p class="text-muted">Total Admins</p>
                </div>
                <div class="col-md-3">
                    <h2 class="text-success">{{ $stats['total_orders'] ?? 0 }}</h2>
                    <p class="text-muted">Total Orders</p>
                </div>
                <div class="col-md-3">
                    <h2 class="text-warning">{{ $stats['total_products'] ?? 0 }}</h2>
                    <p class="text-muted">Total Products</p>
                </div>
                <div class="col-md-3">
                    <h2 class="text-info">Rs. {{ number_format($stats['total_revenue'] ?? 0) }}</h2>
                    <p class="text-muted">Total Revenue</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        new Chart(document.getElementById('tenantGrowthChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'New Admins',
                    data: [5, 8, 12, 15, 20, 25],
                    borderColor: '#667eea',
                    tension: 0.4
                }]
            },
            options: { responsive: true, maintainAspectRatio: true }
        });

        new Chart(document.getElementById('orderTrendsChart'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Orders',
                    data: [150, 200, 280, 320, 450, 520],
                    backgroundColor: '#10b981'
                }]
            },
            options: { responsive: true, maintainAspectRatio: true }
        });
    </script>
</body>
</html>
