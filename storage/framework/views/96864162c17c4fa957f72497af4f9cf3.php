<?php $__env->startSection('title', 'Processed Orders'); ?>
<?php $__env->startSection('page-title', 'Processed Orders'); ?>
<?php $__env->startSection('page-subtitle', 'Manage confirmed, shipped, delivered, and cancelled orders'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <div class="breadcrumb-item">
        <a href="<?php echo e(route('admin.orders.index')); ?>" class="breadcrumb-link">Orders</a>
    </div>
    <div class="breadcrumb-item active">Processed Orders</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Search and Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>Search & Filters
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Search Orders</label>
    <div class="search-box">
        <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           placeholder="Search by ID, customer name, or email..." 
                           id="order-search" 
                           value="<?php echo e(request('search')); ?>"
                           class="search-input">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Payment Status</label>
                <select class="form-select" id="payment-status-filter">
                    <option value="">All Payment Status</option>
                    <option value="paid" <?php echo e(request('payment_status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                    <option value="unpaid" <?php echo e(request('payment_status') == 'unpaid' ? 'selected' : ''); ?>>Unpaid</option>
                    <option value="refunded" <?php echo e(request('payment_status') == 'refunded' ? 'selected' : ''); ?>>Refunded</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date Range</label>
                <input type="date" 
                       class="form-control" 
                       id="date-from"
                       placeholder="From Date"
                       value="<?php echo e(request('date_from')); ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button class="btn btn-primary" onclick="applyFilters()">
                    <i class="fas fa-search"></i> Search
                </button>
                <button class="btn btn-outline-secondary" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-trend neutral">
                    <span><?php echo e($allOrdersCount ?? 0); ?></span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo e($allOrdersCount ?? 0); ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-trend neutral">
                    <span><?php echo e($confirmedCount ?? 0); ?></span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo e($confirmedCount ?? 0); ?></div>
                <div class="stat-label">Confirmed</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="stat-trend neutral">
                    <span><?php echo e($processingCount ?? 0); ?></span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo e($processingCount ?? 0); ?></div>
                <div class="stat-label">Processing</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <div class="stat-trend neutral">
                    <span><?php echo e($shippedCount ?? 0); ?></span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo e($shippedCount ?? 0); ?></div>
                <div class="stat-label">Shipped</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stat-trend neutral">
                    <span><?php echo e($deliveredCount ?? 0); ?></span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo e($deliveredCount ?? 0); ?></div>
                <div class="stat-label">Delivered</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-times"></i>
                </div>
                <div class="stat-trend neutral">
                    <span><?php echo e($cancelledCount ?? 0); ?></span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo e($cancelledCount ?? 0); ?></div>
                <div class="stat-label">Cancelled</div>
            </div>
        </div>
        </div>
    </div>


    <!-- Filter Tabs -->
    <div class="card mb-4">
        <div class="card-body">
    <div class="filter-tabs">
        <a href="<?php echo e(route('admin.orders.index', ['status' => 'all'])); ?>" 
           class="filter-tab <?php echo e(request('status', 'all') == 'all' ? 'active' : ''); ?>">
                <i class="fas fa-list"></i>
                <span>All Orders (<?php echo e($allOrdersCount ?? 0); ?>)</span>
        </a>
        <a href="<?php echo e(route('admin.orders.index', ['status' => 'confirmed'])); ?>" 
           class="filter-tab <?php echo e(request('status') == 'confirmed' ? 'active' : ''); ?>">
                <i class="fas fa-check-circle"></i>
                <span>Confirmed (<?php echo e($confirmedCount ?? 0); ?>)</span>
        </a>
        <a href="<?php echo e(route('admin.orders.index', ['status' => 'processing'])); ?>" 
           class="filter-tab <?php echo e(request('status') == 'processing' ? 'active' : ''); ?>">
                <i class="fas fa-cog"></i>
                <span>Processing (<?php echo e($processingCount ?? 0); ?>)</span>
        </a>
        <a href="<?php echo e(route('admin.orders.index', ['status' => 'shipped'])); ?>" 
           class="filter-tab <?php echo e(request('status') == 'shipped' ? 'active' : ''); ?>">
                <i class="fas fa-shipping-fast"></i>
                <span>Shipped (<?php echo e($shippedCount ?? 0); ?>)</span>
        </a>
        <a href="<?php echo e(route('admin.orders.index', ['status' => 'completed'])); ?>" 
           class="filter-tab <?php echo e(request('status') == 'completed' ? 'active' : ''); ?>">
                <i class="fas fa-check"></i>
                <span>Delivered (<?php echo e($deliveredCount ?? 0); ?>)</span>
        </a>
        <a href="<?php echo e(route('admin.orders.index', ['status' => 'cancelled'])); ?>" 
           class="filter-tab <?php echo e(request('status') == 'cancelled' ? 'active' : ''); ?>">
                <i class="fas fa-times"></i>
                <span>Cancelled (<?php echo e($cancelledCount ?? 0); ?>)</span>
        </a>
        </div>
    </div>
    </div>

    <!-- Bulk Actions -->
<div class="card mb-4" id="bulk-actions-container" style="display: none;">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="select-all-orders">
                    <label for="select-all-orders" class="form-check-label">Select all orders</label>
                </div>
                <span class="selected-count text-muted">0 selected</span>
        </div>
            <div class="bulk-action-buttons d-flex gap-2">
                <button class="btn btn-sm btn-success" onclick="bulkConfirm()">
                <i class="fas fa-check-circle"></i> Confirm
            </button>
            <button class="btn btn-sm btn-warning" onclick="bulkProcess()">
                <i class="fas fa-cog"></i> Process
            </button>
            <button class="btn btn-sm btn-primary" onclick="bulkShip()">
                <i class="fas fa-shipping-fast"></i> Ship
            </button>
            <button class="btn btn-sm btn-success" onclick="bulkDeliver()">
                <i class="fas fa-check"></i> Deliver
            </button>
            <button class="btn btn-sm btn-secondary" onclick="bulkCancel()">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button class="btn btn-sm btn-danger" onclick="bulkDelete()">
                <i class="fas fa-trash"></i> Delete
            </button>
            </div>
        </div>
        </div>
    </div>

    <!-- Orders Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Orders</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-secondary" onclick="toggleView('table')">
                    <i class="fas fa-table"></i>
                </button>
                <button class="btn btn-sm btn-outline-secondary" onclick="toggleView('grid')">
                    <i class="fas fa-th"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" class="form-check-input" id="select-all-orders-header">
                        </th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr data-order-id="<?php echo e($order->id); ?>">
                        <td>
                            <input type="checkbox" class="form-check-input order-checkbox" value="<?php echo e($order->id); ?>">
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="text-decoration-none fw-semibold">
                                    <?php echo e($order->order_number); ?>

                                </a>
                                <small class="text-muted"><?php echo e($order->id); ?></small>
                    </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-initials">
                                        <?php echo e(substr($order->user->name ?? 'G', 0, 1)); ?>

                        </div>
                    </div>
                                <div>
                                    <div class="fw-semibold"><?php echo e($order->user->name ?? 'Guest'); ?></div>
                                    <small class="text-muted"><?php echo e($order->user->email ?? 'N/A'); ?></small>
                        </div>
                    </div>
                        </td>
                        <td>
                            <div class="fw-semibold">Rs. <?php echo e(number_format($order->total, 2)); ?></div>
                            <small class="text-muted"><?php echo e($order->orderItems->count()); ?> items</small>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo e($order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'info'))); ?>">
                                <?php echo e(ucfirst($order->status)); ?>

                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo e($order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'refunded' ? 'warning' : 'danger')); ?>">
                                <?php echo e(ucfirst($order->payment_status)); ?>

                            </span>
                        </td>
                        <td>
                            <div class="text-muted"><?php echo e($order->created_at->format('M j, Y')); ?></div>
                            <small class="text-muted"><?php echo e($order->created_at->format('g:i A')); ?></small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo e(route('admin.orders.show', $order)); ?>" 
                                   class="btn btn-outline-primary" 
                                   title="View Order">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.orders.edit', $order)); ?>" 
                                   class="btn btn-outline-secondary" 
                                   title="Edit Order">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" 
                                            data-bs-toggle="dropdown" 
                                            title="More Actions">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="changeStatus(<?php echo e($order->id); ?>, 'confirmed')">
                                                <i class="fas fa-check-circle text-success"></i> Confirm
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="changeStatus(<?php echo e($order->id); ?>, 'processing')">
                                                <i class="fas fa-cog text-warning"></i> Process
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="changeStatus(<?php echo e($order->id); ?>, 'shipped')">
                                                <i class="fas fa-shipping-fast text-info"></i> Ship
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="changeStatus(<?php echo e($order->id); ?>, 'completed')">
                                                <i class="fas fa-check text-success"></i> Deliver
                                            </a>
                                        </li>
                                <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" onclick="deleteOrder(<?php echo e($order->id); ?>)">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </li>
                            </ul>
                        </div>
                    </div>
                        </td>
                    </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
            <div class="empty-state">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">No orders found</h6>
                                <p class="text-muted">Orders will appear here once customers start placing them.</p>
                                <a href="<?php echo e(route('admin.orders.create')); ?>" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Create Order
                                </a>
                </div>
                        </td>
                    </tr>
            <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
