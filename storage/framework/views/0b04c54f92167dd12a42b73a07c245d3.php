<?php $__env->startSection('title', 'Stock Adjustments'); ?>
<?php $__env->startSection('page-title', 'Stock Adjustments'); ?>

<?php $__env->startSection('content'); ?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded">
                            <i class="fas fa-list fa-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Adjustments</h6>
                        <h3 class="mb-0"><?php echo e($stats['total_adjustments']); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 text-success p-3 rounded">
                            <i class="fas fa-arrow-up fa-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Increases</h6>
                        <h3 class="mb-0 text-success">+<?php echo e(number_format($stats['total_increases'])); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-danger bg-opacity-10 text-danger p-3 rounded">
                            <i class="fas fa-arrow-down fa-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Decreases</h6>
                        <h3 class="mb-0 text-danger">-<?php echo e(number_format($stats['total_decreases'])); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 text-info p-3 rounded">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">This Month</h6>
                        <h3 class="mb-0"><?php echo e($stats['this_month']); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">
                <i class="fas fa-filter me-2"></i>Filters
            </h5>
            <a href="<?php echo e(route('admin.stock-adjustments.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Adjustment
            </a>
        </div>
        
        <form method="GET" action="<?php echo e(route('admin.stock-adjustments.index')); ?>" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="increase" <?php echo e(request('type') == 'increase' ? 'selected' : ''); ?>>Increase</option>
                    <option value="decrease" <?php echo e(request('type') == 'decrease' ? 'selected' : ''); ?>>Decrease</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Reason</label>
                <select name="reason" class="form-select">
                    <option value="">All Reasons</option>
                    <option value="damaged" <?php echo e(request('reason') == 'damaged' ? 'selected' : ''); ?>>Damaged</option>
                    <option value="lost" <?php echo e(request('reason') == 'lost' ? 'selected' : ''); ?>>Lost/Missing</option>
                    <option value="found" <?php echo e(request('reason') == 'found' ? 'selected' : ''); ?>>Found</option>
                    <option value="expired" <?php echo e(request('reason') == 'expired' ? 'selected' : ''); ?>>Expired</option>
                    <option value="returned" <?php echo e(request('reason') == 'returned' ? 'selected' : ''); ?>>Returned</option>
                    <option value="theft" <?php echo e(request('reason') == 'theft' ? 'selected' : ''); ?>>Theft</option>
                    <option value="sample" <?php echo e(request('reason') == 'sample' ? 'selected' : ''); ?>>Sample</option>
                    <option value="manufacturing_defect" <?php echo e(request('reason') == 'manufacturing_defect' ? 'selected' : ''); ?>>Manufacturing Defect</option>
                    <option value="stock_count_correction" <?php echo e(request('reason') == 'stock_count_correction' ? 'selected' : ''); ?>>Stock Count Correction</option>
                    <option value="other" <?php echo e(request('reason') == 'other' ? 'selected' : ''); ?>>Other</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
            </div>
            
            <div class="col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
            </div>
            
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="<?php echo e(route('admin.stock-adjustments.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Stock Adjustment History</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Adjustment #</th>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Old Stock</th>
                        <th>New Stock</th>
                        <th>Reason</th>
                        <th>Adjusted By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $adjustments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adjustment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <a href="<?php echo e(route('admin.stock-adjustments.show', $adjustment)); ?>" class="text-decoration-none fw-bold">
                                <?php echo e($adjustment->adjustment_number); ?>

                            </a>
                        </td>
                        <td><?php echo e($adjustment->adjustment_date->format('M d, Y')); ?></td>
                        <td>
                            <div class="fw-bold"><?php echo e($adjustment->product->name); ?></div>
                            <small class="text-muted">SKU: <?php echo e($adjustment->product->sku); ?></small>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo e($adjustment->type_badge_class); ?>">
                                <i class="fas fa-<?php echo e($adjustment->type_icon); ?> me-1"></i>
                                <?php echo e($adjustment->type_display); ?>

                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-<?php echo e($adjustment->type === 'increase' ? 'success' : 'danger'); ?>">
                                <?php echo e($adjustment->type === 'increase' ? '+' : '-'); ?><?php echo e($adjustment->quantity); ?>

                            </span>
                        </td>
                        <td><?php echo e($adjustment->old_stock); ?></td>
                        <td>
                            <span class="fw-bold"><?php echo e($adjustment->new_stock); ?></span>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?php echo e($adjustment->reason_display); ?></span>
                        </td>
                        <td><?php echo e($adjustment->adjustedBy->name); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo e(route('admin.stock-adjustments.show', $adjustment)); ?>" class="btn btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.stock-adjustments.edit', $adjustment)); ?>" class="btn btn-outline-secondary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            No stock adjustments found. 
                            <a href="<?php echo e(route('admin.stock-adjustments.create')); ?>">Create your first adjustment</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($adjustments->hasPages()): ?>
    <div class="card-footer bg-white">
        <?php echo e($adjustments->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>







<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/stock-adjustments/index.blade.php ENDPATH**/ ?>