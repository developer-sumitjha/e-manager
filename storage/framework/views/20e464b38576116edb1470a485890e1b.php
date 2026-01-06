<?php $__env->startSection('title', 'Edit Delivery'); ?>
<?php $__env->startSection('page-title', 'Edit Delivery'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Edit Delivery</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.manual-delivery.update', $manualDelivery)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="mb-3">
                        <label for="delivery_boy_id" class="form-label">Delivery Boy</label>
                        <select class="form-select" id="delivery_boy_id" name="delivery_boy_id">
                            <option value="">-- Select Delivery Boy --</option>
                            <?php $__currentLoopData = $deliveryBoys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $boy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($boy->id); ?>" <?php echo e($manualDelivery->delivery_boy_id == $boy->id ? 'selected' : ''); ?>>
                                    <?php echo e($boy->name); ?> (<?php echo e($boy->phone); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['delivery_boy_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="assigned" <?php echo e($manualDelivery->status == 'assigned' ? 'selected' : ''); ?>>Assigned</option>
                            <option value="picked_up" <?php echo e($manualDelivery->status == 'picked_up' ? 'selected' : ''); ?>>Picked Up</option>
                            <option value="in_transit" <?php echo e($manualDelivery->status == 'in_transit' ? 'selected' : ''); ?>>In Transit</option>
                            <option value="delivered" <?php echo e($manualDelivery->status == 'delivered' ? 'selected' : ''); ?>>Delivered</option>
                            <option value="cancelled" <?php echo e($manualDelivery->status == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                            <option value="failed" <?php echo e($manualDelivery->status == 'failed' ? 'selected' : ''); ?>>Failed</option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3" id="cancellation_reason_field" style="display: <?php echo e($manualDelivery->status == 'cancelled' ? 'block' : 'none'); ?>;">
                        <label for="cancellation_reason" class="form-label">Cancellation Reason</label>
                        <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3"><?php echo e($manualDelivery->cancellation_reason); ?></textarea>
                        <?php $__errorArgs = ['cancellation_reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="delivery_notes" class="form-label">Delivery Notes</label>
                        <textarea class="form-control" id="delivery_notes" name="delivery_notes" rows="3"><?php echo e($manualDelivery->delivery_notes); ?></textarea>
                        <?php $__errorArgs = ['delivery_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update Delivery
                        </button>
                        <a href="<?php echo e(route('admin.manual-delivery.show', $manualDelivery)); ?>" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Order Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-box me-2"></i> Order Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Order Number:</strong><br>
                <a href="<?php echo e(route('admin.orders.show', $manualDelivery->order)); ?>"><?php echo e($manualDelivery->order->order_number); ?></a></p>
                <p><strong>Customer:</strong><br>
                <?php echo e($manualDelivery->order->user->name ?? 'N/A'); ?></p>
                <p><strong>Total:</strong><br>
                ₨<?php echo e(number_format($manualDelivery->order->total, 2)); ?></p>
            </div>
        </div>

        <!-- Current Delivery Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Current Info</h5>
            </div>
            <div class="card-body">
                <p><strong>Current Status:</strong><br>
                <span class="badge <?php echo e($manualDelivery->getStatusBadgeClass()); ?>">
                    <?php echo e(strtoupper(str_replace('_', ' ', $manualDelivery->status))); ?>

                </span></p>
                <p><strong>Delivery Boy:</strong><br>
                <?php echo e($manualDelivery->deliveryBoy->name); ?></p>
                <p><strong>Assigned At:</strong><br>
                <?php echo e($manualDelivery->assigned_at->format('M d, Y h:i A')); ?></p>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const cancellationField = document.getElementById('cancellation_reason_field');
    
    statusSelect.addEventListener('change', function() {
        if (this.value === 'cancelled') {
            cancellationField.style.display = 'block';
            document.getElementById('cancellation_reason').setAttribute('required', 'required');
        } else {
            cancellationField.style.display = 'none';
            document.getElementById('cancellation_reason').removeAttribute('required');
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/manual-delivery/edit.blade.php ENDPATH**/ ?>