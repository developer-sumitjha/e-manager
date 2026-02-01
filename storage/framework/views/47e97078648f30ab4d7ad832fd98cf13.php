<?php $__env->startSection('title', 'Bulk Create Pathao Shipments'); ?>
<?php $__env->startSection('page-title', 'Bulk Create Pathao Shipments'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .bulk-create-container {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        backdrop-filter: blur(10px);
    }

    .orders-selection {
        margin-bottom: 2rem;
    }

    .selected-count {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: inline-block;
    }

    .excel-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-top: 1rem;
    }

    .excel-table th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        padding: 0.75rem 0.5rem;
        text-align: left;
        border-bottom: 2px solid #dee2e6;
        font-size: 0.85rem;
        white-space: nowrap;
    }

    .excel-table td {
        padding: 0.5rem;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .excel-table tr:hover {
        background-color: #f8f9fa;
    }

    .excel-table tr.selected {
        background-color: #e3f2fd;
    }

    .excel-table input,
    .excel-table select {
        width: 100%;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.375rem 0.5rem;
        font-size: 0.85rem;
        background: white;
    }

    .excel-table input:focus,
    .excel-table select:focus {
        border-color: #22c55e;
        outline: none;
        box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.25);
    }

    .excel-table .order-info {
        font-weight: 600;
        color: #495057;
    }

    .excel-table .order-customer {
        color: #6c757d;
        font-size: 0.8rem;
    }

    .excel-table .address-cell {
        font-size: 0.75rem;
        line-height: 1.2;
        color: #495057;
        word-wrap: break-word;
        max-width: 200px;
    }

    .excel-table .order-amount {
        font-weight: 600;
        color: #28a745;
    }

    .excel-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .table-container {
        overflow-x: auto;
        border-radius: 0.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #6c757d;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="bulk-create-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1>
                            <i class="fas fa-plus-circle"></i>
                            Bulk Create Pathao Shipments
                        </h1>
                        <p class="text-muted">Create multiple Pathao shipments at once</p>
                    </div>
                    <a href="<?php echo e(route('admin.pathao.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <?php if($orders->count() > 0): ?>
                <form action="<?php echo e(route('admin.pathao.bulk-create.store')); ?>" method="POST" id="bulkCreateForm">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Orders Selection -->
                    <div class="orders-selection">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3>
                                <i class="fas fa-list-check"></i>
                                Select Orders
                            </h3>
                            <div class="selected-count" id="selectedCount" style="display: none;">
                                <span id="countText">0</span> orders selected
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                                <i class="fas fa-check-square"></i> Select All
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">
                                <i class="fas fa-square"></i> Deselect All
                            </button>
                        </div>

                        <div class="table-container">
                            <table class="excel-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">
                                            <input type="checkbox" class="excel-checkbox" onchange="toggleAllOrders()">
                                        </th>
                                        <th style="width: 120px;">Order #</th>
                                        <th style="width: 150px;">Customer</th>
                                        <th style="width: 200px;">Address</th>
                                        <th style="width: 120px;">Store *</th>
                                        <th style="width: 120px;">City *</th>
                                        <th style="width: 120px;">Zone *</th>
                                        <th style="width: 120px;">Area</th>
                                        <th style="width: 100px;">Item Type *</th>
                                        <th style="width: 100px;">Delivery *</th>
                                        <th style="width: 100px;">Weight (kg) *</th>
                                        <th style="width: 100px;">COD (₹)</th>
                                        <th style="width: 120px;">Phone *</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr data-order-id="<?php echo e($order->id); ?>">
                                        <td>
                                            <input type="checkbox" 
                                                   name="order_ids[]" 
                                                   value="<?php echo e($order->id); ?>" 
                                                   class="excel-checkbox order-checkbox"
                                                   onchange="updateSelectedCount()">
                                        </td>
                                        <td class="order-info"><?php echo e($order->order_number); ?></td>
                                        <td>
                                            <div class="order-info"><?php echo e($order->user->name ?? 'N/A'); ?></div>
                                            <div class="order-customer"><?php echo e($order->user->email ?? 'N/A'); ?></div>
                                        </td>
                                        <td>
                                            <div class="address-cell">
                                                <?php echo e(Str::limit($order->receiver_full_address ?? $order->shipping_address ?? 'N/A', 60)); ?>

                                            </div>
                                        </td>
                                        <td>
                                            <select name="shipments[<?php echo e($order->id); ?>][store_id]" class="form-select" required>
                                                <option value="">-- Select --</option>
                                                <?php if(isset($stores) && count($stores) > 0): ?>
                                                    <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($store['store_id']); ?>">
                                                            <?php echo e($store['store_name']); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="shipments[<?php echo e($order->id); ?>][recipient_city]" 
                                                    class="form-select city-select" 
                                                    data-order-id="<?php echo e($order->id); ?>"
                                                    required>
                                                <option value="">-- Select --</option>
                                                <?php if(isset($cities) && count($cities) > 0): ?>
                                                    <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($city['city_id']); ?>">
                                                            <?php echo e($city['city_name']); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="shipments[<?php echo e($order->id); ?>][recipient_zone]" 
                                                    class="form-select zone-select" 
                                                    data-order-id="<?php echo e($order->id); ?>"
                                                    required>
                                                <option value="">-- Select Zone --</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="shipments[<?php echo e($order->id); ?>][recipient_area]" 
                                                    class="form-select area-select" 
                                                    data-order-id="<?php echo e($order->id); ?>">
                                                <option value="">-- Optional --</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="shipments[<?php echo e($order->id); ?>][item_type]" class="form-select" required>
                                                <option value="1">Document</option>
                                                <option value="2" selected>Parcel</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="shipments[<?php echo e($order->id); ?>][delivery_type]" class="form-select" required>
                                                <option value="48" selected>Normal</option>
                                                <option value="12">On Demand</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   name="shipments[<?php echo e($order->id); ?>][item_weight]" 
                                                   value="0.5" 
                                                   step="0.1" 
                                                   min="0.5" 
                                                   max="10"
                                                   class="form-control"
                                                   required>
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   name="shipments[<?php echo e($order->id); ?>][amount_to_collect]" 
                                                   value="<?php echo e($order->payment_method === 'cod' || $order->payment_method === 'cash_on_delivery' ? $order->total : 0); ?>" 
                                                   step="0.01" 
                                                   min="0"
                                                   class="form-control">
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   name="shipments[<?php echo e($order->id); ?>][recipient_phone]" 
                                                   value="<?php echo e($order->receiver_phone ?? $order->user->phone ?? ''); ?>" 
                                                   class="form-control"
                                                   required>
                                            <input type="hidden" name="shipments[<?php echo e($order->id); ?>][recipient_name]" value="<?php echo e($order->receiver_name ?? $order->user->name ?? 'Customer'); ?>">
                                            <input type="hidden" name="shipments[<?php echo e($order->id); ?>][recipient_address]" value="<?php echo e($order->receiver_full_address ?? $order->shipping_address ?? ''); ?>">
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                            <i class="fas fa-plus"></i> Create <span id="submitCount">0</span> Shipments
                        </button>
                        <a href="<?php echo e(route('admin.pathao.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No Pending Orders</h3>
                    <p>There are no orders allocated to logistics that need Pathao shipments.</p>
                    <a href="<?php echo e(route('admin.shipments.index')); ?>" class="btn btn-primary">
                        <i class="fas fa-shipping-fast"></i> Allocate Orders to Logistics
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function selectAll() {
    const checkboxes = document.querySelectorAll('.order-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
        checkbox.closest('tr').classList.add('selected');
    });
    updateSelectedCount();
}

