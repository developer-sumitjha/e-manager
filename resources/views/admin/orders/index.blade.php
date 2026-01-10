@extends('admin.layouts.app')

@section('title', 'Processed Orders')
@section('page-title', 'Processed Orders')
@section('page-subtitle', 'Manage confirmed, shipped, delivered, and cancelled orders')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.orders.index') }}" class="breadcrumb-link">Orders</a>
    </div>
    <div class="breadcrumb-item active">Processed Orders</div>
@endsection

@section('content')
<!-- Search and Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>Search & Filters
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Search Orders</label>
    <div class="search-box">
        <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           placeholder="Search by ID, customer name, or email..." 
                           id="order-search" 
                           value="{{ request('search') }}"
                           class="search-input">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Payment Status</label>
                <select class="form-select" id="payment-status-filter">
                    <option value="">All Payment Status</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date Range</label>
                <input type="date" 
                       class="form-control" 
                       id="date-from"
                       placeholder="From Date"
                       value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button class="btn btn-primary" onclick="applyFilters()">
                    <i class="fas fa-search"></i> Search
                </button>
                <button class="btn btn-outline-secondary" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-trend neutral">
                    <span>{{ $allOrdersCount ?? 0 }}</span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $allOrdersCount ?? 0 }}</div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-trend neutral">
                    <span>{{ $confirmedCount ?? 0 }}</span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $confirmedCount ?? 0 }}</div>
                <div class="stat-label">Confirmed</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="stat-trend neutral">
                    <span>{{ $processingCount ?? 0 }}</span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $processingCount ?? 0 }}</div>
                <div class="stat-label">Processing</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <div class="stat-trend neutral">
                    <span>{{ $shippedCount ?? 0 }}</span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $shippedCount ?? 0 }}</div>
                <div class="stat-label">Shipped</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stat-trend neutral">
                    <span>{{ $deliveredCount ?? 0 }}</span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $deliveredCount ?? 0 }}</div>
                <div class="stat-label">Delivered</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-times"></i>
                </div>
                <div class="stat-trend neutral">
                    <span>{{ $cancelledCount ?? 0 }}</span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $cancelledCount ?? 0 }}</div>
                <div class="stat-label">Cancelled</div>
            </div>
        </div>
        </div>
    </div>


    <!-- Filter Tabs -->
    <div class="card mb-4">
        <div class="card-body">
    <div class="filter-tabs">
        <a href="{{ route('admin.orders.index', ['status' => 'all']) }}" 
           class="filter-tab {{ request('status', 'all') == 'all' ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                <span>All Orders ({{ $allOrdersCount ?? 0 }})</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" 
           class="filter-tab {{ request('status') == 'confirmed' ? 'active' : '' }}">
                <i class="fas fa-check-circle"></i>
                <span>Confirmed ({{ $confirmedCount ?? 0 }})</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" 
           class="filter-tab {{ request('status') == 'processing' ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Processing ({{ $processingCount ?? 0 }})</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" 
           class="filter-tab {{ request('status') == 'shipped' ? 'active' : '' }}">
                <i class="fas fa-shipping-fast"></i>
                <span>Shipped ({{ $shippedCount ?? 0 }})</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" 
           class="filter-tab {{ request('status') == 'completed' ? 'active' : '' }}">
                <i class="fas fa-check"></i>
                <span>Delivered ({{ $deliveredCount ?? 0 }})</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" 
           class="filter-tab {{ request('status') == 'cancelled' ? 'active' : '' }}">
                <i class="fas fa-times"></i>
                <span>Cancelled ({{ $cancelledCount ?? 0 }})</span>
        </a>
        </div>
    </div>
    </div>

    <!-- Bulk Actions -->
<div class="card mb-4" id="bulk-actions-container" style="display: none;">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="select-all-orders">
                    <label for="select-all-orders" class="form-check-label">Select all orders</label>
                </div>
                <span class="selected-count text-muted">0 selected</span>
        </div>
            <div class="bulk-action-buttons d-flex gap-2">
                <button class="btn btn-sm btn-success" onclick="bulkConfirm()">
                <i class="fas fa-check-circle"></i> Confirm
            </button>
            <button class="btn btn-sm btn-warning" onclick="bulkProcess()">
                <i class="fas fa-cog"></i> Process
            </button>
            <button class="btn btn-sm btn-primary" onclick="bulkShip()">
                <i class="fas fa-shipping-fast"></i> Ship
            </button>
            <button class="btn btn-sm btn-success" onclick="bulkDeliver()">
                <i class="fas fa-check"></i> Deliver
            </button>
            <button class="btn btn-sm btn-secondary" onclick="bulkCancel()">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button class="btn btn-sm btn-danger" onclick="bulkDelete()">
                <i class="fas fa-trash"></i> Delete
            </button>
            </div>
        </div>
        </div>
    </div>

    <!-- Orders Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Orders</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-secondary" onclick="toggleView('table')">
                    <i class="fas fa-table"></i>
                </button>
                <button class="btn btn-sm btn-outline-secondary" onclick="toggleView('grid')">
                    <i class="fas fa-th"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" class="form-check-input" id="select-all-orders-header">
                        </th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Amount</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
            @forelse($orders as $order)
                    <tr data-order-id="{{ $order->id }}">
                        <td>
                            <input type="checkbox" class="form-check-input order-checkbox" value="{{ $order->id }}">
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none fw-semibold">
                                    {{ $order->order_number }}
                                </a>
                                <small class="text-muted">{{ $order->id }}</small>
                    </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-initials">
                                        {{ substr($order->receiver_name ?? $order->user->name ?? 'G', 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $order->receiver_name ?? $order->user->name ?? 'Guest' }}</div>
                                    <small class="text-muted">{{ $order->receiver_phone ?? $order->user->phone ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="address-info">
                                <div class="fw-semibold">{{ Str::limit($order->shipping_address ?? 'Not specified', 30) }}</div>
                                <small class="text-muted">{{ $order->receiver_city ?? 'N/A' }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="amount-info">
                                <div class="fw-semibold">Rs. {{ number_format($order->total, 0) }}</div>
                                <div class="d-flex gap-2">
                                    @if($order->payment_status === 'paid')
                                        <span class="badge badge-success">Paid</span>
                                    @else
                                        <span class="badge badge-warning">COD</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="products-info">
                                <div class="fw-semibold">{{ $order->orderItems->count() }} items</div>
                                <small class="text-muted">
                                    @if($order->orderItems->count() > 0)
                                        @php
                                            $firstItem = $order->orderItems->first();
                                            $productName = $firstItem->product->name ?? 'N/A';
                                        @endphp
                                        {{ Str::limit($productName, 20) }}
                                        @if($order->orderItems->count() > 1)
                                            +{{ $order->orderItems->count() - 1 }} more
                                        @endif
                                    @else
                                        No products
                                    @endif
                                </small>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'info')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons d-flex gap-1">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="action-btn view" 
                                   title="View Order">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.orders.edit', $order) }}" 
                                   class="action-btn edit" 
                                   title="Edit Order">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <button class="action-btn delete" 
                                        onclick="deleteOrder({{ $order->id }})" 
                                        title="Delete Order">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
            @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
            <div class="empty-state">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">No orders found</h6>
                                <p class="text-muted">Orders will appear here once customers start placing them.</p>
                                <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Create Order
                                </a>
                </div>
                        </td>
                    </tr>
            @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
@if($orders->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $orders->links() }}
</div>
@endif

@endsection

@push('styles')
<style>
/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.filter-tab {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: rgba(30, 41, 59, 0.5);
    border: 1px solid rgba(99, 102, 241, 0.2);
    border-radius: var(--radius-lg);
    color: var(--text-secondary);
    text-decoration: none;
    transition: all var(--transition-fast);
    font-weight: 500;
}

.filter-tab:hover {
    background: rgba(99, 102, 241, 0.1);
    color: var(--text-primary);
    border-color: rgba(99, 102, 241, 0.3);
}

.filter-tab.active {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border-color: transparent;
    box-shadow: var(--shadow-md);
}

.filter-tab i {
    font-size: 0.875rem;
}

/* Avatar */
.avatar-sm {
    width: 32px;
    height: 32px;
}

.avatar-initials {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.75rem;
}

/* Badge Styles */
.badge {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
}

.badge-success {
    background: rgba(34, 197, 94, 0.1);
    color: var(--success);
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.badge-warning {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.badge-danger {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.badge-info {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.25rem;
    align-items: center;
    flex-wrap: wrap;
}

.action-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.75rem;
    font-weight: 600;
    transition: all var(--transition-fast);
    text-decoration: none;
    box-shadow: var(--shadow-sm);
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.action-btn i {
    font-size: 0.75rem;
}

.action-btn.view {
    background: linear-gradient(135deg, #3B82F6, #2563EB);
    color: white;
    border: 1px solid #2563EB;
}

.action-btn.view:hover {
    background: linear-gradient(135deg, #2563EB, #1D4ED8);
    border-color: #1D4ED8;
}

.action-btn.edit {
    background: linear-gradient(135deg, var(--warning), #D97706);
    color: white;
    border: 1px solid #D97706;
}

.action-btn.edit:hover {
    background: linear-gradient(135deg, #D97706, #B45309);
    border-color: #B45309;
}

.action-btn.delete {
    background: linear-gradient(135deg, var(--danger), #DC2626);
    color: white;
    border: 1px solid #DC2626;
}

.action-btn.delete:hover {
    background: linear-gradient(135deg, #DC2626, #B91C1C);
    border-color: #B91C1C;
}

/* Address and Products Info */
.address-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.products-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.amount-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

/* Empty State */
.empty-state {
    padding: var(--space-2xl);
}

/* Responsive */
@media (max-width: 768px) {
    .filter-tabs {
        flex-direction: column;
    }
    
    .filter-tab {
        justify-content: center;
    }
    
    .bulk-action-buttons {
        flex-wrap: wrap;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .action-btn {
        width: 28px;
        height: 28px;
        font-size: 0.625rem;
    }
    
    .action-btn i {
        font-size: 0.625rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bulk selection
    const selectAllCheckbox = document.getElementById('select-all-orders');
    const selectAllHeaderCheckbox = document.getElementById('select-all-orders-header');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const bulkActionsContainer = document.getElementById('bulk-actions-container');
    const selectedCount = document.querySelector('.selected-count');
    
    // Header checkbox controls all
    selectAllHeaderCheckbox.addEventListener('change', function() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        selectAllCheckbox.checked = this.checked;
        updateBulkActions();
    });
    
    // Individual checkboxes
    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActions();
            updateSelectAllState();
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('order-search');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchTerm = this.value;
            if (searchTerm.length >= 2 || searchTerm.length === 0) {
                performSearch();
            }
        }, 500);
    });
    
    // Payment status filter
    const paymentStatusFilter = document.getElementById('payment-status-filter');
    paymentStatusFilter.addEventListener('change', function() {
        performSearch();
    });
});

function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
    const selectedCount = document.querySelector('.selected-count');
    
    if (checkedBoxes.length > 0) {
        bulkActionsContainer.style.display = 'block';
        selectedCount.textContent = `${checkedBoxes.length} selected`;
    } else {
        bulkActionsContainer.style.display = 'none';
        selectedCount.textContent = '0 selected';
    }
}

function updateSelectAllState() {
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
    const selectAllCheckbox = document.getElementById('select-all-orders');
    const selectAllHeaderCheckbox = document.getElementById('select-all-orders-header');
    
    if (checkedBoxes.length === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
        selectAllHeaderCheckbox.indeterminate = false;
        selectAllHeaderCheckbox.checked = false;
    } else if (checkedBoxes.length === orderCheckboxes.length) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
        selectAllHeaderCheckbox.indeterminate = false;
        selectAllHeaderCheckbox.checked = true;
    } else {
        selectAllCheckbox.indeterminate = true;
        selectAllHeaderCheckbox.indeterminate = true;
    }
}

function performSearch() {
    const searchTerm = document.getElementById('order-search').value;
    const paymentStatus = document.getElementById('payment-status-filter').value;
    const currentStatus = new URLSearchParams(window.location.search).get('status') || 'all';
    
    const params = new URLSearchParams();
    if (searchTerm) params.set('search', searchTerm);
    if (paymentStatus) params.set('payment_status', paymentStatus);
    if (currentStatus !== 'all') params.set('status', currentStatus);
    
    window.location.href = `{{ route('admin.orders.index') }}?${params.toString()}`;
}

function bulkConfirm() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    performBulkAction('confirm', checkedIds);
}

function bulkProcess() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    performBulkAction('process', checkedIds);
}

function bulkShip() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    performBulkAction('ship', checkedIds);
}

function bulkDeliver() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    performBulkAction('deliver', checkedIds);
}

function bulkCancel() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    if (confirm(`Are you sure you want to cancel ${checkedIds.length} orders?`)) {
        performBulkAction('cancel', checkedIds);
    }
}

function bulkDelete() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    if (confirm(`Are you sure you want to delete ${checkedIds.length} orders? This action cannot be undone.`)) {
        performBulkAction('delete', checkedIds);
    }
}

function performBulkAction(action, orderIds) {
    fetch('{{ route('admin.orders.bulk-action') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            action: action,
            order_ids: orderIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`${data.message}`, 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showNotification('Failed to perform bulk action', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while performing bulk action', 'error');
    });
}

function changeStatus(orderId, status) {
    if (confirm(`Are you sure you want to change this order status to ${status}?`)) {
        fetch(`{{ url('/admin/orders') }}/${orderId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: status,
                payment_status: 'paid' // Default payment status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Order status updated successfully!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification('Failed to update order status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while updating order status', 'error');
        });
    }
}

function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        fetch(`{{ url('/admin/orders') }}/${orderId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Order deleted successfully!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification('Failed to delete order', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while deleting order', 'error');
        });
    }
}

function exportOrders() {
    const currentStatus = new URLSearchParams(window.location.search).get('status') || 'all';
    const searchTerm = document.getElementById('order-search').value;
    const paymentStatus = document.getElementById('payment-status-filter').value;
    
    const params = new URLSearchParams();
    if (searchTerm) params.set('search', searchTerm);
    if (paymentStatus) params.set('payment_status', paymentStatus);
    if (currentStatus !== 'all') params.set('status', currentStatus);
    
    window.open(`{{ route('admin.orders.index') }}?export=1&${params.toString()}`, '_blank');
}

function toggleView(view) {
    // Implementation for table/grid view toggle
    console.log('Toggle view to:', view);
}

function applyFilters() {
    const searchTerm = document.getElementById('order-search').value;
    const paymentStatus = document.getElementById('payment-status-filter').value;
    const dateFrom = document.getElementById('date-from').value;
    const currentStatus = new URLSearchParams(window.location.search).get('status') || 'all';
    
    const params = new URLSearchParams();
    if (searchTerm) params.set('search', searchTerm);
    if (paymentStatus) params.set('payment_status', paymentStatus);
    if (dateFrom) params.set('date_from', dateFrom);
    if (currentStatus !== 'all') params.set('status', currentStatus);
    
    window.location.href = `{{ route('admin.orders.index') }}?${params.toString()}`;
}

function clearFilters() {
    window.location.href = '{{ route('admin.orders.index') }}';
}
</script>
@endpush