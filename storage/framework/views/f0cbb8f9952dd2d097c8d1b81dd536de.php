<?php $__env->startSection('title', 'Edit Order'); ?>
<?php $__env->startSection('page-title', 'Edit Order'); ?>
<?php $__env->startSection('page-subtitle', 'Update order information and status'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <div class="breadcrumb-item">
        <a href="<?php echo e(route('admin.orders.index')); ?>" class="breadcrumb-link">Orders</a>
    </div>
    <div class="breadcrumb-item">
        <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="breadcrumb-link">Order #<?php echo e($order->order_number); ?></a>
    </div>
    <div class="breadcrumb-item active">Edit</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Edit Order #<?php echo e($order->order_number); ?></h5>
                        <small class="text-muted">Last updated <?php echo e($order->updated_at->format('M j, Y \a\t g:i A')); ?></small>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge badge-<?php echo e($order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'info'))); ?>">
                            <?php echo e(ucfirst($order->status)); ?>

                        </span>
                        <span class="badge badge-<?php echo e($order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'refunded' ? 'warning' : 'danger')); ?>">
                            <?php echo e(ucfirst($order->payment_status)); ?>

                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('admin.orders.update', $order)); ?>" method="POST" id="order-edit-form">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="row g-3">
                        <!-- Order Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label">
                                <i class="fas fa-flag me-1"></i> Order Status *
                            </label>
                            <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="status" 
                                    name="status" 
                                    required>
                                <option value="pending" <?php echo e(old('status', $order->status) == 'pending' ? 'selected' : ''); ?>>
                                    Pending
                                </option>
                                <option value="confirmed" <?php echo e(old('status', $order->status) == 'confirmed' ? 'selected' : ''); ?>>
                                    Confirmed
                                </option>
                                <option value="processing" <?php echo e(old('status', $order->status) == 'processing' ? 'selected' : ''); ?>>
                                    Processing
                                </option>
                                <option value="shipped" <?php echo e(old('status', $order->status) == 'shipped' ? 'selected' : ''); ?>>
                                    Shipped
                                </option>
                                <option value="completed" <?php echo e(old('status', $order->status) == 'completed' ? 'selected' : ''); ?>>
                                    Completed
                                </option>
                                <option value="cancelled" <?php echo e(old('status', $order->status) == 'cancelled' ? 'selected' : ''); ?>>
                                    Cancelled
                                </option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                        <!-- Payment Status -->
                        <div class="col-md-6">
                            <label for="payment_status" class="form-label">
                                <i class="fas fa-credit-card me-1"></i> Payment Status *
                            </label>
                            <select class="form-select <?php $__errorArgs = ['payment_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="payment_status" 
                                    name="payment_status" 
                                    required>
                                <option value="unpaid" <?php echo e(old('payment_status', $order->payment_status) == 'unpaid' ? 'selected' : ''); ?>>
                                    Unpaid
                                </option>
                                <option value="paid" <?php echo e(old('payment_status', $order->payment_status) == 'paid' ? 'selected' : ''); ?>>
                                    Paid
                                </option>
                                <option value="refunded" <?php echo e(old('payment_status', $order->payment_status) == 'refunded' ? 'selected' : ''); ?>>
                                    Refunded
                                </option>
                        </select>
                        <?php $__errorArgs = ['payment_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                        <!-- Payment Method -->
                        <div class="col-md-6">
                            <label for="payment_method" class="form-label">
                                <i class="fas fa-money-bill-wave me-1"></i> Payment Method
                            </label>
                            <select class="form-select <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="payment_method" 
                                    name="payment_method">
                                <option value="cod" <?php echo e(old('payment_method', $order->payment_method) == 'cod' ? 'selected' : ''); ?>>
                                    Cash on Delivery
                                </option>
                                <option value="online" <?php echo e(old('payment_method', $order->payment_method) == 'online' ? 'selected' : ''); ?>>
                                    Online Payment
                                </option>
                                <option value="bank_transfer" <?php echo e(old('payment_method', $order->payment_method) == 'bank_transfer' ? 'selected' : ''); ?>>
                                    Bank Transfer
                                </option>
                                <option value="khalti" <?php echo e(old('payment_method', $order->payment_method) == 'khalti' ? 'selected' : ''); ?>>
                                    Khalti
                                </option>
                                <option value="esewa" <?php echo e(old('payment_method', $order->payment_method) == 'esewa' ? 'selected' : ''); ?>>
                                    eSewa
                                </option>
                            </select>
                            <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Delivery Type -->
                        <div class="col-md-6">
                            <label for="delivery_type" class="form-label">
                                <i class="fas fa-truck me-1"></i> Delivery Type
                            </label>
                            <select class="form-select <?php $__errorArgs = ['delivery_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="delivery_type" 
                                    name="delivery_type">
                                <option value="standard" <?php echo e(old('delivery_type', $order->delivery_type) == 'standard' ? 'selected' : ''); ?>>
                                    Standard Delivery
                                </option>
                                <option value="express" <?php echo e(old('delivery_type', $order->delivery_type) == 'express' ? 'selected' : ''); ?>>
                                    Express Delivery
                                </option>
                            </select>
                            <?php $__errorArgs = ['delivery_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Order Notes -->
                        <div class="col-12">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i> Order Notes
                            </label>
                            <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="notes" 
                                      name="notes" 
                                      rows="4" 
                                      placeholder="Add any additional notes about this order..."><?php echo e(old('notes', $order->notes)); ?></textarea>
                        <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                        <!-- Delivery Instructions -->
                        <div class="col-12">
                            <label for="delivery_instructions" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i> Delivery Instructions
                            </label>
                            <textarea class="form-control <?php $__errorArgs = ['delivery_instructions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="delivery_instructions" 
                                      name="delivery_instructions" 
                                      rows="3" 
                                      placeholder="Special delivery instructions..."><?php echo e(old('delivery_instructions', $order->delivery_instructions)); ?></textarea>
                            <?php $__errorArgs = ['delivery_instructions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary" id="save-btn">
                            <i class="fas fa-save me-1"></i> Update Order
                        </button>
                        <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-eye me-1"></i> View Order
                        </a>
                        <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Orders
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Order Summary Sidebar -->
    <div class="col-xl-4">
        <!-- Order Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Order Summary</h6>
            </div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <small class="text-muted">Order ID</small>
                        <div class="fw-semibold"><?php echo e($order->order_number); ?></div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Total Amount</small>
                        <div class="fw-semibold">Rs. <?php echo e(number_format($order->total, 2)); ?></div>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <small class="text-muted">Items</small>
                        <div class="fw-semibold"><?php echo e($order->orderItems->count()); ?></div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Created</small>
                        <div class="fw-semibold"><?php echo e($order->created_at->format('M j, Y')); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Customer</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-sm me-3">
                        <div class="avatar-initials">
                            <?php echo e(substr($order->user->name ?? 'G', 0, 1)); ?>

                        </div>
                    </div>
                    <div>
                        <div class="fw-semibold"><?php echo e($order->user->name ?? 'Guest'); ?></div>
                        <small class="text-muted"><?php echo e($order->user->email ?? 'N/A'); ?></small>
                    </div>
                </div>
                <div class="text-muted">
                    <small>Phone: <?php echo e($order->user->phone ?? 'N/A'); ?></small>
                </div>
            </div>
        </div>

        <!-- Status History -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Status History</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <div class="fw-semibold">Order Created</div>
                            <small class="text-muted"><?php echo e($order->created_at->format('M j, Y H:i')); ?></small>
                        </div>
                    </div>
                    <?php if($order->status !== 'pending'): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <div class="fw-semibold">Status: <?php echo e(ucfirst($order->status)); ?></div>
                            <small class="text-muted"><?php echo e($order->updated_at->format('M j, Y H:i')); ?></small>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
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
    font-size: 0.875rem;
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: rgba(99, 102, 241, 0.2);
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0.25rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid var(--bg-surface);
}

.timeline-content {
    padding-left: 0.5rem;
}

/* Form Enhancements */
.form-label {
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 0.5rem;
}

.form-label i {
    color: var(--primary);
}

/* Loading State */
.btn.loading {
    position: relative;
    color: transparent;
}

.btn.loading::after {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    top: 50%;
    left: 50%;
    margin-left: -8px;
    margin-top: -8px;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
    .timeline {
        padding-left: 1.5rem;
    }
    
    .timeline::before {
        left: 0.5rem;
    }
    
    .timeline-marker {
        left: -1.5rem;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('order-edit-form');
    const saveBtn = document.getElementById('save-btn');
    
    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        saveBtn.classList.add('loading');
        saveBtn.disabled = true;
    });
    
    // Status change confirmation
    const statusSelect = document.getElementById('status');
    const originalStatus = statusSelect.value;
    
    statusSelect.addEventListener('change', function() {
        if (this.value !== originalStatus) {
            const statusLabels = {
                'pending': 'Pending',
                'confirmed': 'Confirmed',
                'processing': 'Processing',
                'shipped': 'Shipped',
                'completed': 'Completed',
                'cancelled': 'Cancelled'
            };
            
            if (confirm(`Are you sure you want to change the order status to "${statusLabels[this.value]}"?`)) {
                // Status change confirmed
            } else {
                this.value = originalStatus;
            }
        }
    });
    
    // Payment status change confirmation
    const paymentStatusSelect = document.getElementById('payment_status');
    const originalPaymentStatus = paymentStatusSelect.value;
    
    paymentStatusSelect.addEventListener('change', function() {
        if (this.value !== originalPaymentStatus) {
            const paymentLabels = {
                'unpaid': 'Unpaid',
                'paid': 'Paid',
                'refunded': 'Refunded'
            };
            
            if (confirm(`Are you sure you want to change the payment status to "${paymentLabels[this.value]}"?`)) {
                // Payment status change confirmed
            } else {
                this.value = originalPaymentStatus;
            }
        }
    });
    
    // Auto-save draft functionality
    let autoSaveTimeout;
    const formInputs = form.querySelectorAll('input, select, textarea');
    
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                // Auto-save implementation could go here
                console.log('Auto-saving draft...');
            }, 2000);
        });
    });
});
</script>

<script src="<?php echo e(asset('js/orders.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/orders/edit.blade.php ENDPATH**/ ?>