<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="page-title">
        <h1><i class="fas fa-file-plus"></i> Create New Page</h1>
        <p class="text-muted">Add a new page to your website</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('admin.site-pages.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Pages
        </a>
    </div>
</div>

<form action="<?php echo e(route('admin.site-pages.store')); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Page Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('title')); ?>" required>
                        <?php $__errorArgs = ['title'];
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
                    
                    <div class="mb-3">
                        <label class="form-label">Slug (URL)</label>
                        <input type="text" name="slug" class="form-control <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('slug')); ?>" placeholder="Leave blank to auto-generate">
                        <small class="text-muted">Will be auto-generated from title if left blank</small>
                        <?php $__errorArgs = ['slug'];
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
                    
                    <div class="mb-3">
                        <label class="form-label">Short Description</label>
                        <textarea name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="2" placeholder="Brief description of the page"><?php echo e(old('description')); ?></textarea>
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
                    
                    <div class="mb-3">
                        <label class="form-label">Page Content</label>
                        <textarea name="content" id="pageContent" class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="15" placeholder="Write your page content here..."><?php echo e(old('content')); ?></textarea>
                        <small class="text-muted">You can use HTML for formatting</small>
                        <?php $__errorArgs = ['content'];
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
            
            <!-- Contact Page Fields -->
            <div class="card mb-4" id="contactFields" style="display: none;">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-envelope"></i> Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="form-control" value="<?php echo e(old('contact_email')); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-control" value="<?php echo e(old('contact_phone')); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Contact Address</label>
                        <textarea name="contact_address" class="form-control" rows="3"><?php echo e(old('contact_address')); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Google Maps Iframe</label>
                        <textarea name="map_iframe" class="form-control" rows="3" placeholder='<iframe src="..." ...></iframe>'><?php echo e(old('map_iframe')); ?></textarea>
                        <small class="text-muted">Paste your Google Maps embed code here</small>
                    </div>
                </div>
            </div>
            
            <!-- SEO Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-search"></i> SEO Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="<?php echo e(old('meta_title')); ?>" maxlength="60">
                        <small class="text-muted">Recommended: 50-60 characters</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="2" maxlength="160"><?php echo e(old('meta_description')); ?></textarea>
                        <small class="text-muted">Recommended: 150-160 characters</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" class="form-control" value="<?php echo e(old('meta_keywords')); ?>" placeholder="keyword1, keyword2, keyword3">
                    </div>
                </div>
            </div>
            
            <!-- Custom Code -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-code"></i> Custom Code</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Custom CSS</label>
                        <textarea name="custom_css" class="form-control" rows="4" placeholder="/* Add your custom CSS here */"><?php echo e(old('custom_css')); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Custom JavaScript</label>
                        <textarea name="custom_js" class="form-control" rows="4" placeholder="// Add your custom JavaScript here"><?php echo e(old('custom_js')); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Settings -->
        <div class="col-lg-4">
            <!-- Page Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog"></i> Page Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Page Type <span class="text-danger">*</span></label>
                        <select name="page_type" id="pageType" class="form-select <?php $__errorArgs = ['page_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="custom" <?php echo e(old('page_type') == 'custom' ? 'selected' : ''); ?>>Custom Page</option>
                            <option value="about" <?php echo e(old('page_type') == 'about' ? 'selected' : ''); ?>>About Us</option>
                            <option value="contact" <?php echo e(old('page_type') == 'contact' ? 'selected' : ''); ?>>Contact Us</option>
                            <option value="products" <?php echo e(old('page_type') == 'products' ? 'selected' : ''); ?>>Products</option>
                            <option value="categories" <?php echo e(old('page_type') == 'categories' ? 'selected' : ''); ?>>Categories</option>
                        </select>
                        <?php $__errorArgs = ['page_type'];
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
                    
                    <div class="mb-3">
                        <label class="form-label">Template</label>
                        <select name="template" class="form-select">
                            <option value="default" <?php echo e(old('template') == 'default' ? 'selected' : ''); ?>>Default</option>
                            <option value="full-width" <?php echo e(old('template') == 'full-width' ? 'selected' : ''); ?>>Full Width</option>
                            <option value="sidebar" <?php echo e(old('template') == 'sidebar' ? 'selected' : ''); ?>>With Sidebar</option>
                        </select>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="isActive">
                            <strong>Active</strong>
                            <br><small class="text-muted">Page is visible on website</small>
                        </label>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Menu Order</label>
                        <input type="number" name="menu_order" class="form-control" value="<?php echo e(old('menu_order', 0)); ?>" min="0">
                        <small class="text-muted">Lower numbers appear first</small>
                    </div>
                </div>
            </div>
            
            <!-- Banner Image -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-image"></i> Banner Image</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Upload Banner</label>
                        <input type="file" name="banner_image" class="form-control" accept="image/*" onchange="previewBanner(event)">
                        <small class="text-muted">Recommended: 1920x400px, max 5MB</small>
                    </div>
                    
                    <div id="bannerPreview" style="display: none;" class="mt-3">
                        <img src="" alt="Banner Preview" class="img-fluid rounded">
                    </div>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> Create Page
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
// Show/hide contact fields based on page type
document.getElementById('pageType').addEventListener('change', function() {
    const contactFields = document.getElementById('contactFields');
    contactFields.style.display = this.value === 'contact' ? 'block' : 'none';
});

// Trigger on page load
document.addEventListener('DOMContentLoaded', function() {
    const pageType = document.getElementById('pageType').value;
    if (pageType === 'contact') {
        document.getElementById('contactFields').style.display = 'block';
    }
});

// Preview banner image
function previewBanner(event) {
    const preview = document.getElementById('bannerPreview');
    const img = preview.querySelector('img');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}
</script>

<style>
.card-header h5 {
    color: var(--primary-color);
}

.form-check {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
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
}

textarea {
    font-family: 'Courier New', monospace;
}

#bannerPreview img {
    border: 2px solid #e2e8f0;
}
</style>
<?php $__env->stopSection(); ?>







<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/site-pages/create.blade.php ENDPATH**/ ?>