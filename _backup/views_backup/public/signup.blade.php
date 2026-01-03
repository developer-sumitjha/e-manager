<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign Up - E-Manager Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f3f4f6; padding: 3rem 0; }
        .signup-container { max-width: 900px; margin: 0 auto; }
        .signup-card { background: white; border-radius: 12px; padding: 3rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .form-section { margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid #e5e7eb; }
        .plan-selector { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .plan-card { border: 2px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; cursor: pointer; transition: all 0.3s; }
        .plan-card:hover { border-color: #8B5CF6; }
        .plan-card.selected { border-color: #8B5CF6; background: rgba(139, 92, 246, 0.05); }
        .subdomain-input { display: flex; align-items: stretch; }
        .subdomain-suffix { background: #f3f4f6; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-left: none; border-radius: 0 6px 6px 0; }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-card">
            <div class="text-center mb-4">
                <h1>Start Your Free Trial</h1>
                <p class="text-muted">Get started with e-manager in minutes. No credit card required.</p>
            </div>

            <form id="signupForm">
                @csrf
                
                <!-- Business Information -->
                <div class="form-section">
                    <h4>Business Information</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Business Name *</label>
                            <input type="text" name="business_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Business Email *</label>
                            <input type="email" name="business_email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Business Phone</label>
                            <input type="tel" name="business_phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Business Type</label>
                            <select name="business_type" class="form-select">
                                <option value="">Select Type</option>
                                <option value="retail">Retail</option>
                                <option value="wholesale">Wholesale</option>
                                <option value="restaurant">Restaurant</option>
                                <option value="ecommerce">E-commerce</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Owner Information -->
                <div class="form-section">
                    <h4>Owner Information</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="owner_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="owner_email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone *</label>
                            <input type="tel" name="owner_phone" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password *</label>
                            <div class="input-group">
                                <input type="password" id="owner_password" name="password" class="form-control" minlength="8" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('owner_password', this)">Show</button>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm Password *</label>
                            <div class="input-group">
                                <input type="password" id="owner_password_confirmation" name="password_confirmation" class="form-control" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('owner_password_confirmation', this)">Show</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Plan Selection -->
                <div class="form-section">
                    <h4>Select Your Plan</h4>
                    <div class="plan-selector">
                        @foreach($plans as $plan)
                        <div class="plan-card {{ $loop->index == 1 ? 'selected' : '' }}" onclick="selectPlan({{ $plan->id }}, this)">
                            <input type="radio" name="plan_id" value="{{ $plan->id }}" {{ $loop->index == 1 ? 'checked' : '' }} style="display:none;">
                            <h5>{{ $plan->name }}</h5>
                            <div class="price">Rs. {{ number_format($plan->price_monthly, 0) }}</div>
                            <small>/month</small>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Terms -->
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the Terms of Service and Privacy Policy
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-rocket"></i> Start Free Trial
                </button>
            </form>

            <div class="text-center mt-4">
                <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
            </div>
        </div>
    </div>

    <script>
    function selectPlan(planId, element) {
        document.querySelectorAll('.plan-card').forEach(card => card.classList.remove('selected'));
        element.classList.add('selected');
        element.querySelector('input[type="radio"]').checked = true;
    }

    function togglePwd(id, btn){
        const input = document.getElementById(id);
        const isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        btn.textContent = isText ? 'Show' : 'Hide';
    }

    document.getElementById('signupForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
        
        fetch('{{ url("/api/tenants/signup") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.json().then(data => {
                console.log('Response data:', data);
                if (!response.ok) {
                    return Promise.reject({ status: response.status, data: data });
                }
                return data;
            });
        })
        .then(data => {
            console.log('Success response:', data);
            if (data.success) {
                window.location.href = '{{ route('vendor.login') }}?signup=success';
            } else {
                let errorMsg = data.message || 'Unknown error';
                if (data.errors) {
                    errorMsg += '\n\nValidation Errors:\n';
                    for (let field in data.errors) {
                        errorMsg += '• ' + field + ': ' + data.errors[field].join(', ') + '\n';
                    }
                }
                if (data.error) {
                    errorMsg += '\nDetails: ' + data.error;
                }
                alert('❌ Error: ' + errorMsg);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-rocket"></i> Start Free Trial';
            }
        })
        .catch(err => {
            console.error('Signup error:', err);
            let errorMsg = 'An error occurred';
            
            if (err.data) {
                errorMsg = err.data.message || errorMsg;
                if (err.data.errors) {
                    errorMsg += '\n\nValidation Errors:\n';
                    for (let field in err.data.errors) {
                        errorMsg += '• ' + field + ': ' + err.data.errors[field].join(', ') + '\n';
                    }
                }
            } else if (err.message) {
                errorMsg = err.message;
            }
            
            alert('❌ ' + errorMsg);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-rocket"></i> Start Free Trial';
        });
    });
    </script>
</body>
</html>

