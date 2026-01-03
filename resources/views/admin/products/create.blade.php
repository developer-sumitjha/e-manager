@extends('admin.layouts.app')

@section('title', 'Add Product')
@section('page-title', 'Add Product')
@section('page-subtitle', 'Create a new product for your catalog')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">Dashboard</a>
    </div>
    <div class="breadcrumb-item">
        <a href="{{ route('admin.products.index') }}" class="breadcrumb-link">Products</a>
    </div>
    <div class="breadcrumb-item active">Add Product</div>
@endsection

@section('content')
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
    @csrf
    
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name" class="form-label">Product Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sku" class="form-label">SKU</label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                       id="sku" name="sku" value="{{ old('sku') }}">
                                <small class="form-text text-muted">Leave empty to auto-generate</small>
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Pricing -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Pricing</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price" class="form-label">Regular Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs.</span>
                                    <input type="number" step="0.01" min="0" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price') }}" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sale_price" class="form-label">Sale Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs.</span>
                                    <input type="number" step="0.01" min="0" 
                                           class="form-control @error('sale_price') is-invalid @enderror" 
                                           id="sale_price" name="sale_price" value="{{ old('sale_price') }}">
                                </div>
                                @error('sale_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Inventory -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Inventory</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="track_inventory" 
                                   name="track_inventory" value="1" {{ old('track_inventory') ? 'checked' : '' }}>
                            <label class="form-check-label" for="track_inventory">
                                Track inventory for this product
                            </label>
                        </div>
                    </div>
                    
                    <div id="inventory-fields" style="display: {{ old('track_inventory') ? 'block' : 'none' }};">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                    <input type="number" min="0" 
                                           class="form-control @error('stock_quantity') is-invalid @enderror" 
                                           id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}">
                                    @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="low_stock_threshold" class="form-label">Low Stock Threshold</label>
                                    <input type="number" min="0" 
                                           class="form-control @error('low_stock_threshold') is-invalid @enderror" 
                                           id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}">
                                    @error('low_stock_threshold')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="allow_backorders" 
                                       name="allow_backorders" value="1" {{ old('allow_backorders') ? 'checked' : '' }}>
                                <label class="form-check-label" for="allow_backorders">
                                    Allow backorders when out of stock
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Media -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Media</h5>
                </div>
                <div class="card-body">
                    <!-- Images -->
                    <div class="form-group">
                        <label for="images" class="form-label">Product Images</label>
                        <input type="file" class="form-control @error('images') is-invalid @enderror" 
                               id="images" name="images[]" multiple accept="image/*">
                        <small class="form-text text-muted">You can select multiple images. First image will be the primary image.</small>
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Image Preview -->
                    <div id="image-preview" class="row mt-3" style="display: none;">
                        <!-- Preview images will be added here -->
                    </div>
                    
                    <!-- Video -->
                    <div class="form-group">
                        <label for="video" class="form-label">Product Video</label>
                        <input type="file" class="form-control @error('video') is-invalid @enderror" 
                               id="video" name="video" accept="video/*">
                        <small class="form-text text-muted">Optional product demonstration video.</small>
                        @error('video')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Publish -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Publish</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" 
                                   name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (visible to customers)
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_featured" 
                                   name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured product
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Category -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Category</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="category_id" class="form-label">Category *</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                id="category_id" name="category_id" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                            <i class="fas fa-plus"></i> New Category
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- SEO -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">SEO</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                               id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                        <small class="form-text text-muted">Leave empty to use product name</small>
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                  id="meta_description" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                        <small class="form-text text-muted">Leave empty to use product description</small>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="category-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category_name" class="form-label">Category Name *</label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="category_description" class="form-label">Description</label>
                        <textarea class="form-control" id="category_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.image-preview-item {
    position: relative;
    margin-bottom: 1rem;
}

.image-preview-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: var(--radius-sm);
    border: 2px solid var(--gray-200);
}

.image-preview-item .remove-image {
    position: absolute;
    top: 8px;
    right: 8px;
    background: var(--danger-color);
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.image-preview-item .primary-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: var(--primary-color);
    color: white;
    padding: 4px 8px;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-md);
    transition: var(--transition-fast);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
}

.input-group-text {
    background: var(--gray-100);
    border: 2px solid var(--gray-200);
    border-right: none;
    color: var(--gray-600);
    font-weight: 500;
}

.input-group .form-control {
    border-left: none;
}

.input-group .form-control:focus {
    border-left: none;
}

.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.card {
    border: none;
    box-shadow: var(--shadow-sm);
}

.card-header {
    background: var(--gray-50);
    border-bottom: 1px solid var(--gray-200);
}

.card-title {
    font-weight: 600;
    color: var(--gray-900);
    margin: 0;
}

