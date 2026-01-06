<?php $__env->startSection('title', 'My Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="top-bar">
    <div class="page-title">
        <h1>My Profile</h1>
        <p>Manage your account settings and information</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="<?php echo e($deliveryBoy->profile_photo ? asset('storage/' . $deliveryBoy->profile_photo) : 'https://via.placeholder.com/150'); ?>" 
                     alt="Profile Photo" 
                     class="rounded-circle mb-3" 
                     style="width: 150px; height: 150px; object-fit: cover;">
                <h4><?php echo e($deliveryBoy->name); ?></h4>
                <p class="text-muted"><?php echo e($deliveryBoy->delivery_boy_id); ?></p>
                <span class="badge bg-<?php echo e($deliveryBoy->status === 'active' ? 'success' : 'secondary'); ?> mb-3">
                    <?php echo e(strtoupper(str_replace('_', ' ', $deliveryBoy->status))); ?>

                </span>
                
                <hr>
                
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <h5><?php echo e($deliveryBoy->total_deliveries); ?></h5>
                        <small class="text-muted">Total Deliveries</small>
                    </div>
                    <div class="col-6">
                        <h5><?php echo e($deliveryBoy->rating); ?> / 5.0</h5>
                        <small class="text-muted">Rating</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Performance Stats</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>This Month</span>
                        <strong><?php echo e($performanceStats['this_month_deliveries']); ?> deliveries</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Last Month</span>
                        <strong><?php echo e($performanceStats['last_month_deliveries']); ?> deliveries</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>This Week</span>
                        <strong><?php echo e($performanceStats['this_week_deliveries']); ?> deliveries</strong>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Success Rate</span>
                        <strong><?php echo e($deliveryBoy->total_deliveries > 0 ? round(($deliveryBoy->successful_deliveries / $deliveryBoy->total_deliveries) * 100, 1) : 0); ?>%</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Update Profile Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('delivery-boy.profile.update')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name', $deliveryBoy->name)); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo e(old('phone', $deliveryBoy->phone)); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo e(old('email', $deliveryBoy->email)); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="profile_photo" class="form-label">Profile Photo</label>
                            <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/*">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo e(old('address', $deliveryBoy->address)); ?></textarea>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Update Profile
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Change Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('delivery-boy.password.update')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-key me-2"></i> Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>








<?php echo $__env->make('delivery-boy.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/delivery-boy/profile.blade.php ENDPATH**/ ?>