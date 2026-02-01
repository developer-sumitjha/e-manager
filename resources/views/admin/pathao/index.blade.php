@extends('admin.layouts.app')

@section('title', 'Pathao Parcel')
@section('page-title', 'Pathao Parcel')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Pathao Parcel Dashboard</h1>
            <p class="text-muted">Manage your Pathao shipments and track deliveries</p>
        </div>
        <div>
            <a href="{{ route('admin.pathao.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Shipment
            </a>
            <a href="{{ route('admin.pathao.bulk-create') }}" class="btn btn-success">
                <i class="fas fa-layer-group"></i> Bulk Create
            </a>
            <a href="{{ route('admin.pathao.settings') }}" class="btn btn-secondary">
                <i class="fas fa-cog"></i> Settings
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Shipments</h6>
                    <h3>{{ $totalShipments }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Delivered</h6>
                    <h3 class="text-success">{{ $deliveredShipments }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Pending</h6>
                    <h3 class="text-warning">{{ $pendingShipments }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">COD Collected</h6>
                    <h3 class="text-info">{{ $codCollectedCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pathao.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by consignment ID, tracking ID, or order number" 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="Order Created" {{ request('status') == 'Order Created' ? 'selected' : '' }}>Order Created</option>
                            <option value="In Transit" {{ request('status') == 'In Transit' ? 'selected' : '' }}>In Transit</option>
                            <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="cod_collected" class="form-select">
                            <option value="">All COD Status</option>
                            <option value="1" {{ request('cod_collected') == '1' ? 'selected' : '' }}>COD Collected</option>
                            <option value="0" {{ request('cod_collected') == '0' ? 'selected' : '' }}>COD Pending</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Shipments Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Consignment ID</th>
                            <th>Order Number</th>
                            <th>Recipient</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>COD</th>
                            <th>Shipped At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shipments as $shipment)
                        <tr>
                            <td>
                                <strong>{{ $shipment->consignment_id ?? $shipment->pathao_order_id ?? 'N/A' }}</strong>
                                @if($shipment->tracking_id)
                                    <br><small class="text-muted">Track: {{ $shipment->tracking_id }}</small>
                                @endif
                            </td>
                            <td>{{ $shipment->order->order_number ?? 'N/A' }}</td>
                            <td>
                                {{ $shipment->recipient_name }}<br>
                                <small class="text-muted">{{ $shipment->recipient_phone }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $shipment->getStatusColor() }}">
                                    {{ $shipment->status ?? 'N/A' }}
                                </span>
                            </td>
                            <td>₹{{ number_format($shipment->amount, 2) }}</td>
                            <td>
                                @if($shipment->cod_collected)
                                    <span class="badge bg-success">Collected</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>{{ $shipment->shipped_at ? $shipment->shipped_at->format('Y-m-d H:i') : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.pathao.show', $shipment->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No shipments found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $shipments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
