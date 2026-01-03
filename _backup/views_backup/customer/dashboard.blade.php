@extends('layouts.storefront')

@section('title', 'My Account — ' . $tenant->business_name)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('storefront.preview', $tenant->subdomain) }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">My Account</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Account Menu</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('customer.dashboard', $tenant->subdomain) }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="{{ route('customer.profile', $tenant->subdomain) }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <a href="{{ route('customer.addresses', $tenant->subdomain) }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-map-marker-alt"></i> Addresses
                    </a>
                    <a href="{{ route('customer.orders', $tenant->subdomain) }}" class="list-group-item list-group-item-action">
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
                    <h4 class="mb-0">Welcome back, {{ $user->first_name }}!</h4>
                </div>
                <div class="card-body">
                    <!-- Account Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3 class="card-title">{{ $totalOrders }}</h3>
                                    <p class="card-text">Total Orders</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3 class="card-title">{{ $pendingOrders }}</h3>
                                    <p class="card-text">Pending Orders</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3 class="card-title">Rs. {{ number_format($totalSpent, 2) }}</h3>
                                    <p class="card-text">Total Spent</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <h5 class="mb-3">Recent Orders</h5>
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                                {{ ucfirst($order->status) }}
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
                        <div class="text-center mt-3">
                            <a href="{{ route('customer.orders', $tenant->subdomain) }}" class="btn btn-primary">
                                View All Orders
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
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