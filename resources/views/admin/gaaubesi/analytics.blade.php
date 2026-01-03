@extends('admin.layouts.app')

@section('title', 'Analytics & Reports')
@section('page-title', 'Analytics & Reports')

@push('styles')
<style>
    .analytics-container {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        backdrop-filter: blur(10px);
    }

    .date-filter-section {
        background: rgba(139, 92, 246, 0.05);
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
    }

    .filter-form {
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

    .filter-input {
        padding: 0.75rem;
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 0.5rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .filter-input:focus {
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

    .stats-overview {
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
    .stat-icon.delivered { background: rgba(34, 197, 94, 0.1); color: #22C55E; }
    .stat-icon.pending { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .stat-icon.revenue { background: rgba(139, 92, 246, 0.1); color: #8B5CF6; }

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

    .charts-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .chart-header {
        margin-bottom: 1.5rem;
    }

    .chart-header h4 {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.1rem;
        font-weight: 700;
    }

    .chart-placeholder {
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 0.5rem;
        border: 2px dashed #dee2e6;
        color: #6c757d;
        font-size: 1rem;
    }

    .destinations-section {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .destinations-header {
        margin-bottom: 1.5rem;
    }

    .destinations-header h4 {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.1rem;
        font-weight: 700;
    }

    .destination-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid rgba(229, 231, 235, 0.5);
        transition: all 0.3s ease;
    }

    .destination-item:hover {
        background: #f8f9fa;
    }

    .destination-item:last-child {
        border-bottom: none;
    }

    .destination-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .destination-rank {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .destination-name {
        font-weight: 600;
        color: var(--text-primary);
    }

    .destination-count {
        background: rgba(34, 197, 94, 0.1);
        color: #22C55E;
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
    }

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

    @media (max-width: 768px) {
        .charts-section {
            grid-template-columns: 1fr;
        }
        
        .filter-form {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="analytics-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-chart-line"></i>
                            Analytics & Reports
                        </h1>
                        <p class="page-subtitle">Comprehensive logistics analytics and performance insights</p>
                    </div>
                    <a href="{{ route('admin.gaaubesi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <!-- Date Filter Section -->
                <div class="date-filter-section">
                    <form method="GET" class="filter-form">
                        <div class="filter-group">
                            <label for="date_from">From Date</label>
                            <input type="date" name="date_from" id="date_from" class="filter-input" value="{{ $dateFrom }}">
                        </div>

                        <div class="filter-group">
                            <label for="date_to">To Date</label>
                            <input type="date" name="date_to" id="date_to" class="filter-input" value="{{ $dateTo }}">
                        </div>

                        <div class="filter-group">
                            <button type="submit" class="filter-btn">
                                <i class="fas fa-filter"></i> Apply Filters
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Statistics Overview -->
                <div class="stats-overview">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon total">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ number_format($shipmentStats['total_shipments']) }}</h3>
                                <p>Total Shipments</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon delivered">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ number_format($shipmentStats['delivered_shipments']) }}</h3>
                                <p>Delivered</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon pending">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ number_format($shipmentStats['pending_shipments']) }}</h3>
                                <p>Pending</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon revenue">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="stat-content">
                                <h3>Rs. {{ number_format($shipmentStats['total_cod_amount'], 0) }}</h3>
                                <p>COD Revenue</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="charts-section">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h4>
                                <i class="fas fa-chart-line"></i>
                                Monthly Shipment Trends
                            </h4>
                        </div>
                        @if($monthlyTrends->count() > 0)
                            <div class="chart-placeholder">
                                <div class="text-center">
                                    <i class="fas fa-chart-line fa-3x mb-3"></i>
                                    <p>Chart visualization would be implemented here</p>
                                    <small>Data points: {{ $monthlyTrends->count() }}</small>
                                </div>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-chart-line"></i>
                                <h3>No Data Available</h3>
                                <p>No shipment data found for the selected period.</p>
                            </div>
                        @endif
                    </div>

                    <div class="chart-card">
                        <div class="chart-header">
                            <h4>
                                <i class="fas fa-chart-pie"></i>
                                Delivery Performance
                            </h4>
                        </div>
                        @if($shipmentStats['total_shipments'] > 0)
                            <div class="chart-placeholder">
                                <div class="text-center">
                                    <i class="fas fa-chart-pie fa-3x mb-3"></i>
                                    <p>Pie chart visualization would be implemented here</p>
                                    <small>Delivered: {{ $shipmentStats['delivered_shipments'] }} | Pending: {{ $shipmentStats['pending_shipments'] }}</small>
                                </div>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-chart-pie"></i>
                                <h3>No Data Available</h3>
                                <p>No performance data available for the selected period.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Top Destinations -->
                <div class="destinations-section">
                    <div class="destinations-header">
                        <h4>
                            <i class="fas fa-map-marker-alt"></i>
                            Top Destinations
                        </h4>
                    </div>
                    
                    @if($topDestinations->count() > 0)
                        @foreach($topDestinations as $index => $destination)
                        <div class="destination-item">
                            <div class="destination-info">
                                <div class="destination-rank">{{ $index + 1 }}</div>
                                <div class="destination-name">{{ $destination->destination_branch }}</div>
                            </div>
                            <div class="destination-count">{{ $destination->count }} shipments</div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-map-marker-alt"></i>
                            <h3>No Destinations Data</h3>
                            <p>No destination data available for the selected period.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Future: Implement chart visualizations using Chart.js or similar library
// This would include:
// - Line chart for monthly trends
// - Pie chart for delivery performance
// - Bar chart for destination popularity
// - Real-time data updates

document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh data every 5 minutes
    setInterval(function() {
        // Could implement AJAX refresh for real-time updates
    }, 300000);
});
</script>
@endpush






