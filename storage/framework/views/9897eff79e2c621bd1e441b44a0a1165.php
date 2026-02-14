<?php $__env->startSection('title', $settings->meta_title ?? ($settings->site_name ?? $tenant->business_name)); ?>
<?php $__env->startSection('meta_description', $settings->meta_description ?? \Illuminate\Support\Str::limit($settings->site_description ?? ($tenant->business_name . ' online store'), 155)); ?>
<?php $__env->startSection('meta_keywords', $settings->meta_keywords ?? 'store, shop, ecommerce'); ?>
<?php $__env->startSection('og_title', $settings->site_name ?? $tenant->business_name); ?>
<?php $__env->startSection('og_description', $settings->meta_description ?? ''); ?>
<?php $__env->startSection('og_image', $settings->logo_url ?? ''); ?>
<?php $__env->startSection('og_url', route('storefront.preview', $tenant->subdomain)); ?>

<?php $__env->startPush('styles'); ?>
<style>
    :root {
        --primary-color: <?php echo e($settings->primary_color ?? '#667eea'); ?>;
        --secondary-color: <?php echo e($settings->secondary_color ?? '#764ba2'); ?>;
        --accent-color: <?php echo e($settings->accent_color ?? '#10b981'); ?>;
        --text-color: <?php echo e($settings->text_color ?? '#1e293b'); ?>;
        --background-color: <?php echo e($settings->background_color ?? '#ffffff'); ?>;
        --header-bg-color: <?php echo e($settings->header_bg_color ?? '#ffffff'); ?>;
        --footer-bg-color: <?php echo e($settings->footer_bg_color ?? '#1e293b'); ?>;
        --font-family: <?php echo e($settings->font_family ?? 'Inter'); ?>, sans-serif;
        --heading-font: <?php echo e($settings->heading_font ?? 'Inter'); ?>, sans-serif;
        --font-size: <?php echo e($settings->font_size ?? 16); ?>px;
    }
    
    /* Hero Banner */
    .hero-banner { 
        display: <?php echo e(($settings->show_banner ?? true) && !($settings->additional_settings['show_hero_slides'] ?? false) ? 'block' : 'none'); ?>; 
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); 
        color: white; 
        padding: 4rem 2rem; 
        text-align: center; 
        position: relative; 
        overflow: hidden; 
        margin-bottom: 3rem;
    }
    
    <?php if($settings->banner_image): ?>
    .hero-banner { 
        background-image: url('<?php echo e($settings->banner_image_url ?? asset('storage/' . $settings->banner_image)); ?>'); 
        background-size: cover; 
        background-position: center; 
    }
    .hero-banner::before { 
        content: ''; 
        position: absolute; 
        top: 0; 
        left: 0; 
        right: 0; 
        bottom: 0; 
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.8), rgba(118, 75, 162, 0.8)); 
    }
    <?php endif; ?>
    
    .hero-banner .content { 
        position: relative; 
        z-index: 2; 
        max-width: 800px; 
        margin: 0 auto; 
    }
    
    .hero-banner h1 { 
        font-size: 3rem; 
        font-weight: 800; 
        margin-bottom: 1rem; 
        line-height: 1.1;
    }
    
    .hero-banner p { 
        font-size: 1.25rem; 
        margin-bottom: 2rem; 
        opacity: 0.95; 
        line-height: 1.6;
    }
    
    /* Hero Slides Carousel */
    .hero-slides-carousel {
        display: <?php echo e(($settings->additional_settings['show_hero_slides'] ?? false) ? 'block' : 'none'); ?>;
        margin-bottom: 3rem;
        position: relative;
        overflow: visible;
    }
    
    .hero-slides-carousel .carousel {
        position: relative;
        overflow: visible;
    }
    
    .hero-slides-carousel .carousel-inner {
        position: relative;
        overflow: visible;
    }
    
    .hero-slides-carousel .carousel-item {
        min-height: 500px;
        background: var(--background-color);
        position: relative;
    }
    
    .hero-slide-content {
        display: flex;
        align-items: center;
        min-height: 500px;
        padding: 3rem 2rem;
    }
    
    .hero-slide-left {
        flex: 1;
        padding-right: 2rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .hero-slide-right {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .hero-slide-left h1 {
        font-size: 3rem;
        font-weight: 800;
        color: var(--text-color);
        margin-bottom: 1rem;
        line-height: 1.2;
    }
    
    .hero-slide-left p {
        font-size: 1.25rem;
        color: var(--text-color);
        opacity: 0.8;
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    
    .hero-slide-left .btn {
        padding: 0.875rem 2rem;
        font-size: 1.125rem;
        font-weight: 600;
        border-radius: 8px;
        display: inline-block;
        text-decoration: none;
        transition: all 0.3s ease;
        background: var(--primary-color);
        color: white;
        border: none;
        max-width: 200px;
    }
    
    .hero-slide-left .btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .hero-slide-right img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
    }
    
    .hero-slides-carousel .carousel-control-prev,
    .hero-slides-carousel .carousel-control-next {
        width: 50px;
        height: 50px;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0.8;
        transition: all 0.3s ease;
        z-index: 100;
        cursor: pointer;
        pointer-events: auto !important;
        position: absolute;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .hero-slides-carousel .carousel-control-prev:hover,
    .hero-slides-carousel .carousel-control-next:hover {
        opacity: 1;
        background: rgba(0, 0, 0, 0.7);
        transform: translateY(-50%) scale(1.1);
    }
    
    .hero-slides-carousel .carousel-control-prev:active,
    .hero-slides-carousel .carousel-control-next:active {
        transform: translateY(-50%) scale(0.95);
    }
    
    .hero-slides-carousel .carousel-control-prev {
        left: 2rem;
    }
    
    .hero-slides-carousel .carousel-control-next {
        right: 2rem;
    }
    
    .hero-slides-carousel .carousel-control-prev-icon,
    .hero-slides-carousel .carousel-control-next-icon {
        width: 20px;
        height: 20px;
        filter: brightness(0) invert(1);
    }
    
    .hero-slides-carousel .carousel-control-prev span,
    .hero-slides-carousel .carousel-control-next span {
        pointer-events: none;
    }
    
    .hero-slides-carousel .carousel-indicators {
        bottom: 2rem;
    }
    
    .hero-slides-carousel .carousel-indicators button {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        border: 2px solid rgba(255, 255, 255, 0.8);
    }
    
    .hero-slides-carousel .carousel-indicators button.active {
        background: white;
    }
    
    /* Categories Grid */
    .categories-grid { 
        display: <?php echo e(($settings->show_categories ?? true) ? 'grid' : 'none'); ?>; 
        grid-template-columns: repeat(4, 1fr); 
        gap: 1.5rem; 
        margin-bottom: 4rem; 
    }
    
    /* Products Grid */
    .products-grid { 
        display: <?php echo e(($settings->show_featured_products ?? true) ? 'grid' : 'none'); ?>; 
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); 
        gap: 2rem; 
    }
    
    /* Add to Cart Button */
    .add-to-cart-btn { 
        display: <?php echo e(($settings->show_add_to_cart_button ?? true) ? 'flex' : 'none'); ?>; 
    }
    
    /* Search Bar */
    .search-form { 
        display: <?php echo e(($settings->show_search_bar ?? true) ? 'block' : 'none'); ?>; 
    }
    
    /* Cart Icon */
    .cart-section { 
        display: <?php echo e(($settings->show_cart_icon ?? true) ? 'block' : 'none'); ?>; 
    }
    
    /* Social Links */
    .social-links { 
        display: <?php echo e(($settings->show_social_links ?? true) ? 'flex' : 'none'); ?>; 
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .hero-banner h1 { font-size: 2rem; }
        .hero-banner p { font-size: 1rem; }
        .hero-slide-content {
            flex-direction: column;
            padding: 2rem 1rem;
        }
        .hero-slide-left {
            padding-right: 0;
            margin-bottom: 2rem;
            text-align: center;
        }
        .hero-slide-left h1 {
            font-size: 2rem;
        }
        .hero-slide-left p {
            font-size: 1rem;
        }
        .hero-slide-right {
            width: 100%;
        }
        .hero-slides-carousel .carousel-control-prev,
        .hero-slides-carousel .carousel-control-next {
            width: 40px;
            height: 40px;
        }
        .hero-slides-carousel .carousel-control-prev {
            left: 1rem;
        }
        .hero-slides-carousel .carousel-control-next {
            right: 1rem;
        }
        .products-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
        .categories-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .category-icon { height: 150px; }
        .category-image { height: 150px; }
    }
    
    @media (max-width: 480px) {
        .hero-banner { padding: 2rem 1rem; }
        .hero-banner h1 { font-size: 1.75rem; }
        .hero-slide-content {
            min-height: 400px;
        }
        .hero-slide-left h1 {
            font-size: 1.75rem;
        }
        .hero-slide-left .btn {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }
        .products-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $showHeroSlides = $settings->additional_settings['show_hero_slides'] ?? false;
    $heroSlides = $settings->additional_settings['hero_slides'] ?? [];
?>

<!-- Hero Slides Carousel -->
<?php if($showHeroSlides && !empty($heroSlides) && count($heroSlides) > 0): ?>
<div class="hero-slides-carousel">
    <div id="heroSlidesCarousel" class="carousel slide" data-bs-ride="false">
        <?php if(count($heroSlides) > 1): ?>
        <div class="carousel-indicators">
            <?php $__currentLoopData = $heroSlides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button type="button" 
                    data-bs-target="#heroSlidesCarousel" 
                    data-bs-slide-to="<?php echo e($index); ?>" 
                    <?php echo e($index === 0 ? 'class="active" aria-current="true"' : ''); ?> 
                    aria-label="Slide <?php echo e($index + 1); ?>"></button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
        <div class="carousel-inner">
            <?php $__currentLoopData = $heroSlides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>" data-slide-index="<?php echo e($index); ?>" style="background-color: <?php echo e($slide['background_color'] ?? '#ffffff'); ?>;">
                <div class="container">
                    <div class="hero-slide-content">
                        
                        <div class="hero-slide-left">
                            <?php if(!empty($slide['heading'])): ?>
                            <h1 style="color: <?php echo e($slide['text_color'] ?? '#000000'); ?>;"><?php echo e($slide['heading']); ?></h1>
                            <?php endif; ?>
                            <?php if(!empty($slide['subheading'])): ?>
                            <p style="color: <?php echo e($slide['text_color'] ?? '#000000'); ?>;"><?php echo e($slide['subheading']); ?></p>
                            <?php endif; ?>
                            <?php if(!empty($slide['button_text'])): ?>
                            <a href="<?php echo e($slide['button_link'] ?? '/products'); ?>" class="btn" style="background-color: <?php echo e($slide['button_bg_color'] ?? '#000000'); ?>; color: <?php echo e($slide['button_text_color'] ?? '#ffffff'); ?>;">
                                <?php echo e($slide['button_text']); ?>

                            </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="hero-slide-right">
                            <?php if(!empty($slide['image'])): ?>
                            <img src="<?php echo e(asset('storage/' . $slide['image'])); ?>" alt="<?php echo e($slide['heading'] ?? 'Slide ' . ($index + 1)); ?>" loading="<?php echo e($index === 0 ? 'eager' : 'lazy'); ?>">
                            <?php else: ?>
                            <div style="width: 100%; height: 300px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                                <i class="fas fa-image"></i>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php if(count($heroSlides) > 1): ?>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroSlidesCarousel" data-bs-slide="prev" id="carouselPrevBtn" aria-label="Previous slide">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroSlidesCarousel" data-bs-slide="next" id="carouselNextBtn" aria-label="Next slide">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Hero Banner (Fallback if slides not enabled) -->
<?php if(($settings->show_banner ?? true) && !$showHeroSlides): ?>
<div class="hero-banner">
    <div class="content">
        <h1><?php echo e($settings->banner_title ?? 'Welcome to ' . ($settings->site_name ?? $tenant->business_name)); ?></h1>
        <p><?php echo e($settings->banner_subtitle ?? 'Discover amazing products at great prices'); ?></p>
        <?php if($settings->banner_button_text): ?>
            <a href="#products" class="btn btn-primary btn-lg"><?php echo e($settings->banner_button_text); ?></a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<div class="container">
    <!-- Categories Section -->
    <?php if($settings->show_categories ?? true): ?>
    <section id="categories">
        <h2 class="section-title">Shop by Category</h2>
        <?php if($categories->count() > 0): ?>
        <div class="categories-grid">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.category', [$tenant->subdomain, $category->slug])); ?>" class="category-card">
                <div class="category-icon">
                    <?php if($category->image): ?>
                        <img src="<?php echo e(asset('storage/' . $category->image)); ?>" alt="<?php echo e($category->name); ?>" class="category-image">
                    <?php else: ?>
                        <i class="fas fa-box"></i>
                    <?php endif; ?>
                </div>
                <div class="category-name"><?php echo e($category->name); ?></div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-folder-open"></i>
            <p>No categories yet. Add categories from your admin panel.</p>
        </div>
        <?php endif; ?>
    </section>
    <?php endif; ?>

    <!-- Products Section -->
    <?php if($settings->show_featured_products ?? true): ?>
    <section id="products">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">Products</h2>
            <div class="d-flex gap-2 align-items-center">
                <?php if(request('q')): ?>
                    <span class="text-muted small">Search results for "<?php echo e(request('q')); ?>"</span>
                <?php endif; ?>
                <form action="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain])); ?>" method="GET" class="d-inline-block">
                    <input type="hidden" name="q" value="<?php echo e(request('q')); ?>">
                    <select name="sort" onchange="this.form.submit()" class="form-select form-select-sm d-inline-block w-auto">
                        <option value="new" <?php echo e($sort == 'new' ? 'selected' : ''); ?>>Newest</option>
                        <option value="price_asc" <?php echo e($sort == 'price_asc' ? 'selected' : ''); ?>>Price: Low to High</option>
                        <option value="price_desc" <?php echo e($sort == 'price_desc' ? 'selected' : ''); ?>>Price: High to Low</option>
                    </select>
                </form>
            </div>
        </div>
        
        <?php if($products->count() > 0): ?>
        <div class="products-grid">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="product-card" data-product-id="<?php echo e($product->id); ?>">
                <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.product', [$tenant->subdomain, $product->slug])); ?>" class="product-link">
                    <div class="product-image">
                        <?php if($product->primary_image_url): ?>
                            <img src="<?php echo e($product->primary_image_url); ?>" alt="<?php echo e($product->name); ?>" loading="lazy">
                        <?php else: ?>
                            <i class="fas fa-image"></i>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <div class="product-name"><?php echo e($product->name); ?></div>
                        <div class="product-price">
                            <?php if(($settings->currency_position ?? 'before') === 'before'): ?>
                                <?php echo e($settings->currency_symbol ?? 'Rs.'); ?> <?php echo e(number_format($product->sale_price ?? $product->price, 2)); ?>

                            <?php else: ?>
                                <?php echo e(number_format($product->sale_price ?? $product->price, 2)); ?> <?php echo e($settings->currency_symbol ?? 'Rs.'); ?>

                            <?php endif; ?>
                        </div>
                        <?php if($product->track_inventory): ?>
                            <div class="stock-status mb-2">
                                <?php if($product->isInStock()): ?>
                                    <?php if($product->isLowStock()): ?>
                                        <span class="badge bg-warning">Low Stock (<?php echo e($product->stock_quantity); ?> left)</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">In Stock</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-danger">Out of Stock</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </a>
                <?php if($settings->show_add_to_cart_button ?? true): ?>
                <div class="product-actions">
                    <button class="add-to-cart-btn js-add-to-cart" data-product-id="<?php echo e($product->id); ?>" <?php echo e(!$product->isInStock() ? 'disabled' : ''); ?>>
                        <i class="fas fa-shopping-cart"></i> 
                        <?php echo e($product->isInStock() ? 'Add to Cart' : 'Out of Stock'); ?>

                    </button>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($products->links('pagination::bootstrap-5')); ?>

        </div>
        
        <!-- Product Count -->
        <div class="text-center mt-3">
            <p class="text-muted small">
                <?php if(request('q')): ?>
                    Showing <?php echo e($products->count()); ?> of <?php echo e($products->total()); ?> results for "<?php echo e(request('q')); ?>"
                <?php else: ?>
                    Showing <?php echo e($products->count()); ?> of <?php echo e($products->total()); ?> products
                <?php endif; ?>
            </p>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <p>No products found.</p>
            <?php if(request('q')): ?>
                <p class="text-muted">Try adjusting your search terms.</p>
                <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain])); ?>" class="btn btn-primary">View All Products</a>
            <?php else: ?>
                <p>Add products from your admin panel.</p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </section>
    <?php endif; ?>
