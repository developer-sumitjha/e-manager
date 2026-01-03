@extends('admin.layouts.app')

@section('title', 'Shipment Management')
@section('page-title', 'Shipment Management')

@section('content')
<h1 class="page-title">Shipment Management</h1>
<p class="page-subtitle">Comprehensive delivery and logistics management system</p>

<!-- Metrics Cards -->
<div class="metrics-row">
    <div class="metric-card">
        <div class="metric-icon purple">
            <i class="fas fa-box"></i>
        </div>
        <div class="metric-content">
            <div class="metric-title">TOTAL SHIPMENTS</div>
            <div class="metric-value">{{ $totalShipments }}</div>
            <div class="metric-badge">All time</div>
        </div>
        <div class="metric-chart">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon green">
            <i class="fas fa-user"></i>
        </div>
        <div class="metric-content">
            <div class="metric-title">MANUAL DELIVERIES</div>
            <div class="metric-value">{{ $manualDeliveries }}</div>
            <div class="metric-badge">In-house</div>
        </div>
        <div class="metric-chart">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon purple">
            <i class="fas fa-truck"></i>
        </div>
        <div class="metric-content">
            <div class="metric-title">LOGISTICS</div>
            <div class="metric-value">{{ $logisticsShipments }}</div>
            <div class="metric-badge">Third-party</div>
        </div>
        <div class="metric-chart">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon yellow">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <div class="metric-content">
            <div class="metric-title">DELIVERY RATE</div>
            <div class="metric-value">{{ $deliveryRate }}%</div>
            <div class="metric-badge success">Success</div>
        </div>
        <div class="metric-chart">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>
</div>

<!-- Action Cards -->
<div class="action-cards-row">
    <div class="action-card" onclick="window.location.href='{{ route('admin.shipments.create') }}'">
        <div class="action-icon purple">
            <i class="fas fa-box"></i>
        </div>
        <div class="action-content">
            <div class="action-title">Shipment Allotment</div>
            <div class="action-description">Assign orders to delivery methods</div>
        </div>
    </div>

    <div class="action-card" onclick="window.location.href='{{ route('admin.shipments.create') }}?method=manual'">
        <div class="action-icon purple">
            <i class="fas fa-user"></i>
        </div>
        <div class="action-content">
            <div class="action-title">Manual Delivery</div>
            <div class="action-description">In-house delivery management</div>
        </div>
    </div>

    <div class="action-card" onclick="window.location.href='{{ route('admin.shipments.create') }}?method=logistics'">
        <div class="action-icon purple">
            <i class="fas fa-truck"></i>
        </div>
        <div class="action-content">
            <div class="action-title">Logistics</div>
            <div class="action-description">Third-party delivery services</div>
        </div>
    </div>
</div>

<!-- Shipment Allotment Section -->
<div class="allotment-section">
    <div class="section-header">
        <div class="section-title">
            <div class="section-icon purple">
                <i class="fas fa-box"></i>
            </div>
            <div class="section-content">
                <div class="section-main-title">Shipment Allotment</div>
                <div class="section-description">Assign confirmed orders to manual delivery or logistics companies</div>
            </div>
        </div>
        <div class="pending-count">
            <span class="count-badge">{{ $confirmedOrders->count() }} Orders Pending</span>
        </div>
    </div>

    @if($confirmedOrders->count() > 0)
    <div class="allotment-actions">
        <div class="bulk-allotment">
            <input type="checkbox" id="select-all-orders" class="bulk-checkbox">
            <label for="select-all-orders" class="bulk-text">Select all orders for bulk allotment</label>
        </div>
        <div class="allotment-buttons">
            <button class="btn btn-success" onclick="bulkAllotment('manual')">
                <i class="fas fa-user"></i> Manual Delivery
            </button>
            <button class="btn btn-primary" onclick="bulkAllotment('logistics')">
                <i class="fas fa-truck"></i> Logistics
            </button>
        </div>
    </div>
    @endif
