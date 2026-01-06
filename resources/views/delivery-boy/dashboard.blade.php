@extends('delivery-boy.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }

    .stat-card.today::before { background: #8B5CF6; }
    .stat-card.completed::before { background: #10B981; }
    .stat-card.pending::before { background: #F59E0B; }
    .stat-card.earnings::before { background: #3B82F6; }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stat-icon.today { background: rgba(139, 92, 246, 0.1); color: #8B5CF6; }
    .stat-icon.completed { background: rgba(16, 185, 129, 0.1); color: #10B981; }
    .stat-icon.pending { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .stat-icon.earnings { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0;
    }

    .stat-label {
        color: #6B7280;
        margin: 0.5rem 0 0 0;
        font-size: 0.9rem;
    }

    .section-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #E5E7EB;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0;
    }

    .delivery-item {
        padding: 1rem;
        border-bottom: 1px solid #E5E7EB;
        transition: all 0.3s ease;
    }

    .delivery-item:last-child {
        border-bottom: none;
    }

    .delivery-item:hover {
        background: #F9FAFB;
    }

    .delivery-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }

    .order-number {
        font-weight: 700;
        color: #1F2937;
        font-size: 1.05rem;
    }

    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.pending { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .status-badge.picked_up { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
    .status-badge.delivered { background: rgba(16, 185, 129, 0.1); color: #10B981; }

    .delivery-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        font-size: 0.9rem;
        color: #6B7280;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-item i {
        width: 16px;
        color: #8B5CF6;
    }

    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .action-btn.primary {
        background: #8B5CF6;
        color: white;
    }

    .action-btn.primary:hover {
        background: #7C3AED;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6B7280;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #D1D5DB;
    }
</style>
@endpush

@section('content')
<!-- Today's Stats -->
<div class="stats-grid">
    <div class="stat-card today">
        <div class="stat-icon today">
            <i class="fas fa-boxes"></i>
        </div>
        <h3 class="stat-value">{{ $stats['total_deliveries'] ?? 0 }}</h3>
        <p class="stat-label">Total Deliveries</p>
    </div>

    <div class="stat-card completed">
        <div class="stat-icon completed">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3 class="stat-value">{{ $stats['completed_today'] ?? 0 }}</h3>
        <p class="stat-label">Completed Today</p>
    </div>

    <div class="stat-card pending">
        <div class="stat-icon pending">
            <i class="fas fa-clock"></i>
        </div>
        <h3 class="stat-value">{{ $stats['pending_deliveries'] ?? 0 }}</h3>
        <p class="stat-label">Pending Deliveries</p>
    </div>

    <div class="stat-card earnings">
        <div class="stat-icon earnings">
            <i class="fas fa-star"></i>
        </div>
        <h3 class="stat-value">{{ $stats['rating'] ?? 0 }}/5.0</h3>
        <p class="stat-label">Your Rating</p>
    </div>
</div>

<!-- Pending Deliveries -->
<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-box"></i> Pending Deliveries
        </h2>
        <a href="{{ route('delivery-boy.deliveries') }}" class="action-btn primary">
            View All <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    @forelse($assignedDeliveries as $delivery)
    <div class="delivery-item">
        <div class="delivery-header">
            <div>
                <div class="order-number">{{ $delivery->order->order_number ?? 'N/A' }}</div>
            </div>
            <span class="status-badge {{ $delivery->status }}">{{ ucfirst($delivery->status) }}</span>
        </div>

        <div class="delivery-details">
            <div class="detail-item">
                <i class="fas fa-user"></i>
                <span>{{ $delivery->order->user->name ?? 'N/A' }}</span>
            </div>
            <div class="detail-item">
                <i class="fas fa-phone"></i>
                <span>{{ $delivery->order->user->phone ?? 'N/A' }}</span>
            </div>
            <div class="detail-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ Str::limit($delivery->delivery_address, 30) }}</span>
            </div>
            <div class="detail-item">
                <i class="fas fa-money-bill"></i>
                <span>COD: Rs. {{ number_format($delivery->cod_amount ?? 0, 0) }}</span>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('delivery-boy.delivery-details', $delivery->id) }}" class="action-btn primary">
                <i class="fas fa-eye"></i> View Details
            </a>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h3>No Pending Deliveries</h3>
        <p>You don't have any pending deliveries at the moment.</p>
    </div>
    @endforelse
</div>

<!-- Recent Deliveries -->
<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-history"></i> Recent Deliveries
        </h2>
    </div>

    @forelse($recentCompletedDeliveries->take(5) as $delivery)
    <div class="delivery-item">
        <div class="delivery-header">
            <div class="order-number">{{ $delivery->order->order_number ?? 'N/A' }}</div>
            <span class="status-badge {{ $delivery->status }}">{{ ucfirst($delivery->status) }}</span>
        </div>

        <div class="delivery-details">
            <div class="detail-item">
                <i class="fas fa-calendar"></i>
                <span>{{ $delivery->assigned_at->format('M d, Y') }}</span>
            </div>
            <div class="detail-item">
                <i class="fas fa-user"></i>
                <span>{{ $delivery->order->user->name ?? 'N/A' }}</span>
            </div>
            <div class="detail-item">
                <i class="fas fa-money-bill-wave"></i>
                <span>Rs. {{ number_format($delivery->cod_amount ?? 0, 0) }}</span>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-history"></i>
        <h3>No Recent Deliveries</h3>
        <p>No delivery history available.</p>
    </div>
    @endforelse
</div>

<!-- Overall Performance -->
<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-chart-line"></i> Overall Performance
        </h2>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $deliveryBoy->total_deliveries ?? 0 }}</div>
            <p class="stat-label">Total Deliveries</p>
        </div>

        <div class="stat-card">
            <div class="stat-value">{{ $deliveryBoy->successful_deliveries ?? 0 }}</div>
            <p class="stat-label">Completed</p>
        </div>

        <div class="stat-card">
            <div class="stat-value">{{ $stats['success_rate'] ?? 0 }}%</div>
            <p class="stat-label">Success Rate</p>
        </div>

        <div class="stat-card">
            <div class="stat-value">Rs. {{ number_format($stats['pending_cod'] ?? 0, 0) }}</div>
            <p class="stat-label">Pending COD</p>
        </div>
    </div>
</div>
@endsection
