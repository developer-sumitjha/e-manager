<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Super Admin Dashboard - E-Manager Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #3b82f6;
            --sidebar-width: 280px;
        }

        body {
            background: #f3f4f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .sidebar-menu {
            padding: 1.5rem 0;
            list-style: none;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-menu a i {
            width: 24px;
            margin-right: 1rem;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.2);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .top-nav {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .content-area {
            padding: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-left: 4px solid;
        }

        .stat-card.total { border-left-color: #3b82f6; }
        .stat-card.active { border-left-color: #10b981; }
        .stat-card.trial { border-left-color: #f59e0b; }
        .stat-card.revenue { border-left-color: #8b5cf6; }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .stat-label {
            color: #6b7280;
            margin: 0.5rem 0 0 0;
        }

        .section-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-crown"></i>
            </div>
            <h3>Super Admin</h3>
            <p style="margin: 0; opacity: 0.9; font-size: 0.9rem;">Platform Management</p>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('super.dashboard') }}" class="active">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('super.tenants.index') }}">
                    <i class="fas fa-building"></i>
                    <span>Tenants</span>
                </a>
            </li>
            <li>
                <a href="{{ route('super.subscriptions.index') }}">
                    <i class="fas fa-credit-card"></i>
                    <span>Subscriptions</span>
                </a>
            </li>
            <li>
                <a href="{{ route('super.plans.index') }}">
                    <i class="fas fa-tags"></i>
                    <span>Plans</span>
                </a>
            </li>
            <li>
                <a href="{{ route('super.payments.index') }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Payments</span>
                </a>
            </li>
            <li>
                <a href="{{ route('super.analytics') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Analytics</span>
                </a>
            </li>
            <li style="margin-top: 2rem; padding: 0 1.5rem;">
                <form action="{{ route('super.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-nav">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h4 mb-0">Platform Dashboard</h1>
                <div>
                    <strong>{{ Auth::guard('super_admin')->user()->name }}</strong>
                    <span class="badge bg-primary">{{ Auth::guard('super_admin')->user()->role }}</span>
                </div>
            </div>
        </div>

        <div class="content-area">
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card total">
                    <h3 class="stat-value">{{ $stats['total_tenants'] }}</h3>
                    <p class="stat-label">Total Tenants</p>
                </div>
                <div class="stat-card active">
                    <h3 class="stat-value">{{ $stats['active_tenants'] }}</h3>
                    <p class="stat-label">Active Tenants</p>
                </div>
                <div class="stat-card trial">
                    <h3 class="stat-value">{{ $stats['trial_tenants'] }}</h3>
                    <p class="stat-label">On Trial</p>
                </div>
                <div class="stat-card revenue">
                    <h3 class="stat-value">Rs. {{ number_format($stats['revenue_this_month'], 0) }}</h3>
                    <p class="stat-label">Revenue This Month</p>
                    @if($stats['revenue_growth'] > 0)
                        <small class="text-success">
                            <i class="fas fa-arrow-up"></i> {{ number_format($stats['revenue_growth'], 1) }}%
                        </small>
                    @endif
                </div>
            </div>

            <!-- Recent Tenants -->
            <div class="section-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Recent Tenants</h4>
                    <a href="{{ route('super.tenants.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Business Name</th>
                                <th>Subdomain</th>
                                <th>Plan</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTenants as $tenant)
                            <tr>
                                <td>{{ $tenant->tenant_id }}</td>
                                <td>
                                    <strong>{{ $tenant->business_name }}</strong><br>
                                    <small class="text-muted">{{ $tenant->business_email }}</small>
                                </td>
                                <td>{{ $tenant->subdomain }}.emanager.com</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $tenant->currentPlan->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if($tenant->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($tenant->status == 'trial')
                                        <span class="badge bg-warning">Trial</span>
                                    @elseif($tenant->status == 'suspended')
                                        <span class="badge bg-danger">Suspended</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($tenant->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $tenant->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('super.tenants.show', $tenant) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted">No tenants found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="section-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Recent Payments</h4>
                    <a href="{{ route('super.payments.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Payment ID</th>
                                <th>Tenant</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $payment)
                            <tr>
                                <td>{{ $payment->payment_id }}</td>
                                <td>{{ $payment->tenant->business_name }}</td>
                                <td>Rs. {{ number_format($payment->amount, 0) }}</td>
                                <td>{{ ucfirst($payment->payment_method) }}</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>{{ $payment->paid_at ? $payment->paid_at->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted">No payments found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>





