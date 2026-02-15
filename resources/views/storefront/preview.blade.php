@extends('layouts.storefront')

@section('title', $settings->meta_title ?? ($settings->site_name ?? $tenant->business_name))
@section('meta_description', $settings->meta_description ?? \Illuminate\Support\Str::limit($settings->site_description ?? ($tenant->business_name . ' online store'), 155))
@section('meta_keywords', $settings->meta_keywords ?? 'store, shop, ecommerce')
@section('og_title', $settings->site_name ?? $tenant->business_name)
@section('og_description', $settings->meta_description ?? '')
@section('og_image', $settings->logo_url ?? '')
@section('og_url', route('storefront.preview', $tenant->subdomain))

@push('styles')
<style>
    :root {
        --primary-color: {{ $settings->primary_color ?? '#667eea' }};
        --secondary-color: {{ $settings->secondary_color ?? '#764ba2' }};
        --accent-color: {{ $settings->accent_color ?? '#10b981' }};
        --text-color: {{ $settings->text_color ?? '#1e293b' }};
        --background-color: {{ $settings->background_color ?? '#ffffff' }};
        --header-bg-color: {{ $settings->header_bg_color ?? '#ffffff' }};
        --footer-bg-color: {{ $settings->footer_bg_color ?? '#1e293b' }};
        --font-family: {{ $settings->font_family ?? 'Inter' }}, sans-serif;
        --heading-font: {{ $settings->heading_font ?? 'Inter' }}, sans-serif;
        --font-size: {{ $settings->font_size ?? 16 }}px;
    }
    
    /* Banner Section */
    .banner-section { 
        display: {{ ($settings->show_banner ?? true) ? 'block' : 'none' }}; 
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); 
        color: white; 
        text-align: center; 
        position: relative; 
        overflow: hidden; 
        margin-bottom: 4rem;
        border-radius: var(--border-radius-lg);
    }
    
    .banner-section .banner-image-wrapper {
        position: relative;
        width: 100%;
        display: block;
    }
    
    .banner-section .banner-image-wrapper img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: cover;
    }
    
    .banner-section .content { 
        position: absolute; 
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2; 
        max-width: 800px; 
        width: 100%;
        padding: 2rem;
    }
    
    @if(!$settings->banner_image)
    .banner-section {
        padding: 4rem 2rem;
    }
    .banner-section .content {
        position: relative;
        top: auto;
        left: auto;
        transform: none;
        margin: 0 auto;
    }
    @endif
    
    .banner-section h2 { 
        font-size: 2.5rem; 
        font-weight: 800; 
        margin-bottom: 1rem; 
        line-height: 1.1;
    }
    
    .banner-section p { 
        font-size: 1.25rem; 
        margin-bottom: 2rem; 
        opacity: 0.95; 
        line-height: 1.6;
    }
    
    /* Hero Slides Carousel */
    .hero-slides-carousel {
        display: {{ ($settings->additional_settings['show_hero_slides'] ?? false) ? 'block' : 'none' }};
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
        min-height: {{ ($settings->additional_settings['slide_height'] ?? 500) . ($settings->additional_settings['slide_height_unit'] ?? 'px') }};
        position: relative;
    }
    
    .hero-slide-content {
        display: flex;
        align-items: center;
        min-height: {{ ($settings->additional_settings['slide_height'] ?? 500) . ($settings->additional_settings['slide_height_unit'] ?? 'px') }};
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
        display: {{ ($settings->show_categories ?? true) ? 'grid' : 'none' }}; 
        grid-template-columns: repeat(4, 1fr); 
        gap: 1.5rem; 
        margin-bottom: 4rem; 
    }
    
    /* New Arrivals Section */
    .new-arrivals-section {
        margin-bottom: 4rem;
        padding: 2rem 0;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .view-all-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .view-all-link:hover {
        color: var(--secondary-color);
        transform: translateX(4px);
    }
    
    .new-arrivals-carousel-wrapper {
        position: relative;
    }
    
    .new-arrivals-carousel {
        overflow: hidden;
        position: relative;
    }
    
    .new-arrivals-carousel .carousel-container {
        display: flex;
        gap: 1.5rem;
        overflow: hidden;
        scroll-behavior: smooth;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding: 0.5rem 0;
        position: relative;
        width: 100%;
    }
    
    .new-arrivals-carousel .carousel-container::-webkit-scrollbar {
        display: none;
    }
    
    .new-arrivals-carousel .product-card-carousel {
        flex: 0 0 calc(33.333% - 1rem);
        min-width: calc(33.333% - 1rem);
        background: white;
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        border: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
    }
    
    .new-arrivals-carousel .product-card-carousel:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .new-arrivals-carousel .product-link {
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    
    .new-arrivals-carousel .product-image {
        width: 100%;
        aspect-ratio: 1 / 1;
        background: var(--surface-color);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .new-arrivals-carousel .product-image img {
        width: 100%;
        height: 100%;
        max-height: 100%;
        object-fit: contain;
        object-position: center;
        transition: var(--transition);
        display: block;
    }
    
    .new-arrivals-carousel .product-card-carousel:hover .product-image img {
        transform: scale(1.05);
    }
    
    .new-arrivals-carousel .product-info {
        padding: 1.5rem;
        flex: 1;
    }
    
    .new-arrivals-carousel .product-name {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 0.5rem;
        color: var(--text-color);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .new-arrivals-carousel .product-price {
        margin-bottom: 0;
    }
    
    .new-arrivals-carousel .current-price {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .new-arrivals-carousel .original-price {
        font-size: 0.9rem;
        color: var(--text-muted);
        text-decoration: line-through;
        margin-left: 0.5rem;
    }
    
    .new-arrivals-carousel .product-actions {
        padding: 0 1.5rem 1.5rem;
    }
    
    .new-arrivals-carousel .add-to-cart-btn {
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
    
    .new-arrivals-carousel .add-to-cart-btn:hover:not(:disabled) {
        background: var(--secondary-color);
        transform: translateY(-2px);
    }
    
    .new-arrivals-carousel .add-to-cart-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .carousel-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 50%;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: var(--transition);
        box-shadow: var(--shadow-md);
    }
    
    .carousel-nav:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        transform: translateY(-50%) scale(1.1);
    }
    
    .carousel-prev {
        left: -20px;
    }
    
    .carousel-next {
        right: -20px;
    }
    
    @media (max-width: 992px) {
        .new-arrivals-carousel .product-card-carousel {
            flex: 0 0 calc(50% - 0.75rem);
            min-width: calc(50% - 0.75rem);
        }
    }
    
    @media (max-width: 768px) {
        .carousel-prev {
            left: 10px;
        }
        
        .carousel-next {
            right: 10px;
        }
        
        .new-arrivals-carousel .product-card-carousel {
            flex: 0 0 calc(100% - 0.5rem);
            min-width: calc(100% - 0.5rem);
        }
        
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
    
    /* Products Grid */
    .products-grid { 
        display: {{ ($settings->show_featured_products ?? true) ? 'grid' : 'none' }}; 
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); 
        gap: 2rem; 
    }
    
    /* Add to Cart Button */
    .add-to-cart-btn { 
        display: {{ ($settings->show_add_to_cart_button ?? true) ? 'flex' : 'none' }}; 
    }
    
    /* Search Bar */
    .search-form { 
        display: {{ ($settings->show_search_bar ?? true) ? 'block' : 'none' }}; 
    }
    
    /* Cart Icon */
    .cart-section { 
        display: {{ ($settings->show_cart_icon ?? true) ? 'block' : 'none' }}; 
    }
    
    /* Social Links */
    .social-links { 
        display: {{ ($settings->show_social_links ?? true) ? 'flex' : 'none' }}; 
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .banner-section h2 { font-size: 2rem; }
        .banner-section p { font-size: 1rem; }
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
        .banner-section { padding: 2rem 1rem; }
        .banner-section h2 { font-size: 1.75rem; }
        .hero-slide-content {
            @php
                $slideHeight = $settings->additional_settings['slide_height'] ?? 500;
                $slideUnit = $settings->additional_settings['slide_height_unit'] ?? 'px';
                $mobileHeight = $slideUnit === 'px' ? min($slideHeight * 0.7, 400) : ($slideUnit === 'vh' ? min($slideHeight * 0.7, 40) : ($slideUnit === '%' ? min($slideHeight * 0.7, 40) : min($slideHeight * 0.7, 25)));
            @endphp
            min-height: {{ $mobileHeight }}{{ $slideUnit }};
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
@endpush

@section('content')
@php
    $showHeroSlides = $settings->additional_settings['show_hero_slides'] ?? false;
    $heroSlides = $settings->additional_settings['hero_slides'] ?? [];
@endphp

<!-- Hero Slides Carousel -->
@if($showHeroSlides && !empty($heroSlides) && count($heroSlides) > 0)
<div class="hero-slides-carousel">
    <div id="heroSlidesCarousel" class="carousel slide" data-bs-ride="false">
        @if(count($heroSlides) > 1)
        <div class="carousel-indicators">
            @foreach($heroSlides as $index => $slide)
            <button type="button" 
                    data-bs-target="#heroSlidesCarousel" 
                    data-bs-slide-to="{{ $index }}" 
                    {{ $index === 0 ? 'class="active" aria-current="true"' : '' }} 
                    aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
        @endif
        <div class="carousel-inner">
            @foreach($heroSlides as $index => $slide)
            @php
                $bgType = $slide['background_type'] ?? 'color';
                $bgImage = $slide['background_image'] ?? '';
                $bgColor = $slide['background_color'] ?? '#ffffff';
                $bgPosition = $slide['background_position'] ?? 'center';
                $bgSize = $slide['background_size'] ?? 'cover';
                
                $bgStyle = '';
                if ($bgType === 'image' && !empty($bgImage)) {
                    // Generate URL - use Storage::url() for better server compatibility
                    try {
                        $bgUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($bgImage);
                        // If Storage::url() returns a path starting with /storage, use it as-is
                        // Otherwise, ensure it's a full URL
                        if (strpos($bgUrl, 'http') !== 0) {
                            // It's a relative path, make it absolute
                            $bgUrl = asset(ltrim($bgUrl, '/'));
                        }
                    } catch (\Exception $e) {
                        // Fallback to asset() if Storage fails
                        $bgUrl = asset('storage/' . ltrim($bgImage, '/'));
                    }
                    // Escape single quotes in URL for CSS
                    $bgUrl = str_replace("'", "\\'", $bgUrl);
                    $bgStyle = "background-image: url('{$bgUrl}'); background-position: {$bgPosition}; background-size: {$bgSize}; background-repeat: no-repeat;";
                } else {
                    $bgStyle = "background-color: {$bgColor};";
                }
            @endphp
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" data-slide-index="{{ $index }}" style="{{ $bgStyle }}">
                <div class="container">
                    <div class="hero-slide-content">
                        {{-- Left Column: Content --}}
                        <div class="hero-slide-left" style="@if(empty($slide['image']))padding-right: 0; text-align: center;@endif">
                            @if(!empty($slide['heading']))
                            <h1 style="color: {{ $slide['text_color'] ?? '#000000' }};">{{ $slide['heading'] }}</h1>
                            @endif
                            @if(!empty($slide['subheading']))
                            <p style="color: {{ $slide['text_color'] ?? '#000000' }};">{{ $slide['subheading'] }}</p>
                            @endif
                            @if(!empty($slide['button_text']) && !empty($slide['button_link']))
                            <a href="{{ $slide['button_link'] }}" class="btn" style="background-color: {{ $slide['button_bg_color'] ?? '#000000' }}; color: {{ $slide['button_text_color'] ?? '#ffffff' }};">
                                {{ $slide['button_text'] }}
                            </a>
                            @endif
                        </div>
                        {{-- Right Column: Image --}}
                        @if(!empty($slide['image']))
                        <div class="hero-slide-right">
                            <img src="{{ asset('storage/' . $slide['image']) }}" alt="{{ $slide['heading'] ?? 'Slide ' . ($index + 1) }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if(count($heroSlides) > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#heroSlidesCarousel" data-bs-slide="prev" id="carouselPrevBtn" aria-label="Previous slide">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroSlidesCarousel" data-bs-slide="next" id="carouselNextBtn" aria-label="Next slide">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        @endif
    </div>
</div>
@endif


<div class="container">
    <!-- Categories Section -->
    @if($settings->show_categories ?? true)
    <section id="categories">
        <h2 class="section-title">Shop by Category</h2>
        @if($categories->count() > 0)
        <div class="categories-grid">
            @foreach($categories as $category)
            <a href="{{ \App\Helpers\StorefrontHelper::route('storefront.category', [$tenant->subdomain, $category->slug]) }}" class="category-card">
                <div class="category-icon">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="category-image">
                    @else
                        <i class="fas fa-box"></i>
                    @endif
                </div>
                <div class="category-name">{{ $category->name }}</div>
            </a>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-folder-open"></i>
            <p>No categories yet. Add categories from your admin panel.</p>
        </div>
        @endif
    </section>
    @endif

    <!-- New Arrivals Section -->
    @if(($settings->show_new_arrivals ?? false) && isset($newArrivals) && $newArrivals->count() > 0)
    <section id="new-arrivals" class="new-arrivals-section">
        <div class="section-header">
            <h2 class="section-title">New Arrivals</h2>
            <a href="{{ \App\Helpers\StorefrontHelper::route('storefront.dynamic', [$tenant->subdomain, ($settings->additional_settings['products_archive_slug'] ?? 'products')]) }}?sort=new" class="view-all-link">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="new-arrivals-carousel-wrapper">
            <div class="new-arrivals-carousel" id="newArrivalsCarousel">
                <div class="carousel-container">
                    @foreach($newArrivals as $product)
                    <div class="product-card-carousel">
                        <a href="{{ \App\Helpers\StorefrontHelper::route('storefront.product', [$tenant->subdomain, $product->slug]) }}" class="product-link">
                            <div class="product-image">
                                @if($product->primary_image_url)
                                    <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" loading="lazy">
                                @else
                                    <i class="fas fa-image"></i>
                                @endif
                            </div>
                            <div class="product-info">
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="product-price">
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <span class="current-price">${{ number_format($product->sale_price, 2) }}</span>
                                        <span class="original-price">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="current-price">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @if($settings->show_add_to_cart_button ?? true)
                        <div class="product-actions">
                            <button class="add-to-cart-btn js-add-to-cart" data-product-id="{{ $product->id }}" 
                                    {{ !$product->isInStock() ? 'disabled' : '' }}>
                                <i class="fas fa-shopping-cart"></i>
                                {{ $product->isInStock() ? 'Add to Cart' : 'Out of Stock' }}
                            </button>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            <button class="carousel-nav carousel-prev" onclick="scrollCarousel('newArrivalsCarousel', -1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="carousel-nav carousel-next" onclick="scrollCarousel('newArrivalsCarousel', 1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </section>
    @endif

    <!-- Banner Section -->
    @if(($settings->show_banner ?? true) && (trim($settings->banner_title ?? '') !== '' || trim($settings->banner_subtitle ?? '') !== '' || trim($settings->banner_button_text ?? '') !== '' || !empty($settings->banner_image)))
    <section id="banner" class="banner-section">
        @if(!empty($settings->banner_image))
        <div class="banner-image-wrapper">
            @php
                $bannerUrl = '';
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($settings->banner_image)) {
                    $bannerUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($settings->banner_image);
                } else {
                    $bannerUrl = asset('storage/' . $settings->banner_image);
                }
            @endphp
            <img src="{{ $bannerUrl }}" alt="{{ $settings->banner_title ?? 'Banner' }}">
        </div>
        @endif
        <div class="content">
            @if(trim($settings->banner_title ?? '') !== '')
                <h2>{{ $settings->banner_title }}</h2>
            @endif
            @if(trim($settings->banner_subtitle ?? '') !== '')
                <p>{{ $settings->banner_subtitle }}</p>
            @endif
            @if(trim($settings->banner_button_text ?? '') !== '')
                <a href="{{ trim($settings->banner_button_link ?? '') !== '' ? $settings->banner_button_link : '#products' }}" class="btn btn-primary btn-lg">{{ $settings->banner_button_text }}</a>
            @endif
        </div>
    </section>
    @endif

    <!-- Products Section -->
    @if($settings->show_featured_products ?? true)
    <section id="products">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">Products</h2>
            <div class="d-flex gap-2 align-items-center">
                @if(request('q'))
                    <span class="text-muted small">Search results for "{{ request('q') }}"</span>
                @endif
                <form action="{{ \App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain]) }}" method="GET" class="d-inline-block">
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    <select name="sort" onchange="this.form.submit()" class="form-select form-select-sm d-inline-block w-auto">
                        <option value="new" {{ $sort == 'new' ? 'selected' : '' }}>Newest</option>
                        <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>
        </div>
        
        @if($products->count() > 0)
        <div class="products-grid">
            @foreach($products as $product)
            <div class="product-card" data-product-id="{{ $product->id }}">
                <a href="{{ \App\Helpers\StorefrontHelper::route('storefront.product', [$tenant->subdomain, $product->slug]) }}" class="product-link">
                    <div class="product-image">
                        @if($product->primary_image_url)
                            <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" loading="lazy">
                        @else
                            <i class="fas fa-image"></i>
                        @endif
                    </div>
                    <div class="product-info">
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-price">
                            @if(($settings->currency_position ?? 'before') === 'before')
                                {{ $settings->currency_symbol ?? 'Rs.' }} {{ number_format($product->sale_price ?? $product->price, 2) }}
                            @else
                                {{ number_format($product->sale_price ?? $product->price, 2) }} {{ $settings->currency_symbol ?? 'Rs.' }}
                            @endif
                        </div>
                        @if($product->track_inventory)
                            <div class="stock-status mb-2">
                                @if($product->isInStock())
                                    @if($product->isLowStock())
                                        <span class="badge bg-warning">Low Stock ({{ $product->stock_quantity }} left)</span>
                                    @else
                                        <span class="badge bg-success">In Stock</span>
                                    @endif
                                @else
                                    <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </a>
                @if($settings->show_add_to_cart_button ?? true)
                <div class="product-actions">
                    <button class="add-to-cart-btn js-add-to-cart" data-product-id="{{ $product->id }}" {{ !$product->isInStock() ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart"></i> 
                        {{ $product->isInStock() ? 'Add to Cart' : 'Out of Stock' }}
                    </button>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
        
        <!-- Product Count -->
        <div class="text-center mt-3">
            <p class="text-muted small">
                @if(request('q'))
                    Showing {{ $products->count() }} of {{ $products->total() }} results for "{{ request('q') }}"
                @else
                    Showing {{ $products->count() }} of {{ $products->total() }} products
                @endif
            </p>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <p>No products found.</p>
            @if(request('q'))
                <p class="text-muted">Try adjusting your search terms.</p>
                <a href="{{ \App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain]) }}" class="btn btn-primary">View All Products</a>
            @else
                <p>Add products from your admin panel.</p>
            @endif
        </div>
        @endif
    </section>
    @endif
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
@endsection

@push('scripts')
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
    meta.content = '{{ csrf_token() }}';
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
    fetch(`/storefront/{{ $tenant->subdomain }}/product/${productId}/quick-view`)
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

// New Arrivals Carousel Navigation with Loop
let carouselPosition = 0;
let carouselItems = [];
let itemsPerView = 3;
let isScrolling = false;

function getItemsPerView() {
    if (window.innerWidth <= 768) {
        return 1;
    } else if (window.innerWidth <= 992) {
        return 2;
    } else {
        return 3;
    }
}

function initNewArrivalsCarousel() {
    const carousel = document.getElementById('newArrivalsCarousel');
    if (!carousel) return;
    
    const container = carousel.querySelector('.carousel-container');
    if (!container) return;
    
    itemsPerView = getItemsPerView();
    carouselItems = Array.from(container.querySelectorAll('.product-card-carousel:not(.carousel-clone)'));
    
    if (carouselItems.length === 0) return;
    
    // Remove existing clones
    container.querySelectorAll('.carousel-clone').forEach(clone => clone.remove());
    
    // Clone items for seamless loop
    const totalItems = carouselItems.length;
    if (totalItems > itemsPerView) {
        // Clone first items to end
        for (let i = 0; i < itemsPerView; i++) {
            const clone = carouselItems[i].cloneNode(true);
            clone.classList.add('carousel-clone');
            container.appendChild(clone);
        }
        
        // Clone last items to beginning
        for (let i = totalItems - itemsPerView; i < totalItems; i++) {
            const clone = carouselItems[i].cloneNode(true);
            clone.classList.add('carousel-clone');
            container.insertBefore(clone, carouselItems[0]);
        }
        
        // Update carousel items list (including clones)
        const allItems = Array.from(container.querySelectorAll('.product-card-carousel'));
        
        // Set initial position to first real item (after beginning clones)
        setTimeout(() => {
            const firstRealIndex = itemsPerView;
            if (allItems[firstRealIndex]) {
                container.scrollLeft = allItems[firstRealIndex].offsetLeft - container.offsetLeft;
                carouselPosition = 0;
            }
        }, 100);
    }
    
    // Handle scroll end for seamless loop (only add once)
    if (!container.dataset.scrollListenerAdded) {
        container.dataset.scrollListenerAdded = 'true';
        const totalItems = carouselItems.length;
        
        container.addEventListener('scroll', function() {
            if (isScrolling) return;
            
            const scrollLeft = container.scrollLeft;
            const scrollWidth = container.scrollWidth;
            const clientWidth = container.clientWidth;
            const allItems = Array.from(container.querySelectorAll('.product-card-carousel'));
            
            if (allItems.length === 0) return;
            
            const firstItem = allItems[0];
            if (!firstItem) return;
            
            const itemWidth = firstItem.offsetWidth + 24; // width + gap
            const threshold = itemWidth * itemsPerView;
            
            // If scrolled to beginning clones, jump to end
            if (scrollLeft < threshold) {
                isScrolling = true;
                const realLastIndex = allItems.length - (itemsPerView * 2);
                if (allItems[realLastIndex]) {
                    container.scrollLeft = allItems[realLastIndex].offsetLeft - container.offsetLeft;
                    carouselPosition = Math.max(0, totalItems - itemsPerView);
                }
                setTimeout(() => { isScrolling = false; }, 50);
            }
            
            // If scrolled to end clones, jump to beginning
            if (scrollLeft > scrollWidth - clientWidth - threshold) {
                isScrolling = true;
                const realFirstIndex = itemsPerView;
                if (allItems[realFirstIndex]) {
                    container.scrollLeft = allItems[realFirstIndex].offsetLeft - container.offsetLeft;
                    carouselPosition = 0;
                }
                setTimeout(() => { isScrolling = false; }, 50);
            }
        });
    }
}

function scrollCarousel(carouselId, direction) {
    if (isScrolling) return;
    
    const carousel = document.getElementById(carouselId);
    if (!carousel) return;
    
    const container = carousel.querySelector('.carousel-container');
    if (!container) return;
    
    const allItems = Array.from(container.querySelectorAll('.product-card-carousel'));
    if (allItems.length === 0) {
        initNewArrivalsCarousel();
        return;
    }
    
    itemsPerView = getItemsPerView();
    const totalItems = carouselItems.length;
    
    if (totalItems === 0) {
        initNewArrivalsCarousel();
        return;
    }
    
    carouselPosition += (direction * itemsPerView);
    
    // Handle looping
    if (carouselPosition >= totalItems) {
        carouselPosition = 0;
    } else if (carouselPosition < 0) {
        carouselPosition = totalItems - itemsPerView;
        if (carouselPosition < 0) carouselPosition = 0;
    }
    
    // Find target item (accounting for beginning clones)
    const targetIndex = carouselPosition + itemsPerView;
    const targetItem = allItems[targetIndex];
    
    if (targetItem) {
        isScrolling = true;
        container.scrollTo({
            left: targetItem.offsetLeft - container.offsetLeft,
            behavior: 'smooth'
        });
        setTimeout(() => { isScrolling = false; }, 500);
    }
}

// Initialize carousel on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initNewArrivalsCarousel, 100);
    
    // Reinitialize on window resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            initNewArrivalsCarousel();
        }, 250);
    });
});
</script>
@endpush