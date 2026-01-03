@extends('admin.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1><i class="fas fa-file-alt"></i> Site Pages Management</h1>
        <p class="text-muted">Manage your website pages (About, Contact, Custom Pages)</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.site-pages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Page
        </a>
        <a href="{{ route('admin.site-builder.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Site Builder
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        @if($pages->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 5%;">Order</th>
                        <th style="width: 25%;">Title</th>
                        <th style="width: 15%;">Type</th>
                        <th style="width: 15%;">Slug</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 10%;">In Menu</th>
                        <th style="width: 10%;">Created</th>
                        <th style="width: 10%;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pages as $page)
                    <tr>
                        <td>
                            <span class="badge bg-secondary">{{ $page->menu_order }}</span>
                        </td>
                        <td>
                            <strong>{{ $page->title }}</strong>
                            @if($page->description)
                            <br><small class="text-muted">{{ Str::limit($page->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge 
                                @if($page->page_type == 'about') bg-info
                                @elseif($page->page_type == 'contact') bg-warning
                                @elseif($page->page_type == 'products') bg-primary
                                @elseif($page->page_type == 'categories') bg-success
                                @else bg-secondary
                                @endif">
                                {{ $page->page_type_label }}
                            </span>
                        </td>
                        <td>
                            <code>/{{ $page->slug }}</code>
                        </td>
                        <td>
                            @if($page->is_active)
                            <span class="badge bg-success"><i class="fas fa-check"></i> Active</span>
                            @else
                            <span class="badge bg-danger"><i class="fas fa-times"></i> Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($page->show_in_menu)
                            <span class="badge bg-info"><i class="fas fa-eye"></i> Visible</span>
                            @else
                            <span class="badge bg-secondary"><i class="fas fa-eye-slash"></i> Hidden</span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $page->created_at->format('M d, Y') }}</small>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.site-pages.edit', $page) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.site-pages.show', $page) }}" class="btn btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Delete" onclick="deletePage({{ $page->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            
                            <form id="delete-form-{{ $page->id }}" action="{{ route('admin.site-pages.destroy', $page) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $pages->links() }}
        </div>
        @else
        <div class="empty-state text-center py-5">
            <i class="fas fa-file-alt fa-4x mb-3 text-muted"></i>
            <h4>No Pages Yet</h4>
            <p class="text-muted">Create your first page to get started.</p>
            <a href="{{ route('admin.site-pages.create') }}" class="btn btn-primary mt-3">
                <i class="fas fa-plus"></i> Create Your First Page
            </a>
        </div>
        @endif
    </div>
</div>

<script>
function deletePage(id) {
    if (confirm('Are you sure you want to delete this page? This action cannot be undone.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
}

.page-title h1 {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #1e293b;
}

.page-title h1 i {
    color: var(--primary-color);
    margin-right: 0.5rem;
}

.page-actions {
    display: flex;
    gap: 0.75rem;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

.empty-state i {
    opacity: 0.3;
}

code {
    background: #f1f5f9;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.85rem;
}
</style>
@endsection






