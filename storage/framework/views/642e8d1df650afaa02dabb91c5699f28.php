<?php $__env->startSection('title', 'COD Settlements'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 rounded-3 mb-4" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); color:#fff;">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h2 class="m-0 fw-bold"><i class="fas fa-hand-holding-usd"></i> COD Settlements (Manual Delivery)</h2>
            <div class="opacity-75">Settle collected COD from delivery riders</div>
        </div>
        <a href="<?php echo e(route('admin.manual-delivery.index')); ?>" class="btn btn-light"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Pending COD by Rider</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Rider</th><th class="text-end">Pending</th><th></th></tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $pendingCod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?php echo e($row->name); ?></div>
                                    <div class="text-muted small"><?php echo e($row->phone); ?></div>
                                </td>
                                <td class="text-end">₨ <?php echo e(number_format($row->pending_amount,0)); ?> (<?php echo e($row->pending_orders); ?> orders)</td>
                                <td class="text-end">
                                    <a href="<?php echo e(route('admin.manual-delivery.create-settlement', $row->id)); ?>" class="btn btn-sm btn-primary">Settle</a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3" class="text-center text-muted">No pending COD</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Recent Settlements</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Rider</th>
                                <th>Orders</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th>Settled At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $settlements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($s->settlement_id); ?></td>
                                <td><?php echo e($s->deliveryBoy->name); ?></td>
                                <td><?php echo e($s->total_orders); ?></td>
                                <td>₨ <?php echo e(number_format($s->total_amount,0)); ?></td>
                                <td><?php echo e(ucfirst(str_replace('_',' ', $s->payment_method))); ?></td>
                                <td class="text-muted"><?php echo e($s->transaction_reference ?? '—'); ?></td>
                                <td><?php echo e(optional($s->settled_at)->format('d M Y H:i')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="7" class="text-center text-muted">No settlements</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-3"><?php echo e($settlements->links()); ?></div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/manual-delivery/cod-settlements.blade.php ENDPATH**/ ?>