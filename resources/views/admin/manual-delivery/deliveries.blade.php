@extends('admin.layouts.app')

@section('title', 'Manual Deliveries')
@section('page-title', 'Manual Deliveries')

@push('styles')
<style>
    /* Manual Deliveries Page Specific Styles */
    .deliveries-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title-section h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
        background: linear-gradient(135deg, #10B981, #34D399);
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

    .search-add-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        gap: 1rem;
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
        border: 1px solid rgba(16, 185, 129, 0.2);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        border-color: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        background: white;
    }

    .add-delivery-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #10B981, #34D399);
        color: white;
        text-decoration: none;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .add-delivery-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        background: rgba(255, 255, 255, 0.8);
        padding: 0.5rem;
        border-radius: 1rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        overflow-x: auto;
    }

    .filter-tab {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 0.75rem;
        text-decoration: none;
        color: var(--text-secondary);
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .filter-tab:hover {
        color: #10B981;
        background: rgba(16, 185, 129, 0.05);
    }

    .filter-tab.active {
        color: white;
        background: linear-gradient(135deg, #10B981, #34D399);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .deliveries-table {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        overflow-x: auto;
    }

    .table {
        margin: 0;
    }

    .table th {
        border: none;
        background: rgba(16, 185, 129, 0.05);
        color: var(--text-primary);
        font-weight: 600;
        padding: 1rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .table td {
        border: none;
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(16, 185, 129, 0.05);
    }

    .table tbody tr:hover {
        background: rgba(16, 185, 129, 0.02);
    }

    .tracking-number {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: var(--primary-color);
    }

    .delivery-boy-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .delivery-boy-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #10B981, #34D399);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.875rem;
    }

    .delivery-boy-details h6 {
        margin: 0 0 0.25rem 0;
        font-weight: 600;
        color: var(--text-primary);
    }

    .delivery-boy-details p {
        margin: 0;
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .order-info {
        display: flex;
        flex-direction: column;
    }

    .order-number {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .customer-name {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .delivery-fee {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }

    .status-picked_up {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
    }

    .status-in_transit {
        background: rgba(139, 92, 246, 0.1);
        color: #8B5CF6;
    }

    .status-delivered {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .status-failed {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .status-returned {
        background: rgba(107, 114, 128, 0.1);
        color: #6B7280;
    }

    .rating-stars {
        color: #FCD34D;
        font-size: 0.875rem;
    }

    .delivery-date {
        font-size: 0.875rem;
        color: var(--text-secondary);
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
        margin: 0.125rem;
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

    .action-btn.edit {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
        border-color: rgba(245, 158, 11, 0.2);
    }

    .action-btn.edit:hover {
        background: rgba(245, 158, 11, 0.2);
        color: #F59E0B;
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .search-add-bar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-box {
            max-width: none;
        }
        
        .filter-tabs {
            flex-wrap: wrap;
        }
        
        .delivery-boy-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="deliveries-header">
    <div class="page-title-section">
        <h1>Manual Deliveries</h1>
        <p class="page-subtitle">Track and manage in-house deliveries</p>
    </div>
</div>

<div class="search-add-bar">
    <div class="search-box">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="deliveries-search" class="form-control" placeholder="Search deliveries..." value="{{ request('search') }}">
    </div>
    <a href="#" class="add-delivery-btn">
        <i class="fas fa-plus"></i> Add Delivery
    </a>
</div>

<div class="filter-tabs">
    <a href="{{ route('admin.manual-delivery.deliveries', ['status' => '', 'search' => request('search')]) }}" class="filter-tab {{ request('status') == '' ? 'active' : '' }}">
        <span>All</span>
    </a>
    <a href="{{ route('admin.manual-delivery.deliveries', ['status' => 'pending', 'search' => request('search')]) }}" class="filter-tab {{ request('status') == 'pending' ? 'active' : '' }}">
        <span>Pending</span>
    </a>
    <a href="{{ route('admin.manual-delivery.deliveries', ['status' => 'picked_up', 'search' => request('search')]) }}" class="filter-tab {{ request('status') == 'picked_up' ? 'active' : '' }}">
        <span>Picked Up</span>
    </a>
    <a href="{{ route('admin.manual-delivery.deliveries', ['status' => 'in_transit', 'search' => request('search')]) }}" class="filter-tab {{ request('status') == 'in_transit' ? 'active' : '' }}">
        <span>In Transit</span>
    </a>
    <a href="{{ route('admin.manual-delivery.deliveries', ['status' => 'delivered', 'search' => request('search')]) }}" class="filter-tab {{ request('status') == 'delivered' ? 'active' : '' }}">
        <span>Delivered</span>
    </a>
    <a href="{{ route('admin.manual-delivery.deliveries', ['status' => 'failed', 'search' => request('search')]) }}" class="filter-tab {{ request('status') == 'failed' ? 'active' : '' }}">
        <span>Failed</span>
    </a>
    <a href="{{ route('admin.manual-delivery.deliveries', ['status' => 'returned', 'search' => request('search')]) }}" class="filter-tab {{ request('status') == 'returned' ? 'active' : '' }}">
        <span>Returned</span>
    </a>
</div>

<div class="deliveries-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tracking #</th>
                    <th>Delivery Boy</th>
                    <th>Order</th>
                    <th>COD Amount</th>
                    <th>Status</th>
                    <th>Rating</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $delivery)
                <tr>
                    <td>
                        <div class="tracking-number">{{ $delivery->tracking_number }}</div>
                    </td>
                    <td>
                        <div class="delivery-boy-info">
                            <div class="delivery-boy-avatar">
                                {{ substr($delivery->deliveryBoy->name, 0, 1) }}
                            </div>
                            <div class="delivery-boy-details">
                                <h6>{{ $delivery->deliveryBoy->name }}</h6>
                                <p>{{ $delivery->deliveryBoy->phone }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="order-info">
                            <div class="order-number">{{ $delivery->order->order_number }}</div>
                            <div class="customer-name">{{ $delivery->order->user->name ?? 'N/A' }}</div>
                        </div>
                    </td>
                    <td>
                        @php
                            $order = $delivery->order;
                            $isCod = $order && in_array($order->payment_method, ['cod', 'cash_on_delivery']);
                            $codAmount = $isCod ? ($order->total ?? 0) : 0;
                        @endphp
                        @if($isCod)
                            <div class="delivery-fee text-danger">₹{{ number_format($codAmount, 2) }}</div>
                        @else
                            <div class="delivery-fee text-muted">N/A</div>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge status-{{ $delivery->status }}">
                            {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                        </span>
                    </td>
                    <td>
                        @if($delivery->rating)
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $delivery->rating ? '' : '-o' }}"></i>
                                @endfor
                            </div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="delivery-date">{{ $delivery->created_at->format('M d, Y') }}</div>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap">
                            <a href="{{ route('admin.manual-delivery.show', $delivery) }}" class="action-btn view">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('admin.manual-delivery.edit', $delivery) }}" class="action-btn edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button" class="action-btn delete" onclick="deleteDelivery({{ $delivery->id }})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-truck text-muted" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <h5 class="text-muted">No deliveries found</h5>
                        <p class="text-muted">Manual deliveries will appear here.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($deliveries->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $deliveries->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deliveriesSearch = document.getElementById('deliveries-search');

        // Live Search with Debounce
        deliveriesSearch.addEventListener('keyup', debounce(function() {
            const searchValue = this.value;
            const currentStatus = new URLSearchParams(window.location.search).get('status') || '';
            window.location.href = `{{ route('admin.manual-delivery.deliveries') }}?search=${searchValue}&status=${currentStatus}`;
        }, 300));
    });

    function deleteDelivery(deliveryId) {
        if (confirm('Are you sure you want to delete this delivery? This action cannot be undone.')) {
            fetch(`/admin/manual-delivery/${deliveryId}`, {
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
                    showNotification('Failed to delete delivery.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while deleting delivery.', 'error');
            });
        }
    }
</script>
@endpush







