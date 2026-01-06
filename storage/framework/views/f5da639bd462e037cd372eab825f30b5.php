<?php $__env->startSection('title', 'Delivery Details'); ?>
<?php $__env->startSection('page-title', 'Delivery Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8 mb-4">
        <!-- Order Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-box me-2"></i> Order Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Order Number:</strong><br>
                        <a href="<?php echo e(route('admin.orders.show', $manualDelivery->order)); ?>"><?php echo e($manualDelivery->order->order_number); ?></a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Order Date:</strong><br>
                        <?php echo e($manualDelivery->order->created_at->format('M d, Y h:i A')); ?>

                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Customer Name:</strong><br>
                        <?php echo e($manualDelivery->order->user->name ?? 'N/A'); ?>

                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Customer Phone:</strong><br>
                        <?php if($manualDelivery->order->user): ?>
                            <a href="tel:<?php echo e($manualDelivery->order->user->phone); ?>"><?php echo e($manualDelivery->order->user->phone); ?></a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Shipping Address:</strong><br>
                        <?php echo e($manualDelivery->order->receiver_full_address ?? $manualDelivery->order->shipping_address ?? 'Not provided'); ?>

                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i> Order Items</h5>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $manualDelivery->order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($item->product_name ?? ($item->product->name ?? 'N/A')); ?></td>
                            <td><?php echo e($item->quantity); ?></td>
                            <td>₨<?php echo e(number_format($item->price, 2)); ?></td>
                            <td>₨<?php echo e(number_format($item->quantity * $item->price, 2)); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Items Total:</strong></td>
                            <td><strong>₨<?php echo e(number_format($manualDelivery->order->items_total, 2)); ?></strong></td>
                        </tr>
                        <?php if($manualDelivery->order->shipping_cost > 0 || $manualDelivery->order->tax_amount > 0): ?>
                        <tr>
                            <td colspan="3" class="text-end">
                                <?php if($manualDelivery->order->shipping_cost > 0): ?>
                                    <small>Shipping: ₨<?php echo e(number_format($manualDelivery->order->shipping_cost ?? $manualDelivery->order->shipping ?? 0, 2)); ?></small><br>
                                <?php endif; ?>
                                <?php if($manualDelivery->order->tax_amount > 0): ?>
                                    <small>Tax: ₨<?php echo e(number_format($manualDelivery->order->tax_amount ?? $manualDelivery->order->tax ?? 0, 2)); ?></small>
                                <?php endif; ?>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Order Total:</strong></td>
                            <td><strong>₨<?php echo e(number_format($manualDelivery->order->total, 2)); ?></strong></td>
                        </tr>
                        <?php endif; ?>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Delivery Timeline -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i> Delivery Timeline</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php if($manualDelivery->assigned_at): ?>
                        <div class="timeline-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <div>
                                <strong>Assigned</strong><br>
                                <small><?php echo e($manualDelivery->assigned_at->format('M d, Y h:i A')); ?></small>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($manualDelivery->picked_up_at): ?>
                        <div class="timeline-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <div>
                                <strong>Picked Up</strong><br>
                                <small><?php echo e($manualDelivery->picked_up_at->format('M d, Y h:i A')); ?></small>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($manualDelivery->delivered_at): ?>
                        <div class="timeline-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <div>
                                <strong>Delivered</strong><br>
                                <small><?php echo e($manualDelivery->delivered_at->format('M d, Y h:i A')); ?></small>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($manualDelivery->cancelled_at): ?>
                        <div class="timeline-item">
                            <i class="fas fa-times-circle text-danger"></i>
                            <div>
                                <strong>Cancelled</strong><br>
                                <small><?php echo e($manualDelivery->cancelled_at->format('M d, Y h:i A')); ?></small>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Delivery Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-truck me-2"></i> Delivery Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Status:</strong><br>
                    <span class="badge <?php echo e($manualDelivery->getStatusBadgeClass()); ?> px-3 py-2">
                        <?php echo e(strtoupper(str_replace('_', ' ', $manualDelivery->status))); ?>

                    </span>
                </div>
                <div class="mb-3">
                    <strong>Delivery Boy:</strong><br>
                    <?php echo e($manualDelivery->deliveryBoy->name); ?><br>
                    <small class="text-muted"><?php echo e($manualDelivery->deliveryBoy->phone); ?></small>
                </div>
                <?php if($manualDelivery->delivery_notes): ?>
                <div class="mb-3">
                    <strong>Delivery Notes:</strong><br>
                    <?php echo e($manualDelivery->delivery_notes); ?>

                </div>
                <?php endif; ?>
                <?php if($manualDelivery->cancellation_reason): ?>
                <div class="mb-3">
                    <strong>Cancellation Reason:</strong><br>
                    <?php echo e($manualDelivery->cancellation_reason); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-money-bill me-2"></i> Payment Info</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>COD Amount:</strong><br>
                    <?php
                        $codAmount = $manualDelivery->cod_amount ?? 0;
                        if (($codAmount == 0 || $codAmount == null) && $manualDelivery->order && $manualDelivery->order->payment_method === 'cod') {
                            $codAmount = $manualDelivery->order->orderItems->sum(function($item) {
                                return ($item->quantity ?? 0) * ($item->price ?? 0);
                            });
                        }
                    ?>
                    <span class="h4 text-danger">₨<?php echo e(number_format($codAmount, 2)); ?></span>
                </div>
                <div class="mb-2">
                    <strong>COD Collected:</strong><br>
                    <span class="badge bg-<?php echo e($manualDelivery->cod_collected ? 'success' : 'secondary'); ?>">
                        <?php echo e($manualDelivery->cod_collected ? 'Yes' : 'No'); ?>

                    </span>
                </div>
                <div>
                    <strong>Settlement Status:</strong><br>
                    <span class="badge bg-<?php echo e($manualDelivery->cod_settled ? 'success' : 'warning'); ?>">
                        <?php echo e($manualDelivery->cod_settled ? 'Settled' : 'Pending'); ?>

                    </span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card">
            <div class="card-body">
                <a href="<?php echo e(route('admin.manual-delivery.edit', $manualDelivery)); ?>" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-edit me-2"></i> Edit Delivery
                </a>
                <a href="<?php echo e(route('admin.manual-delivery.deliveries')); ?>" class="btn btn-secondary w-100">
                    <i class="fas fa-arrow-left me-2"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
    display: flex;
    gap: 15px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -22px;
    top: 25px;
    width: 2px;
    height: calc(100% - 15px);
    background: #E5E7EB;
}

.timeline-item i {
    position: absolute;
    left: -30px;
    top: 0;
    font-size: 1.2rem;
}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/manual-delivery/show.blade.php ENDPATH**/ ?>