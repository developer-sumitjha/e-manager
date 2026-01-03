<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Tenant - Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #667eea; }
        body { background: #f8fafc; font-family: 'Inter', sans-serif; }
        .sidebar { position: fixed; width: 280px; height: 100vh; background: linear-gradient(180deg, #667eea, #764ba2); color: white; overflow-y: auto; }
        .sidebar-header { padding: 2rem 1.5rem; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-menu { padding: 1.5rem 0; list-style: none; margin: 0; }
        .sidebar-menu a { display: flex; align-items: center; padding: 0.875rem 1.5rem; color: rgba(255,255,255,0.9); text-decoration: none; }
        .sidebar-menu a.active { background: rgba(255,255,255,0.1); color: white; }
        .sidebar-menu i { width: 24px; margin-right: 12px; }
        .main-content { margin-left: 280px; padding: 2rem; }
        .form-card { background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); max-width: 900px; margin: 0 auto; }
        .save-btn { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 0.75rem 2rem; border-radius: 12px; border: none; width: 100%; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h3><i class="fas fa-crown"></i> SUPER ADMIN</h3></div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('super.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="{{ route('super.tenants.index') }}" class="active"><i class="fas fa-store"></i> Admins</a></li>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 1.5rem;">
            <li><a href="{{ route('super.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <form id="logout-form" action="{{ route('super.logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>

    <div class="main-content">
        <div class="form-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-plus"></i> Create New Admin</h2>
                <a href="{{ route('super.tenants.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('super.tenants.store') }}" method="POST">
                @csrf

                <h4 class="mb-3"><i class="fas fa-building"></i> Business Information</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Business Name *</label>
                        <input type="text" name="business_name" class="form-control" value="{{ old('business_name') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Business Email *</label>
                        <input type="email" name="business_email" class="form-control" value="{{ old('business_email') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Business Phone</label>
                        <input type="text" name="business_phone" class="form-control" value="{{ old('business_phone') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Subdomain *</label>
                        <div class="input-group">
                            <input type="text" name="subdomain" class="form-control" value="{{ old('subdomain') }}" required>
                            <span class="input-group-text">.yourdomain.com</span>
                        </div>
                        <small class="text-muted">Only letters, numbers, and hyphens</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Business Address</label>
                    <textarea name="business_address" class="form-control" rows="2">{{ old('business_address') }}</textarea>
                </div>

                <hr class="my-4">

                <h4 class="mb-3"><i class="fas fa-user"></i> Owner Information</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Owner Name *</label>
                        <input type="text" name="owner_name" class="form-control" value="{{ old('owner_name') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Owner Email *</label>
                        <input type="email" name="owner_email" class="form-control" value="{{ old('owner_email') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Owner Phone</label>
                    <input type="text" name="owner_phone" class="form-control" value="{{ old('owner_phone') }}">
                </div>

                <hr class="my-4">

                <h4 class="mb-3"><i class="fas fa-cog"></i> Account Settings</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Subscription Plan *</label>
                        <select name="current_plan_id" class="form-select" required>
                            <option value="">Select Plan</option>
                            @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ old('current_plan_id') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }} - Rs. {{ number_format($plan->price_monthly) }}/month
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="trial" {{ old('status') == 'trial' ? 'selected' : '' }}>Trial</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note:</strong> Default admin password will be <code>password123</code>. 
                    The owner should change it after first login.
                </div>

                <button type="submit" class="save-btn mt-3">
                    <i class="fas fa-plus"></i> Create Admin
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
