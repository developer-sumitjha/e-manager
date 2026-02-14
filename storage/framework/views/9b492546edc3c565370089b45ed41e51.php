<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #3b82f6;
            --sidebar-width: 280px;
        }
        body { background: #f3f4f6; font-family: 'Segoe UI', sans-serif; }
        .sidebar { position: fixed; top: 0; left: 0; width: var(--sidebar-width); height: 100vh; background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; overflow-y: auto; z-index: 1000; }
        .sidebar-header { padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); text-align: center; }
        .sidebar-menu { padding: 1.5rem 0; list-style: none; margin: 0; }
        .sidebar-menu a { display: flex; align-items: center; padding: 1rem 1.5rem; color: white; text-decoration: none; transition: all 0.3s; }
        .sidebar-menu a i { width: 24px; margin-right: 1rem; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: rgba(255, 255, 255, 0.2); }
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }
        .top-nav { background: white; padding: 1rem 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); position: sticky; top: 0; z-index: 100; }
        .content-area { padding: 2rem; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-crown"></i>
            </div>
            <h3>Super Admin</h3>
            <p style="margin: 0; opacity: 0.9; font-size: 0.9rem;"><?php echo e(Auth::guard('super_admin')->user()->name); ?></p>
        </div>
        <ul class="sidebar-menu">
            <li><a href="<?php echo e(route('super.dashboard')); ?>" class="<?php echo e(request()->routeIs('super.dashboard') ? 'active' : ''); ?>"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="<?php echo e(route('super.tenants.index')); ?>" class="<?php echo e(request()->routeIs('super.tenants.*') ? 'active' : ''); ?>"><i class="fas fa-building"></i><span>Tenants</span></a></li>
            <li><a href="<?php echo e(route('super.subscriptions.index')); ?>" class="<?php echo e(request()->routeIs('super.subscriptions.*') ? 'active' : ''); ?>"><i class="fas fa-credit-card"></i><span>Subscriptions</span></a></li>
            <li><a href="<?php echo e(route('super.plans.index')); ?>" class="<?php echo e(request()->routeIs('super.plans.*') ? 'active' : ''); ?>"><i class="fas fa-tags"></i><span>Plans</span></a></li>
            <li><a href="<?php echo e(route('super.payments.index')); ?>" class="<?php echo e(request()->routeIs('super.payments.*') ? 'active' : ''); ?>"><i class="fas fa-money-bill-wave"></i><span>Payments</span></a></li>
            <li><a href="<?php echo e(route('super.analytics')); ?>" class="<?php echo e(request()->routeIs('super.analytics') ? 'active' : ''); ?>"><i class="fas fa-chart-line"></i><span>Analytics</span></a></li>
            <li><a href="<?php echo e(route('super.site-builder.public.index')); ?>" class="<?php echo e(request()->routeIs('super.site-builder.public.*') ? 'active' : ''); ?>"><i class="fas fa-paint-brush"></i><span>Site Builder</span></a></li>
            <li style="margin-top: 2rem; padding: 0 1.5rem;"><form action="<?php echo e(route('super.logout')); ?>" method="POST"><?php echo csrf_field(); ?><button type="submit" class="btn btn-danger w-100"><i class="fas fa-sign-out-alt"></i> Logout</button></form></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="top-nav">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h4 mb-0"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
                <div class="d-flex align-items-center gap-3">
                    <!-- Admin Auth Buttons -->
                    <div class="btn-group" role="group">
                        <a href="<?php echo e(route('vendor.login')); ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-store"></i> Admin Login
                        </a>
                        <a href="<?php echo e(route('vendor.register')); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus"></i> Admin Register
                        </a>
                    </div>
                    
                    <!-- Super Admin Info -->
                    <div class="d-flex align-items-center">
                        <strong><?php echo e(Auth::guard('super_admin')->user()->name); ?></strong> 
                        <span class="badge bg-primary ms-2">Super Admin</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-area">
            <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; ?>
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/super-admin/layout.blade.php ENDPATH**/ ?>