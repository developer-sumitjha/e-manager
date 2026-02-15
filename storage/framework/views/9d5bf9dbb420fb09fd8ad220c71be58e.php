<?php $__env->startSection('title', 'All Products — ' . $tenant->business_name); ?>
<?php $__env->startSection('meta_description', 'Browse all products at ' . $tenant->business_name); ?>
<?php $__env->startSection('meta_keywords', 'products, shop, ' . $tenant->business_name); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain])); ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">All Products</li>
        </ol>
    </nav>

    <!-- Archive Header -->
    <div class="category-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="category-title">All Products</h1>
                <p class="category-description">Browse our complete collection of products</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="category-stats">
                    <span class="badge bg-primary"><?php echo e($products->total()); ?> products</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Sort -->
    <div class="filters-section mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <form action="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.dynamic', [$tenant->subdomain, $archiveSlug])); ?>" method="GET" class="search-form">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="Search products..." value="<?php echo e($search); ?>">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end gap-2">
                            <form action="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.dynamic', [$tenant->subdomain, $archiveSlug])); ?>" method="GET" class="d-inline-block">
                                <?php if($search): ?>
                                    <input type="hidden" name="q" value="<?php echo e($search); ?>">
                                <?php endif; ?>
                                <?php if(request('category')): ?>
                                    <input type="hidden" name="category" value="<?php echo e(request('category')); ?>">
                                <?php endif; ?>
                                <select name="sort" onchange="this.form.submit()" class="form-select form-select-sm d-inline-block w-auto">
                                    <option value="new" <?php echo e($sort == 'new' ? 'selected' : ''); ?>>Newest</option>
                                    <option value="price_asc" <?php echo e($sort == 'price_asc' ? 'selected' : ''); ?>>Price: Low to High</option>
                                    <option value="price_desc" <?php echo e($sort == 'price_desc' ? 'selected' : ''); ?>>Price: High to Low</option>
                                    <option value="name_asc" <?php echo e($sort == 'name_asc' ? 'selected' : ''); ?>>Name: A to Z</option>
                                    <option value="name_desc" <?php echo e($sort == 'name_desc' ? 'selected' : ''); ?>>Name: Z to A</option>
                                </select>
                            </form>
                            
                            <div class="view-toggle">
                                <button class="btn btn-outline-secondary btn-sm active" data-view="grid" title="Grid View">
                                    <i class="fas fa-th"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" data-view="list" title="List View">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Category Filter -->
                <?php if($categories->count() > 0): ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="category-filter">
                            <label class="form-label mb-2">Filter by Category:</label>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.dynamic', [$tenant->subdomain, $archiveSlug])); ?><?php echo e($search ? '?q=' . urlencode($search) : ''); ?>" 
                                   class="badge <?php echo e(!request('category') ? 'bg-primary' : 'bg-secondary'); ?> text-decoration-none">
                                    All Categories
                                </a>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.dynamic', [$tenant->subdomain, $archiveSlug])); ?>?category=<?php echo e($category->id); ?><?php echo e($search ? '&q=' . urlencode($search) : ''); ?>" 
                                   class="badge <?php echo e(request('category') == $category->id ? 'bg-primary' : 'bg-secondary'); ?> text-decoration-none">
                                    <?php echo e($category->name); ?>

                                </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <?php if($products->count() > 0): ?>
        <!-- Search Results Info -->
        <?php if($search): ?>
        <div class="search-results-info mb-3">
            <div class="alert alert-info">
                <i class="fas fa-search"></i>
                Showing <?php echo e($products->count()); ?> of <?php echo e($products->total()); ?> results for "<strong><?php echo e($search); ?></strong>"
                <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.dynamic', [$tenant->subdomain, $archiveSlug])); ?>" class="btn btn-sm btn-outline-primary ms-2">
                    Clear Search
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Products Grid/List -->
        <div class="products-container">
            <div class="products-grid" id="productsGrid">
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="product-card" data-product-id="<?php echo e($product->id); ?>">
                    <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.product', [$tenant->subdomain, $product->slug])); ?>" class="product-link">
                        <div class="product-image<?php echo e($settings->show_quick_view ?? false ? ' js-quick-view' : ''); ?>" style="cursor:pointer" title="<?php echo e($settings->show_quick_view ?? false ? 'Quick view' : ''); ?>">
                            <?php if($product->primary_image_url): ?>
                                <img src="<?php echo e($product->primary_image_url); ?>" alt="<?php echo e($product->name); ?>" loading="lazy">
                            <?php else: ?>
                                <i class="fas fa-image"></i>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <div class="product-name"><?php echo e($product->name); ?></div>
                            <div class="product-price">
                                <?php if($product->sale_price && $product->sale_price < $product->price): ?>
                                    <span class="current-price">$<?php echo e(number_format($product->sale_price, 2)); ?></span>
                                    <span class="original-price">$<?php echo e(number_format($product->price, 2)); ?></span>
                                <?php else: ?>
                                    <span class="current-price">$<?php echo e(number_format($product->price, 2)); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if($product->category): ?>
                                <div class="product-category text-muted small"><?php echo e($product->category->name); ?></div>
                            <?php endif; ?>
                        </div>
                    </a>
                    <?php if($settings->show_add_to_cart_button ?? true): ?>
                    <div class="product-actions">
                        <button class="add-to-cart-btn js-add-to-cart" data-product-id="<?php echo e($product->id); ?>" 
                                <?php echo e(!$product->isInStock() ? 'disabled' : ''); ?>>
                            <i class="fas fa-shopping-cart"></i>
                            <?php echo e($product->isInStock() ? 'Add to Cart' : 'Out of Stock'); ?>

                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($products->links('pagination::bootstrap-5')); ?>

        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="empty-state text-center py-5">
            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
            <h3>No products found</h3>
            <?php if($search): ?>
                <p class="text-muted">No products match your search "<strong><?php echo e($search); ?></strong>"</p>
                <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.dynamic', [$tenant->subdomain, $archiveSlug])); ?>" class="btn btn-primary">
                    View All Products
                </a>
            <?php else: ?>
                <p class="text-muted">There are no products available at the moment.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.category-header {
    padding: 1.5rem 0;
    border-bottom: 2px solid var(--border-color);
}

