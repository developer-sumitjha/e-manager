<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Super Admin Dashboard - E-Manager Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        body {
            background: #f8fafc;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, var(--primary), var(--secondary));
            color: white;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 800;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .sidebar-menu {
            padding: 1.5rem 0;
            list-style: none;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0.5rem 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            border-left-color: white;
            color: white;
        }

        .sidebar-menu i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .main-content {
            margin-left: 280px;
            min-height: 100vh;
        }

        .dashboard-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 2.5rem 2rem;
            color: white;
            border-radius: 0 0 30px 30px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(102,126,234,0.2);
        }

        .dashboard-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            padding: 0 2rem 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 1.75rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--card-color);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }

        .stat-card.success { --card-color: var(--success); }
        .stat-card.primary { --card-color: var(--primary); }
        .stat-card.warning { --card-color: var(--warning); }
        .stat-card.danger { --card-color: var(--danger); }
        .stat-card.info { --card-color: var(--info); }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.25rem;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            background: linear-gradient(135deg, var(--card-color), transparent);
            color: var(--card-color);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .stat-trend {
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stat-trend.up {
            background: #d1fae5;
            color: #065f46;
        }

        .stat-trend.down {
            background: #fee2e2;
            color: #991b1b;
        }

        .stat-value {
            font-size: 2.25rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            color: #64748b;
            font-weight: 600;
        }

        .stat-details {
            display: flex;
            gap: 1.5rem;
            margin-top: 1.25rem;
            padding-top: 1.25rem;
            border-top: 1px solid #e2e8f0;
        }

        .stat-detail-item {
            flex: 1;
        }

        .stat-detail-value {
            font-size: 1.15rem;
            font-weight: 700;
            color: #334155;
        }

        .stat-detail-label {
            font-size: 0.75rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .content-section {
            padding: 0 2rem 2rem;
        }

        .card-custom {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-title i {
            color: var(--primary);
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .quick-action-btn {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 15px;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102,126,234,0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .quick-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102,126,234,0.4);
            color: white;
        }

        .quick-action-btn i {
            font-size: 2rem;
        }

        .table-custom {
            width: 100%;
        }

        .table-custom thead th {
            padding: 1rem;
            background: #f8fafc;
            font-weight: 700;
            color: #475569;
            text-align: left;
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        .table-custom tbody td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-custom tbody tr:hover {
            background: #f8fafc;
        }

        .badge-custom {
            padding: 0.35rem 0.85rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-custom.success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-custom.info {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-custom.warning {
            background: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-crown"></i> SUPER ADMIN</h3>
            <p style="margin: 0.5rem 0 0; font-size: 0.9rem; opacity: 0.9;">E-Manager Platform</p>
        </div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('super.dashboard') }}" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="{{ route('super.tenants.index') }}"><i class="fas fa-store"></i> Admins</a></li>
            <li><a href="{{ route('super.plans.index') }}"><i class="fas fa-layer-group"></i> Plans</a></li>
            <li><a href="{{ route('super.financial.index') }}"><i class="fas fa-chart-pie"></i> Financial</a></li>
            <li><a href="{{ route('super.system.monitor') }}"><i class="fas fa-heartbeat"></i> System Monitor</a></li>
            <li><a href="{{ route('super.activity-monitor.index') }}"><i class="fas fa-activity"></i> Activity Monitor</a></li>
            <li><a href="{{ route('super.notifications.index') }}"><i class="fas fa-bell"></i> Notifications</a></li>
            <li><a href="{{ route('super.data-validation.index') }}"><i class="fas fa-shield-alt"></i> Data Validation</a></li>
            <li><a href="{{ route('super.communication.index') }}"><i class="fas fa-bullhorn"></i> Communication</a></li>
            <li><a href="{{ route('super.analytics') }}"><i class="fas fa-chart-line"></i> Analytics</a></li>
            <li><a href="{{ route('super.security.audit-logs') }}"><i class="fas fa-shield-alt"></i> Security</a></li>
            <li><a href="{{ route('super.settings.general') }}"><i class="fas fa-cog"></i> Settings</a></li>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 1.5rem;">
            <li><a href="{{ route('super.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <form id="logout-form" action="{{ route('super.logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-chart-line"></i> Super Admin Dashboard</h1>
                    <p style="margin: 0; font-size: 1.1rem; opacity: 0.95;">Welcome back! Here's what's happening with your platform today.</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <!-- Admin Auth Buttons -->
                    <div class="btn-group" role="group">
                        <a href="{{ route('vendor.login') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-store"></i> Admin Login
                        </a>
                        <a href="{{ route('vendor.register') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-user-plus"></i> Admin Register
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <!-- Total Revenue -->
            <div class="stat-card success">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <span class="stat-trend up">
                        <i class="fas fa-arrow-up"></i> +12.5%
                    </span>
                </div>
                <div class="stat-value">Rs. {{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-details">
                    <div class="stat-detail-item">
                        <div class="stat-detail-value">Rs. {{ number_format($stats['revenue_this_month'] ?? 0, 2) }}</div>
                        <div class="stat-detail-label">This Month</div>
                    </div>
                    <div class="stat-detail-item">
                        <div class="stat-detail-value">Rs. {{ number_format($stats['revenue_today'] ?? 0, 2) }}</div>
                        <div class="stat-detail-label">Today</div>
                    </div>
                </div>
            </div>

            <!-- Total Admins -->
            <div class="stat-card primary">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <span class="stat-trend up">
                        <i class="fas fa-arrow-up"></i> +{{ $stats['new_today'] ?? 0 }}
                    </span>
                </div>
                <div class="stat-value">{{ $stats['total_tenants'] ?? 0 }}</div>
                <div class="stat-label">Total Admins</div>
                <div class="stat-details">
                    <div class="stat-detail-item">
                        <div class="stat-detail-value">{{ $stats['active_tenants'] ?? 0 }}</div>
                        <div class="stat-detail-label">Active</div>
                    </div>
                    <div class="stat-detail-item">
                        <div class="stat-detail-value">{{ $stats['trial_tenants'] ?? 0 }}</div>
                        <div class="stat-detail-label">Trial</div>
                    </div>
                </div>
            </div>

            <!-- Active Subscriptions -->
            <div class="stat-card info">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <span class="stat-trend up">
                        <i class="fas fa-arrow-up"></i> +5.2%
                    </span>
                </div>
                <div class="stat-value">{{ $stats['active_subscriptions'] ?? 0 }}</div>
                <div class="stat-label">Active Subscriptions</div>
                <div class="stat-details">
                    <div class="stat-detail-item">
                        <div class="stat-detail-value">{{ $stats['expiring_this_week'] ?? 0 }}</div>
                        <div class="stat-detail-label">Expiring</div>
                    </div>
                    <div class="stat-detail-item">
                        <div class="stat-detail-value">{{ $stats['expired_subscriptions'] ?? 0 }}</div>
                        <div class="stat-detail-label">Expired</div>
                    </div>
                </div>
            </div>

            <!-- Platform Orders -->
            <div class="stat-card warning">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <span class="stat-trend up">
                        <i class="fas fa-arrow-up"></i> +18.3%
                    </span>
                </div>
                <div class="stat-value">{{ number_format($stats['total_orders'] ?? 0) }}</div>
                <div class="stat-label">Total Orders</div>
                <div class="stat-details">
                    <div class="stat-detail-item">
                        <div class="stat-detail-value">{{ $stats['orders_today'] ?? 0 }}</div>
                        <div class="stat-detail-label">Today</div>
                    </div>
                    <div class="stat-detail-item">
                        <div class="stat-detail-value">{{ $stats['orders_this_month'] ?? 0 }}</div>
                        <div class="stat-detail-label">This Month</div>
                    </div>
                </div>
            </div>

            <!-- MRR -->
            <div class="stat-card success">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <span class="stat-trend up">
                        <i class="fas fa-arrow-up"></i> +9.7%
                    </span>
                </div>
                <div class="stat-value">Rs. {{ number_format($stats['mrr'] ?? 0, 2) }}</div>
                <div class="stat-label">Monthly Recurring Revenue</div>
                <div class="stat-details">
                    <div class="stat-detail-item">
                        <div class="stat-detail-value">Rs. {{ number_format($stats['arr'] ?? 0, 2) }}</div>
                        <div class="stat-detail-label">ARR</div>
                    </div>
                    <div class="stat-detail-item">
                        <div class="stat-detail-value">Rs. {{ number_format($stats['arpu'] ?? 0, 2) }}</div>
                        <div class="stat-detail-label">ARPU</div>
                    </div>
                </div>
            </div>

            <!-- Pending Approvals -->
            <div class="stat-card danger">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    @if(($stats['pending_tenants'] ?? 0) > 0)
                        <span class="stat-trend" style="background: #fef3c7; color: #92400e;">
                            <i class="fas fa-exclamation"></i> Action Needed
                        </span>
                    @endif
                </div>
                <div class="stat-value">{{ $stats['pending_tenants'] ?? 0 }}</div>
                <div class="stat-label">Pending Approvals</div>
                <div class="stat-details">
                    <div class="stat-detail-item">
                        <div class="stat-detail-value">{{ $stats['pending_payments'] ?? 0 }}</div>
                        <div class="stat-detail-label">Payments</div>
                    </div>
                    <div class="stat-detail-item">
                        <div class="stat-detail-value">{{ $stats['support_tickets'] ?? 0 }}</div>
                        <div class="stat-detail-label">Tickets</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="content-section">
            <div class="card-custom">
                <h3 class="card-title">
                    <i class="fas fa-bolt"></i>
                    Quick Actions
                </h3>
                <div class="quick-actions">
                    <a href="{{ route('super.tenants.index') }}" class="quick-action-btn">
                        <i class="fas fa-store"></i>
                        <span>Manage Admins</span>
                    </a>
                    <a href="{{ route('super.tenants.create') }}" class="quick-action-btn" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Admin</span>
                    </a>
                    <a href="{{ route('super.plans.index') }}" class="quick-action-btn" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-layer-group"></i>
                        <span>Plans</span>
                    </a>
                    <a href="{{ route('super.financial.index') }}" class="quick-action-btn" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                        <i class="fas fa-chart-pie"></i>
                        <span>Financial</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="content-section">
            <div class="row">
                <!-- Revenue Chart -->
                <div class="col-lg-8">
                    <div class="card-custom">
                        <h3 class="card-title">
                            <i class="fas fa-chart-area"></i>
                            Revenue Analytics
                        </h3>
                        <canvas id="revenueChart" height="80"></canvas>
                    </div>
                </div>

                <!-- Admin Distribution -->
                <div class="col-lg-4">
                    <div class="card-custom">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie"></i>
                            Admin Distribution
                        </h3>
                        <canvas id="tenantChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Admins Table -->
        <div class="content-section">
            <div class="card-custom">
                <h3 class="card-title">
                    <i class="fas fa-trophy"></i>
                    Top Performing Admins
                </h3>
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Orders</th>
                            <th>Revenue</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topTenantsData ?? [] as $tenant)
                        <tr>
                            <td><strong>{{ $tenant['business_name'] }}</strong><br><small class="text-muted">{{ $tenant['business_email'] }}</small></td>
                            <td><strong>{{ $tenant['orders_count'] ?? 0 }}</strong></td>
                            <td><strong>Rs. {{ number_format($tenant['revenue'] ?? 0, 2) }}</strong></td>
                            <td><span class="badge-custom {{ $tenant['status'] == 'active' ? 'success' : 'info' }}">{{ ucfirst($tenant['status']) }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">No data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const gradient = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(102, 126, 234, 0.3)');
        gradient.addColorStop(1, 'rgba(102, 126, 234, 0.0)');
        
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode($revenueData ?? [12000, 19000, 15000, 25000, 22000, 30000]) !!},
                    borderColor: '#667eea',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: Rs. ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: function(value) {
                                return 'Rs. ' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Tenant Distribution Chart
    const tenantCtx = document.getElementById('tenantChart');
    if (tenantCtx) {
        new Chart(tenantCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Trial', 'Suspended', 'Pending'],
                datasets: [{
                    data: [
                        {{ $stats['active_tenants'] ?? 0 }},
                        {{ $stats['trial_tenants'] ?? 0 }},
                        {{ $stats['suspended_tenants'] ?? 0 }},
                        {{ $stats['pending_tenants'] ?? 0 }}
                    ],
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                    borderWidth: 4,
                    borderColor: '#fff',
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: { size: 12, weight: 'bold' }
                        }
                    }
                }
            }
        });
    }
    </script>
</body>
</html>
