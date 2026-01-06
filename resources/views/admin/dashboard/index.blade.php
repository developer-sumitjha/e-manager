@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back! Here\'s what\'s happening with your business today.')

@section('breadcrumb')
    <div class="breadcrumb-item active">Dashboard</div>
@endsection

@section('content')
<!-- Stats Grid -->
<div class="stats-grid mb-5">
    <div class="stat-card fade-in">
        <div class="stat-card-header">
            <div class="stat-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"></path>
                    <rect x="9" y="3" width="6" height="4" rx="1"></rect>
                    <path d="M9 12h6"></path>
                    <path d="M9 16h6"></path>
                </svg>
            </div>
            <div class="stat-trend" data-trend="{{ $stats['orders_trend'] ?? 0 }}">
                <i class="fas fa-arrow-up" style="display: none;"></i>
                <i class="fas fa-arrow-down" style="display: none;"></i>
                <i class="fas fa-minus" style="display: none;"></i>
                <span>0%</span>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-value" data-count="{{ $stats['total_orders'] ?? 0 }}">0</div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-description">vs last month</div>
        </div>
    </div>
    
    <div class="stat-card fade-in" style="animation-delay: 0.1s">
        <div class="stat-card-header">
            <div class="stat-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <div class="stat-trend" data-trend="{{ $stats['revenue_trend'] ?? 0 }}">
                <i class="fas fa-arrow-up" style="display: none;"></i>
                <i class="fas fa-arrow-down" style="display: none;"></i>
                <i class="fas fa-minus" style="display: none;"></i>
                <span>0%</span>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-value">Rs. {{ number_format($stats['total_revenue'] ?? 0) }}</div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-description">vs last month</div>
        </div>
    </div>
    
    <div class="stat-card fade-in" style="animation-delay: 0.2s">
        <div class="stat-card-header">
            <div class="stat-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </div>
            <div class="stat-trend" data-trend="{{ $stats['products_trend'] ?? 0 }}">
                <i class="fas fa-arrow-up" style="display: none;"></i>
                <i class="fas fa-arrow-down" style="display: none;"></i>
                <i class="fas fa-minus" style="display: none;"></i>
                <span>0%</span>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['total_products'] ?? 0 }}</div>
            <div class="stat-label">Total Products</div>
            <div class="stat-description">in inventory</div>
        </div>
    </div>
    
    <div class="stat-card fade-in" style="animation-delay: 0.3s">
        <div class="stat-card-header">
            <div class="stat-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <div class="stat-trend" data-trend="{{ $stats['customers_trend'] ?? 0 }}">
                <i class="fas fa-arrow-up" style="display: none;"></i>
                <i class="fas fa-arrow-down" style="display: none;"></i>
                <i class="fas fa-minus" style="display: none;"></i>
                <span>0%</span>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['total_customers'] ?? 0 }}</div>
            <div class="stat-label">Total Customers</div>
            <div class="stat-description">vs last month</div>
        </div>
    </div>
</div>

