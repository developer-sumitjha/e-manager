@extends('layouts.storefront')

@section('title', 'My Orders — ' . $tenant->business_name)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('storefront.preview', $tenant->subdomain) }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard', $tenant->subdomain) }}">My Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">Orders</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Account Menu</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('customer.dashboard', $tenant->subdomain) }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="{{ route('customer.profile', $tenant->subdomain) }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <a href="{{ route('customer.addresses', $tenant->subdomain) }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-map-marker-alt"></i> Addresses
                    </a>
                    <a href="{{ route('customer.orders', $tenant->subdomain) }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-shopping-bag"></i> Orders
                    </a>
                    <form method="POST" action="{{ route('customer.logout', $tenant->subdomain) }}">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action text-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">My Orders</h4>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'info')) }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                        </td>
                                        <td>Rs. {{ number_format($order->total, 2) }}</td>
                                        <td>
                                            <a href="{{ route('customer.order.detail', [$tenant->subdomain, $order->id]) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No orders yet</h5>
                            <p class="text-muted">Start shopping to see your orders here.</p>
                            <a href="{{ route('storefront.preview', $tenant->subdomain) }}" class="btn btn-primary">
                                Start Shopping
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection