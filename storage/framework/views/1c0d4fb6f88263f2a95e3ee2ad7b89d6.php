<?php $__env->startSection('title', $product->name . ' — ' . $tenant->business_name); ?>
<?php $__env->startSection('meta_description', \Illuminate\Support\Str::limit(strip_tags($product->description), 155)); ?>
<?php $__env->startSection('meta_keywords', $product->name . ', ' . ($product->category->name ?? '') . ', products, ' . $tenant->business_name); ?>
<?php $__env->startSection('og_title', $product->name); ?>
<?php $__env->startSection('og_description', \Illuminate\Support\Str::limit(strip_tags($product->description), 155)); ?>
<?php $__env->startSection('og_image', $product->primary_image_url); ?>
<?php $__env->startSection('og_url', \App\Helpers\StorefrontHelper::route('storefront.product', [$tenant->subdomain, $product->slug])); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain])); ?>">Home</a></li>
            <?php if($product->category): ?>
                <li class="breadcrumb-item"><a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.category', [$tenant->subdomain, $product->category->slug])); ?>"><?php echo e($product->category->name); ?></a></li>
            <?php endif; ?>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e($product->name); ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Product Images -->
        <div class="col-md-6">
            <div class="product-image-gallery">
                <div class="main-image-container">
                    <img src="<?php echo e($product->primary_image_url); ?>" class="main-image" alt="<?php echo e($product->name); ?>" id="mainImage">
                    <?php if($product->track_inventory && !$product->isInStock()): ?>
                        <div class="out-of-stock-overlay">
                            <span class="badge bg-danger">Out of Stock</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if(is_array($product->all_images_urls) && count($product->all_images_urls) > 1): ?>
                <div class="thumbnail-gallery">
                    <?php $__currentLoopData = $product->all_images_urls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $imageUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="thumbnail-item <?php echo e($index === ($product->primary_image_index ?? 0) ? 'active' : ''); ?>" 
                             onclick="changeMainImage('<?php echo e($imageUrl); ?>', this)">
                            <img src="<?php echo e($imageUrl); ?>" alt="Thumbnail <?php echo e($index + 1); ?>" loading="lazy">
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <div class="product-details">
                <h1 class="product-title"><?php echo e($product->name); ?></h1>
                
                <!-- Rating -->
                <?php if($avgRating): ?>
                <div class="product-rating mb-3">
                    <div class="stars">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <?php if($i <= floor($avgRating)): ?>
                                <i class="fas fa-star text-warning"></i>
                            <?php elseif($i - 0.5 <= $avgRating): ?>
                                <i class="fas fa-star-half-alt text-warning"></i>
                            <?php else: ?>
                                <i class="far fa-star text-warning"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <span class="rating-text">
                        <?php echo e($avgRating); ?> out of 5 stars
                        <a href="#reviews" class="text-decoration-none">(<?php echo e($reviews->total()); ?> reviews)</a>
                    </span>
                </div>
                <?php endif; ?>

                <!-- Price -->
                <div class="product-price mb-3">
                    <?php if($product->sale_price && $product->sale_price < $product->price): ?>
                        <span class="current-price">Rs. <?php echo e(number_format($product->sale_price, 2)); ?></span>
                        <span class="original-price">Rs. <?php echo e(number_format($product->price, 2)); ?></span>
                        <span class="discount-badge">Save Rs. <?php echo e(number_format($product->price - $product->sale_price, 2)); ?></span>
                    <?php else: ?>
                        <span class="current-price">Rs. <?php echo e(number_format($product->price, 2)); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Stock Status -->
                <?php if($product->track_inventory): ?>
                <div class="stock-status mb-3">
                    <?php if($product->isInStock()): ?>
                        <?php if($product->isLowStock()): ?>
                            <span class="badge bg-warning">
                                <i class="fas fa-exclamation-triangle"></i> Low Stock (<?php echo e($product->stock_quantity); ?> left)
                            </span>
                        <?php else: ?>
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle"></i> In Stock
                            </span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="badge bg-danger">
                            <i class="fas fa-times-circle"></i> Out of Stock
                        </span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Description -->
                <div class="product-description mb-4">
                    <h6>Description</h6>
                    <div class="description-content">
                        <?php echo nl2br(e($product->description)); ?>

                    </div>
                </div>

                <!-- Product Info -->
                <div class="product-info mb-4">
                    <div class="row">
                        <div class="col-6">
                            <div class="info-item">
                                <span class="info-label">SKU:</span>
                                <span class="info-value"><?php echo e($product->sku ?? 'N/A'); ?></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-item">
                                <span class="info-label">Category:</span>
                                <span class="info-value">
                                    <?php if($product->category): ?>
                                        <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.category', [$tenant->subdomain, $product->category->slug])); ?>" class="text-decoration-none">
                                            <?php echo e($product->category->name); ?>

                                        </a>
                                    <?php else: ?>
                                        Uncategorized
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add to Cart Form -->
                <form action="<?php echo e(route('storefront.cart.add', $tenant->subdomain)); ?>" method="post" class="add-to-cart-form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                    
                    <div class="quantity-section mb-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <div class="quantity-controls">
                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                            <input type="number" id="quantity" name="qty" value="1" min="1" max="<?php echo e($product->track_inventory ? $product->stock_quantity : 999); ?>" class="form-control quantity-input">
                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary btn-lg add-to-cart-btn js-add-to-cart" data-product-id="<?php echo e($product->id); ?>" <?php echo e(!$product->isInStock() ? 'disabled' : ''); ?>>
                            <i class="fas fa-shopping-cart"></i> 
                            <?php echo e($product->isInStock() ? 'Add to Cart' : 'Out of Stock'); ?>

                        </button>
                        
                        <button type="button" class="btn btn-outline-primary btn-lg wishlist-btn" title="Add to Wishlist">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </form>

                <!-- Product Features -->
                <div class="product-features mt-4">
                    <div class="feature-item">
                        <i class="fas fa-shipping-fast text-primary"></i>
                        <span>Free shipping on orders over Rs. 1000</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-undo text-primary"></i>
                        <span>30-day return policy</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-shield-alt text-primary"></i>
                        <span>Secure payment</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Reviews -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="reviews-section" id="reviews">
                <h3 class="section-title">Customer Reviews</h3>
                
                <?php if(auth()->guard()->check()): ?>
                <!-- Review Form -->
                <div class="review-form-section mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Write a Review</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo e(route('storefront.product.reviews.store', [$tenant->subdomain, $product->id])); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="rating" class="form-label">Rating *</label>
                                        <select name="rating" id="rating" class="form-select <?php $__errorArgs = ['rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="">Select a rating</option>
                                            <option value="5" <?php echo e(old('rating') == 5 ? 'selected' : ''); ?>>⭐⭐⭐⭐⭐ (5 Stars)</option>
                                            <option value="4" <?php echo e(old('rating') == 4 ? 'selected' : ''); ?>>⭐⭐⭐⭐☆ (4 Stars)</option>
                                            <option value="3" <?php echo e(old('rating') == 3 ? 'selected' : ''); ?>>⭐⭐⭐☆☆ (3 Stars)</option>
                                            <option value="2" <?php echo e(old('rating') == 2 ? 'selected' : ''); ?>>⭐⭐☆☆☆ (2 Stars)</option>
                                            <option value="1" <?php echo e(old('rating') == 1 ? 'selected' : ''); ?>>⭐☆☆☆☆ (1 Star)</option>
                                        </select>
                                        <?php $__errorArgs = ['rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="comment" class="form-label">Your Review *</label>
                                    <textarea name="comment" id="comment" rows="4" class="form-control <?php $__errorArgs = ['comment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              placeholder="Share your experience with this product..." required><?php echo e(old('comment')); ?></textarea>
                                    <?php $__errorArgs = ['comment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i>
                    Please <a href="<?php echo e(route('customer.login', $tenant->subdomain)); ?>">login</a> to leave a review.
                </div>
                <?php endif; ?>

                <!-- Reviews List -->
                <?php if($reviews->count() > 0): ?>
                    <div class="reviews-list">
                        <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar">
                                            <?php echo e(strtoupper(substr($review->user->first_name ?? 'A', 0, 1))); ?>

                                        </div>
                                        <div class="reviewer-details">
                                            <h6 class="reviewer-name"><?php echo e($review->user->first_name ?? 'Anonymous'); ?> <?php echo e($review->user->last_name ?? ''); ?></h6>
                                            <div class="review-rating">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <?php if($i <= $review->rating): ?>
                                                        <i class="fas fa-star text-warning"></i>
                                                    <?php else: ?>
                                                        <i class="far fa-star text-warning"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review-date">
                                        <?php echo e($review->created_at->diffForHumans()); ?>

                                    </div>
                                </div>
                                <div class="review-content">
                                    <p><?php echo e($review->comment); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <!-- Reviews Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($reviews->links('pagination::bootstrap-5')); ?>

                    </div>
                <?php else: ?>
                    <div class="no-reviews">
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h5>No reviews yet</h5>
                            <p class="text-muted">Be the first to review this product!</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.product-image-gallery {
    position: sticky;
    top: 2rem;
}

.main-image-container {
    position: relative;
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    background: var(--surface-color);
    margin-bottom: 1rem;
}

.main-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    transition: var(--transition);
}

.out-of-stock-overlay {
    position: absolute;
    top: 1rem;
    right: 1rem;
}

.thumbnail-gallery {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.thumbnail-item {
    width: 80px;
    height: 80px;
    border-radius: var(--border-radius-sm);
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: var(--transition);
}

.thumbnail-item.active {
    border-color: var(--primary-color);
}

.thumbnail-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-color);
    margin-bottom: 1rem;
    line-height: 1.2;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stars {
    display: flex;
    gap: 0.125rem;
}

.rating-text {
    color: var(--text-muted);
    font-size: 0.9rem;
}

.product-price {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.current-price {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
}

.original-price {
    font-size: 1.25rem;
    color: var(--text-muted);
    text-decoration: line-through;
}

.discount-badge {
    background: var(--accent-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius-sm);
    font-size: 0.875rem;
    font-weight: 600;
}

.stock-status .badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}

.product-description h6 {
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: var(--text-color);
}

.description-content {
    color: var(--text-muted);
    line-height: 1.6;
}

.product-info {
    background: var(--surface-color);
    padding: 1rem;
    border-radius: var(--border-radius-sm);
}

.info-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.info-label {
    font-weight: 600;
    color: var(--text-color);
}

.info-value {
    color: var(--text-muted);
}

.quantity-section {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.quantity-controls {
    display: flex;
    align-items: center;
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    overflow: hidden;
}

.quantity-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: var(--surface-color);
    color: var(--text-color);
    transition: var(--transition);
}

.quantity-btn:hover {
    background: var(--primary-color);
    color: white;
}

.quantity-input {
    width: 80px;
    height: 40px;
    border: none;
    text-align: center;
    font-weight: 600;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
}

.add-to-cart-btn {
    flex: 1;
    padding: 1rem 2rem;
    font-size: 1.1rem;
    font-weight: 600;
}

.wishlist-btn {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--border-radius-sm);
}

