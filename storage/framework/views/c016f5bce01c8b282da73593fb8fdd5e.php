<?php $__env->startSection('title', 'Shopping Cart — ' . $tenant->business_name); ?>
<?php $__env->startSection('meta_description', 'Review your shopping cart items and proceed to checkout'); ?>
<?php $__env->startSection('meta_keywords', 'cart, shopping, checkout, ' . $tenant->business_name); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain])); ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
        </ol>
    </nav>

    <?php
        // Initialize subtotal outside of conditional blocks
        $subtotal = 0;
        if (!empty($cart) && is_array($cart)) {
            foreach ($cart as $item) {
                if (isset($item['price']) && isset($item['quantity'])) {
                    $subtotal += $item['price'] * $item['quantity'];
                }
            }
        }
        
        // Calculate discount if coupon exists
        $discount = 0;
        if (isset($coupon) && is_array($coupon) && !empty($coupon)) {
            if (isset($coupon['type']) && $coupon['type'] === 'percentage') {
                $discount = round($subtotal * (($coupon['value'] ?? 0) / 100), 2);
            } else {
                $discount = min($coupon['value'] ?? 0, $subtotal);
            }
        }
    ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Shopping Cart</h1>
                <span class="text-muted"><?php echo e(count($cart ?? [])); ?> item(s)</span>
            </div>

            <?php if(empty($cart)): ?>
                <div class="empty-state">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Your cart is empty</h3>
                    <p>Looks like you haven't added any items to your cart yet.</p>
                    <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain])); ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            <?php else: ?>
                <div class="cart-items">
                    <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php ($itemSubtotal = $item['price'] * $item['quantity']); ?>
                        <div class="cart-item-card" data-product-id="<?php echo e($item['product_id']); ?>">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="cart-item-image">
                                        <?php if(!empty($item['image'])): ?>
                                            <img src="<?php echo e($item['image']); ?>" alt="<?php echo e($item['name']); ?>" class="img-fluid rounded">
                                        <?php else: ?>
                                            <div class="placeholder-image">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="cart-item-details">
                                        <h5 class="cart-item-name">
                                            <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.product', [$tenant->subdomain, $item['slug']])); ?>" class="text-decoration-none">
                                                <?php echo e($item['name']); ?>

                                            </a>
                                        </h5>
                                        <p class="cart-item-sku text-muted small">SKU: <?php echo e($item['sku'] ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="cart-item-price">
                                        <span class="price">Rs. <?php echo e(number_format($item['price'], 2)); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="cart-item-quantity">
                                        <form action="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.cart.update', [$tenant->subdomain])); ?>" method="post" class="quantity-form">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="product_id" value="<?php echo e($item['product_id']); ?>">
                                            <div class="input-group input-group-sm">
                                                <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                                <input type="number" name="qty" value="<?php echo e($item['quantity']); ?>" min="1" class="form-control text-center quantity-input">
                                                <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="cart-item-subtotal">
                                        <span class="subtotal">Rs. <?php echo e(number_format($itemSubtotal, 2)); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="cart-item-actions">
                                        <form action="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.cart.remove', [$tenant->subdomain])); ?>" method="post" class="d-inline remove-form">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="product_id" value="<?php echo e($item['product_id']); ?>">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-item" title="Remove item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Cart Actions -->
                <div class="cart-actions mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain])); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left"></i> Continue Shopping
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-outline-danger" id="clearCart">
                                <i class="fas fa-trash"></i> Clear Cart
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="order-summary">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <?php if(!empty($cart)): ?>
                            <div class="summary-row">
                                <span>Subtotal (<?php echo e(count($cart)); ?> items)</span>
                                <span id="cart-subtotal">Rs. <?php echo e(number_format($subtotal, 2)); ?></span>
                            </div>

                            <!-- Coupon Section -->
                            <div class="coupon-section mt-3">
                                <h6>Have a coupon?</h6>
                                <form action="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.coupon.apply', [$tenant->subdomain])); ?>" method="post" class="coupon-form">
                                    <?php echo csrf_field(); ?>
                                    <div class="input-group">
                                        <input type="text" name="coupon_code" class="form-control" placeholder="Enter coupon code" value="<?php echo e(isset($coupon) && is_array($coupon) ? ($coupon['code'] ?? '') : ''); ?>">
                                        <button type="submit" class="btn btn-outline-primary">Apply</button>
                                    </div>
                                    <?php $__errorArgs = ['coupon_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <?php if(session('coupon_error')): ?>
                                        <div class="text-danger small mt-1"><?php echo e(session('coupon_error')); ?></div>
                                    <?php endif; ?>
                                    <?php if(session('coupon_success')): ?>
                                        <div class="text-success small mt-1"><?php echo e(session('coupon_success')); ?></div>
                                    <?php endif; ?>
                                </form>
                                
                                <?php if(isset($coupon) && is_array($coupon) && !empty($coupon)): ?>
                                    <div class="applied-coupon mt-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-success">
                                                <i class="fas fa-check-circle"></i> <?php echo e($coupon['code'] ?? ''); ?>

                                            </span>
                                            <form action="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.coupon.remove', [$tenant->subdomain])); ?>" method="post" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Coupon">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if(isset($coupon) && is_array($coupon) && !empty($coupon) && $discount > 0): ?>
                                <div class="summary-row">
                                    <span>Discount (<?php echo e($coupon['code'] ?? ''); ?>)</span>
                                    <span class="text-success">- Rs. <?php echo e(number_format($discount, 2)); ?></span>
                                </div>
                                <div class="summary-row">
                                    <span>Total after discount</span>
                                    <span id="cart-total">Rs. <?php echo e(number_format($subtotal - $discount, 2)); ?></span>
                                </div>
                            <?php else: ?>
                                <div class="summary-row total-row">
                                    <span><strong>Total</strong></span>
                                    <span id="cart-total"><strong>Rs. <?php echo e(number_format($subtotal, 2)); ?></strong></span>
                                </div>
                            <?php endif; ?>

                            <div class="checkout-section mt-4">
                                <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.checkout', [$tenant->subdomain])); ?>" class="btn btn-success w-100 btn-lg">
                                    <i class="fas fa-credit-card"></i> Proceed to Checkout
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted">
                                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                <p>Your cart is empty</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Security Badge -->
                <div class="security-badge mt-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-shield-alt text-success fa-2x mb-2"></i>
                            <h6>Secure Checkout</h6>
                            <p class="small text-muted mb-0">Your payment information is encrypted and secure</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.cart-item-card {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: var(--transition);
}

.cart-item-card:hover {
    box-shadow: var(--shadow-md);
}

.cart-item-image {
    width: 80px;
    height: 80px;
    border-radius: var(--border-radius-sm);
    overflow: hidden;
    background: var(--surface-color);
    display: flex;
    align-items: center;
    justify-content: center;
}

.cart-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-image {
    color: var(--text-muted);
    font-size: 1.5rem;
}

.cart-item-name {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.cart-item-name a {
    color: var(--text-color);
}

.cart-item-name a:hover {
    color: var(--primary-color);
}

.cart-item-price .price {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--primary-color);
}

.quantity-form .input-group {
    width: 120px;
}

.quantity-btn {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border-color);
}

.quantity-input {
    border-left: none;
    border-right: none;
    text-align: center;
    font-weight: 600;
}

.cart-item-subtotal .subtotal {
    font-weight: 600;
    color: var(--text-color);
}

.remove-item {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.order-summary .card {
    border: none;
    box-shadow: var(--shadow-md);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-color);
}

.summary-row:last-child {
    border-bottom: none;
}

.total-row {
    font-size: 1.1rem;
    padding-top: 1rem;
    margin-top: 1rem;
    border-top: 2px solid var(--border-color);
}

.coupon-section {
    padding: 1rem;
    background: var(--surface-color);
    border-radius: var(--border-radius-sm);
    border: 1px solid var(--border-color);
}

.applied-coupon {
    padding: 0.75rem;
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-radius: var(--border-radius-sm);
}

.security-badge .card {
    border: 1px solid rgba(16, 185, 129, 0.2);
    background: rgba(16, 185, 129, 0.05);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--surface-color);
    border-radius: var(--border-radius-lg);
    border: 2px dashed var(--border-color);
}

