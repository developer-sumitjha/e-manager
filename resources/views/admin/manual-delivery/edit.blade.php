@extends('admin.layouts.app')

@section('title', 'Edit Delivery')
@section('page-title', 'Edit Delivery')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Edit Delivery</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.manual-delivery.update', $manualDelivery) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="delivery_boy_id" class="form-label">Delivery Boy</label>
                        <select class="form-select" id="delivery_boy_id" name="delivery_boy_id">
                            <option value="">-- Select Delivery Boy --</option>
                            @foreach($deliveryBoys as $boy)
                                <option value="{{ $boy->id }}" {{ $manualDelivery->delivery_boy_id == $boy->id ? 'selected' : '' }}>
                                    {{ $boy->name }} ({{ $boy->phone }})
                                </option>
                            @endforeach
                        </select>
                        @error('delivery_boy_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="assigned" {{ $manualDelivery->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="picked_up" {{ $manualDelivery->status == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                            <option value="in_transit" {{ $manualDelivery->status == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                            <option value="delivered" {{ $manualDelivery->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $manualDelivery->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="failed" {{ $manualDelivery->status == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="cancellation_reason_field" style="display: {{ $manualDelivery->status == 'cancelled' ? 'block' : 'none' }};">
                        <label for="cancellation_reason" class="form-label">Cancellation Reason</label>
                        <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3">{{ $manualDelivery->cancellation_reason }}</textarea>
                        @error('cancellation_reason')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="delivery_notes" class="form-label">Delivery Notes</label>
                        <textarea class="form-control" id="delivery_notes" name="delivery_notes" rows="3">{{ $manualDelivery->delivery_notes }}</textarea>
                        @error('delivery_notes')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update Delivery
                        </button>
                        <a href="{{ route('admin.manual-delivery.show', $manualDelivery) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Order Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-box me-2"></i> Order Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Order Number:</strong><br>
                <a href="{{ route('admin.orders.show', $manualDelivery->order) }}">{{ $manualDelivery->order->order_number }}</a></p>
                <p><strong>Customer:</strong><br>
                {{ $manualDelivery->order->user->name ?? 'N/A' }}</p>
                <p><strong>Total:</strong><br>
                ₨{{ number_format($manualDelivery->order->total, 2) }}</p>
            </div>
        </div>

        <!-- Current Delivery Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Current Info</h5>
            </div>
            <div class="card-body">
                <p><strong>Current Status:</strong><br>
                <span class="badge {{ $manualDelivery->getStatusBadgeClass() }}">
                    {{ strtoupper(str_replace('_', ' ', $manualDelivery->status)) }}
                </span></p>
                <p><strong>Delivery Boy:</strong><br>
                {{ $manualDelivery->deliveryBoy->name }}</p>
                <p><strong>Assigned At:</strong><br>
                {{ $manualDelivery->assigned_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const cancellationField = document.getElementById('cancellation_reason_field');
    
    statusSelect.addEventListener('change', function() {
        if (this.value === 'cancelled') {
            cancellationField.style.display = 'block';
            document.getElementById('cancellation_reason').setAttribute('required', 'required');
        } else {
            cancellationField.style.display = 'none';
            document.getElementById('cancellation_reason').removeAttribute('required');
        }
    });
});
</script>
@endpush
@endsection

