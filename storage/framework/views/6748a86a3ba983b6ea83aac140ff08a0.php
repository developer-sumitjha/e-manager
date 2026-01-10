<?php $__env->startSection('title', 'Delivery Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="top-bar">
    <div class="page-title">
        <h1>Delivery Details</h1>
        <p>Order #<?php echo e($manualDelivery->order->order_number); ?></p>
    </div>
    <div class="top-bar-actions">
        <span class="badge <?php echo e($manualDelivery->getStatusBadgeClass()); ?> px-3 py-2">
            <?php echo e(strtoupper(str_replace('_', ' ', $manualDelivery->status))); ?>

        </span>
    </div>
</div>

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
                        <?php echo e($manualDelivery->order->order_number); ?>

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

        <!-- Update Status Form -->
        <?php if($manualDelivery->canUpdateStatus()): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Update Delivery Status</h5>
            </div>
            <div class="card-body">
                <form id="updateStatusForm" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <?php if($manualDelivery->status === 'assigned'): ?>
                                <option value="picked_up">Picked Up</option>
                            <?php endif; ?>
                            <?php if(in_array($manualDelivery->status, ['assigned', 'picked_up'])): ?>
                                <option value="in_transit">In Transit</option>
                            <?php endif; ?>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div id="deliveredFields" style="display: none;">
                        <div class="mb-3">
                            <div class="form-check">
                                <?php
                                    // Calculate COD amount - use order total directly
                                    $order = $manualDelivery->order;
                                    $isCod = $order && in_array($order->payment_method, ['cod', 'cash_on_delivery']);
                                    
                                    $codAmount = 0;
                                    if ($isCod) {
                                        // Use order total directly as COD amount
                                        $codAmount = $order->total ?? 0;
                                    }
                                ?>
                                <input class="form-check-input" type="checkbox" id="cod_collected" name="cod_collected" value="1" checked required>
                                <label class="form-check-label" for="cod_collected">
                                    COD Amount Collected (₨<?php echo e(number_format($codAmount, 2)); ?>) <span class="text-danger">*</span>
                                </label>
                                <div class="invalid-feedback" id="cod_collected_error" style="display: none;">
                                    You must confirm that COD amount has been collected before marking as delivered.
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="delivery_proof" class="form-label">Delivery Proof (Photo)</label>
                            <input type="file" class="form-control" id="delivery_proof" name="delivery_proof" accept="image/*">
                        </div>
                    </div>

                    <div id="cancelledFields" style="display: none;">
                        <div class="mb-3">
                            <label for="cancellation_reason" class="form-label">Cancellation Reason</label>
                            <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i> Update Status
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        <!-- Delivery Timeline -->
        <div class="card mb-4">
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

        <!-- Payment Info -->
        <div class="card">
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
                <?php endif; ?>
                
                <?php if(!$isCod): ?>
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
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    // Show/hide fields based on status
    function toggleFields() {
        const status = $('#status').val();
        $('#deliveredFields').toggle(status === 'delivered');
        $('#cancelledFields').toggle(status === 'cancelled');
        
        // When showing delivered fields, ensure checkbox is checked by default
        if (status === 'delivered' && $('#cod_collected').length > 0) {
            $('#cod_collected').prop('checked', true);
            $('#cod_collected').removeClass('is-invalid');
            $('#cod_collected_error').hide();
        }
    }
    
    // Initialize fields visibility on page load
    toggleFields();
    
    // Update on status change
    $('#status').on('change', toggleFields);
    
    // Remove error styling when checkbox is checked
    $('#cod_collected').on('change', function() {
        if ($(this).is(':checked')) {
            $(this).removeClass('is-invalid');
            $('#cod_collected_error').hide();
        }
    });

    // Form submission
    $('#updateStatusForm').on('submit', function(e) {
        e.preventDefault();
        
        const status = $('#status').val();
        const codCollectedCheckbox = $('#cod_collected');
        const isCodOrder = codCollectedCheckbox.length > 0;
        
        // Validation: If status is "delivered" and it's a COD order, checkbox must be checked
        if (status === 'delivered' && isCodOrder) {
            if (!codCollectedCheckbox.is(':checked')) {
                // Show error message
                codCollectedCheckbox.addClass('is-invalid');
                $('#cod_collected_error').show();
                
                // Scroll to the checkbox
                $('html, body').animate({
                    scrollTop: codCollectedCheckbox.offset().top - 100
                }, 500);
                
                // Show notification
                showNotification('Please confirm that COD amount has been collected before marking as delivered.', 'error');
                return false;
            } else {
                // Remove error styling if checkbox is checked
                codCollectedCheckbox.removeClass('is-invalid');
                $('#cod_collected_error').hide();
            }
        }
        
        const formData = new FormData(this);
        
        // Debug: Log form data
        console.log('Form submission - Status:', status);
        console.log('Form submission - COD Collected checkbox checked:', codCollectedCheckbox.is(':checked'));
        
        // CRITICAL FIX: Ensure checkbox value is always included for delivered status
        // When checkbox is in a hidden div, FormData might not include it even if checked
        if (status === 'delivered' && isCodOrder) {
            const isChecked = codCollectedCheckbox.is(':checked');
            // Always set the value explicitly - '1' if checked
            if (isChecked) {
                formData.set('cod_collected', '1');
                console.log('✓ Manually added cod_collected=1 to FormData (checkbox was checked)');
            }
        }
        
        // Debug: Log all FormData entries
        console.log('FormData contents:');
        for (let pair of formData.entries()) {
            console.log('  -', pair[0] + ':', pair[1]);
        }
        
        fetch('<?php echo e(route("delivery-boy.delivery.update-status", $manualDelivery)); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Request failed');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Response from server:', data);
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error.message || 'An error occurred', 'error');
        });
    });
});

function showNotification(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const alert = $(`
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas ${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);
    
    $('body').append(alert);
    setTimeout(() => alert.alert('close'), 3000);
}
</script>
<?php $__env->stopSection(); ?>








<?php echo $__env->make('delivery-boy.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/delivery-boy/delivery-details.blade.php ENDPATH**/ ?>