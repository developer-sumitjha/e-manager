<?php $__env->startSection('title', 'Site Builder'); ?>

<?php $__env->startSection('styles'); ?>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    /* Black and White Theme - Matching Dashboard */
    * {
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background: #ffffff;
        color: #000000;
    }
    
    .site-builder-container {
        background: #ffffff;
        min-height: calc(100vh - 100px);
        padding: 2rem 0;
        width: 100%;
        overflow-x: visible;
    }
    
    .site-builder-container .container-fluid {
        width: 100%;
        max-width: 100%;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        overflow-x: visible;
    }
    
    .builder-header {
        background: #ffffff;
        border: 1px solid #000000;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .builder-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #000000;
        margin-bottom: 0.5rem;
        background: none;
        -webkit-background-clip: unset;
        -webkit-text-fill-color: #000000;
        background-clip: unset;
    }
    
    .builder-tabs {
        background: #ffffff;
        border: 1px solid #000000;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        width: 100%;
        overflow: hidden;
    }
    
    .tabs-nav-wrapper {
        width: 100%;
        overflow-x: scroll !important;
        overflow-y: hidden !important;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #000000 #f5f5f5;
        position: relative;
        display: block;
    }
    
    /* Force scrollbar to always be visible when content overflows */
    .tabs-nav-wrapper:has(.tabs-nav[style*="width"]) {
        overflow-x: scroll !important;
    }
    
    .tabs-nav-wrapper::-webkit-scrollbar {
        height: 8px;
    }
    
    .tabs-nav-wrapper::-webkit-scrollbar-track {
        background: #f5f5f5;
        border-radius: 4px;
    }
    
    .tabs-nav-wrapper::-webkit-scrollbar-thumb {
        background: #000000;
        border-radius: 4px;
    }
    
    .tabs-nav-wrapper::-webkit-scrollbar-thumb:hover {
        background: #333333;
    }
    
    .tabs-nav {
        display: flex !important;
        flex-wrap: nowrap !important;
        background: #ffffff;
        border-bottom: 2px solid #000000;
        padding: 0;
        margin: 0;
        list-style: none;
        width: max-content !important;
        min-width: 100%;
        white-space: nowrap;
        position: relative;
    }
    
    .tab-item {
        flex: 0 0 auto;
        min-width: 120px;
        white-space: nowrap;
        position: relative;
        z-index: 2;
        flex-shrink: 0;
    }
    
    .tab-link {
        white-space: nowrap;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem 1.5rem;
        color: #666666;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        background: transparent;
        box-sizing: border-box;
    }
    
    .tab-link:hover {
        background: #f5f5f5;
        color: #000000;
    }
    
    .tab-link.active {
        background: #ffffff;
        color: #000000;
        border-bottom-color: #000000;
    }
    
    .tab-link i {
        font-size: 18px;
    }
    
    .tab-content {
        padding: 2rem;
    }
    
    .tab-pane {
        display: none;
    }
    
    .tab-pane.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .form-section {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #000000;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-section-title i {
        color: #000000;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #000000;
        margin-bottom: 0.5rem;
        font-size: 14px;
    }
    
    .form-control, .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #000000;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: #ffffff;
        color: #000000;
    }
    
    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #000000;
        box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.1);
    }
    
    .color-picker-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .color-input {
        flex: 1;
    }
    
    .color-preview {
        width: 60px;
        height: 45px;
        border-radius: 8px;
        border: 1px solid #000000;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .color-preview:hover {
        transform: scale(1.1);
        border-color: #333333;
    }
    
    .file-upload-area {
        border: 2px dashed #000000;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #ffffff;
    }
    
    .file-upload-area:hover {
        border-color: #333333;
        background: #f5f5f5;
    }
    
    .file-upload-area.drag-over {
        border-color: #000000;
        background: #f0f0f0;
    }
    
    .file-upload-icon {
        font-size: 48px;
        color: #666666;
        margin-bottom: 1rem;
    }
    
    .image-preview-box {
        margin-top: 1rem;
        border-radius: 12px;
        overflow: hidden;
        max-width: 300px;
        border: 1px solid #000000;
    }
    
    .image-preview-box img {
        width: 100%;
        height: auto;
        display: block;
    }
    
    .btn-builder {
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        border: 1.5px solid;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-builder-primary {
        background: #000000;
        color: #ffffff;
        border-color: #000000;
    }
    
    .btn-builder-primary:hover {
        background: #333333;
        border-color: #333333;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    
    .btn-builder-success {
        background: #000000;
        color: #ffffff;
        border-color: #000000;
    }
    
    .btn-builder-success:hover {
        background: #333333;
        border-color: #333333;
        transform: translateY(-2px);
    }
    
    .btn-builder-outline {
        background: #ffffff;
        color: #000000;
        border: 1.5px solid #000000;
    }
    
    .btn-builder-outline:hover {
        background: #000000;
        color: #ffffff;
    }
    
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 30px;
    }
    
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #e0e0e0;
        transition: .4s;
        border-radius: 30px;
        border: 1px solid #000000;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 4px;
        bottom: 4px;
        background-color: #ffffff;
        transition: .4s;
        border-radius: 50%;
        border: 1px solid #000000;
    }
    
    input:checked + .toggle-slider {
        background: #000000;
    }
    
    input:checked + .toggle-slider:before {
        transform: translateX(30px);
    }
    
    .alert-builder {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: slideInDown 0.3s ease;
        border: 1px solid;
    }
    
    @keyframes slideInDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .alert-success {
        background: #ffffff;
        border-color: #000000;
        color: #000000;
    }
    
    .alert-danger {
        background: #ffffff;
        border-color: #000000;
        color: #000000;
    }
    
    .preview-button {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        padding: 1rem 2rem;
        background: #000000;
        color: #ffffff;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        z-index: 1000;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid #000000;
    }
    
    .preview-button:hover {
        background: #333333;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }
    
    .save-indicator {
        position: fixed;
        top: 2rem;
        right: 2rem;
        padding: 0.75rem 1.5rem;
        background: #ffffff;
        border: 1px solid #000000;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-weight: 600;
        font-size: 14px;
        z-index: 1000;
        display: none;
        animation: fadeIn 0.3s ease;
    }
    
    .save-indicator.saving {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #666666;
    }
    
    .save-indicator.saved {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #000000;
    }
    
    /* Buttons - Matching Dashboard */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        border-width: 1.5px;
    }
    
    .btn-outline-primary {
        color: #000000;
        border-color: #000000;
        background: #ffffff;
    }
    
    .btn-outline-primary:hover,
    .btn-outline-primary.active {
        background: #000000;
        border-color: #000000;
        color: #ffffff;
    }
    
    .btn-outline-secondary {
        color: #666666;
        border-color: #666666;
        background: #ffffff;
    }
    
    .btn-outline-secondary:hover {
        background: #666666;
        border-color: #666666;
        color: #ffffff;
    }
    
    /* Text Colors */
    .text-muted {
        color: #666666 !important;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .tabs-nav {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scroll-snap-type: x mandatory;
        }
        
        .tab-item {
            flex: 0 0 auto;
            scroll-snap-align: start;
        }
        
        .preview-button {
            bottom: 1rem;
            right: 1rem;
            padding: 0.75rem 1.5rem;
            font-size: 14px;
        }
        
        .builder-header {
            padding: 1.5rem;
        }
        
        .tab-content {
            padding: 1.5rem;
        }
    }
    
    /* Ensure tabs are always scrollable */
    .builder-tabs .tabs-nav-wrapper {
        border-radius: 12px 12px 0 0;
        overflow-x: scroll !important;
        overflow-y: hidden !important;
    }
    
    .builder-tabs .tabs-nav {
        width: max-content !important;
        min-width: 100% !important;
        display: flex !important;
    }
    
    .builder-tabs .tabs-nav .tab-item:last-child {
        margin-right: 0;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="site-builder-container">
    <div class="container-fluid">
        
        <div class="builder-header">
            <div>
                <h1>
                    <i class="fas fa-palette"></i> Site Builder
                </h1>
                <p class="text-muted mb-0">Customize your storefront with ease - no coding required!</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('admin.site-pages.index')); ?>" class="btn btn-outline-primary">
                    <i class="fas fa-file-alt"></i> Manage Pages
                </a>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
        
        
        <div class="save-indicator" id="saveIndicator">
            <i class="fas fa-spinner fa-spin"></i>
            <span>Saving...</span>
        </div>
        
        
        <div id="alertArea"></div>
        
        
        <div class="builder-tabs">
            <div class="tabs-nav-wrapper">
                <ul class="tabs-nav" role="tablist">
                <li class="tab-item">
                    <a class="tab-link active" data-tab="general">
                        <i class="fas fa-info-circle"></i>
                        <span>General</span>
                    </a>
                </li>
                <li class="tab-item">
                    <a class="tab-link" data-tab="theme">
                        <i class="fas fa-palette"></i>
                        <span>Theme</span>
                    </a>
                </li>
                <li class="tab-item">
                    <a class="tab-link" data-tab="banner">
                        <i class="fas fa-image"></i>
                        <span>Banner</span>
                    </a>
                </li>
                <li class="tab-item">
                    <a class="tab-link" data-tab="navigation">
                        <i class="fas fa-bars"></i>
                        <span>Navigation</span>
                    </a>
                </li>
                <li class="tab-item">
                    <a class="tab-link" data-tab="homepage">
                        <i class="fas fa-home"></i>
                        <span>Homepage</span>
                    </a>
                </li>
                <li class="tab-item">
                    <a class="tab-link" data-tab="products">
                        <i class="fas fa-box"></i>
                        <span>Products</span>
                    </a>
                </li>
                <li class="tab-item">
                    <a class="tab-link" data-tab="footer">
                        <i class="fas fa-shoe-prints"></i>
                        <span>Footer</span>
                    </a>
                </li>
                <li class="tab-item">
                    <a class="tab-link" data-tab="seo">
                        <i class="fas fa-search"></i>
                        <span>SEO</span>
                    </a>
                </li>
                <li class="tab-item">
                    <a class="tab-link" data-tab="ecommerce">
                        <i class="fas fa-shopping-cart"></i>
                        <span>E-commerce</span>
                    </a>
                </li>
                <li class="tab-item">
                    <a class="tab-link" data-tab="advanced">
                        <i class="fas fa-code"></i>
                        <span>Advanced</span>
                    </a>
                </li>
            </ul>
            </div>
            
            <div class="tab-content">
                
                <?php echo $__env->make('admin.site-builder.tabs.general', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                
                
                <?php echo $__env->make('admin.site-builder.tabs.theme', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                
                
                <?php echo $__env->make('admin.site-builder.tabs.banner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                
                
                <?php echo $__env->make('admin.site-builder.tabs.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                
                
                <?php echo $__env->make('admin.site-builder.tabs.homepage', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                
                
                <?php echo $__env->make('admin.site-builder.tabs.products', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                
                
                <?php echo $__env->make('admin.site-builder.tabs.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                
                
                <?php echo $__env->make('admin.site-builder.tabs.seo', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                
                
                <?php echo $__env->make('admin.site-builder.tabs.ecommerce', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                
                
                <?php echo $__env->make('admin.site-builder.tabs.advanced', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
        
        
        <button class="preview-button" id="previewButton">
            <i class="fas fa-eye"></i>
            <span>Preview Store</span>
        </button>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<!-- Sortable.js for drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
// Ensure tabs navigation is scrollable
function setupTabsScrolling() {
    const tabsNav = document.querySelector('.tabs-nav');
    const tabsNavWrapper = document.querySelector('.tabs-nav-wrapper');
    
    if (tabsNav && tabsNavWrapper) {
        // Reset width to auto first to get accurate measurements
        tabsNav.style.width = 'auto';
        
        // Calculate actual width of all tab items
        let tabsWidth = 0;
        Array.from(tabsNav.children).forEach(item => {
            tabsWidth += item.offsetWidth;
        });
        
        const wrapperWidth = tabsNavWrapper.offsetWidth;
        
        // Set explicit width to ensure scrolling works
        if (tabsWidth > wrapperWidth) {
            tabsNav.style.width = tabsWidth + 'px';
            tabsNav.style.minWidth = wrapperWidth + 'px';
        } else {
            tabsNav.style.width = '100%';
            tabsNav.style.minWidth = '100%';
        }
        
        // Force wrapper to allow scrolling
        tabsNavWrapper.style.overflowX = 'auto';
        tabsNavWrapper.style.overflowY = 'hidden';
        
        // Ensure scrollbar is visible
        if (tabsWidth > wrapperWidth) {
            tabsNavWrapper.style.overflowX = 'scroll';
        }
    }
}

// Run on page load
document.addEventListener('DOMContentLoaded', setupTabsScrolling);

// Run on window resize
window.addEventListener('resize', setupTabsScrolling);

// Run after a short delay to ensure all styles are applied
setTimeout(setupTabsScrolling, 100);

// Tab Switching
document.querySelectorAll('.tab-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all tabs and panes
        document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        
        // Add active class to clicked tab and corresponding pane
        this.classList.add('active');
        const tabId = this.getAttribute('data-tab');
        document.getElementById(tabId + '-tab').classList.add('active');
    });
});

// Alert Helper
function showAlert(message, type = 'success') {
    const alertArea = document.getElementById('alertArea');
    const alertHtml = `
        <div class="alert-builder alert-${type}">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    alertArea.innerHTML = alertHtml;
    setTimeout(() => {
        alertArea.innerHTML = '';
    }, 5000);
}

// Save Indicator
function showSaveIndicator(status = 'saving') {
    const indicator = document.getElementById('saveIndicator');
    indicator.className = 'save-indicator ' + status;
    indicator.querySelector('i').className = status === 'saving' ? 'fas fa-spinner fa-spin' : 'fas fa-check-circle';
    indicator.querySelector('span').textContent = status === 'saving' ? 'Saving...' : 'Saved!';
    
    if (status === 'saved') {
        setTimeout(() => {
            indicator.style.display = 'none';
        }, 2000);
    }
}

// Color Picker Sync
document.querySelectorAll('.color-input').forEach(input => {
    const preview = input.closest('.color-picker-wrapper').querySelector('.color-preview');
    if (preview) {
        preview.style.backgroundColor = input.value;
        input.addEventListener('input', function() {
            preview.style.backgroundColor = this.value;
        });
        preview.addEventListener('click', function() {
            input.click();
        });
    }
});

// File Upload Drag & Drop
document.querySelectorAll('.file-upload-area').forEach(area => {
    const input = area.querySelector('input[type="file"]');
    
    area.addEventListener('click', () => input.click());
    
    area.addEventListener('dragover', (e) => {
        e.preventDefault();
        area.classList.add('drag-over');
    });
    
    area.addEventListener('dragleave', () => {
        area.classList.remove('drag-over');
    });
    
    area.addEventListener('drop', (e) => {
        e.preventDefault();
        area.classList.remove('drag-over');
        if (e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            handleFileUpload(input);
        }
    });
    
    input.addEventListener('change', () => handleFileUpload(input));
});

function handleFileUpload(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = input.closest('.form-group').querySelector('.image-preview-box');
            if (preview) {
                preview.querySelector('img').src = e.target.result;
                preview.style.display = 'block';
            }
        };
        
        reader.readAsDataURL(file);
    }
}

// Preview Button
document.getElementById('previewButton').addEventListener('click', function() {
    fetch('<?php echo e(route('admin.site-builder.preview-url')); ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.open(data.preview_url, '_blank');
            }
        })
        .catch(error => {
            showAlert('Error loading preview', 'danger');
        });
});

// Settings object
const settings = <?php echo json_encode($settings, 15, 512) ?>;
</script>
<!-- Template Preview Modal -->
<div class="modal fade" id="templatePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="templatePreviewTitle"><i class="fas fa-eye"></i> Template Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="templatePreviewCanvas" style="width:100%; height:auto; display:block;"></div>
                <div class="p-3 text-muted" id="templatePreviewDesc"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="templateUseBtn"><i class="fas fa-check"></i> Use This Template</button>
            </div>
        </div>
    </div>
</div>

<script>
const TEMPLATE_META = {
    classic: { title: 'Classic', bg: '#f3f4f6', fg: '#111827', desc: 'A timeless layout with a clean header, simple banner, and grid-based product sections.' },
    modern: { title: 'Modern', bg: '#eef2ff', fg: '#111827', desc: 'Bold hero area, rounded sections, and modern cards for featured categories and products.' },
    fashion: { title: 'Fashion', bg: '#fff1f2', fg: '#111827', desc: 'Large editorial images, typography emphasis, and lookbook-style highlights.' },
    electronics: { title: 'Electronics', bg: '#eff6ff', fg: '#111827', desc: 'Feature-rich sections, promo banners, and spec-focused product cards.' },
    grocery: { title: 'Grocery', bg: '#ecfeff', fg: '#111827', desc: 'Category-first layout with quick-add controls optimized for frequent purchases.' }
};

function svgDataUrl(label, w = 1400, h = 800, bg = '#f3f4f6', fg = '#111827', sublabel = 'Template Preview') {
    const svg = `<svg xmlns='http://www.w3.org/2000/svg' width='${w}' height='${h}'>
        <defs>
            <linearGradient id='g' x1='0' y1='0' x2='1' y2='1'>
                <stop offset='0%' stop-color='${bg}'/>
                <stop offset='100%' stop-color='#e5e7eb'/>
            </linearGradient>
        </defs>
        <rect width='100%' height='100%' fill='url(#g)'/>
        <rect x='40' y='40' width='${w-80}' height='140' rx='16' fill='white' opacity='0.6'/>
        <rect x='40' y='220' width='${w-80}' height='${h-300}' rx='16' fill='white' opacity='0.5'/>
        <text x='50%' y='120' text-anchor='middle' font-family='Inter, Arial' font-size='56' fill='${fg}' font-weight='700'>${label}</text>
        <text x='50%' y='190' text-anchor='middle' font-family='Inter, Arial' font-size='22' fill='${fg}' opacity='0.8'>${sublabel}</text>
    </svg>`;
    return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg);
}

let selectedTemplateKey = null;
function viewTemplate(key) {
    selectedTemplateKey = key;
    const meta = TEMPLATE_META[key];
    if (!meta) return;
    document.getElementById('templatePreviewTitle').innerText = meta.title + ' Template';
    const canvas = document.getElementById('templatePreviewCanvas');
    canvas.innerHTML = buildTemplateHtml(key);
    document.getElementById('templatePreviewDesc').innerText = meta.desc;
    const modalEl = document.getElementById('templatePreviewModal');
    const modal = (window.bootstrap && window.bootstrap.Modal) ? new bootstrap.Modal(modalEl) : null;
    if (!modal) { modalEl.style.display = 'block'; modalEl.classList.add('show'); }
    modal.show();
    document.getElementById('templateUseBtn').onclick = () => {
        applyTemplate(key);
        if (modal) modal.hide(); else { modalEl.classList.remove('show'); modalEl.style.display = 'none'; }
    };
}

function applyTemplate(key) {
    // TODO: Wire this to your existing save endpoints to set theme/colors/sections per template
    showAlert('Template "' + (TEMPLATE_META[key]?.title || key) + '" selected. Apply customization to save.', 'success');
}

// Initialize card thumbnails with inline previews (no external requests)
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.template-card').forEach(card => {
        const key = card.getAttribute('data-template');
        const imgEl = card.querySelector('img');
        const meta = TEMPLATE_META[key];
        if (imgEl && meta) {
            imgEl.src = svgDataUrl(meta.title, 600, 360, meta.bg, meta.fg, 'Glimpse');
        }
    });
});

function buildTemplateHtml(key) {
    const baseStyle = `
        font-family: Inter, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
    `;
    const mockProducts = [
        { name: 'Wireless Headphones', price: 'Rs. 3,999', img: 'https://dummyimage.com/400x300/ede9fe/4c1d95&text=Product+1' },
        { name: 'Smartwatch Series 5', price: 'Rs. 7,499', img: 'https://dummyimage.com/400x300/e0e7ff/3730a3&text=Product+2' },
        { name: 'Compact Camera', price: 'Rs. 12,999', img: 'https://dummyimage.com/400x300/dbeafe/1e3a8a&text=Product+3' },
        { name: 'Gaming Mouse', price: 'Rs. 1,799', img: 'https://dummyimage.com/400x300/fee2e2/991b1b&text=Product+4' }
    ];

    const hero = (title, subtitle, gradient) => `
        <section style="${baseStyle}; background:${gradient}; color:#fff; padding:48px 32px;">
            <div style="max-width:1100px;margin:0 auto;display:flex;align-items:center;gap:24px;flex-wrap:wrap;">
                <div style="flex:1 1 480px;">
                    <h2 style="margin:0 0 8px 0;font-weight:800;font-size:40px;line-height:1.1;">${title}</h2>
                    <p style="margin:0;color:rgba(255,255,255,0.9);font-size:18px;">${subtitle}</p>
                    <div style="margin-top:16px;display:flex;gap:12px;flex-wrap:wrap;">
                        <a style="background:#10b981;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:700;">Shop Now</a>
                        <a style="background:#fff;color:#111827;padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:700;">View Deals</a>
                    </div>
                </div>
                <div style="flex:1 1 420px;background:rgba(255,255,255,0.15);border-radius:16px;min-height:220px;"></div>
            </div>
        </section>`;

    const grid = (title) => `
        <section style="${baseStyle}; background:#f8fafc; padding:32px;">
            <div style="max-width:1100px;margin:0 auto;">
                <h3 style="margin:0 0 16px 0;font-weight:800;color:#111827;">${title}</h3>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;">
                    ${mockProducts.map(p => `
                        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;box-shadow:0 1px 2px rgba(0,0,0,0.04);">
                            <div style="background:#eef2ff;height:160px;background-image:url('${p.img}');background-size:cover;background-position:center;"></div>
                            <div style="padding:12px 14px;">
                                <div style="font-weight:700;color:#111827;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${p.name}</div>
                                <div style="color:#10b981;font-weight:700;">${p.price}</div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        </section>`;

    const footer = `
        <footer style="${baseStyle}; background:#111827;color:#9ca3af;padding:24px 32px;">
            <div style="max-width:1100px;margin:0 auto;display:flex;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                <div>© Demo Storefront</div>
                <div style="display:flex;gap:16px;">
                    <span>Privacy</span>
                    <span>Terms</span>
                    <span>Support</span>
                </div>
            </div>
        </footer>`;

    let gradient = 'linear-gradient(135deg,#667eea,#764ba2)';
    let title = 'Welcome to Your Store';
    let subtitle = 'A modern template to kickstart your storefront';
    if (key === 'fashion') {
        gradient = 'linear-gradient(135deg,#fb7185,#f43f5e)';
        title = 'New Season, New Style';
        subtitle = 'Discover curated looks and essentials';
    } else if (key === 'electronics') {
        gradient = 'linear-gradient(135deg,#38bdf8,#0ea5e9)';
        title = 'Tech that Empowers';
        subtitle = 'Latest gadgets and accessories';
    } else if (key === 'grocery') {
        gradient = 'linear-gradient(135deg,#34d399,#10b981)';
        title = 'Fresh Groceries Delivered';
        subtitle = 'Everything you need, right to your door';
    } else if (key === 'modern') {
        gradient = 'linear-gradient(135deg,#818cf8,#6366f1)';
        title = 'Elevate Your Brand';
        subtitle = 'Beautiful components and polished UI';
    }

    return hero(title, subtitle, gradient) + grid('Featured Products') + footer;
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/site-builder/index.blade.php ENDPATH**/ ?>