@extends('admin.layouts.app')

@section('title', 'Create Pathao Shipment')
@section('page-title', 'Create Pathao Shipment')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Create Pathao Shipment</h1>
            <p class="text-muted">Create a new shipment in Pathao system</p>
        </div>
        <a href="{{ route('admin.pathao.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('admin.pathao.store') }}" method="POST">
        @csrf
        
        <!-- Order Selection -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Select Order</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Order *</label>
                    <select name="order_id" id="order-select" class="form-select @error('order_id') is-invalid @enderror" required>
                        <option value="">-- Select Order --</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}" 
                                    data-receiver-name="{{ $order->receiver_name ?? $order->user->name ?? 'Customer' }}"
                                    data-receiver-phone="{{ $order->receiver_phone ?? $order->user->phone ?? '' }}"
                                    data-receiver-address="{{ $order->receiver_full_address ?? $order->shipping_address ?? '' }}"
                                    data-total="{{ $order->total }}"
                                    data-payment-method="{{ $order->payment_method ?? '' }}"
                                    data-instructions="{{ $order->delivery_instructions ?? $order->notes ?? '' }}"
                                    {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                {{ $order->order_number }} - {{ $order->user->name ?? 'Customer' }} (₹{{ number_format($order->total, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('order_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Store Selection -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-store"></i> Store Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Store *</label>
                    <select name="store_id" class="form-select @error('store_id') is-invalid @enderror" required>
                        <option value="">-- Select Store --</option>
                        @if(isset($stores) && count($stores) > 0)
                            @foreach($stores as $store)
                                <option value="{{ $store['store_id'] }}" {{ old('store_id') == $store['store_id'] ? 'selected' : '' }}>
                                    {{ $store['store_name'] }} (ID: {{ $store['store_id'] }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <small class="text-muted">Configure stores in Pathao Settings</small>
                    @error('store_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                        <label class="form-label">Recipient Name *</label>
                        <input type="text" name="recipient_name" id="recipient-name" class="form-control @error('recipient_name') is-invalid @enderror" 
                               value="{{ old('recipient_name') }}" required>
                        @error('recipient_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Recipient Phone *</label>
                        <input type="text" name="recipient_phone" id="recipient-phone" class="form-control @error('recipient_phone') is-invalid @enderror" 
                               value="{{ old('recipient_phone') }}" required>
                        @error('recipient_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Recipient Address *</label>
                        <textarea name="recipient_address" id="recipient-address" class="form-control @error('recipient_address') is-invalid @enderror" 
                                  rows="3" required>{{ old('recipient_address') }}</textarea>
                        @error('recipient_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Selection -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Delivery Location</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">City *</label>
                        <select name="recipient_city" id="recipient_city" class="form-select @error('recipient_city') is-invalid @enderror" required>
                            <option value="">-- Select City --</option>
                            @if(isset($cities) && count($cities) > 0)
                                @foreach($cities as $city)
                                    <option value="{{ $city['city_id'] }}" {{ old('recipient_city') == $city['city_id'] ? 'selected' : '' }}>
                                        {{ $city['city_name'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('recipient_city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Zone *</label>
                        <select name="recipient_zone" id="recipient_zone" class="form-select @error('recipient_zone') is-invalid @enderror" required>
                            <option value="">-- Select Zone --</option>
                        </select>
                        @error('recipient_zone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Area</label>
                        <select name="recipient_area" id="recipient_area" class="form-select @error('recipient_area') is-invalid @enderror">
                            <option value="">-- Select Area (Optional) --</option>
                        </select>
                        @error('recipient_area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipment Details -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-box"></i> Shipment Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Item Type *</label>
                        <select name="item_type" class="form-select @error('item_type') is-invalid @enderror" required>
                            <option value="1" {{ old('item_type') == '1' ? 'selected' : '' }}>Document</option>
                            <option value="2" {{ old('item_type', '2') == '2' ? 'selected' : '' }}>Parcel</option>
                        </select>
                        @error('item_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Delivery Type *</label>
                        <select name="delivery_type" class="form-select @error('delivery_type') is-invalid @enderror" required>
                            <option value="48" {{ old('delivery_type', '48') == '48' ? 'selected' : '' }}>Normal Delivery (48 hours)</option>
                            <option value="12" {{ old('delivery_type') == '12' ? 'selected' : '' }}>On Demand Delivery (12 hours)</option>
                        </select>
                        @error('delivery_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Item Weight (kg) *</label>
                        <input type="number" name="item_weight" step="0.1" min="0.5" max="10" 
                               class="form-control @error('item_weight') is-invalid @enderror" 
                               value="{{ old('item_weight', '0.5') }}" required>
                        <small class="text-muted">Min: 0.5 kg, Max: 10 kg</small>
                        @error('item_weight')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">COD Amount (₹)</label>
                        <input type="number" name="amount_to_collect" id="cod-amount" step="0.01" min="0" 
                               class="form-control @error('amount_to_collect') is-invalid @enderror" 
                               value="{{ old('amount_to_collect', '0') }}">
                        @error('amount_to_collect')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Item Description</label>
                        <input type="text" name="item_description" id="item-description" class="form-control" 
                               value="{{ old('item_description') }}" 
                               placeholder="Brief description of items">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Special Instructions</label>
                        <textarea name="special_instruction" id="special-instruction" class="form-control" rows="3" 
                                  placeholder="Any special delivery instructions">{{ old('special_instruction') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="card">
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Create Shipment
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Order selection auto-fill
    const orderSelect = document.getElementById('order-select');
    const recipientName = document.getElementById('recipient-name');
    const recipientPhone = document.getElementById('recipient-phone');
    const recipientAddress = document.getElementById('recipient-address');
    const codAmount = document.getElementById('cod-amount');
    const specialInstruction = document.getElementById('special-instruction');
    const itemDescription = document.getElementById('item-description');

    // Auto-fill recipient details when order is selected
    if (orderSelect) {
        orderSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value) {
                // Auto-fill recipient information
                if (recipientName) {
                    recipientName.value = selectedOption.dataset.receiverName || '';
                }
                if (recipientPhone) {
                    recipientPhone.value = selectedOption.dataset.receiverPhone || '';
                }
                if (recipientAddress) {
                    recipientAddress.value = selectedOption.dataset.receiverAddress || '';
                }
                
                // Calculate COD amount based on payment method
                const paymentMethod = selectedOption.dataset.paymentMethod || '';
                const total = parseFloat(selectedOption.dataset.total || '0');
                if (codAmount) {
                    codAmount.value = (paymentMethod === 'cod' || paymentMethod === 'cash_on_delivery') ? total.toFixed(2) : '0';
                }
                
                // Auto-fill special instructions
                if (specialInstruction && selectedOption.dataset.instructions) {
                    specialInstruction.value = selectedOption.dataset.instructions;
                }
                
                // Auto-fill item description (can be based on order items count)
                if (itemDescription && !itemDescription.value) {
                    // You can enhance this to show actual item names if needed
                    itemDescription.value = 'Order items';
                }
            } else {
                // Clear all fields when no order is selected
                if (recipientName) recipientName.value = '';
                if (recipientPhone) recipientPhone.value = '';
                if (recipientAddress) recipientAddress.value = '';
                if (codAmount) codAmount.value = '0';
                if (specialInstruction) specialInstruction.value = '';
            }
        });
    }

    // City/Zone/Area selection
    const citySelect = document.getElementById('recipient_city');
    const zoneSelect = document.getElementById('recipient_zone');
    const areaSelect = document.getElementById('recipient_area');

    // Load zones when city is selected
    citySelect.addEventListener('change', function() {
        const cityId = this.value;
        zoneSelect.innerHTML = '<option value="">Loading zones...</option>';
        areaSelect.innerHTML = '<option value="">-- Select Area (Optional) --</option>';
        
        if (!cityId) {
            zoneSelect.innerHTML = '<option value="">-- Select Zone --</option>';
            return;
        }

        fetch('{{ route("admin.pathao.zones") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ city_id: cityId })
        })
        .then(response => response.json())
        .then(data => {
            zoneSelect.innerHTML = '<option value="">-- Select Zone --</option>';
            if (data.success && data.data) {
                data.data.forEach(zone => {
                    const option = document.createElement('option');
                    option.value = zone.zone_id;
                    option.textContent = zone.zone_name;
                    zoneSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading zones:', error);
            zoneSelect.innerHTML = '<option value="">Error loading zones</option>';
        });
    });

    // Load areas when zone is selected
    zoneSelect.addEventListener('change', function() {
        const zoneId = this.value;
        areaSelect.innerHTML = '<option value="">Loading areas...</option>';
        
        if (!zoneId) {
            areaSelect.innerHTML = '<option value="">-- Select Area (Optional) --</option>';
            return;
        }

        fetch('{{ route("admin.pathao.areas") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ zone_id: zoneId })
        })
        .then(response => response.json())
        .then(data => {
            areaSelect.innerHTML = '<option value="">-- Select Area (Optional) --</option>';
            if (data.success && data.data) {
                data.data.forEach(area => {
                    const option = document.createElement('option');
                    option.value = area.area_id;
                    option.textContent = area.area_name;
                    areaSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading areas:', error);
            areaSelect.innerHTML = '<option value="">Error loading areas</option>';
        });
    });
});
</script>
@endsection
