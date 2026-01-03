@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="m-0 fw-bold"><i class="fas fa-user-cog"></i> Edit User</h2>
            <div class="text-muted">Update user details, role and permissions</div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-lg">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="admin" @selected(old('role', $user->role)==='admin')>Admin</option>
                            <option value="employee" @selected(old('role', $user->role)==='employee')>Employee</option>
                            <option value="delivery_boy" @selected(old('role', $user->role)==='delivery_boy')>Delivery Boy</option>
                            <option value="customer" @selected(old('role', $user->role)==='customer')>Customer</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="fw-bold"><i class="fas fa-shield-alt"></i> Employee Permissions</h5>
                <p class="text-muted">Applies when role is Employee. Admins have full access by default.</p>
                @php
                    $perms = is_array($user->permissions) ? $user->permissions : [];
                @endphp
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="products" id="permProducts" {{ in_array('products', $perms) ? 'checked' : '' }}>
                            <label class="form-check-label" for="permProducts">Products</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="orders" id="permOrders" {{ in_array('orders', $perms) ? 'checked' : '' }}>
                            <label class="form-check-label" for="permOrders">Orders</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="reports" id="permReports" {{ in_array('reports', $perms) ? 'checked' : '' }}>
                            <label class="form-check-label" for="permReports">Reports</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="inventory" id="permInventory" {{ in_array('inventory', $perms) ? 'checked' : '' }}>
                            <label class="form-check-label" for="permInventory">Inventory</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection





