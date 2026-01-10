@extends('admin.layouts.app')

@section('title', 'Trash - Pending Orders')
@section('page-title', 'Trash - Pending Orders')
@section('page-subtitle', 'Deleted orders that can be restored or permanently deleted')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.orders.index') }}" class="breadcrumb-link">Orders</a>
    </div>
    <div class="breadcrumb-item">
        <a href="{{ route('admin.pending-orders.index') }}" class="breadcrumb-link">Pending Orders</a>
    </div>
    <div class="breadcrumb-item active">Trash</div>
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
                           placeholder="Search deleted orders..." 
                           id="order-search" 
                           value="{{ request('search') }}"
                           class="search-input">
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('admin.pending-orders.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Back to Pending Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Deleted Orders (Trash)</h5>
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
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Amount</th>
                        <th>Products</th>
                        <th>Deleted At</th>
                        <th width="200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr data-order-id="{{ $order->id }}">
                        <td>
                            <div class="d-flex flex-column">
                                <span class="text-decoration-none fw-semibold">
                                    {{ $order->order_number }}
                                </span>
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
                            <div>
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
                            <div class="text-muted">
                                <small>{{ $order->deleted_at->format('M d, Y') }}</small><br>
                                <small>{{ $order->deleted_at->format('h:i A') }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-success restore-order-btn" 
                                        data-order-id="{{ $order->id }}"
                                        data-order-number="{{ $order->order_number }}"
                                        title="Restore Order">
                                    <i class="fas fa-undo"></i> Restore
                                </button>
                                <button class="btn btn-sm btn-danger force-delete-order-btn" 
                                        data-order-id="{{ $order->id }}"
                                        data-order-number="{{ $order->order_number }}"
                                        title="Permanently Delete">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-trash-alt fa-3x text-muted mb-3"></i>
                                <h5>No deleted orders found</h5>
                                <p class="text-muted">Trash is empty. Deleted orders will appear here.</p>
                                <a href="{{ route('admin.pending-orders.index') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Back to Pending Orders
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('order-search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const search = this.value;
                const url = new URL(window.location.href);
                if (search) {
                    url.searchParams.set('search', search);
                } else {
                    url.searchParams.delete('search');
                }
                window.location.href = url.toString();
            }, 500);
        });
    }

    // Restore order
    document.querySelectorAll('.restore-order-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const orderNumber = this.dataset.orderNumber;
            
            if (!confirm(`Are you sure you want to restore order ${orderNumber}?`)) {
                return;
            }

            const row = this.closest('tr');
            const originalBtn = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Restoring...';

            fetch(`{{ url('admin/pending-orders') }}/${orderId}/restore`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message || 'Order restored successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route('admin.pending-orders.index') }}';
                    }, 1500);
                } else {
                    showNotification(data.message || 'Failed to restore order.', 'error');
                    this.disabled = false;
                    this.innerHTML = originalBtn;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while restoring the order.', 'error');
                this.disabled = false;
                this.innerHTML = originalBtn;
            });
        });
    });

    // Permanently delete order
    document.querySelectorAll('.force-delete-order-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const orderNumber = this.dataset.orderNumber;
            
            if (!confirm(`Are you sure you want to PERMANENTLY DELETE order ${orderNumber}? This action cannot be undone!`)) {
                return;
            }

            const row = this.closest('tr');
            const originalBtn = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';

            fetch(`{{ url('admin/pending-orders') }}/${orderId}/force-delete`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message || 'Order permanently deleted.', 'success');
                    row.style.opacity = '0.5';
                    setTimeout(() => {
                        row.remove();
                        // Check if table is empty
                        const tbody = row.closest('tbody');
                        if (tbody && tbody.querySelectorAll('tr').length === 0) {
                            window.location.reload();
                        }
                    }, 500);
                } else {
                    showNotification(data.message || 'Failed to delete order.', 'error');
                    this.disabled = false;
                    this.innerHTML = originalBtn;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while deleting the order.', 'error');
                this.disabled = false;
                this.innerHTML = originalBtn;
            });
        });
    });

    // Helper function for notifications
    function showNotification(message, type = 'info') {
        // Use AdminDashboard notification if available
        if (window.AdminDashboard && typeof window.AdminDashboard.showNotification === 'function') {
            window.AdminDashboard.showNotification(message, type);
        } else {
            // Fallback to alert
            alert(message);
        }
    }
});
</script>
@endpush