<!-- Recent Orders Section - Full Width -->
<div class="row mb-5">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="card-title-section">
                    <h5 class="card-title">Recent Orders</h5>
                    <p class="card-subtitle">Latest customer orders</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-external-link-alt"></i> View All
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_orders ?? [] as $order)
                            <tr class="fade-in">
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none fw-semibold">
                                        #{{ $order->order_number }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                        <div class="avatar-initials">
                                            {{ substr($order->billing_first_name ?? 'U', 0, 1) }}{{ substr($order->billing_last_name ?? 'N', 0, 1) }}
                                        </div>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $order->billing_first_name ?? 'Unknown' }} {{ $order->billing_last_name ?? '' }}</div>
                                            <small class="text-muted">{{ $order->billing_email ?? 'No email' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="fw-semibold">Rs. {{ number_format($order->total, 2) }}</td>
                                <td>
                                    <div class="text-muted">{{ $order->created_at->format('M j, Y') }}</div>
                                    <small class="text-muted">{{ $order->created_at->format('g:i A') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-outline-secondary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">No recent orders found</h6>
                                        <p class="text-muted">Orders will appear here once customers start placing them.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-5">
    <div class="col-xl-8 mb-4">
        <div class="card chart-card">
            <div class="card-header">
                <div class="card-title-section">
                    <h5 class="card-title">Sales Overview</h5>
                    <p class="card-subtitle">Revenue trends over time</p>
                </div>
                <div class="card-actions">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary active" data-period="7d">7 Days</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-period="30d">30 Days</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-period="90d">90 Days</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 mb-4">
        <div class="card chart-card">
            <div class="card-header">
                <div class="card-title-section">
                    <h5 class="card-title">Order Status</h5>
                    <p class="card-subtitle">Distribution by status</p>
                </div>
            </div>
            <div class="card-body">
                <canvas id="orderStatusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top Products Section -->
<div class="row mb-5">
    <div class="col-xl-4 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="card-title-section">
                    <h5 class="card-title">Top Products</h5>
                    <p class="card-subtitle">Best performing items</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-external-link-alt"></i> View All
                </a>
            </div>
            <div class="card-body">
                @forelse($top_products ?? [] as $product)
                <div class="product-item fade-in">
                    <div class="product-image">
                        @if($product->primary_image_url)
                            <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}">
                        @else
                            <div class="placeholder-image">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </div>
                    <div class="product-details">
                        <h6 class="product-name">{{ $product->name }}</h6>
                        <div class="product-stats">
                            <span class="sales-count">{{ $product->sales_count ?? 0 }} sales</span>
                            <span class="product-price">Rs. {{ number_format($product->price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                <div class="empty-state text-center py-4">
                    <i class="fas fa-box fa-2x text-muted mb-3"></i>
                    <h6 class="text-muted">No products found</h6>
                    <p class="text-muted">Add products to see them here.</p>
                </div>
                    @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title-section">
                    <h5 class="card-title">Quick Actions</h5>
                    <p class="card-subtitle">Frequently used functions</p>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('admin.products.create') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6>Add Product</h6>
                                <p>Create new product listing</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('admin.orders.index') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6>View Orders</h6>
                                <p>Manage customer orders</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('admin.reports.index') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6>View Reports</h6>
                                <p>Analytics and insights</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('admin.settings.index') }}" class="quick-action-card">
                            <div class="quick-action-icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="quick-action-content">
                                <h6>Settings</h6>
                                <p>Configure your store</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
/* Black and White Theme Dashboard Styles */
* {
    box-sizing: border-box;
}

body {
    font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: #ffffff;
    color: #000000;
}

/* Hero Section - Black and White Style */
.hero-section {
    background: #ffffff;
    border: 1px solid #000000;
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 1rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.hero-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #000000;
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.text-gradient {
    color: #000000;
    background: none;
    -webkit-background-clip: unset;
    -webkit-text-fill-color: #000000;
    background-clip: unset;
}

.hero-subtitle {
    font-size: 0.875rem;
    color: #666666;
    margin-bottom: 1rem;
    line-height: 1.6;
    font-weight: 400;
}

.hero-stats {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.hero-stat {
    text-align: left;
}

.hero-stat-value {
    display: block;
    font-size: 1.25rem;
    font-weight: 700;
    color: #000000;
    line-height: 1.2;
}

.hero-stat-label {
    display: block;
    font-size: 0.75rem;
    color: #666666;
    font-weight: 500;
    margin-top: 0.25rem;
    text-transform: none;
    letter-spacing: 0;
}

.hero-graphic {
    display: flex;
    justify-content: center;
    align-items: center;
}

.graphic-container {
    position: relative;
}

/* Stats Grid - Black and White Card Style */
.stats-grid {
    display: grid !important;
    grid-template-columns: repeat(4, 1fr) !important;
    gap: 1rem;
    margin-bottom: 1rem;
    width: 100%;
}

.stats-grid .stat-card {
    min-width: 0;
    width: 100%;
}

.stat-card {
    background: #ffffff;
    border: 1px solid #000000;
    border-radius: 12px;
    padding: 1rem;
    transition: all 0.2s ease;
    position: relative;
    overflow: visible;
}

.stat-card:hover {
    border-color: #333333;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.stat-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000000;
    background: #ffffff;
    border: 1px solid #000000;
    font-size: 1rem;
    font-weight: 300;
    flex-shrink: 0;
}

/* All stat icons use white background with black border */
.stat-card .stat-icon {
    background: #ffffff;
    color: #000000;
    border: 1px solid #000000;
    font-weight: 300;
}

.stat-icon svg {
    width: 20px;
    height: 20px;
    color: #000000;
    stroke: #000000;
    opacity: 0.8;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.stat-trend.positive {
    background: #f0f0f0;
    color: #000000;
}

.stat-trend.negative {
    background: #e0e0e0;
    color: #000000;
}

.stat-trend.neutral {
    background: #f5f5f5;
    color: #666666;
}

.stat-content {
    text-align: left;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #000000;
    line-height: 1.2;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.8125rem;
    color: #666666;
    font-weight: 500;
    margin-bottom: 0.25rem;
    text-transform: none;
    letter-spacing: 0;
}

.stat-description {
    font-size: 0.6875rem;
    color: #999999;
    font-weight: 400;
}

/* Chart Cards - Black and White Style */
.chart-card {
    background: #ffffff;
    border: 1px solid #000000;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.chart-card .card-header {
    background: #ffffff;
    border-bottom: 1px solid #000000;
    padding: 0.875rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title-section h5 {
    margin: 0;
    font-weight: 600;
    font-size: 0.9375rem;
    color: #000000;
}

.card-subtitle {
    margin: 0.25rem 0 0 0;
    color: #666666;
    font-size: 0.8125rem;
    font-weight: 400;
}

.card-body {
    padding: 1rem;
}

/* Cards - General */
.card {
    background: #ffffff;
    border: 1px solid #000000;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.card-header {
    background: #ffffff;
    border-bottom: 1px solid #000000;
    padding: 1.25rem 1.5rem;
}

.card-title {
    font-size: 1rem;
    font-weight: 600;
    color: #000000;
    margin: 0;
}

/* Avatar */
.avatar-sm {
    width: 32px;
    height: 32px;
}

.avatar-initials {
    width: 100%;
    height: 100%;
    background: #000000;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-weight: 600;
    font-size: 0.75rem;
}

/* Tables */
.table {
    color: #000000;
}

.table thead th {
    font-weight: 600;
    font-size: 0.875rem;
    color: #666666;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 2px solid #000000;
    padding: 0.75rem 1rem;
    background: #ffffff;
}

.table tbody td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #e0e0e0;
}

.table-hover tbody tr:hover {
    background: #f5f5f5;
}

/* Badges - Black and White */
.badge {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 6px;
    border: 1px solid #000000;
}

.badge-success {
    background: #ffffff !important;
    color: #000000 !important;
    border-color: #000000 !important;
}

.badge-warning {
    background: #f5f5f5 !important;
    color: #000000 !important;
    border-color: #000000 !important;
}

.badge-secondary {
    background: #ffffff !important;
    color: #666666 !important;
    border-color: #666666 !important;
}

/* Buttons - Black and White */
.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.625rem 1.25rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    border-width: 1.5px;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.8125rem;
}

.btn-outline-primary {
    color: #000000;
    border-color: #000000;
    background: #ffffff;
}

.btn-outline-primary:hover,
.btn-outline-primary.active {
    background: #000000;
    border-color: #000000;
    color: #ffffff;
}

.btn-outline-secondary {
    color: #666666;
    border-color: #666666;
    background: #ffffff;
}

.btn-outline-secondary:hover {
    background: #666666;
    border-color: #666666;
    color: #ffffff;
}

/* Quick Actions - Black and White */
.quick-action-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: #ffffff;
    border: 1px solid #000000;
    border-radius: 12px;
    text-decoration: none;
    color: #000000;
    transition: all 0.2s ease;
    height: 100%;
}

.quick-action-card:hover {
    border-color: #333333;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    color: #000000;
    text-decoration: none;
}

.quick-action-icon {
    width: 48px;
    height: 48px;
    background: #000000;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.quick-action-content h6 {
    margin: 0 0 0.25rem 0;
    font-weight: 600;
    font-size: 0.9375rem;
    color: #000000;
}

.quick-action-content p {
    margin: 0;
    color: #666666;
    font-size: 0.8125rem;
    font-weight: 400;
}

/* Product Items - Black and White */
.product-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e0e0e0;
}

.product-item:last-child {
    border-bottom: none;
}

.product-image {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    overflow: hidden;
    background: #f5f5f5;
    border: 1px solid #000000;
    flex-shrink: 0;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999999;
}

.product-details {
    flex: 1;
}

.product-name {
    font-size: 0.9375rem;
    font-weight: 600;
    color: #000000;
    margin: 0 0 0.25rem 0;
}

.product-stats {
    display: flex;
    gap: 1rem;
    font-size: 0.8125rem;
    color: #666666;
}

.sales-count {
    font-weight: 500;
}

.product-price {
    font-weight: 600;
    color: #000000;
}

/* Empty State - Black and White */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}

.empty-state i {
    color: #999999;
    margin-bottom: 1rem;
}

.empty-state h6 {
    color: #666666;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #999999;
    font-size: 0.875rem;
    margin: 0;
}

/* Text Colors - Black and White */
.text-muted {
    color: #666666 !important;
}

.fw-semibold {
    font-weight: 600;
}

/* Responsive */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(4, 1fr) !important;
    }
}

@media (max-width: 992px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 1.5rem;
    }
    
    .hero-stats {
        gap: 1rem;
    }
    
    .chart-card .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .hero-section {
        padding: 1.5rem;
    }
}

