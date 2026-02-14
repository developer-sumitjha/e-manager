// Storefront JavaScript - Modern E-commerce Interactions

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initCart();
    initSearch();
    initMobileMenu();
    initProductCards();
    initForms();
    initAnimations();
});

// Cart Management
function initCart() {
    // Add to cart functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-to-cart-btn, .js-add-to-cart')) {
            e.preventDefault();
            const button = e.target.closest('.add-to-cart-btn, .js-add-to-cart');
            const productId = button.dataset.productId;
            const quantity = button.closest('form')?.querySelector('input[name="qty"]')?.value || 1;
            
            addToCart(productId, quantity, button);
        }
        
        // Remove from cart
        if (e.target.closest('.remove-from-cart')) {
            e.preventDefault();
            const button = e.target.closest('.remove-from-cart');
            const productId = button.dataset.productId;
            
            removeFromCart(productId, button);
        }
    });
    
    // Update cart count with initial value from server
    const initialCartCount = (window.STOREFRONT_CONFIG && window.STOREFRONT_CONFIG.initialCartCount) 
        ? window.STOREFRONT_CONFIG.initialCartCount 
        : (() => {
            // Fallback: try to read from DOM
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                const count = parseInt(cartCountElement.textContent.trim()) || 0;
                return count;
            }
            return 0;
        })();
    updateCartCount(initialCartCount);
}

