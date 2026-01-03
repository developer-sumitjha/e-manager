@extends('admin.layouts.app')

@section('title', 'Delivery Activities')
@section('page-title', 'Delivery Activities')

@push('styles')
<style>
    /* Delivery Activities Page Specific Styles */
    .activities-header {
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

    .activity-filters {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-label {
        font-weight: 500;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .filter-select {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid rgba(16, 185, 129, 0.2);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        border-color: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        background: white;
    }

    .activities-timeline {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
    }

    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(16, 185, 129, 0.1);
    }

    .timeline-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .timeline-title i {
        color: #10B981;
    }

    .timeline-date {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 2rem;
        position: relative;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 1rem;
        border: 1px solid rgba(16, 185, 129, 0.05);
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
    }

    .activity-item:last-child {
        margin-bottom: 0;
    }

    .activity-item::before {
        content: '';
        position: absolute;
        left: 2.75rem;
        top: 4rem;
        bottom: -2rem;
        width: 2px;
        background: rgba(16, 185, 129, 0.2);
    }

    .activity-item:last-child::before {
        display: none;
    }

    .activity-icon {
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        flex-shrink: 0;
        z-index: 1;
        position: relative;
    }

    .activity-icon.delivery-created {
        background: linear-gradient(135deg, #3B82F6, #60A5FA);
    }

    .activity-icon.delivery-assigned {
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
    }

    .activity-icon.delivery-picked {
        background: linear-gradient(135deg, #F59E0B, #FBBF24);
    }

    .activity-icon.delivery-transit {
        background: linear-gradient(135deg, #06B6D4, #22D3EE);
    }

    .activity-icon.delivery-delivered {
        background: linear-gradient(135deg, #10B981, #34D399);
    }

    .activity-icon.delivery-failed {
        background: linear-gradient(135deg, #EF4444, #F87171);
    }

    .activity-icon.delivery-returned {
        background: linear-gradient(135deg, #6B7280, #9CA3AF);
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .activity-description {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: 0.75rem;
        line-height: 1.5;
    }

    .activity-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .activity-delivery-boy {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .activity-delivery-boy i {
        color: #10B981;
    }

    .activity-time {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .activity-order {
        font-size: 0.875rem;
        color: var(--primary-color);
        font-weight: 500;
        text-decoration: none;
    }

    .activity-order:hover {
        color: var(--primary-color);
        text-decoration: underline;
    }

    .activity-status {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .activity-status.pending {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }

    .activity-status.picked_up {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
    }

    .activity-status.in_transit {
        background: rgba(139, 92, 246, 0.1);
        color: #8B5CF6;
    }

    .activity-status.delivered {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .activity-status.failed {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .activity-status.returned {
        background: rgba(107, 114, 128, 0.1);
        color: #6B7280;
    }

    .no-activities {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .no-activities i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: rgba(16, 185, 129, 0.3);
    }

    .no-activities h5 {
        margin-bottom: 0.5rem;
        color: var(--text-secondary);
    }

    .load-more-btn {
        display: block;
        margin: 2rem auto 0;
        padding: 0.75rem 2rem;
        background: linear-gradient(135deg, #10B981, #34D399);
        color: white;
        border: none;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .load-more-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .activity-filters {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-group {
            justify-content: space-between;
        }
        
        .activity-item {
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 1rem;
        }
        
        .activity-item::before {
            display: none;
        }
        
        .activity-meta {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="activities-header">
    <div class="page-title-section">
        <h1>Delivery Activities</h1>
        <p class="page-subtitle">Track all delivery activities and updates</p>
    </div>
</div>

<div class="activity-filters">
    <div class="filter-group">
        <label class="filter-label">Delivery Boy:</label>
        <select class="filter-select" id="delivery-boy-filter">
            <option value="">All Delivery Boys</option>
            @foreach($deliveryBoys as $boy)
                <option value="{{ $boy->id }}" {{ request('delivery_boy') == $boy->id ? 'selected' : '' }}>
                    {{ $boy->name }}
                </option>
            @endforeach
        </select>
    </div>
    
    <div class="filter-group">
        <label class="filter-label">Status:</label>
        <select class="filter-select" id="status-filter">
            <option value="">All Statuses</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="picked_up" {{ request('status') == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
            <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
        </select>
    </div>
    
    <div class="filter-group">
        <label class="filter-label">Date Range:</label>
        <select class="filter-select" id="date-range-filter">
            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
            <option value="all" {{ request('date_range') == 'all' ? 'selected' : '' }}>All Time</option>
        </select>
    </div>
</div>

<div class="activities-timeline">
    <div class="timeline-header">
        <h3 class="timeline-title">
            <i class="fas fa-history"></i>
            Activity Timeline
        </h3>
        <span class="timeline-date">{{ now()->format('M d, Y') }}</span>
    </div>
    
    @forelse($activities as $activity)
    <div class="activity-item">
        <div class="activity-icon activity-{{ $activity['type'] }}">
            <i class="{{ $activity['icon'] }}"></i>
        </div>
        <div class="activity-content">
            <div class="activity-title">{{ $activity['title'] }}</div>
            <div class="activity-description">{{ $activity['description'] }}</div>
            <div class="activity-meta">
                <div class="activity-delivery-boy">
                    <i class="fas fa-user"></i>
                    <span>{{ $activity['delivery_boy'] }}</span>
                </div>
                <a href="#" class="activity-order">{{ $activity['order_number'] }}</a>
                <span class="activity-status activity-status-{{ $activity['status'] }}">
                    {{ ucfirst(str_replace('_', ' ', $activity['status'])) }}
                </span>
                <span class="activity-time">{{ $activity['time'] }}</span>
            </div>
        </div>
    </div>
    @empty
    <div class="no-activities">
        <i class="fas fa-clock"></i>
        <h5>No activities found</h5>
        <p>Delivery activities will appear here as they happen.</p>
    </div>
    @endforelse
    
    @if($activities->count() >= 20)
    <button class="load-more-btn" onclick="loadMoreActivities()">
        <i class="fas fa-plus"></i> Load More Activities
    </button>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deliveryBoyFilter = document.getElementById('delivery-boy-filter');
        const statusFilter = document.getElementById('status-filter');
        const dateRangeFilter = document.getElementById('date-range-filter');

        // Filter change handlers
        [deliveryBoyFilter, statusFilter, dateRangeFilter].forEach(filter => {
            filter.addEventListener('change', function() {
                applyFilters();
            });
        });
    });

    function applyFilters() {
        const deliveryBoy = document.getElementById('delivery-boy-filter').value;
        const status = document.getElementById('status-filter').value;
        const dateRange = document.getElementById('date-range-filter').value;
        
        const params = new URLSearchParams();
        if (deliveryBoy) params.append('delivery_boy', deliveryBoy);
        if (status) params.append('status', status);
        if (dateRange) params.append('date_range', dateRange);
        
        window.location.href = `{{ route('admin.manual-delivery.activities') }}?${params.toString()}`;
    }

    function loadMoreActivities() {
        const button = document.querySelector('.load-more-btn');
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        button.disabled = true;
        
        // Simulate loading more activities
        setTimeout(() => {
            // In a real implementation, this would fetch more data from the server
            showNotification('More activities loaded successfully.', 'success');
            button.innerHTML = '<i class="fas fa-plus"></i> Load More Activities';
            button.disabled = false;
        }, 1500);
    }
</script>
@endpush







