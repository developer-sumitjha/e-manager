@extends('admin.layouts.app')

@section('title', 'Order Allocation - Manual Delivery')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Order Allocation</h1>
            <p class="text-muted">Allocate confirmed orders to delivery boys</p>
        </div>
        <a href="{{ route('admin.manual-delivery.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.manual-delivery.allocation') }}" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search by order number or customer..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                    <a href="{{ route('admin.manual-delivery.allocation') }}" class="btn btn-outline-secondary"><i class="fas fa-refresh"></i> Reset</a>
                </div>
                <div class="col-md-3 text-end">
                    <button type="button" class="btn btn-success" onclick="bulkAllocate()">
                        <i class="fas fa-truck"></i> Bulk Allocate
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Confirmed Orders Ready for Allocation ({{ $orders->total() }})
            </h6>
        </div>
        <div class="card-body">
            @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Order Number</th>
                            <th>Customer</th>
                            <th>Address</th>
                            <th>Total Amount</th>
                            <th>Payment Method</th>
                            <th>Order Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td><input type="checkbox" class="order-checkbox" value="{{ $order->id }}"></td>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>
                                <div>{{ $order->user->name }}</div>
                                <small class="text-muted">{{ $order->user->phone }}</small>
                            </td>
                            <td>{{ Str::limit($order->shipping_address ?? 'N/A', 40) }}</td>
                            <td><strong class="text-success">₨{{ number_format($order->total, 2) }}</strong></td>
                            <td>
                                @php
                                    $paymentMethod = $order->payment_method;
                                    // Normalize payment method display
                                    if (in_array($paymentMethod, ['cod', 'cash_on_delivery'])) {
                                        $paymentMethod = 'COD';
                                        $badgeClass = 'warning';
                                    } elseif (in_array($paymentMethod, ['paid', 'online', 'bank_transfer', 'khalti', 'esewa'])) {
                                        $paymentMethod = ucfirst(str_replace('_', ' ', $paymentMethod));
                                        $badgeClass = 'success';
                                    } else {
                                        $paymentMethod = ucfirst(str_replace('_', ' ', $paymentMethod ?? 'N/A'));
                                        $badgeClass = 'info';
                                    }
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">
                                    {{ $paymentMethod }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="allocateOrder({{ $order->id }})">
                                    <i class="fas fa-user-plus"></i> Allocate
                                </button>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $orders->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-box-open text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3">No Orders Available for Allocation</h4>
                <p class="text-muted">All confirmed orders have been allocated or there are no confirmed orders.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Allocate Order Modal -->
<div class="modal fade" id="allocateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Allocate Order to Delivery Boy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="allocateForm">
                @csrf
                <input type="hidden" id="order_id" name="order_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="delivery_boy_id" class="form-label">Select Delivery Boy</label>
                        <select class="form-select" id="delivery_boy_id" name="delivery_boy_id" required>
                            <option value="">Choose delivery boy...</option>
                            @foreach($deliveryBoys as $boy)
                                <option value="{{ $boy->id }}">
                                    {{ $boy->name }} ({{ $boy->delivery_boy_id }}) - {{ ucfirst($boy->zone ?? 'No Zone') }}
                                    - Rating: {{ $boy->rating }}/5
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="delivery_notes" class="form-label">Delivery Notes (Optional)</label>
                        <textarea class="form-control" id="delivery_notes" name="delivery_notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Allocate Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Allocate Modal -->
<div class="modal fade" id="bulkAllocateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Allocate Orders</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkAllocateForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <span id="selectedCount">0</span> orders selected
                    </div>
                    <div class="mb-3">
                        <label for="bulk_delivery_boy_id" class="form-label">Select Delivery Boy</label>
                        <select class="form-select" id="bulk_delivery_boy_id" name="delivery_boy_id" required>
                            <option value="">Choose delivery boy...</option>
                            @foreach($deliveryBoys as $boy)
                                <option value="{{ $boy->id }}">
                                    {{ $boy->name }} ({{ $boy->delivery_boy_id }}) - {{ ucfirst($boy->zone ?? 'No Zone') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Allocate Selected Orders</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all functionality
    const selectAll = document.getElementById('selectAll');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            orderCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });
    }
    
    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
        });
    });
    
    // Allocate single order form
    const allocateForm = document.getElementById('allocateForm');
    if (allocateForm) {
        allocateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("admin.manual-delivery.allocate") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('allocateModal'));
                    if (modal) {
                        modal.hide();
                    }
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message || 'Failed to allocate order', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while allocating the order', 'error');
            });
        });
    }
    
    // Bulk allocate form
    const bulkAllocateForm = document.getElementById('bulkAllocateForm');
    if (bulkAllocateForm) {
        bulkAllocateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const orderIds = getSelectedOrderIds();
            const deliveryBoyId = document.getElementById('bulk_delivery_boy_id').value;
            
            if (orderIds.length === 0) {
                showNotification('Please select at least one order', 'error');
                return;
            }
            
            fetch('{{ route("admin.manual-delivery.bulk-allocate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    order_ids: orderIds,
                    delivery_boy_id: deliveryBoyId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('bulkAllocateModal'));
                    if (modal) {
                        modal.hide();
                    }
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message || 'Failed to allocate orders', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while allocating orders', 'error');
            });
        });
    }
});

function updateSelectedCount() {
    const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
    const count = checkedBoxes.length;
    const selectedCountElement = document.getElementById('selectedCount');
    if (selectedCountElement) {
        selectedCountElement.textContent = count;
    }
}

function allocateOrder(orderId) {
    const orderIdInput = document.getElementById('order_id');
    if (orderIdInput) {
        orderIdInput.value = orderId;
    }
    
    const modalElement = document.getElementById('allocateModal');
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }
}

function bulkAllocate() {
    const selectedIds = getSelectedOrderIds();
    if (selectedIds.length === 0) {
        showNotification('Please select at least one order', 'error');
        return;
    }
    updateSelectedCount();
    
    const modalElement = document.getElementById('bulkAllocateModal');
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }
}

function getSelectedOrderIds() {
    const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
    return Array.from(checkedBoxes).map(checkbox => checkbox.value);
}

function showNotification(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const alert = document.createElement('div');
    alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alert.innerHTML = `
        <i class="fas ${icon} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endpush







