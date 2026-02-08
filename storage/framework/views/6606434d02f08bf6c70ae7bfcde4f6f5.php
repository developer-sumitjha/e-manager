<div id="banner-tab" class="tab-pane">
    <form id="bannerForm">
        <?php echo csrf_field(); ?>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-image"></i>
                Banner Settings
            </h3>
            
            <div class="form-group">
                <label class="form-label">Show Banner</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_banner" <?php echo e($settings->show_banner ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
                <small class="text-muted d-block mt-2">Display hero banner on homepage</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Banner Image</label>
                <div class="file-upload-area" style="cursor: pointer;">
                    <input type="file" name="banner_image" id="bannerImageInput" accept="image/*" style="display: none;">
                    <div class="file-upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <p class="mb-0"><strong>Click to upload</strong> or drag and drop</p>
                    <p class="text-muted small mb-0">Recommended: 1920x600px, JPG or PNG up to 5MB</p>
                </div>
                <div id="bannerImagePreview" class="image-preview-box mt-3" style="max-width: 100%; <?php echo e($settings->banner_image ? 'display: block;' : 'display: none;'); ?>">
                    <?php if($settings->banner_image): ?>
                    <img src="<?php echo e(Storage::url($settings->banner_image)); ?>" alt="Current Banner">
                    <?php else: ?>
                    <img src="" alt="Banner Preview">
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Banner Title</label>
                        <input type="text" class="form-control" name="banner_title" value="<?php echo e($settings->banner_title); ?>" placeholder="Welcome to Our Store">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Banner Subtitle</label>
                        <input type="text" class="form-control" name="banner_subtitle" value="<?php echo e($settings->banner_subtitle); ?>" placeholder="Find amazing products at great prices">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Button Text</label>
                        <input type="text" class="form-control" name="banner_button_text" value="<?php echo e($settings->banner_button_text); ?>" placeholder="Shop Now">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Button Link</label>
                        <input type="text" class="form-control" name="banner_button_link" value="<?php echo e($settings->banner_button_link); ?>" placeholder="/products">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-end">
            <button type="button" class="btn-builder btn-builder-primary" onclick="saveBanner()">
                <i class="fas fa-save"></i>
                Save Banner
            </button>
        </div>
    </form>
</div>

<script>
// Banner image preview
document.getElementById('bannerImageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('bannerImagePreview');
            const img = preview.querySelector('img');
            img.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

function saveBanner() {
    const form = document.getElementById('bannerForm');
    const formData = new FormData(form);
    
    // Convert checkbox to 1/0
    formData.set('show_banner', form.querySelector('input[name="show_banner"]').checked ? '1' : '0');
    
    showSaveIndicator('saving');
    
    // Check if banner image is selected
    const bannerFile = form.querySelector('input[name="banner_image"]').files[0];
    
    // If image is selected, upload it first
    if (bannerFile) {
        const bannerData = new FormData();
        bannerData.append('banner_image', bannerFile);
        
        fetch('<?php echo e(route('admin.site-builder.upload-banner-image')); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            },
            body: bannerData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Then save banner settings
                return saveBannerSettings(formData);
            } else {
                throw new Error(data.message || 'Failed to upload banner image');
            }
        })
        .then(() => {
            showSaveIndicator('saved');
            showAlert('Banner image and settings saved successfully!');
            setTimeout(() => location.reload(), 1500);
        })
        .catch(error => {
            showSaveIndicator('saved');
            showAlert('Error: ' + error.message, 'danger');
        });
    } else {
        // No image, just save settings
        saveBannerSettings(formData)
            .then(() => {
                showSaveIndicator('saved');
                showAlert('Banner settings saved successfully!');
            })
            .catch(error => {
                showSaveIndicator('saved');
                showAlert('Error saving banner: ' + error.message, 'danger');
            });
    }
}

function saveBannerSettings(formData) {
    return fetch('<?php echo e(route('admin.site-builder.update-banner')); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            throw new Error(data.message || 'Failed to save banner settings');
        }
        return data;
    });
}
</script>

<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/site-builder/tabs/banner.blade.php ENDPATH**/ ?>