.btn {
    border-radius: var(--radius-md);
    font-weight: 500;
    transition: var(--transition-fast);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

@media (max-width: 768px) {
    .col-lg-4 {
        margin-top: 2rem;
    }
    
    .image-preview-item img {
        height: 120px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeProductForm();
});

function initializeProductForm() {
    // Inventory toggle
    const trackInventoryCheckbox = document.getElementById('track_inventory');
    const inventoryFields = document.getElementById('inventory-fields');
    
    if (trackInventoryCheckbox) {
        trackInventoryCheckbox.addEventListener('change', function() {
            inventoryFields.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    // Image preview
    const imagesInput = document.getElementById('images');
    if (imagesInput) {
        imagesInput.addEventListener('change', function() {
            previewImages(this.files);
        });
    }
    
    // Category form
    const categoryForm = document.getElementById('category-form');
    if (categoryForm) {
        categoryForm.addEventListener('submit', function(e) {
            e.preventDefault();
            createCategory();
        });
    }
    
    // Product form validation
    const productForm = document.getElementById('product-form');
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            if (!FormValidator.validateForm(this)) {
                e.preventDefault();
            }
        });
    }
    
    // Auto-generate SKU
    const nameInput = document.getElementById('name');
    const skuInput = document.getElementById('sku');
    
    if (nameInput && skuInput) {
        nameInput.addEventListener('input', function() {
            if (!skuInput.value) {
                generateSKU(this.value);
            }
        });
    }
}

function previewImages(files) {
    const previewContainer = document.getElementById('image-preview');
    previewContainer.innerHTML = '';
    
    if (files.length === 0) {
        previewContainer.style.display = 'none';
        return;
    }
    
    previewContainer.style.display = 'block';
    
    Array.from(files).forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewItem = document.createElement('div');
                previewItem.className = 'col-md-4 image-preview-item';
                previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="Preview ${index + 1}">
                    ${index === 0 ? '<div class="primary-badge">Primary</div>' : ''}
                    <button type="button" class="remove-image" onclick="removeImagePreview(this)">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                previewContainer.appendChild(previewItem);
            };
            reader.readAsDataURL(file);
        }
    });
}

function removeImagePreview(button) {
    const previewItem = button.closest('.image-preview-item');
    previewItem.remove();
    
    // Update file input
    updateFileInput();
}

function updateFileInput() {
    // This would need to be implemented to actually remove files from the input
    // For now, we'll just hide the preview if no images are left
    const previewContainer = document.getElementById('image-preview');
    if (previewContainer.children.length === 0) {
        previewContainer.style.display = 'none';
    }
}

function generateSKU(name) {
    if (!name) return;
    
    // Generate SKU from product name
    const sku = name
        .toLowerCase()
        .replace(/[^a-z0-9\s]/g, '')
        .replace(/\s+/g, '-')
        .substring(0, 20)
        + '-' + Date.now().toString().slice(-4);
    
    document.getElementById('sku').value = sku;
}

function createCategory() {
    const form = document.getElementById('category-form');
    const formData = new FormData(form);
    
    const loadingState = AdminDashboard.showLoading(form.querySelector('button[type="submit"]'), 'Creating...');
    
    AjaxHelper.post('/admin/categories', {
        name: formData.get('name'),
        description: formData.get('description')
    })
    .then(response => {
        AdminDashboard.showNotification(response.message || 'Category created successfully', 'success');
        
        // Add new category to select
        const categorySelect = document.getElementById('category_id');
        const newOption = document.createElement('option');
        newOption.value = response.category.id;
        newOption.textContent = response.category.name;
        newOption.selected = true;
        categorySelect.appendChild(newOption);
        
        // Close modal and reset form
        const modal = bootstrap.Modal.getInstance(document.getElementById('categoryModal'));
        modal.hide();
        form.reset();
    })
    .catch(error => {
        AdminDashboard.showNotification(error.message || 'Failed to create category', 'error');
    })
    .finally(() => {
        loadingState();
    });
}

// Form auto-save functionality
let autoSaveTimeout;
document.querySelectorAll('#product-form input, #product-form textarea, #product-form select').forEach(field => {
    field.addEventListener('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Auto-save to localStorage
            const formData = new FormData(document.getElementById('product-form'));
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            localStorage.setItem('product-draft', JSON.stringify(data));
        }, 2000);
    });
});

// Load draft on page load
window.addEventListener('load', function() {
    const draft = localStorage.getItem('product-draft');
    if (draft && !document.getElementById('name').value) {
        try {
            const data = JSON.parse(draft);
            Object.keys(data).forEach(key => {
                const field = document.querySelector(`[name="${key}"]`);
                if (field) {
                    field.value = data[key];
                }
            });
        } catch (e) {
            console.error('Error loading draft:', e);
        }
    }
});

// Clear draft on successful submit
document.getElementById('product-form').addEventListener('submit', function() {
    localStorage.removeItem('product-draft');
});
</script>
@endpush