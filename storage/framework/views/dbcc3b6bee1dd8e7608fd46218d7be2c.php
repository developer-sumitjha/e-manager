<?php $__env->startSection('title', 'Order Placed — ' . $tenant->business_name); ?>
<?php $__env->startSection('meta_description', 'Thank you for your order! Your order has been successfully placed.'); ?>
<?php $__env->startSection('meta_keywords', 'order success, thank you, ' . $tenant->business_name); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="order-success text-center mb-5">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="success-title">Thank you for your order!</h1>
                <p class="success-message">Your order has been successfully placed and is being processed.</p>
                
                <?php if($order): ?>
                    <div class="order-details">
                        <div class="order-number">
                            <strong>Order Number:</strong> <?php echo e($order->order_number); ?>

                        </div>
                        <div class="order-date">
                            <strong>Order Date:</strong> <?php echo e($order->created_at->format('F j, Y \a\t g:i A')); ?>

                        </div>
                        <div class="order-total">
                            <strong>Total Amount:</strong> Rs. <?php echo e(number_format($order->total, 2)); ?>

                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Order Summary -->
            <?php if($order): ?>
            <div class="order-summary-card mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-receipt"></i> Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <!-- Order Items -->
                        <div class="order-items">
                            <?php $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="order-item">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <div class="item-image">
                                            <?php if($item->product->primary_image_url): ?>
                                                <img src="<?php echo e($item->product->primary_image_url); ?>" alt="<?php echo e($item->product->name); ?>" class="img-fluid rounded">
                                            <?php else: ?>
                                                <div class="placeholder-image">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="item-details">
                                            <h6 class="item-name"><?php echo e($item->product->name); ?></h6>
                                            <p class="item-sku text-muted small">SKU: <?php echo e($item->product->sku ?? 'N/A'); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="item-quantity">
                                            <span class="quantity">Qty: <?php echo e($item->quantity); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="item-price">
                                            <span class="price">Rs. <?php echo e(number_format($item->total, 2)); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <hr>

                        <!-- Order Totals -->
                        <div class="order-totals">
                            <div class="total-row">
                                <span>Subtotal:</span>
                                <span>Rs. <?php echo e(number_format($order->subtotal, 2)); ?></span>
                            </div>
                            
                            <?php if($order->discount_total > 0): ?>
                            <div class="total-row">
                                <span>Discount:</span>
                                <span class="text-success">- Rs. <?php echo e(number_format($order->discount_total, 2)); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="total-row">
                                <span>Shipping:</span>
                                <span>Rs. <?php echo e(number_format($order->shipping_cost, 2)); ?></span>
                            </div>
                            
                            <div class="total-row">
                                <span>Tax:</span>
                                <span>Rs. <?php echo e(number_format($order->tax_amount, 2)); ?></span>
                            </div>
                            
                            <hr>
                            
                            <div class="total-row total-final">
                                <span><strong>Total:</strong></span>
                                <span><strong>Rs. <?php echo e(number_format($order->total, 2)); ?></strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="shipping-info-card mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-truck"></i> Shipping Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Shipping Address</h6>
                                <address class="shipping-address">
                                    <?php echo e($order->shipping_first_name); ?> <?php echo e($order->shipping_last_name); ?><br>
                                    <?php echo e($order->shipping_address); ?><br>
                                    <?php echo e($order->shipping_city); ?>, <?php echo e($order->shipping_state); ?> <?php echo e($order->shipping_postal_code); ?><br>
                                    <?php echo e($order->shipping_country); ?>

                                </address>
                            </div>
                            <div class="col-md-6">
                                <h6>Contact Information</h6>
                                <div class="contact-info">
                                    <p><i class="fas fa-envelope"></i> <?php echo e($order->shipping_email); ?></p>
                                    <p><i class="fas fa-phone"></i> <?php echo e($order->shipping_phone); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="payment-info-card mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-credit-card"></i> Payment Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="payment-method">
                                    <strong>Payment Method:</strong> 
                                    <?php switch($order->payment_method):
                                        case ('cod'): ?>
                                            <span class="badge bg-warning">Cash on Delivery</span>
                                            <?php break; ?>
                                        <?php case ('esewa'): ?>
                                            <span class="badge bg-primary">eSewa</span>
                                            <?php break; ?>
                                        <?php case ('khalti'): ?>
                                            <span class="badge bg-success">Khalti</span>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <span class="badge bg-secondary"><?php echo e(ucfirst($order->payment_method)); ?></span>
                                    <?php endswitch; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payment-status">
                                    <strong>Payment Status:</strong> 
                                    <span class="badge bg-<?php echo e($order->payment_status === 'paid' ? 'success' : 'warning'); ?>">
                                        <?php echo e(ucfirst($order->payment_status)); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Next Steps -->
            <div class="next-steps-card mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> What's Next?</h5>
                    </div>
                    <div class="card-body">
                        <div class="steps-list">
                            <div class="step-item">
                                <div class="step-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="step-content">
                                    <h6>Order Confirmation</h6>
                                    <p>You will receive an email confirmation shortly with your order details.</p>
                                </div>
                            </div>
                            
                            <div class="step-item">
                                <div class="step-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="step-content">
                                    <h6>Order Processing</h6>
                                    <p>We will prepare your order for shipment within 1-2 business days.</p>
                                </div>
                            </div>
                            
                            <div class="step-item">
                                <div class="step-icon">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="step-content">
                                    <h6>Shipping</h6>
                                    <p>Your order will be shipped and you'll receive tracking information.</p>
                                </div>
                            </div>
                            
                            <?php if($order && $order->payment_method === 'cod'): ?>
                            <div class="step-item">
                                <div class="step-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="step-content">
                                    <h6>Payment on Delivery</h6>
                                    <p>Please have the exact amount ready for the delivery person.</p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons text-center">
                <a href="<?php echo e(route('storefront.preview', $tenant->subdomain)); ?>" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
                
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('customer.orders', $tenant->subdomain)); ?>" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-list"></i> View My Orders
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('customer.login', $tenant->subdomain)); ?>" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-sign-in-alt"></i> Login to Track Order
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.order-success {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(102, 126, 234, 0.1));
    padding: 3rem 2rem;
    border-radius: var(--border-radius-lg);
    border: 2px solid rgba(16, 185, 129, 0.2);
}

.success-icon {
    margin-bottom: 1.5rem;
}

.success-icon i {
    font-size: 4rem;
    color: var(--success-color);
    animation: bounce 1s ease-in-out;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

.success-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-color);
    margin-bottom: 1rem;
}

.success-message {
    font-size: 1.25rem;
    color: var(--text-muted);
    margin-bottom: 2rem;
}

.order-details {
    background: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-sm);
    text-align: left;
    max-width: 400px;
    margin: 0 auto;
}

.order-details > div {
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border-color);
}

.order-details > div:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.order-summary-card .card,
.shipping-info-card .card,
.payment-info-card .card,
.next-steps-card .card {
    border: none;
    box-shadow: var(--shadow-md);
}

.order-item {
    padding: 1rem 0;
    border-bottom: 1px solid var(--border-color);
}

.order-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 60px;
    height: 60px;
    border-radius: var(--border-radius-sm);
    overflow: hidden;
    background: var(--surface-color);
    display: flex;
    align-items: center;
    justify-content: center;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-image {
    color: var(--text-muted);
    font-size: 1.25rem;
}

.item-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--text-color);
}

.item-price .price {
    font-weight: 600;
    color: var(--primary-color);
}

.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
}

.total-final {
    font-size: 1.1rem;
    padding-top: 1rem;
    margin-top: 1rem;
    border-top: 2px solid var(--border-color);
}

.shipping-address {
    font-style: normal;
    line-height: 1.6;
    color: var(--text-color);
}

.contact-info p {
    margin-bottom: 0.5rem;
    color: var(--text-color);
}

.contact-info i {
    width: 20px;
    color: var(--primary-color);
}

.steps-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.step-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.step-content h6 {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text-color);
}

.step-content p {
    color: var(--text-muted);
    margin: 0;
    line-height: 1.5;
}

.action-buttons {
    margin-top: 3rem;
}

@media (max-width: 768px) {
    .success-title {
        font-size: 2rem;
    }
    
    .success-message {
        font-size: 1rem;
    }
    
    .order-details {
        padding: 1.5rem;
    }
    
    .steps-list {
        gap: 1rem;
    }
    
    .step-item {
        flex-direction: column;
        text-align: center;
    }
    
    .step-icon {
        margin: 0 auto;
    }
    
    .action-buttons .btn {
        display: block;
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .action-buttons .btn:last-child {
        margin-bottom: 0;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Add some interactive elements
document.addEventListener('DOMContentLoaded', function() {
    // Animate success icon
    const successIcon = document.querySelector('.success-icon i');
    if (successIcon) {
        setTimeout(() => {
            successIcon.style.transform = 'scale(1.1)';
            setTimeout(() => {
                successIcon.style.transform = 'scale(1)';
            }, 200);
        }, 500);
    }
    
    // Add fade-in animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 200 * (index + 1));
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.storefront', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/storefront/checkout-success.blade.php ENDPATH**/ ?>