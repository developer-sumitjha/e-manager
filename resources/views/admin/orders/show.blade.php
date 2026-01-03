@extends('admin.layouts.app')

@section('title', 'Order Details')
@section('page-title', 'Order Details')
@section('page-subtitle', 'View and manage order information')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.orders.index') }}" class="breadcrumb-link">Orders</a>
    </div>
    <div class="breadcrumb-item active">Order #{{ $order->order_number }}</div>
@endsection

@section('content')
<div class="row">
    <!-- Order Information -->
    <div class="col-xl-8 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Order #{{ $order->order_number }}</h5>
                        <small class="text-muted">Created {{ $order->created_at->format('M j, Y \a\t g:i A') }}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'info')) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <span class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'refunded' ? 'warning' : 'danger') }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Order Items -->
                <h6 class="fw-semibold mb-3">Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->primary_image_url)
                                            <img src="{{ $item->product->primary_image_url }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="product-thumb me-3">
                                        @else
                                            <div class="product-thumb me-3">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $item->product_name ?? $item->product->name ?? 'N/A' }}</div>
                                            @if($item->product)
                                                <small class="text-muted">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>Rs. {{ number_format($item->price, 2) }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $item->quantity }}</span>
                                </td>
                                <td class="fw-semibold">Rs. {{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-semibold">Subtotal:</td>
                                <td class="fw-semibold">Rs. {{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            @if($order->tax > 0)
                            <tr>
                                <td colspan="3" class="text-end">Tax:</td>
                                <td>Rs. {{ number_format($order->tax, 2) }}</td>
                            </tr>
                            @endif
                            @if($order->shipping > 0)
                            <tr>
                                <td colspan="3" class="text-end">Shipping:</td>
                                <td>Rs. {{ number_format($order->shipping, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="table-active">
                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                <td class="fw-bold">Rs. {{ number_format($order->total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Notes -->
        @if($order->notes)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Order Notes</h6>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $order->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar Information -->
    <div class="col-xl-4">
        <!-- Customer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Customer Information</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-lg me-3">
                        <div class="avatar-initials">
                            {{ substr($order->user->name ?? 'G', 0, 1) }}
                        </div>
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $order->user->name ?? 'Guest' }}</div>
                        <small class="text-muted">{{ $order->user->email ?? 'N/A' }}</small>
                    </div>
                </div>
                
                <div class="row g-2">
                    <div class="col-6">
                        <small class="text-muted">Phone</small>
                        <div class="fw-semibold">{{ $order->user->phone ?? 'N/A' }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Role</small>
                        <div class="fw-semibold">{{ ucfirst($order->user->role ?? 'Guest') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Order Details</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <small class="text-muted">Order Date</small>
                        <div class="fw-semibold">{{ $order->created_at->format('M j, Y H:i') }}</div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">Last Updated</small>
                        <div class="fw-semibold">{{ $order->updated_at->format('M j, Y H:i') }}</div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">Payment Method</small>
                        <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</div>
                    </div>
                    @if($order->is_manual)
                    <div class="col-12">
                        <small class="text-muted">Order Type</small>
                        <div class="fw-semibold">
                            <span class="badge badge-info">Manual Order</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Shipping Address -->
        @if($order->shipping_address || $order->receiver_name)
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Shipping Address</h6>
            </div>
            <div class="card-body">
                @if($order->receiver_name)
                <div class="fw-semibold mb-2">{{ $order->receiver_name }}</div>
                @endif
                @if($order->receiver_phone)
                <div class="text-muted mb-2">{{ $order->receiver_phone }}</div>
                @endif
                @if($order->shipping_address)
                <div class="text-muted">{{ $order->shipping_address }}</div>
                @endif
                @if($order->receiver_city)
                <div class="text-muted">{{ $order->receiver_city }}{{ $order->receiver_area ? ', ' . $order->receiver_area : '' }}</div>
                @endif
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Order
                    </a>
                    <button class="btn btn-outline-primary" onclick="printOrder()">
                        <i class="fas fa-print"></i> Print Order
                    </button>
                    <button class="btn btn-outline-secondary" onclick="exportOrder()">
                        <i class="fas fa-download"></i> Export PDF
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-outline-info dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-cog"></i> Change Status
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="changeStatus('confirmed')">Confirm</a></li>
                            <li><a class="dropdown-item" href="#" onclick="changeStatus('processing')">Processing</a></li>
                            <li><a class="dropdown-item" href="#" onclick="changeStatus('shipped')">Shipped</a></li>
                            <li><a class="dropdown-item" href="#" onclick="changeStatus('completed')">Delivered</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="changeStatus('cancelled')">Cancel</a></li>
                        </ul>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Product Thumbnail */
.product-thumb {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-md);
    overflow: hidden;
    background: rgba(30, 41, 59, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.product-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Avatar */
.avatar-lg {
    width: 48px;
    height: 48px;
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
    font-size: 1.125rem;
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

/* Table Active Row */
.table-active {
    background: rgba(99, 102, 241, 0.05) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .product-thumb {
        width: 40px;
        height: 40px;
    }
    
    .avatar-lg {
        width: 40px;
        height: 40px;
    }
    
    .avatar-initials {
        font-size: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
function changeStatus(status) {
    if (confirm(`Are you sure you want to change this order status to ${status}?`)) {
        fetch(`{{ url('/admin/orders') }}/{{ $order->id }}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: status,
                payment_status: '{{ $order->payment_status }}'
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

function printOrder() {
    window.print();
}

function exportOrder() {
    // Implementation for PDF export
    window.open(`{{ route('admin.orders.show', $order) }}?export=pdf`, '_blank');
}
</script>

<script src="{{ asset('js/orders.js') }}"></script>
@endpush