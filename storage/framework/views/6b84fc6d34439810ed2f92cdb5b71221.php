<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        // Ensure $tenant and $settings exist to avoid undefined variable errors
        $tenant = $tenant ?? \App\Models\Tenant::first();
        if (!isset($settings) || !$settings) {
            if ($tenant) {
                $settings = \App\Models\SiteSettings::where('tenant_id', $tenant->id)->first();
            }
            if (!$settings) {
                $defaults = \App\Models\SiteSettings::getDefaultSettings();
                // align default site name with tenant if available
                if ($tenant && !isset($defaults['site_name'])) {
                    $defaults['site_name'] = $tenant->business_name;
                }
                $settings = (object) $defaults;
            }
        }
    ?>
    <title><?php echo $__env->yieldContent('title', $settings->site_name ?? $tenant->business_name); ?></title>
    
    <!-- Meta Tags -->
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', $settings->meta_description ?? ''); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('meta_keywords', $settings->meta_keywords ?? ''); ?>">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $__env->yieldContent('og_title', $settings->site_name ?? $tenant->business_name); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('og_description', $settings->meta_description ?? ''); ?>">
    <meta property="og:image" content="<?php echo $__env->yieldContent('og_image', $settings->logo_url ?? ''); ?>">
    <meta property="og:url" content="<?php echo $__env->yieldContent('og_url', url()->current()); ?>">
    <meta property="og:type" content="website">
    
    <!-- Favicon -->
    <?php if($settings->favicon): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset('storage/' . $settings->favicon)); ?>">
    <?php endif; ?>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=<?php echo e(urlencode($settings->font_family ?? 'Inter')); ?>:wght@300;400;500;600;700;800&family=<?php echo e(urlencode($settings->heading_font ?? 'Inter')); ?>:wght@600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Storefront Styles -->
    <link rel="stylesheet" href="<?php echo e(asset('css/storefront.css')); ?>">
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Preview Badge (only in preview mode) -->
    <?php if(request()->is('storefront/*')): ?>
        <div class="preview-badge">
            <i class="fas fa-eye"></i> Preview Mode
        </div>
    <?php endif; ?>
    
    <!-- Header -->
    <header class="storefront-header">
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo-section">
                    <a href="<?php echo e(route('storefront.preview', $tenant->subdomain)); ?>" class="logo">
                        <?php if($settings->logo): ?>
                            <img src="<?php echo e($settings->logo_url ?? asset('storage/' . $settings->logo)); ?>" alt="<?php echo e($settings->site_name ?? $tenant->business_name); ?>">
                        <?php else: ?>
                            <?php echo e($settings->site_name ?? $tenant->business_name); ?>

                        <?php endif; ?>
                    </a>
                </div>
                
                <!-- Navigation -->
                <nav class="main-nav">
                    <ul class="nav-list">
                        <li><a href="<?php echo e(route('storefront.preview', $tenant->subdomain)); ?>">Home</a></li>
                        <?php if($settings->show_categories_menu ?? true): ?>
                            <li><a href="#categories">Categories</a></li>
                        <?php endif; ?>
                        <li><a href="#products">Products</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </nav>
                
                <!-- Header Actions -->
                <div class="header-actions">
                    <!-- Search Bar -->
                    <?php if($settings->show_search_bar ?? true): ?>
                        <form class="search-form" method="GET" action="<?php echo e(route('storefront.preview', $tenant->subdomain)); ?>">
                            <div class="search-input-group">
                                <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Search products..." class="search-input">
                                <button type="submit" class="search-btn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                    
                    <!-- Cart Icon -->
                    <?php if($settings->show_cart_icon ?? true): ?>
                        <div class="cart-section">
                            <button class="cart-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="cart-count" id="cart-count"><?php echo e(count($cart ?? [])); ?></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end cart-dropdown">
                                <div class="cart-dropdown-header">
                                    <h6>Shopping Cart</h6>
                                </div>
                                <div class="cart-dropdown-content" id="mini-cart-content">
                                    <?php if(isset($cart) && count($cart) > 0): ?>
                                        <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="cart-item" data-product-id="<?php echo e($item['product_id']); ?>">
                                                <img src="<?php echo e($item['image']); ?>" alt="<?php echo e($item['name']); ?>" class="cart-item-image">
                                                <div class="cart-item-details">
                                                    <div class="cart-item-name"><?php echo e($item['name']); ?></div>
                                                    <div class="cart-item-price">Qty: <?php echo e($item['quantity']); ?> × Rs. <?php echo e(number_format($item['price'], 2)); ?></div>
                                                </div>
                                                <button class="cart-item-remove remove-from-cart" data-product-id="<?php echo e($item['product_id']); ?>">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <div class="cart-dropdown-footer">
                                            <div class="cart-total">
                                                <strong>Total: Rs. <?php echo e(number_format(array_sum(array_map(function($item) { return $item['quantity'] * $item['price']; }, $cart)), 2)); ?></strong>
                                            </div>
                                            <a href="<?php echo e(route('storefront.cart', $tenant->subdomain)); ?>" class="btn btn-primary btn-sm w-100">View Cart</a>
                                        </div>
                                    <?php else: ?>
                                        <div class="cart-empty">
                                            <i class="fas fa-shopping-cart"></i>
                                            <p>Your cart is empty</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- User Account -->
                    <?php if(auth()->guard()->check()): ?>
                        <div class="user-section">
                            <button class="user-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i>
                                <span><?php echo e(auth()->user()->first_name); ?></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end user-dropdown">
                                <a class="dropdown-item" href="<?php echo e(route('customer.dashboard', $tenant->subdomain)); ?>">
                                    <i class="fas fa-tachometer-alt"></i> My Account
                                </a>
                                <a class="dropdown-item" href="<?php echo e(route('customer.orders', $tenant->subdomain)); ?>">
                                    <i class="fas fa-shopping-bag"></i> My Orders
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="<?php echo e(route('customer.logout', $tenant->subdomain)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item logout-btn">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="auth-section">
                            <a href="<?php echo e(route('customer.login', $tenant->subdomain)); ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                            <a href="<?php echo e(route('customer.register', $tenant->subdomain)); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div class="collapse mobile-menu" id="mobileMenu">
                <nav class="mobile-nav">
                    <ul class="mobile-nav-list">
                        <li><a href="<?php echo e(route('storefront.preview', $tenant->subdomain)); ?>">Home</a></li>
                        <?php if($settings->show_categories_menu ?? true): ?>
                            <li><a href="#categories">Categories</a></li>
                        <?php endif; ?>
                        <li><a href="#products">Products</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="main-content">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    
    <!-- Footer -->
    <footer class="storefront-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h5><?php echo e($settings->site_name ?? $tenant->business_name); ?></h5>
                    <p><?php echo e($settings->site_description ?? 'Your trusted online store'); ?></p>
                    <?php if($settings->show_social_links ?? true): ?>
                        <div class="social-links">
                            <?php if($settings->facebook_url): ?>
                                <a href="<?php echo e($settings->facebook_url); ?>" target="_blank" rel="noopener">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            <?php endif; ?>
                            <?php if($settings->twitter_url): ?>
                                <a href="<?php echo e($settings->twitter_url); ?>" target="_blank" rel="noopener">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            <?php endif; ?>
                            <?php if($settings->instagram_url): ?>
                                <a href="<?php echo e($settings->instagram_url); ?>" target="_blank" rel="noopener">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="footer-section">
                    <h6>Quick Links</h6>
                    <ul>
                        <li><a href="<?php echo e(route('storefront.preview', $tenant->subdomain)); ?>">Home</a></li>
                        <li><a href="#products">Products</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h6>Customer Service</h6>
                    <ul>
                        <li><a href="#shipping">Shipping Info</a></li>
                        <li><a href="#returns">Returns</a></li>
                        <li><a href="#privacy">Privacy Policy</a></li>
                        <li><a href="#terms">Terms of Service</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h6>Contact Info</h6>
                    <div class="contact-info">
                        <?php if($settings->contact_email): ?>
                            <p><i class="fas fa-envelope"></i> <?php echo e($settings->contact_email); ?></p>
                        <?php endif; ?>
                        <?php if($settings->contact_phone): ?>
                            <p><i class="fas fa-phone"></i> <?php echo e($settings->contact_phone); ?></p>
                        <?php endif; ?>
                        <?php if($settings->contact_address): ?>
                            <p><i class="fas fa-map-marker-alt"></i> <?php echo e($settings->contact_address); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo e(date('Y')); ?> <?php echo e($settings->site_name ?? $tenant->business_name); ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Storefront JS -->
    <script src="<?php echo e(asset('js/storefront.js')); ?>"></script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/layouts/storefront.blade.php ENDPATH**/ ?>