function deselectAll() {
    const checkboxes = document.querySelectorAll('.order-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
        checkbox.closest('tr').classList.remove('selected');
    });
    updateSelectedCount();
}

function toggleAllOrders() {
    const masterCheckbox = document.querySelector('thead .excel-checkbox');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    
    orderCheckboxes.forEach(checkbox => {
        checkbox.checked = masterCheckbox.checked;
        checkbox.closest('tr').classList.toggle('selected', masterCheckbox.checked);
    });
    
    updateSelectedCount();
}

function updateSelectedCount() {
    const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
    const count = checkedBoxes.length;
    
    const selectedCount = document.getElementById('selectedCount');
    const countText = document.getElementById('countText');
    const submitBtn = document.getElementById('submitBtn');
    const submitCount = document.getElementById('submitCount');
    
    if (count > 0) {
        selectedCount.style.display = 'inline-block';
        countText.textContent = count;
        submitCount.textContent = count;
        submitBtn.disabled = false;
    } else {
        selectedCount.style.display = 'none';
        submitCount.textContent = '0';
        submitBtn.disabled = true;
    }
}

// City/Zone/Area dynamic loading
document.addEventListener('DOMContentLoaded', function() {
    // Handle city selection for each row
    document.querySelectorAll('.city-select').forEach(citySelect => {
        citySelect.addEventListener('change', function() {
            const cityId = this.value;
            const orderId = this.dataset.orderId;
            const zoneSelect = document.querySelector(`select[name="shipments[${orderId}][recipient_zone]"]`);
            const areaSelect = document.querySelector(`select[name="shipments[${orderId}][recipient_area]"]`);
            
            // Reset zone and area
            zoneSelect.innerHTML = '<option value="">Loading zones...</option>';
            areaSelect.innerHTML = '<option value="">-- Optional --</option>';
            
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
    });

    // Handle zone selection for each row
    document.querySelectorAll('.zone-select').forEach(zoneSelect => {
        zoneSelect.addEventListener('change', function() {
            const zoneId = this.value;
            const orderId = this.dataset.orderId;
            const areaSelect = document.querySelector(`select[name="shipments[${orderId}][recipient_area]"]`);
            
            areaSelect.innerHTML = '<option value="">Loading areas...</option>';
            
            if (!zoneId) {
                areaSelect.innerHTML = '<option value="">-- Optional --</option>';
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
                areaSelect.innerHTML = '<option value="">-- Optional --</option>';
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
});

// Form submission
document.getElementById('bulkCreateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalHtml = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            if (data.created > 0) {
                window.location.href = '<?php echo e(route("admin.pathao.index")); ?>';
            }
        } else {
            alert('Error: ' + (data.message || 'Failed to create shipments'));
            if (data.errors && data.errors.length > 0) {
                console.error('Errors:', data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHtml;
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/pathao/bulk-create.blade.php ENDPATH**/ ?>