<?php $__env->startSection('title', 'Pending Orders Management'); ?>
<?php $__env->startSection('page-title', 'Pending Orders Management'); ?>
<?php $__env->startSection('page-subtitle', 'Manage manual orders awaiting confirmation'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <div class="breadcrumb-item">
        <a href="<?php echo e(route('admin.orders.index')); ?>" class="breadcrumb-link">Orders</a>
    </div>
    <div class="breadcrumb-item active">Pending Orders</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Search and Actions -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           placeholder="Search orders by ID, customer name, phone, or email..." 
                           id="order-search" 
                           value="<?php echo e(request('search')); ?>"
                           class="search-input">
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="<?php echo e(route('admin.rejected-orders.index')); ?>" class="btn btn-outline-danger">
                        <i class="fas fa-times-circle"></i> Rejected Orders
                    </a>
                    <a href="<?php echo e(route('admin.pending-orders.create-bulk')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-plus"></i> Bulk Create
                    </a>
                    <a href="<?php echo e(route('admin.pending-orders.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Order
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-trend neutral">
                    <span><?php echo e($totalPendingOrders ?? 0); ?></span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo e($totalPendingOrders ?? 0); ?></div>
                <div class="stat-label">Total Pending</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="stat-trend neutral">
                    <span><?php echo e($manualOrdersCount ?? 0); ?></span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo e($manualOrdersCount ?? 0); ?></div>
                <div class="stat-label">Manual Orders</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-trend neutral">
                    <span><?php echo e($orders->total() ?? 0); ?></span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?php echo e($orders->total() ?? 0); ?></div>
                <div class="stat-label">Showing</div>
            </div>
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
                    <i class="fas fa-check"></i> Confirm
                </button>
                <button class="btn btn-sm btn-danger" onclick="bulkReject()">
                    <i class="fas fa-times"></i> Reject
                </button>
                <button class="btn btn-sm btn-secondary" onclick="bulkDelete()">
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
            <h5 class="card-title mb-0">Manual Orders</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()" title="Refresh">
                    <i class="fas fa-sync-alt"></i>
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
                        <th>Address</th>
                        <th>Amount</th>
                        <th>Products</th>
                        <th width="140">Actions</th>
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
                                <a href="<?php echo e(route('admin.pending-orders.show', $order)); ?>" class="text-decoration-none fw-semibold">
                                    <?php echo e($order->order_number); ?>

                                </a>
                                <small class="text-muted"><?php echo e($order->id); ?></small>
                                <span class="badge badge-info">Manual</span>
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
                                    <small class="text-muted"><?php echo e($order->user->phone ?? 'N/A'); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="address-info">
                                <div class="fw-semibold"><?php echo e(Str::limit($order->shipping_address ?? 'Not specified', 30)); ?></div>
                                <small class="text-muted"><?php echo e($order->receiver_city ?? 'N/A'); ?></small>
                            </div>
                        </td>
                        <td>
                            <div class="amount-info">
                                <div class="fw-semibold">Rs. <?php echo e(number_format($order->total, 0)); ?></div>
                                <div class="d-flex gap-2">
                                    <?php if($order->payment_status === 'paid'): ?>
                                        <span class="badge badge-success">Paid</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">COD</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="products-info">
                                <div class="fw-semibold"><?php echo e($order->orderItems->count()); ?> items</div>
                                <small class="text-muted">
                                    <?php if($order->orderItems->count() > 0): ?>
                                        <?php echo e(Str::limit($order->orderItems->first()->product_name ?? 'N/A', 20)); ?>

                                        <?php if($order->orderItems->count() > 1): ?>
                                            +<?php echo e($order->orderItems->count() - 1); ?> more
                                        <?php endif; ?>
                                    <?php else: ?>
                                        No products
                                    <?php endif; ?>
                                </small>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons d-flex gap-1">
                                <button class="action-btn confirm" 
                                        onclick="confirmOrder(<?php echo e($order->id); ?>)" 
                                        title="Confirm Order">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="action-btn reject" 
                                        onclick="rejectOrder(<?php echo e($order->id); ?>)" 
                                        title="Reject Order">
                                    <i class="fas fa-times"></i>
                                </button>
                                <a href="<?php echo e(route('admin.pending-orders.edit', $order)); ?>" 
                                   class="action-btn edit" 
                                   title="Edit Order">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <button class="action-btn delete" 
                                        onclick="deleteOrder(<?php echo e($order->id); ?>)" 
                                        title="Delete Order">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">No pending orders found</h6>
                                <p class="text-muted">Manual orders will appear here once created.</p>
                                <a href="<?php echo e(route('admin.pending-orders.create')); ?>" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Create Your First Order
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

<!-- Confirm Order Modal -->
<div class="modal fade" id="confirmOrderModal" tabindex="-1" aria-labelledby="confirmOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmOrderModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Confirm Order
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Order Number:</strong> <span id="confirmOrderNumber">-</span>
                </div>
                
                <div class="mb-3">
                    <label for="confirmShippingCost" class="form-label">Shipping Cost (₨) *</label>
                    <input type="number" class="form-control" id="confirmShippingCost" 
                           value="0.00" min="0" step="0.01" required>
                    <small class="text-muted">Enter the shipping cost for this order</small>
                </div>
                
                <div class="mb-3">
                    <label for="confirmTaxAmount" class="form-label">Tax Amount (₨) *</label>
                    <input type="number" class="form-control" id="confirmTaxAmount" 
                           value="0.00" min="0" step="0.01" required>
                    <small class="text-muted">Enter the tax amount for this order</small>
                </div>
                
                <div class="border-top pt-3 mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong id="confirmSubtotal">₨0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <strong id="confirmShippingDisplay">₨0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <strong id="confirmTaxDisplay">₨0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-2">
                        <strong>Total:</strong>
                        <strong class="text-primary fs-5" id="confirmTotal">₨0.00</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmOrderSubmitBtn" onclick="submitConfirmOrder()">
                    <i class="fas fa-check me-2"></i>Confirm Order
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.25rem;
    align-items: center;
    flex-wrap: wrap;
}