function addToCart(productId, quantity = 1, button) {
    if (!productId) return;
    
    // Show loading state
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    button.disabled = true;
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                     (window.STOREFRONT_CONFIG && window.STOREFRONT_CONFIG.csrfToken) || '';
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        showNotification('Error: Security token missing. Please refresh the page.', 'error');
        button.innerHTML = originalText;
        button.disabled = false;
        return;
    }
    
    // Prepare form data
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('qty', quantity);
    formData.append('_token', csrfToken);
    
    // Get subdomain from window.STOREFRONT_CONFIG (set by Blade template) or fallback to URL parsing
    let subdomain = '';
    let cartUrl = '';
    
    if (window.STOREFRONT_CONFIG && window.STOREFRONT_CONFIG.subdomain) {
        subdomain = window.STOREFRONT_CONFIG.subdomain;
        // Use the cart URL from config if available, otherwise construct it
        if (window.STOREFRONT_CONFIG.cartUrl) {
            cartUrl = window.STOREFRONT_CONFIG.cartUrl;
        } else {
            cartUrl = `/storefront/${subdomain}/cart/add`;
        }
    } else {
        // Fallback: try to get from URL
        subdomain = getSubdomainFromUrl();
        cartUrl = `/storefront/${subdomain}/cart/add`;
    }
    
    // Validate we have a subdomain
    if (!subdomain) {
        console.error('Could not determine subdomain for cart operation');
        showNotification('Error: Could not determine store. Please refresh the page.', 'error');
        button.innerHTML = originalText;
        button.disabled = false;
        return;
    }
    
    fetch(cartUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count
            updateCartCount(data.cart_count);
            
            // Update mini cart
            updateMiniCart(data.cart);
            
            // Show success message
            showNotification('Product added to cart!', 'success');
            
            // Add animation to button
            button.style.transform = 'scale(1.1)';
            setTimeout(() => {
                button.style.transform = 'scale(1)';
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
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function removeFromCart(productId, button) {
    if (!productId) return;
    
    // Show loading state
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    // Prepare form data
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
    
    // Get the current subdomain from the URL
    const subdomain = getSubdomainFromUrl();
    
    fetch(`/storefront/${subdomain}/cart/remove`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count
            updateCartCount(data.cart_count);
            
            // Update mini cart
            updateMiniCart(data.cart);
            
            // Show success message
            showNotification('Product removed from cart', 'success');
            
            // Remove the cart item element
            const cartItem = button.closest('.cart-item, .dropdown-item-text');
            if (cartItem) {
                cartItem.style.opacity = '0';
                cartItem.style.transform = 'translateX(-100%)';
                setTimeout(() => {
                    cartItem.remove();
                }, 300);
            }
        } else {
            showNotification(data.error || 'Failed to remove product from cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        // Restore button state
        button.innerHTML = originalContent;
        button.disabled = false;
    });
}

function updateCartCount(count) {
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count || 0;
        
        // Add animation
        cartCountElement.style.transform = 'scale(1.2)';
        setTimeout(() => {
            cartCountElement.style.transform = 'scale(1)';
        }, 200);
    }
}

function updateMiniCart(cart) {
    const miniCartContent = document.getElementById('mini-cart-content');
    if (!miniCartContent) return;
    
    if (cart && cart.length > 0) {
        let html = '';
        let total = 0;
        
        cart.forEach(item => {
            total += item.quantity * item.price;
            html += `
                <div class="cart-item" data-product-id="${item.product_id}">
                    <img src="${item.image}" alt="${item.name}" class="cart-item-image">
                    <div class="cart-item-details">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">Qty: ${item.quantity} × Rs. ${item.price.toFixed(2)}</div>
                    </div>
                    <button class="cart-item-remove remove-from-cart" data-product-id="${item.product_id}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        });
        
        html += `
            <div class="cart-dropdown-footer">
                <div class="cart-total">
                    <strong>Total: Rs. ${total.toFixed(2)}</strong>
                </div>
                <a href="/storefront/${getSubdomainFromUrl()}/cart" class="btn btn-primary btn-sm w-100">View Cart</a>
            </div>
        `;
        
        miniCartContent.innerHTML = html;
    } else {
        miniCartContent.innerHTML = `
            <div class="cart-empty">
                <i class="fas fa-shopping-cart"></i>
                <p>Your cart is empty</p>
            </div>
        `;
    }
}

// Search Functionality
function initSearch() {
    const searchToggleBtn = document.getElementById('searchToggleBtn');
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    
    if (!searchToggleBtn || !searchForm) {
        return;
    }
    
    // Show search form if there's already a search query
    if (searchInput && searchInput.value.trim() !== '') {
        searchForm.classList.add('active');
    }
    
    // Toggle search form on icon click
    searchToggleBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (searchForm.classList.contains('active')) {
            // Close search
            searchForm.classList.remove('active');
            if (searchInput) searchInput.blur();
        } else {
            // Open search
            searchForm.classList.add('active');
            // Focus input after animation starts
            setTimeout(() => {
                if (searchInput) {
                    searchInput.focus();
                }
            }, 150);
        }
    });
    
    // Close search when clicking outside
    document.addEventListener('click', function(e) {
        if (searchForm.classList.contains('active') && 
            !searchForm.contains(e.target) && 
            !searchToggleBtn.contains(e.target)) {
            searchForm.classList.remove('active');
            searchInput.blur();
        }
    });
    
    // Close search on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && searchForm.classList.contains('active')) {
            searchForm.classList.remove('active');
            searchInput.blur();
        }
    });
    
    // Auto-submit search (optional - can be removed if not needed)
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            // Remove auto-submit for better UX - user can press Enter or click search button
        });
        
        // Submit on Enter key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });
    }
}

// Mobile Menu
function initMobileMenu() {
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (!mobileMenuToggle || !mobileMenu) return;
    
    mobileMenuToggle.addEventListener('click', function() {
        const isExpanded = this.getAttribute('aria-expanded') === 'true';
        this.setAttribute('aria-expanded', !isExpanded);
        
        // Toggle icon
        const icon = this.querySelector('i');
        if (icon) {
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        }
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!mobileMenu.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
            mobileMenu.classList.remove('show');
            mobileMenuToggle.setAttribute('aria-expanded', 'false');
            
            const icon = mobileMenuToggle.querySelector('i');
            if (icon) {
                icon.classList.add('fa-bars');
                icon.classList.remove('fa-times');
            }
        }
    });
}

// Product Cards
function initProductCards() {
    // Quick view functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.js-quick-view')) {
            e.preventDefault();
            const card = e.target.closest('.product-card');
            const productId = card.dataset.productId;
            
            if (productId) {
                showQuickView(productId);
            }
        }
    });
    
    // Product card hover effects
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

function showQuickView(productId) {
    // This would typically open a modal with product details
    // For now, redirect to product page
    const subdomain = getSubdomainFromUrl();
    window.location.href = `/storefront/${subdomain}/product/${productId}`;
}

// Form Handling
function initForms() {
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });
    
    // Real-time validation
    const inputs = document.querySelectorAll('input[required], textarea[required], select[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            clearFieldError(this);
        });
    });
}

function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });
    
    return isValid;
}

function validateField(field) {
    const value = field.value.trim();
    const type = field.type;
    let isValid = true;
    let errorMessage = '';
    
    // Required field validation
    if (!value) {
        isValid = false;
        errorMessage = 'This field is required';
    }
    
    // Email validation
    if (type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            isValid = false;
            errorMessage = 'Please enter a valid email address';
        }
    }
    
    // Phone validation
    if (type === 'tel' && value) {
        const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
        if (!phoneRegex.test(value.replace(/\s/g, ''))) {
            isValid = false;
            errorMessage = 'Please enter a valid phone number';
        }
    }
    
    // Show/hide error
    if (isValid) {
        clearFieldError(field);
    } else {
        showFieldError(field, errorMessage);
    }
    
    return isValid;
}

function showFieldError(field, message) {
    clearFieldError(field);
    
    field.classList.add('is-invalid');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    
    field.parentNode.appendChild(errorDiv);
}

function clearFieldError(field) {
    field.classList.remove('is-invalid');
    
    const errorDiv = field.parentNode.querySelector('.invalid-feedback');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Animations
function initAnimations() {
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    const animateElements = document.querySelectorAll('.product-card, .category-card, .section-title');
    animateElements.forEach(el => {
        observer.observe(el);
    });
    
    // Counter animation
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.dataset.target);
        const duration = parseInt(counter.dataset.duration) || 2000;
        
        animateCounter(counter, target, duration);
    });
}

function animateCounter(element, target, duration) {
    let start = 0;
    const increment = target / (duration / 16);
    
    const timer = setInterval(() => {
        start += increment;
        element.textContent = Math.floor(start);
        
        if (start >= target) {
            element.textContent = target;
            clearInterval(timer);
        }
    }, 16);
}

// Notifications
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
            <span>${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${getNotificationColor(type)};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        max-width: 400px;
        animation: slideIn 0.3s ease-out;
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideOut 0.3s ease-in';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }
    }, 5000);
}

function getNotificationIcon(type) {
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    return icons[type] || 'info-circle';
}

function getNotificationColor(type) {
    const colors = {
        success: '#10b981',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6'
    };
    return colors[type] || '#3b82f6';
}

// Utility Functions
function getSubdomainFromUrl() {
    // First, try to get subdomain from path (e.g., /storefront/primax/...)
    const path = window.location.pathname;
    const pathMatch = path.match(/\/storefront\/([^\/]+)/);
    if (pathMatch && pathMatch[1]) {
        return pathMatch[1];
    }
    
    // If not in path, try to get from hostname (e.g., primax.localhost)
    const hostname = window.location.hostname;
    const hostnameParts = hostname.split('.');
    
    // For localhost or IP addresses, subdomain might be in path or query
    if (hostname === 'localhost' || hostname === '127.0.0.1' || hostname.startsWith('192.168.') || hostname.startsWith('10.')) {
        // Check if there's a subdomain in the URL path
        const urlParams = new URLSearchParams(window.location.search);
        const subdomainParam = urlParams.get('store');
        if (subdomainParam) {
            return subdomainParam;
        }
        // Try to extract from path
        if (pathMatch && pathMatch[1]) {
            return pathMatch[1];
        }
        // Default fallback - try to get from first path segment after /storefront/
        const segments = path.split('/').filter(s => s);
        const storefrontIndex = segments.indexOf('storefront');
        if (storefrontIndex !== -1 && segments[storefrontIndex + 1]) {
            return segments[storefrontIndex + 1];
        }
    } else {
        // For domain-based subdomains (e.g., primax.example.com)
        // The subdomain is the first part
        if (hostnameParts.length > 2) {
            return hostnameParts[0];
        }
    }
    
    // Last resort: return empty string (will cause 404, but better than undefined)
    console.warn('Could not determine subdomain from URL:', window.location.href);
    return '';
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 0.25rem;
        margin-left: auto;
    }
    
    .notification-close:hover {
        opacity: 0.8;
    }
`;
document.head.appendChild(style);




