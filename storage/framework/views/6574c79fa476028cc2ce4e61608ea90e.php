<?php $__env->startSection('title', $category->name . ' — ' . $tenant->business_name); ?>
<?php $__env->startSection('meta_description', 'Browse ' . $category->name . ' products at ' . $tenant->business_name); ?>
<?php $__env->startSection('meta_keywords', $category->name . ', products, ' . $tenant->business_name); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain])); ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e($category->name); ?></li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="category-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="category-title"><?php echo e($category->name); ?></h1>
                <?php if($category->description): ?>
                    <p class="category-description"><?php echo e($category->description); ?></p>
                <?php endif; ?>
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
                        <form action="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.category', [$tenant->subdomain, $category->slug])); ?>" method="GET" class="search-form">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="Search in <?php echo e($category->name); ?>..." value="<?php echo e($search); ?>">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end gap-2">
                            <form action="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.category', [$tenant->subdomain, $category->slug])); ?>" method="GET" class="d-inline-block">
                                <?php if($search): ?>
                                    <input type="hidden" name="q" value="<?php echo e($search); ?>">
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
                Showing <?php echo e($products->count()); ?> of <?php echo e($products->total()); ?> results for "<strong><?php echo e($search); ?></strong>" in <?php echo e($category->name); ?>

                <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.category', [$tenant->subdomain, $category->slug])); ?>" class="btn btn-sm btn-outline-primary ms-2">
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
                    <div class="product-image js-quick-view" style="cursor:pointer" title="Quick view">
                        <?php if($product->primary_image_url): ?>
                            <img src="<?php echo e($product->primary_image_url); ?>" alt="<?php echo e($product->name); ?>" loading="lazy">
                        <?php else: ?>
                            <i class="fas fa-image"></i>
                        <?php endif; ?>
                        
                        <?php if($product->track_inventory && !$product->isInStock()): ?>
                            <div class="out-of-stock-overlay">
                                <span class="badge bg-danger">Out of Stock</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-info">
                        <div class="product-name"><?php echo e($product->name); ?></div>
                        <div class="product-price">
                            <?php if($product->sale_price && $product->sale_price < $product->price): ?>
                                <span class="current-price">Rs. <?php echo e(number_format($product->sale_price, 2)); ?></span>
                                <span class="original-price">Rs. <?php echo e(number_format($product->price, 2)); ?></span>
                            <?php else: ?>
                                <span class="current-price">Rs. <?php echo e(number_format($product->price, 2)); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if($product->track_inventory): ?>
                            <div class="stock-status mb-2">
                                <?php if($product->isInStock()): ?>
                                    <?php if($product->isLowStock()): ?>
                                        <span class="badge bg-warning">Low Stock</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">In Stock</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-danger">Out of Stock</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <button class="add-to-cart-btn js-add-to-cart" data-product-id="<?php echo e($product->id); ?>" <?php echo e(!$product->isInStock() ? 'disabled' : ''); ?>>
                            <i class="fas fa-shopping-cart"></i> 
                            <?php echo e($product->isInStock() ? 'Add to Cart' : 'Out of Stock'); ?>

                        </button>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($products->links('pagination::bootstrap-5')); ?>

        </div>

        <!-- Results Summary -->
        <div class="results-summary text-center mt-3">
            <p class="text-muted">
                <?php if($search): ?>
                    Showing <?php echo e($products->count()); ?> of <?php echo e($products->total()); ?> results for "<?php echo e($search); ?>" in <?php echo e($category->name); ?>

                <?php else: ?>
                    Showing <?php echo e($products->count()); ?> of <?php echo e($products->total()); ?> products in <?php echo e($category->name); ?>

                <?php endif; ?>
            </p>
        </div>
    <?php else: ?>
        <!-- No Products Found -->
        <div class="no-products">
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>No products found</h3>
                <?php if($search): ?>
                    <p>No products match your search criteria "<strong><?php echo e($search); ?></strong>" in <?php echo e($category->name); ?>.</p>
                    <div class="empty-state-actions">
                        <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.category', [$tenant->subdomain, $category->slug])); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-times"></i> Clear Search
                        </a>
                        <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain])); ?>" class="btn btn-primary">
                            <i class="fas fa-home"></i> Browse All Products
                        </a>
                    </div>
                <?php else: ?>
                    <p>There are no products available in <?php echo e($category->name); ?> at the moment.</p>
                    <div class="empty-state-actions">
                        <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain])); ?>" class="btn btn-primary">
                            <i class="fas fa-home"></i> Browse All Products
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.category-header {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    padding: 2rem;
    border-radius: var(--border-radius-lg);
    border: 1px solid rgba(102, 126, 234, 0.2);
}

