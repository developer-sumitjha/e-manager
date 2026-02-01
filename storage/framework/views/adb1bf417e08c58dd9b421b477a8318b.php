<?php $__env->startSection('title', 'Create Pathao Shipment'); ?>
<?php $__env->startSection('page-title', 'Create Pathao Shipment'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Create Pathao Shipment</h1>
            <p class="text-muted">Create a new shipment in Pathao system</p>
        </div>
        <a href="<?php echo e(route('admin.pathao.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.pathao.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        
        <!-- Order Selection -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Select Order</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Order *</label>
                    <select name="order_id" id="order-select" class="form-select <?php $__errorArgs = ['order_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">-- Select Order --</option>
                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($order->id); ?>" 
                                    data-receiver-name="<?php echo e($order->receiver_name ?? $order->user->name ?? 'Customer'); ?>"
                                    data-receiver-phone="<?php echo e($order->receiver_phone ?? $order->user->phone ?? ''); ?>"
                                    data-receiver-address="<?php echo e($order->receiver_full_address ?? $order->shipping_address ?? ''); ?>"
                                    data-total="<?php echo e($order->total); ?>"
                                    data-payment-method="<?php echo e($order->payment_method ?? ''); ?>"
                                    data-instructions="<?php echo e($order->delivery_instructions ?? $order->notes ?? ''); ?>"
                                    <?php echo e(old('order_id') == $order->id ? 'selected' : ''); ?>>
                                <?php echo e($order->order_number); ?> - <?php echo e($order->user->name ?? 'Customer'); ?> (₹<?php echo e(number_format($order->total, 2)); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['order_id'];
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

        <!-- Store Selection -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-store"></i> Store Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Store *</label>
                    <select name="store_id" class="form-select <?php $__errorArgs = ['store_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">-- Select Store --</option>
                        <?php if(isset($stores) && count($stores) > 0): ?>
                            <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($store['store_id']); ?>" <?php echo e(old('store_id') == $store['store_id'] ? 'selected' : ''); ?>>
                                    <?php echo e($store['store_name']); ?> (ID: <?php echo e($store['store_id']); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-muted">Configure stores in Pathao Settings</small>
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

        <!-- Recipient Information -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-user"></i> Recipient Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Recipient Name *</label>
                        <input type="text" name="recipient_name" id="recipient-name" class="form-control <?php $__errorArgs = ['recipient_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('recipient_name')); ?>" required>
                        <?php $__errorArgs = ['recipient_name'];
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
                        <label class="form-label">Recipient Phone *</label>
                        <input type="text" name="recipient_phone" id="recipient-phone" class="form-control <?php $__errorArgs = ['recipient_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('recipient_phone')); ?>" required>
                        <?php $__errorArgs = ['recipient_phone'];
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
                        <label class="form-label">Recipient Address *</label>
                        <textarea name="recipient_address" id="recipient-address" class="form-control <?php $__errorArgs = ['recipient_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  rows="3" required><?php echo e(old('recipient_address')); ?></textarea>
                        <?php $__errorArgs = ['recipient_address'];
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

        <!-- Location Selection -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Delivery Location</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">City *</label>
                        <select name="recipient_city" id="recipient_city" class="form-select <?php $__errorArgs = ['recipient_city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">-- Select City --</option>
                            <?php if(isset($cities) && count($cities) > 0): ?>
                                <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($city['city_id']); ?>" <?php echo e(old('recipient_city') == $city['city_id'] ? 'selected' : ''); ?>>
                                        <?php echo e($city['city_name']); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                        <?php $__errorArgs = ['recipient_city'];
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
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Zone *</label>
                        <select name="recipient_zone" id="recipient_zone" class="form-select <?php $__errorArgs = ['recipient_zone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">-- Select Zone --</option>
                        </select>
                        <?php $__errorArgs = ['recipient_zone'];
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
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Area</label>
                        <select name="recipient_area" id="recipient_area" class="form-select <?php $__errorArgs = ['recipient_area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">-- Select Area (Optional) --</option>
                        </select>
                        <?php $__errorArgs = ['recipient_area'];
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

        <!-- Shipment Details -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-box"></i> Shipment Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Item Type *</label>
                        <select name="item_type" class="form-select <?php $__errorArgs = ['item_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="1" <?php echo e(old('item_type') == '1' ? 'selected' : ''); ?>>Document</option>
                            <option value="2" <?php echo e(old('item_type', '2') == '2' ? 'selected' : ''); ?>>Parcel</option>
                        </select>
                        <?php $__errorArgs = ['item_type'];
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
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Delivery Type *</label>
                        <select name="delivery_type" class="form-select <?php $__errorArgs = ['delivery_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="48" <?php echo e(old('delivery_type', '48') == '48' ? 'selected' : ''); ?>>Normal Delivery (48 hours)</option>
                            <option value="12" <?php echo e(old('delivery_type') == '12' ? 'selected' : ''); ?>>On Demand Delivery (12 hours)</option>
                        </select>
                        <?php $__errorArgs = ['delivery_type'];
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
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Item Weight (kg) *</label>
                        <input type="number" name="item_weight" step="0.1" min="0.5" max="10" 
                               class="form-control <?php $__errorArgs = ['item_weight'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('item_weight', '0.5')); ?>" required>
                        <small class="text-muted">Min: 0.5 kg, Max: 10 kg</small>
                        <?php $__errorArgs = ['item_weight'];
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
                        <label class="form-label">COD Amount (₹)</label>
                        <input type="number" name="amount_to_collect" id="cod-amount" step="0.01" min="0" 
                               class="form-control <?php $__errorArgs = ['amount_to_collect'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('amount_to_collect', '0')); ?>">
                        <?php $__errorArgs = ['amount_to_collect'];
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
                        <label class="form-label">Item Description</label>
                        <input type="text" name="item_description" id="item-description" class="form-control" 
                               value="<?php echo e(old('item_description')); ?>" 
                               placeholder="Brief description of items">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Special Instructions</label>
                        <textarea name="special_instruction" id="special-instruction" class="form-control" rows="3" 
                                  placeholder="Any special delivery instructions"><?php echo e(old('special_instruction')); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="card">
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Create Shipment
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Order selection auto-fill
    const orderSelect = document.getElementById('order-select');
    const recipientName = document.getElementById('recipient-name');
    const recipientPhone = document.getElementById('recipient-phone');
    const recipientAddress = document.getElementById('recipient-address');
    const codAmount = document.getElementById('cod-amount');
    const specialInstruction = document.getElementById('special-instruction');
    const itemDescription = document.getElementById('item-description');

    // Auto-fill recipient details when order is selected
    if (orderSelect) {
        orderSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value) {
                // Auto-fill recipient information
                if (recipientName) {
                    recipientName.value = selectedOption.dataset.receiverName || '';
                }
                if (recipientPhone) {
                    recipientPhone.value = selectedOption.dataset.receiverPhone || '';
                }
                if (recipientAddress) {
                    recipientAddress.value = selectedOption.dataset.receiverAddress || '';
                }
                
                // Calculate COD amount based on payment method
                const paymentMethod = selectedOption.dataset.paymentMethod || '';
                const total = parseFloat(selectedOption.dataset.total || '0');
                if (codAmount) {
                    codAmount.value = (paymentMethod === 'cod' || paymentMethod === 'cash_on_delivery') ? total.toFixed(2) : '0';
                }
                
                // Auto-fill special instructions
                if (specialInstruction && selectedOption.dataset.instructions) {
                    specialInstruction.value = selectedOption.dataset.instructions;
                }
                
                // Auto-fill item description (can be based on order items count)
                if (itemDescription && !itemDescription.value) {
                    // You can enhance this to show actual item names if needed
                    itemDescription.value = 'Order items';
                }
            } else {
                // Clear all fields when no order is selected
                if (recipientName) recipientName.value = '';
                if (recipientPhone) recipientPhone.value = '';
                if (recipientAddress) recipientAddress.value = '';
                if (codAmount) codAmount.value = '0';
                if (specialInstruction) specialInstruction.value = '';
            }
        });
    }

    // City/Zone/Area selection
    const citySelect = document.getElementById('recipient_city');
    const zoneSelect = document.getElementById('recipient_zone');
    const areaSelect = document.getElementById('recipient_area');

    // Load zones when city is selected
    citySelect.addEventListener('change', function() {
        const cityId = this.value;
        zoneSelect.innerHTML = '<option value="">Loading zones...</option>';
        areaSelect.innerHTML = '<option value="">-- Select Area (Optional) --</option>';
        
        if (!cityId) {
            zoneSelect.innerHTML = '<option value="">-- Select Zone --</option>';
            return;
        }

        fetch('<?php echo e(route("admin.pathao.zones")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({ city_id: cityId })
        })
        .then(response => response.json())
        .then(data => {
            zoneSelect.innerHTML = '<option value="">-- Select Zone --</option>';
            if (data.success && data.data) {
                data.data.forEach(zone => {
                    const option = document.createElement('option');
                    option.value = zone.zone_id;
                    option.textContent = zone.zone_name;
                    zoneSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading zones:', error);
            zoneSelect.innerHTML = '<option value="">Error loading zones</option>';
        });
    });

    // Load areas when zone is selected
    zoneSelect.addEventListener('change', function() {
        const zoneId = this.value;
        areaSelect.innerHTML = '<option value="">Loading areas...</option>';
        
        if (!zoneId) {
            areaSelect.innerHTML = '<option value="">-- Select Area (Optional) --</option>';
            return;
        }

        fetch('<?php echo e(route("admin.pathao.areas")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({ zone_id: zoneId })
        })
        .then(response => response.json())
        .then(data => {
            areaSelect.innerHTML = '<option value="">-- Select Area (Optional) --</option>';
            if (data.success && data.data) {
                data.data.forEach(area => {
                    const option = document.createElement('option');
                    option.value = area.area_id;
                    option.textContent = area.area_name;
                    areaSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading areas:', error);
            areaSelect.innerHTML = '<option value="">Error loading areas</option>';
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/pathao/create.blade.php ENDPATH**/ ?>