<?php if($orders->hasPages()): ?>
<div class="d-flex justify-content-center mt-4">
    <?php echo e($orders->links()); ?>

</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.filter-tab {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: rgba(30, 41, 59, 0.5);
    border: 1px solid rgba(99, 102, 241, 0.2);
    border-radius: var(--radius-lg);
    color: var(--text-secondary);
    text-decoration: none;
    transition: all var(--transition-fast);
    font-weight: 500;
}

.filter-tab:hover {
    background: rgba(99, 102, 241, 0.1);
    color: var(--text-primary);
    border-color: rgba(99, 102, 241, 0.3);
}

.filter-tab.active {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border-color: transparent;
    box-shadow: var(--shadow-md);
}

.filter-tab i {
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

/* Badge Styles */
.badge {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
}

.badge-success {
    background: rgba(34, 197, 94, 0.1);
    color: var(--success);
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.badge-warning {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.badge-danger {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.badge-info {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

/* Empty State */
.empty-state {
    padding: var(--space-2xl);
}

/* Responsive */
@media (max-width: 768px) {
    .filter-tabs {
        flex-direction: column;
    }
    
    .filter-tab {
        justify-content: center;
    }
    
    .bulk-action-buttons {
        flex-wrap: wrap;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bulk selection
    const selectAllCheckbox = document.getElementById('select-all-orders');
    const selectAllHeaderCheckbox = document.getElementById('select-all-orders-header');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const bulkActionsContainer = document.getElementById('bulk-actions-container');
    const selectedCount = document.querySelector('.selected-count');
    
    // Header checkbox controls all
    selectAllHeaderCheckbox.addEventListener('change', function() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        selectAllCheckbox.checked = this.checked;
        updateBulkActions();
    });
    
    // Individual checkboxes
    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActions();
            updateSelectAllState();
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('order-search');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchTerm = this.value;
            if (searchTerm.length >= 2 || searchTerm.length === 0) {
                performSearch();
            }
        }, 500);
    });
    
    // Payment status filter
    const paymentStatusFilter = document.getElementById('payment-status-filter');
    paymentStatusFilter.addEventListener('change', function() {
        performSearch();
    });
});

