@extends('admin.layouts.app')

@section('title', 'Delivery Details')
@section('page-title', 'Delivery Details')

@section('content')
<div class="row">
    <div class="col-lg-8 mb-4">
        <!-- Order Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-box me-2"></i> Order Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Order Number:</strong><br>
                        <a href="{{ route('admin.orders.show', $manualDelivery->order) }}">{{ $manualDelivery->order->order_number }}</a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Order Date:</strong><br>
                        {{ $manualDelivery->order->created_at->format('M d, Y h:i A') }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Customer Name:</strong><br>
                        {{ $manualDelivery->order->user->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Customer Phone:</strong><br>
                        @if($manualDelivery->order->user)
                            <a href="tel:{{ $manualDelivery->order->user->phone }}">{{ $manualDelivery->order->user->phone }}</a>
                        @else
                            N/A
                        @endif
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Shipping Address:</strong><br>
                        {{ $manualDelivery->order->receiver_full_address ?? $manualDelivery->order->shipping_address ?? 'Not provided' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i> Order Items</h5>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($manualDelivery->order->orderItems as $item)
                        <tr>
                            <td>{{ $item->product_name ?? ($item->product->name ?? 'N/A') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₨{{ number_format($item->price, 2) }}</td>
                            <td>₨{{ number_format($item->quantity * $item->price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Items Total:</strong></td>
                            <td><strong>₨{{ number_format($manualDelivery->order->items_total, 2) }}</strong></td>
                        </tr>
                        @if($manualDelivery->order->shipping_cost > 0 || $manualDelivery->order->tax_amount > 0)
                        <tr>
                            <td colspan="3" class="text-end">
                                @if($manualDelivery->order->shipping_cost > 0)
                                    <small>Shipping: ₨{{ number_format($manualDelivery->order->shipping_cost ?? $manualDelivery->order->shipping ?? 0, 2) }}</small><br>
                                @endif
                                @if($manualDelivery->order->tax_amount > 0)
                                    <small>Tax: ₨{{ number_format($manualDelivery->order->tax_amount ?? $manualDelivery->order->tax ?? 0, 2) }}</small>
                                @endif
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Order Total:</strong></td>
                            <td><strong>₨{{ number_format($manualDelivery->order->total, 2) }}</strong></td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Delivery Timeline -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i> Delivery Timeline</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @if($manualDelivery->assigned_at)
                        <div class="timeline-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <div>
                                <strong>Assigned</strong><br>
                                <small>{{ $manualDelivery->assigned_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                    @endif
                    @if($manualDelivery->picked_up_at)
                        <div class="timeline-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <div>
                                <strong>Picked Up</strong><br>
                                <small>{{ $manualDelivery->picked_up_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                    @endif
                    @if($manualDelivery->delivered_at)
                        <div class="timeline-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <div>
                                <strong>Delivered</strong><br>
                                <small>{{ $manualDelivery->delivered_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                    @endif
                    @if($manualDelivery->cancelled_at)
                        <div class="timeline-item">
                            <i class="fas fa-times-circle text-danger"></i>
                            <div>
                                <strong>Cancelled</strong><br>
                                <small>{{ $manualDelivery->cancelled_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Delivery Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-truck me-2"></i> Delivery Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Status:</strong><br>
                    <span class="badge {{ $manualDelivery->getStatusBadgeClass() }} px-3 py-2">
                        {{ strtoupper(str_replace('_', ' ', $manualDelivery->status)) }}
                    </span>
                </div>
                <div class="mb-3">
                    <strong>Delivery Boy:</strong><br>
                    {{ $manualDelivery->deliveryBoy->name }}<br>
                    <small class="text-muted">{{ $manualDelivery->deliveryBoy->phone }}</small>
                </div>
                @if($manualDelivery->delivery_notes)
                <div class="mb-3">
                    <strong>Delivery Notes:</strong><br>
                    {{ $manualDelivery->delivery_notes }}
                </div>
                @endif
                @if($manualDelivery->cancellation_reason)
                <div class="mb-3">
                    <strong>Cancellation Reason:</strong><br>
                    {{ $manualDelivery->cancellation_reason }}
                </div>
                @endif
            </div>
        </div>

        <!-- Payment Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-money-bill me-2"></i> Payment Info</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>COD Amount:</strong><br>
                    @php
                        $codAmount = $manualDelivery->cod_amount ?? 0;
                        if (($codAmount == 0 || $codAmount == null) && $manualDelivery->order && $manualDelivery->order->payment_method === 'cod') {
                            $codAmount = $manualDelivery->order->orderItems->sum(function($item) {
                                return ($item->quantity ?? 0) * ($item->price ?? 0);
                            });
                        }
                    @endphp
                    <span class="h4 text-danger">₨{{ number_format($codAmount, 2) }}</span>
                </div>
                <div class="mb-2">
                    <strong>COD Collected:</strong><br>
                    <span class="badge bg-{{ $manualDelivery->cod_collected ? 'success' : 'secondary' }}">
                        {{ $manualDelivery->cod_collected ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div>
                    <strong>Settlement Status:</strong><br>
                    <span class="badge bg-{{ $manualDelivery->cod_settled ? 'success' : 'warning' }}">
                        {{ $manualDelivery->cod_settled ? 'Settled' : 'Pending' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card">
            <div class="card-body">
                <a href="{{ route('admin.manual-delivery.edit', $manualDelivery) }}" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-edit me-2"></i> Edit Delivery
                </a>
                <a href="{{ route('admin.manual-delivery.deliveries') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-arrow-left me-2"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
    display: flex;
    gap: 15px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -22px;
    top: 25px;
    width: 2px;
    height: calc(100% - 15px);
    background: #E5E7EB;
}

.timeline-item i {
    position: absolute;
    left: -30px;
    top: 0;
    font-size: 1.2rem;
}
</style>
@endpush
@endsection

