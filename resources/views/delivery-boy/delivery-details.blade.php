@extends('delivery-boy.layouts.app')

@section('title', 'Delivery Details')

@section('content')
<div class="top-bar">
    <div class="page-title">
        <h1>Delivery Details</h1>
        <p>Order #{{ $manualDelivery->order->order_number }}</p>
    </div>
    <div class="top-bar-actions">
        <span class="badge {{ $manualDelivery->getStatusBadgeClass() }} px-3 py-2">
            {{ strtoupper(str_replace('_', ' ', $manualDelivery->status)) }}
        </span>
    </div>
</div>

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
                        {{ $manualDelivery->order->order_number }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Order Date:</strong><br>
                        {{ $manualDelivery->order->created_at->format('M d, Y h:i A') }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Customer Name:</strong><br>
                        {{ $manualDelivery->order->user->name }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Customer Phone:</strong><br>
                        <a href="tel:{{ $manualDelivery->order->user->phone }}">{{ $manualDelivery->order->user->phone }}</a>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Shipping Address:</strong><br>
                        {{ $manualDelivery->order->shipping_address ?? 'Not provided' }}
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
                            <td>{{ $item->product_name }}</td>
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

        <!-- Update Status Form -->
        @if($manualDelivery->canUpdateStatus())
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Update Delivery Status</h5>
            </div>
            <div class="card-body">
                <form id="updateStatusForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            @if($manualDelivery->status === 'assigned')
                                <option value="picked_up">Picked Up</option>
                            @endif
                            @if(in_array($manualDelivery->status, ['assigned', 'picked_up']))
                                <option value="in_transit">In Transit</option>
                            @endif
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div id="deliveredFields" style="display: none;">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cod_collected" name="cod_collected" value="1">
                                <label class="form-check-label" for="cod_collected">
                                    @php
                                        // Calculate COD amount from order items if stored value is 0 or order is COD
                                        $codAmount = $manualDelivery->cod_amount ?? 0;
                                        if (($codAmount == 0 || $codAmount == null) && $manualDelivery->order && $manualDelivery->order->payment_method === 'cod') {
                                            $codAmount = $manualDelivery->order->orderItems->sum(function($item) {
                                                return ($item->quantity ?? 0) * ($item->price ?? 0);
                                            });
                                        }
                                    @endphp
                                    COD Amount Collected (₨{{ number_format($codAmount, 2) }})
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="delivery_proof" class="form-label">Delivery Proof (Photo)</label>
                            <input type="file" class="form-control" id="delivery_proof" name="delivery_proof" accept="image/*">
                        </div>
                    </div>

                    <div id="cancelledFields" style="display: none;">
                        <div class="mb-3">
                            <label for="cancellation_reason" class="form-label">Cancellation Reason</label>
                            <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i> Update Status
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Delivery Timeline -->
        <div class="card mb-4">
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

        <!-- Payment Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-money-bill me-2"></i> Payment Info</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>COD Amount:</strong><br>
                    @php
                        // Calculate COD amount from order items if stored value is 0 or order is COD
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
    </div>
</div>
@endsection

@section('styles')
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
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Show/hide fields based on status
    $('#status').on('change', function() {
        const status = $(this).val();
        $('#deliveredFields').toggle(status === 'delivered');
        $('#cancelledFields').toggle(status === 'cancelled');
    });

    // Form submission
    $('#updateStatusForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("delivery-boy.delivery.update-status", $manualDelivery) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
    });
});

function showNotification(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const alert = $(`
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas ${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);
    
    $('body').append(alert);
    setTimeout(() => alert.alert('close'), 3000);
}
</script>
@endsection







