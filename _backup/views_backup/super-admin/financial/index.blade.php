<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Management - Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --primary: #667eea; --secondary: #764ba2; }
        body { background: #f8fafc; font-family: 'Inter', sans-serif; }
        .sidebar { position: fixed; top: 0; left: 0; width: 280px; height: 100vh; background: linear-gradient(180deg, var(--primary), var(--secondary)); color: white; overflow-y: auto; z-index: 1000; }
        .sidebar-header { padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); text-align: center; }
        .sidebar-menu { padding: 1.5rem 0; list-style: none; margin: 0; }
        .sidebar-menu a { display: flex; align-items: center; padding: 0.875rem 1.5rem; color: rgba(255,255,255,0.9); text-decoration: none; transition: all 0.3s ease; border-left: 4px solid transparent; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: rgba(255,255,255,0.1); border-left-color: white; color: white; }
        .sidebar-menu i { width: 24px; margin-right: 12px; }
        .main-content { margin-left: 280px; padding: 2rem; }
        .page-header { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 2rem; border-radius: 20px; margin-bottom: 2rem; }
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin: 2rem 0; }
        .stat-card { background: white; padding: 1.75rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .stat-value { font-size: 2rem; font-weight: 800; color: var(--primary); }
        .stat-label { color: #64748b; margin-top: 0.5rem; }
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
            <li><a href="{{ route('super.financial.index') }}" class="active"><i class="fas fa-chart-pie"></i> Financial</a></li>
            <li><a href="{{ route('super.system.monitor') }}"><i class="fas fa-heartbeat"></i> System Monitor</a></li>
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
            <h1><i class="fas fa-chart-pie"></i> Financial Management</h1>
            <p class="mb-0">Track revenue, payments, and financial performance</p>
        </div>

        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-value">Rs. {{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">Rs. {{ number_format($stats['revenue_this_month'] ?? 0, 2) }}</div>
                <div class="stat-label">This Month</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">Rs. {{ number_format($stats['mrr'] ?? 0, 2) }}</div>
                <div class="stat-label">MRR</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">Rs. {{ number_format($stats['arr'] ?? 0, 2) }}</div>
                <div class="stat-label">ARR</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="chart-card">
                    <h3><i class="fas fa-chart-line"></i> Revenue Trend</h3>
                    <canvas id="revenueChart" height="80"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="chart-card">
                    <h3><i class="fas fa-chart-pie"></i> Payment Methods</h3>
                    <canvas id="paymentChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="chart-card">
            <h3><i class="fas fa-money-bill-wave"></i> Recent Transactions</h3>
            <table class="table">
                <thead>
                    <tr><th>Date</th><th>Admin</th><th>Amount</th><th>Method</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($recentPayments ?? [] as $payment)
                    <tr>
                        <td>{{ $payment->created_at->format('M d, Y') }}</td>
                        <td>{{ $payment->tenant->business_name ?? 'N/A' }}</td>
                        <td>Rs. {{ number_format($payment->amount, 2) }}</td>
                        <td>{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                        <td><span class="badge bg-success">{{ ucfirst($payment->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">No transactions yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueLabels ?? ['Jan','Feb','Mar','Apr','May','Jun']) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode($revenueData ?? [0,0,0,0,0,0]) !!},
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102,126,234,0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: { responsive: true, maintainAspectRatio: true }
        });

        new Chart(document.getElementById('paymentChart'), {
            type: 'doughnut',
            data: {
                labels: ['Online', 'Cash', 'Bank Transfer'],
                datasets: [{
                    data: [60, 25, 15],
                    backgroundColor: ['#667eea', '#10b981', '#f59e0b']
                }]
            },
            options: { responsive: true, maintainAspectRatio: true }
        });
    </script>
</body>
</html>