@media (max-width: 576px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize counters and trends
    if (typeof AdminDashboard !== 'undefined') {
        AdminDashboard.initCounters();
        AdminDashboard.initTrends();
    } else {
        // Fallback if AdminDashboard is not available
        initCounters();
        initTrends();
    }
    
    // Fallback functions if AdminDashboard is not available
    function initCounters() {
        const counters = document.querySelectorAll('[data-count]');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-count'));
            const duration = 2000; // 2 seconds
            const increment = target / (duration / 16); // 60fps
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                // Format the number based on type
                if (counter.textContent.includes('Rs.')) {
                    counter.textContent = 'Rs. ' + Math.floor(current).toLocaleString();
                } else {
                    counter.textContent = Math.floor(current).toLocaleString();
                }
            }, 16);
        });
    }
    
    function initTrends() {
        const trendElements = document.querySelectorAll('[data-trend]');
        
        trendElements.forEach(element => {
            const trend = parseFloat(element.getAttribute('data-trend'));
            const span = element.querySelector('span');
            const upIcon = element.querySelector('.fa-arrow-up');
            const downIcon = element.querySelector('.fa-arrow-down');
            const minusIcon = element.querySelector('.fa-minus');
            
            // Hide all icons first
            [upIcon, downIcon, minusIcon].forEach(icon => {
                if (icon) icon.style.display = 'none';
            });
            
            if (trend > 0) {
                if (upIcon) upIcon.style.display = 'inline';
                element.classList.add('positive');
                span.textContent = '+' + trend + '%';
            } else if (trend < 0) {
                if (downIcon) downIcon.style.display = 'inline';
                element.classList.add('negative');
                span.textContent = trend + '%';
            } else {
                if (minusIcon) minusIcon.style.display = 'inline';
                element.classList.add('neutral');
                span.textContent = '0%';
            }
        });
    }
    
    const ctxSales = document.getElementById('salesChart');
    const ctxStatus = document.getElementById('orderStatusChart');

    // Sales data from backend or fallback
    const salesLabels = <?php echo json_encode(($sales_series['labels'] ?? [])); ?>;
    const salesData = <?php echo json_encode(($sales_series['data'] ?? [])); ?>;
    const statusData = <?php echo json_encode(($order_status_breakdown ?? ['pending' => 0, 'processing' => 0, 'completed' => 0, 'cancelled' => 0])); ?>;

    const effectiveSalesLabels = (salesLabels && salesLabels.length) ? salesLabels : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    const effectiveSalesData = (salesData && salesData.length) ? salesData : [12, 19, 7, 15, 22, 18, 25];

    // Sales Chart
    if (ctxSales) {
        try {
            new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: effectiveSalesLabels,
                datasets: [{
                    label: 'Revenue (Rs.)',
                    data: effectiveSalesData,
                    tension: 0.4,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            color: '#666666'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            color: '#666666',
                            callback: function(value) {
                                return 'Rs. ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
        } catch (error) {
            console.error('Error initializing sales chart:', error);
            ctxSales.parentElement.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-exclamation-triangle"></i> Unable to load chart data</div>';
        }
    }

    // Order Status Chart
    if (ctxStatus) {
        try {
            const statusLabels = Object.keys(statusData);
            const statusValues = Object.values(statusData);
            
            // Map status to colors
            const statusColors = {
                'pending': '#f59e0b',      // Amber/Orange
                'processing': '#3b82f6',   // Blue
                'completed': '#10b981',    // Green
                'cancelled': '#ef4444',    // Red
                'confirmed': '#8b5cf6',    // Purple
                'shipped': '#06b6d4'       // Cyan
            };
            
            const statusBackgroundColors = statusLabels.map(label => {
                return statusColors[label.toLowerCase()] || '#94a3b8'; // Default gray
            });
            
            new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: statusLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
                datasets: [{
                    data: statusValues,
                    backgroundColor: statusBackgroundColors,
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#000000',
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
        } catch (error) {
            console.error('Error initializing order status chart:', error);
            ctxStatus.parentElement.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-exclamation-triangle"></i> Unable to load chart data</div>';
        }
    }

    // Period buttons functionality
    const periodButtons = document.querySelectorAll('[data-period]');
    periodButtons.forEach(button => {
        button.addEventListener('click', function() {
            periodButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            // Here you would typically reload chart data based on period
        });
    });
});
</script>
@endpush
@endsection