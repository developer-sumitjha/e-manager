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
                        {{ $manualDelivery->order->receiver_name ?? $manualDelivery->order->user->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Customer Phone:</strong><br>
                        @php
                            $customerPhone = $manualDelivery->order->receiver_phone ?? $manualDelivery->order->user->phone ?? null;
                        @endphp
                        @if($customerPhone)
                            <a href="tel:{{ $customerPhone }}">{{ $customerPhone }}</a>
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
                        @php
                            $order = $manualDelivery->order;
                            // Get subtotal - prefer stored subtotal, fallback to calculated items_total
                            $subtotal = $order->subtotal ?? $order->items_total ?? 0;
                            
                            // Get tax - check both tax_amount (new) and tax (legacy), use whichever has a value
                            $tax = null;
                            if ($order->tax_amount !== null && $order->tax_amount > 0) {
                                $tax = $order->tax_amount;
                            } elseif ($order->tax !== null && $order->tax > 0) {
                                $tax = $order->tax;
                            } else {
                                $tax = $order->tax_amount ?? $order->tax ?? 0;
                            }
                            
                            // Get shipping - check both shipping_cost (new) and shipping (legacy), use whichever has a value
                            $shipping = null;
                            if ($order->shipping_cost !== null && $order->shipping_cost > 0) {
                                $shipping = $order->shipping_cost;
                            } elseif ($order->shipping !== null && $order->shipping > 0) {
                                $shipping = $order->shipping;
                            } else {
                                $shipping = $order->shipping_cost ?? $order->shipping ?? 0;
                            }
                        @endphp
                        <tr>
                            <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                            <td><strong>₨{{ number_format($subtotal, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Tax:</td>
                            <td>₨{{ number_format($tax, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Shipping:</td>
                            <td>₨{{ number_format($shipping, 2) }}</td>
                        </tr>
                        <tr class="table-active">
                            <td colspan="3" class="text-end fw-bold">Total:</td>
                            <td class="fw-bold">₨{{ number_format($order->total, 2) }}</td>
                        </tr>
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
                    @if($manualDelivery->status === 'in_transit' || ($manualDelivery->status === 'delivered' && $manualDelivery->picked_up_at))
                        <div class="timeline-item">
                            <i class="fas fa-truck text-warning"></i>
                            <div>
                                <strong>In Transit</strong><br>
                                <small>
                                    @if($manualDelivery->status === 'in_transit')
                                        {{ $manualDelivery->updated_at->format('M d, Y h:i A') }}
                                    @elseif($manualDelivery->picked_up_at)
                                        {{ $manualDelivery->picked_up_at->format('M d, Y h:i A') }}
                                    @endif
                                </small>
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
                @php
                    $order = $manualDelivery->order;
                    $isCod = $order && in_array($order->payment_method, ['cod', 'cash_on_delivery']);
                    
                    // Get COD amount - use order total directly
                    $codAmount = 0;
                    if ($isCod) {
                        // Use order total directly as COD amount
                        $codAmount = $order->total ?? 0;
                    }
                    
                    // Get COD collected status from database
                    $codCollected = $manualDelivery->cod_collected ?? false;
                    
                    // Get COD settled status from database
                    $codSettled = $manualDelivery->cod_settled ?? false;
                @endphp
                
                <div class="mb-2">
                    <strong>Payment Method:</strong><br>
                    <span class="badge bg-{{ $isCod ? 'warning' : 'info' }}">
                        {{ $isCod ? 'COD' : strtoupper(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}
                    </span>
                </div>
                
                @if($isCod)
                <div class="mb-2">
                    <strong>COD Amount:</strong><br>
                    <span class="h4 text-danger">₨{{ number_format($codAmount, 2) }}</span>
                </div>
                <div class="mb-2">
                    <strong>COD Collected:</strong><br>
                    <span class="badge bg-{{ $codCollected ? 'success' : 'secondary' }}">
                        {{ $codCollected ? 'Yes' : 'No' }}
                    </span>
                    @if($codCollected && $manualDelivery->delivered_at)
                        <br><small class="text-muted">Collected on {{ $manualDelivery->delivered_at->format('M d, Y h:i A') }}</small>
                    @endif
                </div>
                <div>
                    <strong>Settlement Status:</strong><br>
                    <span class="badge bg-{{ $codSettled ? 'success' : 'warning' }}">
                        {{ $codSettled ? 'Settled' : 'Pending' }}
                    </span>
                    @if($codSettled && $manualDelivery->cod_settled_at)
                        <br><small class="text-muted">Settled on {{ $manualDelivery->cod_settled_at->format('M d, Y h:i A') }}</small>
                    @endif
                </div>
                @endif
                
                @if($isCod && isset($codSettlement) && $codSettlement)
                <!-- COD Settlement Details -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i> COD Settlement Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Settlement ID:</strong><br>
                            <span class="fw-semibold">{{ $codSettlement->settlement_id }}</span>
                        </div>
                        <div class="mb-2">
                            <strong>Settlement Amount:</strong><br>
                            <span class="h5 text-success">₨{{ number_format($codSettlement->total_amount, 2) }}</span>
                        </div>
                        <div class="mb-2">
                            <strong>Payment Method:</strong><br>
                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $codSettlement->payment_method)) }}</span>
                        </div>
                        @if($codSettlement->transaction_reference)
                        <div class="mb-2">
                            <strong>Transaction Reference:</strong><br>
                            <span class="text-muted">{{ $codSettlement->transaction_reference }}</span>
                        </div>
                        @endif
                        <div class="mb-2">
                            <strong>Settled By:</strong><br>
                            <span>{{ $codSettlement->settledBy->name ?? 'N/A' }}</span>
                        </div>
                        <div class="mb-2">
                            <strong>Settlement Date:</strong><br>
                            <span>{{ $codSettlement->settled_at->format('M d, Y h:i A') }}</span>
                        </div>
                        @if($codSettlement->notes)
                        <div>
                            <strong>Notes:</strong><br>
                            <span class="text-muted">{{ $codSettlement->notes }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @elseif(!$isCod)
                <div class="mb-2">
                    <strong>Payment Status:</strong><br>
                    <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'secondary' }}">
                        {{ ucfirst($order->payment_status ?? 'unpaid') }}
                    </span>
                </div>
                <div>
                    <strong>Order Total:</strong><br>
                    <span class="h4 text-primary">₨{{ number_format($order->total, 2) }}</span>
                </div>
                @endif
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

