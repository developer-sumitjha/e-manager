@extends('admin.layouts.app')

@section('title', 'Create User')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="m-0 fw-bold"><i class="fas fa-user-plus"></i> Create User</h2>
            <div class="text-muted">Add a new user to your admin account</div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-lg">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Choose User Type</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(['admin'=>'Admin','employee'=>'Employee','delivery_boy'=>'Delivery Boy','customer'=>'Customer'] as $key=>$label)
                            <label class="btn btn-outline-primary {{ old('role','admin')===$key ? 'active' : '' }}">
                                <input type="radio" name="role" value="{{ $key }}" class="d-none role-switch" {{ old('role','admin')===$key ? 'checked' : '' }}> {{ $label }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Common Fields -->
                    <div class="col-md-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" minlength="8" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" minlength="8" required>
                    </div>

                    <!-- Employee Permissions -->
                    <div class="col-12 role-section role-employee" style="display: none;">
                        <hr>
                        <h5 class="fw-bold"><i class="fas fa-shield-alt"></i> Employee Permissions</h5>
                        <div class="row g-3">
                            @foreach(['products'=>'Products','orders'=>'Orders','inventory'=>'Inventory','shipments'=>'Shipments','manual_delivery'=>'Manual Delivery','site_builder'=>'Site Builder','accounting'=>'Accounting','reports'=>'Reports','users'=>'Users'] as $k=>$label)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $k }}" id="perm_{{ $k }}">
                                    <label class="form-check-label" for="perm_{{ $k }}">{{ $label }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Delivery Boy Details -->
                    <div class="col-12 role-section role-delivery_boy" style="display: none;">
                        <hr>
                        <h5 class="fw-bold"><i class="fas fa-motorcycle"></i> Delivery Boy Details</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Zone <span class="text-danger">*</span></label>
                                <select name="db_zone" class="form-select">
                                    @foreach(['north','south','east','west','central'] as $z)
                                    <option value="{{ $z }}" @selected(old('db_zone')===$z)>{{ ucfirst($z) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Vehicle Type</label>
                                <select name="db_vehicle_type" class="form-select">
                                    @foreach(['motorcycle','bicycle','car','van'] as $v)
                                    <option value="{{ $v }}" @selected(old('db_vehicle_type')===$v)>{{ ucfirst($v) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Vehicle Number</label>
                                <input type="text" name="db_vehicle_number" value="{{ old('db_vehicle_number') }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">CNIC</label>
                                <input type="text" name="db_cnic" value="{{ old('db_cnic') }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">License Number</label>
                                <input type="text" name="db_license_number" value="{{ old('db_license_number') }}" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <textarea name="db_address" class="form-control" rows="2">{{ old('db_address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Extra (optional) -->
                    <div class="col-12 role-section role-customer" style="display: none;">
                        <hr>
                        <h5 class="fw-bold"><i class="fas fa-user"></i> Customer Details</h5>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <textarea name="customer_address" class="form-control" rows="2">{{ old('customer_address') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button class="btn btn-primary">
                        <i class="fas fa-save"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.querySelectorAll('.role-switch').forEach(r => {
    r.addEventListener('change', () => toggleRoleSections(r.value));
});

function toggleRoleSections(role) {
    document.querySelectorAll('.role-section').forEach(s => s.style.display = 'none');
    const sec = document.querySelector('.role-' + role.replace(/\s/g,'_'));
    if (sec) sec.style.display = '';
}

// Initialize
toggleRoleSections(document.querySelector('.role-switch:checked')?.value || 'admin');
</script>
@endpush


