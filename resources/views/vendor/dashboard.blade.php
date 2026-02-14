<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vendor Dashboard - {{ $tenant->business_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #ffffff;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #1e293b;
            line-height: 1.6;
        }
        
        /* Modern Navbar - Clean Header Style */
        .navbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 600;
            font-size: 1.25rem;
            color: #1e293b !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .navbar-brand i {
            font-size: 1.5rem;
            color: #3b82f6;
        }
        
        .nav-link {
            color: #64748b !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
        }
        
        .nav-link:hover {
            color: #1e293b !important;
        }
        
        .dropdown-menu {
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 0.5rem;
        }
        
        .dropdown-item {
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .dropdown-item:hover {
            background: #f1f5f9;
        }
        
        /* Container */
        .container {
            max-width: 1400px;
            padding: 2rem 1.5rem;
        }
        
        /* Cards - Modern Clean Style */
        .card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.25rem 1.5rem;
        }
        
        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Stat Cards - Cemdash Style */
        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.2s ease;
            height: 100%;
        }
        
        .stat-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            flex-shrink: 0;
        }
        
        .stat-icon.products { 
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }
        
        .stat-icon.orders { 
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .stat-icon.users { 
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }
        
        .stat-icon.pending { 
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .stat-card h3 {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            line-height: 1.2;
        }
        
        .stat-card p {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0.25rem 0 0 0;
            font-weight: 500;
        }
        
        /* Buttons - Modern Style */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            border-width: 1.5px;
        }
        
        .btn-outline-primary {
            color: #3b82f6;
            border-color: #3b82f6;
        }
        
        .btn-outline-primary:hover {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
        
        .btn-outline-success {
            color: #10b981;
            border-color: #10b981;
        }
        
        .btn-outline-success:hover {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }
        
        .btn-outline-info {
            color: #06b6d4;
            border-color: #06b6d4;
        }
        
        .btn-outline-info:hover {
            background: #06b6d4;
            border-color: #06b6d4;
            color: white;
        }
        
        .btn-outline-secondary {
            color: #64748b;
            border-color: #64748b;
        }
        
        .btn-outline-secondary:hover {
            background: #64748b;
            border-color: #64748b;
            color: white;
        }
        
        /* Badges - Modern Style */
        .badge {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 6px;
            border: none;
        }
        
        .bg-success {
            background: #d1fae5 !important;
            color: #065f46 !important;
        }
        
        .bg-warning {
            background: #fef3c7 !important;
            color: #92400e !important;
        }
        
        .bg-secondary {
            background: #f1f5f9 !important;
            color: #475569 !important;
        }
        
        /* Typography */
        h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        
        h5 {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
        }
        
        .text-muted {
            color: #64748b !important;
        }
        
        /* Tables and Lists */
        .border-bottom {
            border-bottom: 1px solid #e2e8f0 !important;
        }
        
        .py-2 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        
        /* Alerts */
        .alert {
            border-radius: 8px;
            border: 1px solid;
            padding: 0.875rem 1rem;
        }
        
        .alert-success {
            background: #d1fae5;
            border-color: #a7f3d0;
            color: #065f46;
        }
        
        /* Spacing */
        .mb-4 {
            margin-bottom: 1.5rem;
        }
        
        .mb-3 {
            margin-bottom: 1rem;
        }
        
        .mb-2 {
            margin-bottom: 0.5rem;
        }
        
        .mt-4 {
            margin-top: 1.5rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .stat-card {
                margin-bottom: 1rem;
            }
        }
        
        /* Clean shadows */
        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }
        
        /* Text colors */
        strong {
            font-weight: 600;
            color: #1e293b;
        }
        
        small {
            font-size: 0.8125rem;
            color: #64748b;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-store"></i> {{ $tenant->business_name }}
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('vendor.site-builder.index') }}">
                    <i class="fas fa-paint-brush"></i> Site Builder
                </a>
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('vendor.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title">
                            <i class="fas fa-tachometer-alt text-primary"></i>
                            Welcome to your Dashboard
                        </h2>
                        <p class="card-text text-muted">
                            Manage your business with ease. Track your performance and grow your sales.
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Business:</strong> {{ $tenant->business_name }}<br>
                                <strong>Status:</strong> 
                                <span class="badge bg-{{ $tenant->status === 'active' ? 'success' : ($tenant->status === 'trial' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($tenant->status) }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <strong>Plan Limits:</strong><br>
                                Products: {{ $stats['total_products'] }} / {{ $tenant->max_products }}<br>
                                Users: {{ $stats['total_users'] }} / {{ $tenant->max_users }}<br>
                                Orders: {{ $stats['total_orders'] }} / {{ $tenant->max_orders }}/month
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon products me-3">
                            <i class="fas fa-box"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['total_products'] }}</h3>
                            <p class="text-muted mb-0">Total Products</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon orders me-3">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                            <p class="text-muted mb-0">Total Orders</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon users me-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                            <p class="text-muted mb-0">Total Users</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon pending me-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['pending_orders'] }}</h3>
                            <p class="text-muted mb-0">Pending Orders</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders & Low Stock -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart text-primary"></i> Recent Orders</h5>
                    </div>
                    <div class="card-body">
                        @if($recentOrders->count() > 0)
                            @foreach($recentOrders as $order)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <strong>#{{ $order->order_number }}</strong><br>
                                        <small class="text-muted">{{ $order->user->name ?? 'Guest' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : 'success' }}">
                                            {{ ucfirst($order->status) }}
                                        </span><br>
                                        <small class="text-muted">Rs. {{ number_format($order->total_amount, 2) }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center">No orders yet</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle text-warning"></i> Low Stock Products</h5>
                    </div>
                    <div class="card-body">
                        @if($lowStockProducts->count() > 0)
                            @foreach($lowStockProducts as $product)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <strong>{{ $product->name }}</strong><br>
                                        <small class="text-muted">SKU: {{ $product->sku }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-warning">{{ $product->stock }} left</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center">All products are well stocked</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-bolt text-primary"></i> Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <a href="#" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-plus"></i> Add Product
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="#" class="btn btn-outline-success w-100">
                                    <i class="fas fa-shopping-cart"></i> View Orders
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('vendor.site-builder.index') }}" class="btn btn-outline-info w-100">
                                    <i class="fas fa-paint-brush"></i> Site Builder
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="#" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-cog"></i> Settings
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>





