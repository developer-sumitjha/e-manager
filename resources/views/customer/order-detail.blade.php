@extends('layouts.storefront')

@section('title', 'Order #' . $order->order_number . ' — ' . $tenant->business_name)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('storefront.preview', $tenant->subdomain) }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard', $tenant->subdomain) }}">My Account</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.orders', $tenant->subdomain) }}">Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->order_number }}</li>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Order #{{ $order->order_number }}</h4>
                    <div>
                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'info')) }} me-2">
                            {{ ucfirst($order->status) }}
                        </span>
                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Order Information</h6>
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y g:i A') }}</p>
                            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                            <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                            <p><strong>Shipping Method:</strong> {{ $order->shipping_method }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Billing Address</h6>
                            <p>
                                {{ $order->billing_first_name }} {{ $order->billing_last_name }}<br>
                                {{ $order->billing_address }}<br>
                                {{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_postal_code }}<br>
                                {{ $order->billing_country }}<br>
                                <i class="fas fa-phone"></i> {{ $order->billing_phone }}
                            </p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="text-muted mb-3">Order Items</h6>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->primary_image_url)
                                                <img src="{{ $item->product->primary_image_url }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="me-3" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ $item->product->name ?? 'Product Not Found' }}</strong>
                                                @if($item->product)
                                                    <br><small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rs. {{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rs. {{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Shipping Address</h6>
                            <p>
                                {{ $order->shipping_first_name }} {{ $order->shipping_last_name }}<br>
                                {{ $order->shipping_address }}<br>
                                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                                {{ $order->shipping_country }}<br>
                                <i class="fas fa-phone"></i> {{ $order->shipping_phone }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Order Summary</h6>
                            <div class="d-flex justify-content-between">
                                <span>Subtotal:</span>
                                <span>Rs. {{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            @if($order->discount_total > 0)
                            <div class="d-flex justify-content-between text-success">
                                <span>Discount:</span>
                                <span>- Rs. {{ number_format($order->discount_total, 2) }}</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between">
                                <span>Shipping:</span>
                                <span>Rs. {{ number_format($order->shipping_cost, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Tax:</span>
                                <span>Rs. {{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span>Rs. {{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection