@extends('admin.layouts.app')

@section('title', 'Products')
@section('page-title', 'Products')
@section('page-subtitle', 'Manage your product catalog')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">Dashboard</a>
    </div>
    <div class="breadcrumb-item active">Products</div>
@endsection

@section('content')
<!-- Action Bar -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" placeholder="Search products..." id="product-search" value="{{ request('search') }}">
        </div>
    </div>
    <div class="col-md-6 text-end">
        <div class="btn-group">
            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-filter"></i> Filters
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#" data-filter="all">All Products</a>
                <a class="dropdown-item" href="#" data-filter="active">Active Only</a>
                <a class="dropdown-item" href="#" data-filter="inactive">Inactive Only</a>
                <a class="dropdown-item" href="#" data-filter="low-stock">Low Stock</a>
            </div>
        </div>
        
        <div class="btn-group ms-2">
            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-download"></i> Export
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#" data-export="csv">Export CSV</a>
                <a class="dropdown-item" href="#" data-export="excel">Export Excel</a>
            </div>
        </div>
        
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary ms-2">
            <i class="fas fa-plus"></i> Add Product
        </a>
    </div>
</div>

<!-- Products Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Products ({{ $products->total() }})</h5>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" id="bulk-action">
                    <option value="">Bulk Actions</option>
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
                    <option value="delete">Delete</option>
                </select>
                <button type="button" class="btn btn-sm btn-outline-primary" id="apply-bulk-action">
                    Apply
                </button>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="select-all" class="form-check-input">
                        </th>
                        <th width="80">Image</th>
                        <th data-sort="name">Product Name</th>
                        <th data-sort="sku">SKU</th>
                        <th data-sort="price">Price</th>
                        <th data-sort="stock">Stock</th>
                        <th data-sort="status">Status</th>
                        <th data-sort="created_at">Created</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr data-product-id="{{ $product->id }}">
                        <td>
                            <input type="checkbox" class="form-check-input product-checkbox" value="{{ $product->id }}">
                        </td>
                        <td>
                            <div class="product-image">
                                @if($product->primary_image_url)
                                    <img src="{{ $product->primary_image_url }}" 
                                         alt="{{ $product->name }}" 
                                         class="img-fluid rounded"
                                         onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<div class=\'placeholder-image\'><i class=\'fas fa-image\'></i></div>';">
                                @else
                                    <div class="placeholder-image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                
                                @if(is_array($product->images) && count($product->images) > 1)
                                    <div class="image-count-badge">
                                        <i class="fas fa-images"></i>
                                        {{ count($product->images) }}
                                    </div>
                                @endif
                                
                                @if($product->video)
                                    <div class="video-badge">
                                        <i class="fas fa-video"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="product-info">
                                <h6 class="product-name">
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h6>
                                <small class="text-muted">{{ $product->category->name ?? 'Uncategorized' }}</small>
                            </div>
                        </td>
                        <td>
                            <code>{{ $product->sku ?? 'N/A' }}</code>
                        </td>
                        <td>
                            <div class="price-info">
                                <div class="current-price">Rs. {{ number_format($product->sale_price ?? $product->price, 2) }}</div>
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <small class="original-price">Rs. {{ number_format($product->price, 2) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="stock-info">
                                @if($product->track_inventory)
                                    <span class="stock-quantity">{{ $product->stock_quantity ?? 0 }}</span>
                                    @if($product->isLowStock())
                                        <small class="text-warning d-block">Low Stock</small>
                                    @endif
                                @else
                                    <span class="text-muted">Not Tracked</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="status-toggle">
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-toggle-input" type="checkbox" 
                                           {{ $product->is_active ? 'checked' : '' }} 
                                           data-product-id="{{ $product->id }}">
                                </div>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">{{ $product->created_at->format('M j, Y') }}</small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.products.show', $product->id) }}" 
                                   class="btn btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                   class="btn btn-outline-secondary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-outline-info duplicate-product" 
                                        data-product-id="{{ $product->id }}" title="Duplicate">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger delete-product" 
                                        data-product-id="{{ $product->id }}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                <h5>No products found</h5>
                                <p class="text-muted">Start by adding your first product.</p>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add Product
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($products->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
            </div>
            <div>
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.search-box {
    position: relative;
    max-width: 400px;
}

.search-box .search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    z-index: 2;
}

.search-box input {
    padding-left: 40px;
    border-radius: var(--radius-lg);
    border: 2px solid var(--gray-200);
    transition: var(--transition-fast);
}

.search-box input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
}

.product-image {
    position: relative;
    width: 60px;
    height: 60px;
    border-radius: var(--radius-sm);
    overflow: hidden;
    background: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-image {
    color: var(--gray-400);
    font-size: 1.25rem;
}

.image-count-badge, .video-badge {
    position: absolute;
    top: 2px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 2px 6px;
    border-radius: var(--radius-sm);
    font-size: 0.7rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 2px;
}

.image-count-badge {
    left: 2px;
}

.video-badge {
    right: 2px;
    background: rgba(239, 68, 68, 0.9);
}

.product-info {
    min-width: 200px;
}

.product-name {
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 2px;
    font-size: var(--font-size-sm);
}

.price-info {
    text-align: right;
}

.current-price {
    font-weight: 600;
    color: var(--primary-color);
    font-size: var(--font-size-sm);
}

.original-price {
    text-decoration: line-through;
    color: var(--gray-500);
}

.stock-info {
    text-align: center;
}

.stock-quantity {
    font-weight: 600;
    color: var(--gray-900);
}

.status-toggle .form-check-input {
    margin: 0;
}

.empty-state {
    text-align: center;
    padding: 2rem;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.table th[data-sort] {
    cursor: pointer;
    user-select: none;
    position: relative;
}

.table th[data-sort]:hover {
    background: var(--gray-50);
}

.table th[data-sort]::after {
    content: '↕';
    position: absolute;
    right: 8px;
    opacity: 0.5;
    font-size: 0.8rem;
}

.table th.sort-asc::after {
    content: '↑';
    opacity: 1;
}

.table th.sort-desc::after {
    content: '↓';
    opacity: 1;
}

@media (max-width: 768px) {
    .search-box {
        max-width: 100%;
        margin-bottom: 1rem;
    }
    
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin-bottom: 0.25rem;
    }
    
    .table-responsive {
        font-size: var(--font-size-xs);
    }
    
    .product-image {
        width: 40px;
        height: 40px;
    }
    
    .btn-group-sm .btn {
        padding: 0.125rem 0.25rem;
        font-size: 0.7rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeProductManagement();
});

// AjaxHelper fallback if not available
window.AjaxHelper = window.AjaxHelper || {
    put: function(url, data) {
        return fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        }).then(response => response.json());
    },
    post: function(url, data) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        }).then(response => response.json());
    },
    delete: function(url) {
        return fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => response.json());
    }
};

// AdminDashboard fallback/extension if not available or missing methods
if (!window.AdminDashboard) {
    window.AdminDashboard = {};
}

// Add showLoading if it doesn't exist
if (!window.AdminDashboard.showLoading) {
    window.AdminDashboard.showLoading = function(element, text) {
        const originalText = element.textContent || element.innerHTML;
        const originalDisabled = element.disabled;
        const originalHTML = element.innerHTML;
        
        if (text) {
            element.textContent = text;
        }
        element.disabled = true;
        element.classList.add('loading');
        
        // Return function to restore original state
        return function() {
            element.innerHTML = originalHTML;
            element.textContent = originalText;
            element.disabled = originalDisabled;
            element.classList.remove('loading');
        };
    };
}

// Add showNotification if it doesn't exist
if (!window.AdminDashboard.showNotification) {
    window.AdminDashboard.showNotification = function(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
        notification.style.zIndex = '9999';
        notification.style.minWidth = '300px';
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 150);
            }
        }, 5000);
    };
}

function initializeProductManagement() {
    // Search functionality
    const searchInput = document.getElementById('product-search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length > 2 || this.value.length === 0) {
                    performSearch(this.value);
                }
            }, 500);
        });
    }
    
    // Select all checkbox
    const selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const productCheckboxes = document.querySelectorAll('.product-checkbox');
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Status toggle
    document.querySelectorAll('.status-toggle-input').forEach(toggle => {
        toggle.addEventListener('change', function() {
            toggleProductStatus(this);
        });
    });
    
    // Duplicate product
    document.querySelectorAll('.duplicate-product').forEach(button => {
        button.addEventListener('click', function() {
            duplicateProduct(this.dataset.productId);
        });
    });
    
    // Delete product
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const productId = this.getAttribute('data-product-id') || this.dataset.productId;
            if (productId) {
                deleteProduct(productId);
            } else {
                console.error('Product ID not found on delete button');
                AdminDashboard.showNotification('Error: Product ID not found', 'error');
            }
        });
    });
    
    // Bulk actions
    const applyBulkActionBtn = document.getElementById('apply-bulk-action');
    if (applyBulkActionBtn) {
        applyBulkActionBtn.addEventListener('click', function() {
            const bulkAction = document.getElementById('bulk-action').value;
            if (bulkAction) {
                performBulkAction(bulkAction);
            }
        });
    }
    
    // Filter functionality
    document.querySelectorAll('[data-filter]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            applyFilter(this.dataset.filter);
        });
    });
    
    // Export functionality
    document.querySelectorAll('[data-export]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            exportProducts(this.dataset.export);
        });
    });
}

