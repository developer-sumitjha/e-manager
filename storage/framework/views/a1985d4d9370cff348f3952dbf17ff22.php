<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - Super Admin</title>
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
        .settings-card { background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); margin-bottom: 2rem; }
        .settings-tabs { display: flex; gap: 1rem; border-bottom: 2px solid #e5e7eb; margin-bottom: 2rem; }
        .tab-link { padding: 1rem 1.5rem; color: #64748b; text-decoration: none; border-bottom: 3px solid transparent; transition: all 0.3s; }
        .tab-link.active { color: var(--primary); border-bottom-color: var(--primary); }
        .save-btn { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 0.75rem 2rem; border-radius: 12px; border: none; transition: all 0.3s; }
        .save-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102,126,234,0.4); }
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
            <li><a href="<?php echo e(route('super.communication.index')); ?>"><i class="fas fa-bullhorn"></i> Communication</a></li>
            <li><a href="<?php echo e(route('super.analytics')); ?>"><i class="fas fa-chart-line"></i> Analytics</a></li>
            <li><a href="<?php echo e(route('super.security.audit-logs')); ?>"><i class="fas fa-shield-alt"></i> Security</a></li>
            <li><a href="<?php echo e(route('super.settings.general')); ?>" class="active"><i class="fas fa-cog"></i> Settings</a></li>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 1.5rem;">
            <li><a href="<?php echo e(route('super.logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <form id="logout-form" action="<?php echo e(route('super.logout')); ?>" method="POST" style="display: none;"><?php echo csrf_field(); ?></form>
    </div>

    <div class="main-content">
        <div class="settings-card">
            <h1><i class="fas fa-cog"></i> Platform Settings</h1>
            <p class="text-muted mb-0">Configure platform-wide settings and preferences</p>
        </div>

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="settings-tabs">
            <a href="<?php echo e(route('super.settings.general')); ?>" class="tab-link active">
                <i class="fas fa-sliders-h"></i> General
            </a>
            <a href="<?php echo e(route('super.settings.email')); ?>" class="tab-link">
                <i class="fas fa-envelope"></i> Email
            </a>
            <a href="<?php echo e(route('super.settings.payments')); ?>" class="tab-link">
                <i class="fas fa-credit-card"></i> Payments
            </a>
            <a href="<?php echo e(route('super.settings.api')); ?>" class="tab-link">
                <i class="fas fa-code"></i> API
            </a>
        </div>

        <div class="settings-card">
            <form action="<?php echo e(route('super.settings.save')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="category" value="general">

                <h4><i class="fas fa-info-circle"></i> General Settings</h4>
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Platform Name</label>
                        <input type="text" name="platform_name" class="form-control" value="<?php echo e($settings['platform_name'] ?? 'E-Manager'); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Support Email</label>
                        <input type="email" name="support_email" class="form-control" value="<?php echo e($settings['support_email'] ?? 'support@e-manager.com'); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Currency</label>
                        <select name="currency" class="form-select">
                            <option value="NPR">Nepalese Rupee (NPR)</option>
                            <option value="USD">US Dollar (USD)</option>
                            <option value="INR">Indian Rupee (INR)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Timezone</label>
                        <select name="timezone" class="form-select">
                            <option value="Asia/Kathmandu">Asia/Kathmandu</option>
                            <option value="Asia/Kolkata">Asia/Kolkata</option>
                            <option value="UTC">UTC</option>
                        </select>
                    </div>
                </div>

                <h4 class="mt-4"><i class="fas fa-user-plus"></i> Registration Settings</h4>
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-check-label">
                            <input type="checkbox" name="auto_approve_vendors" class="form-check-input" <?php echo e(($settings['auto_approve_vendors'] ?? false) ? 'checked' : ''); ?>>
                            Auto-approve new vendors
                        </label>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-check-label">
                            <input type="checkbox" name="require_email_verification" class="form-check-input" <?php echo e(($settings['require_email_verification'] ?? true) ? 'checked' : ''); ?>>
                            Require email verification
                        </label>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Default Trial Period (days)</label>
                        <input type="number" name="default_trial_days" class="form-control" value="<?php echo e($settings['default_trial_days'] ?? 14); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Default Plan</label>
                        <select name="default_plan_id" class="form-select">
                            <?php $__currentLoopData = $plans ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($plan->id); ?>"><?php echo e($plan->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <h4 class="mt-4"><i class="fas fa-tools"></i> Maintenance</h4>
                <div class="mb-3">
                    <label class="form-check-label">
                        <input type="checkbox" name="maintenance_mode" class="form-check-input" <?php echo e(($settings['maintenance_mode'] ?? false) ? 'checked' : ''); ?>>
                        Enable maintenance mode
                    </label>
                    <small class="text-muted d-block">When enabled, only super admins can access the platform</small>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="save-btn">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/super-admin/settings/general.blade.php ENDPATH**/ ?>