</div>

<!-- Quick View Modal -->
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickViewModalLabel">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="quickViewContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Initialize Hero Slides Carousel
(function() {
    let retryCount = 0;
    const maxRetries = 50; // 5 seconds max wait
    
    function initCarousel() {
        const carouselElement = document.getElementById('heroSlidesCarousel');
        if (!carouselElement) {
            return;
        }
        
        // Validate carousel structure before initializing
        const carouselInner = carouselElement.querySelector('.carousel-inner');
        const carouselItems = carouselElement.querySelectorAll('.carousel-item');
        const indicators = carouselElement.querySelectorAll('.carousel-indicators button');
        
        if (!carouselInner || carouselItems.length === 0) {
            console.warn('Carousel structure incomplete, skipping initialization');
            return;
        }
        
        // Ensure at least one item has active class
        const hasActiveItem = Array.from(carouselItems).some(item => item.classList.contains('active'));
        if (!hasActiveItem && carouselItems.length > 0) {
            carouselItems[0].classList.add('active');
        }
        
        // Ensure at least one indicator has active class
        if (indicators.length > 0) {
            const hasActiveIndicator = Array.from(indicators).some(btn => btn.classList.contains('active'));
            if (!hasActiveIndicator) {
                indicators[0].classList.add('active');
                indicators[0].setAttribute('aria-current', 'true');
            }
        }
        
        // Wait for Bootstrap to be available
        if (typeof bootstrap === 'undefined' || typeof bootstrap.Carousel === 'undefined') {
            retryCount++;
            if (retryCount < maxRetries) {
                setTimeout(initCarousel, 100);
            } else {
                console.error('Bootstrap Carousel not available after waiting');
            }
            return;
        }
        
        try {
            // Check if carousel is already initialized
            const existingCarousel = bootstrap.Carousel.getInstance(carouselElement);
            if (existingCarousel) {
                existingCarousel.dispose();
            }
            
            // Initialize Bootstrap carousel
            const carousel = new bootstrap.Carousel(carouselElement, {
                interval: 5000,
                wrap: true,
                keyboard: true,
                pause: 'hover',
                ride: false
            });
            
            // Manual click handlers for controls
            const prevButton = document.getElementById('carouselPrevBtn');
            const nextButton = document.getElementById('carouselNextBtn');
            
            if (prevButton) {
                prevButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    try {
                        carousel.prev();
                    } catch (err) {
                        console.error('Error navigating to previous slide:', err);
                    }
                    return false;
                });
            }
            
            if (nextButton) {
                nextButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    try {
                        carousel.next();
                    } catch (err) {
                        console.error('Error navigating to next slide:', err);
                    }
                    return false;
                });
            }
            
            // Fix for indicators
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    try {
                        carousel.to(index);
                    } catch (err) {
                        console.error('Error navigating to slide:', err);
                    }
                });
            });
            
            console.log('Hero slides carousel initialized successfully');
        } catch (error) {
            console.error('Error initializing carousel:', error);
        }
    }
    
    // Wait for window to be fully loaded (ensures Bootstrap is loaded)
    if (window.addEventListener) {
        window.addEventListener('load', function() {
            setTimeout(initCarousel, 300);
        });
    } else {
        // Fallback for older browsers
        window.onload = function() {
            setTimeout(initCarousel, 300);
        };
    }
})();

