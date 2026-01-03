@extends('admin.layouts.app')

@section('title', 'Create Gaaubesi Shipment')
@section('page-title', 'Create Gaaubesi Shipment')

@push('styles')
<style>
    /* Create Gaaubesi Shipment Specific Styles */
    .create-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title-section h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #8B5CF6;
        margin: 0;
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .back-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: rgba(107, 114, 128, 0.1);
        color: #6B7280;
        text-decoration: none;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid rgba(107, 114, 128, 0.2);
    }

    .back-btn:hover {
        background: rgba(107, 114, 128, 0.2);
        color: #6B7280;
        transform: translateY(-2px);
    }

    .form-container {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
        backdrop-filter: blur(10px);
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid rgba(139, 92, 246, 0.1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: #8B5CF6;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-control, .form-select {
        border-radius: 0.75rem;
        border: 1px solid rgba(139, 92, 246, 0.2);
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #8B5CF6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .info-box {
        background: rgba(139, 92, 246, 0.05);
        border-left: 4px solid #8B5CF6;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .info-box strong {
        color: #8B5CF6;
    }

    .order-card {
        background: rgba(255, 255, 255, 0.5);
        border: 2px solid rgba(139, 92, 246, 0.1);
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        border-color: #8B5CF6;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.1);
    }

    .order-card.selected {
        border-color: #8B5CF6;
        background: rgba(139, 92, 246, 0.05);
    }

    .submit-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        color: white;
        border: none;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3);
    }

    .submit-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .create-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .form-container {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="create-header">
    <div class="page-title-section">
        <h1>Create Gaaubesi Shipment</h1>
        <p class="page-subtitle">Create a new shipment with Gaaubesi Logistics</p>
    </div>
    <a href="{{ route('admin.gaaubesi.index') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Shipments
    </a>
</div>

<div class="form-container">
    <form action="{{ route('admin.gaaubesi.store') }}" method="POST" id="gaaubesi-form">
        @csrf

        <!-- Order Selection -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-shopping-cart"></i>
                Select Order
            </h3>

            <div class="info-box">
                <strong>Note:</strong> Only confirmed orders without existing Gaaubesi shipments are shown below.
            </div>

            @if($orders->count() > 0)
                <div class="mb-3">
                    <label class="form-label">Select Order <span class="text-danger">*</span></label>
                    <select class="form-select" name="order_id" id="order-select" required>
                        <option value="">Choose an order...</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}" 
                                    data-receiver="{{ $order->receiver_name ?? $order->user->name ?? 'Customer' }}"
                                    data-address="{{ $order->receiver_full_address ?? $order->shipping_address }}"
                                    data-phone="{{ $order->receiver_phone ?? $order->user->phone ?? '' }}"
                                    data-total="{{ $order->total }}"
                                    data-payment="{{ $order->payment_method }}"
                                    data-city="{{ $order->receiver_city ?? '' }}"
                                    data-area="{{ $order->receiver_area ?? '' }}"
                                    data-branch="{{ $order->delivery_branch ?? 'HEAD OFFICE' }}"
                                    data-package-access="{{ $order->package_access ?? 'Can\'t Open' }}"
                                    data-delivery-type="{{ $order->delivery_type ?? 'Drop Off' }}"
                                    data-package-type="{{ $order->package_type ?? '' }}"
                                    data-sender-name="{{ $order->sender_name ?? config('app.name') }}"
                                    data-sender-phone="{{ $order->sender_phone ?? '' }}"
                                    data-instructions="{{ $order->delivery_instructions ?? $order->notes ?? '' }}">
                                {{ $order->order_number }} - {{ $order->receiver_name ?? $order->user->name ?? 'Guest' }} (₨{{ number_format($order->total, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    No confirmed orders available for shipment. Please confirm some orders first.
                </div>
            @endif
        </div>

        @if($orders->count() > 0)
        <!-- Branch Information -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-building"></i>
                Branch Information
            </h3>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Source Branch <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="source_branch" value="HEAD OFFICE" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Destination Branch <span class="text-danger">*</span></label>
                    <select class="form-select" name="destination_branch" id="destination-branch" required>
                        <option value="HEAD OFFICE">HEAD OFFICE</option>
                        @foreach($locations as $location => $rate)
                            <option value="{{ $location }}">{{ $location }} (₹{{ $rate }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Receiver Information -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-user"></i>
                Receiver Information
            </h3>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Receiver Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="receiver_name" id="receiver-name" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Receiver Phone <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="receiver_number" id="receiver-phone" required>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Receiver Address <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="receiver_address" id="receiver-address" rows="3" required></textarea>
                </div>
            </div>
        </div>

        <!-- Shipment Details -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-box"></i>
                Shipment Details
            </h3>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">COD Charge (₹) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="cod_charge" id="cod-charge" step="0.01" min="0" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Package Access <span class="text-danger">*</span></label>
                    <select class="form-select" name="package_access" required>
                        @foreach($packageAccessOptions as $option)
                            <option value="{{ $option }}" {{ $option === "Can't Open" ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Delivery Type <span class="text-danger">*</span></label>
                    <select class="form-select" name="delivery_type" required>
                        @foreach($deliveryTypeOptions as $option)
                            <option value="{{ $option }}" {{ $option === 'Drop Off' ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Package Type (Description)</label>
                    <input type="text" class="form-control" name="package_type" placeholder="e.g., Electronics, Clothing">
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Delivery Instructions / Remarks</label>
                    <textarea class="form-control" name="remarks" rows="3" placeholder="Enter any special delivery instructions..."></textarea>
                </div>
            </div>
        </div>

        <!-- Sub Vendor Information (Optional) -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-users"></i>
                Sub Vendor Information (Optional)
            </h3>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sub Vendor Name</label>
                    <input type="text" class="form-control" name="order_contact_name" placeholder="Sub vendor name">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Sub Vendor Phone</label>
                    <input type="text" class="form-control" name="order_contact_number" placeholder="Sub vendor phone">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <button type="submit" class="submit-btn" id="submit-btn">
                <i class="fas fa-paper-plane"></i>
                Create Shipment in Gaaubesi
            </button>
        </div>
        @endif
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderSelect = document.getElementById('order-select');
        const receiverName = document.getElementById('receiver-name');
        const receiverAddress = document.getElementById('receiver-address');
        const receiverPhone = document.getElementById('receiver-phone');
        const codCharge = document.getElementById('cod-charge');
        const form = document.getElementById('gaaubesi-form');
        const submitBtn = document.getElementById('submit-btn');

        // Auto-fill ALL Gaaubesi fields when order is selected
        if (orderSelect) {
            orderSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (this.value) {
                    // Basic receiver information
                    receiverName.value = selectedOption.dataset.receiver || '';
                    receiverAddress.value = selectedOption.dataset.address || '';
                    receiverPhone.value = selectedOption.dataset.phone || '';
                    
                    // Calculate COD charge based on payment method
                    const paymentMethod = selectedOption.dataset.payment || '';
                    const total = selectedOption.dataset.total || '0';
                    codCharge.value = (paymentMethod === 'cod' || paymentMethod === 'cash_on_delivery') ? total : '0';
                    
                    // Auto-populate branch if element exists
                    const destinationBranch = document.querySelector('select[name="destination_branch"]');
                    if (destinationBranch && selectedOption.dataset.branch) {
                        destinationBranch.value = selectedOption.dataset.branch;
                    }
                    
                    // Auto-populate package access if element exists
                    const packageAccess = document.querySelector('select[name="package_access"]');
                    if (packageAccess && selectedOption.dataset.packageAccess) {
                        packageAccess.value = selectedOption.dataset.packageAccess;
                    }
                    
                    // Auto-populate delivery type if element exists
                    const deliveryType = document.querySelector('select[name="delivery_type"]');
                    if (deliveryType && selectedOption.dataset.deliveryType) {
                        deliveryType.value = selectedOption.dataset.deliveryType;
                    }
                    
                    // Auto-populate package type if element exists
                    const packageType = document.querySelector('input[name="package_type"]');
                    if (packageType && selectedOption.dataset.packageType) {
                        packageType.value = selectedOption.dataset.packageType;
                    }
                    
                    // Auto-populate remarks/instructions if element exists
                    const remarks = document.querySelector('textarea[name="remarks"]');
                    if (remarks && selectedOption.dataset.instructions) {
                        remarks.value = selectedOption.dataset.instructions;
                    }
                    
                    // Auto-populate sender contact if elements exist
                    const orderContactName = document.querySelector('input[name="order_contact_name"]');
                    if (orderContactName && selectedOption.dataset.senderName) {
                        orderContactName.value = selectedOption.dataset.senderName;
                    }
                    
                    const orderContactNumber = document.querySelector('input[name="order_contact_number"]');
                    if (orderContactNumber && selectedOption.dataset.senderPhone) {
                        orderContactNumber.value = selectedOption.dataset.senderPhone;
                    }
                } else {
                    // Clear all fields
                    receiverName.value = '';
                    receiverAddress.value = '';
                    receiverPhone.value = '';
                    codCharge.value = '';
                }
            });
        }

        // Form submission with loading state
        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Shipment...';
        });
    });
</script>
@endpush


