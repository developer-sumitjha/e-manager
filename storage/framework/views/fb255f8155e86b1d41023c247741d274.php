<?php $__env->startSection('title', 'Comments & Communication'); ?>
<?php $__env->startSection('page-title', 'Comments & Communication'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .comments-container {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        backdrop-filter: blur(10px);
    }

    .comment-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
        text-align: center;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 1rem;
    }

    .stat-icon.total { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
    .stat-icon.today { background: rgba(34, 197, 94, 0.1); color: #22C55E; }
    .stat-icon.unread { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }

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

    .filter-select, .filter-input {
        padding: 0.75rem;
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 0.5rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .filter-select:focus, .filter-input:focus {
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

    .shipments-list {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid rgba(229, 231, 235, 0.5);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .shipments-header {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(229, 231, 235, 0.5);
    }

    .shipments-header h3 {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.1rem;
        font-weight: 700;
    }

    .shipment-item {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(229, 231, 235, 0.5);
        transition: all 0.3s ease;
    }

    .shipment-item:hover {
        background-color: #f8f9fa;
    }

    .shipment-item:last-child {
        border-bottom: none;
    }

    .shipment-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .shipment-info h5 {
        margin: 0;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 700;
    }

    .shipment-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin-top: 0.5rem;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.created { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
    .status-badge.in-transit { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .status-badge.delivered { background: rgba(34, 197, 94, 0.1); color: #22C55E; }
    .status-badge.failed { background: rgba(239, 68, 68, 0.1); color: #EF4444; }

    .comments-section {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(229, 231, 235, 0.3);
    }

    .add-comment-form {
        background: rgba(139, 92, 246, 0.05);
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
    }

    .comment-input-group {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
    }

    .comment-input {
        flex: 1;
        padding: 0.75rem;
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 0.5rem;
        font-size: 0.9rem;
        resize: vertical;
        min-height: 80px;
        transition: all 0.3s ease;
    }

    .comment-input:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .comment-type-select {
        padding: 0.75rem;
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 0.5rem;
        font-size: 0.9rem;
        background: white;
        transition: all 0.3s ease;
    }

    .comment-type-select:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .add-comment-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .add-comment-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .comments-list {
        max-height: 300px;
        overflow-y: auto;
    }

    .comment-item {
        background: white;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .comment-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .comment-type {
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .comment-type.general { background: rgba(107, 114, 128, 0.1); color: #6B7280; }
    .comment-type.delivery { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
    .comment-type.customer { background: rgba(34, 197, 94, 0.1); color: #22C55E; }
    .comment-type.internal { background: rgba(139, 92, 246, 0.1); color: #8B5CF6; }

    .comment-meta {
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin-left: auto;
    }

    .comment-text {
        color: var(--text-primary);
        font-size: 0.9rem;
        line-height: 1.5;
        margin: 0;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--text-secondary);
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

    .action-btn.primary {
        background: var(--primary-color);
        color: white;
    }

    .action-btn.secondary {
        background: rgba(107, 114, 128, 0.1);
        color: var(--text-secondary);
        border: 1px solid rgba(107, 114, 128, 0.3);
    }

    .action-btn:hover {
        transform: translateY(-1px);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="comments-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-comments"></i>
                            Comments & Communication
                        </h1>
                        <p class="page-subtitle">Track and manage shipment communications</p>
                    </div>
                    <a href="<?php echo e(route('admin.gaaubesi.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <!-- Comment Statistics -->
                <div class="comment-stats">
                    <div class="stat-card">
                        <div class="stat-icon total">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo e(number_format($commentStats['total_comments'])); ?></h3>
                            <p>Total Comments</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon today">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo e(number_format($commentStats['comments_today'])); ?></h3>
                            <p>Today's Comments</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon unread">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo e(number_format($commentStats['unread_comments'])); ?></h3>
                            <p>Unread Comments</p>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filters-section">
                    <form method="GET">
                        <div class="filters-row">
                            <div class="filter-group">
                                <label for="status">Shipment Status</label>
                                <select name="status" id="status" class="filter-select">
                                    <option value="">All Status</option>
                                    <option value="Order Created" <?php echo e(request('status') == 'Order Created' ? 'selected' : ''); ?>>Order Created</option>
                                    <option value="In Transit" <?php echo e(request('status') == 'In Transit' ? 'selected' : ''); ?>>In Transit</option>
                                    <option value="Delivered" <?php echo e(request('status') == 'Delivered' ? 'selected' : ''); ?>>Delivered</option>
                                    <option value="Failed" <?php echo e(request('status') == 'Failed' ? 'selected' : ''); ?>>Failed</option>
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

                <!-- Shipments with Comments -->
                <div class="shipments-list">
                    <div class="shipments-header">
                        <h3>Shipment Communications</h3>
                    </div>
                    
                    <?php $__empty_1 = true; $__currentLoopData = $shipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="shipment-item">
                        <div class="shipment-header">
                            <div class="shipment-info">
                                <h5><?php echo e($shipment->order->order_number ?? 'N/A'); ?></h5>
                                <div class="shipment-meta">
                                    <span><i class="fas fa-user"></i> <?php echo e($shipment->order->user->name ?? 'N/A'); ?></span>
                                    <span><i class="fas fa-phone"></i> <?php echo e($shipment->order->user->phone ?? 'N/A'); ?></span>
                                    <span><i class="fas fa-clock"></i> <?php echo e($shipment->created_at->diffForHumans()); ?></span>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <span class="status-badge <?php echo e(strtolower(str_replace(' ', '-', $shipment->last_delivery_status))); ?>">
                                    <?php echo e($shipment->last_delivery_status ?? 'Unknown'); ?>

                                </span>
                                <a href="<?php echo e(route('admin.gaaubesi.show', $shipment)); ?>" class="action-btn primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </div>
                        </div>
                        
                        <div class="comments-section">
                            <div class="add-comment-form">
                                <form class="comment-input-group" onsubmit="addComment(event, <?php echo e($shipment->id); ?>)">
                                    <?php echo csrf_field(); ?>
                                    <textarea name="comment" class="comment-input" placeholder="Add a comment..." required></textarea>
                                    <select name="comment_type" class="comment-type-select" required>
                                        <option value="">Type</option>
                                        <option value="general">General</option>
                                        <option value="delivery">Delivery</option>
                                        <option value="customer">Customer</option>
                                        <option value="internal">Internal</option>
                                    </select>
                                    <button type="submit" class="add-comment-btn">
                                        <i class="fas fa-plus"></i> Add
                                    </button>
                                </form>
                            </div>
                            
                            <div class="comments-list">
                                <!-- Sample comments - in real implementation, these would come from database -->
                                <div class="comment-item">
                                    <div class="comment-header">
                                        <span class="comment-type general">General</span>
                                        <span class="comment-meta">
                                            <i class="fas fa-user"></i> Admin • <?php echo e(now()->subHours(2)->diffForHumans()); ?>

                                        </span>
                                    </div>
                                    <p class="comment-text">Shipment created and ready for pickup.</p>
                                </div>
                                
                                <div class="comment-item">
                                    <div class="comment-header">
                                        <span class="comment-type delivery">Delivery</span>
                                        <span class="comment-meta">
                                            <i class="fas fa-user"></i> System • <?php echo e(now()->subHours(1)->diffForHumans()); ?>

                                        </span>
                                    </div>
                                    <p class="comment-text">Package picked up from source branch.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state">
                        <i class="fas fa-comments"></i>
                        <h3>No Shipments Found</h3>
                        <p>No shipments match your current filters.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if($shipments->hasPages()): ?>
                <div class="pagination-wrapper mt-4">
                    <?php echo e($shipments->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function addComment(event, shipmentId) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const comment = formData.get('comment');
    const commentType = formData.get('comment_type');
    
    if (!comment.trim() || !commentType) {
        alert('Please fill in all fields.');
        return;
    }
    
    // Add the comment to the list immediately (optimistic update)
    const commentsList = form.closest('.comments-section').querySelector('.comments-list');
    const commentItem = document.createElement('div');
    commentItem.className = 'comment-item';
    commentItem.innerHTML = `
        <div class="comment-header">
            <span class="comment-type ${commentType}">${commentType}</span>
            <span class="comment-meta">
                <i class="fas fa-user"></i> You • Just now
            </span>
        </div>
        <p class="comment-text">${comment}</p>
    `;
    
    commentsList.insertBefore(commentItem, commentsList.firstChild);
    
    // Clear the form
    form.reset();
    
    // Send to server
    fetch(`/admin/gaaubesi/${shipmentId}/add-comment`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            // Remove the comment if server request failed
            commentItem.remove();
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        commentItem.remove();
        alert('An error occurred while adding the comment.');
    });
}

// Auto-refresh comments every 30 seconds
setInterval(function() {
    // Could implement real-time comment updates here
}, 30000);
</script>
<?php $__env->stopPush(); ?>







<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/gaaubesi/comments.blade.php ENDPATH**/ ?>