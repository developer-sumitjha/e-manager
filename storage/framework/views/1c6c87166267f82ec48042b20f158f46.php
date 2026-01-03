<div id="homepage-tab" class="tab-pane">
    <form id="homepageForm">
        <?php echo csrf_field(); ?>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-home"></i>
                Homepage Sections
            </h3>
            
            <div class="form-group">
                <label class="form-label">Show Featured Products</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_featured_products" <?php echo e($settings->show_featured_products ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Show New Arrivals</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_new_arrivals" <?php echo e($settings->show_new_arrivals ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Show Categories</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_categories" <?php echo e($settings->show_categories ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Show Testimonials</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_testimonials" <?php echo e($settings->show_testimonials ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Show About Section</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_about_section" <?php echo e($settings->show_about_section ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>
        
        <div class="text-end">
            <button type="button" class="btn-builder btn-builder-primary" onclick="saveHomepage()">
                <i class="fas fa-save"></i>
                Save Homepage
            </button>
        </div>
    </form>
</div>

<script>
function saveHomepage() {
    const form = document.getElementById('homepageForm');
    const formData = new FormData();
    formData.append('_token', '<?php echo e(csrf_token()); ?>');
    formData.append('show_featured_products', form.querySelector('input[name="show_featured_products"]').checked ? '1' : '0');
    formData.append('show_new_arrivals', form.querySelector('input[name="show_new_arrivals"]').checked ? '1' : '0');
    formData.append('show_categories', form.querySelector('input[name="show_categories"]').checked ? '1' : '0');
    formData.append('show_testimonials', form.querySelector('input[name="show_testimonials"]').checked ? '1' : '0');
    formData.append('show_about_section', form.querySelector('input[name="show_about_section"]').checked ? '1' : '0');
    
    showSaveIndicator('saving');
    
    fetch('<?php echo e(route('admin.site-builder.update-homepage')); ?>', {
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
            showSaveIndicator('saved');
            showAlert('Homepage settings saved successfully!');
        } else {
            throw new Error(data.message || 'Failed to save');
        }
    })
    .catch(error => {
        showSaveIndicator('saved');
        showAlert('Error: ' + error.message, 'danger');
    });
}
</script>






<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/site-builder/tabs/homepage.blade.php ENDPATH**/ ?>