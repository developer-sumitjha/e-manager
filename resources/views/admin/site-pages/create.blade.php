@extends('admin.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1><i class="fas fa-file-plus"></i> Create New Page</h1>
        <p class="text-muted">Add a new page to your website</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.site-pages.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Pages
        </a>
    </div>
</div>

<form action="{{ route('admin.site-pages.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
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
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Slug (URL)</label>
                        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="Leave blank to auto-generate">
                        <small class="text-muted">Will be auto-generated from title if left blank</small>
                        @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Short Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="2" placeholder="Brief description of the page">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Page Content</label>
                        <textarea name="content" id="pageContent" class="form-control @error('content') is-invalid @enderror" rows="15" placeholder="Write your page content here...">{{ old('content') }}</textarea>
                        <small class="text-muted">You can use HTML for formatting</small>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                        <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Contact Address</label>
                        <textarea name="contact_address" class="form-control" rows="3">{{ old('contact_address') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Google Maps Iframe</label>
                        <textarea name="map_iframe" class="form-control" rows="3" placeholder='<iframe src="..." ...></iframe>'>{{ old('map_iframe') }}</textarea>
                        <small class="text-muted">Paste your Google Maps embed code here</small>
                    </div>
                </div>
            </div>
            
            <!-- About Page Fields - Team Members -->
            <div class="card mb-4" id="aboutFields" style="display: none;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Our Team</h5>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addTeamMember()">
                        <i class="fas fa-plus"></i> Add Team Member
                    </button>
                </div>
                <div class="card-body">
                    <div id="teamMembersContainer">
                        <!-- Team members will be added here dynamically -->
                    </div>
                    <p class="text-muted small mb-0">Add team members to display in the "Our Team" section on the about page.</p>
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
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}" maxlength="60">
                        <small class="text-muted">Recommended: 50-60 characters</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="2" maxlength="160">{{ old('meta_description') }}</textarea>
                        <small class="text-muted">Recommended: 150-160 characters</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords') }}" placeholder="keyword1, keyword2, keyword3">
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
                        <textarea name="custom_css" class="form-control" rows="4" placeholder="/* Add your custom CSS here */">{{ old('custom_css') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Custom JavaScript</label>
                        <textarea name="custom_js" class="form-control" rows="4" placeholder="// Add your custom JavaScript here">{{ old('custom_js') }}</textarea>
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
                        <select name="page_type" id="pageType" class="form-select @error('page_type') is-invalid @enderror" required>
                            <option value="custom" {{ old('page_type') == 'custom' ? 'selected' : '' }}>Custom Page</option>
                            <option value="about" {{ old('page_type') == 'about' ? 'selected' : '' }}>About Us</option>
                            <option value="contact" {{ old('page_type') == 'contact' ? 'selected' : '' }}>Contact Us</option>
                            <option value="products" {{ old('page_type') == 'products' ? 'selected' : '' }}>Products</option>
                            <option value="categories" {{ old('page_type') == 'categories' ? 'selected' : '' }}>Categories</option>
                        </select>
                        @error('page_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Template</label>
                        <select name="template" class="form-select">
                            <option value="default" {{ old('template') == 'default' ? 'selected' : '' }}>Default</option>
                            <option value="full-width" {{ old('template') == 'full-width' ? 'selected' : '' }}>Full Width</option>
                            <option value="sidebar" {{ old('template') == 'sidebar' ? 'selected' : '' }}>With Sidebar</option>
                        </select>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">
                            <strong>Active</strong>
                            <br><small class="text-muted">Page is visible on website</small>
                        </label>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Menu Order</label>
                        <input type="number" name="menu_order" class="form-control" value="{{ old('menu_order', 0) }}" min="0">
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
let teamMemberIndex = 0;

// Show/hide fields based on page type
document.getElementById('pageType').addEventListener('change', function() {
    const contactFields = document.getElementById('contactFields');
    const aboutFields = document.getElementById('aboutFields');
    const pageType = this.value;
    
    contactFields.style.display = pageType === 'contact' ? 'block' : 'none';
    aboutFields.style.display = pageType === 'about' ? 'block' : 'none';
});

// Trigger on page load
document.addEventListener('DOMContentLoaded', function() {
    const pageType = document.getElementById('pageType').value;
    if (pageType === 'contact') {
        document.getElementById('contactFields').style.display = 'block';
    }
    if (pageType === 'about') {
        document.getElementById('aboutFields').style.display = 'block';
    }
});

// Add team member
function addTeamMember() {
    const container = document.getElementById('teamMembersContainer');
    const index = teamMemberIndex++;
    
    const teamMemberHtml = `
        <div class="team-member-item card mb-3" data-index="${index}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Team Member #${index + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeTeamMember(${index})">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="team_members[${index}][name]" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Position/Title</label>
                        <input type="text" name="team_members[${index}][position]" class="form-control" placeholder="e.g., CEO, Manager">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Photo</label>
                        <input type="file" name="team_members[${index}][photo]" class="form-control" accept="image/*" onchange="previewTeamPhoto(event, ${index})">
                        <small class="text-muted">Recommended: 400x400px, square image</small>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="team-photo-preview" id="teamPhotoPreview${index}" style="display: none;">
                            <img src="" alt="Preview" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="team_members[${index}][email]" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="team_members[${index}][phone]" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Bio/Description</label>
                        <textarea name="team_members[${index}][bio]" class="form-control" rows="3" placeholder="Brief description about the team member"></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Facebook URL</label>
                        <input type="url" name="team_members[${index}][facebook]" class="form-control" placeholder="https://facebook.com/...">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Twitter URL</label>
                        <input type="url" name="team_members[${index}][twitter]" class="form-control" placeholder="https://twitter.com/...">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">LinkedIn URL</label>
                        <input type="url" name="team_members[${index}][linkedin]" class="form-control" placeholder="https://linkedin.com/in/...">
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', teamMemberHtml);
}

// Remove team member
function removeTeamMember(index) {
    const item = document.querySelector(`.team-member-item[data-index="${index}"]`);
    if (item) {
        item.remove();
    }
}

// Preview team member photo
function previewTeamPhoto(event, index) {
    const preview = document.getElementById(`teamPhotoPreview${index}`);
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
@endsection






