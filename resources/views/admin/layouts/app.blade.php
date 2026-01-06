<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'E-Manager') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    <!-- DealDeck Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/theme-dealdeck.css') }}?v={{ time() }}">
    
    <!-- Professional Modern Color Theme Styles -->
    <style>
        .theme-dealdeck {
            background: #ffffff !important;
            font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }
        
        /* Add fade-in animation to all cards */
        .theme-dealdeck .card,
        .theme-dealdeck .stat-card {
            animation: fadeInUp 0.8s ease-out;
        }
        
        /* Sidebar - no animation */
        .theme-dealdeck .sidebar {
            /* Animation removed */
        }
        
        /* Black and white scrollbar */
        .theme-dealdeck ::-webkit-scrollbar {
            width: 10px;
        }
        
        .theme-dealdeck ::-webkit-scrollbar-track {
            background: #f5f5f5;
            border-radius: 5px;
        }
        
        .theme-dealdeck ::-webkit-scrollbar-thumb {
            background: #000000;
            border-radius: 5px;
            border: 2px solid #ffffff;
        }
        
        .theme-dealdeck ::-webkit-scrollbar-thumb:hover {
            background: #333333;
        }
        
        /* Black and white active elements */
        .theme-dealdeck .nav-link.active {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2) !important;
        }
        
        /* Black and white focus states */
        .theme-dealdeck input:focus,
        .theme-dealdeck button:focus {
            outline: none !important;
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.1) !important;
            border-color: #000000 !important;
        }
        
        /* Black and white theme for page title */
        .theme-dealdeck .page-title {
            color: #000000 !important;
            background: none !important;
            -webkit-background-clip: unset !important;
            -webkit-text-fill-color: #000000 !important;
            background-clip: unset !important;
        }
        
        /* Black and white button */
        .theme-dealdeck .btn-primary {
            background: #000000 !important;
            border: 1px solid #000000 !important;
            color: #ffffff !important;
            border-radius: 12px !important;
            font-weight: 500 !important;
            padding: 12px 24px !important;
            transition: all 0.3s ease !important;
        }
        
        .theme-dealdeck .btn-primary:hover {
            background: #333333 !important;
            border-color: #333333 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2) !important;
        }
        
        /* Professional card styling */
        .theme-dealdeck .card {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 20px !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
            transition: all 0.3s ease !important;
        }
        
        .theme-dealdeck .card:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
            border-color: #000000 !important;
        }
        
        /* Professional stat card styling */
        .theme-dealdeck .stat-card {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 20px !important;
            padding: 24px !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
            transition: all 0.3s ease !important;
        }
        
        .theme-dealdeck .stat-card:hover {
            transform: translateY(-4px) !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
            border-color: #000000 !important;
        }
        
        /* Black and white hero section */
        .theme-dealdeck .hero-section {
            background: #ffffff !important;
            border: 1px solid #000000 !important;
            border-radius: 20px !important;
            padding: 32px !important;
            margin-bottom: 32px !important;
        }
        
        .theme-dealdeck .hero-title {
            color: #000000 !important;
            font-weight: 700 !important;
            font-size: 2.5rem !important;
            margin-bottom: 16px !important;
        }
        
        .theme-dealdeck .hero-subtitle {
            color: #666666 !important;
            font-size: 1.125rem !important;
            margin-bottom: 24px !important;
        }
        
        .theme-dealdeck .hero-stat-value {
            font-size: 2rem !important;
            font-weight: 700 !important;
            color: #000000 !important;
            margin-bottom: 4px !important;
        }
        
        .theme-dealdeck .hero-stat-label {
            font-size: 0.875rem !important;
            color: #6B7280 !important;
            font-weight: 500 !important;
        }
        
        /* Professional table styling */
        .theme-dealdeck .table {
            background: #ffffff !important;
            border-radius: 12px !important;
            overflow: hidden !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
            border: 1px solid #e5e7eb !important;
        }
        
        .theme-dealdeck .table thead th {
            background: linear-gradient(135deg, #EFF6FF 0%, #F3F0FF 100%) !important;
            color: #1F2937 !important;
            font-weight: 600 !important;
            padding: 16px !important;
            border: none !important;
            font-size: 0.875rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }
        
        .theme-dealdeck .table tbody td {
            padding: 16px !important;
            border: none !important;
            border-bottom: 1px solid #e5e7eb !important;
            color: #1F2937 !important;
            vertical-align: middle !important;
        }
        
        .theme-dealdeck .table tbody tr:hover {
            background: #EFF6FF !important;
        }
        
        .theme-dealdeck .table tbody tr:last-child td {
            border-bottom: none !important;
        }
        
        /* Professional form styling */
        .theme-dealdeck .form-control {
            border: 2px solid #e5e7eb !important;
            border-radius: 12px !important;
            padding: 12px 16px !important;
            background: #ffffff !important;
            color: #1F2937 !important;
            font-size: 0.875rem !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }
        
        .theme-dealdeck .form-control:focus {
            outline: none !important;
            border-color: #000000 !important;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1) !important;
            transform: translateY(-1px) !important;
        }
        
        .theme-dealdeck .form-select {
            border: 2px solid #e5e7eb !important;
            border-radius: 12px !important;
            padding: 12px 16px !important;
            background: #ffffff !important;
            color: #1F2937 !important;
            font-size: 0.875rem !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }
        
        .theme-dealdeck .form-select:focus {
            outline: none !important;
            border-color: #000000 !important;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1) !important;
            transform: translateY(-1px) !important;
        }
        
        .theme-dealdeck .form-label {
            color: #1F2937 !important;
            font-weight: 500 !important;
            margin-bottom: 8px !important;
            display: block !important;
        }
        
        /* Professional badge styling */
        .theme-dealdeck .badge {
            padding: 6px 12px !important;
            border-radius: 9999px !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }
        
        .theme-dealdeck .badge-primary {
            background: #000000 !important;
            color: #ffffff !important;
            border: 1px solid #000000 !important;
        }
        
        .theme-dealdeck .badge-success {
            background: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #000000 !important;
        }
        
        .theme-dealdeck .badge-warning {
            background: #f5f5f5 !important;
            color: #000000 !important;
            border: 1px solid #000000 !important;
        }
        
        .theme-dealdeck .badge-danger {
            background: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #000000 !important;
        }
        
        .theme-dealdeck .badge-secondary {
            background: #ffffff !important;
            color: #666666 !important;
            border: 1px solid #666666 !important;
        }
        
        /* Professional alert styling */
        .theme-dealdeck .alert {
            border: none !important;
            border-radius: 12px !important;
            padding: 16px 24px !important;
            margin-bottom: 16px !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
            border-left: 4px solid !important;
        }
        
        .theme-dealdeck .alert-success {
            background: #ECFDF5 !important;
            color: #059669 !important;
            border-left-color: #10B981 !important;
        }
        
        .theme-dealdeck .alert-danger {
            background: #FEF2F2 !important;
            color: #DC2626 !important;
            border-left-color: #EF4444 !important;
        }
        
        .theme-dealdeck .alert-warning {
            background: #FFFBEB !important;
            color: #D97706 !important;
            border-left-color: #F59E0B !important;
        }
        
        .theme-dealdeck .alert-info {
            background: #ECFEFF !important;
            color: #0891B2 !important;
            border-left-color: #06B6D4 !important;
        }
        
        /* Professional pagination styling */
        .theme-dealdeck .pagination {
            gap: 8px !important;
        }
        
        .theme-dealdeck .page-link {
            border: 1px solid #e5e7eb !important;
            color: #1F2937 !important;
            background: #ffffff !important;
            border-radius: 12px !important;
            padding: 12px 16px !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }
        
        .theme-dealdeck .page-link:hover {
            background: #f5f5f5 !important;
            color: #000000 !important;
            border-color: #000000 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        }
        
        .theme-dealdeck .page-item.active .page-link {
            background: #000000 !important;
            color: #ffffff !important;
            border-color: #000000 !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        }
        
        /* Professional modal styling */
        .theme-dealdeck .modal-content {
            border: none !important;
            border-radius: 20px !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
            backdrop-filter: blur(10px) !important;
        }
        
        .theme-dealdeck .modal-header {
            background: #ffffff !important;
            border-bottom: 1px solid #000000 !important;
            border-radius: 20px 20px 0 0 !important;
        }
        
        .theme-dealdeck .modal-title {
            color: #1F2937 !important;
            font-weight: 600 !important;
        }
        
        .theme-dealdeck .modal-body {
            padding: 32px !important;
        }
        
        .theme-dealdeck .modal-footer {
            border-top: 1px solid #e5e7eb !important;
            background: #F9FAFB !important;
            border-radius: 0 0 20px 20px !important;
        }
    </style>
    
    @stack('styles')
    @yield('styles')
</head>
    <body data-theme="light" class="theme-dealdeck">
        <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="E-Manager Logo" onerror="this.style.display='none'">
                    <span class="logo-text">E-Manager</span>
                </a>
                <button class="sidebar-toggle d-lg-none" id="sidebar-toggle" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <nav class="sidebar-nav">
                <!-- Dashboard -->
                <div class="nav-section">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>
                
                <!-- Products -->
                <div class="nav-section">
                    <div class="nav-section-title">Products</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box"></i>
                            <span class="nav-text">Products</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tags"></i>
                            <span class="nav-text">Categories</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.inventory.index') }}" class="nav-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <span class="nav-text">Inventory</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.stock-adjustments.index') }}" class="nav-link {{ request()->routeIs('admin.stock-adjustments.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-adjust"></i>
                            <span class="nav-text">Stock Adjustments</span>
                        </a>
                    </div>
                </div>
                
                <!-- Orders -->
                <div class="nav-section">
                    <div class="nav-section-title">Orders</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <span class="nav-text">Orders</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.pending-orders.index') }}" class="nav-link {{ request()->routeIs('admin.pending-orders.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clock"></i>
                            <span class="nav-text">Pending Orders</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.shipments.index') }}" class="nav-link {{ request()->routeIs('admin.shipments.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck"></i>
                            <span class="nav-text">Shipments</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.rejected-orders.index') }}" class="nav-link {{ request()->routeIs('admin.rejected-orders.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-times-circle"></i>
                            <span class="nav-text">Rejected Orders</span>
                        </a>
                    </div>
                </div>
                
                <!-- Logistics -->
                <div class="nav-section">
                    <div class="nav-section-title">Logistics</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.gaaubesi.index') }}" class="nav-link {{ request()->routeIs('admin.gaaubesi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shipping-fast"></i>
                            <span class="nav-text">Gaaubesi Logistics</span>
                        </a>
                    </div>
                    <div class="nav-item has-submenu">
                        <a href="#" class="nav-link submenu-toggle">
                            <i class="nav-icon fas fa-motorcycle"></i>
                            <span class="nav-text">Manual Delivery</span>
                            <i class="fas fa-chevron-right submenu-arrow"></i>
                        </a>
                        <div class="submenu">
                            <a href="{{ route('admin.manual-delivery.index') }}" class="nav-link {{ request()->routeIs('admin.manual-delivery.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <span class="nav-text">Dashboard</span>
                            </a>
                            <a href="{{ route('admin.manual-delivery.allocation') }}" class="nav-link {{ request()->routeIs('admin.manual-delivery.allocation') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-truck"></i>
                                <span class="nav-text">Order Allocation</span>
                            </a>
                            <a href="{{ route('admin.manual-delivery.delivery-boys') }}" class="nav-link {{ request()->routeIs('admin.manual-delivery.delivery-boys') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <span class="nav-text">Delivery Boys</span>
                            </a>
                            <a href="{{ route('admin.manual-delivery.cod-settlements') }}" class="nav-link {{ request()->routeIs('admin.manual-delivery.cod-settlements') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-money-bill-wave"></i>
                                <span class="nav-text">COD Settlements</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Accounting -->
                <div class="nav-section">
                    <div class="nav-section-title">Accounting</div>
                    <div class="nav-item has-submenu">
                        <a href="#" class="nav-link submenu-toggle">
                            <i class="nav-icon fas fa-calculator"></i>
                            <span class="nav-text">Accounting</span>
                            <i class="fas fa-chevron-right submenu-arrow"></i>
                        </a>
                        <div class="submenu">
                            <a href="{{ route('admin.accounting.index') }}" class="nav-link {{ request()->routeIs('admin.accounting.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <span class="nav-text">Overview</span>
                            </a>
                            <a href="{{ route('admin.accounting.sales') }}" class="nav-link {{ request()->routeIs('admin.accounting.sales') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <span class="nav-text">Sales</span>
                            </a>
                            <a href="{{ route('admin.accounting.expenses') }}" class="nav-link {{ request()->routeIs('admin.accounting.expenses') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-receipt"></i>
                                <span class="nav-text">Expenses</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Reports -->
                <div class="nav-section">
                    <div class="nav-section-title">Analytics</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <span class="nav-text">Reports</span>
                        </a>
                    </div>
                </div>
                
                <!-- Site Management -->
                <div class="nav-section">
                    <div class="nav-section-title">Site</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.site-builder.index') }}" class="nav-link {{ request()->routeIs('admin.site-builder.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-paint-brush"></i>
                            <span class="nav-text">Site Builder</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.site-pages.index') }}" class="nav-link {{ request()->routeIs('admin.site-pages.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <span class="nav-text">Pages</span>
                        </a>
                    </div>
                </div>
                
                <!-- User Management -->
                <div class="nav-section">
                    <div class="nav-section-title">Users</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <span class="nav-text">Users</span>
                        </a>
                    </div>
                </div>
                
                <!-- Settings -->
                <div class="nav-section">
                    <div class="nav-section-title">Settings</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <span class="nav-text">Settings</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.subscription.index') }}" class="nav-link {{ request()->routeIs('admin.subscription.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-credit-card"></i>
                            <span class="nav-text">Subscription</span>
                        </a>
                    </div>
                </div>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="btn btn-outline-secondary d-lg-none" id="mobile-menu-toggle" aria-label="Toggle mobile menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <nav class="breadcrumb" aria-label="Breadcrumb">
                        <div class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link" aria-label="Dashboard home">
                                <i class="fas fa-home"></i>
                            </a>
                        </div>
                        @yield('breadcrumb')
                    </nav>
                </div>
        
        <div class="header-center">
            <div class="datetime">
                <div class="date" id="current-date"></div>
                <div class="time" id="current-time"></div>
            </div>
                    
            <div class="live-indicator">
                <div class="live-dot"></div>
                <span class="live-text">Live</span>
            </div>
        </div>
        
                <div class="header-right">
                    <!-- Search -->
                    <div class="search-box">
                        <i class="fas fa-search search-icon" aria-hidden="true"></i>
                        <input type="text" placeholder="Search..." class="search-input" aria-label="Search dashboard">
                    </div>
                    
                    <!-- Notifications -->
                    <div class="notification-icon" id="notification-icon" role="button" tabindex="0" aria-label="Notifications">
                        <i class="fas fa-bell" aria-hidden="true"></i>
                        <span class="notification-badge" aria-label="3 unread notifications">3</span>
                    </div>
                    
                    <!-- Dark Mode Toggle -->
                    <div class="dark-mode-toggle">
                        <button class="btn btn-outline-secondary" id="dark-mode-toggle" title="Toggle Dark Mode" aria-label="Toggle dark mode">
                            <i class="fas fa-moon" id="dark-mode-icon" aria-hidden="true"></i>
                        </button>
                    </div>

                    <!-- User Menu -->
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="User menu">
                            <i class="fas fa-user" aria-hidden="true"></i>
                            <span>{{ auth()->user()->first_name ?? 'Admin' }}</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#" aria-label="View profile">
                                <i class="fas fa-user" aria-hidden="true"></i> Profile
                            </a>
                            <a class="dropdown-item" href="#" aria-label="Settings">
                                <i class="fas fa-cog" aria-hidden="true"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger" aria-label="Logout">
                                    <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Header -->
            @hasSection('page-header')
                <div class="page-header">
                    @yield('page-header')
        </div>
            @else
                <div class="page-header">
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                    @hasSection('page-subtitle')
                        <p class="page-subtitle">@yield('page-subtitle')</p>
                    @endif
    </div>
            @endif

            <!-- Content -->
            <div class="content">
                <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

                <!-- Validation Errors -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <!-- Main Content -->
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Admin JS -->
    <script src="{{ asset('js/admin.js') }}"></script>
    
    @stack('scripts')
    @yield('scripts')
</body>
</html>