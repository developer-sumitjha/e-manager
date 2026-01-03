@extends('admin.layouts.app')

@section('title', 'Notifications & Alerts')
@section('page-title', 'Notifications & Alerts')

@push('styles')
<style>
    .notifications-container {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        backdrop-filter: blur(10px);
    }

    .alert-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.total { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
    .stat-icon.delayed { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .stat-icon.cod { background: rgba(239, 68, 68, 0.1); color: #EF4444; }
    .stat-icon.failed { background: rgba(107, 114, 128, 0.1); color: #6B7280; }

    .stat-content h3 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .stat-content p {
        color: var(--text-secondary);
        margin: 0;
        font-size: 0.9rem;
    }

    .filters-section {
        background: rgba(139, 92, 246, 0.05);
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
    }

    .filters-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .filter-select {
        padding: 0.75rem;
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 0.5rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .filter-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .notifications-list {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid rgba(229, 231, 235, 0.5);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .notifications-header {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(229, 231, 235, 0.5);
    }

    .notifications-header h3 {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.1rem;
        font-weight: 700;
    }

    .notification-item {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(229, 231, 235, 0.5);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .notification-item:hover {
        background-color: #f8f9fa;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .notification-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-icon {
        width: 40px;
        height: 40px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .alert-icon.delayed { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .alert-icon.cod { background: rgba(239, 68, 68, 0.1); color: #EF4444; }
    .alert-icon.failed { background: rgba(107, 114, 128, 0.1); color: #6B7280; }
    .alert-icon.info { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }

    .notification-content h5 {
        margin: 0;
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 700;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    .notification-body {
        margin-bottom: 1rem;
    }

    .notification-body p {
        margin: 0;
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .notification-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .action-btn.primary {
        background: var(--primary-color);
        color: white;
    }

    .action-btn.secondary {
        background: rgba(107, 114, 128, 0.1);
        color: var(--text-secondary);
        border: 1px solid rgba(107, 114, 128, 0.3);
    }

    .action-btn:hover {
        transform: translateY(-1px);
    }

    .priority-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .priority-badge.high { background: rgba(239, 68, 68, 0.1); color: #EF4444; }
    .priority-badge.medium { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .priority-badge.low { background: rgba(34, 197, 94, 0.1); color: #22C55E; }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--text-secondary);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="notifications-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-bell"></i>
                            Notifications & Alerts
                        </h1>
                        <p class="page-subtitle">Monitor shipment alerts and important notifications</p>
                    </div>
                    <a href="{{ route('admin.gaaubesi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <!-- Alert Statistics -->
                <div class="alert-stats">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon total">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ number_format($alertStats['total_alerts']) }}</h3>
                                <p>Total Alerts</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon delayed">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ number_format($alertStats['delayed_shipments']) }}</h3>
                                <p>Delayed Shipments</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon cod">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ number_format($alertStats['cod_pending']) }}</h3>
                                <p>COD Pending</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon failed">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ number_format($alertStats['failed_deliveries']) }}</h3>
                                <p>Failed Deliveries</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filters-section">
                    <form method="GET">
                        <div class="filters-row">
                            <div class="filter-group">
                                <label for="alert_type">Alert Type</label>
                                <select name="alert_type" id="alert_type" class="filter-select">
                                    <option value="">All Alerts</option>
                                    <option value="delayed" {{ request('alert_type') == 'delayed' ? 'selected' : '' }}>Delayed Shipments</option>
                                    <option value="cod_pending" {{ request('alert_type') == 'cod_pending' ? 'selected' : '' }}>COD Pending</option>
                                    <option value="failed_delivery" {{ request('alert_type') == 'failed_delivery' ? 'selected' : '' }}>Failed Deliveries</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <button type="submit" class="filter-btn">
                                    <i class="fas fa-filter"></i> Apply Filters
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Notifications List -->
                <div class="notifications-list">
                    <div class="notifications-header">
                        <h3>Recent Alerts & Notifications</h3>
                    </div>
                    
                    @forelse($alerts as $shipment)
                    @php
                        // Determine alert type
                        $isDelayed = $shipment->last_delivery_status && stripos($shipment->last_delivery_status, 'delay') !== false;
                        $isCodPending = $shipment->cod_charge > 0 && !$shipment->cod_paid;
                        $isFailed = $shipment->last_delivery_status && stripos($shipment->last_delivery_status, 'failed') !== false;
                        
                        // Set alert type
                        if ($isDelayed) {
                            $alertType = 'delayed';
                            $alertTitle = 'Shipment Delayed';
                            $alertDescription = "Shipment {$shipment->order->order_number ?? 'N/A'} has been delayed. Current status: {$shipment->last_delivery_status}";
                            $priorityLevel = 'medium';
                        } elseif ($isCodPending) {
                            $alertType = 'cod';
                            $alertTitle = 'COD Settlement Pending';
                            $alertDescription = "COD amount of Rs. {$shipment->cod_charge} is pending settlement for order {$shipment->order->order_number ?? 'N/A'}";
                            $priorityLevel = 'medium';
                        } elseif ($isFailed) {
                            $alertType = 'failed';
                            $alertTitle = 'Delivery Failed';
                            $alertDescription = "Delivery failed for order {$shipment->order->order_number ?? 'N/A'}. Reason: {$shipment->last_delivery_status}";
                            $priorityLevel = 'high';
                        } else {
                            $alertType = 'info';
                            $alertTitle = 'Shipment Update';
                            $alertDescription = "Status update for order {$shipment->order->order_number ?? 'N/A'}";
                            $priorityLevel = 'low';
                        }
                    @endphp
                    <div class="notification-item" onclick="window.location.href='{{ route('admin.gaaubesi.show', $shipment) }}'">
                        <div class="notification-header">
                            <div class="notification-title">
                                <div class="alert-icon {{ $alertType }}">
                                    @if($isDelayed)
                                        <i class="fas fa-clock"></i>
                                    @elseif($isCodPending)
                                        <i class="fas fa-money-bill-wave"></i>
                                    @elseif($isFailed)
                                        <i class="fas fa-exclamation-triangle"></i>
                                    @else
                                        <i class="fas fa-info-circle"></i>
                                    @endif
                                </div>
                                <div class="notification-content">
                                    <h5>{{ $alertTitle }}</h5>
                                    <div class="notification-meta">
                                        <span><i class="fas fa-tag"></i> {{ $shipment->order->order_number ?? 'N/A' }}</span>
                                        <span><i class="fas fa-user"></i> {{ $shipment->order->user->name ?? 'N/A' }}</span>
                                        <span><i class="fas fa-clock"></i> {{ $shipment->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="priority-badge {{ $priorityLevel }}">
                                {{ $priorityLevel }}
                            </div>
                        </div>
                        
                        <div class="notification-body">
                            <p>{{ $alertDescription }}</p>
                        </div>
                        
                        <div class="notification-actions">
                            <a href="{{ route('admin.gaaubesi.show', $shipment) }}" class="action-btn primary">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            @if($isCodPending)
                                <a href="{{ route('admin.gaaubesi.cod-settlement') }}" class="action-btn secondary">
                                    <i class="fas fa-money-bill-wave"></i> Settle COD
                                </a>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-bell-slash"></i>
                        <h3>No Alerts Found</h3>
                        <p>No alerts or notifications match your current filters.</p>
                    </div>
                    @endforelse
                </div>

                @if($alerts->hasPages())
                <div class="pagination-wrapper mt-4">
                    {{ $alerts->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-refresh notifications every 2 minutes
setInterval(function() {
    // Could implement real-time notifications here
    // location.reload();
}, 120000);
</script>
@endpush
