@extends('admin.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1><i class="fas fa-file-alt"></i> {{ $sitePage->title }}</h1>
        <p class="text-muted">
            <span class="badge bg-{{ $sitePage->is_active ? 'success' : 'danger' }}">
                {{ $sitePage->is_active ? 'Active' : 'Inactive' }}
            </span>
            <span class="ms-2">Created: {{ $sitePage->created_at->format('M d, Y') }}</span>
        </p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.site-pages.edit', $sitePage) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Page
        </a>
        <a href="{{ route('admin.site-pages.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Pages
        </a>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Banner Image -->
        @if($sitePage->banner_image)
        <div class="card mb-4">
            <img src="{{ $sitePage->banner_image_url }}" alt="{{ $sitePage->title }}" class="card-img-top">
        </div>
        @endif
        
        <!-- Page Content -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-text"></i> Page Content</h5>
            </div>
            <div class="card-body">
                @if($sitePage->description)
                <div class="alert alert-info">
                    <strong>Description:</strong> {{ $sitePage->description }}
                </div>
                @endif
                
                @if($sitePage->content)
                <div class="content-preview">
                    {!! nl2br(e($sitePage->content)) !!}
                </div>
                @else
                <p class="text-muted">No content added yet.</p>
                @endif
            </div>
        </div>
        
        <!-- Contact Information (if contact page) -->
        @if($sitePage->page_type == 'contact')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-envelope"></i> Contact Information</h5>
            </div>
            <div class="card-body">
                @if($sitePage->contact_email)
                <p><strong>Email:</strong> <a href="mailto:{{ $sitePage->contact_email }}">{{ $sitePage->contact_email }}</a></p>
                @endif
                
                @if($sitePage->contact_phone)
                <p><strong>Phone:</strong> <a href="tel:{{ $sitePage->contact_phone }}">{{ $sitePage->contact_phone }}</a></p>
                @endif
                
                @if($sitePage->contact_address)
                <p><strong>Address:</strong><br>{{ $sitePage->contact_address }}</p>
                @endif
                
                @if($sitePage->map_iframe)
                <div class="mt-3">
                    <strong>Map Location:</strong>
                    <div class="mt-2">
                        {!! $sitePage->map_iframe !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- SEO Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-search"></i> SEO Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Meta Title:</strong>
                    <p>{{ $sitePage->meta_title ?: 'Not set' }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Meta Description:</strong>
                    <p>{{ $sitePage->meta_description ?: 'Not set' }}</p>
                </div>
                
                <div class="mb-0">
                    <strong>Meta Keywords:</strong>
                    <p>{{ $sitePage->meta_keywords ?: 'Not set' }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Info -->
    <div class="col-lg-4">
        <!-- Page Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Page Details</h5>
            </div>
            <div class="card-body">
                <div class="detail-item">
                    <strong>Page Type:</strong>
                    <span class="badge bg-primary">{{ $sitePage->page_type_label }}</span>
                </div>
                
                <div class="detail-item">
                    <strong>Slug:</strong>
                    <code>/{{ $sitePage->slug }}</code>
                </div>
                
                <div class="detail-item">
                    <strong>Template:</strong>
                    <span>{{ ucfirst(str_replace('-', ' ', $sitePage->template)) }}</span>
                </div>
                
                <div class="detail-item">
                    <strong>Menu Order:</strong>
                    <span class="badge bg-secondary">{{ $sitePage->menu_order }}</span>
                </div>
                
                <div class="detail-item">
                    <strong>Show in Menu:</strong>
                    <span class="badge bg-{{ $sitePage->show_in_menu ? 'success' : 'secondary' }}">
                        {{ $sitePage->show_in_menu ? 'Yes' : 'No' }}
                    </span>
                </div>
                
                <div class="detail-item">
                    <strong>Created:</strong>
                    <span>{{ $sitePage->created_at->format('M d, Y H:i') }}</span>
                </div>
                
                <div class="detail-item">
                    <strong>Last Updated:</strong>
                    <span>{{ $sitePage->updated_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
        </div>
        
        <!-- Custom Code -->
        @if($sitePage->custom_css || $sitePage->custom_js)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-code"></i> Custom Code</h5>
            </div>
            <div class="card-body">
                @if($sitePage->custom_css)
                <div class="mb-3">
                    <strong>Has Custom CSS</strong>
                    <p class="text-muted mb-0"><i class="fas fa-check text-success"></i> Yes</p>
                </div>
                @endif
                
                @if($sitePage->custom_js)
                <div class="mb-0">
                    <strong>Has Custom JavaScript</strong>
                    <p class="text-muted mb-0"><i class="fas fa-check text-success"></i> Yes</p>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-ellipsis-h"></i> Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.site-pages.edit', $sitePage) }}" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-edit"></i> Edit Page
                </a>
                
                <button type="button" class="btn btn-danger w-100" onclick="deletePage()">
                    <i class="fas fa-trash"></i> Delete Page
                </button>
                
                <form id="delete-form" action="{{ route('admin.site-pages.destroy', $sitePage) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deletePage() {
    if (confirm('Are you sure you want to delete this page? This action cannot be undone.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>

<style>
.content-preview {
    line-height: 1.8;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.detail-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-item strong {
    display: block;
    margin-bottom: 0.25rem;
    color: #64748b;
    font-size: 0.875rem;
}

code {
    background: #f1f5f9;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.85rem;
}

.card-header h5 {
    color: var(--primary-color);
}
</style>
@endsection