function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
    const selectedCount = document.querySelector('.selected-count');
    
    if (checkedBoxes.length > 0) {
        bulkActionsContainer.style.display = 'block';
        selectedCount.textContent = `${checkedBoxes.length} selected`;
    } else {
        bulkActionsContainer.style.display = 'none';
        selectedCount.textContent = '0 selected';
    }
}

function updateSelectAllState() {
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
    const selectAllCheckbox = document.getElementById('select-all-orders');
    const selectAllHeaderCheckbox = document.getElementById('select-all-orders-header');
    
    if (checkedBoxes.length === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
        selectAllHeaderCheckbox.indeterminate = false;
        selectAllHeaderCheckbox.checked = false;
    } else if (checkedBoxes.length === orderCheckboxes.length) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
        selectAllHeaderCheckbox.indeterminate = false;
        selectAllHeaderCheckbox.checked = true;
    } else {
        selectAllCheckbox.indeterminate = true;
        selectAllHeaderCheckbox.indeterminate = true;
    }
}

function performSearch() {
    const searchTerm = document.getElementById('order-search').value;
    const paymentStatus = document.getElementById('payment-status-filter').value;
    const currentStatus = new URLSearchParams(window.location.search).get('status') || 'all';
    
    const params = new URLSearchParams();
    if (searchTerm) params.set('search', searchTerm);
    if (paymentStatus) params.set('payment_status', paymentStatus);
    if (currentStatus !== 'all') params.set('status', currentStatus);
    
    window.location.href = `<?php echo e(route('admin.orders.index')); ?>?${params.toString()}`;
}

