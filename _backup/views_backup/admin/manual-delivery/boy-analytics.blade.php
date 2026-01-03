@extends('admin.layouts.app')

@section('title', 'Delivery Boy Analytics')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ $deliveryBoy->name }} - Analytics & Statement</h1>
            <p class="text-muted">{{ $deliveryBoy->delivery_boy_id }} | {{ $deliveryBoy->phone }}</p>
        </div>
        <a href="{{ route('admin.manual-delivery.delivery-boy-wise') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
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
                        <i class="fas fa-print"></i> Print Statement
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
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Assigned</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_assigned'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Delivered</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['delivered'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total COD Collected</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">₨{{ number_format($stats['total_cod_collected'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Pending Settlement</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">₨{{ number_format($stats['pending_settlement'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Deliveries -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Delivery History</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>COD Amount</th>
                            <th>Status</th>
                            <th>Assigned At</th>
                            <th>Delivered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentDeliveries as $delivery)
                        <tr>
                            <td><strong>{{ $delivery->order->order_number }}</strong></td>
                            <td>{{ $delivery->order->user->name }}</td>
                            <td>₨{{ number_format($delivery->cod_amount, 2) }}</td>
                            <td>
                                <span class="badge {{ $delivery->getStatusBadgeClass() }}">
                                    {{ strtoupper(str_replace('_', ' ', $delivery->status)) }}
                                </span>
                            </td>
                            <td>{{ $delivery->assigned_at->format('M d, Y h:i A') }}</td>
                            <td>{{ $delivery->delivered_at ? $delivery->delivered_at->format('M d, Y h:i A') : 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No deliveries found for the selected period</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection





