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
                        <?php echo e($manualDelivery->order->receiver_name ?? $manualDelivery->order->user->name ?? 'N/A'); ?>

                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Customer Phone:</strong><br>
                        <?php
                            $customerPhone = $manualDelivery->order->receiver_phone ?? $manualDelivery->order->user->phone ?? null;
                        ?>
                        <?php if($customerPhone): ?>
                            <a href="tel:<?php echo e($customerPhone); ?>"><?php echo e($customerPhone); ?></a>
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
                        <?php
                            $order = $manualDelivery->order;
                            // Get subtotal - prefer stored subtotal, fallback to calculated items_total
                            $subtotal = $order->subtotal ?? $order->items_total ?? 0;
                            
                            // Get tax - check both tax_amount (new) and tax (legacy), use whichever has a value
                            $tax = null;
                            if ($order->tax_amount !== null && $order->tax_amount > 0) {
                                $tax = $order->tax_amount;
                            } elseif ($order->tax !== null && $order->tax > 0) {
                                $tax = $order->tax;
                            } else {
                                $tax = $order->tax_amount ?? $order->tax ?? 0;
                            }
                            
                            // Get shipping - check both shipping_cost (new) and shipping (legacy), use whichever has a value
                            $shipping = null;
                            if ($order->shipping_cost !== null && $order->shipping_cost > 0) {
                                $shipping = $order->shipping_cost;
                            } elseif ($order->shipping !== null && $order->shipping > 0) {
                                $shipping = $order->shipping;
                            } else {
                                $shipping = $order->shipping_cost ?? $order->shipping ?? 0;
                            }
                        ?>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                            <td><strong>₨<?php echo e(number_format($subtotal, 2)); ?></strong></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Tax:</td>
                            <td>₨<?php echo e(number_format($tax, 2)); ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Shipping:</td>
                            <td>₨<?php echo e(number_format($shipping, 2)); ?></td>
                        </tr>
                        <tr class="table-active">
                            <td colspan="3" class="text-end fw-bold">Total:</td>
                            <td class="fw-bold">₨<?php echo e(number_format($order->total, 2)); ?></td>
                        </tr>
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
                    <?php if($manualDelivery->status === 'in_transit' || ($manualDelivery->status === 'delivered' && $manualDelivery->picked_up_at)): ?>
                        <div class="timeline-item">
                            <i class="fas fa-truck text-warning"></i>
                            <div>
                                <strong>In Transit</strong><br>
                                <small>
                                    <?php if($manualDelivery->status === 'in_transit'): ?>
                                        <?php echo e($manualDelivery->updated_at->format('M d, Y h:i A')); ?>

                                    <?php elseif($manualDelivery->picked_up_at): ?>
                                        <?php echo e($manualDelivery->picked_up_at->format('M d, Y h:i A')); ?>

                                    <?php endif; ?>
                                </small>
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
                <?php
                    $order = $manualDelivery->order;
                    $isCod = $order && in_array($order->payment_method, ['cod', 'cash_on_delivery']);
                    
                    // Get COD amount - use order total directly
                    $codAmount = 0;
                    if ($isCod) {
                        // Use order total directly as COD amount
                        $codAmount = $order->total ?? 0;
                    }
                    
                    // Get COD collected status from database
                    $codCollected = $manualDelivery->cod_collected ?? false;
                    
                    // Get COD settled status from database
                    $codSettled = $manualDelivery->cod_settled ?? false;
                ?>
                
                <div class="mb-2">
                    <strong>Payment Method:</strong><br>
                    <span class="badge bg-<?php echo e($isCod ? 'warning' : 'info'); ?>">
                        <?php echo e($isCod ? 'COD' : strtoupper(str_replace('_', ' ', $order->payment_method ?? 'N/A'))); ?>

                    </span>
                </div>
                
                <?php if($isCod): ?>
                <div class="mb-2">
                    <strong>COD Amount:</strong><br>
                    <span class="h4 text-danger">₨<?php echo e(number_format($codAmount, 2)); ?></span>
                </div>
                <div class="mb-2">
                    <strong>COD Collected:</strong><br>
                    <span class="badge bg-<?php echo e($codCollected ? 'success' : 'secondary'); ?>">
                        <?php echo e($codCollected ? 'Yes' : 'No'); ?>

                    </span>
                    <?php if($codCollected && $manualDelivery->delivered_at): ?>
                        <br><small class="text-muted">Collected on <?php echo e($manualDelivery->delivered_at->format('M d, Y h:i A')); ?></small>
                    <?php endif; ?>
                </div>
                <div>
                    <strong>Settlement Status:</strong><br>
                    <span class="badge bg-<?php echo e($codSettled ? 'success' : 'warning'); ?>">
                        <?php echo e($codSettled ? 'Settled' : 'Pending'); ?>

                    </span>
                    <?php if($codSettled && $manualDelivery->cod_settled_at): ?>
                        <br><small class="text-muted">Settled on <?php echo e($manualDelivery->cod_settled_at->format('M d, Y h:i A')); ?></small>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if($isCod && isset($codSettlement) && $codSettlement): ?>
                <!-- COD Settlement Details -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i> COD Settlement Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Settlement ID:</strong><br>
                            <span class="fw-semibold"><?php echo e($codSettlement->settlement_id); ?></span>
                        </div>
                        <div class="mb-2">
                            <strong>Settlement Amount:</strong><br>
                            <span class="h5 text-success">₨<?php echo e(number_format($codSettlement->total_amount, 2)); ?></span>
                        </div>
                        <div class="mb-2">
                            <strong>Payment Method:</strong><br>
                            <span class="badge bg-info"><?php echo e(ucfirst(str_replace('_', ' ', $codSettlement->payment_method))); ?></span>
                        </div>
                        <?php if($codSettlement->transaction_reference): ?>
                        <div class="mb-2">
                            <strong>Transaction Reference:</strong><br>
                            <span class="text-muted"><?php echo e($codSettlement->transaction_reference); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="mb-2">
                            <strong>Settled By:</strong><br>
                            <span><?php echo e($codSettlement->settledBy->name ?? 'N/A'); ?></span>
                        </div>
                        <div class="mb-2">
                            <strong>Settlement Date:</strong><br>
                            <span><?php echo e($codSettlement->settled_at->format('M d, Y h:i A')); ?></span>
                        </div>
                        <?php if($codSettlement->notes): ?>
                        <div>
                            <strong>Notes:</strong><br>
                            <span class="text-muted"><?php echo e($codSettlement->notes); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php elseif(!$isCod): ?>
                <div class="mb-2">
                    <strong>Payment Status:</strong><br>
                    <span class="badge bg-<?php echo e($order->payment_status === 'paid' ? 'success' : 'secondary'); ?>">
                        <?php echo e(ucfirst($order->payment_status ?? 'unpaid')); ?>

                    </span>
                </div>
                <div>
                    <strong>Order Total:</strong><br>
                    <span class="h4 text-primary">₨<?php echo e(number_format($order->total, 2)); ?></span>
                </div>
                <?php endif; ?>
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