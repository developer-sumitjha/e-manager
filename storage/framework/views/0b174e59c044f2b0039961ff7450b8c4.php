<?php $__env->startSection('title', 'COD Settlement'); ?>
<?php $__env->startSection('page-title', 'COD Settlement'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .cod-settlement-container {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        backdrop-filter: blur(10px);
    }

    .settlement-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.total { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
    .stat-icon.pending { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .stat-icon.settled { background: rgba(34, 197, 94, 0.1); color: #22C55E; }
    .stat-icon.count { background: rgba(139, 92, 246, 0.1); color: #8B5CF6; }

    .stat-content h3 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .stat-content p {
        color: var(--text-secondary);
        margin: 0;
        font-size: 0.9rem;
    }

    .filters-section {
        background: rgba(139, 92, 246, 0.05);
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
    }

    .filters-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .filter-input, .filter-select {
        padding: 0.75rem;
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 0.5rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .filter-input:focus, .filter-select:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .filter-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .settlements-table {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid rgba(229, 231, 235, 0.5);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(229, 231, 235, 0.5);
    }

    .table-header h3 {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.1rem;
        font-weight: 700;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table {
        margin: 0;
        width: 100%;
    }

    .table th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        padding: 1rem;
        border-bottom: 2px solid #dee2e6;
        font-size: 0.9rem;
        white-space: nowrap;
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .table tr:hover {
        background-color: #f8f9fa;
    }

    .settlement-status {
        padding: 0.5rem 1rem;
        border-radius: 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        text-align: center;
    }

    .settlement-status.pending {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }

    .settlement-status.settled {
        background: rgba(34, 197, 94, 0.1);
        color: #22C55E;
    }

    .cod-amount {
        font-weight: 700;
        color: #22C55E;
    }

    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .action-btn.settle {
        background: #22C55E;
        color: white;
    }

    .action-btn.view {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .action-btn:hover {
        transform: translateY(-1px);
    }

    .modal-content {
        border-radius: 1rem;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        color: white;
        border-radius: 1rem 1rem 0 0;
        border: none;
    }

    .modal-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 0.5rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="cod-settlement-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-money-bill-wave"></i>
                            COD Settlement
                        </h1>
                        <p class="page-subtitle">Manage cash-on-delivery payments and settlements</p>
                    </div>
                    <a href="<?php echo e(route('admin.gaaubesi.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <!-- Settlement Statistics -->
                <div class="settlement-stats">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon total">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="stat-content">
                                <h3>Rs. <?php echo e(number_format($settlementStats['total_cod_amount'], 0)); ?></h3>
                                <p>Total COD Amount</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon pending">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <h3>Rs. <?php echo e(number_format($settlementStats['pending_cod_amount'], 0)); ?></h3>
                                <p>Pending Settlement</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon settled">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <h3>Rs. <?php echo e(number_format($settlementStats['settled_cod_amount'], 0)); ?></h3>
                                <p>Settled Amount</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon count">
                                <i class="fas fa-list"></i>
                            </div>
                            <div class="stat-content">
                                <h3><?php echo e($settlementStats['pending_count']); ?></h3>
                                <p>Pending Shipments</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filters-section">
                    <form method="GET">
                        <div class="filters-row">
                            <div class="filter-group">
                                <label for="settlement_status">Settlement Status</label>
                                <select name="settlement_status" id="settlement_status" class="filter-select">
                                    <option value="">All Status</option>
                                    <option value="pending" <?php echo e(request('settlement_status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                    <option value="settled" <?php echo e(request('settlement_status') == 'settled' ? 'selected' : ''); ?>>Settled</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label for="date_from">From Date</label>
                                <input type="date" name="date_from" id="date_from" class="filter-input" value="<?php echo e(request('date_from')); ?>">
                            </div>

                            <div class="filter-group">
                                <label for="date_to">To Date</label>
                                <input type="date" name="date_to" id="date_to" class="filter-input" value="<?php echo e(request('date_to')); ?>">
                            </div>

                            <div class="filter-group">
                                <button type="submit" class="filter-btn">
                                    <i class="fas fa-filter"></i> Apply Filters
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Settlements Table -->
                <div class="settlements-table">
                    <div class="table-header">
                        <h3>COD Settlement Records</h3>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tracking #</th>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>COD Amount</th>
                                    <th>Delivery Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $shipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($shipment->tracking_number ?? 'N/A'); ?></td>
                                    <td><?php echo e($shipment->order->order_number ?? 'N/A'); ?></td>
                                    <td>
                                        <div>
                                            <strong><?php echo e($shipment->order->user->name ?? 'N/A'); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo e($shipment->order->user->phone ?? 'N/A'); ?></small>
                                        </div>
                                    </td>
                                    <td class="cod-amount">Rs. <?php echo e(number_format($shipment->cod_charge, 0)); ?></td>
                                    <td><?php echo e($shipment->delivered_at ? $shipment->delivered_at->format('M d, Y') : 'Not delivered'); ?></td>
                                    <td>
                                        <span class="settlement-status <?php echo e($shipment->cod_paid ? 'settled' : 'pending'); ?>">
                                            <?php echo e($shipment->cod_paid ? 'Settled' : 'Pending'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if(!$shipment->cod_paid && $shipment->delivered_at): ?>
                                            <button class="action-btn settle" onclick="openSettleModal(<?php echo e($shipment->id); ?>, <?php echo e($shipment->cod_charge); ?>)">
                                                <i class="fas fa-money-bill-wave"></i> Settle
                                            </button>
                                        <?php endif; ?>
                                        <a href="<?php echo e(route('admin.gaaubesi.show', $shipment)); ?>" class="action-btn view">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <p>No COD settlements found.</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($shipments->hasPages()): ?>
                    <div class="pagination-wrapper p-3">
                        <?php echo e($shipments->links()); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Settlement Modal -->
<div class="modal fade" id="settleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-money-bill-wave"></i> Record COD Settlement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="settleForm">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label class="form-label">Settlement Amount *</label>
                        <input type="number" name="settlement_amount" id="settlement_amount" class="form-control" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Settlement Date *</label>
                        <input type="date" name="settlement_date" id="settlement_date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Settlement Method *</label>
                        <select name="settlement_method" id="settlement_method" class="form-control" required>
                            <option value="">Select Method</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cash">Cash</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Digital Payment">Digital Payment</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Additional notes..."></textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Record Settlement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let currentShipmentId = null;

function openSettleModal(shipmentId, codAmount) {
    currentShipmentId = shipmentId;
    document.getElementById('settlement_amount').value = codAmount;
    document.getElementById('settlement_date').value = new Date().toISOString().split('T')[0];
    
    const modal = new bootstrap.Modal(document.getElementById('settleModal'));
    modal.show();
}

document.getElementById('settleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!currentShipmentId) return;
    
    const formData = new FormData(this);
    
    fetch(`/admin/gaaubesi/${currentShipmentId}/mark-cod-settled`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while recording settlement.');
    });
});
</script>
<?php $__env->stopPush(); ?>







<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/gaaubesi/cod-settlement.blade.php ENDPATH**/ ?>