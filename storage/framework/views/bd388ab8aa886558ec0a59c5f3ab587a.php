<?php $__env->startSection('title', 'My Deliveries'); ?>

<?php $__env->startSection('content'); ?>
<div class="top-bar">
    <div class="page-title">
        <h1>My Deliveries</h1>
        <p>Manage all your delivery assignments</p>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('delivery-boy.deliveries')); ?>" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search orders..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="assigned" <?php echo e(request('status') === 'assigned' ? 'selected' : ''); ?>>Assigned</option>
                    <option value="picked_up" <?php echo e(request('status') === 'picked_up' ? 'selected' : ''); ?>>Picked Up</option>
                    <option value="in_transit" <?php echo e(request('status') === 'in_transit' ? 'selected' : ''); ?>>In Transit</option>
                    <option value="delivered" <?php echo e(request('status') === 'delivered' ? 'selected' : ''); ?>>Delivered</option>
                    <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                <a href="<?php echo e(route('delivery-boy.deliveries')); ?>" class="btn btn-outline-secondary"><i class="fas fa-refresh"></i> Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Deliveries Table -->
<div class="table-card">
    <div class="card-header">
        <h5><i class="fas fa-list me-2"></i> All Deliveries (<?php echo e($deliveries->total()); ?>)</h5>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Address</th>
                    <th>COD Amount</th>
                    <th>Status</th>
                    <th>Assigned At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $deliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $order = $delivery->order;
                    $isCod = $order && in_array($order->payment_method, ['cod', 'cash_on_delivery']);
                    $codAmount = $isCod ? ($order->total ?? 0) : 0;
                ?>
                <tr>
                    <td><strong><?php echo e($order->order_number); ?></strong></td>
                    <td>
                        <div><?php echo e($order->receiver_name ?? $order->user->name ?? 'N/A'); ?></div>
                        <small class="text-muted"><?php echo e($order->receiver_phone ?? $order->user->phone ?? 'N/A'); ?></small>
                    </td>
                    <td><?php echo e(Str::limit($order->receiver_full_address ?? $order->shipping_address ?? 'N/A', 40)); ?></td>
                    <td>
                        <?php if($isCod): ?>
                            <strong class="text-danger">₨<?php echo e(number_format($codAmount, 2)); ?></strong>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge <?php echo e($delivery->getStatusBadgeClass()); ?>"><?php echo e(strtoupper(str_replace('_', ' ', $delivery->status))); ?></span></td>
                    <td><?php echo e($delivery->assigned_at->format('M d, Y h:i A')); ?></td>
                    <td>
                        <a href="<?php echo e(route('delivery-boy.delivery-details', $delivery)); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-2 text-muted">No deliveries found</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if($deliveries->hasPages()): ?>
    <div class="card-body">
        <?php echo e($deliveries->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>








<?php echo $__env->make('delivery-boy.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/delivery-boy/deliveries.blade.php ENDPATH**/ ?>