<?php $__env->startSection('title', 'Subscriptions Management'); ?>
<?php $__env->startSection('page-title', 'Subscriptions Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total</h6>
                    <h3 class="mb-0"><?php echo e($stats['total']); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Active</h6>
                    <h3 class="text-success mb-0"><?php echo e($stats['active']); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="text-muted mb-1">On Trial</h6>
                    <h3 class="text-warning mb-0"><?php echo e($stats['trial']); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-danger">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Expired</h6>
                    <h3 class="text-danger mb-0"><?php echo e($stats['expired']); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-secondary">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Cancelled</h6>
                    <h3 class="text-secondary mb-0"><?php echo e($stats['cancelled']); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-danger">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Past Due</h6>
                    <h3 class="text-danger mb-0"><?php echo e($stats['past_due']); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <a href="<?php echo e(route('super.subscriptions.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create New Subscription
                    </a>
                </div>
                <div class="d-flex gap-2">
                    <form id="bulkActionForm" method="POST" action="<?php echo e(route('super.subscriptions.bulk-action')); ?>" class="d-flex gap-2">
                        <?php echo csrf_field(); ?>
                        <select name="action" id="bulkAction" class="form-select form-select-sm" style="width: auto;" required>
                            <option value="">Bulk Actions</option>
                            <option value="activate">Activate</option>
                            <option value="cancel">Cancel</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-secondary" id="bulkActionBtn" disabled>
                            <i class="fas fa-check-double"></i> Apply
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search tenant, subdomain, or subscription ID..." value="<?php echo e(request('search')); ?>">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="trial" <?php echo e(request('status') == 'trial' ? 'selected' : ''); ?>>Trial</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="past_due" <?php echo e(request('status') == 'past_due' ? 'selected' : ''); ?>>Past Due</option>
                        <option value="expired" <?php echo e(request('status') == 'expired' ? 'selected' : ''); ?>>Expired</option>
                        <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="expiry_status" class="form-select">
                        <option value="">All Expiry Status</option>
                        <option value="active" <?php echo e(request('expiry_status') == 'active' ? 'selected' : ''); ?>>Active (Not Expired)</option>
                        <option value="expiring_soon" <?php echo e(request('expiry_status') == 'expiring_soon' ? 'selected' : ''); ?>>Expiring Soon (7 days)</option>
                        <option value="expired" <?php echo e(request('expiry_status') == 'expired' ? 'selected' : ''); ?>>Expired</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="plan" class="form-select">
                        <option value="">All Plans</option>
                        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($plan->id); ?>" <?php echo e(request('plan') == $plan->id ? 'selected' : ''); ?>><?php echo e($plan->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="<?php echo e(route('super.subscriptions.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Subscriptions Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Subscriptions List</h5>
            <span class="text-muted"><?php echo e($subscriptions->total()); ?> total</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAll" title="Select All">
                            </th>
                            <th>Subscription ID</th>
                            <th>Tenant</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Billing Cycle</th>
                            <th>Started</th>
                            <th>Ends</th>
                            <th>Days Left</th>
                            <th>Amount</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="subscription_ids[]" value="<?php echo e($subscription->id); ?>" class="subscription-checkbox">
                            </td>
                            <td>
                                <strong><?php echo e($subscription->subscription_id); ?></strong>
                            </td>
                            <td>
                                <strong><?php echo e($subscription->tenant->business_name); ?></strong><br>
                                <small class="text-muted">
                                    <i class="fas fa-globe"></i> <?php echo e($subscription->tenant->subdomain); ?>.emanager.com
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-info"><?php echo e($subscription->plan->name); ?></span>
                            </td>
                            <td>
                                <?php if($subscription->status == 'active'): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php elseif($subscription->status == 'trial'): ?>
                                    <span class="badge bg-warning">Trial</span>
                                <?php elseif($subscription->status == 'past_due'): ?>
                                    <span class="badge bg-danger">Past Due</span>
                                <?php elseif($subscription->status == 'expired'): ?>
                                    <span class="badge bg-danger">Expired</span>
                                <?php elseif($subscription->status == 'cancelled'): ?>
                                    <span class="badge bg-secondary">Cancelled</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e(ucfirst($subscription->billing_cycle)); ?></td>
                            <td>
                                <?php echo e($subscription->starts_at ? $subscription->starts_at->format('M d, Y') : 'N/A'); ?>

                            </td>
                            <td>
                                <?php if($subscription->ends_at): ?>
                                    <?php if($subscription->ends_at->isPast()): ?>
                                        <span class="text-danger">
                                            <?php echo e($subscription->ends_at->format('M d, Y')); ?>

                                            <i class="fas fa-exclamation-triangle" title="Expired"></i>
                                        </span>
                                    <?php elseif($subscription->ends_at->diffInDays(now()) <= 7): ?>
                                        <span class="text-warning">
                                            <?php echo e($subscription->ends_at->format('M d, Y')); ?>

                                            <i class="fas fa-clock" title="Expiring Soon"></i>
                                        </span>
                                    <?php else: ?>
                                        <?php echo e($subscription->ends_at->format('M d, Y')); ?>

                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($subscription->ends_at): ?>
                                    <?php
                                        $daysLeft = $subscription->ends_at->diffInDays(now(), false);
                                    ?>
                                    <?php if($daysLeft < 0): ?>
                                        <span class="text-danger">Expired</span>
                                    <?php elseif($daysLeft <= 7): ?>
                                        <span class="text-warning"><?php echo e($daysLeft); ?> days</span>
                                    <?php else: ?>
                                        <span class="text-success"><?php echo e($daysLeft); ?> days</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>Rs. <?php echo e(number_format($subscription->amount, 0)); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?php echo e(route('super.subscriptions.show', $subscription)); ?>" class="btn btn-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('super.subscriptions.edit', $subscription)); ?>" class="btn btn-info" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" title="More Actions">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <?php if($subscription->status != 'active'): ?>
                                            <li>
                                                <form action="<?php echo e(route('super.subscriptions.activate', $subscription)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="fas fa-check"></i> Activate
                                                    </button>
                                                </form>
                                            </li>
                                            <?php endif; ?>
                                            <?php if($subscription->status == 'active' || $subscription->status == 'trial'): ?>
                                            <li>
                                                <button type="button" class="dropdown-item text-warning" onclick="extendSubscription(<?php echo e($subscription->id); ?>)">
                                                    <i class="fas fa-calendar-plus"></i> Extend
                                                </button>
                                            </li>
                                            <li>
                                                <form action="<?php echo e(route('super.subscriptions.cancel', $subscription)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this subscription?')">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-times"></i> Cancel
                                                    </button>
                                                </form>
                                            </li>
                                            <?php endif; ?>
                                            <?php if($subscription->status == 'cancelled' || $subscription->status == 'expired'): ?>
                                            <li>
                                                <form action="<?php echo e(route('super.subscriptions.renew', $subscription)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="fas fa-redo"></i> Renew
                                                    </button>
                                                </form>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No subscriptions found</p>
                                <a href="<?php echo e(route('super.subscriptions.create')); ?>" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Create First Subscription
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($subscriptions->hasPages()): ?>
            <div class="mt-4">
                <?php echo e($subscriptions->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Extend Subscription Modal -->
<div class="modal fade" id="extendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="extendForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Extend Subscription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="months" class="form-label">Extend by (months)</label>
                        <input type="number" class="form-control" id="months" name="months" min="1" max="12" value="1" required>
                        <small class="text-muted">Enter number of months to extend (1-12)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Extend Subscription</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Select All functionality
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.subscription-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkActionButton();
    });

    // Update bulk action button state
    document.querySelectorAll('.subscription-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionButton);
    });

    function updateBulkActionButton() {
        const checked = document.querySelectorAll('.subscription-checkbox:checked');
        const btn = document.getElementById('bulkActionBtn');
        const select = document.getElementById('bulkAction');
        
        if (checked.length > 0 && select.value) {
            btn.disabled = false;
            // Add selected IDs to form
            const form = document.getElementById('bulkActionForm');
            form.innerHTML = form.innerHTML.replace(/<input type="hidden" name="subscription_ids\[\]"[^>]*>/g, '');
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'subscription_ids[]';
                input.value = cb.value;
                form.appendChild(input);
            });
        } else {
            btn.disabled = true;
        }
    }

    document.getElementById('bulkAction')?.addEventListener('change', updateBulkActionButton);

    // Bulk action form submission
    document.getElementById('bulkActionForm')?.addEventListener('submit', function(e) {
        const checked = document.querySelectorAll('.subscription-checkbox:checked');
        const action = document.getElementById('bulkAction').value;
        
        if (checked.length === 0) {
            e.preventDefault();
            alert('Please select at least one subscription.');
            return false;
        }
        
        if (!action) {
            e.preventDefault();
            alert('Please select an action.');
            return false;
        }
        
        const actionText = action === 'delete' ? 'delete' : action + 'e';
        if (!confirm(`Are you sure you want to ${actionText} ${checked.length} subscription(s)?`)) {
            e.preventDefault();
            return false;
        }
    });

    // Extend subscription
    function extendSubscription(subscriptionId) {
        const form = document.getElementById('extendForm');
        form.action = `/super/subscriptions/${subscriptionId}/extend`;
        const modal = new bootstrap.Modal(document.getElementById('extendModal'));
        modal.show();
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('super-admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/super-admin/subscriptions/index.blade.php ENDPATH**/ ?>