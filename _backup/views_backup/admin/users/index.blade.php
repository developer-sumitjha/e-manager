@extends('admin.layouts.app')

@section('title', 'Users')
@section('page-title', 'Users Management')

@section('content')
<div class="p-4 rounded-3 mb-4" style="background: linear-gradient(135deg, #8B5CF6 0%, #6366F1 100%); color:#fff;">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h2 class="m-0 fw-bold"><i class="fas fa-users"></i> Users</h2>
            <div class="opacity-75">Manage roles, activation and granular permissions</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.create') }}" class="btn btn-light">
                <i class="fas fa-user-plus"></i> Add User
            </a>
        </div>
    </div>
    <div class="d-flex flex-wrap gap-3 mt-3">
        <span class="badge bg-dark">Total: {{ $users->total() }}</span>
        <span class="badge bg-success">Active: {{ $users->where('is_active', true)->count() }}</span>
        <span class="badge bg-secondary">Inactive: {{ $users->where('is_active', false)->count() }}</span>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-sm-6 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search by name or email">
                </div>
            </div>
            <div class="col-sm-3 col-md-3">
                <select name="role" class="form-select">
                    <option value="">All roles</option>
                    @foreach(['admin'=>'Admin','employee'=>'Employee','delivery_boy'=>'Delivery Boy','customer'=>'Customer'] as $key=>$label)
                        <option value="{{ $key }}" @selected(request('role')===$key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 col-md-2">
                <select name="active" class="form-select">
                    <option value="">Any status</option>
                    <option value="1" @selected(request('active')==='1')>Active</option>
                    <option value="0" @selected(request('active')==='0')>Inactive</option>
                </select>
            </div>
            <div class="col-sm-12 col-md-2 d-grid">
                <button class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Active</th>
                        <th>Orders</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#EEF2FF;color:#4F46E5;font-weight:700;">
                                {{ strtoupper(substr($user->name ?? $user->email,0,1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                <div class="text-muted small">#{{ $user->tenant_id }}</div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'employee' ? 'warning' : ($user->role == 'delivery_boy' ? 'info' : 'secondary')) }}">
                                {{ str_replace('_',' ', ucfirst($user->role)) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">{{ $user->is_active ? 'Yes' : 'No' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $user->orders_count ?? 0 }}</span>
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-secondary" title="View"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-pen"></i></a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No users found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $users->links() }}
</div>
@endsection



