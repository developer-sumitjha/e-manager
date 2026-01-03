<!DOCTYPE html>
<html>
<head>
    <title>API Test - E-Manager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        .test-section { background: #f5f5f5; padding: 20px; margin: 20px 0; border-radius: 8px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        pre { background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 4px; overflow-x: auto; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>🧪 E-Manager API Test</h1>

    <div class="test-section">
        <h2>Test 1: Get Plans</h2>
        <button onclick="testPlans()">Test GET /api/plans</button>
        <pre id="plans-result">Click button to test...</pre>
    </div>

    <div class="test-section">
        <h2>Test 2: Check Subdomain</h2>
        <button onclick="testSubdomain()">Test Check Subdomain</button>
        <pre id="subdomain-result">Click button to test...</pre>
    </div>

    <div class="test-section">
        <h2>Test 3: Create Tenant</h2>
        <button onclick="testSignup()">Test Create Tenant</button>
        <pre id="signup-result">Click button to test...</pre>
    </div>

    <div class="test-section">
        <h2>Info</h2>
        <p><strong>Base URL:</strong> {{ url('/') }}</p>
        <p><strong>API URL:</strong> {{ url('/api') }}</p>
        <p><strong>CSRF Token:</strong> {{ csrf_token() }}</p>
    </div>

    <script>
    async function testPlans() {
        const result = document.getElementById('plans-result');
        result.innerHTML = 'Loading...';
        
        try {
            const response = await fetch('{{ url("/api/plans") }}', {
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            result.innerHTML = '<span class="success">✅ Success!</span>\n\n' + JSON.stringify(data, null, 2);
        } catch (error) {
            result.innerHTML = '<span class="error">❌ Error:</span>\n\n' + error.message;
        }
    }

    async function testSubdomain() {
        const result = document.getElementById('subdomain-result');
        result.innerHTML = 'Loading...';
        
        try {
            const response = await fetch('{{ url("/api/tenants/check-subdomain") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ subdomain: 'test' + Date.now() })
            });
            
            const data = await response.json();
            result.innerHTML = '<span class="success">✅ Success!</span>\n\n' + JSON.stringify(data, null, 2);
        } catch (error) {
            result.innerHTML = '<span class="error">❌ Error:</span>\n\n' + error.message;
        }
    }

    async function testSignup() {
        const result = document.getElementById('signup-result');
        result.innerHTML = 'Loading...';
        
        const timestamp = Date.now();
        const testData = {
            business_name: 'Test Business ' + timestamp,
            business_email: 'test' + timestamp + '@example.com',
            owner_name: 'Test Owner',
            owner_email: 'owner' + timestamp + '@example.com',
            owner_phone: '9800000000',
            password: 'password123',
            password_confirmation: 'password123',
            subdomain: 'test' + timestamp,
            plan_id: 2
        };
        
        try {
            const response = await fetch('{{ url("/api/tenants/signup") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(testData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                result.innerHTML = '<span class="success">✅ Account Created Successfully!</span>\n\n' + 
                                 'Tenant ID: ' + data.tenant.id + '\n' +
                                 'Business: ' + data.tenant.business_name + '\n' +
                                 'Subdomain: ' + data.tenant.subdomain + '\n' +
                                 'Login URL: ' + data.tenant.login_url + '\n\n' +
                                 'Full Response:\n' + JSON.stringify(data, null, 2);
            } else {
                result.innerHTML = '<span class="error">❌ Failed:</span>\n\n' + JSON.stringify(data, null, 2);
            }
        } catch (error) {
            result.innerHTML = '<span class="error">❌ Error:</span>\n\n' + error.message;
        }
    }
    </script>
</body>
</html>





