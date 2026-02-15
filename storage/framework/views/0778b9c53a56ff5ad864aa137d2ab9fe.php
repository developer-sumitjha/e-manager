<?php $__env->startSection('title', 'Add Product'); ?>
<?php $__env->startSection('page-title', 'Add Product'); ?>
<?php $__env->startSection('page-subtitle', 'Create a new product for your catalog'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <div class="breadcrumb-item">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="breadcrumb-link">Dashboard</a>
    </div>
    <div class="breadcrumb-item">
        <a href="<?php echo e(route('admin.products.index')); ?>" class="breadcrumb-link">Products</a>
    </div>
    <div class="breadcrumb-item active">Add Product</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('admin.products.store')); ?>" method="POST" enctype="multipart/form-data" id="product-form">
    <?php echo csrf_field(); ?>
    
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
                                <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="name" name="name" value="<?php echo e(old('name')); ?>" required>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sku" class="form-label">SKU</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="sku" name="sku" value="<?php echo e(old('sku')); ?>">
                                <small class="form-text text-muted">Leave empty to auto-generate</small>
                                <?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="description" name="description" rows="4"><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                           class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="price" name="price" value="<?php echo e(old('price')); ?>" required>
                                </div>
                                <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sale_price" class="form-label">Sale Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs.</span>
                                    <input type="number" step="0.01" min="0" 
                                           class="form-control <?php $__errorArgs = ['sale_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="sale_price" name="sale_price" value="<?php echo e(old('sale_price')); ?>">
                                </div>
                                <?php $__errorArgs = ['sale_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                   name="track_inventory" value="1" <?php echo e(old('track_inventory') ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="track_inventory">
                                Track inventory for this product
                            </label>
                        </div>
                    </div>
                    
                    <!-- Hidden stock field for backward compatibility -->
                    <input type="hidden" id="stock" name="stock" value="<?php echo e(old('stock', 0)); ?>">
                    
                    <div id="inventory-fields" style="display: <?php echo e(old('track_inventory') ? 'block' : 'none'); ?>;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                    <input type="number" min="0" 
                                           class="form-control <?php $__errorArgs = ['stock_quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="stock_quantity" name="stock_quantity" value="<?php echo e(old('stock_quantity', 0)); ?>">
                                    <?php $__errorArgs = ['stock_quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="low_stock_threshold" class="form-label">Low Stock Threshold</label>
                                    <input type="number" min="0" 
                                           class="form-control <?php $__errorArgs = ['low_stock_threshold'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="low_stock_threshold" name="low_stock_threshold" value="<?php echo e(old('low_stock_threshold', 5)); ?>">
                                    <?php $__errorArgs = ['low_stock_threshold'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="allow_backorders" 
                                       name="allow_backorders" value="1" <?php echo e(old('allow_backorders') ? 'checked' : ''); ?>>
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
                        <input type="file" class="form-control <?php $__errorArgs = ['images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="images" name="images[]" multiple accept="image/*">
                        <small class="form-text text-muted">You can select multiple images. First image will be the primary image.</small>
                        <?php $__errorArgs = ['images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <!-- Image Preview -->
                    <div id="image-preview" class="row mt-3" style="display: none;">
                        <!-- Preview images will be added here -->
                    </div>
                    
                    <!-- Video -->
                    <div class="form-group">
                        <label for="video" class="form-label">Product Video</label>
                        <input type="file" class="form-control <?php $__errorArgs = ['video'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="video" name="video" accept="video/*">
                        <small class="form-text text-muted">Optional product demonstration video.</small>
                        <?php $__errorArgs = ['video'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        <div class="form-check" style="pointer-events: auto;">
                            <input class="form-check-input" type="checkbox" id="is_active" 
                                   name="is_active" value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>

                                   style="cursor: pointer; pointer-events: auto !important; position: relative; z-index: 999; width: 1.25em; height: 1.25em; margin-top: 0.25em; flex-shrink: 0;">
                            <label class="form-check-label" for="is_active" style="cursor: pointer; pointer-events: auto; margin-left: 0.5em; user-select: none;">
                                Active (visible to customers)
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_featured" 
                                   name="is_featured" value="1" <?php echo e(old('is_featured') ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="is_featured">
                                Featured product
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Product
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearProductForm()">
                            <i class="fas fa-times"></i> Clear Form
                        </button>
                        <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-outline-secondary">
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
                        <select class="form-select <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="category_id" name="category_id" required>
                            <option value="">Select a category</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        <input type="text" class="form-control <?php $__errorArgs = ['meta_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="meta_title" name="meta_title" value="<?php echo e(old('meta_title')); ?>">
                        <small class="form-text text-muted">Leave empty to use product name</small>
                        <?php $__errorArgs = ['meta_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control <?php $__errorArgs = ['meta_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="meta_description" name="meta_description" rows="3"><?php echo e(old('meta_description')); ?></textarea>
                        <small class="form-text text-muted">Leave empty to use product description</small>
                        <?php $__errorArgs = ['meta_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
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

.form-check-input {
    width: 1.25em !important;
    height: 1.25em !important;
    margin-top: 0.25em;
    vertical-align: top;
    background-color: #fff;
    border: 2px solid #000000 !important;
    border-radius: 0.25em;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
    position: relative;
    flex-shrink: 0;
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
}

.form-check-input:checked {
    background-color: #000000 !important;
    border-color: #000000 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat;
    background-position: center;
    background-size: 100% 100%;
}

.form-check-input:focus {
    border-color: #000000 !important;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25) !important;
}

.form-check-label {
    cursor: pointer;
    margin-left: 0.5rem;
    user-select: none;
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// AjaxHelper fallback if not available
window.AjaxHelper = window.AjaxHelper || {
    post: function(url, data) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        });
    },
    put: function(url, data) {
        return fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        });
    },
    delete: function(url) {
        return fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        });
    }
};

// AdminDashboard fallback if not available
if (!window.AdminDashboard) {
    window.AdminDashboard = {};
}

// Add showLoading if it doesn't exist
if (!window.AdminDashboard.showLoading) {
    window.AdminDashboard.showLoading = function(element, text) {
        if (!element) return function() {};
        
        const originalText = element.innerHTML;
        element.disabled = true;
        if (text) {
            element.innerHTML = text;
        }
        
        return function() {
            element.disabled = false;
            element.innerHTML = originalText;
        };
    };
}

// Add showNotification if it doesn't exist
if (!window.AdminDashboard.showNotification) {
    window.AdminDashboard.showNotification = function(message, type = 'info') {
        // Try to use Bootstrap toast if available
        if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
            const toastContainer = document.querySelector('.toast-container') || (function() {
                const container = document.createElement('div');
                container.className = 'toast-container position-fixed top-0 end-0 p-3';
                container.style.zIndex = '9999';
                document.body.appendChild(container);
                return container;
            })();
            
            const toastElement = document.createElement('div');
            toastElement.className = 'toast align-items-center text-white bg-' + (type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info') + ' border-0';
            toastElement.setAttribute('role', 'alert');
            toastElement.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            toastContainer.appendChild(toastElement);
            
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
            
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        } else {
            // Fallback to alert
            alert(message);
        }
    };
}

document.addEventListener('DOMContentLoaded', function() {
    initializeProductForm();
    
    // Ensure stock field is initialized on page load
    const stockHiddenInput = document.getElementById('stock');
    if (stockHiddenInput && !stockHiddenInput.value) {
        stockHiddenInput.value = 0;
    }
});

function initializeProductForm() {
    // Inventory toggle
    const trackInventoryCheckbox = document.getElementById('track_inventory');
    const inventoryFields = document.getElementById('inventory-fields');
    const stockQuantityInput = document.getElementById('stock_quantity');
    const stockHiddenInput = document.getElementById('stock');
    
    if (trackInventoryCheckbox) {
        trackInventoryCheckbox.addEventListener('change', function() {
            inventoryFields.style.display = this.checked ? 'block' : 'none';
            // Sync stock field when inventory tracking is toggled
            syncStockField();
        });
    }
    
    // Sync stock_quantity to stock field (for backward compatibility)
    function syncStockField() {
        if (stockQuantityInput && stockHiddenInput) {
            stockHiddenInput.value = stockQuantityInput.value || 0;
        }
    }
    
    if (stockQuantityInput) {
        stockQuantityInput.addEventListener('input', syncStockField);
        // Initial sync
        syncStockField();
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
    
    // Function to refresh CSRF token from meta tag
    function refreshCSRFToken() {
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        const formToken = document.querySelector('input[name="_token"]');
        
        if (metaToken && formToken) {
            const newToken = metaToken.getAttribute('content');
            if (newToken) {
                formToken.value = newToken;
                return newToken;
            }
        }
        return null;
    }
    
    // Sync CSRF token from meta tag to form input periodically (every 2 minutes)
    // This ensures the form always has the latest token
    setInterval(function() {
        refreshCSRFToken();
    }, 2 * 60 * 1000); // Every 2 minutes
    
    // Product form validation
    const productForm = document.getElementById('product-form');
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            // Refresh CSRF token from meta tag before submission
            refreshCSRFToken();
            
            // Ensure stock field is always set before submission
            syncStockField();
            
            // If track_inventory is not checked, set stock to 0
            if (!trackInventoryCheckbox || !trackInventoryCheckbox.checked) {
                if (stockHiddenInput) {
                    stockHiddenInput.value = 0;
                }
                if (stockQuantityInput) {
                    stockQuantityInput.value = 0;
                }
            }
            
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
    
    // Fix for is_active checkbox not toggling - comprehensive fix
    const isActiveCheckbox = document.getElementById('is_active');
    if (isActiveCheckbox) {
        // Remove any attributes that might prevent interaction
        isActiveCheckbox.removeAttribute('readonly');
        isActiveCheckbox.removeAttribute('disabled');
        isActiveCheckbox.setAttribute('tabindex', '0');
        
        // Force styles to ensure it's clickable
        isActiveCheckbox.style.setProperty('pointer-events', 'auto', 'important');
        isActiveCheckbox.style.setProperty('cursor', 'pointer', 'important');
        isActiveCheckbox.style.setProperty('position', 'relative', 'important');
        isActiveCheckbox.style.setProperty('z-index', '999', 'important');
        isActiveCheckbox.style.setProperty('opacity', '1', 'important');
        
        // Ensure label works
        const label = document.querySelector('label[for="is_active"]');
        if (label) {
            label.style.setProperty('pointer-events', 'auto', 'important');
            label.style.setProperty('cursor', 'pointer', 'important');
        }
        
        // Ensure form-check container doesn't block
        const formCheck = isActiveCheckbox.closest('.form-check');
        if (formCheck) {
            formCheck.style.setProperty('pointer-events', 'auto', 'important');
        }
        
        // Add explicit click handler that ensures toggle works
        isActiveCheckbox.addEventListener('click', function(e) {
            // The browser should handle the toggle, but ensure it happens
            console.log('Checkbox clicked, will be:', !this.checked);
            // Don't prevent default - let browser handle it
        }, false);
        
        // Monitor change events
        isActiveCheckbox.addEventListener('change', function(e) {
            console.log('Checkbox state changed to:', this.checked);
            // Force visual update by triggering reflow
            this.style.display = 'none';
            void this.offsetHeight;
            this.style.display = '';
        }, false);
        
        // Also handle mousedown to catch it early
        isActiveCheckbox.addEventListener('mousedown', function(e) {
            console.log('Checkbox mousedown');
            e.stopPropagation(); // Prevent any parent handlers
        }, true); // Use capture phase
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
    if (!form) {
        console.error('Category form not found');
        return;
    }
    
    const formData = new FormData(form);
    const categoryName = formData.get('name');
    
    // Validate category name
    if (!categoryName || !categoryName.trim()) {
        AdminDashboard.showNotification('Category name is required', 'error');
        return;
    }
    
    const submitButton = form.querySelector('button[type="submit"]');
    const loadingState = AdminDashboard.showLoading(submitButton, 'Creating...');
    
    AjaxHelper.post('<?php echo e(route("admin.categories.store")); ?>', {
        name: categoryName.trim(),
        description: formData.get('description') || '',
        is_active: true
    })
    .then(response => {
        if (response.success && response.category) {
            AdminDashboard.showNotification(response.message || 'Category created successfully', 'success');
            
            // Add new category to select
            const categorySelect = document.getElementById('category_id');
            if (categorySelect) {
                const newOption = document.createElement('option');
                newOption.value = response.category.id;
                newOption.textContent = response.category.name;
                newOption.selected = true;
                categorySelect.appendChild(newOption);
            }
            
            // Close modal and reset form
            const modalElement = document.getElementById('categoryModal');
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
            }
            form.reset();
        } else {
            throw new Error(response.message || 'Failed to create category');
        }
    })
    .catch(error => {
        console.error('Category creation error:', error);
        const errorMessage = error.message || error.error?.message || 'Failed to create category. Please try again.';
        AdminDashboard.showNotification(errorMessage, 'error');
    })
    .finally(() => {
        loadingState();
    });
}

// Form auto-save functionality
let autoSaveTimeout;
let formHasBeenModified = false;

document.querySelectorAll('#product-form input, #product-form textarea, #product-form select').forEach(field => {
    field.addEventListener('input', function() {
        formHasBeenModified = true;
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Only auto-save if form has been modified by user
            if (formHasBeenModified) {
                // Auto-save to localStorage
                const formData = new FormData(document.getElementById('product-form'));
                const data = {};
                for (let [key, value] of formData.entries()) {
                    data[key] = value;
                }
                // Add timestamp to track when draft was saved
                data._timestamp = Date.now();
                localStorage.setItem('product-draft', JSON.stringify(data));
            }
        }, 2000);
    });
});

// Load draft on page load - only if there's no old() input (validation errors)
// But DON'T load on fresh page loads - only if user was actively editing
document.addEventListener('DOMContentLoaded', function() {
    // Check if there's any old() input data (from validation errors)
    const nameField = document.getElementById('name');
    const hasOldInput = nameField && nameField.value && nameField.value.trim() !== '';
    const hasValidationErrors = <?php echo json_encode($errors->any(), 15, 512) ?>;
    
    // If there are validation errors or old input, clear the draft
    if (hasOldInput || hasValidationErrors) {
        localStorage.removeItem('product-draft');
        return;
    }
    
    // For fresh page loads, don't auto-load draft
    // Only load if user explicitly wants it (via a button or if they start typing)
    // This prevents the "previous data" issue when clicking "Add Product"
    const draft = localStorage.getItem('product-draft');
    if (draft && nameField && !nameField.value) {
        // Check if draft was saved recently (within last hour)
        try {
            const data = JSON.parse(draft);
            const draftTimestamp = data._timestamp || 0;
            const oneHourAgo = Date.now() - (60 * 60 * 1000);
            
            // Only load if draft is recent (within 1 hour) and form is empty
            if (draftTimestamp > oneHourAgo) {
                // Don't auto-load - let user decide
                // Instead, show a notification that draft exists
                console.log('Draft available. Use "Restore Draft" if needed.');
            } else {
                // Old draft - remove it
                localStorage.removeItem('product-draft');
            }
        } catch (e) {
            console.error('Error checking draft:', e);
            localStorage.removeItem('product-draft');
        }
    }
});

// Clear draft on successful submit
document.getElementById('product-form').addEventListener('submit', function(e) {
    // Ensure CSRF token is fresh before submission
    refreshCSRFToken();
    localStorage.removeItem('product-draft');
});

// Add function to clear form and draft
function clearProductForm() {
    if (confirm('Are you sure you want to clear all form data? This cannot be undone.')) {
        document.getElementById('product-form').reset();
        localStorage.removeItem('product-draft');
        formHasBeenModified = false;
        
        // Clear image previews
        const imagePreview = document.getElementById('image-preview');
        if (imagePreview) {
            imagePreview.innerHTML = '';
        }
        
        // Clear file inputs
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.value = '';
        });
        
        // Reset checkboxes - but keep is_active checked by default
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            if (checkbox.id === 'is_active') {
                checkbox.checked = true; // Keep is_active checked by default
            } else {
                checkbox.checked = false;
            }
        });
        
        // Reset stock to 0
        const stockInput = document.getElementById('stock');
        const stockQuantityInput = document.getElementById('stock_quantity');
        if (stockInput) stockInput.value = 0;
        if (stockQuantityInput) stockQuantityInput.value = 0;
        
        // Reset category
        const categorySelect = document.getElementById('category_id');
        if (categorySelect) categorySelect.selectedIndex = 0;
        
        alert('Form cleared successfully!');
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/products/create.blade.php ENDPATH**/ ?>