function performSearch(query) {
    const url = new URL(window.location);
    if (query) {
        url.searchParams.set('search', query);
    } else {
        url.searchParams.delete('search');
    }
    window.location.href = url.toString();
}

function toggleProductStatus(toggle) {
    const productId = toggle.dataset.productId;
    const isActive = toggle.checked;
    
    const loadingState = AdminDashboard.showLoading(toggle, 'Updating...');
    
    AjaxHelper.put(`{{ url('admin/products') }}/${productId}/toggle-status`, {
        is_active: isActive
    })
    .then(response => {
        if (response.success !== false) {
            AdminDashboard.showNotification(response.message || 'Product status updated successfully', 'success');
        } else {
            throw new Error(response.message || 'Failed to update product status');
        }
    })
    .catch(error => {
        // Revert toggle state
        toggle.checked = !isActive;
        AdminDashboard.showNotification(error.message || 'Failed to update product status', 'error');
        console.error('Error updating product status:', error);
    })
    .finally(() => {
        loadingState();
    });
}

function duplicateProduct(productId) {
    if (!confirm('Are you sure you want to duplicate this product?')) {
        return;
    }
    
    const button = document.querySelector(`[data-product-id="${productId}"].duplicate-product`);
    const loadingState = AdminDashboard.showLoading(button, 'Duplicating...');
    
    AjaxHelper.post(`{{ url('admin/products') }}/${productId}/duplicate`)
    .then(response => {
        if (response.success !== false) {
            AdminDashboard.showNotification(response.message || 'Product duplicated successfully', 'success');
            // Reload page to show new product
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(response.message || 'Failed to duplicate product');
        }
    })
    .catch(error => {
        AdminDashboard.showNotification(error.message || 'Failed to duplicate product', 'error');
        console.error('Error duplicating product:', error);
    })
    .finally(() => {
        loadingState();
    });
}

