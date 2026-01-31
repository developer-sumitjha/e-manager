<?php $__env->startSection('title', 'Delivery Boys Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Delivery Boys Management</h1>
            <p class="text-muted">Manage delivery personnel and their assignments</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDeliveryBoyModal">
            <i class="fas fa-plus"></i> Add Delivery Boy
        </button>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" id="searchInput" class="form-control" placeholder="Search delivery boys...">
            </div>
        </div>
        <div class="col-md-3">
            <select id="statusFilter" class="form-select">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="on_duty">On Duty</option>
                <option value="off_duty">Off Duty</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="zoneFilter" class="form-select">
                <option value="">All Zones</option>
                <option value="north">North Zone</option>
                <option value="south">South Zone</option>
                <option value="east">East Zone</option>
                <option value="west">West Zone</option>
                <option value="central">Central Zone</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                <i class="fas fa-refresh"></i> Reset
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Delivery Boys</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalDeliveryBoys">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Boys</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeDeliveryBoys">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">On Duty</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="onDutyBoys">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-motorcycle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Avg Rating</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="avgRating">0.0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Boys Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Delivery Boys</h6>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="exportDeliveryBoys()">
                    <i class="fas fa-download"></i> Export
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="refreshTable()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="deliveryBoysTable">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Zone</th>
                            <th>Status</th>
                            <th>Rating</th>
                            <th>Deliveries</th>
                            <th>Join Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="deliveryBoysTableBody">
                        <?php $__empty_1 = true; $__currentLoopData = $deliveryBoys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliveryBoy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><input type="checkbox" class="form-check-input delivery-boy-checkbox" value="<?php echo e($deliveryBoy->id); ?>"></td>
                            <td><?php echo e($deliveryBoy->delivery_boy_id); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                        <?php echo e(strtoupper(substr($deliveryBoy->name, 0, 1))); ?>

                                    </div>
                                    <div>
                                        <div class="fw-bold"><?php echo e($deliveryBoy->name); ?></div>
                                        <small class="text-muted">ID: <?php echo e($deliveryBoy->delivery_boy_id); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($deliveryBoy->phone); ?></td>
                            <td><?php echo e($deliveryBoy->email ?? 'N/A'); ?></td>
                            <td>
                                <?php
                                    $zoneBadge = match($deliveryBoy->zone) {
                                        'north' => 'bg-primary',
                                        'south' => 'bg-success',
                                        'east' => 'bg-warning',
                                        'west' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                ?>
                                <span class="badge <?php echo e($zoneBadge); ?>"><?php echo e(ucfirst($deliveryBoy->zone)); ?> Zone</span>
                            </td>
                            <td>
                                <?php
                                    $statusBadge = match($deliveryBoy->status) {
                                        'active' => 'bg-success',
                                        'on_duty' => 'bg-warning',
                                        'inactive' => 'bg-secondary',
                                        default => 'bg-secondary'
                                    };
                                ?>
                                <span class="badge <?php echo e($statusBadge); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $deliveryBoy->status))); ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php
                                        $rating = $deliveryBoy->rating ?? 0;
                                        $fullStars = floor($rating);
                                        $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                    ?>
                                    <span class="text-warning">
                                        <?php for($i = 0; $i < $fullStars; $i++): ?>★<?php endfor; ?>
                                        <?php if($hasHalfStar): ?>★<?php endif; ?>
                                        <?php for($i = 0; $i < $emptyStars; $i++): ?>☆<?php endfor; ?>
                                    </span>
                                    <small class="text-muted ms-1"><?php echo e(number_format($rating, 1)); ?></small>
                                </div>
                            </td>
                            <td><span class="badge bg-info"><?php echo e($deliveryBoy->total_deliveries ?? 0); ?></span></td>
                            <td><?php echo e($deliveryBoy->created_at->format('Y-m-d')); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="viewDeliveryBoy('<?php echo e($deliveryBoy->delivery_boy_id); ?>')" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" onclick="editDeliveryBoy('<?php echo e($deliveryBoy->delivery_boy_id); ?>')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-success" onclick="assignDelivery('<?php echo e($deliveryBoy->delivery_boy_id); ?>')" title="Assign Delivery">
                                        <i class="fas fa-truck"></i>
                                    </button>
                                    <?php if($deliveryBoy->status == 'active' || $deliveryBoy->status == 'on_duty'): ?>
                                    <button class="btn btn-outline-danger" onclick="deactivateDeliveryBoy('<?php echo e($deliveryBoy->delivery_boy_id); ?>')" title="Deactivate">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    <?php else: ?>
                                    <button class="btn btn-outline-success" onclick="activateDeliveryBoy('<?php echo e($deliveryBoy->delivery_boy_id); ?>')" title="Activate">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-user-slash fa-3x mb-3"></i>
                                    <h5>No Delivery Boys Found</h5>
                                    <p>Click "Add Delivery Boy" button to create a new delivery boy.</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Bulk Actions -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="bulkAssignDeliveries()">
                            <i class="fas fa-truck"></i> Assign Deliveries
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="bulkActivate()">
                            <i class="fas fa-check"></i> Activate Selected
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="bulkDeactivate()">
                            <i class="fas fa-ban"></i> Deactivate Selected
                        </button>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">Showing 1-3 of 3 delivery boys</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Delivery Boy Modal -->
<div class="modal fade" id="addDeliveryBoyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Delivery Boy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addDeliveryBoyForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="password" name="password" required minlength="6" placeholder="Minimum 6 characters">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="zone" class="form-label">Assigned Zone *</label>
                                <select class="form-select" id="zone" name="zone" required>
                                    <option value="">Select Zone</option>
                                    <option value="north">North Zone</option>
                                    <option value="south">South Zone</option>
                                    <option value="east">East Zone</option>
                                    <option value="west">West Zone</option>
                                    <option value="central">Central Zone</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cnic" class="form-label">CNIC Number</label>
                                <input type="text" class="form-control" id="cnic" name="cnic" placeholder="12345-1234567-1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="license" class="form-label">Driving License</label>
                                <input type="text" class="form-control" id="license" name="license">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vehicle_type" class="form-label">Vehicle Type</label>
                                <select class="form-select" id="vehicle_type" name="vehicle_type">
                                    <option value="">Select Vehicle</option>
                                    <option value="motorcycle">Motorcycle</option>
                                    <option value="bicycle">Bicycle</option>
                                    <option value="car">Car</option>
                                    <option value="van">Van</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vehicle_number" class="form-label">Vehicle Number</label>
                                <input type="text" class="form-control" id="vehicle_number" name="vehicle_number">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Delivery Boy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Delivery Boy Modal -->
<div class="modal fade" id="editDeliveryBoyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Delivery Boy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDeliveryBoyForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="edit_delivery_boy_id" name="delivery_boy_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="edit_phone" name="phone" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="edit_email" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_password" class="form-label">Password (Leave blank to keep current)</label>
                                <input type="password" class="form-control" id="edit_password" name="password" minlength="6" placeholder="Leave blank to keep current password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_zone" class="form-label">Assigned Zone *</label>
                                <select class="form-select" id="edit_zone" name="zone" required>
                                    <option value="">Select Zone</option>
                                    <option value="north">North Zone</option>
                                    <option value="south">South Zone</option>
                                    <option value="east">East Zone</option>
                                    <option value="west">West Zone</option>
                                    <option value="central">Central Zone</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_vehicle_type" class="form-label">Vehicle Type</label>
                                <select class="form-select" id="edit_vehicle_type" name="vehicle_type">
                                    <option value="">Select Type</option>
                                    <option value="motorcycle">Motorcycle</option>
                                    <option value="bicycle">Bicycle</option>
                                    <option value="car">Car</option>
                                    <option value="van">Van</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_license_number" class="form-label">License Number</label>
                                <input type="text" class="form-control" id="edit_license_number" name="license_number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_vehicle_number" class="form-label">Vehicle Number</label>
                                <input type="text" class="form-control" id="edit_vehicle_number" name="vehicle_number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_cnic" class="form-label">CNIC/ID Number</label>
                                <input type="text" class="form-control" id="edit_cnic" name="cnic">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="edit_address" class="form-label">Address</label>
                                <textarea class="form-control" id="edit_address" name="address" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Delivery Boy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Delivery Boy Modal -->
<div class="modal fade" id="viewDeliveryBoyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delivery Boy Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="deliveryBoyDetails">
                <!-- Details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editDeliveryBoyFromModal()">Edit</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded - Initializing delivery boys page');
    
    // Initialize statistics
    updateStatistics();
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            filterTable(searchTerm);
        });
    }
    
    // Filter functionality
    const statusFilter = document.getElementById('statusFilter');
    const zoneFilter = document.getElementById('zoneFilter');
    
    if (statusFilter) {
        statusFilter.addEventListener('change', applyFilters);
    }
    if (zoneFilter) {
        zoneFilter.addEventListener('change', applyFilters);
    }
    
    // Select all functionality
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.delivery-boy-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});