// Add CSRF token to meta tag if not present
if (!document.querySelector('meta[name="csrf-token"]')) {
    const meta = document.createElement('meta');
    meta.name = 'csrf-token';
    meta.content = '<?php echo e(csrf_token()); ?>';
    document.head.appendChild(meta);
}

// Prevent add to cart button from navigating to product page
// This handler intercepts clicks on add to cart buttons and prevents navigation
document.addEventListener('click', function(e) {
    if (e.target.closest('.js-add-to-cart') || e.target.closest('.add-to-cart-btn')) {
        // Check if the click is on the button itself, not on a link
        const button = e.target.closest('.js-add-to-cart, .add-to-cart-btn');
        if (button && button.tagName !== 'A') {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = button.dataset.productId;
            
            if (productId) {
                // Wait for storefront.js to be loaded, then use addToCart function
                if (typeof addToCart === 'function') {
                    addToCart(productId, 1, button);
                } else {
                    // Fallback if storefront.js hasn't loaded yet
                    setTimeout(() => {
                        if (typeof addToCart === 'function') {
                            addToCart(productId, 1, button);
                        } else {
                            // Final fallback: direct AJAX call
                            const formData = new FormData();
                            formData.append('product_id', productId);
                            formData.append('qty', 1);
                            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
                            
                            const subdomain = window.location.pathname.match(/\/storefront\/([^\/]+)/)?.[1] || '';
                            fetch(`/storefront/${subdomain}/cart/add`, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const cartCount = document.getElementById('cart-count');
                                    if (cartCount) cartCount.textContent = data.cart_count || 0;
                                    if (typeof showNotification === 'function') {
                                        showNotification('Product added to cart!', 'success');
                                    } else {
                                        alert('Product added to cart!');
                                    }
                                }
                            })
                            .catch(error => console.error('Error:', error));
                        }
                    }, 100);
                }
            }
        }
    }
});

