@extends('super-admin.layout')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="dashboard-title">
                    <i class="fas fa-chart-line"></i>
                    Analytics Dashboard
                </h1>
                <p class="dashboard-subtitle">Comprehensive platform analytics and insights</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-select" id="dateRangeFilter" onchange="updateAnalytics()">
                            @foreach($filterOptions['date_ranges'] as $value => $label)
                                <option value="{{ $value }}" {{ $dateRange === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" id="tenantFilter" onchange="updateAnalytics()">
                            <option value="all" {{ $tenantId === 'all' ? 'selected' : '' }}>All Tenants</option>
                            @foreach($filterOptions['tenants'] as $id => $name)
                                <option value="{{ $id }}" {{ $tenantId == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Statistics -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                    <i class="fas fa-store"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($stats['total_tenants']) }}</h3>
                    <p>Total Tenants</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($stats['total_orders']) }}</h3>
                    <p>Total Orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($stats['total_products']) }}</h3>
                    <p>Total Products</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <h3>Rs. {{ number_format($stats['total_revenue']) }}</h3>
                    <p>Total Revenue</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ number_format($stats['total_users']) }}</h3>
                    <p>Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="stat-content">
                    <h3>Rs. {{ number_format($stats['average_order_value']) }}</h3>
                    <p>Avg Order Value</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Growth Metrics -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-trending-up"></i>
                        Growth Metrics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6">
                            <h3 class="text-{{ $growthMetrics['tenant_growth_rate'] >= 0 ? 'success' : 'danger' }}">
                                {{ $growthMetrics['tenant_growth_rate'] }}%
                            </h3>
                            <p class="text-muted">Tenant Growth Rate</p>
                        </div>
                        <div class="col-md-6">
                            <h3 class="text-{{ $growthMetrics['revenue_growth_rate'] >= 0 ? 'success' : 'danger' }}">
                                {{ $growthMetrics['revenue_growth_rate'] }}%
                            </h3>
                            <p class="text-muted">Revenue Growth Rate</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-pie"></i>
                        Revenue by Payment Method
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentMethodsChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-line"></i>
                        Revenue Trend
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueTrendChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-users"></i>
                        Tenant Growth
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="tenantGrowthChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-shopping-cart"></i>
                        Order Volume
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="orderVolumeChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-user-plus"></i>
                        User Registration
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="userRegistrationChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-trophy"></i>
                        Top Tenants by Revenue
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tenant</th>
                                    <th>Revenue</th>
                                    <th>Orders</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topPerformers['top_tenants_by_revenue'] as $tenant)
                                <tr>
                                    <td>{{ $tenant->business_name }}</td>
                                    <td>Rs. {{ number_format($tenant->payments_sum_amount ?? 0) }}</td>
                                    <td>{{ $tenant->payments_count ?? 0 }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-star"></i>
                        Top Products by Sales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Sales</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topPerformers['top_products_by_sales'] as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->order_items_count ?? 0 }}</td>
                                    <td>Rs. {{ number_format($product->order_items_sum_total ?? 0) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribution Charts -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-pie"></i>
                        Tenant Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="tenantStatusChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-pie"></i>
                        Order Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="orderStatusChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-pie"></i>
                        User Role Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="userRoleChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Time-based Analytics -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-clock"></i>
                        Hourly Activity (Today)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="hourlyActivityChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-calendar-week"></i>
                        Weekly Activity
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="weeklyActivityChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    background: white;
    border-radius: 20px;
    padding: 1.75rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    color: white;
    font-size: 1.5rem;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #1f2937;
}

.stat-content p {
    color: #6b7280;
    margin-bottom: 0;
    font-weight: 500;
}

.card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
}

.card-header {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-bottom: 1px solid #e2e8f0;
    border-radius: 20px 20px 0 0 !important;
    padding: 1.5rem;
}

.card-title {
    margin: 0;
    font-weight: 600;
    color: #1f2937;
}

.card-body {
    padding: 1.5rem;
}
</style>

<script>
let charts = {};

function updateAnalytics() {
    const dateRange = document.getElementById('dateRangeFilter').value;
    const tenantId = document.getElementById('tenantFilter').value;
    
    // Update URL parameters
    const url = new URL(window.location);
    url.searchParams.set('date_range', dateRange);
    url.searchParams.set('tenant_id', tenantId);
    window.history.pushState({}, '', url);
    
    // Reload page with new parameters
    window.location.reload();
}

// Initialize all charts
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

function initializeCharts() {
    // Revenue Trend Chart
    const revenueCtx = document.getElementById('revenueTrendChart');
    if (revenueCtx) {
        const revenueData = @json($revenueAnalytics['daily_revenue']);
        charts.revenueTrend = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueData.map(item => item.formatted_date),
                datasets: [{
                    label: 'Revenue',
                    data: revenueData.map(item => item.revenue),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rs. ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Tenant Growth Chart
    const tenantCtx = document.getElementById('tenantGrowthChart');
    if (tenantCtx) {
        const tenantData = @json($tenantAnalytics['tenant_growth']);
        charts.tenantGrowth = new Chart(tenantCtx, {
            type: 'line',
            data: {
                labels: tenantData.map(item => item.formatted_date),
                datasets: [{
                    label: 'Total Tenants',
                    data: tenantData.map(item => item.count),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Order Volume Chart
    const orderCtx = document.getElementById('orderVolumeChart');
    if (orderCtx) {
        const orderData = @json($orderAnalytics['daily_orders']);
        charts.orderVolume = new Chart(orderCtx, {
            type: 'bar',
            data: {
                labels: orderData.map(item => item.formatted_date),
                datasets: [{
                    label: 'Orders',
                    data: orderData.map(item => item.count),
                    backgroundColor: '#f59e0b',
                    borderColor: '#d97706',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // User Registration Chart
    const userCtx = document.getElementById('userRegistrationChart');
    if (userCtx) {
        const userData = @json($userAnalytics['user_registration']);
        charts.userRegistration = new Chart(userCtx, {
            type: 'line',
            data: {
                labels: userData.map(item => item.formatted_date),
                datasets: [{
                    label: 'New Users',
                    data: userData.map(item => item.count),
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Payment Methods Chart
    const paymentCtx = document.getElementById('paymentMethodsChart');
    if (paymentCtx) {
        const paymentData = @json($revenueAnalytics['revenue_by_method']);
        charts.paymentMethods = new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: paymentData.map(item => item.payment_method || 'Unknown'),
                datasets: [{
                    data: paymentData.map(item => item.total),
                    backgroundColor: ['#667eea', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Tenant Status Chart
    const tenantStatusCtx = document.getElementById('tenantStatusChart');
    if (tenantStatusCtx) {
        const statusData = @json($tenantAnalytics['status_distribution']);
        charts.tenantStatus = new Chart(tenantStatusCtx, {
            type: 'doughnut',
            data: {
                labels: statusData.map(item => item.status),
                datasets: [{
                    data: statusData.map(item => item.count),
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#3b82f6']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Order Status Chart
    const orderStatusCtx = document.getElementById('orderStatusChart');
    if (orderStatusCtx) {
        const orderStatusData = @json($orderAnalytics['status_distribution']);
        charts.orderStatus = new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: orderStatusData.map(item => item.status),
                datasets: [{
                    data: orderStatusData.map(item => item.count),
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // User Role Chart
    const userRoleCtx = document.getElementById('userRoleChart');
    if (userRoleCtx) {
        const roleData = @json($userAnalytics['role_distribution']);
        charts.userRole = new Chart(userRoleCtx, {
            type: 'doughnut',
            data: {
                labels: roleData.map(item => item.role),
                datasets: [{
                    data: roleData.map(item => item.count),
                    backgroundColor: ['#667eea', '#10b981', '#f59e0b', '#ef4444']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Hourly Activity Chart
    const hourlyCtx = document.getElementById('hourlyActivityChart');
    if (hourlyCtx) {
        const hourlyData = @json($timeBasedAnalytics['hourly_activity']);
        charts.hourlyActivity = new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: hourlyData.map(item => item.formatted_hour),
                datasets: [{
                    label: 'Orders',
                    data: hourlyData.map(item => item.count),
                    backgroundColor: '#06b6d4',
                    borderColor: '#0891b2',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Weekly Activity Chart
    const weeklyCtx = document.getElementById('weeklyActivityChart');
    if (weeklyCtx) {
        const weeklyData = @json($timeBasedAnalytics['weekly_activity']);
        charts.weeklyActivity = new Chart(weeklyCtx, {
            type: 'line',
            data: {
                labels: weeklyData.map(item => item.formatted_week),
                datasets: [{
                    label: 'Orders',
                    data: weeklyData.map(item => item.count),
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
}

// Auto-refresh every 5 minutes
setInterval(() => {
    location.reload();
}, 300000);
</script>
@endsection