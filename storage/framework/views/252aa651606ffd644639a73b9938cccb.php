<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Delivery Boy Dashboard'); ?> - E-Manager</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4F46E5;
            --secondary-color: #10B981;
            --danger-color: #EF4444;
            --warning-color: #F59E0B;
            --dark-color: #1F2937;
            --light-gray: #F3F4F6;
            --border-color: #E5E7EB;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #F9FAFB;
            color: #111827;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(180deg, #4F46E5 0%, #6366F1 100%);
            color: white;
            padding: 2rem 0;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 10px;
        }

        .sidebar-brand {
            padding: 0 1.5rem 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            margin-bottom: 2rem;
        }

        .sidebar-brand h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .sidebar-brand p {
            font-size: 0.875rem;
            opacity: 0.8;
            margin: 0;
        }

        .nav-menu {
            list-style: none;
            padding: 0 0.75rem;
        }

        .nav-menu li {
            margin-bottom: 0.25rem;
        }

        .nav-menu a {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            border-radius: 0.75rem;
            transition: all 0.3s;
            font-weight: 500;
        }

        .nav-menu a:hover {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(5px);
        }

        .nav-menu a.active {
            background: rgba(255,255,255,0.2);
            color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .nav-menu a i {
            width: 24px;
            margin-right: 0.875rem;
            font-size: 1.125rem;
        }

        .user-profile {
            padding: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.2);
            margin-top: 2rem;
        }

        .user-profile .profile-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-profile img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .user-profile .info h6 {
            font-size: 0.875rem;
            font-weight: 600;
            margin: 0;
        }

        .user-profile .info p {
            font-size: 0.75rem;
            opacity: 0.8;
            margin: 0;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }

        .top-bar {
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin: 0;
            color: var(--dark-color);
        }

        .page-title p {
            color: #6B7280;
            margin: 0.25rem 0 0;
        }

        .top-bar-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .stats-card .icon {
            width: 56px;
            height: 56px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stats-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .stats-card p {
            color: #6B7280;
            margin: 0.25rem 0 0;
            font-size: 0.875rem;
        }

        /* Badge Styles */
        .badge {
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.75rem;
        }

        /* Table */
        .table-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .table-card .card-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-color);
        }

        .table-card .card-header h5 {
            margin: 0;
            font-weight: 700;
        }

        .table {
            margin: 0;
        }

        .table th {
            font-weight: 600;
            color: #6B7280;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.5rem;
            background: var(--light-gray);
        }

        .table td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
        }

        /* Buttons */
        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            background: #4338CA;
        }

        .btn-success {
            background: var(--secondary-color);
            border: none;
        }

        .btn-success:hover {
            background: #059669;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block !important;
            }
        }

        .mobile-toggle {
            display: none;
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 56px;
            height: 56px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 999;
        }

        /* Notification */
        .alert {
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }
    </style>
    
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h2><i class="fas fa-motorcycle"></i> Delivery</h2>
            <p>Delivery Boy Panel</p>
        </div>
        
        <ul class="nav-menu">
            <li>
                <a href="<?php echo e(route('delivery-boy.dashboard')); ?>" class="<?php echo e(request()->routeIs('delivery-boy.dashboard') ? 'active' : ''); ?>">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('delivery-boy.deliveries')); ?>" class="<?php echo e(request()->routeIs('delivery-boy.deliveries') ? 'active' : ''); ?>">
                    <i class="fas fa-box"></i>
                    <span>My Deliveries</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('delivery-boy.activities')); ?>" class="<?php echo e(request()->routeIs('delivery-boy.activities') ? 'active' : ''); ?>">
                    <i class="fas fa-history"></i>
                    <span>Activities</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('delivery-boy.profile')); ?>" class="<?php echo e(request()->routeIs('delivery-boy.profile') ? 'active' : ''); ?>">
                    <i class="fas fa-user"></i>
                    <span>My Profile</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>

        <div class="user-profile">
            <div class="profile-info">
                <img src="<?php echo e(Auth::guard('delivery_boy')->user()->profile_photo ? asset('storage/' . Auth::guard('delivery_boy')->user()->profile_photo) : 'https://via.placeholder.com/48'); ?>" alt="Profile">
                <div class="info">
                    <h6><?php echo e(Auth::guard('delivery_boy')->user()->name); ?></h6>
                    <p><?php echo e(Auth::guard('delivery_boy')->user()->delivery_boy_id); ?></p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Notifications -->
        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Mobile Toggle -->
    <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Logout Form -->
    <form id="logout-form" action="<?php echo e(route('delivery-boy.logout')); ?>" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>







<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/delivery-boy/layouts/app.blade.php ENDPATH**/ ?>