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
    
    /* Hero Banner */
    .hero-banner { 
        display: {{ ($settings->show_banner ?? true) ? 'block' : 'none' }}; 
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); 
        color: white; 
        padding: 4rem 2rem; 
        text-align: center; 
        position: relative; 
        overflow: hidden; 
        margin-bottom: 3rem;
    }
    
    @if($settings->banner_image)
    .hero-banner { 
        background-image: url('{{ $settings->banner_image_url ?? asset('storage/' . $settings->banner_image) }}'); 
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
    @endif
    
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
    
    /* Categories Grid */
    .categories-grid { 
        display: {{ ($settings->show_categories ?? true) ? 'grid' : 'none' }}; 
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
        gap: 1.5rem; 
        margin-bottom: 4rem; 
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
        .hero-banner h1 { font-size: 2rem; }
        .hero-banner p { font-size: 1rem; }
        .products-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
        .categories-grid { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; }
    }
    
    @media (max-width: 480px) {
        .hero-banner { padding: 2rem 1rem; }
        .hero-banner h1 { font-size: 1.75rem; }
        .products-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); }
    }
</style>
@endpush

@section('content')
<!-- Hero Banner -->
@if($settings->show_banner ?? true)
<div class="hero-banner">
    <div class="content">
        <h1>{{ $settings->banner_title ?? 'Welcome to ' . ($settings->site_name ?? $tenant->business_name) }}</h1>
        <p>{{ $settings->banner_subtitle ?? 'Discover amazing products at great prices' }}</p>
        @if($settings->banner_button_text)
            <a href="#products" class="btn btn-primary btn-lg">{{ $settings->banner_button_text }}</a>
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
            <a href="{{ route('storefront.category', [$tenant->subdomain, $category->slug]) }}" class="category-card">
                <div class="category-icon">
                    <i class="fas fa-box"></i>
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

    <!-- Products Section -->
    @if($settings->show_featured_products ?? true)
    <section id="products">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">Products</h2>
            <div class="d-flex gap-2 align-items-center">
                @if(request('q'))
                    <span class="text-muted small">Search results for "{{ request('q') }}"</span>
                @endif
                <form action="{{ route('storefront.preview', $tenant->subdomain) }}" method="GET" class="d-inline-block">
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
                <div class="product-image js-quick-view" style="cursor:pointer" title="Quick view">
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
                    @if($settings->show_add_to_cart_button ?? true)
                    <button class="add-to-cart-btn js-add-to-cart" data-product-id="{{ $product->id }}" {{ !$product->isInStock() ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart"></i> 
                        {{ $product->isInStock() ? 'Add to Cart' : 'Out of Stock' }}
                    </button>
                    @endif
                </div>
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
                <a href="{{ route('storefront.preview', $tenant->subdomain) }}" class="btn btn-primary">View All Products</a>
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
// Add CSRF token to meta tag if not present
if (!document.querySelector('meta[name="csrf-token"]')) {
    const meta = document.createElement('meta');
    meta.name = 'csrf-token';
    meta.content = '{{ csrf_token() }}';
    document.head.appendChild(meta);
}

// Enhanced quick view functionality
document.addEventListener('click', function(e) {
    if (e.target.closest('.js-quick-view')) {
        e.preventDefault();
        const card = e.target.closest('.product-card');
        const productId = card.dataset.productId;
        
        if (productId) {
            showQuickViewModal(productId);
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

// Smooth scroll for anchor links
document.addEventListener('click', function(e) {
    if (e.target.matches('a[href^="#"]')) {
        e.preventDefault();
        const target = document.querySelector(e.target.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }
});
</script>
@endpush