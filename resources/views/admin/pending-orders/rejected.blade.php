@extends('admin.layouts.app')

@section('title', 'Rejected Orders')
@section('page-title', 'Rejected Orders')

@push('styles')
<style>
    /* Rejected Orders Specific Styles */
    .rejected-orders-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title-section h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #EF4444;
        margin: 0;
        background: linear-gradient(135deg, #EF4444, #F87171);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-top: 0.5rem;
        font-weight: 400;
    }

    .back-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: rgba(107, 114, 128, 0.1);
        color: #6B7280;
        text-decoration: none;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid rgba(107, 114, 128, 0.2);
    }

    .back-btn:hover {
        background: rgba(107, 114, 128, 0.2);
        color: #6B7280;
        transform: translateY(-2px);
    }

    .search-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        align-items: center;
    }

    .search-box {
        position: relative;
        flex: 1;
        max-width: 400px;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        z-index: 2;
    }

    .search-box input {
        padding-left: 3rem;
        border-radius: 0.75rem;
        border: 1px solid rgba(239, 68, 68, 0.2);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        border-color: #EF4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        background: white;
    }

    .stats-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        background: rgba(239, 68, 68, 0.1);
        border-radius: 0.75rem;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .stats-badge i {
        color: #EF4444;
        font-size: 1.25rem;
    }

    .stats-text {
        display: flex;
        flex-direction: column;
    }

    .stats-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .stats-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #EF4444;
    }

    .rejected-orders-table {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(239, 68, 68, 0.1);
        backdrop-filter: blur(10px);
    }

    .table-grid {
        display: grid;
        grid-template-columns: auto 1fr 1fr 1fr 120px 150px auto;
        gap: 1rem;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid rgba(239, 68, 68, 0.05);
    }

    .table-header {
        background: rgba(239, 68, 68, 0.05);
        border-radius: 0.75rem;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        margin-bottom: 0.5rem;
    }

    .table-row {
        transition: all 0.3s ease;
        border-radius: 0.75rem;
    }

    .table-row:hover {
        background: rgba(239, 68, 68, 0.02);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.05);
    }

    .order-id-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .order-number {
        font-weight: 600;
        color: var(--text-primary);
    }

    .rejected-tag {
        display: inline-block;
        padding: 0.125rem 0.5rem;
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
        border-radius: 0.25rem;
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .customer-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .customer-name {
        font-weight: 600;
        color: var(--text-primary);
    }

    .customer-phone {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .address-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .address-line {
        color: var(--text-secondary);
        font-size: 0.875rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .amount-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .amount-total {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .rejected-date {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        cursor: pointer;
    }

    .action-btn.view {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
        border-color: rgba(59, 130, 246, 0.2);
    }

    .action-btn.view:hover {
        background: rgba(59, 130, 246, 0.2);
        color: #3B82F6;
    }

    .action-btn.delete {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
        border-color: rgba(239, 68, 68, 0.2);
    }

    .action-btn.delete:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #EF4444;
    }

    .no-orders {
        text-align: center;
        padding: 4rem 2rem;
    }

    .no-orders i {
        font-size: 3rem;
        color: rgba(239, 68, 68, 0.3);
        margin-bottom: 1rem;
    }

    .no-orders h5 {
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .no-orders p {
        color: var(--text-secondary);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .rejected-orders-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .search-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            max-width: none;
        }

        .table-grid {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .table-header {
            display: none;
        }

        .table-row {
            padding: 1rem;
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(239, 68, 68, 0.1);
        }
    }
</style>
@endpush

@section('content')
<div class="rejected-orders-header">
    <div class="page-title-section">
        <h1>Rejected Orders</h1>
        <p class="page-subtitle">Orders that have been rejected</p>
    </div>
    <a href="{{ route('admin.pending-orders.index') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Pending Orders
    </a>
</div>

<div class="search-bar">
    <div class="search-box">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="order-search" class="form-control" placeholder="Search by order number, customer name..." value="{{ request('search') }}">
    </div>

    <div class="stats-badge">
        <i class="fas fa-times-circle"></i>
        <div class="stats-text">
            <span class="stats-label">Total Rejected</span>
            <span class="stats-value">{{ $totalRejectedOrders }}</span>
        </div>
    </div>
</div>

<div class="rejected-orders-table">
    @if($orders->count() > 0)
        <div class="table-grid table-header">
            <div></div>
            <div>Order ID</div>
            <div>Customer</div>
            <div>Address</div>
            <div>Amount</div>
            <div>Rejected Date</div>
            <div>Actions</div>
        </div>

        @foreach($orders as $order)
        <div class="table-grid table-row">
            <div></div>
            <div class="order-id-info">
                <div class="order-number">#{{ $order->order_number }}</div>
                <span class="rejected-tag">Rejected</span>
            </div>
            <div class="customer-info">
                <div class="customer-name">{{ $order->user->name ?? 'Guest' }}</div>
                <div class="customer-phone">{{ $order->user->email ?? 'N/A' }}</div>
            </div>
            <div class="address-info">
                <div class="address-line">{{ Str::limit($order->shipping_address, 50) }}</div>
            </div>
            <div class="amount-info">
                <div class="amount-total">₹{{ number_format($order->total, 2) }}</div>
            </div>
            <div class="rejected-date">
                {{ $order->updated_at->format('M d, Y') }}
            </div>
            <div class="action-buttons">
                <a href="{{ route('admin.pending-orders.show', $order->id) }}" class="action-btn view">
                    <i class="fas fa-eye"></i> View
                </a>
                <button type="button" class="action-btn delete" onclick="deleteOrder({{ $order->id }})">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
        @endforeach
    @else
        <div class="no-orders">
            <i class="fas fa-check-circle"></i>
            <h5>No Rejected Orders</h5>
            <p>There are no rejected orders at the moment.</p>
        </div>
    @endif

    @if($orders->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderSearch = document.getElementById('order-search');

        // Live Search with Debounce
        orderSearch.addEventListener('keyup', debounce(function() {
            const searchValue = this.value;
            window.location.href = `{{ route('admin.rejected-orders.index') }}?search=${searchValue}`;
        }, 300));
    });

    function deleteOrder(orderId) {
        if (confirm('Are you sure you want to delete this rejected order? This action cannot be undone.')) {
            fetch(`/admin/pending-orders/${orderId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    showNotification('Failed to delete order.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while deleting order.', 'error');
            });
        }
    }
</script>
@endpush








