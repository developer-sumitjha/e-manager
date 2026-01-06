<?php $__env->startSection('title', 'Activities'); ?>

<?php $__env->startSection('content'); ?>
<div class="top-bar">
    <div class="page-title">
        <h1>Activity Log</h1>
        <p>Track all your account activities</p>
    </div>
</div>

<div class="table-card">
    <div class="card-header">
        <h5><i class="fas fa-history me-2"></i> Recent Activities (<?php echo e($activities->total()); ?>)</h5>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Description</th>
                    <th>Order</th>
                    <th>Date & Time</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <span class="badge bg-primary"><?php echo e(strtoupper(str_replace('_', ' ', $activity->activity_type))); ?></span>
                    </td>
                    <td><?php echo e($activity->description); ?></td>
                    <td>
                        <?php if($activity->manualDelivery): ?>
                            <a href="<?php echo e(route('delivery-boy.delivery-details', $activity->manualDelivery)); ?>">
                                <?php echo e($activity->manualDelivery->order->order_number); ?>

                            </a>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($activity->created_at->format('M d, Y h:i A')); ?></td>
                    <td><small class="text-muted"><?php echo e($activity->ip_address); ?></small></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <i class="fas fa-history text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-2 text-muted">No activities recorded yet</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if($activities->hasPages()): ?>
    <div class="card-body">
        <?php echo e($activities->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>








<?php echo $__env->make('delivery-boy.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/delivery-boy/activities.blade.php ENDPATH**/ ?>