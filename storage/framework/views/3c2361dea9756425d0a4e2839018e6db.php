<?php $__env->startSection('title', 'Edit Pending Order'); ?>
<?php $__env->startSection('page-title', 'Edit Pending Order'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Edit Order Page Specific Styles - Same as create */
    .create-order-header {
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
        background: linear-gradient(135deg, #10B981, #34D399);
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

    .back-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: rgba(107, 114, 128, 0.1);
        color: #6B7280;
        text-decoration: none;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid rgba(107, 114, 128, 0.2);
    }

    .back-btn:hover {
        background: rgba(107, 114, 128, 0.2);
        color: #6B7280;
        transform: translateY(-2px);
    }

    .create-order-form {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        margin-bottom: 2rem;
    }

    .form-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: rgba(16, 185, 129, 0.02);
        border-radius: 0.75rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: #10B981;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control, .form-select {
        border-radius: 0.75rem;
        border: 1px solid rgba(16, 185, 129, 0.2);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        padding: 0.875rem 1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        background: white;
        outline: none;
    }

    .order-summary {
        background: rgba(16, 185, 129, 0.05);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        margin-bottom: 2rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(16, 185, 129, 0.1);
    }

    .summary-row:last-child {
        border-bottom: none;
        font-weight: 700;
        font-size: 1.125rem;
        color: var(--text-primary);
    }

    .summary-label {
        color: var(--text-secondary);
        font-weight: 500;
    }

    .summary-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    .submit-section {
        background: rgba(16, 185, 129, 0.05);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        text-align: center;
    }

    .submit-btn {
        background: linear-gradient(135deg, #10B981, #34D399);
        color: white;
        border: none;
        border-radius: 0.75rem;
        padding: 1rem 2rem;
        font-weight: 600;
        font-size: 1.125rem;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="create-order-header">
    <div class="page-title-section">
        <h1>Edit Pending Order</h1>
        <p class="page-subtitle">Order #<?php echo e($pending_order->order_number); ?></p>
    </div>
    <a href="<?php echo e(route('admin.pending-orders.index')); ?>" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
</div>

<form action="<?php echo e(route('admin.pending-orders.update', $pending_order)); ?>" method="POST" id="editOrderForm">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    
    <div class="create-order-form">
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-user"></i>
                Customer/Receiver Information
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_name" class="form-label">Customer Name *</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" 
                               value="<?php echo e(old('customer_name', $pending_order->receiver_name ?? $pending_order->user->name ?? '')); ?>" required 
                               placeholder="Full name of customer">
                        <?php $__errorArgs = ['customer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_phone" class="form-label">Customer Phone *</label>
                        <input type="tel" class="form-control" id="customer_phone" name="customer_phone" 
                               value="<?php echo e(old('customer_phone', $pending_order->receiver_phone ?? $pending_order->user->phone ?? '')); ?>" required 
                               placeholder="+92XXXXXXXXXX">
                        <?php $__errorArgs = ['customer_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="shipping_address" class="form-label">Complete Shipping Address *</label>
                <textarea class="form-control" id="shipping_address" name="shipping_address" 
                          rows="3" required placeholder="House/Flat number, Street, Landmark, City"><?php echo e(old('shipping_address', $pending_order->receiver_full_address ?? $pending_order->shipping_address ?? '')); ?></textarea>
                <?php $__errorArgs = ['shipping_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
        
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-credit-card"></i>
                Payment & Status Information
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_method" class="form-label">Payment Method *</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="cash_on_delivery" <?php echo e(old('payment_method', $pending_order->payment_method) == 'cash_on_delivery' || old('payment_method', $pending_order->payment_method) == 'cod' ? 'selected' : ''); ?>>Cash on Delivery</option>
                            <option value="paid" <?php echo e(old('payment_method', $pending_order->payment_method) == 'paid' ? 'selected' : ''); ?>>Paid</option>
                            <option value="bank_transfer" <?php echo e(old('payment_method', $pending_order->payment_method) == 'bank_transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
                            <option value="khalti" <?php echo e(old('payment_method', $pending_order->payment_method) == 'khalti' ? 'selected' : ''); ?>>Khalti</option>
                            <option value="esewa" <?php echo e(old('payment_method', $pending_order->payment_method) == 'esewa' ? 'selected' : ''); ?>>eSewa</option>
                        </select>
                        <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status" class="form-label">Order Status *</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" <?php echo e(old('status', $pending_order->status) == 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="processing" <?php echo e(old('status', $pending_order->status) == 'processing' ? 'selected' : ''); ?>>Processing</option>
                            <option value="completed" <?php echo e(old('status', $pending_order->status) == 'completed' ? 'selected' : ''); ?>>Completed</option>
                            <option value="cancelled" <?php echo e(old('status', $pending_order->status) == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="total_amount" class="form-label">Total Amount *</label>
                        <input type="number" class="form-control" id="total_amount" name="total_amount" 
                               value="<?php echo e(old('total_amount', $pending_order->total)); ?>" step="0.01" min="0" required>
                        <?php $__errorArgs = ['total_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Add any special instructions..."><?php echo e(old('notes', $pending_order->notes ?? '')); ?></textarea>
                        <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="order-summary">
        <h4 class="section-title mb-3">
            <i class="fas fa-info-circle"></i>
            Order Information
        </h4>
        
        <div class="summary-row">
            <span class="summary-label">Order Number:</span>
            <span class="summary-value"><?php echo e($pending_order->order_number); ?></span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Created At:</span>
            <span class="summary-value"><?php echo e($pending_order->created_at->format('M d, Y h:i A')); ?></span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Current Total:</span>
            <span class="summary-value">₨<?php echo e(number_format($pending_order->total, 2)); ?></span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Current Status:</span>
            <span class="summary-value">
                <span class="badge badge-<?php echo e($pending_order->status === 'completed' ? 'success' : ($pending_order->status === 'pending' ? 'warning' : ($pending_order->status === 'cancelled' ? 'danger' : 'info'))); ?>">
                    <?php echo e(ucfirst($pending_order->status)); ?>

                </span>
            </span>
        </div>
        <?php if($pending_order->orderItems->count() > 0): ?>
        <div class="summary-row">
            <span class="summary-label">Items:</span>
            <span class="summary-value"><?php echo e($pending_order->orderItems->count()); ?> item(s)</span>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="submit-section">
        <button type="submit" class="submit-btn" id="submitBtn">
            <i class="fas fa-save"></i> Update Order
        </button>
        <p class="mt-3 text-muted">Review all information before updating the order</p>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.getElementById('editOrderForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating Order...';
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/pending-orders/edit.blade.php ENDPATH**/ ?>