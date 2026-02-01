@extends('admin.layouts.app')

@section('title', 'Pathao Shipment Details')
@section('page-title', 'Pathao Shipment Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Pathao Shipment Details</h1>
            <p class="text-muted">View and manage shipment information</p>
        </div>
        <div>
            <form action="{{ route('admin.pathao.refresh-status', $pathaoShipment->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-info">
                    <i class="fas fa-sync"></i> Refresh Status
                </button>
            </form>
            <a href="{{ route('admin.pathao.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Shipment Information -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Shipment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Consignment ID:</strong><br>
                            <span class="text-primary">{{ $pathaoShipment->consignment_id ?? $pathaoShipment->pathao_order_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Tracking ID:</strong><br>
                            <span>{{ $pathaoShipment->tracking_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Status:</strong><br>
                            <span class="badge bg-{{ $pathaoShipment->getStatusColor() }}">
                                {{ $pathaoShipment->status ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Store ID:</strong><br>
                            <span>{{ $pathaoShipment->store_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Item Type:</strong><br>
                            <span>{{ $pathaoShipment->item_type == 1 ? 'Document' : 'Parcel' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Delivery Type:</strong><br>
                            <span>{{ $pathaoShipment->delivery_type == 48 ? 'Normal Delivery' : 'On Demand Delivery' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Item Weight:</strong><br>
                            <span>{{ $pathaoShipment->item_weight }} kg</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>COD Amount:</strong><br>
                            <span class="text-success">₹{{ number_format($pathaoShipment->amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recipient Information -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Recipient Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Name:</strong><br>
                            <span>{{ $pathaoShipment->recipient_name }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Phone:</strong><br>
                            <span>{{ $pathaoShipment->recipient_phone }}</span>
                        </div>
                        <div class="col-md-12 mb-3">
                            <strong>Address:</strong><br>
                            <span>{{ $pathaoShipment->recipient_address }}</span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>City:</strong><br>
                            <span>{{ $pathaoShipment->recipient_city_name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>Zone:</strong><br>
                            <span>{{ $pathaoShipment->recipient_zone_name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <strong>Area:</strong><br>
                            <span>{{ $pathaoShipment->recipient_area_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Order Number:</strong><br>
                            <span>{{ $pathaoShipment->order->order_number ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Order Total:</strong><br>
                            <span>₹{{ number_format($pathaoShipment->order->total ?? 0, 2) }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Customer:</strong><br>
                            <span>{{ $pathaoShipment->order->user->name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Payment Method:</strong><br>
                            <span>{{ ucfirst($pathaoShipment->order->payment_method ?? 'N/A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status History -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Status History</h5>
                </div>
                <div class="card-body">
                    @if($pathaoShipment->status_history && count($pathaoShipment->status_history) > 0)
                        <div class="timeline">
                            @foreach(array_reverse($pathaoShipment->status_history) as $history)
                                <div class="mb-3">
                                    <strong>{{ $history['status'] ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $history['timestamp'] ?? 'N/A' }}</small>
                                    @if(isset($history['description']))
                                        <br><small>{{ $history['description'] }}</small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No status history available</p>
                    @endif
                </div>
            </div>

            <!-- COD Information -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> COD Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>COD Amount:</strong><br>
                        <span class="text-success">₹{{ number_format($pathaoShipment->amount, 2) }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong><br>
                        @if($pathaoShipment->cod_collected)
                            <span class="badge bg-success">Collected</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </div>
                    @if($pathaoShipment->cod_amount)
                        <div class="mb-3">
                            <strong>Collected Amount:</strong><br>
                            <span>₹{{ number_format($pathaoShipment->cod_amount, 2) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
