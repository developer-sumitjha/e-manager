<?php $__env->startSection('title', 'Reports & Analytics'); ?>
<?php $__env->startSection('page-title', 'Reports & Analytics'); ?>
<?php $__env->startSection('page-subtitle', 'Comprehensive business insights and performance metrics'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <div class="breadcrumb-item">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="breadcrumb-link">Dashboard</a>
    </div>
    <div class="breadcrumb-item active">Reports & Analytics</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<div class="hero-section mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="fas fa-chart-line me-3"></i>Reports & Analytics
                </h1>
                <p class="hero-subtitle">Comprehensive business insights and performance metrics to drive informed decisions.</p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="hero-actions">
                <a href="<?php echo e(route('admin.reports.export', ['date_from' => $dateFrom, 'date_to' => $dateTo])); ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-download me-2"></i>Export Report
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Date Filter -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-calendar me-2"></i>Date Range Filter
        </h5>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('admin.reports.index')); ?>" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control" value="<?php echo e($dateFrom); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control" value="<?php echo e($dateTo); ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Apply Filter
                </button>
                <a href="<?php echo e(route('admin.reports.index')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Stats Overview -->
<div class="stats-grid mb-4">
    <div class="stat-card fade-in">
        <div class="stat-card-header">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>+12%</span>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-value" data-count="<?php echo e($totalSales); ?>">Rs. 0</div>
            <div class="stat-label">Total Sales</div>
            <div class="stat-description">vs last period</div>
        </div>
    </div>

    <div class="stat-card fade-in" style="animation-delay: 0.1s">
        <div class="stat-card-header">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>+8%</span>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-value" data-count="<?php echo e($totalOrders); ?>">0</div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-description">vs last period</div>
        </div>
    </div>

    <div class="stat-card fade-in" style="animation-delay: 0.2s">
        <div class="stat-card-header">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-trend neutral">
                <i class="fas fa-minus"></i>
                <span>0%</span>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-value" data-count="<?php echo e($averageOrderValue); ?>">Rs. 0</div>
            <div class="stat-label">Avg Order Value</div>
            <div class="stat-description">per order</div>
        </div>
    </div>

    <div class="stat-card fade-in" style="animation-delay: 0.3s">
        <div class="stat-card-header">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-trend negative">
                <i class="fas fa-arrow-down"></i>
                <span>-5%</span>
            </div>
        </div>
        <div class="stat-content">
            <div class="stat-value" data-count="<?php echo e($pendingOrders); ?>">0</div>
            <div class="stat-label">Pending Orders</div>
            <div class="stat-description">awaiting processing</div>
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
                            <?php $__currentLoopData = $dailySales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e(\Carbon\Carbon::parse($sale->date)->format('M d, Y')); ?></td>
                                <td><?php echo e($sale->orders); ?></td>
                                <td>$<?php echo e(number_format($sale->revenue, 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <?php $__currentLoopData = $salesByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span><?php echo e($category->name); ?></span>
                        <strong>$<?php echo e(number_format($category->total_sales, 2)); ?></strong>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar" style="width: <?php echo e($salesByCategory->max('total_sales') > 0 ? ($category->total_sales / $salesByCategory->max('total_sales')) * 100 : 0); ?>%"></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <?php $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($product->name); ?></td>
                                <td><?php echo e($product->category->name); ?></td>
                                <td><span class="badge bg-success"><?php echo e($product->total_sold); ?></span></td>
                                <td>$<?php echo e(number_format($product->price, 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <?php $__empty_1 = true; $__currentLoopData = $topCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($customer->name); ?></td>
                                <td><span class="badge bg-primary"><?php echo e($customer->order_count); ?></span></td>
                                <td>$<?php echo e(number_format($customer->total_spent, 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">No customer data available</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Alert -->
<?php if($lowStockProducts->count() > 0 || $outOfStock > 0): ?>
<div class="row g-4 mt-2">
    <div class="col-md-12">
        <div class="card border-warning">
            <div class="card-header bg-warning bg-opacity-10">
                <h5 class="mb-0 text-warning">
                    <i class="fas fa-exclamation-triangle"></i> Inventory Alerts
                </h5>
            </div>
            <div class="card-body">
                <?php if($outOfStock > 0): ?>
                <div class="alert alert-danger">
                    <strong><?php echo e($outOfStock); ?></strong> product(s) are out of stock!
                </div>
                <?php endif; ?>
                
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
                            <?php $__currentLoopData = $lowStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($product->name); ?></td>
                                <td><?php echo e($product->sku); ?></td>
                                <td><span class="badge bg-warning"><?php echo e($product->stock); ?></span></td>
                                <td>
                                    <a href="<?php echo e(route('admin.products.edit', $product)); ?>" class="btn btn-sm btn-primary">
                                        Update Stock
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize counters and trends
    if (typeof AdminDashboard !== 'undefined') {
        AdminDashboard.initCounters();
        AdminDashboard.initTrends();
    }
});
</script>
<?php $__env->stopPush(); ?>









<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/reports/index.blade.php ENDPATH**/ ?>