<?php $__env->startSection('title', 'Tenant Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><?php echo e($tenant->business_name); ?></h1>
            <p class="text-muted">Tenant ID: <?php echo e($tenant->tenant_id); ?></p>
        </div>
        <div>
            <a href="<?php echo e(route('super.tenants.edit', $tenant)); ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="<?php echo e(route('super.tenants.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Business Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Business Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Business Name</label>
                            <p class="fw-bold"><?php echo e($tenant->business_name); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Business Email</label>
                            <p class="fw-bold"><?php echo e($tenant->business_email); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Business Phone</label>
                            <p class="fw-bold"><?php echo e($tenant->business_phone ?? 'N/A'); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Business Type</label>
                            <p class="fw-bold"><?php echo e(ucfirst($tenant->business_type ?? 'N/A')); ?></p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted">Subdomain</label>
                            <p class="fw-bold">
                                <a href="http://<?php echo e($tenant->subdomain); ?>.emanager.com" target="_blank">
                                    <?php echo e($tenant->subdomain); ?>.emanager.com
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Owner Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Owner Name</label>
                            <p class="fw-bold"><?php echo e($tenant->owner_name); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Owner Email</label>
                            <p class="fw-bold"><?php echo e($tenant->owner_email); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Owner Phone</label>
                            <p class="fw-bold"><?php echo e($tenant->owner_phone); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription History -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Subscription History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Subscription ID</th>
                                    <th>Plan</th>
                                    <th>Period</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $tenant->subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($subscription->subscription_id); ?></td>
                                    <td><?php echo e($subscription->plan->name); ?></td>
                                    <td>
                                        <?php echo e($subscription->starts_at->format('M d, Y')); ?> - 
                                        <?php echo e($subscription->ends_at->format('M d, Y')); ?>

                                    </td>
                                    <td>Rs. <?php echo e(number_format($subscription->amount, 2)); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($subscription->status == 'active' ? 'success' : ($subscription->status == 'trial' ? 'warning' : 'secondary')); ?>">
                                            <?php echo e(ucfirst($subscription->status)); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No subscriptions yet</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Payment History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Payment ID</th>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $tenant->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($payment->payment_id); ?></td>
                                    <td><?php echo e($payment->paid_at?->format('M d, Y') ?? 'N/A'); ?></td>
                                    <td><?php echo e(ucfirst($payment->payment_method)); ?></td>
                                    <td>Rs. <?php echo e(number_format($payment->amount, 2)); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($payment->status == 'completed' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger')); ?>">
                                            <?php echo e(ucfirst($payment->status)); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No payments yet</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Activity Log -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Activity Log</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php $__empty_1 = true; $__currentLoopData = $tenant->activities()->latest()->take(20)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="timeline-item mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-circle text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-0"><strong><?php echo e($activity->action); ?></strong></p>
                                    <p class="text-muted mb-0"><?php echo e($activity->description); ?></p>
                                    <small class="text-muted"><?php echo e($activity->created_at->diffForHumans()); ?></small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted text-center">No activity logged yet</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted">Current Status</label>
                        <p>
                            <?php if($tenant->status == 'active'): ?>
                                <span class="badge bg-success">Active</span>
                            <?php elseif($tenant->status == 'trial'): ?>
                                <span class="badge bg-warning">Trial</span>
                            <?php elseif($tenant->status == 'suspended'): ?>
                                <span class="badge bg-danger">Suspended</span>
                            <?php elseif($tenant->status == 'pending'): ?>
                                <span class="badge bg-secondary">Pending</span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Current Plan</label>
                        <p class="fw-bold"><?php echo e($tenant->currentPlan->name ?? 'N/A'); ?></p>
                    </div>

                    <?php if($tenant->isOnTrial()): ?>
                    <div class="mb-3">
                        <label class="text-muted">Trial Ends</label>
                        <p class="fw-bold"><?php echo e($tenant->trial_ends_at->format('M d, Y')); ?></p>
                        <small class="text-muted"><?php echo e($tenant->getDaysUntilTrialEnd()); ?> days remaining</small>
                    </div>
                    <?php endif; ?>

                    <?php if($tenant->subscriptionActive()): ?>
                    <div class="mb-3">
                        <label class="text-muted">Subscription Ends</label>
                        <p class="fw-bold"><?php echo e($tenant->subscription_ends_at->format('M d, Y')); ?></p>
                    </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="text-muted">Registered</label>
                        <p><?php echo e($tenant->created_at->format('M d, Y')); ?></p>
                        <small class="text-muted"><?php echo e($tenant->created_at->diffForHumans()); ?></small>
                    </div>

                    <hr>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <?php if($tenant->status == 'pending'): ?>
                            <form action="<?php echo e(route('super.tenants.approve', $tenant)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check"></i> Approve Tenant
                                </button>
                            </form>
                        <?php elseif($tenant->status == 'active' || $tenant->status == 'trial'): ?>
                            <form action="<?php echo e(route('super.tenants.suspend', $tenant)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to suspend this tenant?')">
                                    <i class="fas fa-ban"></i> Suspend Tenant
                                </button>
                            </form>
                        <?php elseif($tenant->status == 'suspended'): ?>
                            <form action="<?php echo e(route('super.tenants.activate', $tenant)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check-circle"></i> Activate Tenant
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Usage Stats -->
            <?php if(isset($usageStats)): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Usage Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Orders</span>
                            <span><?php echo e($usageStats['orders']['used']); ?> / <?php echo e($usageStats['orders']['limit']); ?></span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: <?php echo e($usageStats['orders']['percentage']); ?>%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Products</span>
                            <span><?php echo e($usageStats['products']['used']); ?> / <?php echo e($usageStats['products']['limit']); ?></span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: <?php echo e($usageStats['products']['percentage']); ?>%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Users</span>
                            <span><?php echo e($usageStats['users']['used']); ?> / <?php echo e($usageStats['users']['limit']); ?></span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: <?php echo e($usageStats['users']['percentage']); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>







<?php echo $__env->make('super-admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/super-admin/tenants/show.blade.php ENDPATH**/ ?>