.category-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-color);
    margin-bottom: 0.5rem;
}

.category-description {
    font-size: 1.1rem;
    color: var(--text-muted);
    margin: 0;
}

.category-stats .badge {
    font-size: 1rem;
    padding: 0.75rem 1.5rem;
}

.filters-section .card {
    border: none;
    box-shadow: var(--shadow-sm);
}

.search-form .input-group {
    max-width: 400px;
}

.view-toggle {
    display: flex;
    gap: 0.25rem;
}

.view-toggle .btn {
    padding: 0.5rem 0.75rem;
}

.view-toggle .btn.active {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.products-container {
    margin-bottom: 2rem;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
}

.products-grid.list-view {
    grid-template-columns: 1fr;
    gap: 1rem;
}

.products-grid.list-view .product-card {
    display: flex;
    flex-direction: row;
    align-items: center;
    padding: 1rem;
}

.products-grid.list-view .product-image {
    width: 150px;
    aspect-ratio: 1 / 1;
    flex-shrink: 0;
    margin-right: 1rem;
}

.products-grid.list-view .product-info {
    flex: 1;
    padding: 0;
}

.product-card {
    background: white;
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    cursor: pointer;
    border: 1px solid var(--border-color);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.product-image {
    width: 100%;
    aspect-ratio: 1 / 1;
    background: var(--surface-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: var(--text-muted);
    position: relative;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    max-height: 100%;
    object-fit: contain;
    object-position: center;
    transition: var(--transition);
    display: block;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.out-of-stock-overlay {
    position: absolute;
    top: 1rem;
    right: 1rem;
}

.product-info {
    padding: 1.5rem;
}

.product-name {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    color: var(--text-color);
    line-height: 1.4;
}

.product-price {
    margin-bottom: 1rem;
}

.current-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary-color);
}

.original-price {
    font-size: 1rem;
    color: var(--text-muted);
    text-decoration: line-through;
    margin-left: 0.5rem;
}

.stock-status .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius-sm);
}

.add-to-cart-btn {
    width: 100%;
    padding: 0.75rem;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--border-radius-sm);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.add-to-cart-btn:hover:not(:disabled) {
    background: var(--secondary-color);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.add-to-cart-btn:disabled {
    background: var(--text-muted);
    cursor: not-allowed;
    opacity: 0.6;
}

.search-results-info .alert {
    border: none;
    background: rgba(59, 130, 246, 0.1);
    color: var(--text-color);
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

.empty-state-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.results-summary {
    padding: 1rem;
    background: var(--surface-color);
    border-radius: var(--border-radius-sm);
    border: 1px solid var(--border-color);
}

@media (max-width: 768px) {
    .category-title {
        font-size: 2rem;
    }
    
    .filters-section .row {
        flex-direction: column;
        gap: 1rem;
    }
    
    .filters-section .col-md-6 {
        width: 100%;
    }
    
    .search-form .input-group {
        max-width: 100%;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .products-grid.list-view .product-card {
        flex-direction: column;
        text-align: center;
    }
    
    .products-grid.list-view .product-image {
        width: 100%;
        aspect-ratio: 1 / 1;
        margin-right: 0;
        margin-bottom: 1rem;
    }
    
    .empty-state-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .empty-state-actions .btn {
        width: 100%;
        max-width: 300px;
    }
}

@media (max-width: 480px) {
    .category-header {
        padding: 1.5rem;
    }
    
    .category-title {
        font-size: 1.75rem;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
    
    .product-info {
        padding: 1rem;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// View toggle functionality
document.querySelectorAll('.view-toggle .btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const view = this.dataset.view;
        const productsGrid = document.getElementById('productsGrid');
        
        // Update active button
        document.querySelectorAll('.view-toggle .btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Update grid class
        if (view === 'list') {
            productsGrid.classList.add('list-view');
        } else {
            productsGrid.classList.remove('list-view');
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

// Enhanced search with debouncing
const searchInput = document.querySelector('input[name="q"]');
if (searchInput) {
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (this.value.length > 2) {
                this.closest('form').submit();
            }
        }, 500);
    });
}

// Product card interactions
document.addEventListener('click', function(e) {
    if (e.target.closest('.js-quick-view')) {
        e.preventDefault();
        const card = e.target.closest('.product-card');
        const productId = card.dataset.productId;
        
        if (productId) {
            // Redirect to product page for now
            window.location.href = `/storefront/<?php echo e($tenant->subdomain); ?>/product/${productId}`;
        }
    }
});

// Add to cart functionality (inherited from main storefront.js)
// The addToCart function is already defined in the main storefront.js file
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.storefront', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/storefront/category.blade.php ENDPATH**/ ?>