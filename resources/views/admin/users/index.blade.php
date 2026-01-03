@extends('admin.layouts.app')

@section('title', 'Users')
@section('page-title', 'Users Management')
@section('page-subtitle', 'Manage user roles, permissions, and account status')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">Dashboard</a>
    </div>
    <div class="breadcrumb-item active">Users</div>
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero-section mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="fas fa-users me-3"></i>Users Management
                </h1>
                <p class="hero-subtitle">Manage user roles, permissions, and account status across your platform.</p>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="hero-stat-value" data-count="{{ $users->total() }}">0</span>
                        <span class="hero-stat-label">Total Users</span>
                    </div>
                    <div class="hero-stat">
                        <span class="hero-stat-value" data-count="{{ $users->where('is_active', true)->count() }}">0</span>
                        <span class="hero-stat-label">Active Users</span>
                    </div>
                    <div class="hero-stat">
                        <span class="hero-stat-value" data-count="{{ $users->where('is_active', false)->count() }}">0</span>
                        <span class="hero-stat-label">Inactive Users</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="hero-actions">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-plus me-2"></i>Add New User
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>Search & Filters
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Search Users</label>
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           class="search-input" 
                           placeholder="Search by name or email">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    @foreach(['admin'=>'Admin','employee'=>'Employee','delivery_boy'=>'Delivery Boy','customer'=>'Customer'] as $key=>$label)
                        <option value="{{ $key }}" @selected(request('role')===$key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="active" class="form-select">
                    <option value="">Any Status</option>
                    <option value="1" @selected(request('active')==='1')>Active</option>
                    <option value="0" @selected(request('active')==='0')>Inactive</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Users ({{ $users->total() }})</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" onclick="exportUsers()">
                    <i class="fas fa-download"></i> Export
                </button>
                <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="select-all-users" class="form-check-input">
                        </th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Orders</th>
                        <th>Created</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr data-user-id="{{ $user->id }}">
                        <td>
                            <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-initials">
                                        {{ strtoupper(substr($user->name ?? $user->email, 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 200px;">{{ $user->email }}</div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'employee' ? 'warning' : ($user->role == 'delivery_boy' ? 'info' : 'secondary')) }}">
                                {{ str_replace('_',' ', ucfirst($user->role)) }}
                            </span>
                        </td>
                        <td>
                            <div class="status-toggle">
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-toggle-input" 
                                           type="checkbox" 
                                           {{ $user->is_active ? 'checked' : '' }} 
                                           data-user-id="{{ $user->id }}">
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-secondary">{{ $user->orders_count ?? 0 }}</span>
                        </td>
                        <td>
                            <div class="text-muted">{{ $user->created_at->format('M j, Y') }}</div>
                            <small class="text-muted">{{ $user->created_at->format('g:i A') }}</small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="btn btn-outline-primary" 
                                   title="View User">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="btn btn-outline-secondary" 
                                   title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <button type="button" 
                                        class="btn btn-outline-danger delete-user" 
                                        data-user-id="{{ $user->id }}" 
                                        title="Delete User">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">No users found</h6>
                                <p class="text-muted">Users will appear here once they register.</p>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                    <i class="fas fa-user-plus"></i> Add User
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($users->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
            </div>
            <div>
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize counters
    if (typeof AdminDashboard !== 'undefined') {
        AdminDashboard.initCounters();
    }
    
    // Select all functionality
    const selectAllCheckbox = document.getElementById('select-all-users');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Status toggle functionality
    document.querySelectorAll('.status-toggle-input').forEach(toggle => {
        toggle.addEventListener('change', function() {
            toggleUserStatus(this);
        });
    });
    
    // Delete user functionality
    document.querySelectorAll('.delete-user').forEach(button => {
        button.addEventListener('click', function() {
            deleteUser(this.dataset.userId);
        });
    });
});

function toggleUserStatus(toggle) {
    const userId = toggle.dataset.userId;
    const isActive = toggle.checked;
    
    if (typeof AdminDashboard !== 'undefined') {
        const loadingState = AdminDashboard.setLoadingState(toggle, true);
        
        fetch(`{{ url('admin/users') }}/${userId}/toggle-status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                is_active: isActive
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                AdminDashboard.showNotification(data.message || 'User status updated successfully', 'success');
            } else {
                toggle.checked = !isActive;
                AdminDashboard.showNotification(data.message || 'Failed to update user status', 'error');
            }
        })
        .catch(error => {
            toggle.checked = !isActive;
            AdminDashboard.showNotification('An error occurred while updating user status', 'error');
        })
        .finally(() => {
            AdminDashboard.setLoadingState(toggle, false);
        });
    }
}

function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        return;
    }
    
    const button = document.querySelector(`[data-user-id="${userId}"].delete-user`);
    if (typeof AdminDashboard !== 'undefined') {
        const loadingState = AdminDashboard.setLoadingState(button, true);
        
        fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                AdminDashboard.showNotification(data.message || 'User deleted successfully', 'success');
                // Remove row from table
                const row = document.querySelector(`tr[data-user-id="${userId}"]`);
                if (row) {
                    row.style.opacity = '0';
                    setTimeout(() => {
                        row.remove();
                    }, 300);
                }
            } else {
                AdminDashboard.showNotification(data.message || 'Failed to delete user', 'error');
            }
        })
        .catch(error => {
            AdminDashboard.showNotification('An error occurred while deleting user', 'error');
        })
        .finally(() => {
            AdminDashboard.setLoadingState(button, false);
        });
    }
}

function exportUsers() {
    const url = new URL('{{ route("admin.users.export") }}');
    // Add current filters to export
    const searchTerm = document.getElementById('user-search')?.value;
    const roleFilter = document.getElementById('role-filter')?.value;
    const statusFilter = document.getElementById('status-filter')?.value;
    
    if (searchTerm) url.searchParams.set('search', searchTerm);
    if (roleFilter) url.searchParams.set('role', roleFilter);
    if (statusFilter) url.searchParams.set('status', statusFilter);
    
    window.open(url.toString(), '_blank');
}
</script>
@endpush



