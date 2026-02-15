<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
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
    @endphp
    <title>@yield('title', $settings->site_name ?? $tenant->business_name)</title>
    
    <!-- Meta Tags -->
    <meta name="description" content="@yield('meta_description', $settings->meta_description ?? '')">
    <meta name="keywords" content="@yield('meta_keywords', $settings->meta_keywords ?? '')">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og_title', $settings->site_name ?? $tenant->business_name)">
    <meta property="og:description" content="@yield('og_description', $settings->meta_description ?? '')">
    <meta property="og:image" content="@yield('og_image', $settings->logo_url ?? '')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:type" content="website">
    
    <!-- Favicon -->
    @if($settings->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $settings->favicon) }}">
    @endif
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($settings->font_family ?? 'Inter') }}:wght@300;400;500;600;700;800&family={{ urlencode($settings->heading_font ?? 'Inter') }}:wght@600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Storefront Styles -->
    <link rel="stylesheet" href="{{ asset('css/storefront.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Preview Badge (only in preview mode) -->
    @if(request()->is('storefront/*'))
        <div class="preview-badge">
            <i class="fas fa-eye"></i> Preview Mode
        </div>
    @endif
    
    <!-- Header -->
    <header class="storefront-header">
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo-section">
                    <a href="{{ route('storefront.preview', $tenant->subdomain) }}" class="logo">
                        @if($settings->logo)
                            <img src="{{ $settings->logo_url ?? asset('storage/' . $settings->logo) }}" alt="{{ $settings->site_name ?? $tenant->business_name }}">
                        @else
                            {{ $settings->site_name ?? $tenant->business_name }}
                        @endif
                    </a>
                </div>
                
                <!-- Navigation -->
                <nav class="main-nav">
                    <ul class="nav-list">
                        @php
                            $navigationLinks = $settings->navigation_links ?? [];
                            // Default menu items if none exist
                            if (empty($navigationLinks)) {
                                $navigationLinks = [
                                    ['label' => 'Home', 'url' => '/', 'type' => 'link', 'order' => 1],
                                    ['label' => 'Products', 'url' => '#products', 'type' => 'link', 'order' => 2],
                                    ['label' => 'About', 'url' => '#about', 'type' => 'link', 'order' => 3],
                                    ['label' => 'Contact', 'url' => '#contact', 'type' => 'link', 'order' => 4],
                                ];
                            }
                            // Sort by order
                            usort($navigationLinks, function($a, $b) {
                                return ($a['order'] ?? 999) <=> ($b['order'] ?? 999);
                            });
                        @endphp
                        @foreach($navigationLinks as $item)
                            @php
                                $url = $item['url'] ?? '#';
                                // Handle different URL types
                                if ($url === '/' || $url === '') {
                                    try {
                                        $url = \App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain]);
                                        if (empty($url) || $url === '#') {
                                            $url = url('/');
                                        }
                                    } catch (\Exception $e) {
                                        $url = url('/');
                                    }
                                } elseif (strpos($url, '#') === 0) {
                                    // Anchor link - keep as is
                                    $url = $url;
                                } elseif (strpos($url, 'http') === 0 || strpos($url, '//') === 0) {
                                    // External URL - keep as is
                                    $url = $url;
                                } else {
                                    // Internal path - construct proper URL dynamically from current request
                                    // Always use current request to ensure domain is correct (not hardcoded localhost)
                                    $host = request()->getHost();
                                    $scheme = request()->getScheme();
                                    $basePath = request()->getBasePath();
                                    
                                    // Construct base URL from current request
                                    $baseUrl = $scheme . '://' . $host;
                                    if ($basePath && $basePath !== '/') {
                                        $baseUrl .= rtrim($basePath, '/');
                                    }
                                    
                                    // Remove trailing slash from base URL
                                    $baseUrl = rtrim($baseUrl, '/');
                                    
                                    // Ensure path starts with /
                                    $path = '/' . ltrim($url, '/');
                                    
                                    // For subdomain routes, we might need to add the subdomain path
                                    // Check if we're on a subdomain route and need to adjust
                                    if (\App\Helpers\StorefrontHelper::isSubdomainAccess()) {
                                        // On subdomain, path is relative to root
                                        $url = $baseUrl . $path;
                                    } else {
                                        // On path-based route, need to include subdomain in path
                                        $url = $baseUrl . '/storefront/' . $tenant->subdomain . $path;
                                    }
                                }
                            @endphp
                            <li><a href="{{ $url }}">{{ $item['label'] ?? 'Menu Item' }}</a></li>
                        @endforeach
                        @if($settings->show_categories_menu ?? true)
                            <li><a href="#categories">Categories</a></li>
                        @endif
                    </ul>
                </nav>
                
                <!-- Header Actions -->
                <div class="header-actions">
                    <!-- Search Bar -->
                    @if($settings->show_search_bar ?? true)
                        <div class="search-section">
                            <button class="search-icon-btn" id="searchToggleBtn" aria-label="Search">
                                <i class="fas fa-search"></i>
                            </button>
                            <form class="search-form" id="searchForm" method="GET" action="{{ route('storefront.preview', $tenant->subdomain) }}">
                                <div class="search-input-group">
                                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..." class="search-input" id="searchInput">
                                    <button type="submit" class="search-btn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                    
                    <!-- Cart Icon -->
                    @if($settings->show_cart_icon ?? true)
                        <div class="cart-section">
                            <button class="cart-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-shopping-cart"></i>
                                @php
                                    // Calculate cart count as sum of quantities (not count of items)
                                    $displayCartCount = 0;
                                    if (isset($cart) && is_array($cart) && !empty($cart)) {
                                        $displayCartCount = array_sum(array_map(function($item) {
                                            return isset($item['quantity']) ? (int)$item['quantity'] : 0;
                                        }, $cart));
                                    }
                                @endphp
                                <span class="cart-count" id="cart-count">{{ $displayCartCount }}</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end cart-dropdown">
                                <div class="cart-dropdown-header">
                                    <h6>Shopping Cart</h6>
                                </div>
                                <div class="cart-dropdown-content" id="mini-cart-content">
                                    @if(isset($cart) && count($cart) > 0)
                                        @foreach($cart as $item)
                                            <div class="cart-item" data-product-id="{{ $item['product_id'] }}">
                                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="cart-item-image">
                                                <div class="cart-item-details">
                                                    <div class="cart-item-name">{{ $item['name'] }}</div>
                                                    <div class="cart-item-price">Qty: {{ $item['quantity'] }} × Rs. {{ number_format($item['price'], 2) }}</div>
                                                </div>
                                                <button class="cart-item-remove remove-from-cart" data-product-id="{{ $item['product_id'] }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                        <div class="cart-dropdown-footer">
                                            <div class="cart-total">
                                                <strong>Total: Rs. {{ number_format(array_sum(array_map(function($item) { return $item['quantity'] * $item['price']; }, $cart)), 2) }}</strong>
                                            </div>
                                            <a href="{{ \App\Helpers\StorefrontHelper::route('storefront.cart', [$tenant->subdomain]) }}" class="btn btn-primary btn-sm w-100">View Cart</a>
                                        </div>
                                    @else
                                        <div class="cart-empty">
                                            <i class="fas fa-shopping-cart"></i>
                                            <p>Your cart is empty</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- User Account -->
                    @auth
                        <div class="user-section">
                            <button class="user-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i>
                                <span>{{ auth()->user()->first_name }}</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end user-dropdown">
                                <a class="dropdown-item" href="{{ route('customer.dashboard', $tenant->subdomain) }}">
                                    <i class="fas fa-tachometer-alt"></i> My Account
                                </a>
                                <a class="dropdown-item" href="{{ route('customer.orders', $tenant->subdomain) }}">
                                    <i class="fas fa-shopping-bag"></i> My Orders
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('customer.logout', $tenant->subdomain) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-btn">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="auth-section">
                            <a href="{{ route('customer.login', $tenant->subdomain) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                            <a href="{{ route('customer.register', $tenant->subdomain) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        </div>
                    @endauth
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
                        @php
                            $navigationLinks = $settings->navigation_links ?? [];
                            // Default menu items if none exist
                            if (empty($navigationLinks)) {
                                $navigationLinks = [
                                    ['label' => 'Home', 'url' => '/', 'type' => 'link', 'order' => 1],
                                    ['label' => 'Products', 'url' => '#products', 'type' => 'link', 'order' => 2],
                                    ['label' => 'About', 'url' => '#about', 'type' => 'link', 'order' => 3],
                                    ['label' => 'Contact', 'url' => '#contact', 'type' => 'link', 'order' => 4],
                                ];
                            }
                            // Sort by order
                            usort($navigationLinks, function($a, $b) {
                                return ($a['order'] ?? 999) <=> ($b['order'] ?? 999);
                            });
                        @endphp
                        @foreach($navigationLinks as $item)
                            @php
                                $url = $item['url'] ?? '#';
                                // Handle different URL types
                                if ($url === '/' || $url === '') {
                                    try {
                                        $url = \App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain]);
                                        if (empty($url) || $url === '#') {
                                            $url = url('/');
                                        }
                                    } catch (\Exception $e) {
                                        $url = url('/');
                                    }
                                } elseif (strpos($url, '#') === 0) {
                                    // Anchor link - keep as is
                                    $url = $url;
                                } elseif (strpos($url, 'http') === 0 || strpos($url, '//') === 0) {
                                    // External URL - keep as is
                                    $url = $url;
                                } else {
                                    // Internal path - construct proper URL dynamically from current request
                                    // Always use current request to ensure domain is correct (not hardcoded localhost)
                                    $host = request()->getHost();
                                    $scheme = request()->getScheme();
                                    $basePath = request()->getBasePath();
                                    
                                    // Construct base URL from current request
                                    $baseUrl = $scheme . '://' . $host;
                                    if ($basePath && $basePath !== '/') {
                                        $baseUrl .= rtrim($basePath, '/');
                                    }
                                    
                                    // Remove trailing slash from base URL
                                    $baseUrl = rtrim($baseUrl, '/');
                                    
                                    // Ensure path starts with /
                                    $path = '/' . ltrim($url, '/');
                                    
                                    // For subdomain routes, we might need to add the subdomain path
                                    // Check if we're on a subdomain route and need to adjust
                                    if (\App\Helpers\StorefrontHelper::isSubdomainAccess()) {
                                        // On subdomain, path is relative to root
                                        $url = $baseUrl . $path;
                                    } else {
                                        // On path-based route, need to include subdomain in path
                                        $url = $baseUrl . '/storefront/' . $tenant->subdomain . $path;
                                    }
                                }
                            @endphp
                            <li><a href="{{ $url }}">{{ $item['label'] ?? 'Menu Item' }}</a></li>
                        @endforeach
                        @if($settings->show_categories_menu ?? true)
                            <li><a href="#categories">Categories</a></li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="storefront-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h5>{{ $settings->site_name ?? $tenant->business_name }}</h5>
                    <p>{{ $settings->site_description ?? 'Your trusted online store' }}</p>
                    @if($settings->show_social_links ?? true)
                        <div class="social-links">
                            @if($settings->facebook_url)
                                <a href="{{ $settings->facebook_url }}" target="_blank" rel="noopener">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @endif
                            @if($settings->twitter_url)
                                <a href="{{ $settings->twitter_url }}" target="_blank" rel="noopener">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            @endif
                            @if($settings->instagram_url)
                                <a href="{{ $settings->instagram_url }}" target="_blank" rel="noopener">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
                
                <div class="footer-section">
                    <h6>Quick Links</h6>
                    <ul>
                        <li><a href="{{ route('storefront.preview', $tenant->subdomain) }}">Home</a></li>
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
                        @if($settings->contact_email)
                            <p><i class="fas fa-envelope"></i> {{ $settings->contact_email }}</p>
                        @endif
                        @if($settings->contact_phone)
                            <p><i class="fas fa-phone"></i> {{ $settings->contact_phone }}</p>
                        @endif
                        @if($settings->contact_address)
                            <p><i class="fas fa-map-marker-alt"></i> {{ $settings->contact_address }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} {{ $settings->site_name ?? $tenant->business_name }}. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Storefront JS -->
    <script>
        // Make subdomain and CSRF token available to JavaScript
        @php
            $isSubdomain = \App\Helpers\StorefrontHelper::isSubdomainAccess();
            $subdomain = $tenant->subdomain ?? '';
            
            // Use StorefrontHelper to generate cart URL with base path included
            $cartUrl = \App\Helpers\StorefrontHelper::route('storefront.cart.add', [$subdomain]);
            
            $baseUrl = $isSubdomain 
                ? url('/')
                : url('/storefront/' . $subdomain);
        @endphp
        @php
            // Calculate initial cart count (sum of all quantities)
            $cartCount = 0;
            if (isset($cart) && is_array($cart) && !empty($cart)) {
                // Check if cart is indexed array (array_values was called) or associative array
                $firstKey = array_key_first($cart);
                if (is_numeric($firstKey) && isset($cart[$firstKey]['quantity'])) {
                    // Indexed array - sum quantities directly
                    $cartCount = array_sum(array_column($cart, 'quantity'));
                } else {
                    // Associative array (product_id => item) - sum quantities
                    $cartCount = array_sum(array_map(function($item) {
                        return isset($item['quantity']) ? (int)$item['quantity'] : 0;
                    }, $cart));
                }
            }
        @endphp
        window.STOREFRONT_CONFIG = {
            subdomain: '{{ $tenant->subdomain ?? '' }}',
            cartUrl: '{{ $cartUrl }}',
            baseUrl: '{{ $baseUrl }}',
            csrfToken: '{{ csrf_token() }}',
            isSubdomain: {{ $isSubdomain ? 'true' : 'false' }},
            initialCartCount: {{ $cartCount }}
        };
    </script>
    <script src="{{ asset('js/storefront.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
