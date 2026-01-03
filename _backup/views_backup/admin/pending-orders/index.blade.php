@extends('admin.layouts.app')

@section('title', 'Pending Orders Management')
@section('page-title', 'Pending Orders Management')

@section('content')
<h1 class="page-title">Pending Orders Management</h1>

<!-- Search and Refresh Bar -->
<div class="search-refresh-bar">
    <div class="search-box">
        <i class="fas fa-search search-icon"></i>
        <input type="text" placeholder="Search orders..." id="order-search" value="{{ request('search') }}">
    </div>
    <button class="refresh-btn" onclick="window.location.reload()" title="Refresh">
        <i class="fas fa-sync-alt"></i>
    </button>
</div>

<!-- Manual Orders Section -->
<div class="manual-orders-section">
    <div class="section-header">
        <div class="section-title">
            <i class="fas fa-user"></i>
            <span>Manual Orders</span>
            <small>Orders created manually by admin staff</small>
        </div>
        <div class="section-actions">
            <div class="orders-count">
                <span class="count-badge">{{ $manualOrdersCount ?? 7 }} Orders</span>
            </div>
            <a href="{{ route('admin.rejected-orders.index') }}" class="btn btn-danger">
                <i class="fas fa-times-circle"></i>
                Rejected Orders
            </a>
            <a href="{{ route('admin.pending-orders.create-bulk') }}" class="btn btn-secondary">
                <i class="fas fa-plus"></i>
                Add Bulk Orders
            </a>
            <a href="{{ route('admin.pending-orders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Add Order
            </a>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulk-actions-container">
        <div class="bulk-select">
            <input type="checkbox" class="bulk-checkbox" id="select-all-orders">
            <label for="select-all-orders" class="bulk-text">Select items for bulk actions</label>
        </div>
        <div class="bulk-action-buttons" style="display: none;">
            <button class="btn btn-sm btn-success" onclick="bulkConfirm()">
                <i class="fas fa-check"></i> Confirm
            </button>
            <button class="btn btn-sm btn-danger" onclick="bulkReject()">
                <i class="fas fa-times"></i> Reject
            </button>
            <button class="btn btn-sm btn-secondary" onclick="bulkDelete()">
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
                <div class="col-address"><strong>Address</strong></div>
                <div class="col-amount"><strong>Amount</strong></div>
                <div class="col-products"><strong>Products</strong></div>
                <div class="col-actions"><strong>Actions</strong></div>
            </div>
        </div>
        
        <div class="table-body">
            @forelse($orders as $order)
            <div class="table-row" data-order-id="{{ $order->id }}">
                <div class="table-grid">
                    <!-- Checkbox -->
                    <div class="col-checkbox">
                        <input type="checkbox" class="bulk-checkbox order-checkbox" value="{{ $order->id }}">
                    </div>
                    
                    <!-- Order ID -->
                    <div class="col-order-id">
                        <div class="order-id-info">
                            <span class="order-number">{{ $order->order_number }}</span>
                            <span class="manual-tag">+ Manual</span>
                        </div>
                    </div>
                    
                    <!-- Customer -->
                    <div class="col-customer">
                        <div class="customer-info">
                            <div class="customer-name">{{ $order->user->name ?? 'N/A' }}</div>
                            <div class="customer-phone">{{ $order->user->phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                    
                    <!-- Address -->
                    <div class="col-address">
                        <div class="address-info">
                            <div class="address-line">{{ $order->shipping_address ?? 'Not specified' }}</div>
                            <div class="address-detail">Not specified</div>
                        </div>
                    </div>
                    
                    <!-- Amount -->
                    <div class="col-amount">
                        <div class="amount-info">
                            <div class="amount-paid">Paid: Rs. {{ $order->payment_status === 'paid' ? number_format($order->total, 0) : '0' }}</div>
                            <div class="amount-cod">COD: Rs. {{ $order->payment_status === 'unpaid' ? number_format($order->total, 0) : '0' }}</div>
                            <div class="amount-total">Total: Rs. {{ number_format($order->total, 0) }}</div>
                        </div>
                    </div>
                    
                    <!-- Products -->
                    <div class="col-products">
                        @if($order->orderItems->count() > 0)
                            <span class="products-count">{{ $order->orderItems->count() }} products</span>
                        @else
                            <span class="no-products">No products</span>
                        @endif
                    </div>
                    
                    <!-- Actions -->
                    <div class="col-actions">
                        <div class="action-buttons">
                            <button class="action-btn confirm" onclick="confirmOrder({{ $order->id }})" title="Confirm Order">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="action-btn reject" onclick="rejectOrder({{ $order->id }})" title="Reject Order">
                                <i class="fas fa-times"></i>
                            </button>
                            <a href="{{ route('admin.pending-orders.edit', $order) }}" class="action-btn edit" title="Edit Order">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <button class="action-btn delete" onclick="deleteOrder({{ $order->id }})" title="Delete Order">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>No Pending Orders</h3>
                <p>There are no pending manual orders at the moment.</p>
                <a href="{{ route('admin.pending-orders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Your First Order
                </a>
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

@push('styles')
<style>
/* Action Column Styling */
.col-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 140px;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    flex-wrap: wrap;
}

.action-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.action-btn i {
    font-size: 12px;
}

.action-btn.confirm {
    background: linear-gradient(135deg, #10B981, #059669);
    color: white;
    border: 2px solid #059669;
}

.action-btn.confirm:hover {
    background: linear-gradient(135deg, #059669, #047857);
    border-color: #047857;
}

.action-btn.reject {
    background: linear-gradient(135deg, #EF4444, #DC2626);
    color: white;
    border: 2px solid #DC2626;
}

.action-btn.reject:hover {
    background: linear-gradient(135deg, #DC2626, #B91C1C);
    border-color: #B91C1C;
}

.action-btn.edit {
    background: linear-gradient(135deg, #F59E0B, #D97706);
    color: white;
    border: 2px solid #D97706;
}

.action-btn.edit:hover {
    background: linear-gradient(135deg, #D97706, #B45309);
    border-color: #B45309;
}

.action-btn.delete {
    background: linear-gradient(135deg, #EF4444, #DC2626);
    color: white;
    border: 2px solid #DC2626;
}

.action-btn.delete:hover {
    background: linear-gradient(135deg, #DC2626, #B91C1C);
    border-color: #B91C1C;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .col-actions {
        min-width: 100px;
        padding: 0.5rem;
    }
    
    .action-btn {
        width: 28px;
        height: 28px;
        font-size: 12px;
    }
    
    .action-btn i {
        font-size: 10px;
    }
    
    .action-buttons {
        gap: 0.25rem;
    }
}
</style>
@endpush

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
                window.location.href = `{{ route('admin.pending-orders.index') }}?search=${encodeURIComponent(searchTerm)}`;
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
    fetch('{{ url('/admin/pending-orders/bulk-action') }}', {
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
