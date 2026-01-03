@extends('admin.layouts.app')

@section('title', 'Site Builder')

@section('styles')
<style>
    :root {
        --builder-primary: #667eea;
        --builder-secondary: #764ba2;
        --builder-success: #10b981;
        --builder-danger: #ef4444;
        --builder-warning: #f59e0b;
    }
    
    .site-builder-container {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        min-height: calc(100vh - 100px);
        padding: 2rem 0;
    }
    
    .builder-header {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .builder-header h1 {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--builder-primary), var(--builder-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }
    
    .builder-tabs {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    
    .tabs-nav {
        display: flex;
        flex-wrap: wrap;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        border-bottom: 2px solid rgba(102, 126, 234, 0.2);
        padding: 0;
        margin: 0;
        list-style: none;
    }
    
    .tab-item {
        flex: 1;
        min-width: 120px;
    }
    
    .tab-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem 1.5rem;
        color: #6B7280;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        background: transparent;
    }
    
    .tab-link:hover {
        background: rgba(102, 126, 234, 0.1);
        color: var(--builder-primary);
    }
    
    .tab-link.active {
        background: white;
        color: var(--builder-primary);
        border-bottom-color: var(--builder-primary);
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
        background: rgba(249, 250, 251, 0.5);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-section-title i {
        color: var(--builder-primary);
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 14px;
    }
    
    .form-control, .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--builder-primary);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
        border: 2px solid #e5e7eb;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .color-preview:hover {
        transform: scale(1.1);
        border-color: var(--builder-primary);
    }
    
    .file-upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }
    
    .file-upload-area:hover {
        border-color: var(--builder-primary);
        background: rgba(102, 126, 234, 0.05);
    }
    
    .file-upload-area.drag-over {
        border-color: var(--builder-success);
        background: rgba(16, 185, 129, 0.1);
    }
    
    .file-upload-icon {
        font-size: 48px;
        color: #9ca3af;
        margin-bottom: 1rem;
    }
    
    .image-preview-box {
        margin-top: 1rem;
        border-radius: 12px;
        overflow: hidden;
        max-width: 300px;
        border: 2px solid #e5e7eb;
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
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-builder-primary {
        background: linear-gradient(135deg, var(--builder-primary), var(--builder-secondary));
        color: white;
        box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
    }
    
    .btn-builder-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
    }
    
    .btn-builder-success {
        background: var(--builder-success);
        color: white;
    }
    
    .btn-builder-success:hover {
        background: #059669;
        transform: translateY(-2px);
    }
    
    .btn-builder-outline {
        background: white;
        color: var(--builder-primary);
        border: 2px solid var(--builder-primary);
    }
    
    .btn-builder-outline:hover {
        background: var(--builder-primary);
        color: white;
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
        background-color: #cbd5e1;
        transition: .4s;
        border-radius: 30px;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .toggle-slider {
        background: linear-gradient(135deg, var(--builder-primary), var(--builder-secondary));
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
    }
    
    @keyframes slideInDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 2px solid var(--builder-success);
        color: #065f46;
    }
    
    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        border: 2px solid var(--builder-danger);
        color: #991b1b;
    }
    
    .preview-button {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, var(--builder-success), #059669);
        color: white;
        border-radius: 50px;
        font-weight: 700;
        font-size: 16px;
        box-shadow: 0 8px 16px rgba(16, 185, 129, 0.4);
        cursor: pointer;
        z-index: 1000;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
    }
    
    .preview-button:hover {
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 12px 24px rgba(16, 185, 129, 0.5);
    }
    
    .save-indicator {
        position: fixed;
        top: 2rem;
        right: 2rem;
        padding: 0.75rem 1.5rem;
        background: white;
        border-radius: 50px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
        color: var(--builder-warning);
    }
    
    .save-indicator.saved {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--builder-success);
    }
    
    @media (max-width: 768px) {
        .tabs-nav {
            overflow-x: auto;
        }
        
        .tab-item {
            flex: none;
        }
        
        .preview-button {
            bottom: 1rem;
            right: 1rem;
            padding: 0.75rem 1.5rem;
            font-size: 14px;
        }
    }
</style>
@endsection