</div>

<!-- Confirmed Orders Section -->
<div class="confirmed-orders-section">
    <div class="section-header">
        <h3>Confirmed Orders - Awaiting Shipment Allotment</h3>
    </div>

    @if($confirmedOrders->count() > 0)
    <div class="orders-list">
        @foreach($confirmedOrders as $order)
        <div class="order-item" data-order-id="{{ $order->id }}">
            <div class="order-checkbox">
                <input type="checkbox" class="order-select-checkbox" value="{{ $order->id }}">
            </div>
            <div class="order-info">
                <div class="order-id">{{ $order->order_number }}</div>
                <div class="order-customer">{{ $order->user->name ?? 'N/A' }}</div>
            </div>
            <div class="order-details">
                <div class="order-amount">Rs. {{ number_format($order->total, 2) }}</div>
                <div class="order-date">{{ $order->created_at->format('M d, Y') }}</div>
            </div>
            <div class="order-actions">
                <button class="btn btn-sm btn-outline-success" onclick="quickAllotment({{ $order->id }}, 'manual')">
                    <i class="fas fa-user"></i> Manual
                </button>
                <button class="btn btn-sm btn-outline-primary" onclick="quickAllotment({{ $order->id }}, 'logistics')">
                    <i class="fas fa-truck"></i> Logistics
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3>All Orders Processed</h3>
        <p>There are no confirmed orders awaiting shipment allotment.</p>
    </div>
    @endif
</div>

<!-- Recent Shipments -->
@if($recentShipments->count() > 0)
<div class="recent-shipments-section">
    <div class="section-header">
        <h3>Recent Shipments</h3>
        <a href="#" class="view-all-link">View All</a>
    </div>
    
    <div class="shipments-list">
        @foreach($recentShipments as $shipment)
        <div class="shipment-item">
            <div class="shipment-info">
                <div class="shipment-tracking">{{ $shipment->tracking_number }}</div>
                <div class="shipment-order">Order: {{ $shipment->order->order_number ?? 'N/A' }}</div>
            </div>
            <div class="shipment-details">
                <div class="shipment-method">
                    <span class="method-badge method-{{ $shipment->delivery_method }}">
                        {{ $shipment->delivery_method === 'manual' ? 'Manual' : 'Logistics' }}
                    </span>
                </div>
                <div class="shipment-status">
                    <span class="status-badge status-{{ $shipment->status }}">
                        {{ ucfirst($shipment->status) }}
                    </span>
                </div>
            </div>
            <div class="shipment-actions">
                <a href="{{ route('admin.shipments.show', $shipment) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all-orders');
    const orderCheckboxes = document.querySelectorAll('.order-select-checkbox');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            orderCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});

function bulkAllotment(method) {
    const selectedOrders = Array.from(document.querySelectorAll('.order-select-checkbox:checked')).map(cb => cb.value);
    
    if (selectedOrders.length === 0) {
        showNotification('Please select at least one order', 'error');
        return;
    }
    
    const methodText = method === 'manual' ? 'Manual Delivery' : 'Logistics';
    
    if (confirm(`Assign ${selectedOrders.length} orders to ${methodText}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.shipments.allot") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = 'delivery_method';
        methodInput.value = method;
        form.appendChild(methodInput);
        
        selectedOrders.forEach(orderId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'order_ids[]';
            input.value = orderId;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

function quickAllotment(orderId, method) {
    const methodText = method === 'manual' ? 'Manual Delivery' : 'Logistics';
    
    if (confirm(`Assign this order to ${methodText}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.shipments.allot") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = 'delivery_method';
        methodInput.value = method;
        form.appendChild(methodInput);
        
        const orderInput = document.createElement('input');
        orderInput.type = 'hidden';
        orderInput.name = 'order_ids[]';
        orderInput.value = orderId;
        form.appendChild(orderInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush

