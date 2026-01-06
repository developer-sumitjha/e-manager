<?php $__env->startSection('title', 'Shipment Details'); ?>
<?php $__env->startSection('page-title', 'Shipment Details'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Shipment Details Page Specific Styles */
    .shipment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title-section h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
        background: linear-gradient(135deg, #10B981, #34D399);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-top: 0.5rem;
        font-weight: 400;
    }

    .back-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: rgba(107, 114, 128, 0.1);
        color: #6B7280;
        text-decoration: none;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid rgba(107, 114, 128, 0.2);
    }

    .back-btn:hover {
        background: rgba(107, 114, 128, 0.2);
        color: #6B7280;
        transform: translateY(-2px);
    }

    .shipment-details {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .details-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(16, 185, 129, 0.1);
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-title i {
        color: #10B981;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }

    .status-in-transit {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
    }

    .status-delivered {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .status-returned {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(16, 185, 129, 0.05);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .detail-value {
        font-weight: 500;
        color: var(--text-primary);
        text-align: right;
    }

    .tracking-number {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: var(--primary-color);
        background: rgba(16, 185, 129, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
    }

    .method-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .method-manual {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .method-logistics {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
    }

    .timeline {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        margin-bottom: 2rem;
    }

    .timeline-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 1.5rem;
        top: 2.5rem;
        bottom: -1.5rem;
        width: 2px;
        background: rgba(16, 185, 129, 0.2);
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
        flex-shrink: 0;
        z-index: 1;
        position: relative;
    }

    .timeline-icon.pending {
        background: linear-gradient(135deg, #F59E0B, #FBBF24);
    }

    .timeline-icon.in-transit {
        background: linear-gradient(135deg, #3B82F6, #60A5FA);
    }

    .timeline-icon.delivered {
        background: linear-gradient(135deg, #10B981, #34D399);
    }

    .timeline-icon.returned {
        background: linear-gradient(135deg, #EF4444, #F87171);
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-title {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .timeline-description {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .timeline-date {
        color: var(--text-secondary);
        font-size: 0.75rem;
        font-weight: 500;
    }

    .actions-section {
        background: rgba(16, 185, 129, 0.05);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        text-align: center;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        cursor: pointer;
        margin: 0.25rem;
    }

    .action-btn.edit {
        background: linear-gradient(135deg, #F59E0B, #FBBF24);
        color: white;
    }

    .action-btn.edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        color: white;
    }

    .action-btn.delete {
        background: linear-gradient(135deg, #EF4444, #F87171);
        color: white;
    }

    .action-btn.delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
        color: white;
    }

    .action-btn.update-status {
        background: linear-gradient(135deg, #3B82F6, #60A5FA);
        color: white;
    }

    .action-btn.update-status:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        color: white;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .shipment-details {
            grid-template-columns: 1fr;
        }
        
        .details-card {
            padding: 1rem;
        }
        
        .timeline {
            padding: 1rem;
        }
        
        .timeline-item {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .timeline-item::before {
            display: none;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="shipment-header">
    <div class="page-title-section">
        <h1>Shipment Details</h1>
        <p class="page-subtitle">View and manage shipment information</p>
    </div>
    <a href="<?php echo e(route('admin.shipments.index')); ?>" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Shipments
    </a>
</div>

<div class="shipment-details">
    <div class="details-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-shipping-fast"></i>
                Shipment Information
            </h3>
            <span class="status-badge status-<?php echo e($shipment->status); ?>">
                <?php echo e(ucfirst(str_replace('_', ' ', $shipment->status))); ?>

            </span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Tracking Number</span>
            <span class="detail-value">
                <span class="tracking-number"><?php echo e($shipment->tracking_number); ?></span>
            </span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Shipping Method</span>
            <span class="detail-value">
                <span class="method-badge method-<?php echo e($shipment->method); ?>">
                    <?php echo e(ucfirst($shipment->method)); ?>

                </span>
            </span>
        </div>
        
        <?php if($shipment->carrier): ?>
        <div class="detail-row">
            <span class="detail-label">Carrier</span>
            <span class="detail-value"><?php echo e($shipment->carrier); ?></span>
        </div>
        <?php endif; ?>
        
        <div class="detail-row">
            <span class="detail-label">Order Number</span>
            <span class="detail-value">
                <a href="<?php echo e(route('admin.orders.show', $shipment->order_id)); ?>" class="text-decoration-none">
                    <?php echo e($shipment->order->order_number); ?>

                </a>
            </span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Customer</span>
            <span class="detail-value"><?php echo e($shipment->order->user->name ?? 'N/A'); ?></span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Shipping Address</span>
            <span class="detail-value"><?php echo e($shipment->order->shipping_address); ?></span>
        </div>
        
        <?php if($shipment->notes): ?>
        <div class="detail-row">
            <span class="detail-label">Notes</span>
            <span class="detail-value"><?php echo e($shipment->notes); ?></span>
        </div>
        <?php endif; ?>
        
        <div class="detail-row">
            <span class="detail-label">Created By</span>
            <span class="detail-value"><?php echo e($shipment->createdBy->name ?? 'System'); ?></span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Created At</span>
            <span class="detail-value"><?php echo e($shipment->created_at->format('M d, Y H:i')); ?></span>
        </div>
        
        <?php if($shipment->shipped_at): ?>
        <div class="detail-row">
            <span class="detail-label">Shipped At</span>
            <span class="detail-value"><?php echo e($shipment->shipped_at->format('M d, Y H:i')); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if($shipment->delivered_at): ?>
        <div class="detail-row">
            <span class="detail-label">Delivered At</span>
            <span class="detail-value"><?php echo e($shipment->delivered_at->format('M d, Y H:i')); ?></span>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="details-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-box"></i>
                Order Summary
            </h3>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Order Total</span>
            <span class="detail-value">₹<?php echo e(number_format($shipment->order->total, 2)); ?></span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Payment Status</span>
            <span class="detail-value">
                <span class="status-badge status-<?php echo e($shipment->order->payment_status); ?>">
                    <?php echo e(ucfirst($shipment->order->payment_status)); ?>

                </span>
            </span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Payment Method</span>
            <span class="detail-value"><?php echo e(ucfirst(str_replace('_', ' ', $shipment->order->payment_method))); ?></span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Order Status</span>
            <span class="detail-value">
                <span class="status-badge status-<?php echo e($shipment->order->status); ?>">
                    <?php echo e(ucfirst($shipment->order->status)); ?>

                </span>
            </span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Items Count</span>
            <span class="detail-value"><?php echo e($shipment->order->orderItems->count()); ?> items</span>
        </div>
        
        <div class="detail-row">
            <span class="detail-label">Order Date</span>
            <span class="detail-value"><?php echo e($shipment->order->created_at->format('M d, Y')); ?></span>
        </div>
    </div>
</div>

<div class="timeline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-route"></i>
            Shipping Timeline
        </h3>
    </div>
    
    <div class="timeline-item">
        <div class="timeline-icon pending">
            <i class="fas fa-clock"></i>
        </div>
        <div class="timeline-content">
            <div class="timeline-title">Shipment Created</div>
            <div class="timeline-description">Shipment has been created and is ready for processing</div>
            <div class="timeline-date"><?php echo e($shipment->created_at->format('M d, Y H:i')); ?></div>
        </div>
    </div>
    
    <?php if($shipment->shipped_at): ?>
    <div class="timeline-item">
        <div class="timeline-icon in-transit">
            <i class="fas fa-truck"></i>
        </div>
        <div class="timeline-content">
            <div class="timeline-title">Shipped</div>
            <div class="timeline-description">Package has been shipped and is in transit</div>
            <div class="timeline-date"><?php echo e($shipment->shipped_at->format('M d, Y H:i')); ?></div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if($shipment->status === 'delivered' && $shipment->delivered_at): ?>
    <div class="timeline-item">
        <div class="timeline-icon delivered">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="timeline-content">
            <div class="timeline-title">Delivered</div>
            <div class="timeline-description">Package has been successfully delivered</div>
            <div class="timeline-date"><?php echo e($shipment->delivered_at->format('M d, Y H:i')); ?></div>
        </div>
    </div>
    <?php elseif($shipment->status === 'returned'): ?>
    <div class="timeline-item">
        <div class="timeline-icon returned">
            <i class="fas fa-undo"></i>
        </div>
        <div class="timeline-content">
            <div class="timeline-title">Returned</div>
            <div class="timeline-description">Package has been returned</div>
            <div class="timeline-date"><?php echo e($shipment->updated_at->format('M d, Y H:i')); ?></div>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="actions-section">
    <a href="<?php echo e(route('admin.shipments.edit', $shipment->id)); ?>" class="action-btn edit">
        <i class="fas fa-edit"></i> Edit Shipment
    </a>
    
    <button type="button" class="action-btn update-status" onclick="updateStatus()">
        <i class="fas fa-sync"></i> Update Status
    </button>
    
    <button type="button" class="action-btn delete" onclick="deleteShipment()">
        <i class="fas fa-trash"></i> Delete Shipment
    </button>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Shipment Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm">
                    <div class="mb-3">
                        <label for="status" class="form-label">New Status</label>
                        <select class="form-select" id="status" required>
                            <option value="pending" <?php echo e($shipment->status === 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="in_transit" <?php echo e($shipment->status === 'in_transit' ? 'selected' : ''); ?>>In Transit</option>
                            <option value="delivered" <?php echo e($shipment->status === 'delivered' ? 'selected' : ''); ?>>Delivered</option>
                            <option value="returned" <?php echo e($shipment->status === 'returned' ? 'selected' : ''); ?>>Returned</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status_notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="status_notes" rows="3" placeholder="Add any notes about this status update..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmUpdateStatus()">Update Status</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function updateStatus() {
        const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
        modal.show();
    }

    function confirmUpdateStatus() {
        const status = document.getElementById('status').value;
        const notes = document.getElementById('status_notes').value;
        
        fetch('<?php echo e(route("admin.shipments.update-status", $shipment->id)); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({
                status: status,
                notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                const modal = bootstrap.Modal.getInstance(document.getElementById('updateStatusModal'));
                modal.hide();
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                showNotification(data.message || 'Failed to update status.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while updating status.', 'error');
        });
    }

    function deleteShipment() {
        if (confirm('Are you sure you want to delete this shipment? This action cannot be undone.')) {
            fetch('<?php echo e(route("admin.shipments.destroy", $shipment->id)); ?>', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = '<?php echo e(route("admin.shipments.index")); ?>';
                    }, 500);
                } else {
                    showNotification('Failed to delete shipment.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while deleting shipment.', 'error');
            });
        }
    }
</script>
<?php $__env->stopPush(); ?>








<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/shipments/show.blade.php ENDPATH**/ ?>