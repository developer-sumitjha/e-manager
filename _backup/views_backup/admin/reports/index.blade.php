@extends('admin.layouts.app')

@section('title', 'Reports & Analytics')
@section('page-title', 'Reports & Analytics')

@section('content')
<!-- Date Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.reports.export', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Stats Overview -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-muted mb-0">Total Sales</h6>
                    <h3 class="mb-0">${{ number_format($totalSales, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-muted mb-0">Total Orders</h6>
                    <h3 class="mb-0">{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-muted mb-0">Avg Order Value</h6>
                    <h3 class="mb-0">${{ number_format($averageOrderValue, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-muted mb-0">Pending Orders</h6>
                    <h3 class="mb-0">{{ $pendingOrders }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Daily Sales Chart -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Daily Sales (Last 7 Days)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Orders</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailySales as $sale)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($sale->date)->format('M d, Y') }}</td>
                                <td>{{ $sale->orders }}</td>
                                <td>${{ number_format($sale->revenue, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales by Category -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Sales by Category</h5>
            </div>
            <div class="card-body">
                @foreach($salesByCategory as $category)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>{{ $category->name }}</span>
                        <strong>${{ number_format($category->total_sales, 2) }}</strong>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar" style="width: {{ $salesByCategory->max('total_sales') > 0 ? ($category->total_sales / $salesByCategory->max('total_sales')) * 100 : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Top Products -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Top Selling Products</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Sold</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td><span class="badge bg-success">{{ $product->total_sold }}</span></td>
                                <td>${{ number_format($product->price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Customers -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Top Customers</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Orders</th>
                                <th>Total Spent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topCustomers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td><span class="badge bg-primary">{{ $customer->order_count }}</span></td>
                                <td>${{ number_format($customer->total_spent, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No customer data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Alert -->
@if($lowStockProducts->count() > 0 || $outOfStock > 0)
<div class="row g-4 mt-2">
    <div class="col-md-12">
        <div class="card border-warning">
            <div class="card-header bg-warning bg-opacity-10">
                <h5 class="mb-0 text-warning">
                    <i class="fas fa-exclamation-triangle"></i> Inventory Alerts
                </h5>
            </div>
            <div class="card-body">
                @if($outOfStock > 0)
                <div class="alert alert-danger">
                    <strong>{{ $outOfStock }}</strong> product(s) are out of stock!
                </div>
                @endif
                
                <h6>Low Stock Products (Below 20 units):</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Current Stock</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->sku }}</td>
                                <td><span class="badge bg-warning">{{ $product->stock }}</span></td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary">
                                        Update Stock
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection






