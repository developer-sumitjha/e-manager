@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back! Here\'s what\'s happening with your business today.')

@section('breadcrumb')
    <div class="breadcrumb-item active">Dashboard</div>
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero-section mb-5">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <div class="hero-content">
                <h1 class="hero-title text-gradient">Welcome back, {{ auth()->user()->first_name ?? 'Admin' }}!</h1>
                <p class="hero-subtitle">Here's a comprehensive overview of your business performance and key metrics.</p>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="hero-stat-value">{{ $stats['total_orders'] ?? 0 }}</span>
                        <span class="hero-stat-label">Total Orders</span>
                    </div>
                    <div class="hero-stat">
                        <span class="hero-stat-value">Rs. {{ number_format($stats['total_revenue'] ?? 0) }}</span>
                        <span class="hero-stat-label">Revenue</span>
                    </div>
                    <div class="hero-stat">
                        <span class="hero-stat-value">{{ $stats['total_customers'] ?? 0 }}</span>
                        <span class="hero-stat-label">Customers</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="hero-graphic">
                <div class="graphic-container">
                    <svg width="300" height="200" viewBox="0 0 300 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="heroGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#6366f1;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#06b6d4;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <!-- Background circles -->
                        <circle cx="50" cy="50" r="40" fill="url(#heroGradient)" opacity="0.1"/>
                        <circle cx="250" cy="150" r="60" fill="url(#heroGradient)" opacity="0.1"/>
                        <!-- Main chart area -->
                        <rect x="20" y="80" width="260" height="100" rx="12" fill="url(#heroGradient)" opacity="0.05" stroke="url(#heroGradient)" stroke-width="2"/>
                        <!-- Chart bars -->
                        <rect x="40" y="120" width="20" height="60" fill="url(#heroGradient)" opacity="0.8"/>
                        <rect x="70" y="100" width="20" height="80" fill="url(#heroGradient)" opacity="0.8"/>
                        <rect x="100" y="90" width="20" height="90" fill="url(#heroGradient)" opacity="0.8"/>
                        <rect x="130" y="110" width="20" height="70" fill="url(#heroGradient)" opacity="0.8"/>
                        <rect x="160" y="85" width="20" height="95" fill="url(#heroGradient)" opacity="0.8"/>
                        <rect x="190" y="95" width="20" height="85" fill="url(#heroGradient)" opacity="0.8"/>
                        <rect x="220" y="105" width="20" height="75" fill="url(#heroGradient)" opacity="0.8"/>
                        <!-- Trend line -->
                        <path d="M40 140 L70 120 L100 110 L130 130 L160 105 L190 115 L220 125 L250 135" stroke="url(#heroGradient)" stroke-width="3" fill="none" opacity="0.8"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid mb-5">
    <div class="stat-card fade-in">
        <div class="stat-card-header">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>+12%</span>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['total_orders'] ?? 0 }}</div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-description">vs last month</div>
        </div>
    </div>
    
    <div class="stat-card fade-in" style="animation-delay: 0.1s">
        <div class="stat-card-header">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>+8%</span>
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
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-trend neutral">
                <i class="fas fa-minus"></i>
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
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>+15%</span>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $stats['total_customers'] ?? 0 }}</div>
            <div class="stat-label">Total Customers</div>
            <div class="stat-description">vs last month</div>
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

<!-- Recent Activity Section -->
<div class="row mb-5">
    <div class="col-xl-8 mb-4">
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
                                                {{ substr($order->billing_first_name, 0, 1) }}{{ substr($order->billing_last_name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $order->billing_first_name }} {{ $order->billing_last_name }}</div>
                                            <small class="text-muted">{{ $order->billing_email }}</small>
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
<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(6, 182, 212, 0.1));
    border: 1px solid rgba(99, 102, 241, 0.2);
    border-radius: var(--radius-2xl);
    padding: var(--space-2xl);
    margin-bottom: var(--space-xl);
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 0%, transparent 70%);
    animation: float 6s ease-in-out infinite;
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: var(--space-md);
    line-height: 1.1;
}

