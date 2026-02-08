<?php $__env->startSection('title', 'Trash - Pending Orders'); ?>
<?php $__env->startSection('page-title', 'Trash - Pending Orders'); ?>
<?php $__env->startSection('page-subtitle', 'Deleted orders that can be restored or permanently deleted'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <div class="breadcrumb-item">
        <a href="<?php echo e(route('admin.orders.index')); ?>" class="breadcrumb-link">Orders</a>
    </div>
    <div class="breadcrumb-item">
        <a href="<?php echo e(route('admin.pending-orders.index')); ?>" class="breadcrumb-link">Pending Orders</a>
    </div>
    <div class="breadcrumb-item active">Trash</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Search and Actions -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           placeholder="Search deleted orders..." 
                           id="order-search" 
                           value="<?php echo e(request('search')); ?>"
                           class="search-input">
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="<?php echo e(route('admin.pending-orders.index')); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Back to Pending Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Deleted Orders (Trash)</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()" title="Refresh">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Amount</th>
                        <th>Products</th>
                        <th>Deleted At</th>
                        <th width="200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr data-order-id="<?php echo e($order->id); ?>">
                        <td>
                            <div class="d-flex flex-column">
                                <span class="text-decoration-none fw-semibold">
                                    <?php echo e($order->order_number); ?>

                                </span>
                                <small class="text-muted"><?php echo e($order->id); ?></small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-initials">
                                        <?php echo e(substr($order->receiver_name ?? $order->user->name ?? 'G', 0, 1)); ?>

                                    </div>
                                </div>
                                <div>
                                    <div class="fw-semibold"><?php echo e($order->receiver_name ?? $order->user->name ?? 'Guest'); ?></div>
                                    <small class="text-muted"><?php echo e($order->receiver_phone ?? $order->user->phone ?? 'N/A'); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="address-info">
                                <div class="fw-semibold"><?php echo e(Str::limit($order->shipping_address ?? 'Not specified', 30)); ?></div>
                                <small class="text-muted"><?php echo e($order->receiver_city ?? 'N/A'); ?></small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div class="fw-semibold">Rs. <?php echo e(number_format($order->total, 0)); ?></div>
                                <div class="d-flex gap-2">
                                    <?php if($order->payment_status === 'paid'): ?>
                                        <span class="badge badge-success">Paid</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">COD</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="products-info">
                                <div class="fw-semibold"><?php echo e($order->orderItems->count()); ?> items</div>
                                <small class="text-muted">
                                    <?php if($order->orderItems->count() > 0): ?>
                                        <?php
                                            $firstItem = $order->orderItems->first();
                                            $productName = $firstItem->product->name ?? 'N/A';
                                        ?>
                                        <?php echo e(Str::limit($productName, 20)); ?>

                                        <?php if($order->orderItems->count() > 1): ?>
                                            +<?php echo e($order->orderItems->count() - 1); ?> more
                                        <?php endif; ?>
                                    <?php else: ?>
                                        No products
                                    <?php endif; ?>
                                </small>
                            </div>
                        </td>
                        <td>
                            <div class="text-muted">
                                <small><?php echo e($order->deleted_at->format('M d, Y')); ?></small><br>
                                <small><?php echo e($order->deleted_at->format('h:i A')); ?></small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-success restore-order-btn" 
                                        data-order-id="<?php echo e($order->id); ?>"
                                        data-order-number="<?php echo e($order->order_number); ?>"
                                        title="Restore Order">
                                    <i class="fas fa-undo"></i> Restore
                                </button>
                                <button class="btn btn-sm btn-danger force-delete-order-btn" 
                                        data-order-id="<?php echo e($order->id); ?>"
                                        data-order-number="<?php echo e($order->order_number); ?>"
                                        title="Permanently Delete">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-trash-alt fa-3x text-muted mb-3"></i>
                                <h5>No deleted orders found</h5>
                                <p class="text-muted">Trash is empty. Deleted orders will appear here.</p>
                                <a href="<?php echo e(route('admin.pending-orders.index')); ?>" class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Back to Pending Orders
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($orders->hasPages()): ?>
    <div class="card-footer">
        <div class="d-flex justify-content-center">
            <?php echo e($orders->links()); ?>

        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('order-search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const search = this.value;
                const url = new URL(window.location.href);
                if (search) {
                    url.searchParams.set('search', search);
                } else {
                    url.searchParams.delete('search');
                }
                window.location.href = url.toString();
            }, 500);
        });
    }

    // Restore order
    document.querySelectorAll('.restore-order-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const orderNumber = this.dataset.orderNumber;
            
            if (!confirm(`Are you sure you want to restore order ${orderNumber}?`)) {
                return;
            }

            const row = this.closest('tr');
            const originalBtn = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Restoring...';

            fetch(`<?php echo e(url('admin/pending-orders')); ?>/${orderId}/restore`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message || 'Order restored successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = '<?php echo e(route('admin.pending-orders.index')); ?>';
                    }, 1500);
                } else {
                    showNotification(data.message || 'Failed to restore order.', 'error');
                    this.disabled = false;
                    this.innerHTML = originalBtn;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while restoring the order.', 'error');
                this.disabled = false;
                this.innerHTML = originalBtn;
            });
        });
    });

    // Permanently delete order
    document.querySelectorAll('.force-delete-order-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const orderNumber = this.dataset.orderNumber;
            
            if (!confirm(`Are you sure you want to PERMANENTLY DELETE order ${orderNumber}? This action cannot be undone!`)) {
                return;
            }

            const row = this.closest('tr');
            const originalBtn = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';

            fetch(`<?php echo e(url('admin/pending-orders')); ?>/${orderId}/force-delete`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message || 'Order permanently deleted.', 'success');
                    row.style.opacity = '0.5';
                    setTimeout(() => {
                        row.remove();
                        // Check if table is empty
                        const tbody = row.closest('tbody');
                        if (tbody && tbody.querySelectorAll('tr').length === 0) {
                            window.location.reload();
                        }
                    }, 500);
                } else {
                    showNotification(data.message || 'Failed to delete order.', 'error');
                    this.disabled = false;
                    this.innerHTML = originalBtn;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while deleting the order.', 'error');
                this.disabled = false;
                this.innerHTML = originalBtn;
            });
        });
    });

    // Helper function for notifications
    function showNotification(message, type = 'info') {
        // Use AdminDashboard notification if available
        if (window.AdminDashboard && typeof window.AdminDashboard.showNotification === 'function') {
            window.AdminDashboard.showNotification(message, type);
        } else {
            // Fallback to alert
            alert(message);
        }
    }
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/pending-orders/trash.blade.php ENDPATH**/ ?>