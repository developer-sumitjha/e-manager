<?php $__env->startSection('title', 'Pending Order Details'); ?>
<?php $__env->startSection('page-title', 'Pending Order Details'); ?>
<?php $__env->startSection('page-subtitle', 'View and manage pending order information'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <div class="breadcrumb-item">
        <a href="<?php echo e(route('admin.orders.index')); ?>" class="breadcrumb-link">Orders</a>
    </div>
    <div class="breadcrumb-item">
        <a href="<?php echo e(route('admin.pending-orders.index')); ?>" class="breadcrumb-link">Pending Orders</a>
    </div>
    <div class="breadcrumb-item active">Order #<?php echo e($pending_order->order_number); ?></div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Order Information -->
    <div class="col-xl-8 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Order #<?php echo e($pending_order->order_number); ?></h5>
                        <small class="text-muted">Created <?php echo e($pending_order->created_at->format('M j, Y \a\t g:i A')); ?></small>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge badge-warning">
                            <?php echo e(ucfirst($pending_order->status)); ?>

                        </span>
                        <span class="badge badge-<?php echo e($pending_order->payment_status === 'paid' ? 'success' : ($pending_order->payment_status === 'refunded' ? 'warning' : 'danger')); ?>">
                            <?php echo e(ucfirst($pending_order->payment_status)); ?>

                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Order Items -->
                <h6 class="fw-semibold mb-3">Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $pending_order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if($item->product && $item->product->primary_image_url): ?>
                                            <img src="<?php echo e($item->product->primary_image_url); ?>" 
                                                 alt="<?php echo e($item->product->name); ?>" 
                                                 class="product-thumb me-3">
                                        <?php else: ?>
                                            <div class="product-thumb me-3">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="fw-semibold"><?php echo e($item->product->name ?? 'N/A'); ?></div>
                                            <?php if($item->product): ?>
                                                <small class="text-muted">SKU: <?php echo e($item->product->sku ?? 'N/A'); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>Rs. <?php echo e(number_format($item->price, 2)); ?></td>
                                <td>
                                    <span class="badge badge-info"><?php echo e($item->quantity); ?></span>
                                </td>
                                <td class="fw-semibold">Rs. <?php echo e(number_format($item->total, 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-semibold">Subtotal:</td>
                                <td class="fw-semibold">Rs. <?php echo e(number_format($pending_order->subtotal ?? $pending_order->orderItems->sum('total'), 2)); ?></td>
                            </tr>
                            <?php if($pending_order->tax_amount > 0): ?>
                            <tr>
                                <td colspan="3" class="text-end">Tax:</td>
                                <td>Rs. <?php echo e(number_format($pending_order->tax_amount, 2)); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if($pending_order->shipping_cost > 0): ?>
                            <tr>
                                <td colspan="3" class="text-end">Shipping:</td>
                                <td>Rs. <?php echo e(number_format($pending_order->shipping_cost, 2)); ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr class="table-active">
                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                <td class="fw-bold">Rs. <?php echo e(number_format($pending_order->total, 2)); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Notes -->
        <?php if($pending_order->notes): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Order Notes</h6>
            </div>
            <div class="card-body">
                <p class="mb-0"><?php echo e($pending_order->notes); ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar Information -->
    <div class="col-xl-4">
        <!-- Customer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Customer Information</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-lg me-3">
                        <div class="avatar-initials">
                            <?php echo e(substr($pending_order->receiver_name ?? $pending_order->user->name ?? 'G', 0, 1)); ?>

                        </div>
                    </div>
                    <div>
                        <div class="fw-semibold"><?php echo e($pending_order->receiver_name ?? $pending_order->user->name ?? 'Guest'); ?></div>
                        <small class="text-muted"><?php echo e($pending_order->receiver_phone ?? $pending_order->user->phone ?? 'N/A'); ?></small>
                    </div>
                </div>
                
                <div class="row g-2">
                    <div class="col-12">
                        <small class="text-muted">Phone</small>
                        <div class="fw-semibold"><?php echo e($pending_order->receiver_phone ?? $pending_order->user->phone ?? 'N/A'); ?></div>
                    </div>
                    <?php if($pending_order->user): ?>
                    <div class="col-12">
                        <small class="text-muted">Email</small>
                        <div class="fw-semibold"><?php echo e($pending_order->user->email ?? 'N/A'); ?></div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">Role</small>
                        <div class="fw-semibold"><?php echo e(ucfirst($pending_order->user->role ?? 'Guest')); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Order Details</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <small class="text-muted">Order Date</small>
                        <div class="fw-semibold"><?php echo e($pending_order->created_at->format('M j, Y H:i')); ?></div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">Last Updated</small>
                        <div class="fw-semibold"><?php echo e($pending_order->updated_at->format('M j, Y H:i')); ?></div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">Payment Method</small>
                        <div class="fw-semibold"><?php echo e(ucfirst(str_replace('_', ' ', $pending_order->payment_method ?? 'N/A'))); ?></div>
                    </div>
                    <?php if($pending_order->createdBy): ?>
                    <div class="col-12">
                        <small class="text-muted">Created By</small>
                        <div class="fw-semibold"><?php echo e($pending_order->createdBy->name ?? 'N/A'); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Shipping Address -->
        <?php if($pending_order->shipping_address || $pending_order->receiver_name): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Shipping Address</h6>
            </div>
            <div class="card-body">
                <?php if($pending_order->receiver_name): ?>
                <div class="fw-semibold mb-2"><?php echo e($pending_order->receiver_name); ?></div>
                <?php endif; ?>
                <?php if($pending_order->receiver_phone): ?>
                <div class="text-muted mb-2"><?php echo e($pending_order->receiver_phone); ?></div>
                <?php endif; ?>
                <?php if($pending_order->receiver_full_address): ?>
                <div class="text-muted mb-2"><?php echo e($pending_order->receiver_full_address); ?></div>
                <?php elseif($pending_order->shipping_address): ?>
                <div class="text-muted mb-2"><?php echo e($pending_order->shipping_address); ?></div>
                <?php endif; ?>
                <?php if($pending_order->receiver_city): ?>
                <div class="text-muted"><?php echo e($pending_order->receiver_city); ?><?php echo e($pending_order->receiver_area ? ', ' . $pending_order->receiver_area : ''); ?></div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-success" onclick="confirmOrder(<?php echo e($pending_order->id); ?>)">
                        <i class="fas fa-check"></i> Confirm Order
                    </button>
                    <button class="btn btn-danger" onclick="rejectOrder(<?php echo e($pending_order->id); ?>)">
                        <i class="fas fa-times"></i> Reject Order
                    </button>
                    <a href="<?php echo e(route('admin.pending-orders.edit', $pending_order)); ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Order
                    </a>
                    <button class="btn btn-outline-danger" onclick="deleteOrder(<?php echo e($pending_order->id); ?>)">
                        <i class="fas fa-trash"></i> Delete Order
                    </button>
                    <button class="btn btn-outline-primary" onclick="printOrder()">
                        <i class="fas fa-print"></i> Print Order
                    </button>
                    <a href="<?php echo e(route('admin.pending-orders.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Pending Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

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
                    <strong>Order Number:</strong> <span id="confirmOrderNumber"><?php echo e($pending_order->order_number); ?></span>
                </div>
                
                <div class="mb-3">
                    <label for="confirmShippingCost" class="form-label">Shipping Cost (Rs.) *</label>
                    <input type="number" class="form-control" id="confirmShippingCost" 
                           value="0.00" min="0" step="0.01" required oninput="updateConfirmTotal()" onchange="updateConfirmTotal()">
                    <small class="text-muted">Enter the shipping cost for this order</small>
                </div>
                
                <div class="mb-3">
                    <label for="confirmTaxAmount" class="form-label">Tax Amount (Rs.) *</label>
                    <input type="number" class="form-control" id="confirmTaxAmount" 
                           value="0.00" min="0" step="0.01" required oninput="updateConfirmTotal()" onchange="updateConfirmTotal()">
                    <small class="text-muted">Enter the tax amount for this order</small>
                </div>
                
                <div class="border-top pt-3 mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong id="confirmSubtotal">Rs. <?php echo e(number_format($pending_order->subtotal ?? $pending_order->orderItems->sum('total'), 2)); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <strong id="confirmShippingDisplay">Rs. 0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <strong id="confirmTaxDisplay">Rs. 0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-2">
                        <strong>Total:</strong>
                        <strong class="text-primary fs-5" id="confirmTotal">Rs. <?php echo e(number_format($pending_order->total, 2)); ?></strong>
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
/* Product Thumbnail */
.product-thumb {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-md);
    overflow: hidden;
    background: rgba(30, 41, 59, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.product-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Avatar */
.avatar-lg {
    width: 48px;
    height: 48px;
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
    font-size: 1.125rem;
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

/* Table Active Row */
.table-active {
    background: rgba(99, 102, 241, 0.05) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .product-thumb {
        width: 40px;
        height: 40px;
    }
    
    .avatar-lg {
        width: 40px;
        height: 40px;
    }
    
    .avatar-initials {
        font-size: 1rem;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Store subtotal as a variable to avoid parsing issues
let currentSubtotal = 0;

// Fallback notification function if showNotification doesn't exist
if (typeof showNotification === 'undefined') {
    function showNotification(message, type) {
        // Use AdminDashboard notification if available
        if (window.AdminDashboard && typeof window.AdminDashboard.showNotification === 'function') {
            window.AdminDashboard.showNotification(message, type);
        } else {
            // Fallback to alert
            alert(message);
        }
    }
}

function confirmOrder(orderId) {
    if (!orderId) {
        console.error('Order ID is required');
        showNotification('Order ID is missing', 'error');
        return;
    }

    // Calculate subtotal from order items
    const orderItems = <?php echo json_encode($pending_order->orderItems, 15, 512) ?>;
    currentSubtotal = 0;
    orderItems.forEach(item => {
        currentSubtotal += parseFloat(item.price || 0) * parseInt(item.quantity || 0);
    });

    // Set default values
    document.getElementById('confirmOrderNumber').textContent = '<?php echo e($pending_order->order_number); ?>';
    document.getElementById('confirmSubtotal').textContent = 'Rs. ' + currentSubtotal.toFixed(2);
    document.getElementById('confirmShippingCost').value = '0.00';
    document.getElementById('confirmTaxAmount').value = '0.00';
    updateConfirmTotal();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('confirmOrderModal'));
    modal.show();
}

function updateConfirmTotal() {
    // Use the stored subtotal variable instead of parsing from text
    const subtotal = currentSubtotal || 0;
    const shipping = parseFloat(document.getElementById('confirmShippingCost').value) || 0;
    const tax = parseFloat(document.getElementById('confirmTaxAmount').value) || 0;
    const total = subtotal + shipping + tax;
    
    document.getElementById('confirmShippingDisplay').textContent = 'Rs. ' + shipping.toFixed(2);
    document.getElementById('confirmTaxDisplay').textContent = 'Rs. ' + tax.toFixed(2);
    document.getElementById('confirmTotal').textContent = 'Rs. ' + total.toFixed(2);
}

function submitConfirmOrder() {
    const orderId = <?php echo e($pending_order->id); ?>;
    const shippingCost = parseFloat(document.getElementById('confirmShippingCost').value) || 0;
    const taxAmount = parseFloat(document.getElementById('confirmTaxAmount').value) || 0;
    // Use the stored subtotal variable instead of parsing from text
    const subtotal = currentSubtotal || 0;
    const total = subtotal + shippingCost + taxAmount;

    const submitBtn = document.getElementById('confirmOrderSubmitBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Confirming...';

    fetch(`<?php echo e(url('/admin/pending-orders')); ?>/${orderId}/confirm`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value
        },
        body: JSON.stringify({
            shipping_cost: shippingCost,
            tax_amount: taxAmount,
            subtotal: subtotal,
            total: total,
            // Also send the calculated values to ensure backend gets correct data
            calculated_subtotal: currentSubtotal
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || `Server error: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'Order confirmed successfully!', 'success');
            const modal = bootstrap.Modal.getInstance(document.getElementById('confirmOrderModal'));
            if (modal) {
                modal.hide();
            }
            setTimeout(() => {
                window.location.href = '<?php echo e(route('admin.pending-orders.index')); ?>';
            }, 1500);
        } else {
            throw new Error(data.message || 'Failed to confirm order');
        }
    })
    .catch(error => {
        console.error('Confirm error:', error);
        showNotification(error.message || 'An error occurred while confirming order', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

function rejectOrder(orderId) {
    if (!orderId) {
        console.error('Order ID is required');
        showNotification('Order ID is missing', 'error');
        return;
    }

    if (confirm('Are you sure you want to reject this order? It will be moved to Rejected Orders list.')) {
        const button = event.target.closest('button');
        const originalText = button ? button.innerHTML : '';
        
        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Rejecting...';
        }

        fetch(`<?php echo e(url('/admin/pending-orders')); ?>/${orderId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || `Server error: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'Order rejected successfully!', 'success');
                setTimeout(() => {
                    window.location.href = '<?php echo e(route('admin.pending-orders.index')); ?>';
                }, 1500);
            } else {
                throw new Error(data.message || 'Failed to reject order');
            }
        })
        .catch(error => {
            console.error('Reject error:', error);
            showNotification(error.message || 'An error occurred while rejecting order', 'error');
            if (button) {
                button.disabled = false;
                button.innerHTML = originalText;
            }
        });
    }
}

function deleteOrder(orderId) {
    if (!orderId) {
        console.error('Order ID is required');
        showNotification('Order ID is missing', 'error');
        return;
    }

    if (confirm('Are you sure you want to delete this order? It will be moved to trash.')) {
        const button = event.target.closest('button');
        const originalText = button ? button.innerHTML : '';
        
        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
        }

        fetch(`<?php echo e(url('/admin/pending-orders')); ?>/${orderId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || `Server error: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success !== false) {
                showNotification(data.message || 'Order moved to trash successfully!', 'success');
                setTimeout(() => {
                    window.location.href = '<?php echo e(route('admin.pending-orders.index')); ?>';
                }, 1500);
            } else {
                throw new Error(data.message || 'Failed to delete order');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            showNotification(error.message || 'An error occurred while deleting order', 'error');
            if (button) {
                button.disabled = false;
                button.innerHTML = originalText;
            }
        });
    }
}

function printOrder() {
    window.print();
}
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/pending-orders/show.blade.php ENDPATH**/ ?>