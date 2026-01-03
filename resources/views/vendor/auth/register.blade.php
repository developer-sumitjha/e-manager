<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vendor Registration - E-Manager Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
            font-family: 'Inter', sans-serif;
        }
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
        }
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .register-body {
            padding: 2rem;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .login-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        .login-link:hover {
            color: #764ba2;
        }
        .section-title {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-card">
            <div class="register-header">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                    <i class="fas fa-store"></i>
                </div>
                <h3 class="mb-0">Vendor Registration</h3>
                <p class="mb-0 opacity-75">Join our platform and start selling</p>
            </div>
            <div class="register-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('vendor.register') }}">
                    @csrf
                    
                    <!-- Business Information -->
                    <h5 class="section-title">
                        <i class="fas fa-building"></i> Business Information
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="business_name" class="form-label">Business Name *</label>
                            <input type="text" class="form-control @error('business_name') is-invalid @enderror" 
                                   id="business_name" name="business_name" value="{{ old('business_name') }}" required>
                            @error('business_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="business_email" class="form-label">Business Email *</label>
                            <input type="email" class="form-control @error('business_email') is-invalid @enderror" 
                                   id="business_email" name="business_email" value="{{ old('business_email') }}" required>
                            @error('business_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="business_phone" class="form-label">Business Phone</label>
                            <input type="text" class="form-control @error('business_phone') is-invalid @enderror" 
                                   id="business_phone" name="business_phone" value="{{ old('business_phone') }}">
                            @error('business_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="subdomain" class="form-label">Store URL *</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('subdomain') is-invalid @enderror" 
                                       id="subdomain" name="subdomain" value="{{ old('subdomain') }}" required>
                                <span class="input-group-text">.emanager.com</span>
                            </div>
                            @error('subdomain')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="business_address" class="form-label">Business Address</label>
                        <textarea class="form-control @error('business_address') is-invalid @enderror" 
                                  id="business_address" name="business_address" rows="2">{{ old('business_address') }}</textarea>
                        @error('business_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Owner Information -->
                    <h5 class="section-title">
                        <i class="fas fa-user"></i> Owner Information
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="owner_name" class="form-label">Owner Name *</label>
                            <input type="text" class="form-control @error('owner_name') is-invalid @enderror" 
                                   id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required>
                            @error('owner_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="owner_email" class="form-label">Owner Email *</label>
                            <input type="email" class="form-control @error('owner_email') is-invalid @enderror" 
                                   id="owner_email" name="owner_email" value="{{ old('owner_email') }}" required>
                            @error('owner_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="owner_phone" class="form-label">Owner Phone</label>
                            <input type="text" class="form-control @error('owner_phone') is-invalid @enderror" 
                                   id="owner_phone" name="owner_phone" value="{{ old('owner_phone') }}">
                            @error('owner_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="this.previousElementSibling.type = this.previousElementSibling.type==='password' ? 'text' : 'password'; this.textContent = this.textContent==='Show' ? 'Hide' : 'Show'">Show</button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password *</label>
                        <div class="input-group">
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="this.previousElementSibling.type = this.previousElementSibling.type==='password' ? 'text' : 'password'; this.textContent = this.textContent==='Show' ? 'Hide' : 'Show'">Show</button>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> Your account will be pending approval. You will receive an email notification once approved.
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-register">
                            <i class="fas fa-user-plus"></i> Register as Vendor
                        </button>
                    </div>
                </form>

                <div class="text-center">
                    <p class="mb-0">Already have an account? 
                        <a href="{{ route('vendor.login') }}" class="login-link">Login here</a>
                    </p>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('super.login') }}" class="text-muted small">
                        <i class="fas fa-crown"></i> Super Admin Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

