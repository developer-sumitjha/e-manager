@extends('admin.layouts.app')

@section('title', 'Pending Orders Management')
@section('page-title', 'Pending Orders Management')
@section('page-subtitle', 'Manage manual orders awaiting confirmation')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.orders.index') }}" class="breadcrumb-link">Orders</a>
    </div>
    <div class="breadcrumb-item active">Pending Orders</div>
@endsection

@section('content')
<!-- Search and Actions -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           placeholder="Search orders by ID, customer name, phone, or email..." 
                           id="order-search" 
                           value="{{ request('search') }}"
                           class="search-input">
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('admin.rejected-orders.index') }}" class="btn btn-outline-danger">
                        <i class="fas fa-times-circle"></i> Rejected Orders
                    </a>
                    <a href="{{ route('admin.pending-orders.create-bulk') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-plus"></i> Bulk Create
                    </a>
                    <a href="{{ route('admin.pending-orders.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Order
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-trend neutral">
                    <span>{{ $totalPendingOrders ?? 0 }}</span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalPendingOrders ?? 0 }}</div>
                <div class="stat-label">Total Pending</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="stat-trend neutral">
                    <span>{{ $manualOrdersCount ?? 0 }}</span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $manualOrdersCount ?? 0 }}</div>
                <div class="stat-label">Manual Orders</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-trend neutral">
                    <span>{{ $orders->total() ?? 0 }}</span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $orders->total() ?? 0 }}</div>
                <div class="stat-label">Showing</div>
            </div>
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
                    <i class="fas fa-check"></i> Confirm
                </button>
                <button class="btn btn-sm btn-danger" onclick="bulkReject()">
                    <i class="fas fa-times"></i> Reject
                </button>
                <button class="btn btn-sm btn-secondary" onclick="bulkDelete()">
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
            <h5 class="card-title mb-0">Manual Orders</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()" title="Refresh">
                    <i class="fas fa-sync-alt"></i>
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
                                <a href="{{ route('admin.pending-orders.show', $order) }}" class="text-decoration-none fw-semibold">
                                    {{ $order->order_number }}
                                </a>
                                <small class="text-muted">{{ $order->id }}</small>
                                <span class="badge badge-info">Manual</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-initials">
                                        {{ substr($order->user->name ?? 'G', 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $order->user->name ?? 'Guest' }}</div>
                                    <small class="text-muted">{{ $order->user->phone ?? 'N/A' }}</small>
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
                                        {{ Str::limit($order->orderItems->first()->product_name ?? 'N/A', 20) }}
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
                            <div class="action-buttons d-flex gap-1">
                                <button class="action-btn confirm" 
                                        onclick="confirmOrder({{ $order->id }})" 
                                        title="Confirm Order">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="action-btn reject" 
                                        onclick="rejectOrder({{ $order->id }})" 
                                        title="Reject Order">
                                    <i class="fas fa-times"></i>
                                </button>
                                <a href="{{ route('admin.pending-orders.edit', $order) }}" 
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
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">No pending orders found</h6>
                                <p class="text-muted">Manual orders will appear here once created.</p>
                                <a href="{{ route('admin.pending-orders.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Create Your First Order
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

.action-btn.confirm {
    background: linear-gradient(135deg, var(--success), #059669);
    color: white;
    border: 1px solid #059669;
}

.action-btn.confirm:hover {
    background: linear-gradient(135deg, #059669, #047857);
    border-color: #047857;
}

.action-btn.reject {
    background: linear-gradient(135deg, var(--danger), #DC2626);
    color: white;
    border: 1px solid #DC2626;
}

.action-btn.reject:hover {
    background: linear-gradient(135deg, #DC2626, #B91C1C);
    border-color: #B91C1C;
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
    font-size: 0.625rem;
    font-weight: 600;
    padding: 0.125rem 0.375rem;
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

.badge-info {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

/* Responsive */
@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
        gap: 0.125rem;
    }
    
    .action-btn {
        width: 28px;
        height: 28px;
        font-size: 0.625rem;
    }
    
    .action-btn i {
        font-size: 0.625rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
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
    
    const params = new URLSearchParams();
    if (searchTerm) params.set('search', searchTerm);
    
    window.location.href = `{{ route('admin.pending-orders.index') }}?${params.toString()}`;
}

function bulkConfirm() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    if (confirm(`Are you sure you want to confirm ${checkedIds.length} orders? Confirmed orders will be moved to Order Management.`)) {
        performBulkAction('confirm', checkedIds);
    }
}

function bulkReject() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    if (confirm(`Are you sure you want to reject ${checkedIds.length} orders? Rejected orders will be moved to Rejected Orders list.`)) {
        performBulkAction('reject', checkedIds);
    }
}

function bulkDelete() {
    const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    if (confirm(`Are you sure you want to delete ${checkedIds.length} orders? This action cannot be undone.`)) {
        performBulkAction('delete', checkedIds);
    }
}

function performBulkAction(action, orderIds) {
    fetch('{{ route('admin.pending-orders.bulk-action') }}', {
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

function confirmOrder(orderId) {
    console.log('confirmOrder called with orderId:', orderId);
    
    if (confirm('Are you sure you want to confirm this order? It will be moved to Order Management.')) {
        console.log('User confirmed, making AJAX request...');
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found!');
            alert('CSRF token not found. Please refresh the page.');
            return;
        }
        
        fetch(`{{ url('/admin/pending-orders') }}/${orderId}/confirm`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                if (typeof showNotification === 'function') {
                    showNotification('Order confirmed successfully!', 'success');
                } else {
                    alert('Order confirmed successfully!');
                }
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                const message = data.message || 'Failed to confirm order';
                console.error('Server returned error:', message);
                
                // Handle specific error cases
                if (message.includes('non-pending')) {
                    if (typeof showNotification === 'function') {
                        showNotification('This order has already been processed. The page will refresh.', 'warning');
                    } else {
                        alert('This order has already been processed. The page will refresh.');
                    }
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    if (typeof showNotification === 'function') {
                        showNotification(message, 'error');
                    } else {
                        alert('Error: ' + message);
                    }
                }
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            const errorMessage = 'An error occurred while confirming order: ' + error.message;
            if (typeof showNotification === 'function') {
                showNotification(errorMessage, 'error');
            } else {
                alert(errorMessage);
            }
        });
    }
}

function rejectOrder(orderId) {
    if (confirm('Are you sure you want to reject this order? It will be moved to Rejected Orders list.')) {
        fetch(`{{ url('/admin/pending-orders') }}/${orderId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Order rejected successfully!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'Failed to reject order', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while rejecting order', 'error');
        });
    }
}

function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        fetch(`{{ url('/admin/pending-orders') }}/${orderId}`, {
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