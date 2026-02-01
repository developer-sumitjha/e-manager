<?php $__env->startSection('settings-title', 'Shipping Settings'); ?>
<?php $__env->startSection('settings-description', 'Configure shipping methods, costs, and delivery options'); ?>
<?php $__env->startSection('settings-icon', 'shipping-fast'); ?>
<?php $__env->startSection('settings-group', 'general'); ?>

<?php $__env->startSection('settings-content'); ?>
<form action="<?php echo e(route('admin.settings.update')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="group" value="shipping">

    <div class="settings-form-grid">
        <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="form-group">
            <label for="<?php echo e($key); ?>" class="form-label">
                <?php echo e($setting['label']); ?>

                <?php if(in_array($setting['type'], ['text', 'email', 'number'])): ?>
                    <span class="text-danger">*</span>
                <?php endif; ?>
            </label>
            
            <?php if($setting['description']): ?>
            <p class="form-description"><?php echo e($setting['description']); ?></p>
            <?php endif; ?>

            <?php if($setting['type'] == 'text' || $setting['type'] == 'email' || $setting['type'] == 'number'): ?>
                <input type="<?php echo e($setting['type']); ?>" 
                       name="<?php echo e($key); ?>" 
                       id="<?php echo e($key); ?>" 
                       class="form-control" 
                       value="<?php echo e($setting['value']); ?>"
                       <?php echo e(in_array($setting['type'], ['text', 'email']) ? 'required' : ''); ?>>
            
            <?php elseif($setting['type'] == 'password'): ?>
                <input type="password" 
                       name="<?php echo e($key); ?>" 
                       id="<?php echo e($key); ?>" 
                       class="form-control" 
                       value="<?php echo e($setting['value']); ?>"
                       placeholder="••••••••">
            
            <?php elseif($setting['type'] == 'textarea'): ?>
                <textarea name="<?php echo e($key); ?>" 
                          id="<?php echo e($key); ?>" 
                          class="form-control" 
                          rows="4"><?php echo e($setting['value']); ?></textarea>
            
            <?php elseif($setting['type'] == 'select'): ?>
                <select name="<?php echo e($key); ?>" 
                        id="<?php echo e($key); ?>" 
                        class="form-select">
                    <?php if($setting['options']): ?>
                        <?php $__currentLoopData = $setting['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($option); ?>" <?php echo e($setting['value'] == $option ? 'selected' : ''); ?>>
                                <?php echo e($option); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
            
            <?php elseif($setting['type'] == 'checkbox'): ?>
                <div class="form-check form-switch">
                    <input type="checkbox" 
                           name="<?php echo e($key); ?>" 
                           id="<?php echo e($key); ?>" 
                           class="form-check-input" 
                           value="1"
                           <?php echo e($setting['value'] ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="<?php echo e($key); ?>">
                        Enable
                    </label>
                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="form-actions">
        <a href="<?php echo e(route('admin.settings.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Settings
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Save Changes
        </button>
    </div>
</form>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.settings.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/settings/shipping.blade.php ENDPATH**/ ?>