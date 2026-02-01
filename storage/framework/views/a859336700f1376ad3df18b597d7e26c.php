<?php $__env->startSection('title', 'Pathao Parcel'); ?>
<?php $__env->startSection('page-title', 'Pathao Parcel'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Pathao Parcel Dashboard</h1>
            <p class="text-muted">Manage your Pathao shipments and track deliveries</p>
        </div>
        <div>
            <a href="<?php echo e(route('admin.pathao.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Shipment
            </a>
            <a href="<?php echo e(route('admin.pathao.bulk-create')); ?>" class="btn btn-success">
                <i class="fas fa-layer-group"></i> Bulk Create
            </a>
            <a href="<?php echo e(route('admin.pathao.settings')); ?>" class="btn btn-secondary">
                <i class="fas fa-cog"></i> Settings
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Shipments</h6>
                    <h3><?php echo e($totalShipments); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Delivered</h6>
                    <h3 class="text-success"><?php echo e($deliveredShipments); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Pending</h6>
                    <h3 class="text-warning"><?php echo e($pendingShipments); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">COD Collected</h6>
                    <h3 class="text-info"><?php echo e($codCollectedCount); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.pathao.index')); ?>">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by consignment ID, tracking ID, or order number" 
                               value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="Order Created" <?php echo e(request('status') == 'Order Created' ? 'selected' : ''); ?>>Order Created</option>
                            <option value="In Transit" <?php echo e(request('status') == 'In Transit' ? 'selected' : ''); ?>>In Transit</option>
                            <option value="Delivered" <?php echo e(request('status') == 'Delivered' ? 'selected' : ''); ?>>Delivered</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="cod_collected" class="form-select">
                            <option value="">All COD Status</option>
                            <option value="1" <?php echo e(request('cod_collected') == '1' ? 'selected' : ''); ?>>COD Collected</option>
                            <option value="0" <?php echo e(request('cod_collected') == '0' ? 'selected' : ''); ?>>COD Pending</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Shipments Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Consignment ID</th>
                            <th>Order Number</th>
                            <th>Recipient</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>COD</th>
                            <th>Shipped At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $shipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($shipment->consignment_id ?? $shipment->pathao_order_id ?? 'N/A'); ?></strong>
                                <?php if($shipment->tracking_id): ?>
                                    <br><small class="text-muted">Track: <?php echo e($shipment->tracking_id); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($shipment->order->order_number ?? 'N/A'); ?></td>
                            <td>
                                <?php echo e($shipment->recipient_name); ?><br>
                                <small class="text-muted"><?php echo e($shipment->recipient_phone); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($shipment->getStatusColor()); ?>">
                                    <?php echo e($shipment->status ?? 'N/A'); ?>

                                </span>
                            </td>
                            <td>₹<?php echo e(number_format($shipment->amount, 2)); ?></td>
                            <td>
                                <?php if($shipment->cod_collected): ?>
                                    <span class="badge bg-success">Collected</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($shipment->shipped_at ? $shipment->shipped_at->format('Y-m-d H:i') : 'N/A'); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.pathao.show', $shipment->id)); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center">No shipments found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <?php echo e($shipments->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/pathao/index.blade.php ENDPATH**/ ?>