.hero-subtitle {
    font-size: 1.25rem;
    color: var(--text-muted);
    margin-bottom: var(--space-xl);
    line-height: 1.6;
}

.hero-stats {
    display: flex;
    gap: var(--space-xl);
    flex-wrap: wrap;
}

.hero-stat {
    text-align: center;
}

.hero-stat-value {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary);
    line-height: 1;
}

.hero-stat-label {
    display: block;
    font-size: 0.875rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-top: var(--space-xs);
}

.hero-graphic {
    display: flex;
    justify-content: center;
    align-items: center;
}

.graphic-container {
    position: relative;
    animation: float 4s ease-in-out infinite;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: var(--space-lg);
}

.stat-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.9));
    border: 1px solid rgba(99, 102, 241, 0.1);
    border-radius: var(--radius-xl);
    padding: var(--space-lg);
    position: relative;
    overflow: hidden;
    transition: all var(--transition-normal);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-2xl);
    border-color: rgba(99, 102, 241, 0.3);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
}

.stat-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-md);
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: var(--space-xs);
    padding: var(--space-xs) var(--space-sm);
    border-radius: var(--radius-lg);
    font-size: 0.75rem;
    font-weight: 600;
}

.stat-trend.positive {
    background: rgba(34, 197, 94, 0.1);
    color: var(--success);
}

.stat-trend.neutral {
    background: rgba(148, 163, 184, 0.1);
    color: var(--text-muted);
}

.stat-content {
    text-align: left;
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: var(--space-xs);
}

.stat-label {
    font-size: 0.875rem;
    color: var(--text-muted);
    font-weight: 500;
    margin-bottom: var(--space-xs);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stat-description {
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* Chart Cards */
.chart-card .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title-section h5 {
    margin: 0;
    font-weight: 600;
}

.card-subtitle {
    margin: 0;
    color: var(--text-muted);
    font-size: 0.875rem;
}

/* Avatar */
.avatar-sm {
    width: 32px;
    height: 32px;
}

.avatar-initials {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.75rem;
}

/* Empty State */
.empty-state {
    padding: var(--space-2xl);
}

/* Quick Actions */
.quick-action-card {
    display: flex;
    align-items: center;
    gap: var(--space-md);
    padding: var(--space-lg);
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.5), rgba(15, 23, 42, 0.5));
    border: 1px solid rgba(99, 102, 241, 0.1);
    border-radius: var(--radius-lg);
    text-decoration: none;
    color: var(--text-primary);
    transition: all var(--transition-normal);
    height: 100%;
}

.quick-action-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(99, 102, 241, 0.3);
    color: var(--text-primary);
}

.quick-action-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.quick-action-content h6 {
    margin: 0 0 var(--space-xs) 0;
    font-weight: 600;
}

.quick-action-content p {
    margin: 0;
    color: var(--text-muted);
    font-size: 0.875rem;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-stats {
        gap: var(--space-lg);
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .chart-card .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--space-md);
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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
        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: effectiveSalesLabels,
                datasets: [{
                    label: 'Revenue (Rs.)',
                    data: effectiveSalesData,
                    tension: 0.4,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#6366f1',
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
                            color: 'rgba(148, 163, 184, 0.1)'
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)'
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });
    }

    // Order Status Chart
    if (ctxStatus) {
        const statusLabels = Object.keys(statusData);
        const statusValues = Object.values(statusData);
        
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: statusLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
                datasets: [{
                    data: statusValues,
                    backgroundColor: [
                        '#f59e0b',
                        '#06b6d4',
                        '#22c55e',
                        '#ef4444'
                    ],
                    borderColor: 'rgba(15, 23, 42, 0.8)',
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
                            color: '#cbd5e1',
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
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