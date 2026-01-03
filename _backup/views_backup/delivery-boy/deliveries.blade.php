@extends('delivery-boy.layouts.app')

@section('title', 'My Deliveries')

@section('content')
<div class="top-bar">
    <div class="page-title">
        <h1>My Deliveries</h1>
        <p>Manage all your delivery assignments</p>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('delivery-boy.deliveries') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search orders..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="picked_up" {{ request('status') === 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                    <option value="in_transit" {{ request('status') === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                <a href="{{ route('delivery-boy.deliveries') }}" class="btn btn-outline-secondary"><i class="fas fa-refresh"></i> Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Deliveries Table -->
<div class="table-card">
    <div class="card-header">
        <h5><i class="fas fa-list me-2"></i> All Deliveries ({{ $deliveries->total() }})</h5>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Address</th>
                    <th>COD Amount</th>
                    <th>Status</th>
                    <th>Assigned At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $delivery)
                <tr>
                    <td><strong>{{ $delivery->order->order_number }}</strong></td>
                    <td>
                        <div>{{ $delivery->order->user->name }}</div>
                        <small class="text-muted">{{ $delivery->order->user->phone }}</small>
                    </td>
                    <td>{{ Str::limit($delivery->order->shipping_address ?? 'N/A', 40) }}</td>
                    <td><strong class="text-danger">₨{{ number_format($delivery->cod_amount, 2) }}</strong></td>
                    <td><span class="badge {{ $delivery->getStatusBadgeClass() }}">{{ strtoupper(str_replace('_', ' ', $delivery->status)) }}</span></td>
                    <td>{{ $delivery->assigned_at->format('M d, Y h:i A') }}</td>
                    <td>
                        <a href="{{ route('delivery-boy.delivery.show', $delivery) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-2 text-muted">No deliveries found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($deliveries->hasPages())
    <div class="card-body">
        {{ $deliveries->links() }}
    </div>
    @endif
</div>
@endsection





