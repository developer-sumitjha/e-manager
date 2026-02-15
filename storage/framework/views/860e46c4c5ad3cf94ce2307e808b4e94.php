<div id="theme-tab" class="tab-pane">
    <form id="themeForm">
        <?php echo csrf_field(); ?>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-paint-brush"></i>
                Color Scheme
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Primary Color</label>
                        <div class="color-picker-wrapper">
                            <input type="color" class="color-input" name="primary_color" value="<?php echo e($settings->primary_color); ?>">
                            <div class="color-preview" style="background-color: <?php echo e($settings->primary_color); ?>"></div>
                        </div>
                        <small class="text-muted">Main brand color for buttons and links</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Secondary Color</label>
                        <div class="color-picker-wrapper">
                            <input type="color" class="color-input" name="secondary_color" value="<?php echo e($settings->secondary_color); ?>">
                            <div class="color-preview" style="background-color: <?php echo e($settings->secondary_color); ?>"></div>
                        </div>
                        <small class="text-muted">Complementary color for gradients</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Accent Color</label>
                        <div class="color-picker-wrapper">
                            <input type="color" class="color-input" name="accent_color" value="<?php echo e($settings->accent_color); ?>">
                            <div class="color-preview" style="background-color: <?php echo e($settings->accent_color); ?>"></div>
                        </div>
                        <small class="text-muted">For highlights and success states</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Text Color</label>
                        <div class="color-picker-wrapper">
                            <input type="color" class="color-input" name="text_color" value="<?php echo e($settings->text_color); ?>">
                            <div class="color-preview" style="background-color: <?php echo e($settings->text_color); ?>"></div>
                        </div>
                        <small class="text-muted">Main text color</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Background Color</label>
                        <div class="color-picker-wrapper">
                            <input type="color" class="color-input" name="background_color" value="<?php echo e($settings->background_color); ?>">
                            <div class="color-preview" style="background-color: <?php echo e($settings->background_color); ?>"></div>
                        </div>
                        <small class="text-muted">Page background</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Header Background</label>
                        <div class="color-picker-wrapper">
                            <input type="color" class="color-input" name="header_bg_color" value="<?php echo e($settings->header_bg_color); ?>">
                            <div class="color-preview" style="background-color: <?php echo e($settings->header_bg_color); ?>"></div>
                        </div>
                        <small class="text-muted">Header/navigation background</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Footer Background</label>
                        <div class="color-picker-wrapper">
                            <input type="color" class="color-input" name="footer_bg_color" value="<?php echo e($settings->footer_bg_color); ?>">
                            <div class="color-preview" style="background-color: <?php echo e($settings->footer_bg_color); ?>"></div>
                        </div>
                        <small class="text-muted">Footer background</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-font"></i>
                Typography
            </h3>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Font Family</label>
                        <select class="form-select" name="font_family">
                            <option value="Inter" <?php echo e($settings->font_family == 'Inter' ? 'selected' : ''); ?>>Inter</option>
                            <option value="Roboto" <?php echo e($settings->font_family == 'Roboto' ? 'selected' : ''); ?>>Roboto</option>
                            <option value="Open Sans" <?php echo e($settings->font_family == 'Open Sans' ? 'selected' : ''); ?>>Open Sans</option>
                            <option value="Lato" <?php echo e($settings->font_family == 'Lato' ? 'selected' : ''); ?>>Lato</option>
                            <option value="Poppins" <?php echo e($settings->font_family == 'Poppins' ? 'selected' : ''); ?>>Poppins</option>
                            <option value="Montserrat" <?php echo e($settings->font_family == 'Montserrat' ? 'selected' : ''); ?>>Montserrat</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Font Size</label>
                        <input type="number" class="form-control" name="font_size" value="<?php echo e($settings->font_size); ?>" min="12" max="24">
                        <small class="text-muted">Base font size (12-24px)</small>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Heading Font</label>
                        <select class="form-select" name="heading_font">
                            <option value="Inter" <?php echo e($settings->heading_font == 'Inter' ? 'selected' : ''); ?>>Inter</option>
                            <option value="Playfair Display" <?php echo e($settings->heading_font == 'Playfair Display' ? 'selected' : ''); ?>>Playfair Display</option>
                            <option value="Merriweather" <?php echo e($settings->heading_font == 'Merriweather' ? 'selected' : ''); ?>>Merriweather</option>
                            <option value="Oswald" <?php echo e($settings->heading_font == 'Oswald' ? 'selected' : ''); ?>>Oswald</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-th-large"></i>
                Layout
            </h3>
            
            <div class="row">
                <div class="col-md-4">
                    <label class="layout-option">
                        <input type="radio" name="layout" value="default" <?php echo e($settings->layout == 'default' ? 'checked' : ''); ?>>
                        <div class="layout-card">
                            <div class="layout-preview default-layout"></div>
                            <h4>Default</h4>
                            <p>Standard layout</p>
                        </div>
                    </label>
                </div>
                <div class="col-md-4">
                    <label class="layout-option">
                        <input type="radio" name="layout" value="sidebar" <?php echo e($settings->layout == 'sidebar' ? 'checked' : ''); ?>>
                        <div class="layout-card">
                            <div class="layout-preview sidebar-layout"></div>
                            <h4>Sidebar</h4>
                            <p>With left sidebar</p>
                        </div>
                    </label>
                </div>
                <div class="col-md-4">
                    <label class="layout-option">
                        <input type="radio" name="layout" value="fullwidth" <?php echo e($settings->layout == 'fullwidth' ? 'checked' : ''); ?>>
                        <div class="layout-card">
                            <div class="layout-preview fullwidth-layout"></div>
                            <h4>Full Width</h4>
                            <p>Maximum width</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>
        
        <div class="text-end">
            <button type="button" class="btn-builder btn-builder-primary" onclick="saveTheme()">
                <i class="fas fa-save"></i>
                Save Theme
            </button>
        </div>
    </form>
</div>

<style>
.layout-option {
    cursor: pointer;
    display: block;
    margin-bottom: 1rem;
}

.layout-option input[type="radio"] {
    display: none;
}

.layout-card {
    border: 3px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
}

.layout-option input[type="radio"]:checked + .layout-card {
    border-color: var(--builder-primary);
    background: rgba(102, 126, 234, 0.05);
}

.layout-card:hover {
    border-color: var(--builder-primary);
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.layout-preview {
    height: 100px;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.default-layout {
    background: repeating-linear-gradient(90deg, #e5e7eb, #e5e7eb 20px, #fff 20px, #fff 40px);
}

.sidebar-layout {
    background: linear-gradient(90deg, #e5e7eb 30%, #fff 30%);
}

.fullwidth-layout {
    background: #e5e7eb;
}

.layout-card h4 {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.layout-card p {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 0;
}
</style>

<script>
function saveTheme() {
    const form = document.getElementById('themeForm');
    const formData = new FormData(form);
    
    showSaveIndicator('saving');
    
    fetch('<?php echo e(route('admin.site-builder.update-theme')); ?>', {
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
            showAlert('Theme settings saved successfully!');
        } else {
            throw new Error(data.message || 'Failed to save theme');
        }
    })
    .catch(error => {
        showSaveIndicator('saved');
        showAlert('Error saving theme: ' + error.message, 'danger');
    });
}
</script>






<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/site-builder/tabs/theme.blade.php ENDPATH**/ ?>