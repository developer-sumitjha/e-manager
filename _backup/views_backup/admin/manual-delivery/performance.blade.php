@extends('admin.layouts.app')

@section('title', 'Performance Analytics - Manual Delivery')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Performance Analytics</h1>
            <p class="text-muted">Track manual delivery performance and metrics</p>
        </div>
        <a href="{{ route('admin.manual-delivery.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Date Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">From Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">To Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Apply Filter</button>
                </div>
                <div class="col-md-3 d-flex align-items-end justify-content-end">
                    <button type="button" class="btn btn-success" onclick="window.print()">
                        <i class="fas fa-print"></i> Print Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Deliveries</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_deliveries'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed'] }}</div>
                    <small class="text-muted">
                        {{ $stats['total_deliveries'] > 0 ? round(($stats['completed'] / $stats['total_deliveries']) * 100, 1) : 0 }}% Success Rate
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Cancelled</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['cancelled'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Revenue</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">₨{{ number_format($stats['total_revenue'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Performance Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daily Performance</h6>
        </div>
        <div class="card-body">
            @if($dailyPerformance->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Total Deliveries</th>
                            <th>Completed</th>
                            <th>Cancelled</th>
                            <th>Success Rate</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyPerformance as $day)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</td>
                            <td>{{ $day->total_deliveries }}</td>
                            <td><span class="badge bg-success">{{ $day->completed }}</span></td>
                            <td><span class="badge bg-danger">{{ $day->cancelled }}</span></td>
                            <td>
                                {{ $day->total_deliveries > 0 ? round(($day->completed / $day->total_deliveries) * 100, 1) : 0 }}%
                            </td>
                            <td><strong>₨{{ number_format($day->revenue, 2) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-active">
                            <td><strong>Total</strong></td>
                            <td><strong>{{ $dailyPerformance->sum('total_deliveries') }}</strong></td>
                            <td><strong>{{ $dailyPerformance->sum('completed') }}</strong></td>
                            <td><strong>{{ $dailyPerformance->sum('cancelled') }}</strong></td>
                            <td><strong>
                                {{ $dailyPerformance->sum('total_deliveries') > 0 
                                    ? round(($dailyPerformance->sum('completed') / $dailyPerformance->sum('total_deliveries')) * 100, 1) 
                                    : 0 }}%
                            </strong></td>
                            <td><strong>₨{{ number_format($dailyPerformance->sum('revenue'), 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-chart-line text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3">No Data Available</h4>
                <p class="text-muted">No deliveries found for the selected period.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Delivery Boy Performance -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Delivery Boy Performance</h6>
        </div>
        <div class="card-body">
            @if($deliveryBoyPerformance->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Delivery Boy</th>
                            <th>Zone</th>
                            <th>Total Deliveries</th>
                            <th>Completed</th>
                            <th>Cancelled</th>
                            <th>Success Rate</th>
                            <th>Total Revenue</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deliveryBoyPerformance as $boy)
                        <tr>
                            <td>
                                <div><strong>{{ $boy->name }}</strong></div>
                                <small class="text-muted">{{ $boy->delivery_boy_id }}</small>
                            </td>
                            <td><span class="badge bg-primary">{{ ucfirst($boy->zone ?? 'N/A') }}</span></td>
                            <td>{{ $boy->total_deliveries }}</td>
                            <td><span class="badge bg-success">{{ $boy->completed_deliveries }}</span></td>
                            <td><span class="badge bg-danger">{{ $boy->cancelled_deliveries }}</span></td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar {{ $boy->success_rate >= 80 ? 'bg-success' : ($boy->success_rate >= 60 ? 'bg-warning' : 'bg-danger') }}" 
                                         style="width: {{ $boy->success_rate }}%">
                                        {{ $boy->success_rate }}%
                                    </div>
                                </div>
                            </td>
                            <td><strong>₨{{ number_format($boy->total_revenue, 2) }}</strong></td>
                            <td>
                                <span class="text-warning">★</span> {{ $boy->rating }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-users text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3">No Performance Data</h4>
                <p class="text-muted">No delivery boy performance data available for the selected period.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
@media print {
    .btn, .sidebar, .top-bar, .card-header .btn {
        display: none !important;
    }
}
</style>
@endsection





