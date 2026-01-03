@extends('layouts.storefront')

@section('title', $product->name . ' — ' . $tenant->business_name)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($product->description), 155))
@section('meta_keywords', $product->name . ', ' . ($product->category->name ?? '') . ', products, ' . $tenant->business_name)
@section('og_title', $product->name)
@section('og_description', \Illuminate\Support\Str::limit(strip_tags($product->description), 155))
@section('og_image', $product->primary_image_url)
@section('og_url', route('storefront.product', [$tenant->subdomain, $product->slug]))

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('storefront.preview', $tenant->subdomain) }}">Home</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('storefront.category', [$tenant->subdomain, $product->category->slug]) }}">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Product Images -->
        <div class="col-md-6">
            <div class="product-image-gallery">
                <div class="main-image-container">
                    <img src="{{ $product->primary_image_url }}" class="main-image" alt="{{ $product->name }}" id="mainImage">
                    @if($product->track_inventory && !$product->isInStock())
                        <div class="out-of-stock-overlay">
                            <span class="badge bg-danger">Out of Stock</span>
                        </div>
                    @endif
                </div>
                
                @if(is_array($product->all_images_urls) && count($product->all_images_urls) > 1)
                <div class="thumbnail-gallery">
                    @foreach($product->all_images_urls as $index => $imageUrl)
                        <div class="thumbnail-item {{ $index === ($product->primary_image_index ?? 0) ? 'active' : '' }}" 
                             onclick="changeMainImage('{{ $imageUrl }}', this)">
                            <img src="{{ $imageUrl }}" alt="Thumbnail {{ $index + 1 }}" loading="lazy">
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <div class="product-details">
                <h1 class="product-title">{{ $product->name }}</h1>
                
                <!-- Rating -->
                @if($avgRating)
                <div class="product-rating mb-3">
                    <div class="stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($avgRating))
                                <i class="fas fa-star text-warning"></i>
                            @elseif ($i - 0.5 <= $avgRating)
                                <i class="fas fa-star-half-alt text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="rating-text">
                        {{ $avgRating }} out of 5 stars
                        <a href="#reviews" class="text-decoration-none">({{ $reviews->total() }} reviews)</a>
                    </span>
                </div>
                @endif

                <!-- Price -->
                <div class="product-price mb-3">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="current-price">Rs. {{ number_format($product->sale_price, 2) }}</span>
                        <span class="original-price">Rs. {{ number_format($product->price, 2) }}</span>
                        <span class="discount-badge">Save Rs. {{ number_format($product->price - $product->sale_price, 2) }}</span>
                    @else
                        <span class="current-price">Rs. {{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <!-- Stock Status -->
                @if($product->track_inventory)
                <div class="stock-status mb-3">
                    @if($product->isInStock())
                        @if($product->isLowStock())
                            <span class="badge bg-warning">
                                <i class="fas fa-exclamation-triangle"></i> Low Stock ({{ $product->stock_quantity }} left)
                            </span>
                        @else
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle"></i> In Stock
                            </span>
                        @endif
                    @else
                        <span class="badge bg-danger">
                            <i class="fas fa-times-circle"></i> Out of Stock
                        </span>
                    @endif
                </div>
                @endif

                <!-- Description -->
                <div class="product-description mb-4">
                    <h6>Description</h6>
                    <div class="description-content">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <!-- Product Info -->
                <div class="product-info mb-4">
                    <div class="row">
                        <div class="col-6">
                            <div class="info-item">
                                <span class="info-label">SKU:</span>
                                <span class="info-value">{{ $product->sku ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-item">
                                <span class="info-label">Category:</span>
                                <span class="info-value">
                                    @if($product->category)
                                        <a href="{{ route('storefront.category', [$tenant->subdomain, $product->category->slug]) }}" class="text-decoration-none">
                                            {{ $product->category->name }}
                                        </a>
                                    @else
                                        Uncategorized
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add to Cart Form -->
                <form action="{{ route('storefront.cart.add', $tenant->subdomain) }}" method="post" class="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="quantity-section mb-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <div class="quantity-controls">
                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                            <input type="number" id="quantity" name="qty" value="1" min="1" max="{{ $product->track_inventory ? $product->stock_quantity : 999 }}" class="form-control quantity-input">
                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary btn-lg add-to-cart-btn" {{ !$product->isInStock() ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart"></i> 
                            {{ $product->isInStock() ? 'Add to Cart' : 'Out of Stock' }}
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
                
                @auth
                <!-- Review Form -->
                <div class="review-form-section mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Write a Review</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('storefront.product.reviews.store', [$tenant->subdomain, $product->id]) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="rating" class="form-label">Rating *</label>
                                        <select name="rating" id="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                            <option value="">Select a rating</option>
                                            <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 Stars)</option>
                                            <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>⭐⭐⭐⭐☆ (4 Stars)</option>
                                            <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>⭐⭐⭐☆☆ (3 Stars)</option>
                                            <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>⭐⭐☆☆☆ (2 Stars)</option>
                                            <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>⭐☆☆☆☆ (1 Star)</option>
                                        </select>
                                        @error('rating')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="comment" class="form-label">Your Review *</label>
                                    <textarea name="comment" id="comment" rows="4" class="form-control @error('comment') is-invalid @enderror" 
                                              placeholder="Share your experience with this product..." required>{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i>
                    Please <a href="{{ route('customer.login', $tenant->subdomain) }}">login</a> to leave a review.
                </div>
                @endauth

                <!-- Reviews List -->
                @if($reviews->count() > 0)
                    <div class="reviews-list">
                        @foreach($reviews as $review)
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar">
                                            {{ strtoupper(substr($review->user->first_name ?? 'A', 0, 1)) }}
                                        </div>
                                        <div class="reviewer-details">
                                            <h6 class="reviewer-name">{{ $review->user->first_name ?? 'Anonymous' }} {{ $review->user->last_name ?? '' }}</h6>
                                            <div class="review-rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review-date">
                                        {{ $review->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="review-content">
                                    <p>{{ $review->comment }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Reviews Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $reviews->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="no-reviews">
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h5>No reviews yet</h5>
                            <p class="text-muted">Be the first to review this product!</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
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
@endpush

@push('scripts')
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

// Add to cart form
document.querySelector('.add-to-cart-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = form.querySelector('.add-to-cart-btn');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    submitBtn.disabled = true;
    
    // Prepare form data
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
            showNotification('Product added to cart!', 'success');
            
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
            showNotification(data.error || 'Failed to add product to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        // Restore button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
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
@endpush