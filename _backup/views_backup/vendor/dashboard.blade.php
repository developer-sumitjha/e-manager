<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vendor Dashboard - {{ $tenant->business_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        .stat-icon.products { background: linear-gradient(135deg, #667eea, #764ba2); }
        .stat-icon.orders { background: linear-gradient(135deg, #10b981, #059669); }
        .stat-icon.users { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .stat-icon.pending { background: linear-gradient(135deg, #f59e0b, #d97706); }
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
                                <a href="#" class="btn btn-outline-info w-100">
                                    <i class="fas fa-chart-bar"></i> Reports
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



