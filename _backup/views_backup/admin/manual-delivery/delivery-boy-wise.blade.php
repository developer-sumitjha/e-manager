@extends('admin.layouts.app')

@section('title', 'Delivery Boy Wise Orders')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Delivery Boy Wise Orders</h1>
            <p class="text-muted">View and manage orders assigned to each delivery boy</p>
        </div>
        <a href="{{ route('admin.manual-delivery.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    @foreach($deliveryBoys as $deliveryBoy)
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div>
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-user-circle"></i> {{ $deliveryBoy->name }} ({{ $deliveryBoy->delivery_boy_id }})
                </h6>
                <small>{{ $deliveryBoy->phone }} | Zone: {{ ucfirst($deliveryBoy->zone ?? 'N/A') }} | Rating: {{ $deliveryBoy->rating }}/5</small>
            </div>
            <div>
                <span class="badge bg-light text-dark">Active Orders: {{ $deliveryBoy->active_deliveries_count }}</span>
                <span class="badge bg-light text-dark">Total: {{ $deliveryBoy->manual_deliveries_count }}</span>
                <a href="{{ route('admin.manual-delivery.boy-analytics', $deliveryBoy) }}" class="btn btn-sm btn-light">
                    <i class="fas fa-chart-line"></i> Analytics
                </a>
            </div>
        </div>
        
        @if($deliveryBoy->activeDeliveries->count() > 0)
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Address</th>
                            <th>COD Amount</th>
                            <th>Status</th>
                            <th>Assigned At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deliveryBoy->activeDeliveries as $delivery)
                        <tr>
                            <td><strong>{{ $delivery->order->order_number }}</strong></td>
                            <td>
                                <div>{{ $delivery->order->user->name }}</div>
                                <small class="text-muted">{{ $delivery->order->user->phone }}</small>
                            </td>
                            <td>{{ Str::limit($delivery->order->shipping_address ?? 'N/A', 35) }}</td>
                            <td><strong class="text-danger">₨{{ number_format($delivery->cod_amount, 2) }}</strong></td>
                            <td>
                                <select class="form-select form-select-sm status-select" data-delivery-id="{{ $delivery->id }}" style="min-width: 130px;">
                                    <option value="assigned" {{ $delivery->status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                                    <option value="picked_up" {{ $delivery->status === 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                                    <option value="in_transit" {{ $delivery->status === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                    <option value="delivered" {{ $delivery->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $delivery->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </td>
                            <td>{{ $delivery->assigned_at->format('M d, h:i A') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $delivery->order) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="card-body text-center text-muted py-4">
            <i class="fas fa-inbox" style="font-size: 2rem;"></i>
            <p class="mb-0 mt-2">No active deliveries</p>
        </div>
        @endif
    </div>
    @endforeach

    @if($deliveryBoys->isEmpty())
    <div class="card shadow">
        <div class="card-body text-center py-5">
            <i class="fas fa-users text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3">No Active Delivery Boys</h4>
            <p class="text-muted">Add delivery boys to start managing deliveries.</p>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.status-select').on('change', function() {
        const deliveryId = $(this).data('delivery-id');
        const newStatus = $(this).val();
        const select = $(this);
        
        if (confirm(`Change delivery status to "${newStatus}"?`)) {
            updateDeliveryStatus(deliveryId, newStatus, select);
        } else {
            // Revert selection
            select.val(select.data('original-value'));
        }
    }).each(function() {
        $(this).data('original-value', $(this).val());
    });
});

function updateDeliveryStatus(deliveryId, status, selectElement) {
    let additionalData = {};
    
    if (status === 'cancelled') {
        const reason = prompt('Please enter cancellation reason:');
        if (!reason) {
            selectElement.val(selectElement.data('original-value'));
            return;
        }
        additionalData.cancellation_reason = reason;
    }
    
    fetch(`/admin/manual-delivery/deliveries/${deliveryId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            status: status,
            ...additionalData
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            selectElement.data('original-value', status);
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message, 'error');
            selectElement.val(selectElement.data('original-value'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
        selectElement.val(selectElement.data('original-value'));
    });
}

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