function updateStatistics() {
    // Statistics are now server-side rendered, no need for JS update
    console.log('Statistics loaded from server');
}

function filterTable(searchTerm) {
    const rows = document.querySelectorAll('#deliveryBoysTableBody tr');
    rows.forEach(function(row) {
        const rowText = row.textContent.toLowerCase();
        if (rowText.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function applyFilters() {
    const statusFilterEl = document.getElementById('statusFilter');
    const zoneFilterEl = document.getElementById('zoneFilter');
    
    const statusFilter = statusFilterEl ? statusFilterEl.value : '';
    const zoneFilter = zoneFilterEl ? zoneFilterEl.value : '';
    
    const rows = document.querySelectorAll('#deliveryBoysTableBody tr');
    
    rows.forEach(function(row) {
        let showRow = true;
        
        if (statusFilter) {
            const badges = row.querySelectorAll('.badge');
            let statusMatch = false;
            badges.forEach(badge => {
                if (badge.textContent.toLowerCase().includes(statusFilter.replace('_', ' '))) {
                    statusMatch = true;
                }
            });
            if (!statusMatch) showRow = false;
        }
        
        if (zoneFilter && showRow) {
            const badges = row.querySelectorAll('.badge');
            let zoneMatch = false;
            if (badges.length > 0) {
                if (badges[0].textContent.toLowerCase().includes(zoneFilter)) {
                    zoneMatch = true;
                }
            }
            if (!zoneMatch) showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

function resetFilters() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const zoneFilter = document.getElementById('zoneFilter');
    
    if (searchInput) searchInput.value = '';
    if (statusFilter) statusFilter.value = '';
    if (zoneFilter) zoneFilter.value = '';
    
    const rows = document.querySelectorAll('#deliveryBoysTableBody tr');
    rows.forEach(row => row.style.display = '');
}

function viewDeliveryBoy(deliveryBoyId) {
    console.log('Viewing delivery boy:', deliveryBoyId);
    
    // Find the delivery boy data from the table row
    const rows = document.querySelectorAll('#deliveryBoysTableBody tr');
    let deliveryBoyData = null;
    
    rows.forEach(row => {
        const idCell = row.cells[1]; // Second column has the ID
        if (idCell && idCell.textContent.trim() === deliveryBoyId) {
            deliveryBoyData = {
                id: idCell.textContent.trim(),
                name: row.cells[2].querySelector('.fw-bold').textContent.trim(),
                phone: row.cells[3].textContent.trim(),
                email: row.cells[4].textContent.trim(),
                zone: row.cells[5].textContent.trim(),
                status: row.cells[6].textContent.trim(),
                rating: row.cells[7].querySelector('.ms-1') ? row.cells[7].querySelector('.ms-1').textContent.trim() : '0.0',
                deliveries: row.cells[8].textContent.trim(),
                joinDate: row.cells[9].textContent.trim()
            };
        }
    });
    
    if (!deliveryBoyData) {
        alert('Delivery boy not found!');
        return;
    }
    
    const details = `
        <div class="row">
            <div class="col-md-4">
                <div class="text-center">
                    <div class="rounded-circle bg-primary text-white mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; font-size: 3rem;">
                        ${deliveryBoyData.name.charAt(0).toUpperCase()}
                    </div>
                    <h5>${deliveryBoyData.name}</h5>
                    <p class="text-muted">ID: ${deliveryBoyData.id}</p>
                </div>
            </div>
            <div class="col-md-8">
                <table class="table table-borderless">
                    <tr><td><strong>Phone:</strong></td><td>${deliveryBoyData.phone}</td></tr>
                    <tr><td><strong>Email:</strong></td><td>${deliveryBoyData.email}</td></tr>
                    <tr><td><strong>Zone:</strong></td><td>${deliveryBoyData.zone}</td></tr>
                    <tr><td><strong>Status:</strong></td><td>${deliveryBoyData.status}</td></tr>
                    <tr><td><strong>Rating:</strong></td><td><span class="text-warning">★</span> ${deliveryBoyData.rating}/5</td></tr>
                    <tr><td><strong>Total Deliveries:</strong></td><td><span class="badge bg-info">${deliveryBoyData.deliveries}</span></td></tr>
                    <tr><td><strong>Join Date:</strong></td><td>${deliveryBoyData.joinDate}</td></tr>
                </table>
            </div>
        </div>
        <hr>
        <h6>Recent Deliveries</h6>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Delivery history will be loaded from the database.
        </div>
    `;
    
    const detailsEl = document.getElementById('deliveryBoyDetails');
    if (detailsEl) {
        detailsEl.innerHTML = details;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('viewDeliveryBoyModal'));
    modal.show();
}

function editDeliveryBoy(deliveryBoyId) {
    console.log('Editing delivery boy:', deliveryBoyId);
    
    // Find the delivery boy data from the table row
    const rows = document.querySelectorAll('#deliveryBoysTableBody tr');
    let deliveryBoyData = null;
    
    rows.forEach(row => {
        const idCell = row.cells[1];
        if (idCell && idCell.textContent.trim() === deliveryBoyId) {
            deliveryBoyData = {
                id: idCell.textContent.trim(),
                name: row.cells[2].querySelector('.fw-bold').textContent.trim(),
                phone: row.cells[3].textContent.trim(),
                email: row.cells[4].textContent.trim(),
                zone: row.cells[5].textContent.trim().toLowerCase().replace(' zone', '')
            };
        }
    });
    
    if (!deliveryBoyData) {
        alert('Delivery boy not found!');
        return;
    }
    
    // Populate edit form
    document.getElementById('edit_delivery_boy_id').value = deliveryBoyData.id;
    document.getElementById('edit_name').value = deliveryBoyData.name;
    document.getElementById('edit_phone').value = deliveryBoyData.phone;
    document.getElementById('edit_email').value = deliveryBoyData.email;
    document.getElementById('edit_zone').value = deliveryBoyData.zone;
    document.getElementById('edit_password').value = '';
    
    // Open modal
    const modal = new bootstrap.Modal(document.getElementById('editDeliveryBoyModal'));
    modal.show();
}

function assignDelivery(id) {
    // This would open delivery assignment modal
    alert(`Assign delivery to ${id} - This would open assignment form`);
}

function activateDeliveryBoy(id) {
    if (confirm('Are you sure you want to activate this delivery boy?')) {
        // API call to activate
        showNotification('Delivery boy activated successfully!', 'success');
        setTimeout(() => location.reload(), 1500);
    }
}

function deactivateDeliveryBoy(id) {
    if (confirm('Are you sure you want to deactivate this delivery boy?')) {
        // API call to deactivate
        showNotification('Delivery boy deactivated successfully!', 'success');
        setTimeout(() => location.reload(), 1500);
    }
}

function bulkAssignDeliveries() {
    const selectedIds = getSelectedIds();
    if (selectedIds.length === 0) {
        alert('Please select delivery boys first');
        return;
    }
    alert(`Assign deliveries to ${selectedIds.length} delivery boys`);
}

function bulkActivate() {
    const selectedIds = getSelectedIds();
    if (selectedIds.length === 0) {
        alert('Please select delivery boys first');
        return;
    }
    if (confirm(`Activate ${selectedIds.length} selected delivery boys?`)) {
        showNotification('Selected delivery boys activated!', 'success');
        setTimeout(() => location.reload(), 1500);
    }
}

function bulkDeactivate() {
    const selectedIds = getSelectedIds();
    if (selectedIds.length === 0) {
        alert('Please select delivery boys first');
        return;
    }
    if (confirm(`Deactivate ${selectedIds.length} selected delivery boys?`)) {
        showNotification('Selected delivery boys deactivated!', 'success');
        setTimeout(() => location.reload(), 1500);
    }
}

function getSelectedIds() {
    const selectedIds = [];
    $('.delivery-boy-checkbox:checked').each(function() {
        selectedIds.push($(this).closest('tr').find('td:nth-child(2)').text());
    });
    return selectedIds;
}

function exportDeliveryBoys() {
    alert('Exporting delivery boys data...');
}

function refreshTable() {
    showNotification('Table refreshed!', 'info');
    updateStatistics();
}

function showNotification(message, type) {
    // Create notification element using vanilla JavaScript
    const alertType = type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info';
    const notification = document.createElement('div');
    notification.className = `alert alert-${alertType} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            const bsAlert = new bootstrap.Alert(notification);
            bsAlert.close();
        }
    }, 5000);
}

// Form submission - Wrap in DOMContentLoaded to ensure form exists
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addDeliveryBoyForm');
    
    if (!form) {
        console.error('Form not found: addDeliveryBoyForm');
        return;
    }
    
    console.log('Form found and event listener attached');
    
    form.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Debug: Log form data
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    console.log('Sending request to:', '<?php echo e(route("admin.manual-delivery.store-delivery-boy")); ?>'); // Debug log
    
    fetch('<?php echo e(route("admin.manual-delivery.store-delivery-boy")); ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json().then(data => {
            return { status: response.status, data: data };
        });
    })
    .then(({ status, data }) => {
        console.log('Success response:', data);
        
        if (status === 200 || status === 201) {
            if (data.success) {
                showNotification(data.message || 'Delivery boy created successfully', 'success');
                
                // Close modal
                const modalElement = document.getElementById('addDeliveryBoyModal');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                }
                
                // Reset form
                const form = document.getElementById('addDeliveryBoyForm');
                if (form) {
                    form.reset();
                }
                
                // Refresh page
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification(data.message || 'An error occurred', 'error');
            }
        } else if (status === 422) {
            // Validation errors
            let errorMessage = data.message || 'Validation failed';
            
            // Add specific field errors if available
            if (data.errors) {
                const errorList = [];
                for (const [field, messages] of Object.entries(data.errors)) {
                    if (Array.isArray(messages)) {
                        errorList.push(...messages);
                    } else {
                        errorList.push(messages);
                    }
                }
                if (errorList.length > 0) {
                    errorMessage = errorList.join('<br>');
                }
            }
            
            showNotification(errorMessage, 'error');
        } else {
            showNotification(data.message || 'An error occurred. Please try again.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please check the console for details.', 'error');
    });
    });
    
    // Edit Form Submission
    const editForm = document.getElementById('editDeliveryBoyForm');
    
    if (!editForm) {
        console.error('Edit form not found: editDeliveryBoyForm');
    } else {
        console.log('Edit form found and event listener attached');
        
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Edit form submitted');
            
            const formData = new FormData(this);
            const deliveryBoyId = formData.get('delivery_boy_id');
            
            // Debug: Log form data
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            
            console.log('Sending update request for:', deliveryBoyId);
            
            fetch(`/admin/manual-delivery/delivery-boy/${deliveryBoyId}/update`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Edit response status:', response.status);
                return response.json().then(data => {
                    return { status: response.status, data: data };
                });
            })
            .then(({ status, data }) => {
                console.log('Edit success response:', data);
                
                if (status === 200 || status === 201) {
                    if (data.success) {
                        showNotification(data.message || 'Delivery boy updated successfully', 'success');
                        
                        // Close modal
                        const modalElement = document.getElementById('editDeliveryBoyModal');
                        if (modalElement) {
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) {
                                modal.hide();
                            }
                        }
                        
                        // Refresh page
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showNotification(data.message || 'An error occurred', 'error');
                    }
                } else if (status === 422) {
                    // Validation errors
                    let errorMessage = data.message || 'Validation failed';
                    
                    // Add specific field errors if available
                    if (data.errors) {
                        const errorList = [];
                        for (const [field, messages] of Object.entries(data.errors)) {
                            if (Array.isArray(messages)) {
                                errorList.push(...messages);
                            } else {
                                errorList.push(messages);
                            }
                        }
                        if (errorList.length > 0) {
                            errorMessage = errorList.join('<br>');
                        }
                    }
                    
                    showNotification(errorMessage, 'error');
                } else {
                    showNotification(data.message || 'An error occurred. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Edit error:', error);
                showNotification('An error occurred while updating. Please try again.', 'error');
            });
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/manual-delivery/delivery-boys.blade.php ENDPATH**/ ?>