function deleteProduct(productId) {
    if (!productId) {
        console.error('Product ID is required');
        AdminDashboard.showNotification('Error: Product ID is missing', 'error');
        return;
    }
    
    if (!confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        return;
    }
    
    const button = document.querySelector(`[data-product-id="${productId}"].delete-product`);
    if (!button) {
        console.error('Delete button not found for product:', productId);
        AdminDashboard.showNotification('Error: Delete button not found', 'error');
        return;
    }
    
    const loadingState = AdminDashboard.showLoading(button, 'Deleting...');
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.error('CSRF token not found');
        AdminDashboard.showNotification('Error: CSRF token not found. Please refresh the page.', 'error');
        loadingState();
        return;
    }
    
    // Use the destroyJson route which handles JSON responses better
    const url = `{{ url('admin/products') }}/${productId}/delete`;
    
    console.log('Deleting product:', productId, 'URL:', url);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async response => {
        // Get response text first to see what we're dealing with
        const responseText = await response.text();
        console.log('Delete response status:', response.status);
        console.log('Delete response text:', responseText);
        
        // Try to parse as JSON
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (e) {
            // If not JSON, might be HTML error page
            if (response.status === 419) {
                throw new Error('Session expired. Please refresh the page and try again.');
            } else if (response.status === 403) {
                throw new Error('You do not have permission to delete this product.');
            } else if (response.status === 404) {
                throw new Error('Product not found. It may have already been deleted.');
            } else if (response.status === 500) {
                throw new Error('Server error occurred. Please check the logs or try again later.');
            } else {
                throw new Error(`Unexpected response format. Status: ${response.status}`);
            }
        }
        
        // Check if response is ok
        if (!response.ok) {
            throw new Error(data.message || data.error || `Server error: ${response.status}`);
        }
        
        return data;
    })
    .then(data => {
        console.log('Delete response data:', data);
        
        if (data.success !== false && data.success !== 0) {
            AdminDashboard.showNotification(data.message || 'Product deleted successfully', 'success');
            // Remove row from table
            const row = document.querySelector(`tr[data-product-id="${productId}"]`);
            if (row) {
                row.style.opacity = '0';
                row.style.transition = 'opacity 0.3s';
                setTimeout(() => {
                    row.remove();
                    // Update product count if displayed
                    const productCount = document.querySelector('.card-title');
                    if (productCount) {
                        const currentText = productCount.textContent;
                        const match = currentText.match(/\((\d+)\)/);
                        if (match) {
                            const newCount = Math.max(0, parseInt(match[1]) - 1);
                            productCount.textContent = currentText.replace(/\(\d+\)/, `(${newCount})`);
                        }
                    }
                }, 300);
            } else {
                // If row not found, reload page to reflect changes
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        } else {
            throw new Error(data.message || data.error || 'Failed to delete product');
        }
    })
    .catch(error => {
        console.error('Delete error details:', {
            error: error,
            message: error.message,
            stack: error.stack,
            productId: productId
        });
        const errorMessage = error.message || (typeof error === 'string' ? error : 'Failed to delete product. Please try again.');
        AdminDashboard.showNotification(errorMessage, 'error');
    })
    .finally(() => {
        if (loadingState) loadingState();
    });
}

function performBulkAction(action) {
    const selectedProducts = Array.from(document.querySelectorAll('.product-checkbox:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedProducts.length === 0) {
        AdminDashboard.showNotification('Please select at least one product', 'warning');
        return;
    }
    
    if (!confirm(`Are you sure you want to ${action} ${selectedProducts.length} product(s)?`)) {
        return;
    }
    
    const button = document.getElementById('apply-bulk-action');
    const loadingState = AdminDashboard.showLoading(button, 'Processing...');
    
    AjaxHelper.post('{{ route("admin.products.bulk-action") }}', {
        action: action,
        product_ids: selectedProducts
    })
    .then(response => {
        AdminDashboard.showNotification(response.message || 'Bulk action completed successfully', 'success');
        // Reload page to reflect changes
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    })
    .catch(error => {
        AdminDashboard.showNotification(error.message || 'Failed to perform bulk action', 'error');
    })
    .finally(() => {
        loadingState();
    });
}

function applyFilter(filter) {
    const url = new URL(window.location);
    if (filter === 'all') {
        url.searchParams.delete('filter');
    } else {
        url.searchParams.set('filter', filter);
    }
    window.location.href = url.toString();
}

function exportProducts(format) {
    const url = new URL(window.location);
    url.searchParams.set('export', format);
    window.open(url.toString(), '_blank');
}
</script>
@endpush