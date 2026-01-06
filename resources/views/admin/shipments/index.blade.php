@extends('admin.layouts.app')

@section('title', 'Shipment Management')
@section('page-title', 'Shipment Management')

@push('styles')
<style>
    /* Clean Shipment Management Styles - Matching Vendor Dashboard */
    .shipment-hero {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .hero-content {
        position: relative;
    }

    .hero-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1e293b;
    }

    .hero-subtitle {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 1.5rem;
        font-weight: 400;
    }

    .hero-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .hero-stat {
        text-align: center;
        padding: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    .hero-stat:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .hero-stat-value {
        font-size: 1.875rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: #1e293b;
    }

    .hero-stat-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Action Cards */
    .action-cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .action-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .action-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .action-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        margin-bottom: 1rem;
        background: #3b82f6;
    }

    .action-card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .action-card-description {
        color: #64748b;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    /* Allotment Section */
    .allotment-section {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .section-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        background: #3b82f6;
    }

    .section-main-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .section-description {
        color: #64748b;
        font-size: 0.875rem;
    }

    .pending-count {
        background: #fef3c7;
        color: #92400e;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    /* Allotment Actions */
    .allotment-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f1f5f9;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .bulk-allotment {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .bulk-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 4px;
        border: 2px solid #cbd5e1;
        background: white;
        cursor: pointer;
    }

    .bulk-checkbox:checked {
        background: #3b82f6;
        border-color: #3b82f6;
    }

    .bulk-text {
        font-weight: 600;
        color: #1e293b;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .allotment-buttons {
        display: flex;
        gap: 0.75rem;
    }

    .btn-allotment {
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        border: 1.5px solid;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-manual {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }

    .btn-manual:hover {
        background: #059669;
        border-color: #059669;
    }

    .btn-logistics {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .btn-logistics:hover {
        background: #2563eb;
        border-color: #2563eb;
    }

    /* Orders List */
    .orders-list {
        display: grid;
        gap: 1rem;
    }

    .order-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.2s ease;
        display: grid;
        grid-template-columns: auto 1fr auto auto;
        gap: 1.5rem;
        align-items: center;
    }

    .order-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .order-checkbox {
        display: flex;
        align-items: center;
    }

    .order-select-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 4px;
        border: 2px solid #cbd5e1;
        background: white;
        cursor: pointer;
    }

    .order-select-checkbox:checked {
        background: #3b82f6;
        border-color: #3b82f6;
    }

    .order-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .order-id {
        font-weight: 600;
        color: #1e293b;
        font-size: 1rem;
    }

    .order-customer {
        color: #64748b;
        font-size: 0.875rem;
    }

    .order-details {
        text-align: right;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .order-amount {
        font-weight: 600;
        color: #059669;
        font-size: 1rem;
    }

    .order-date {
        color: #64748b;
        font-size: 0.875rem;
    }

    .order-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-quick {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 500;
        border: 1.5px solid;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .btn-quick-manual {
        background: #d1fae5;
        color: #065f46;
        border-color: #a7f3d0;
    }

    .btn-quick-manual:hover {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }

    .btn-quick-logistics {
        background: #dbeafe;
        color: #1e40af;
        border-color: #93c5fd;
    }

    .btn-quick-logistics:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }

    .empty-icon {
        width: 4rem;
        height: 4rem;
        border-radius: 50%;
        background: #d1fae5;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 1.5rem;
        color: #065f46;
    }

    .empty-state h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #64748b;
        font-size: 0.875rem;
    }

    /* Recent Shipments */
    .recent-shipments-section {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .view-all-link {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: color 0.2s ease;
    }

    .view-all-link:hover {
        color: #2563eb;
    }

    .shipments-list {
        display: grid;
        gap: 1rem;
    }

    .shipment-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.2s ease;
        display: grid;
        grid-template-columns: 1fr auto auto;
        gap: 1.5rem;
        align-items: center;
    }

    .shipment-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .shipment-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .shipment-tracking {
        font-weight: 600;
        color: #1e293b;
        font-size: 1rem;
    }

    .shipment-order {
        color: #64748b;
        font-size: 0.875rem;
    }

    .shipment-details {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: center;
    }

    .method-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .method-manual {
        background: #d1fae5;
        color: #065f46;
    }

    .method-logistics {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-shipped {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-delivered {
        background: #d1fae5;
        color: #065f46;
    }

    .shipment-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-view {
        padding: 0.5rem;
        border-radius: 8px;
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-view:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .hero-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .action-cards-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .shipment-hero {
            padding: 1.5rem;
        }
        
        .hero-title {
            font-size: 1.25rem;
        }
        
        .hero-stats {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .action-cards-grid {
            grid-template-columns: 1fr;
        }
        
        .order-item {
            grid-template-columns: 1fr;
            gap: 1rem;
            text-align: center;
        }
        
        .shipment-item {
            grid-template-columns: 1fr;
            gap: 1rem;
            text-align: center;
        }
        
        .allotment-actions {
            flex-direction: column;
            gap: 1rem;
        }
        
        .section-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
    }

    @media (max-width: 480px) {
        .hero-title {
            font-size: 1.125rem;
        }
        
        .hero-subtitle {
            font-size: 0.8125rem;
        }
        
        .action-card {
            padding: 1.25rem;
        }
        
        .allotment-section,
        .recent-shipments-section {
            padding: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="shipment-hero">
    <div class="hero-content">
        <h1 class="hero-title">Shipment Management</h1>
        <p class="hero-subtitle">Comprehensive delivery and logistics management system</p>
        
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-value">{{ $totalShipments }}</div>
                <div class="hero-stat-label">Total Shipments</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">{{ $manualDeliveries }}</div>
                <div class="hero-stat-label">Manual Deliveries</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">{{ $logisticsShipments }}</div>
                <div class="hero-stat-label">Logistics</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">{{ $deliveryRate }}%</div>
                <div class="hero-stat-label">Success Rate</div>
            </div>
        </div>
    </div>
</div>

<!-- Action Cards -->
<div class="action-cards-grid">
    <div class="action-card" onclick="window.location.href='{{ route('admin.shipments.create') }}'">
        <div class="action-card-icon">
            <i class="fas fa-box"></i>
        </div>
        <div class="action-card-title">Shipment Allotment</div>
        <div class="action-card-description">Assign orders to delivery methods and manage shipment allocation</div>
    </div>

    <div class="action-card" onclick="window.location.href='{{ route('admin.shipments.create') }}?method=manual'">
        <div class="action-card-icon">
            <i class="fas fa-user"></i>
        </div>
        <div class="action-card-title">Manual Delivery</div>
        <div class="action-card-description">Manage in-house delivery operations and track manual shipments</div>
    </div>

    <div class="action-card" onclick="window.location.href='{{ route('admin.shipments.create') }}?method=logistics'">
        <div class="action-card-icon">
            <i class="fas fa-truck"></i>
        </div>
        <div class="action-card-title">Logistics</div>
        <div class="action-card-description">Coordinate with third-party delivery services and logistics partners</div>
    </div>
</div>

<!-- Shipment Allotment Section -->
<div class="allotment-section">
    <div class="section-header">
        <div class="section-title">
            <div class="section-icon">
                <i class="fas fa-box"></i>
            </div>
            <div>
                <div class="section-main-title">Shipment Allotment</div>
                <div class="section-description">Assign confirmed orders to manual delivery or logistics companies</div>
            </div>
        </div>
        <div class="pending-count">
            {{ $confirmedOrders->count() }} Orders Pending
        </div>
    </div>

    @if($confirmedOrders->count() > 0)
    <div class="allotment-actions">
        <div class="bulk-allotment">
            <input type="checkbox" id="select-all-orders" class="bulk-checkbox">
            <label for="select-all-orders" class="bulk-text">Select all orders for bulk allotment</label>
        </div>
        <div class="allotment-buttons">
            <button class="btn-allotment btn-manual" onclick="bulkAllotment('manual')">
                <i class="fas fa-user"></i> Manual Delivery
            </button>
            <button class="btn-allotment btn-logistics" onclick="bulkAllotment('logistics')">
                <i class="fas fa-truck"></i> Logistics
            </button>
        </div>
    </div>
    @endif
</div>

<!-- Confirmed Orders Section -->
<div class="allotment-section">
    <div class="section-header">
        <div class="section-title">
            <div class="section-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <div class="section-main-title">Confirmed Orders</div>
                <div class="section-description">Orders awaiting shipment allotment</div>
            </div>
        </div>
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
                <button class="btn-quick btn-quick-manual" onclick="quickAllotment({{ $order->id }}, 'manual')">
                    <i class="fas fa-user"></i> Manual
                </button>
                <button class="btn-quick btn-quick-logistics" onclick="quickAllotment({{ $order->id }}, 'logistics')">
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
        <div class="section-title">
            <div class="section-icon">
                <i class="fas fa-shipping-fast"></i>
            </div>
            <div>
                <div class="section-main-title">Recent Shipments</div>
                <div class="section-description">Latest shipment activities and tracking information</div>
            </div>
        </div>
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
                <a href="{{ route('admin.shipments.show', $shipment) }}" class="btn-view">
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
    // Utility functions for shipment management
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log('Shipment Management loaded');
        
        const selectAllCheckbox = document.getElementById('select-all-orders');
        const orderCheckboxes = document.querySelectorAll('.order-select-checkbox');
        
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                orderCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }

        // Add loading states to action cards
        const actionCards = document.querySelectorAll('.action-card');
        actionCards.forEach(card => {
            card.addEventListener('click', function() {
                this.style.opacity = '0.7';
                this.style.transform = 'scale(0.98)';
                
                setTimeout(() => {
                    this.style.opacity = '1';
                    this.style.transform = 'scale(1)';
                }, 200);
            });
        });

        // Add hover effects to order items
        const orderItems = document.querySelectorAll('.order-item');
        orderItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });

    function bulkAllotment(method) {
        const selectedOrders = Array.from(document.querySelectorAll('.order-select-checkbox:checked')).map(cb => cb.value);
        
        if (selectedOrders.length === 0) {
            showNotification('Please select at least one order', 'error');
            return;
        }
        
        const methodText = method === 'manual' ? 'Manual Delivery' : 'Logistics';
        
        if (confirm(`Assign ${selectedOrders.length} orders to ${methodText}?`)) {
            // Add loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            button.disabled = true;
            
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
            // Add loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;
            
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

