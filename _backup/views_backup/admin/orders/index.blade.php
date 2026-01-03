@extends('admin.layouts.app')

@section('title', 'Processed Orders')
@section('page-title', 'Processed Orders')

@section('content')
<h1 class="page-title">Processed Orders</h1>

<!-- Search Bar -->
<div class="search-bar">
    <div class="search-box">
        <i class="fas fa-search search-icon"></i>
        <input type="text" placeholder="Search processed order." id="order-search" value="{{ request('search') }}">
    </div>
</div>

<!-- Order Management Section -->
<div class="order-management-section">
    <div class="section-header">
        <div class="section-title">
            <h3>Order Management</h3>
            <p>Confirmed, shipped, delivered, and cancelled orders.</p>
        </div>
        <div class="total-orders">
            <span class="orders-count">{{ $allOrdersCount ?? 28 }} Orders</span>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="{{ route('admin.orders.index', ['status' => 'all']) }}" 
           class="filter-tab {{ request('status', 'all') == 'all' ? 'active' : '' }}">
            <span>All Orders ({{ $allOrdersCount ?? 28 }})</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" 
           class="filter-tab {{ request('status') == 'confirmed' ? 'active' : '' }}">
            <span>Confirmed</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" 
           class="filter-tab {{ request('status') == 'processing' ? 'active' : '' }}">
            <span>Processing ({{ $processingCount ?? 14 }})</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" 
           class="filter-tab {{ request('status') == 'shipped' ? 'active' : '' }}">
            <span>Shipped ({{ $shippedCount ?? 2 }})</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" 
           class="filter-tab {{ request('status') == 'completed' ? 'active' : '' }}">
            <span>Delivered ({{ $deliveredCount ?? 8 }})</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" 
           class="filter-tab {{ request('status') == 'cancelled' ? 'active' : '' }}">
            <span>Cancelled ({{ $cancelledCount ?? 3 }})</span>
        </a>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulk-actions-container">
        <div class="bulk-select">
            <input type="checkbox" class="bulk-checkbox" id="select-all-orders">
            <label for="select-all-orders" class="bulk-text">Select items for bulk actions</label>
        </div>
        <div class="bulk-action-buttons" style="display: none;">
            <button class="btn btn-sm btn-info" onclick="bulkConfirm()">
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
            <span class="selected-count"></span>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="orders-table">
        <div class="table-header">
            <div class="table-grid">
                <div class="col-checkbox"></div>
                <div class="col-order-id"><strong>Order ID</strong></div>
                <div class="col-customer"><strong>Customer</strong></div>
                <div class="col-amount"><strong>Amount</strong></div>
                <div class="col-status"><strong>Status</strong></div>
                <div class="col-payment"><strong>Payment</strong></div>
                <div class="col-date"><strong>Date</strong></div>
                <div class="col-actions"><strong>Actions</strong></div>
            </div>
        </div>
        
        <div class="table-body">
            @forelse($orders as $order)
            <div class="table-row {{ $loop->iteration == 2 ? 'highlighted' : '' }}" data-order-id="{{ $order->id }}">
                <div class="table-grid">
                    <!-- Checkbox -->
                    <div class="col-checkbox">
                        <input type="checkbox" class="bulk-checkbox order-checkbox" value="{{ $order->id }}" {{ $loop->iteration <= 2 ? 'checked' : '' }}>
                    </div>
                    
                    <!-- Order ID -->
                    <div class="col-order-id">
                        <div class="order-id-info">
                            <span class="order-number">{{ $order->order_number }}</span>
                            <span class="status-tag status-{{ $order->status }}">{{ strtoupper($order->status) }}</span>
                        </div>
                    </div>
                    
                    <!-- Customer -->
                    <div class="col-customer">
                        <div class="customer-info">
                            <div class="customer-name">{{ $order->user->name ?? 'N/A' }}</div>
                            <div class="customer-phone">{{ $order->user->phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                    
                    <!-- Amount -->
                    <div class="col-amount">
                        <div class="amount-value">Rs. {{ number_format($order->total, 2) }}</div>
                    </div>
                    
                    <!-- Status -->
                    <div class="col-status">
                        <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </div>
                    
                    <!-- Payment -->
                    <div class="col-payment">
                        <span class="payment-badge payment-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
                    </div>
                    
                    <!-- Date -->
                    <div class="col-date">
                        <span class="order-date">{{ $order->created_at->format('n/j/Y') }}</span>
                    </div>
                    
                    <!-- Actions -->
                    <div class="col-actions">
                        <div class="action-dropdown">
                            <button class="action-btn dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.orders.show', $order) }}"><i class="fas fa-eye"></i> View</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.orders.edit', $order) }}"><i class="fas fa-edit"></i> Edit</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteOrder({{ $order->id }})"><i class="fas fa-trash"></i> Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>No Processed Orders</h3>
                <p>There are no processed orders at the moment.</p>
            </div>
            @endforelse
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

@push('scripts')
<script>
// Bulk Actions
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all-orders');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    
    selectAllCheckbox.addEventListener('change', function() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });
    
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
                const currentStatus = new URLSearchParams(window.location.search).get('status') || 'all';
                window.location.href = `{{ route('admin.orders.index') }}?search=${encodeURIComponent(searchTerm)}&status=${currentStatus}`;
            }
        }, 500);
    });
});

function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
    const bulkActionButtons = document.querySelector('.bulk-action-buttons');
    const selectedCount = document.querySelector('.selected-count');
    
    if (checkedBoxes.length > 0) {
        bulkActionButtons.style.display = 'flex';
        selectedCount.textContent = `${checkedBoxes.length} selected`;
    } else {
        bulkActionButtons.style.display = 'none';
        selectedCount.textContent = '';
    }
}

function updateSelectAllState() {
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
    const selectAllCheckbox = document.getElementById('select-all-orders');
    
    if (checkedBoxes.length === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
    } else if (checkedBoxes.length === orderCheckboxes.length) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
    } else {
        selectAllCheckbox.indeterminate = true;
    }
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
    fetch('/admin/orders/bulk-action', {
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

function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        fetch(`/admin/orders/${orderId}`, {
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
</script>
@endpush