@section('content')
<div class="site-builder-container">
    <div class="container-fluid">
        {{-- Header --}}
        <div class="builder-header">
            <div>
                <h1>
                    <i class="fas fa-palette"></i> Site Builder
                </h1>
                <p class="text-muted mb-0">Customize your storefront with ease - no coding required!</p>
            </div>
            <div>
                <a href="{{ route('admin.site-pages.index') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-file-alt"></i> Manage Pages
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
        
        {{-- Save Indicator --}}
        <div class="save-indicator" id="saveIndicator">
            <i class="fas fa-spinner fa-spin"></i>
            <span>Saving...</span>
        </div>
        
        {{-- Alert Area --}}
        <div id="alertArea"></div>
        
        {{-- Templates Gallery --}}
        <div class="builder-header" style="border: 2px dashed rgba(102,126,234,0.2);">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
                <div>
                    <h2 class="m-0" style="font-weight:800; background: linear-gradient(135deg, var(--builder-primary), var(--builder-secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        <i class="fas fa-store"></i> Store Templates
                    </h2>
                    <div class="text-muted">Choose a starting template. You can customize everything later.</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary">New</span>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card h-100 shadow-sm border-0 template-card" data-template="classic">
                        <div class="position-relative">
                            <img src="https://dummyimage.com/600x360/f3f4f6/8b5cf6&text=Classic" class="card-img-top" alt="Classic">
                            <span class="position-absolute top-0 start-0 m-2 badge bg-dark">Glimpse</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title m-0">Classic</h5>
                            <div class="text-muted small">Clean and minimal storefront layout</div>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0">
                            <div class="d-grid gap-2">
                                <button class="btn btn-sm btn-outline-secondary" onclick="viewTemplate('classic')">
                                    <i class="fas fa-eye"></i> View Template
                                </button>
                                <button class="btn btn-sm btn-outline-primary" onclick="applyTemplate('classic')">
                                    <i class="fas fa-check"></i> Use Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card h-100 shadow-sm border-0 template-card" data-template="modern">
                        <div class="position-relative">
                            <img src="https://dummyimage.com/600x360/eef2ff/6366f1&text=Modern" class="card-img-top" alt="Modern">
                            <span class="position-absolute top-0 start-0 m-2 badge bg-dark">Glimpse</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title m-0">Modern</h5>
                            <div class="text-muted small">Bold hero, cards, and rounded sections</div>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0">
                            <div class="d-grid gap-2">
                                <button class="btn btn-sm btn-outline-secondary" onclick="viewTemplate('modern')">
                                    <i class="fas fa-eye"></i> View Template
                                </button>
                                <button class="btn btn-sm btn-outline-primary" onclick="applyTemplate('modern')">
                                    <i class="fas fa-check"></i> Use Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card h-100 shadow-sm border-0 template-card" data-template="fashion">
                        <div class="position-relative">
                            <img src="https://dummyimage.com/600x360/fff1f2/f43f5e&text=Fashion" class="card-img-top" alt="Fashion">
                            <span class="position-absolute top-0 start-0 m-2 badge bg-dark">Glimpse</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title m-0">Fashion</h5>
                            <div class="text-muted small">Large imagery and editorial typography</div>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0">
                            <div class="d-grid gap-2">
                                <button class="btn btn-sm btn-outline-secondary" onclick="viewTemplate('fashion')">
                                    <i class="fas fa-eye"></i> View Template
                                </button>
                                <button class="btn btn-sm btn-outline-primary" onclick="applyTemplate('fashion')">
                                    <i class="fas fa-check"></i> Use Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card h-100 shadow-sm border-0 template-card" data-template="electronics">
                        <div class="position-relative">
                            <img src="https://dummyimage.com/600x360/eff6ff/0ea5e9&text=Electronics" class="card-img-top" alt="Electronics">
                            <span class="position-absolute top-0 start-0 m-2 badge bg-dark">Glimpse</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title m-0">Electronics</h5>
                            <div class="text-muted small">Feature grid and promo highlight sections</div>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0">
                            <div class="d-grid gap-2">
                                <button class="btn btn-sm btn-outline-secondary" onclick="viewTemplate('electronics')">
                                    <i class="fas fa-eye"></i> View Template
                                </button>
                                <button class="btn btn-sm btn-outline-primary" onclick="applyTemplate('electronics')">
                                    <i class="fas fa-check"></i> Use Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card h-100 shadow-sm border-0 template-card" data-template="grocery">
                        <div class="position-relative">
                            <img src="https://dummyimage.com/600x360/ecfeff/14b8a6&text=Grocery" class="card-img-top" alt="Grocery">
                            <span class="position-absolute top-0 start-0 m-2 badge bg-dark">Glimpse</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title m-0">Grocery</h5>
                            <div class="text-muted small">Category first, quick add-to-cart layout</div>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0">
                            <div class="d-grid gap-2">
                                <button class="btn btn-sm btn-outline-secondary" onclick="viewTemplate('grocery')">
                                    <i class="fas fa-eye"></i> View Template
                                </button>
                                <button class="btn btn-sm btn-outline-primary" onclick="applyTemplate('grocery')">
                                    <i class="fas fa-check"></i> Use Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="builder-tabs">
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
            
            <div class="tab-content">
                {{-- GENERAL TAB --}}
                @include('admin.site-builder.tabs.general')
                
                {{-- THEME TAB --}}
                @include('admin.site-builder.tabs.theme')
                
                {{-- BANNER TAB --}}
                @include('admin.site-builder.tabs.banner')
                
                {{-- NAVIGATION TAB --}}
                @include('admin.site-builder.tabs.navigation')
                
                {{-- HOMEPAGE TAB --}}
                @include('admin.site-builder.tabs.homepage')
                
                {{-- PRODUCTS TAB --}}
                @include('admin.site-builder.tabs.products')
                
                {{-- FOOTER TAB --}}
                @include('admin.site-builder.tabs.footer')
                
                {{-- SEO TAB --}}
                @include('admin.site-builder.tabs.seo')
                
                {{-- ECOMMERCE TAB --}}
                @include('admin.site-builder.tabs.ecommerce')
                
                {{-- ADVANCED TAB --}}
                @include('admin.site-builder.tabs.advanced')
            </div>
        </div>
        
        {{-- Preview Button --}}
        <button class="preview-button" id="previewButton">
            <i class="fas fa-eye"></i>
            <span>Preview Store</span>
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
    fetch('{{ route('admin.site-builder.preview-url') }}')
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
const settings = @json($settings);
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
@endsection

