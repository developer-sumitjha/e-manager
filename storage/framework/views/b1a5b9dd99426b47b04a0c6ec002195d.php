<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Communication Center - Super Admin</title>
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
        .action-card { background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); margin-bottom: 2rem; }
        .action-btn { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 1rem 2rem; border-radius: 12px; text-decoration: none; display: inline-block; transition: all 0.3s; }
        .action-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102,126,234,0.4); color: white; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h3><i class="fas fa-crown"></i> SUPER ADMIN</h3></div>
        <ul class="sidebar-menu">
            <li><a href="<?php echo e(route('super.dashboard')); ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="<?php echo e(route('super.tenants.index')); ?>"><i class="fas fa-store"></i> Admins</a></li>
            <li><a href="<?php echo e(route('super.plans.index')); ?>"><i class="fas fa-layer-group"></i> Plans</a></li>
            <li><a href="<?php echo e(route('super.financial.index')); ?>"><i class="fas fa-chart-pie"></i> Financial</a></li>
            <li><a href="<?php echo e(route('super.system.monitor')); ?>"><i class="fas fa-heartbeat"></i> System Monitor</a></li>
            <li><a href="<?php echo e(route('super.communication.index')); ?>" class="active"><i class="fas fa-bullhorn"></i> Communication</a></li>
            <li><a href="<?php echo e(route('super.analytics')); ?>"><i class="fas fa-chart-line"></i> Analytics</a></li>
            <li><a href="<?php echo e(route('super.security.audit-logs')); ?>"><i class="fas fa-shield-alt"></i> Security</a></li>
            <li><a href="<?php echo e(route('super.settings.general')); ?>"><i class="fas fa-cog"></i> Settings</a></li>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 1.5rem;">
            <li><a href="<?php echo e(route('super.logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <form id="logout-form" action="<?php echo e(route('super.logout')); ?>" method="POST" style="display: none;"><?php echo csrf_field(); ?></form>
    </div>

    <div class="main-content">
        <div class="action-card">
            <h1><i class="fas fa-bullhorn"></i> Communication Center</h1>
            <p class="text-muted">Send announcements, emails, and notifications to vendors</p>
        </div>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-6">
                <div class="action-card">
                    <h4><i class="fas fa-envelope"></i> Send Announcement</h4>
                    <form action="<?php echo e(route('super.communication.announcements.store')); ?>" method="POST" class="mt-3">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Send To</label>
                            <select name="recipient_type" class="form-select">
                                <option value="all">All Admins</option>
                                <option value="active">Active Admins Only</option>
                                <option value="trial">Trial Admins Only</option>
                                <option value="pending">Pending Admins Only</option>
                            </select>
                        </div>
                        <button type="submit" class="action-btn w-100">
                            <i class="fas fa-paper-plane"></i> Send Announcement
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="action-card">
                    <h4><i class="fas fa-mail-bulk"></i> Recent Communications</h4>
                    <div class="mt-3">
                        <?php $__empty_1 = true; $__currentLoopData = $recentCommunications ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div style="border-bottom: 1px solid #e5e7eb; padding: 1rem 0;">
                            <h6><?php echo e($comm->title); ?></h6>
                            <p class="text-muted small mb-0"><?php echo e($comm->created_at->diffForHumans()); ?></p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted text-center py-4">No communications sent yet</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="action-card">
                    <h4><i class="fas fa-life-ring"></i> Support Tickets</h4>
                    <div class="mt-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <h3 class="text-danger"><?php echo e($stats['open_tickets'] ?? 0); ?></h3>
                                <small class="text-muted">Open</small>
                            </div>
                            <div class="col-4">
                                <h3 class="text-warning"><?php echo e($stats['pending_tickets'] ?? 0); ?></h3>
                                <small class="text-muted">Pending</small>
                            </div>
                            <div class="col-4">
                                <h3 class="text-success"><?php echo e($stats['closed_tickets'] ?? 0); ?></h3>
                                <small class="text-muted">Closed</small>
                            </div>
                        </div>
                        <a href="<?php echo e(route('super.communication.tickets')); ?>" class="action-btn w-100 mt-3">
                            <i class="fas fa-ticket-alt"></i> View All Tickets
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/super-admin/communication/index.blade.php ENDPATH**/ ?>