.product-features {
    background: var(--surface-color);
    padding: 1.5rem;
    border-radius: var(--border-radius-sm);
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.feature-item:last-child {
    margin-bottom: 0;
}

.feature-item i {
    width: 20px;
    text-align: center;
}

.reviews-section {
    margin-top: 3rem;
    padding-top: 3rem;
    border-top: 1px solid var(--border-color);
}

.review-form-section .card {
    border: none;
    box-shadow: var(--shadow-sm);
}

.review-item {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.reviewer-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.1rem;
}

.reviewer-name {
    margin: 0;
    font-weight: 600;
    color: var(--text-color);
}

.review-rating {
    margin-top: 0.25rem;
}

.review-date {
    color: var(--text-muted);
    font-size: 0.875rem;
}

.review-content p {
    margin: 0;
    color: var(--text-color);
    line-height: 1.6;
}

.no-reviews {
    background: var(--surface-color);
    border-radius: var(--border-radius-lg);
    border: 2px dashed var(--border-color);
}

@media (max-width: 768px) {
    .product-image-gallery {
        position: static;
    }
    
    .main-image {
        height: 300px;
    }
    
    .product-title {
        font-size: 1.5rem;
    }
    
    .current-price {
        font-size: 1.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .wishlist-btn {
        width: 100%;
        height: 50px;
    }
    
    .review-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .reviewer-info {
        width: 100%;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Image gallery functionality
function changeMainImage(imageUrl, thumbnail) {
    document.getElementById('mainImage').src = imageUrl;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail-item').forEach(item => {
        item.classList.remove('active');
    });
    thumbnail.classList.add('active');
}

// Quantity controls
document.addEventListener('click', function(e) {
    if (e.target.closest('.quantity-btn')) {
        e.preventDefault();
        const btn = e.target.closest('.quantity-btn');
        const action = btn.dataset.action;
        const input = btn.closest('.quantity-controls').querySelector('.quantity-input');
        
        let currentValue = parseInt(input.value);
        const maxValue = parseInt(input.getAttribute('max'));
        
        if (action === 'increase' && currentValue < maxValue) {
            input.value = currentValue + 1;
        } else if (action === 'decrease' && currentValue > 1) {
            input.value = currentValue - 1;
        }
    }
});

// Add to cart form - the storefront.js handler will catch button clicks
// This is just a fallback for form submission (e.g., pressing Enter)
document.addEventListener('DOMContentLoaded', function() {
    const addToCartForm = document.querySelector('.add-to-cart-form');
    if (!addToCartForm) return;
    
    // Flag to prevent double handling
    let isHandling = false;
    
    addToCartForm.addEventListener('submit', function(e) {
        // If the button click was already handled by storefront.js, don't handle again
        if (isHandling) {
            e.preventDefault();
            return;
        }
        
        e.preventDefault();
        isHandling = true;
        
        const form = this;
        const submitBtn = form.querySelector('.add-to-cart-btn');
        const quantityInput = form.querySelector('input[name="qty"]');
        const productId = form.querySelector('input[name="product_id"]').value;
        const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;
        
        // Use the addToCart function from storefront.js if available
        if (typeof addToCart === 'function') {
            addToCart(productId, quantity, submitBtn);
            isHandling = false;
        } else {
            // Fallback if storefront.js hasn't loaded yet
            setTimeout(() => {
                if (typeof addToCart === 'function') {
                    addToCart(productId, quantity, submitBtn);
                } else {
                    // Final fallback: direct AJAX call
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
                    submitBtn.disabled = true;
                    
                    const formData = new FormData(form);
                    
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            if (typeof showNotification === 'function') {
                                showNotification('Product added to cart!', 'success');
                            } else {
                                alert('Product added to cart!');
                            }
                            
                            // Update cart count if element exists
                            const cartCount = document.getElementById('cart-count');
                            if (cartCount) {
                                cartCount.textContent = data.cart_count || 0;
                            }
                            
                            // Add animation to button
                            submitBtn.style.transform = 'scale(1.05)';
                            setTimeout(() => {
                                submitBtn.style.transform = 'scale(1)';
                            }, 200);
                        } else {
                            if (typeof showNotification === 'function') {
                                showNotification(data.error || 'Failed to add product to cart', 'error');
                            } else {
                                alert(data.error || 'Failed to add product to cart');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (typeof showNotification === 'function') {
                            showNotification('An error occurred. Please try again.', 'error');
                        } else {
                            alert('An error occurred. Please try again.');
                        }
                    })
                    .finally(() => {
                        // Restore button state
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        isHandling = false;
                    });
                }
            }, 100);
        }
    });
});

// Wishlist functionality (placeholder)
document.querySelector('.wishlist-btn').addEventListener('click', function() {
    const icon = this.querySelector('i');
    const isActive = icon.classList.contains('fas');
    
    if (isActive) {
        icon.classList.remove('fas');
        icon.classList.add('far');
        showNotification('Removed from wishlist', 'info');
    } else {
        icon.classList.remove('far');
        icon.classList.add('fas');
        showNotification('Added to wishlist', 'success');
    }
});

// Smooth scroll to reviews
document.addEventListener('click', function(e) {
    if (e.target.matches('a[href="#reviews"]')) {
        e.preventDefault();
        document.getElementById('reviews').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.storefront', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/storefront/product.blade.php ENDPATH**/ ?>