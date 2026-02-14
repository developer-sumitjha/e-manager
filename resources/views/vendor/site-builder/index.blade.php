<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Site Builder - {{ $tenant->business_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #F5F7FB;
            font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #1C1E21;
            line-height: 1.6;
        }
        
        /* Modern Navbar - DealDeck Style */
        .navbar {
            background: #FFFFFF;
            border-bottom: 1px solid #E5E7EB;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: #1C1E21 !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: -0.025em;
        }
        
        .navbar-brand i {
            font-size: 1.5rem;
            color: #000000;
        }
        
        .nav-link {
            color: #6B7280 !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.2s ease;
        }
        
        .nav-link:hover {
            color: #000000 !important;
        }
        
        .dropdown-menu {
            border: 1px solid #E5E7EB;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 12px;
            padding: 0.5rem;
        }
        
        .dropdown-item {
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background: #F5F7FB;
            color: #000000;
        }
        
        /* Container */
        .container {
            max-width: 1400px;
            padding: 2rem 1.5rem;
        }
        
        /* Cards - DealDeck Style */
        .card {
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 20px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            border-color: #000000;
        }
        
        .card-header {
            background: #FFFFFF;
            border-bottom: 1px solid #E5E7EB;
            padding: 1.25rem 1.5rem;
        }
        
        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1C1E21;
            margin: 0;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Buttons - DealDeck Style */
        .btn {
            border-radius: 12px;
            font-weight: 500;
            padding: 12px 24px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            border-width: 1.5px;
        }
        
        .btn-primary {
            background: #000000;
            border-color: #000000;
            color: #FFFFFF;
        }
        
        .btn-primary:hover {
            background: #333333;
            border-color: #333333;
            color: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .btn-outline-primary {
            color: #000000;
            border-color: #000000;
            background: transparent;
        }
        
        .btn-outline-primary:hover {
            background: #000000;
            border-color: #000000;
            color: #FFFFFF;
            transform: translateY(-2px);
        }
        
        .btn-outline-success {
            color: #00B074;
            border-color: #00B074;
        }
        
        .btn-outline-success:hover {
            background: #00B074;
            border-color: #00B074;
            color: white;
        }
        
        .btn-outline-secondary {
            color: #6B7280;
            border-color: #6B7280;
        }
        
        .btn-outline-secondary:hover {
            background: #6B7280;
            border-color: #6B7280;
            color: white;
        }
        
        /* Badges - DealDeck Style */
        .badge {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
        }
        
        .bg-primary {
            background: #F5F7FB !important;
            color: #1C1E21 !important;
        }
        
        .bg-success {
            background: #D1FAE5 !important;
            color: #065F46 !important;
        }
        
        .bg-warning {
            background: #FEF3C7 !important;
            color: #92400E !important;
        }
        
        .bg-secondary {
            background: #F5F7FB !important;
            color: #4B5563 !important;
        }
        
        /* Typography */
        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 0.5rem;
        }
        
        h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1C1E21;
            margin-bottom: 0.5rem;
        }
        
        h5 {
            font-size: 1rem;
            font-weight: 600;
            color: #1C1E21;
        }
        
        .text-muted {
            color: #9CA3AF !important;
        }
        
        .text-primary {
            color: #000000 !important;
        }
        
        /* Alerts */
        .alert {
            border-radius: 12px;
            border: 1px solid;
            padding: 0.875rem 1rem;
        }
        
        .alert-success {
            background: #D1FAE5;
            border-color: #A7F3D0;
            color: #065F46;
        }
        
        /* Spacing */
        .mb-4 {
            margin-bottom: 1.5rem;
        }
        
        .mb-3 {
            margin-bottom: 1rem;
        }
        
        .mb-2 {
            margin-bottom: 0.5rem;
        }
        
        .mt-4 {
            margin-top: 1.5rem;
        }
        
        /* Site Builder Specific Styles */
        .site-builder-header {
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        .tab-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #E5E7EB;
            padding-bottom: 1rem;
        }
        
        .tab-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            background: transparent;
            color: #9CA3AF;
            font-weight: 500;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .tab-btn:hover {
            background: #F5F7FB;
            color: #000000;
        }
        
        .tab-btn.active {
            background: #000000;
            color: #FFFFFF;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .form-label {
            font-weight: 500;
            color: #1C1E21;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #000000;
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.1);
            outline: none;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .site-builder-header {
                padding: 1.5rem;
            }
        }
        
        /* Clean shadows */
        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }
        
        /* Text colors */
        strong {
            font-weight: 600;
            color: #1C1E21;
        }
        
        small {
            font-size: 0.8125rem;
            color: #9CA3AF;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('vendor.dashboard') }}">
                <i class="fas fa-store"></i> {{ $tenant->business_name }}
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('vendor.dashboard') }}">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('vendor.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Site Builder Header -->
        <div class="site-builder-header mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1>
                        <i class="fas fa-paint-brush"></i> Site Builder
                    </h1>
                    <p class="text-muted mb-0">Customize your storefront with ease - no coding required!</p>
                </div>
                <div>
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fas fa-eye"></i> Preview Store
                    </a>
                </div>
            </div>
        </div>

        <!-- Site Builder Tabs -->
        <div class="card">
            <div class="card-header">
                <div class="tab-nav">
                    <button class="tab-btn active" data-tab="general">
                        <i class="fas fa-info-circle"></i> General
                    </button>
                    <button class="tab-btn" data-tab="theme">
                        <i class="fas fa-palette"></i> Theme
                    </button>
                    <button class="tab-btn" data-tab="banner">
                        <i class="fas fa-image"></i> Banner
                    </button>
                    <button class="tab-btn" data-tab="navigation">
                        <i class="fas fa-bars"></i> Navigation
                    </button>
                    <button class="tab-btn" data-tab="homepage">
                        <i class="fas fa-home"></i> Homepage
                    </button>
                    <button class="tab-btn" data-tab="products">
                        <i class="fas fa-box"></i> Products
                    </button>
                    <button class="tab-btn" data-tab="footer">
                        <i class="fas fa-shoe-prints"></i> Footer
                    </button>
                    <button class="tab-btn" data-tab="seo">
                        <i class="fas fa-search"></i> SEO
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- General Tab -->
                <div class="tab-content active" id="general-tab">
                    <h5 class="mb-3">General Settings</h5>
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Site Name</label>
                                <input type="text" class="form-control" value="{{ $settings->site_name ?? '' }}" placeholder="Enter site name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Site Tagline</label>
                                <input type="text" class="form-control" value="{{ $settings->site_tagline ?? '' }}" placeholder="Enter tagline">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Contact Email</label>
                                <input type="email" class="form-control" value="{{ $settings->contact_email ?? '' }}" placeholder="contact@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Phone</label>
                                <input type="text" class="form-control" value="{{ $settings->contact_phone ?? '' }}" placeholder="+1 234 567 8900">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>

                <!-- Theme Tab -->
                <div class="tab-content" id="theme-tab">
                    <h5 class="mb-3">Theme Settings</h5>
                    <p class="text-muted">Theme customization options will be available here.</p>
                </div>

                <!-- Banner Tab -->
                <div class="tab-content" id="banner-tab">
                    <h5 class="mb-3">Banner Settings</h5>
                    <p class="text-muted">Banner customization options will be available here.</p>
                </div>

                <!-- Navigation Tab -->
                @include('admin.site-builder.tabs.navigation')

                <!-- Homepage Tab -->
                <div class="tab-content" id="homepage-tab">
                    <h5 class="mb-3">Homepage Settings</h5>
                    <p class="text-muted">Homepage layout customization options will be available here.</p>
                </div>

                <!-- Products Tab -->
                <div class="tab-content" id="products-tab">
                    <h5 class="mb-3">Products Settings</h5>
                    <p class="text-muted">Product page customization options will be available here.</p>
                </div>

                <!-- Footer Tab -->
                <div class="tab-content" id="footer-tab">
                    <h5 class="mb-3">Footer Settings</h5>
                    <p class="text-muted">Footer customization options will be available here.</p>
                </div>

                <!-- SEO Tab -->
                <div class="tab-content" id="seo-tab">
                    <h5 class="mb-3">SEO Settings</h5>
                    <p class="text-muted">SEO optimization options will be available here.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tab Switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all tabs and content
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId + '-tab').classList.add('active');
            });
        });
    </script>
</body>
</html>
