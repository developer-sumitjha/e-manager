<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="page-title">
        <h1><i class="fas fa-edit"></i> Edit Page: <?php echo e($sitePage->title); ?></h1>
        <p class="text-muted">Update page content and settings</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('admin.site-pages.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Pages
        </a>
    </div>
</div>

<form action="<?php echo e(route('admin.site-pages.update', $sitePage)); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    
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
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('title', $sitePage->title)); ?>" required>
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
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('slug', $sitePage->slug)); ?>">
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
unset($__errorArgs, $__bag); ?>" rows="2"><?php echo e(old('description', $sitePage->description)); ?></textarea>
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
                        <textarea name="content" class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="15"><?php echo e(old('content', $sitePage->content)); ?></textarea>
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
            <div class="card mb-4" id="contactFields" style="display: <?php echo e($sitePage->page_type == 'contact' ? 'block' : 'none'); ?>;">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-envelope"></i> Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="form-control" value="<?php echo e(old('contact_email', $sitePage->contact_email)); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-control" value="<?php echo e(old('contact_phone', $sitePage->contact_phone)); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Contact Address</label>
                        <textarea name="contact_address" class="form-control" rows="3"><?php echo e(old('contact_address', $sitePage->contact_address)); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Google Maps Iframe</label>
                        <textarea name="map_iframe" class="form-control" rows="3"><?php echo e(old('map_iframe', $sitePage->map_iframe)); ?></textarea>
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
                        <input type="text" name="meta_title" class="form-control" value="<?php echo e(old('meta_title', $sitePage->meta_title)); ?>" maxlength="60">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="2" maxlength="160"><?php echo e(old('meta_description', $sitePage->meta_description)); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" class="form-control" value="<?php echo e(old('meta_keywords', $sitePage->meta_keywords)); ?>">
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
                        <textarea name="custom_css" class="form-control" rows="4"><?php echo e(old('custom_css', $sitePage->custom_css)); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Custom JavaScript</label>
                        <textarea name="custom_js" class="form-control" rows="4"><?php echo e(old('custom_js', $sitePage->custom_js)); ?></textarea>
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
                        <select name="page_type" id="pageType" class="form-select" required>
                            <option value="custom" <?php echo e($sitePage->page_type == 'custom' ? 'selected' : ''); ?>>Custom Page</option>
                            <option value="about" <?php echo e($sitePage->page_type == 'about' ? 'selected' : ''); ?>>About Us</option>
                            <option value="contact" <?php echo e($sitePage->page_type == 'contact' ? 'selected' : ''); ?>>Contact Us</option>
                            <option value="products" <?php echo e($sitePage->page_type == 'products' ? 'selected' : ''); ?>>Products</option>
                            <option value="categories" <?php echo e($sitePage->page_type == 'categories' ? 'selected' : ''); ?>>Categories</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Template</label>
                        <select name="template" class="form-select">
                            <option value="default" <?php echo e($sitePage->template == 'default' ? 'selected' : ''); ?>>Default</option>
                            <option value="full-width" <?php echo e($sitePage->template == 'full-width' ? 'selected' : ''); ?>>Full Width</option>
                            <option value="sidebar" <?php echo e($sitePage->template == 'sidebar' ? 'selected' : ''); ?>>With Sidebar</option>
                        </select>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive" <?php echo e($sitePage->is_active ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="isActive">
                            <strong>Active</strong>
                            <br><small class="text-muted">Page is visible on website</small>
                        </label>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input type="checkbox" name="show_in_menu" class="form-check-input" id="showInMenu" <?php echo e($sitePage->show_in_menu ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="showInMenu">
                            <strong>Show in Navigation</strong>
                            <br><small class="text-muted">Display in main menu</small>
                        </label>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Menu Order</label>
                        <input type="number" name="menu_order" class="form-control" value="<?php echo e($sitePage->menu_order); ?>" min="0">
                    </div>
                </div>
            </div>
            
            <!-- Banner Image -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-image"></i> Banner Image</h5>
                </div>
                <div class="card-body">
                    <?php if($sitePage->banner_image): ?>
                    <div class="mb-3">
                        <label class="form-label">Current Banner</label>
                        <img src="<?php echo e($sitePage->banner_image_url); ?>" alt="Current Banner" class="img-fluid rounded">
                    </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label"><?php echo e($sitePage->banner_image ? 'Replace Banner' : 'Upload Banner'); ?></label>
                        <input type="file" name="banner_image" class="form-control" accept="image/*" onchange="previewBanner(event)">
                        <small class="text-muted">Recommended: 1920x400px, max 5MB</small>
                    </div>
                    
                    <div id="bannerPreview" style="display: none;" class="mt-3">
                        <label class="form-label">New Banner Preview</label>
                        <img src="" alt="Banner Preview" class="img-fluid rounded">
                    </div>
                </div>
            </div>
            
            <!-- Submit Buttons -->
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-save"></i> Update Page
                    </button>
                    <a href="<?php echo e(route('admin.site-pages.show', $sitePage)); ?>" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-eye"></i> View Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.getElementById('pageType').addEventListener('change', function() {
    const contactFields = document.getElementById('contactFields');
    contactFields.style.display = this.value === 'contact' ? 'block' : 'none';
});

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
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/site-pages/edit.blade.php ENDPATH**/ ?>