function showQuickViewModal(productId) {
    const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
    const content = document.getElementById('quickViewContent');
    
    // Show loading state
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading product details...</p>
        </div>
    `;
    
    modal.show();
    
    // Fetch product details
    fetch(`/storefront/<?php echo e($tenant->subdomain); ?>/product/${productId}/quick-view`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                content.innerHTML = data.html;
            } else {
                content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        Failed to load product details. Please try again.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    An error occurred while loading product details.
                </div>
            `;
        });
}

// Smooth scroll for anchor links (only for hash links, not product links)
document.addEventListener('click', function(e) {
    const link = e.target.closest('a');
    if (link && link.getAttribute('href')) {
        const href = link.getAttribute('href');
        
        // Handle anchor links (hash links)
        if (href.startsWith('#') && href !== '#' && !link.classList.contains('product-link')) {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
            return;
        }
        
        // Prevent navigation if href is just '#' (error case)
        if (href === '#' && link.classList.contains('product-link')) {
            e.preventDefault();
            e.stopPropagation();
            console.error('Product link has invalid URL. Route generation may have failed.');
            if (typeof showNotification === 'function') {
                showNotification('Error: Unable to load product page. Please try again.', 'error');
            }
            return;
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.storefront', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/storefront/preview.blade.php ENDPATH**/ ?>