.category-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-color);
    margin-bottom: 0.5rem;
}

.category-description {
    color: var(--text-muted);
    font-size: 1.1rem;
    margin-bottom: 0;
}

.category-stats .badge {
    font-size: 1rem;
    padding: 0.5rem 1rem;
}

.filters-section .card {
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
}

.category-filter .badge {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    cursor: pointer;
    transition: var(--transition);
}

.category-filter .badge:hover {
    opacity: 0.8;
    transform: translateY(-2px);
}

.empty-state {
    padding: 4rem 2rem;
}

.empty-state i {
    opacity: 0.3;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// View toggle functionality
document.querySelectorAll('.view-toggle button').forEach(btn => {
    btn.addEventListener('click', function() {
        const view = this.dataset.view;
        const grid = document.getElementById('productsGrid');
        
        // Update active button
        document.querySelectorAll('.view-toggle button').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Toggle view class
        if (view === 'list') {
            grid.classList.add('list-view');
        } else {
            grid.classList.remove('list-view');
        }
        
        // Store preference
        localStorage.setItem('productView', view);
    });
});

// Restore view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('productView');
    if (savedView) {
        const btn = document.querySelector(`[data-view="${savedView}"]`);
        if (btn) {
            btn.click();
        }
    }
});

// Add to cart functionality
document.querySelectorAll('.js-add-to-cart').forEach(btn => {
    btn.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const btn = this;
        const originalText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        
        fetch('<?php echo e(\App\Helpers\StorefrontHelper::route("storefront.cart.add", [$tenant->subdomain])); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                btn.innerHTML = '<i class="fas fa-check"></i> Added!';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 2000);
                
                // Update cart count if element exists
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cart_count || 0;
                }
            } else {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    alert(data.message || 'Failed to add to cart');
                }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('An error occurred. Please try again.');
        });
    });
});

// Quick view functionality (if enabled)
<?php if($settings->show_quick_view ?? false): ?>
document.querySelectorAll('.js-quick-view').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        const productCard = this.closest('.product-card');
        const productLink = productCard.querySelector('.product-link');
        if (productLink) {
            window.location.href = productLink.href;
        }
    });
});
<?php endif; ?>
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.storefront', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/storefront/products-archive.blade.php ENDPATH**/ ?>