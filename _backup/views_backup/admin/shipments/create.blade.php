@extends('admin.layouts.app')

@section('title', 'Create Shipment')
@section('page-title', 'Create Shipment')

@section('content')
<div class="shipment-form-section">
    <div class="section-header">
        <div class="section-title">
            <div class="section-icon purple">
                <i class="fas fa-box"></i>
            </div>
            <div class="section-content">
                <div class="section-main-title">Create New Shipment</div>
                <div class="section-description">Assign confirmed orders to delivery methods</div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.shipments.store') }}" method="POST" class="shipment-form">
        @csrf
        
        <div class="form-row">
            <div class="form-group">
                <label for="order_id" class="form-label">Select Order</label>
                <select name="order_id" id="order_id" class="form-control" required>
                    <option value="">Choose an order...</option>
                    @foreach($confirmedOrders as $order)
                    <option value="{{ $order->id }}" {{ request('order') == $order->id ? 'selected' : '' }}>
                        {{ $order->order_number }} - {{ $order->user->name ?? 'N/A' }} (Rs. {{ number_format($order->total, 2) }})
                    </option>
                    @endforeach
                </select>
                @error('order_id')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="delivery_method" class="form-label">Delivery Method</label>
                <select name="delivery_method" id="delivery_method" class="form-control" required>
                    <option value="">Choose delivery method...</option>
                    @foreach($deliveryMethods as $key => $value)
                    <option value="{{ $key }}" {{ request('method') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
                @error('delivery_method')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="tracking_number" class="form-label">Tracking Number</label>
                <input type="text" name="tracking_number" id="tracking_number" class="form-control" placeholder="Auto-generated if left empty">
                @error('tracking_number')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="estimated_delivery_date" class="form-label">Estimated Delivery Date</label>
                <input type="date" name="estimated_delivery_date" id="estimated_delivery_date" class="form-control">
                @error('estimated_delivery_date')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Manual Delivery Fields -->
        <div id="manual-delivery-fields" class="delivery-method-fields" style="display: none;">
            <div class="form-row">
                <div class="form-group">
                    <label for="delivery_agent_name" class="form-label">Delivery Agent Name</label>
                    <input type="text" name="delivery_agent_name" id="delivery_agent_name" class="form-control" placeholder="Enter agent name">
                    @error('delivery_agent_name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="delivery_agent_phone" class="form-label">Delivery Agent Phone</label>
                    <input type="text" name="delivery_agent_phone" id="delivery_agent_phone" class="form-control" placeholder="Enter agent phone">
                    @error('delivery_agent_phone')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Logistics Fields -->
        <div id="logistics-fields" class="delivery-method-fields" style="display: none;">
            <div class="form-row">
                <div class="form-group">
                    <label for="logistics_company" class="form-label">Logistics Company</label>
                    <input type="text" name="logistics_company" id="logistics_company" class="form-control" placeholder="Enter company name">
                    @error('logistics_company')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Additional notes..."></textarea>
                @error('notes')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.shipments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Shipments
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Create Shipment
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deliveryMethodSelect = document.getElementById('delivery_method');
    const manualFields = document.getElementById('manual-delivery-fields');
    const logisticsFields = document.getElementById('logistics-fields');
    
    function toggleDeliveryFields() {
        const selectedMethod = deliveryMethodSelect.value;
        
        // Hide all fields first
        manualFields.style.display = 'none';
        logisticsFields.style.display = 'none';
        
        // Show relevant fields based on selection
        if (selectedMethod === 'manual') {
            manualFields.style.display = 'block';
        } else if (selectedMethod === 'logistics') {
            logisticsFields.style.display = 'block';
        }
    }
    
    deliveryMethodSelect.addEventListener('change', toggleDeliveryFields);
    
    // Initialize on page load
    toggleDeliveryFields();
    
    // Set default estimated delivery date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('estimated_delivery_date').value = tomorrow.toISOString().split('T')[0];
});
</script>
@endpush





