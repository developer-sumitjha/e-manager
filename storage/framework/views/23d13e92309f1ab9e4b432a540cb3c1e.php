<div id="footer-tab" class="tab-pane">
    <form id="footerForm">
        <?php echo csrf_field(); ?>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-shoe-prints"></i>
                Footer Content
            </h3>
            
            <div class="form-group">
                <label class="form-label">About Text</label>
                <textarea class="form-control" name="footer_about" rows="4" placeholder="Tell customers about your store..."><?php echo e($settings->footer_about); ?></textarea>
            </div>
        </div>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-share-alt"></i>
                Social Media
            </h3>
            
            <div class="form-group">
                <label class="form-label">Show Social Links</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_social_links" <?php echo e($settings->show_social_links ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-facebook"></i> Facebook URL</label>
                        <input type="url" class="form-control" name="facebook_url" value="<?php echo e($settings->facebook_url); ?>" placeholder="https://facebook.com/yourpage">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-instagram"></i> Instagram URL</label>
                        <input type="url" class="form-control" name="instagram_url" value="<?php echo e($settings->instagram_url); ?>" placeholder="https://instagram.com/yourpage">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-twitter"></i> Twitter URL</label>
                        <input type="url" class="form-control" name="twitter_url" value="<?php echo e($settings->twitter_url); ?>" placeholder="https://twitter.com/yourpage">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-youtube"></i> YouTube URL</label>
                        <input type="url" class="form-control" name="youtube_url" value="<?php echo e($settings->youtube_url); ?>" placeholder="https://youtube.com/yourchannel">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-linkedin"></i> LinkedIn URL</label>
                        <input type="url" class="form-control" name="linkedin_url" value="<?php echo e($settings->linkedin_url); ?>" placeholder="https://linkedin.com/company/yourpage">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-end">
            <button type="button" class="btn-builder btn-builder-primary" onclick="saveFooter()">
                <i class="fas fa-save"></i>
                Save Footer
            </button>
        </div>
    </form>
</div>

<script>
function saveFooter() {
    const form = document.getElementById('footerForm');
    const formData = new FormData(form);
    formData.set('show_social_links', form.querySelector('input[name="show_social_links"]').checked ? '1' : '0');
    
    showSaveIndicator('saving');
    
    fetch('<?php echo e(route('admin.site-builder.update-footer')); ?>', {
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
            showAlert('Footer settings saved!');
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






<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/site-builder/tabs/footer.blade.php ENDPATH**/ ?>