<?php $__env->startSection('title', 'Pathao Settings'); ?>
<?php $__env->startSection('page-title', 'Pathao Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Pathao Parcel Settings</h1>
            <p class="text-muted">Configure your Pathao API credentials and default settings</p>
        </div>
        <a href="<?php echo e(route('admin.pathao.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.pathao.update-settings')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        
        <!-- API Credentials -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-key"></i> OAuth 2.0 Credentials</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note:</strong> Pathao uses OAuth 2.0 authentication. You need to provide Client ID, Client Secret, Username, and Password to generate access tokens.
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Client ID *</label>
                        <input type="text" name="client_id" class="form-control <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('client_id', $settings->client_id)); ?>" 
                               placeholder="Enter your Pathao Client ID">
                        <small class="text-muted">Get your Client ID from Pathao Merchant Panel</small>
                        <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Client Secret *</label>
                        <input type="password" name="client_secret" class="form-control <?php $__errorArgs = ['client_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('client_secret', $settings->client_secret)); ?>" 
                               placeholder="Enter your Pathao Client Secret">
                        <small class="text-muted">Keep this secure and private</small>
                        <?php $__errorArgs = ['client_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Username (Email) *</label>
                        <input type="email" name="username" class="form-control <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('username', $settings->username)); ?>" 
                               placeholder="your.email@pathao.com">
                        <small class="text-muted">Your Pathao merchant account email</small>
                        <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('password', $settings->password)); ?>" 
                               placeholder="Enter your Pathao account password">
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">API URL *</label>
                        <input type="url" name="api_url" class="form-control <?php $__errorArgs = ['api_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('api_url', $settings->api_url)); ?>" 
                               placeholder="https://courier-api-sandbox.pathao.com">
                        <small class="text-muted">Sandbox: https://courier-api-sandbox.pathao.com | Production: https://api-hermes.pathao.com</small>
                        <?php $__errorArgs = ['api_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <?php if($settings->access_token): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <strong>Access Token Active:</strong> Token expires at <?php echo e($settings->token_expires_at ? $settings->token_expires_at->format('Y-m-d H:i:s') : 'N/A'); ?>

                </div>
                <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>No Access Token!</strong> Please enter your credentials and save settings. The system will automatically generate an access token.
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Store Selection -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-store"></i> Store Selection</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Default Store *</label>
                        <select name="store_id" class="form-select <?php $__errorArgs = ['store_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">-- Select Store --</option>
                            <?php if(isset($stores) && count($stores) > 0): ?>
                                <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($store['store_id']); ?>" 
                                            <?php echo e(old('store_id', $settings->store_id) == $store['store_id'] ? 'selected' : ''); ?>>
                                        <?php echo e($store['store_name']); ?> (ID: <?php echo e($store['store_id']); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-muted">Stores will be loaded after you configure credentials and save settings</small>
                        <?php $__errorArgs = ['store_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Default Shipment Settings -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-cog"></i> Default Shipment Settings</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Item Type *</label>
                        <select name="default_item_type" class="form-select">
                            <option value="1" <?php echo e($settings->default_item_type == 1 ? 'selected' : ''); ?>>Document</option>
                            <option value="2" <?php echo e($settings->default_item_type == 2 ? 'selected' : ''); ?>>Parcel</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Delivery Type *</label>
                        <select name="default_delivery_type" class="form-select">
                            <option value="48" <?php echo e($settings->default_delivery_type == 48 ? 'selected' : ''); ?>>Normal Delivery (48 hours)</option>
                            <option value="12" <?php echo e($settings->default_delivery_type == 12 ? 'selected' : ''); ?>>On Demand Delivery (12 hours)</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Default Weight (kg) *</label>
                        <input type="number" name="default_item_weight" step="0.1" min="0.5" max="10" 
                               class="form-control" value="<?php echo e(old('default_item_weight', $settings->default_item_weight)); ?>">
                        <small class="text-muted">Minimum: 0.5 kg, Maximum: 10 kg</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pickup Information -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Pickup Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="pickup_contact_name" class="form-control" 
                               value="<?php echo e(old('pickup_contact_name', $settings->pickup_contact_name)); ?>" 
                               placeholder="Contact person name">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="pickup_contact_number" class="form-control" 
                               value="<?php echo e(old('pickup_contact_number', $settings->pickup_contact_number)); ?>" 
                               placeholder="Phone number">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Pickup Address</label>
                        <textarea name="pickup_address" class="form-control" rows="3" 
                                  placeholder="Full pickup address"><?php echo e(old('pickup_address', $settings->pickup_address)); ?></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">City ID</label>
                        <input type="number" name="pickup_city_id" class="form-control" 
                               value="<?php echo e(old('pickup_city_id', $settings->pickup_city_id)); ?>" 
                               placeholder="City ID">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Zone ID</label>
                        <input type="number" name="pickup_zone_id" class="form-control" 
                               value="<?php echo e(old('pickup_zone_id', $settings->pickup_zone_id)); ?>" 
                               placeholder="Zone ID">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Area ID</label>
                        <input type="number" name="pickup_area_id" class="form-control" 
                               value="<?php echo e(old('pickup_area_id', $settings->pickup_area_id)); ?>" 
                               placeholder="Area ID">
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Settings -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-sliders-h"></i> Advanced Settings</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="auto_create_shipment" value="1" 
                                   <?php echo e($settings->auto_create_shipment ? 'checked' : ''); ?>>
                            <label class="form-check-label">
                                <strong>Auto Create Shipment</strong><br>
                                <small class="text-muted">Automatically create Pathao shipment when order is allocated to logistics</small>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="send_notifications" value="1" 
                                   <?php echo e($settings->send_notifications ? 'checked' : ''); ?>>
                            <label class="form-check-label">
                                <strong>Send Notifications</strong><br>
                                <small class="text-muted">Receive email/SMS notifications for shipment updates</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="card">
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Test Connection -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-plug"></i> Test API Connection</h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">Test your API connection with the current settings. Make sure to save your settings first if you've made changes.</p>
            <button type="button" class="btn btn-info" id="testConnectionBtn" onclick="testConnection()">
                <i class="fas fa-check-circle"></i> Test Connection
            </button>
            <div id="connectionResult" class="mt-3"></div>
        </div>
    </div>
</div>

<script>
function testConnection() {
    const resultDiv = document.getElementById('connectionResult');
    const testBtn = document.getElementById('testConnectionBtn');
    const originalBtnHtml = testBtn.innerHTML;
    
    // Disable button and show loading
    testBtn.disabled = true;
    testBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';
    resultDiv.innerHTML = '<div class="alert alert-info"><i class="fas fa-spinner fa-spin"></i> Testing connection...</div>';
    
    fetch('<?php echo e(route("admin.pathao.test-connection")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => {
        return response.json().then(data => {
            if (!response.ok) {
                throw new Error(data.message || 'Connection failed');
            }
            return data;
        }).catch(error => {
            if (error instanceof SyntaxError) {
                return response.text().then(text => {
                    throw new Error('API returned non-JSON response. Status: ' + response.status + '. Content: ' + text.substring(0, 200));
                });
            }
            throw error;
        });
    })
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> <strong>Connection Successful!</strong> ' + (data.message || 'API connection is working.') + '</div>';
        } else {
            resultDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-times-circle"></i> <strong>Connection Failed!</strong> ' + (data.message || 'Please check your credentials.') + '</div>';
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        resultDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-times-circle"></i> <strong>Error!</strong> ' + error.message + '</div>';
    })
    .finally(() => {
        // Re-enable button
        testBtn.disabled = false;
        testBtn.innerHTML = originalBtnHtml;
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/pathao/settings-form.blade.php ENDPATH**/ ?>