.empty-state i {
    font-size: 4rem;
    color: var(--text-muted);
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: var(--text-color);
    margin-bottom: 1rem;
}

.empty-state p {
    color: var(--text-muted);
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .cart-item-card .row > div {
        margin-bottom: 1rem;
    }
    
    .cart-item-image {
        width: 60px;
        height: 60px;
    }
    
    .quantity-form .input-group {
        width: 100px;
    }
    
    .summary-row {
        font-size: 0.9rem;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Quantity controls
document.addEventListener('click', function(e) {
    if (e.target.closest('.quantity-btn')) {
        e.preventDefault();
        const btn = e.target.closest('.quantity-btn');
        const action = btn.dataset.action;
        const input = btn.closest('.quantity-form').querySelector('.quantity-input');
        const form = btn.closest('.quantity-form');
        
        let currentValue = parseInt(input.value);
        
        if (action === 'increase') {
            input.value = currentValue + 1;
        } else if (action === 'decrease' && currentValue > 1) {
            input.value = currentValue - 1;
        }
        
        // Auto-submit form
        form.submit();
    }
});

// Remove item confirmation
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-item')) {
        e.preventDefault();
        const btn = e.target.closest('.remove-item');
        const form = btn.closest('.remove-form');
        
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            form.submit();
        }
    }
});

// Clear cart confirmation
document.getElementById('clearCart')?.addEventListener('click', function() {
    if (confirm('Are you sure you want to clear your entire cart?')) {
        // This would need to be implemented in the controller
        fetch('<?php echo e(\App\Helpers\StorefrontHelper::route("storefront.cart.clear", [$tenant->subdomain])); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to clear cart. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
});

// Coupon form handling
document.querySelector('.coupon-form')?.addEventListener('submit', function(e) {
    const input = this.querySelector('input[name="coupon_code"]');
    if (!input.value.trim()) {
        e.preventDefault();
        input.focus();
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Applying...';
    submitBtn.disabled = true;
    
    // Reset button after form submission
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 2000);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.storefront', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/storefront/cart.blade.php ENDPATH**/ ?>