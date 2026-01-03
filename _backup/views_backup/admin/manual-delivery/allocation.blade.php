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
                                <span class="badge bg-{{ $order->payment_method === 'cod' ? 'warning' : 'info' }}">
                                    {{ strtoupper($order->payment_method) }}
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

@section('scripts')
<script>
$(document).ready(function() {
    // Select all functionality
    $('#selectAll').on('change', function() {
        $('.order-checkbox').prop('checked', $(this).prop('checked'));
        updateSelectedCount();
    });
    
    $('.order-checkbox').on('change', function() {
        updateSelectedCount();
    });
});

function updateSelectedCount() {
    const count = $('.order-checkbox:checked').length;
    $('#selectedCount').text(count);
}

function allocateOrder(orderId) {
    $('#order_id').val(orderId);
    $('#allocateModal').modal('show');
}

function bulkAllocate() {
    const selectedIds = getSelectedOrderIds();
    if (selectedIds.length === 0) {
        alert('Please select at least one order');
        return;
    }
    updateSelectedCount();
    $('#bulkAllocateModal').modal('show');
}

function getSelectedOrderIds() {
    return $('.order-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
}

// Allocate single order
$('#allocateForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = $(this).serialize();
    
    fetch('{{ route("admin.manual-delivery.allocate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            $('#allocateModal').modal('hide');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
});

// Bulk allocate
$('#bulkAllocateForm').on('submit', function(e) {
    e.preventDefault();
    
    const orderIds = getSelectedOrderIds();
    const deliveryBoyId = $('#bulk_delivery_boy_id').val();
    
    fetch('{{ route("admin.manual-delivery.bulk-allocate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
            $('#bulkAllocateModal').modal('hide');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
});

function showNotification(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const alert = $(`
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas ${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);
    
    $('body').append(alert);
    setTimeout(() => alert.alert('close'), 3000);
}
</script>
@endsection