.action-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.75rem;
    font-weight: 600;
    transition: all var(--transition-fast);
    text-decoration: none;
    box-shadow: var(--shadow-sm);
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.action-btn i {
    font-size: 0.75rem;
}

.action-btn.confirm {
    background: linear-gradient(135deg, var(--success), #059669);
    color: white;
    border: 1px solid #059669;
}

.action-btn.confirm:hover {
    background: linear-gradient(135deg, #059669, #047857);
    border-color: #047857;
}

.action-btn.reject {
    background: linear-gradient(135deg, var(--danger), #DC2626);
    color: white;
    border: 1px solid #DC2626;
}

.action-btn.reject:hover {
    background: linear-gradient(135deg, #DC2626, #B91C1C);
    border-color: #B91C1C;
}

.action-btn.edit {
    background: linear-gradient(135deg, var(--warning), #D97706);
    color: white;
    border: 1px solid #D97706;
}

.action-btn.edit:hover {
    background: linear-gradient(135deg, #D97706, #B45309);
    border-color: #B45309;
}

.action-btn.delete {
    background: linear-gradient(135deg, var(--danger), #DC2626);
    color: white;
    border: 1px solid #DC2626;
}

.action-btn.delete:hover {
    background: linear-gradient(135deg, #DC2626, #B91C1C);
    border-color: #B91C1C;
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
    font-size: 0.625rem;
    font-weight: 600;
    padding: 0.125rem 0.375rem;
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

.badge-info {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

/* Responsive */
@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
        gap: 0.125rem;
    }
    
    .action-btn {
        width: 28px;
        height: 28px;
        font-size: 0.625rem;
    }
    
    .action-btn i {
        font-size: 0.625rem;
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
    
    const params = new URLSearchParams();
    if (searchTerm) params.set('search', searchTerm);
    
    window.location.href = `<?php echo e(route('admin.pending-orders.index')); ?>?${params.toString()}`;
}

function bulkConfirm() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    if (confirm(`Are you sure you want to confirm ${checkedIds.length} orders? Confirmed orders will be moved to Order Management.`)) {
        performBulkAction('confirm', checkedIds);
    }
}

function bulkReject() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    if (confirm(`Are you sure you want to reject ${checkedIds.length} orders? Rejected orders will be moved to Rejected Orders list.`)) {
        performBulkAction('reject', checkedIds);
    }
}

function bulkDelete() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    if (confirm(`Are you sure you want to delete ${checkedIds.length} orders? This action cannot be undone.`)) {
        performBulkAction('delete', checkedIds);
    }
}

function performBulkAction(action, orderIds) {
    fetch('<?php echo e(route('admin.pending-orders.bulk-action')); ?>', {
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

// Confirm Order Modal - replaced old confirmOrder function

function rejectOrder(orderId) {
    if (confirm('Are you sure you want to reject this order? It will be moved to Rejected Orders list.')) {
        fetch(`<?php echo e(url('/admin/pending-orders')); ?>/${orderId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Order rejected successfully!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'Failed to reject order', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while rejecting order', 'error');
        });
    }
}

function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        fetch(`<?php echo e(url('/admin/pending-orders')); ?>/${orderId}`, {
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

// Confirm Order Modal
let currentOrderId = null;

<?php
    // Pass order data to JavaScript for subtotal calculation
    $ordersData = [];
    foreach($orders as $order) {
        $subtotal = $order->orderItems->sum(function($item) {
            return ($item->quantity ?? 0) * ($item->price ?? 0);
        });
        $ordersData[$order->id] = [
            'subtotal' => $subtotal,
            'order_number' => $order->order_number
        ];
    }
?>

const ordersData = <?php echo json_encode($ordersData, 15, 512) ?>;

function confirmOrder(orderId) {
    currentOrderId = orderId;
    
    const orderData = ordersData[orderId] || { subtotal: 0, order_number: 'N/A' };
    
    // Set default values
    document.getElementById('confirmOrderNumber').textContent = orderData.order_number;
    document.getElementById('confirmSubtotal').textContent = '₨' + orderData.subtotal.toFixed(2);
    document.getElementById('confirmShippingCost').value = '0.00';
    document.getElementById('confirmTaxAmount').value = '0.00';
    updateConfirmTotal();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('confirmOrderModal'));
    modal.show();
}

function updateConfirmTotal() {
    const subtotalText = document.getElementById('confirmSubtotal').textContent.replace(/[^\d.]/g, '');
    const subtotal = parseFloat(subtotalText) || 0;
    const shipping = parseFloat(document.getElementById('confirmShippingCost').value) || 0;
    const tax = parseFloat(document.getElementById('confirmTaxAmount').value) || 0;
    const total = subtotal + shipping + tax;
    
    document.getElementById('confirmShippingDisplay').textContent = '₨' + shipping.toFixed(2);
    document.getElementById('confirmTaxDisplay').textContent = '₨' + tax.toFixed(2);
    document.getElementById('confirmTotal').textContent = '₨' + total.toFixed(2);
}

function submitConfirmOrder() {
    if (!currentOrderId) {
        showNotification('Order ID not found', 'error');
        return;
    }
    
    const shippingCost = parseFloat(document.getElementById('confirmShippingCost').value) || 0;
    const taxAmount = parseFloat(document.getElementById('confirmTaxAmount').value) || 0;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        alert('CSRF token not found. Please refresh the page.');
        return;
    }
    
    // Disable submit button
    const submitBtn = document.getElementById('confirmOrderSubmitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Confirming...';
    
    const confirmUrl = `<?php echo e(route('admin.pending-orders.confirm', ['pending_order' => '__ID__'])); ?>`.replace('__ID__', currentOrderId);
    
    fetch(confirmUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            shipping_cost: shippingCost,
            tax_amount: taxAmount
        })
    })
    .then(async response => {
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            // If not JSON, get text to see what we got
            const text = await response.text();
            console.error('Non-JSON response received:', text.substring(0, 200));
            throw new Error(`Server returned non-JSON response. Status: ${response.status}`);
        }
        
        if (!response.ok) {
            // Try to parse error response
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Hide modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('confirmOrderModal'));
            modal.hide();
            
            if (typeof showNotification === 'function') {
                showNotification('Order confirmed successfully!', 'success');
            } else {
                alert('Order confirmed successfully!');
            }
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            const message = data.message || 'Failed to confirm order';
            if (typeof showNotification === 'function') {
                showNotification(message, 'error');
            } else {
                alert('Error: ' + message);
            }
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check"></i> Confirm Order';
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        const errorMessage = 'An error occurred while confirming order: ' + error.message;
        if (typeof showNotification === 'function') {
            showNotification(errorMessage, 'error');
        } else {
            alert(errorMessage);
        }
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check"></i> Confirm Order';
    });
}

// Add event listeners for input changes
document.addEventListener('DOMContentLoaded', function() {
    const shippingInput = document.getElementById('confirmShippingCost');
    const taxInput = document.getElementById('confirmTaxAmount');
    
    if (shippingInput) {
        shippingInput.addEventListener('input', updateConfirmTotal);
    }
    if (taxInput) {
        taxInput.addEventListener('input', updateConfirmTotal);
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/pending-orders/index.blade.php ENDPATH**/ ?>