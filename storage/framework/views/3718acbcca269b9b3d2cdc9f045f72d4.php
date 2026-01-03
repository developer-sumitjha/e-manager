<div id="seo-tab" class="tab-pane">
    <form id="seoForm">
        <?php echo csrf_field(); ?>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-search"></i>
                SEO Settings
            </h3>
            
            <div class="form-group">
                <label class="form-label">Meta Title</label>
                <input type="text" class="form-control" name="meta_title" value="<?php echo e($settings->meta_title); ?>" maxlength="60" placeholder="Your Store Name - Best Products Online">
                <small class="text-muted">Maximum 60 characters</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Meta Description</label>
                <textarea class="form-control" name="meta_description" rows="3" maxlength="160" placeholder="Describe your store in 160 characters..."><?php echo e($settings->meta_description); ?></textarea>
                <small class="text-muted">Maximum 160 characters</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Meta Keywords</label>
                <input type="text" class="form-control" name="meta_keywords" value="<?php echo e($settings->meta_keywords); ?>" placeholder="ecommerce, online shopping, products">
                <small class="text-muted">Comma separated keywords</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Open Graph Image</label>
                <div class="file-upload-area">
                    <input type="file" name="og_image" accept="image/*" style="display: none;">
                    <div class="file-upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <p class="mb-0"><strong>Click to upload</strong> or drag and drop</p>
                    <p class="text-muted small mb-0">Recommended: 1200x630px for social sharing</p>
                </div>
                <?php if($settings->og_image): ?>
                <div class="image-preview-box mt-3">
                    <img src="<?php echo e(Storage::url($settings->og_image)); ?>" alt="OG Image">
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-chart-line"></i>
                Analytics
            </h3>
            
            <div class="form-group">
                <label class="form-label">Google Analytics Code</label>
                <textarea class="form-control" name="google_analytics_code" rows="3" placeholder="<!-- Google Analytics -->"><?php echo e($settings->google_analytics_code); ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Facebook Pixel Code</label>
                <textarea class="form-control" name="facebook_pixel_code" rows="3" placeholder="<!-- Facebook Pixel -->"><?php echo e($settings->facebook_pixel_code); ?></textarea>
            </div>
        </div>
        
        <div class="text-end">
            <button type="button" class="btn-builder btn-builder-primary" onclick="saveSeo()">
                <i class="fas fa-save"></i>
                Save SEO
            </button>
        </div>
    </form>
</div>

<script>
function saveSeo() {
    const form = document.getElementById('seoForm');
    const formData = new FormData(form);
    
    showSaveIndicator('saving');
    
    fetch('<?php echo e(route('admin.site-builder.update-seo')); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Upload OG image if present
            const ogFile = form.querySelector('input[name="og_image"]').files[0];
            if (ogFile) {
                const ogData = new FormData();
                ogData.append('og_image', ogFile);
                
                return fetch('<?php echo e(route('admin.site-builder.upload-og-image')); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json'
                    },
                    body: ogData
                });
            }
            return Promise.resolve({json: () => ({success: true})});
        } else {
            throw new Error(data.message || 'Failed to save');
        }
    })
    .then(response => response.json ? response.json() : response)
    .then(data => {
        showSaveIndicator('saved');
        showAlert('SEO settings saved!');
        if (form.querySelector('input[name="og_image"]').files[0]) {
            setTimeout(() => location.reload(), 1500);
        }
    })
    .catch(error => {
        showSaveIndicator('saved');
        showAlert('Error: ' + error.message, 'danger');
    });
}
</script>






<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/site-builder/tabs/seo.blade.php ENDPATH**/ ?>