<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Plans - Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
        }

        body {
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
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
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-menu {
            padding: 1.5rem 0;
            list-style: none;
            margin: 0;
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
        }

        .main-content {
            margin-left: 280px;
            padding: 2rem;
        }

        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .plan-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .plan-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .plan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }

        .plan-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .plan-name {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .plan-price {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary);
            margin: 1rem 0;
        }

        .plan-price small {
            font-size: 1.25rem;
            color: #64748b;
        }

        .plan-features {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
        }

        .plan-features li {
            padding: 0.75rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        .plan-features li:last-child {
            border-bottom: none;
        }

        .plan-features i {
            color: #10b981;
            font-size: 1.1rem;
        }

        .plan-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 2rem;
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            flex: 1;
            transition: all 0.3s ease;
        }

        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102,126,234,0.4);
        }

        .badge-popular {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-status.active {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-status.inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .plan-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin: 1.5rem 0;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 12px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
        }

        .stat-label {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-crown"></i> SUPER ADMIN</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('super.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="{{ route('super.tenants.index') }}"><i class="fas fa-store"></i> Admins</a></li>
            <li><a href="{{ route('super.plans.index') }}" class="active"><i class="fas fa-layer-group"></i> Plans</a></li>
            <li><a href="{{ route('super.financial.index') }}"><i class="fas fa-chart-pie"></i> Financial</a></li>
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

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-layer-group"></i> Subscription Plans</h1>
                    <p class="mb-0">Manage subscription plans and pricing tiers</p>
                </div>
                <a href="{{ route('super.plans.create') }}" class="btn-primary-gradient">
                    <i class="fas fa-plus"></i> Add New Plan
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="plans-grid">
            @forelse($plans as $plan)
            <div class="plan-card">
                @if($plan->is_featured)
                <span class="badge-popular">
                    <i class="fas fa-star"></i> Popular
                </span>
                @endif

                <div class="plan-header">
                    <h3 class="plan-name">{{ $plan->name }}</h3>
                    <span class="badge-status {{ $plan->is_active ? 'active' : 'inactive' }}">
                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <div class="plan-price">
                        Rs. {{ number_format($plan->price_monthly, 0) }}
                        <small>/month</small>
                    </div>
                    <p style="color: #64748b; margin: 0;">{{ $plan->description }}</p>
                </div>

                <div class="plan-stats">
                    <div class="stat-item">
                        <div class="stat-value">{{ $plan->tenants_count ?? 0 }}</div>
                        <div class="stat-label">Active Users</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $plan->trial_days }}</div>
                        <div class="stat-label">Trial Days</div>
                    </div>
                </div>

                <ul class="plan-features">
                    <li><i class="fas fa-check-circle"></i> {{ number_format($plan->max_products) }} Products</li>
                    <li><i class="fas fa-check-circle"></i> {{ number_format($plan->max_orders_per_month) }} Orders/Month</li>
                    <li><i class="fas fa-check-circle"></i> {{ number_format($plan->max_users) }} Users</li>
                    @if($plan->features && is_array($plan->features))
                        @foreach(array_slice($plan->features, 0, 3) as $feature)
                        <li><i class="fas fa-check-circle"></i> {{ $feature }}</li>
                        @endforeach
                    @endif
                </ul>

                <div class="plan-actions">
                    <a href="{{ route('super.plans.edit', $plan) }}" class="btn-primary-gradient">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('super.plans.destroy', $plan) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Delete this plan?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" style="padding: 0.75rem; border-radius: 12px;">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-layer-group" style="font-size: 4rem; color: #cbd5e1;"></i>
                <p style="margin-top: 1rem; color: #64748b;">No plans found</p>
            </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
