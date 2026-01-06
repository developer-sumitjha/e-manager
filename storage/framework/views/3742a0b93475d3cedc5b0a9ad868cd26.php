<?php $__env->startSection('title', 'Gaaubesi Logistics'); ?>
<?php $__env->startSection('page-title', 'Gaaubesi Logistics'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Gaaubesi Dashboard Specific Styles */
    .gaaubesi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title-section h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-top: 0.5rem;
        font-weight: 400;
    }

    .gaaubesi-logo {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: rgba(139, 92, 246, 0.1);
        border-radius: 0.75rem;
        border: 1px solid rgba(139, 92, 246, 0.2);
    }

    .gaaubesi-logo i {
        font-size: 1.5rem;
        color: #8B5CF6;
    }

    .gaaubesi-logo span {
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Navigation Tabs Styles */
    .gaaubesi-nav-tabs {
        margin-bottom: 2rem;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 0.5rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        backdrop-filter: blur(10px);
    }

    .nav-tabs-container {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .nav-tabs-container::-webkit-scrollbar {
        display: none;
    }

    .nav-tab {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        text-decoration: none;
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        white-space: nowrap;
        background: transparent;
        border: 1px solid transparent;
    }

    .nav-tab:hover {
        background: rgba(139, 92, 246, 0.1);
        color: var(--primary-color);
        border-color: rgba(139, 92, 246, 0.2);
    }

    .nav-tab.active {
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        color: white;
        border-color: #8B5CF6;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .nav-tab i {
        font-size: 1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Pending Logistics Orders Styles */
    .pending-logistics-section {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        backdrop-filter: blur(10px);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .section-title h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title p {
        color: var(--text-secondary);
        margin: 0;
        font-size: 0.9rem;
    }

    .section-actions .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .pending-orders-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    .pending-order-card {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 0.75rem;
        padding: 1.5rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .pending-order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .order-info h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.25rem 0;
    }

    .customer-name {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .order-amount {
        text-align: right;
    }

    .order-amount .amount {
        display: block;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--success-color);
    }

    .order-amount .payment-method {
        font-size: 0.8rem;
        color: var(--text-secondary);
        background: rgba(139, 92, 246, 0.1);
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        margin-top: 0.25rem;
        display: inline-block;
    }

    .order-details {
        margin-bottom: 1.5rem;
    }

    .detail-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .detail-row i {
        width: 16px;
        color: var(--primary-color);
        font-size: 0.8rem;
    }

    .order-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }

    .order-actions .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(139, 92, 246, 0.15);
    }

    .stat-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin-bottom: 1rem;
    }

    .stat-icon.total {
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
    }

    .stat-icon.delivered {
        background: linear-gradient(135deg, #10B981, #34D399);
    }

    .stat-icon.cod-paid {
        background: linear-gradient(135deg, #F59E0B, #FBBF24);
    }

    .stat-icon.pending {
        background: linear-gradient(135deg, #3B82F6, #60A5FA);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .search-filter-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 300px;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        z-index: 2;
    }

    .search-box input {
        padding-left: 3rem;
        border-radius: 0.75rem;
        border: 1px solid rgba(139, 92, 246, 0.2);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        border-color: #8B5CF6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        background: white;
    }

    .filter-group {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .filter-select {
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid rgba(139, 92, 246, 0.2);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        border-color: #8B5CF6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .create-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        color: white;
        text-decoration: none;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        white-space: nowrap;
    }

    .create-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3);
        color: white;
    }

    .shipments-table {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
        backdrop-filter: blur(10px);
        overflow-x: auto;
    }

    .table {
        margin: 0;
    }

    .table th {
        border: none;
        background: rgba(139, 92, 246, 0.05);
        color: var(--text-primary);
        font-weight: 600;
        padding: 1rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .table td {
        border: none;
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(139, 92, 246, 0.05);
    }

    .table tbody tr:hover {
        background: rgba(139, 92, 246, 0.02);
    }

    .tracking-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .gaaubesi-order-id {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: #8B5CF6;
        font-size: 0.875rem;
    }

    .track-id {
        font-family: 'Courier New', monospace;
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .order-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .order-number {
        font-weight: 600;
        color: var(--text-primary);
    }

    .receiver-name {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .branch-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .branch-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(139, 92, 246, 0.1);
        color: #8B5CF6;
    }

    .delivery-status {
        padding: 0.5rem 1rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-align: center;
    }

    .delivery-status.created {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
    }

    .delivery-status.in-transit {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }

    .delivery-status.delivered {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .delivery-status.failed {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .cod-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .cod-badge.paid {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .cod-badge.unpaid {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        margin: 0.125rem;
    }

    .action-btn.view {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
        border-color: rgba(59, 130, 246, 0.2);
    }

    .action-btn.view:hover {
        background: rgba(59, 130, 246, 0.2);
        color: #3B82F6;
    }

    .action-btn.track {
        background: rgba(139, 92, 246, 0.1);
        color: #8B5CF6;
        border-color: rgba(139, 92, 246, 0.2);
    }

    .action-btn.track:hover {
        background: rgba(139, 92, 246, 0.2);
        color: #8B5CF6;
    }

    .no-shipments {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .no-shipments i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: rgba(139, 92, 246, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .gaaubesi-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .search-filter-bar {
            flex-direction: column;
        }

        .search-box {
            min-width: auto;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="gaaubesi-header">
    <div class="page-title-section">
        <h1>Gaaubesi Logistics</h1>
        <p class="page-subtitle">Manage third-party logistics shipments</p>
    </div>
    <div class="gaaubesi-logo">
        <i class="fas fa-shipping-fast"></i>
        <span>GAAUBESI</span>
    </div>
</div>

<!-- Navigation Tabs -->
<div class="gaaubesi-nav-tabs">
    <div class="nav-tabs-container">
        <a href="<?php echo e(route('admin.gaaubesi.index')); ?>" class="nav-tab <?php echo e(request()->routeIs('admin.gaaubesi.index') ? 'active' : ''); ?>">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="<?php echo e(route('admin.gaaubesi.service-stations')); ?>" class="nav-tab <?php echo e(request()->routeIs('admin.gaaubesi.service-stations') ? 'active' : ''); ?>">
            <i class="fas fa-map-marker-alt"></i> Service Stations
        </a>
        <a href="<?php echo e(route('admin.gaaubesi.comments')); ?>" class="nav-tab <?php echo e(request()->routeIs('admin.gaaubesi.comments') ? 'active' : ''); ?>">
            <i class="fas fa-comments"></i> Comments
        </a>
        <a href="<?php echo e(route('admin.gaaubesi.cod-settlement')); ?>" class="nav-tab <?php echo e(request()->routeIs('admin.gaaubesi.cod-settlement') ? 'active' : ''); ?>">
            <i class="fas fa-money-bill-wave"></i> COD Settlement
        </a>
        <a href="<?php echo e(route('admin.gaaubesi.analytics')); ?>" class="nav-tab <?php echo e(request()->routeIs('admin.gaaubesi.analytics') ? 'active' : ''); ?>">
            <i class="fas fa-chart-line"></i> Analytics
        </a>
        <a href="<?php echo e(route('admin.gaaubesi.notifications')); ?>" class="nav-tab <?php echo e(request()->routeIs('admin.gaaubesi.notifications') ? 'active' : ''); ?>">
            <i class="fas fa-bell"></i> Notifications
        </a>
        <a href="<?php echo e(route('admin.gaaubesi.settings')); ?>" class="nav-tab <?php echo e(request()->routeIs('admin.gaaubesi.settings') ? 'active' : ''); ?>">
            <i class="fas fa-cog"></i> Settings
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon total">
            <i class="fas fa-boxes"></i>
        </div>
        <div class="stat-value"><?php echo e($totalShipments); ?></div>
        <div class="stat-label">Total Shipments</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon delivered">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-value"><?php echo e($deliveredShipments); ?></div>
        <div class="stat-label">Delivered</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon cod-paid">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-value"><?php echo e($codPaidCount); ?></div>
        <div class="stat-label">COD Paid</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon pending">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-value"><?php echo e($pendingShipments); ?></div>
        <div class="stat-label">In Transit</div>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="search-filter-bar">
    <div class="search-box">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="shipments-search" class="form-control" placeholder="Search by order ID, tracking number, receiver..." value="<?php echo e(request('search')); ?>">
    </div>

    <div class="filter-group">
        <select class="filter-select" id="status-filter">
            <option value="">All Status</option>
            <option value="created" <?php echo e(request('status') == 'created' ? 'selected' : ''); ?>>Created</option>
            <option value="transit" <?php echo e(request('status') == 'transit' ? 'selected' : ''); ?>>In Transit</option>
            <option value="delivered" <?php echo e(request('status') == 'delivered' ? 'selected' : ''); ?>>Delivered</option>
            <option value="failed" <?php echo e(request('status') == 'failed' ? 'selected' : ''); ?>>Failed</option>
        </select>

        <select class="filter-select" id="cod-filter">
            <option value="">All COD Status</option>
            <option value="1" <?php echo e(request('cod_paid') === '1' ? 'selected' : ''); ?>>Paid</option>
            <option value="0" <?php echo e(request('cod_paid') === '0' ? 'selected' : ''); ?>>Unpaid</option>
        </select>
    </div>

    <a href="<?php echo e(route('admin.gaaubesi.create')); ?>" class="create-btn">
        <i class="fas fa-plus"></i> Create Shipment
    </a>
</div>

<!-- Pending Logistics Orders Section -->
<?php if($pendingLogisticsOrders->count() > 0): ?>
<div class="pending-logistics-section">
    <div class="section-header">
        <div class="section-title">
            <h3>
                <i class="fas fa-clock"></i>
                Pending Logistics Orders
            </h3>
            <p>Orders allocated to logistics but not yet created in Gaaubesi system</p>
        </div>
        <div class="section-actions">
            <a href="<?php echo e(route('admin.gaaubesi.bulk-create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Bulk Shipments
            </a>
        </div>
    </div>
    
    <div class="pending-orders-grid">
        <?php $__currentLoopData = $pendingLogisticsOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="pending-order-card">
            <div class="order-header">
                <div class="order-info">
                    <h4><?php echo e($order->order_number); ?></h4>
                    <span class="customer-name"><?php echo e($order->user->name ?? 'N/A'); ?></span>
                </div>
                <div class="order-amount">
                    <span class="amount">Rs. <?php echo e(number_format($order->total, 0)); ?></span>
                    <span class="payment-method"><?php echo e(ucfirst($order->payment_method)); ?></span>
                </div>
            </div>
            
            <div class="order-details">
                <div class="detail-row">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?php echo e($order->shipping_address); ?></span>
                </div>
                <div class="detail-row">
                    <i class="fas fa-phone"></i>
                    <span><?php echo e($order->user->phone ?? 'N/A'); ?></span>
                </div>
                <div class="detail-row">
                    <i class="fas fa-box"></i>
                    <span><?php echo e($order->orderItems->count()); ?> products</span>
                </div>
                <div class="detail-row">
                    <i class="fas fa-calendar"></i>
                    <span>Allocated: <?php echo e($order->shipment->created_at->format('M d, Y')); ?></span>
                </div>
            </div>
            
            <div class="order-actions">
                <a href="<?php echo e(route('admin.gaaubesi.create', ['order_id' => $order->id])); ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Create Shipment
                </a>
                <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-eye"></i> View Details
                </a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>

<!-- Shipments Table -->
<div class="shipments-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tracking Info</th>
                    <th>Order</th>
                    <th>Branch</th>
                    <th>Receiver</th>
                    <th>COD Amount</th>
                    <th>Status</th>
                    <th>COD Paid</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $shipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="tracking-info">
                            <div class="gaaubesi-order-id">#<?php echo e($shipment->gaaubesi_order_id ?? 'N/A'); ?></div>
                            <?php if($shipment->track_id): ?>
                                <div class="track-id"><?php echo e($shipment->track_id); ?></div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="order-info">
                            <div class="order-number"><?php echo e($shipment->order->order_number); ?></div>
                            <div class="receiver-name"><?php echo e($shipment->receiver_name); ?></div>
                        </div>
                    </td>
                    <td>
                        <div class="branch-info">
                            <div class="branch-badge"><?php echo e($shipment->source_branch); ?></div>
                            <div style="text-align: center; color: var(--text-secondary); font-size: 0.75rem;">↓</div>
                            <div class="branch-badge"><?php echo e($shipment->destination_branch); ?></div>
                        </div>
                    </td>
                    <td>
                        <div><?php echo e($shipment->receiver_name); ?></div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary);"><?php echo e($shipment->receiver_number); ?></div>
                    </td>
                    <td>
                        <strong>₹<?php echo e(number_format($shipment->cod_charge, 2)); ?></strong>
                        <?php if($shipment->delivery_charge): ?>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">
                                Delivery: ₹<?php echo e(number_format($shipment->delivery_charge, 2)); ?>

                            </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="delivery-status <?php echo e(strtolower(str_replace(' ', '-', $shipment->last_delivery_status ?? 'created'))); ?>">
                            <?php echo e($shipment->last_delivery_status ?? 'Created'); ?>

                        </span>
                    </td>
                    <td>
                        <span class="cod-badge <?php echo e($shipment->cod_paid ? 'paid' : 'unpaid'); ?>">
                            <?php echo e($shipment->cod_paid ? 'Paid' : 'Unpaid'); ?>

                        </span>
                    </td>
                    <td><?php echo e($shipment->created_at->format('M d, Y')); ?></td>
                    <td>
                        <div class="d-flex flex-wrap">
                            <a href="<?php echo e(route('admin.gaaubesi.show', $shipment->id)); ?>" class="action-btn view">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <?php if($shipment->track_id): ?>
                                <a href="<?php echo e($shipment->getTrackingUrl()); ?>" target="_blank" class="action-btn track">
                                    <i class="fas fa-map-marker-alt"></i> Track
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="no-shipments">
                        <i class="fas fa-shipping-fast"></i>
                        <h5>No Gaaubesi Shipments</h5>
                        <p>Start by creating your first shipment with Gaaubesi Logistics.</p>
                        <a href="<?php echo e(route('admin.gaaubesi.create')); ?>" class="btn btn-primary mt-3">
                            <i class="fas fa-plus"></i> Create Shipment
                        </a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($shipments->hasPages()): ?>
    <div class="d-flex justify-content-center mt-4">
        <?php echo e($shipments->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const shipmentsSearch = document.getElementById('shipments-search');
        const statusFilter = document.getElementById('status-filter');
        const codFilter = document.getElementById('cod-filter');

        // Live Search with Debounce
        shipmentsSearch.addEventListener('keyup', debounce(function() {
            applyFilters();
        }, 300));

        // Filter changes
        statusFilter.addEventListener('change', applyFilters);
        codFilter.addEventListener('change', applyFilters);

        function applyFilters() {
            const search = shipmentsSearch.value;
            const status = statusFilter.value;
            const codPaid = codFilter.value;

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (status) params.append('status', status);
            if (codPaid) params.append('cod_paid', codPaid);

            window.location.href = `<?php echo e(route('admin.gaaubesi.index')); ?>?${params.toString()}`;
        }
    });
</script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/gaaubesi/index.blade.php ENDPATH**/ ?>