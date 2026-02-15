<div id="ecommerce-tab" class="tab-pane">
    <form id="ecommerceForm">
        <?php echo csrf_field(); ?>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-money-bill-wave"></i>
                Currency Settings
            </h3>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Currency</label>
                        <select class="form-select" name="currency">
                            <option value="NPR" <?php echo e($settings->currency == 'NPR' ? 'selected' : ''); ?>>NPR - Nepali Rupee</option>
                            <option value="USD" <?php echo e($settings->currency == 'USD' ? 'selected' : ''); ?>>USD - US Dollar</option>
                            <option value="EUR" <?php echo e($settings->currency == 'EUR' ? 'selected' : ''); ?>>EUR - Euro</option>
                            <option value="INR" <?php echo e($settings->currency == 'INR' ? 'selected' : ''); ?>>INR - Indian Rupee</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Currency Symbol</label>
                        <input type="text" class="form-control" name="currency_symbol" value="<?php echo e($settings->currency_symbol); ?>">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Symbol Position</label>
                        <select class="form-select" name="currency_position">
                            <option value="before" <?php echo e($settings->currency_position == 'before' ? 'selected' : ''); ?>>Before (Rs.100)</option>
                            <option value="after" <?php echo e($settings->currency_position == 'after' ? 'selected' : ''); ?>>After (100 Rs.)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-shopping-cart"></i>
                Checkout Settings
            </h3>
            
            <div class="form-group">
                <label class="form-label">Enable Guest Checkout</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="enable_guest_checkout" <?php echo e($settings->enable_guest_checkout ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Enable Reviews</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="enable_reviews" <?php echo e($settings->enable_reviews ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Enable Wishlist</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="enable_wishlist" <?php echo e($settings->enable_wishlist ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Minimum Order Amount</label>
                <input type="number" class="form-control" name="min_order_amount" value="<?php echo e($settings->min_order_amount); ?>" step="0.01" placeholder="0.00">
            </div>
        </div>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-truck"></i>
                Shipping Settings
            </h3>
            
            <div class="form-group">
                <label class="form-label">Shipping Cost</label>
                <input type="number" class="form-control" name="shipping_cost" value="<?php echo e($settings->shipping_cost); ?>" step="0.01">
            </div>
            
            <div class="form-group">
                <label class="form-label">Free Shipping Over</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="free_shipping_over" <?php echo e($settings->free_shipping_over ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Free Shipping Amount</label>
                <input type="number" class="form-control" name="free_shipping_amount" value="<?php echo e($settings->free_shipping_amount); ?>" step="0.01" placeholder="1000.00">
            </div>
        </div>
        
        <div class="text-end">
            <button type="button" class="btn-builder btn-builder-primary" onclick="saveEcommerce()">
                <i class="fas fa-save"></i>
                Save E-commerce
            </button>
        </div>
    </form>
</div>

<script>
function saveEcommerce() {
    const form = document.getElementById('ecommerceForm');
    const formData = new FormData(form);
    formData.set('enable_guest_checkout', form.querySelector('input[name="enable_guest_checkout"]').checked ? '1' : '0');
    formData.set('enable_reviews', form.querySelector('input[name="enable_reviews"]').checked ? '1' : '0');
    formData.set('enable_wishlist', form.querySelector('input[name="enable_wishlist"]').checked ? '1' : '0');
    formData.set('free_shipping_over', form.querySelector('input[name="free_shipping_over"]').checked ? '1' : '0');
    
    showSaveIndicator('saving');
    
    fetch('<?php echo e(route('admin.site-builder.update-ecommerce')); ?>', {
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
            showAlert('E-commerce settings saved!');
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






<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/site-builder/tabs/ecommerce.blade.php ENDPATH**/ ?>