function bulkConfirm() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    performBulkAction('confirm', checkedIds);
}

function bulkProcess() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    performBulkAction('process', checkedIds);
}

function bulkShip() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    performBulkAction('ship', checkedIds);
}

function bulkDeliver() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    performBulkAction('deliver', checkedIds);
}

function bulkCancel() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    if (confirm(`Are you sure you want to cancel ${checkedIds.length} orders?`)) {
        performBulkAction('cancel', checkedIds);
    }
}

function bulkDelete() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    if (confirm(`Are you sure you want to delete ${checkedIds.length} orders? This action cannot be undone.`)) {
        performBulkAction('delete', checkedIds);
    }
}

function performBulkAction(action, orderIds) {
    fetch('<?php echo e(route('admin.orders.bulk-action')); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            action: action,
            order_ids: orderIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`${data.message}`, 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showNotification('Failed to perform bulk action', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while performing bulk action', 'error');
    });
}

function changeStatus(orderId, status) {
    if (confirm(`Are you sure you want to change this order status to ${status}?`)) {
        fetch(`<?php echo e(url('/admin/orders')); ?>/${orderId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: status,
                payment_status: 'paid' // Default payment status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Order status updated successfully!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification('Failed to update order status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while updating order status', 'error');
        });
    }
}

function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        fetch(`<?php echo e(url('/admin/orders')); ?>/${orderId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Order deleted successfully!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification('Failed to delete order', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while deleting order', 'error');
        });
    }
}

function exportOrders() {
    const currentStatus = new URLSearchParams(window.location.search).get('status') || 'all';
    const searchTerm = document.getElementById('order-search').value;
    const paymentStatus = document.getElementById('payment-status-filter').value;
    
    const params = new URLSearchParams();
    if (searchTerm) params.set('search', searchTerm);
    if (paymentStatus) params.set('payment_status', paymentStatus);
    if (currentStatus !== 'all') params.set('status', currentStatus);
    
    window.open(`<?php echo e(route('admin.orders.index')); ?>?export=1&${params.toString()}`, '_blank');
}

function toggleView(view) {
    // Implementation for table/grid view toggle
    console.log('Toggle view to:', view);
}

function applyFilters() {
    const searchTerm = document.getElementById('order-search').value;
    const paymentStatus = document.getElementById('payment-status-filter').value;
    const dateFrom = document.getElementById('date-from').value;
    const currentStatus = new URLSearchParams(window.location.search).get('status') || 'all';
    
    const params = new URLSearchParams();
    if (searchTerm) params.set('search', searchTerm);
    if (paymentStatus) params.set('payment_status', paymentStatus);
    if (dateFrom) params.set('date_from', dateFrom);
    if (currentStatus !== 'all') params.set('status', currentStatus);
    
    window.location.href = `<?php echo e(route('admin.orders.index')); ?>?${params.toString()}`;
}

function clearFilters() {
    window.location.href = '<?php echo e(route('admin.orders.index')); ?>';
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/orders/index.blade.php ENDPATH**/ ?>