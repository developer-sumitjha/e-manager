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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    @stack('styles')
    @yield('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" onerror="this.style.display='none'">
                    <span class="logo-text">E-Manager</span>
                </a>
                <button class="sidebar-toggle" id="sidebar-toggle">
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
                    <button class="btn btn-outline-secondary d-lg-none" id="mobile-menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <nav class="breadcrumb">
                        <div class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">
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
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Search..." class="search-input">
                    </div>
                    
                    <!-- Notifications -->
                    <div class="notification-icon" id="notification-icon">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i>
                            <span>{{ auth()->user()->first_name ?? 'Admin' }}</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user"></i> Profile
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt"></i> Logout
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