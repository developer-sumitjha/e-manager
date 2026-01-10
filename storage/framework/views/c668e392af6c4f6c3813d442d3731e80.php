<?php $__env->startSection('title', 'Create COD Settlement'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Create COD Settlement</h1>
            <p class="text-muted">Settle pending COD payments with <?php echo e($deliveryBoy->name); ?></p>
        </div>
        <a href="<?php echo e(route('admin.manual-delivery.cod-settlements')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-list"></i> Pending Deliveries</h6>
                </div>
                <div class="card-body">
                    <?php if($pendingDeliveries->count() > 0): ?>
                    <form method="POST" action="<?php echo e(route('admin.manual-delivery.store-settlement', $deliveryBoy)); ?>" id="settlementForm">
                        <?php echo csrf_field(); ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll" checked></th>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>COD Amount</th>
                                        <th>Delivered At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $pendingDeliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="delivery_ids[]" value="<?php echo e($delivery->id); ?>" class="delivery-checkbox" checked>
                                        </td>
                                        <td><strong><?php echo e($delivery->order->order_number); ?></strong></td>
                                        <td><?php echo e($delivery->order->receiver_name ?? $delivery->order->user->name ?? 'N/A'); ?></td>
                                        <td class="cod-amount" data-amount="<?php echo e($delivery->order->total ?? 0); ?>">
                                            <strong class="text-danger">₨<?php echo e(number_format($delivery->order->total ?? 0, 2)); ?></strong>
                                        </td>
                                        <td><?php echo e($delivery->delivered_at->format('M d, Y h:i A')); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <td colspan="3" class="text-end"><strong>Selected Total:</strong></td>
                                        <td colspan="2"><strong class="text-success" id="selectedTotal">₨<?php echo e(number_format($totalAmount, 2)); ?></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">Payment Method *</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="mobile_wallet">Mobile Wallet</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="transaction_reference" class="form-label">Transaction Reference (Optional)</label>
                                <input type="text" class="form-control" id="transaction_reference" name="transaction_reference" placeholder="e.g., Cheque #, Transaction ID">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="notes" class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any additional notes..."></textarea>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-check-circle me-2"></i> Complete Settlement
                        </button>
                    </form>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">No Pending COD</h4>
                        <p class="text-muted">This delivery boy has no pending COD settlements.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-user"></i> Delivery Boy Info</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="<?php echo e($deliveryBoy->profile_photo ? asset('storage/' . $deliveryBoy->profile_photo) : 'https://via.placeholder.com/100'); ?>" 
                             alt="<?php echo e($deliveryBoy->name); ?>" 
                             class="rounded-circle" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong>Name:</strong></td>
                            <td><?php echo e($deliveryBoy->name); ?></td>
                        </tr>
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td><?php echo e($deliveryBoy->delivery_boy_id); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Phone:</strong></td>
                            <td><?php echo e($deliveryBoy->phone); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Zone:</strong></td>
                            <td><span class="badge bg-primary"><?php echo e(ucfirst($deliveryBoy->zone ?? 'N/A')); ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Rating:</strong></td>
                            <td><span class="text-warning">★</span> <?php echo e($deliveryBoy->rating); ?>/5</td>
                        </tr>
                        <tr>
                            <td><strong>Total Deliveries:</strong></td>
                            <td><?php echo e($deliveryBoy->total_deliveries); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle"></i> Settlement Summary</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Orders:</span>
                        <strong id="totalOrders"><?php echo e($pendingDeliveries->count()); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Selected Orders:</span>
                        <strong id="selectedOrders"><?php echo e($pendingDeliveries->count()); ?></strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span><strong>Total Amount:</strong></span>
                        <strong class="text-success" id="totalAmountSummary">₨<?php echo e(number_format($totalAmount, 2)); ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    updateTotals();
    
    $('#selectAll').on('change', function() {
        $('.delivery-checkbox').prop('checked', $(this).prop('checked'));
        updateTotals();
    });
    
    $('.delivery-checkbox').on('change', function() {
        updateTotals();
        
        // Update select all checkbox
        const totalCheckboxes = $('.delivery-checkbox').length;
        const checkedCheckboxes = $('.delivery-checkbox:checked').length;
        $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
    });
});

function updateTotals() {
    let total = 0;
    let count = 0;
    
    $('.delivery-checkbox:checked').each(function() {
        const row = $(this).closest('tr');
        const amount = parseFloat(row.find('.cod-amount').data('amount'));
        total += amount;
        count++;
    });
    
    $('#selectedTotal').text('₨' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    $('#totalAmountSummary').text('₨' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    $('#selectedOrders').text(count);
}

$('#settlementForm').on('submit', function(e) {
    const selectedCount = $('.delivery-checkbox:checked').length;
    if (selectedCount === 0) {
        e.preventDefault();
        alert('Please select at least one delivery to settle');
        return false;
    }
    
    return confirm(`Create settlement for ${selectedCount} orders?`);
});
</script>
<?php $__env->stopSection(); ?>








<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/manual-delivery/create-settlement.blade.php ENDPATH**/ ?>