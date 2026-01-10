@extends('admin.layouts.app')

@section('title', 'Edit Order')
@section('page-title', 'Edit Order')
@section('page-subtitle', 'Update order information and status')

@section('breadcrumb')
    <div class="breadcrumb-item">
        <a href="{{ route('admin.orders.index') }}" class="breadcrumb-link">Orders</a>
    </div>
    <div class="breadcrumb-item">
        <a href="{{ route('admin.orders.show', $order) }}" class="breadcrumb-link">Order #{{ $order->order_number }}</a>
    </div>
    <div class="breadcrumb-item active">Edit</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Edit Order #{{ $order->order_number }}</h5>
                        <small class="text-muted">Last updated {{ $order->updated_at->format('M j, Y \a\t g:i A') }}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'info')) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <span class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'refunded' ? 'warning' : 'danger') }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="order-edit-form">
                    @csrf
                    @method('PUT')
                    
                    <!-- Customer Information Section -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-user me-2"></i>Customer/Receiver Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="customer_name" class="form-label">
                                        <i class="fas fa-user me-1"></i> Customer Name *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('customer_name') is-invalid @enderror" 
                                           id="customer_name" 
                                           name="customer_name" 
                                           value="{{ old('customer_name', $order->receiver_name ?? $order->user->name ?? '') }}" 
                                           required 
                                           placeholder="Full name of customer">
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="customer_phone" class="form-label">
                                        <i class="fas fa-phone me-1"></i> Customer Phone *
                                    </label>
                                    <input type="tel" 
                                           class="form-control @error('customer_phone') is-invalid @enderror" 
                                           id="customer_phone" 
                                           name="customer_phone" 
                                           value="{{ old('customer_phone', $order->receiver_phone ?? $order->user->phone ?? '') }}" 
                                           required 
                                           placeholder="Phone number">
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="shipping_address" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i> Complete Shipping Address *
                                    </label>
                                    <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                              id="shipping_address" 
                                              name="shipping_address" 
                                              rows="3" 
                                              required 
                                              placeholder="House/Flat number, Street, Landmark, City">{{ old('shipping_address', $order->receiver_full_address ?? $order->shipping_address ?? '') }}</textarea>
                                    @error('shipping_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Products Section -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-box me-2"></i>Products
                                </h6>
                                <button type="button" class="btn btn-sm btn-primary" onclick="addProductRow()">
                                    <i class="fas fa-plus me-1"></i> Add Product
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="productsContainer">
                                @if($order->orderItems->count() > 0)
                                    @foreach($order->orderItems as $index => $item)
                                        <div class="product-row mb-3 p-3 border rounded">
                                            <div class="row align-items-end">
                                                <div class="col-md-5">
                                                    <label class="form-label">Product *</label>
                                                    <select class="form-select" name="product_ids[]" required onchange="updatePrice(this)">
                                                        <option value="">Select Product</option>
                                                        @foreach($products as $product)
                                                            <option value="{{ $product->id }}" 
                                                                    data-price="{{ $product->sale_price ?? $product->price }}"
                                                                    {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                                {{ $product->name }} - Rs. {{ number_format($product->sale_price ?? $product->price, 2) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Quantity *</label>
                                                    <input type="number" class="form-control" name="quantities[]" 
                                                           value="{{ old('quantities.'.$index, $item->quantity) }}" min="1" required onchange="calculateTotal()">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Price</label>
                                                    <div class="form-control-plaintext fw-semibold" id="price-{{ $index }}">
                                                        Rs. {{ number_format($item->price * $item->quantity, 2) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label">&nbsp;</label>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeProductRow(this)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="product-row mb-3 p-3 border rounded">
                                        <div class="row align-items-end">
                                            <div class="col-md-5">
                                                <label class="form-label">Product *</label>
                                                <select class="form-select" name="product_ids[]" required onchange="updatePrice(this)">
                                                    <option value="">Select Product</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" data-price="{{ $product->sale_price ?? $product->price }}">
                                                            {{ $product->name }} - Rs. {{ number_format($product->sale_price ?? $product->price, 2) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Quantity *</label>
                                                <input type="number" class="form-control" name="quantities[]" value="1" min="1" required onchange="calculateTotal()">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Price</label>
                                                <div class="form-control-plaintext fw-semibold" id="price-0">Rs. 0.00</div>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="removeProductRow(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            @if(count($products) == 0)
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>No products available.</strong> Please add products first before editing orders.
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-plus me-1"></i> Add Product
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <!-- Order Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label">
                                <i class="fas fa-flag me-1"></i> Order Status *
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status" 
                                    required>
                                <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="confirmed" {{ old('status', $order->status) == 'confirmed' ? 'selected' : '' }}>
                                    Confirmed
                                </option>
                                <option value="processing" {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>
                                    Processing
                                </option>
                                <option value="shipped" {{ old('status', $order->status) == 'shipped' ? 'selected' : '' }}>
                                    Shipped
                                </option>
                                <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>
                                <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                        <!-- Payment Status -->
                        <div class="col-md-6">
                            <label for="payment_status" class="form-label">
                                <i class="fas fa-credit-card me-1"></i> Payment Status *
                            </label>
                            <select class="form-select @error('payment_status') is-invalid @enderror" 
                                    id="payment_status" 
                                    name="payment_status" 
                                    required>
                                <option value="unpaid" {{ old('payment_status', $order->payment_status) == 'unpaid' ? 'selected' : '' }}>
                                    Unpaid
                                </option>
                                <option value="paid" {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>
                                    Paid
                                </option>
                                <option value="refunded" {{ old('payment_status', $order->payment_status) == 'refunded' ? 'selected' : '' }}>
                                    Refunded
                                </option>
                        </select>
                        @error('payment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                        <!-- Payment Method -->
                        <div class="col-md-6">
                            <label for="payment_method" class="form-label">
                                <i class="fas fa-money-bill-wave me-1"></i> Payment Method
                            </label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" 
                                    name="payment_method">
                                <option value="cod" {{ old('payment_method', $order->payment_method) == 'cod' ? 'selected' : '' }}>
                                    Cash on Delivery
                                </option>
                                <option value="online" {{ old('payment_method', $order->payment_method) == 'online' ? 'selected' : '' }}>
                                    Online Payment
                                </option>
                                <option value="bank_transfer" {{ old('payment_method', $order->payment_method) == 'bank_transfer' ? 'selected' : '' }}>
                                    Bank Transfer
                                </option>
                                <option value="khalti" {{ old('payment_method', $order->payment_method) == 'khalti' ? 'selected' : '' }}>
                                    Khalti
                                </option>
                                <option value="esewa" {{ old('payment_method', $order->payment_method) == 'esewa' ? 'selected' : '' }}>
                                    eSewa
                                </option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Delivery Type -->
                        <div class="col-md-6">
                            <label for="delivery_type" class="form-label">
                                <i class="fas fa-truck me-1"></i> Delivery Type
                            </label>
                            <select class="form-select @error('delivery_type') is-invalid @enderror" 
                                    id="delivery_type" 
                                    name="delivery_type">
                                <option value="standard" {{ old('delivery_type', $order->delivery_type) == 'standard' ? 'selected' : '' }}>
                                    Standard Delivery
                                </option>
                                <option value="express" {{ old('delivery_type', $order->delivery_type) == 'express' ? 'selected' : '' }}>
                                    Express Delivery
                                </option>
                            </select>
                            @error('delivery_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Shipping Cost -->
                        <div class="col-md-6">
                            <label for="shipping_cost" class="form-label">
                                <i class="fas fa-shipping-fast me-1"></i> Shipping Cost (Rs.)
                            </label>
                            <input type="number" 
                                   class="form-control @error('shipping_cost') is-invalid @enderror" 
                                   id="shipping_cost" 
                                   name="shipping_cost" 
                                   value="{{ old('shipping_cost', $order->shipping_cost ?? $order->shipping ?? 0) }}" 
                                   min="0" 
                                   step="0.01" 
                                   onchange="calculateTotal()"
                                   placeholder="0.00">
                            @error('shipping_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tax Amount -->
                        <div class="col-md-6">
                            <label for="tax_amount" class="form-label">
                                <i class="fas fa-receipt me-1"></i> Tax Amount (Rs.)
                            </label>
                            <input type="number" 
                                   class="form-control @error('tax_amount') is-invalid @enderror" 
                                   id="tax_amount" 
                                   name="tax_amount" 
                                   value="{{ old('tax_amount', $order->tax_amount ?? $order->tax ?? 0) }}" 
                                   min="0" 
                                   step="0.01" 
                                   onchange="calculateTotal()"
                                   placeholder="0.00">
                            @error('tax_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Order Notes -->
                        <div class="col-12">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i> Order Notes
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="4" 
                                      placeholder="Add any additional notes about this order...">{{ old('notes', $order->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                        <!-- Delivery Instructions -->
                        <div class="col-12">
                            <label for="delivery_instructions" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i> Delivery Instructions
                            </label>
                            <textarea class="form-control @error('delivery_instructions') is-invalid @enderror" 
                                      id="delivery_instructions" 
                                      name="delivery_instructions" 
                                      rows="3" 
                                      placeholder="Special delivery instructions...">{{ old('delivery_instructions', $order->delivery_instructions) }}</textarea>
                            @error('delivery_instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary" id="save-btn">
                            <i class="fas fa-save me-1"></i> Update Order
                        </button>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-eye me-1"></i> View Order
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Orders
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Order Summary Sidebar -->
    <div class="col-xl-4">
        <!-- Order Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Order Summary</h6>
            </div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <small class="text-muted">Order ID</small>
                        <div class="fw-semibold">{{ $order->order_number }}</div>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Items</small>
                        <div class="fw-semibold">{{ $order->orderItems->count() }}</div>
                    </div>
                </div>
                <div class="border-top pt-2 mb-2">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Subtotal:</small>
                        <div class="fw-semibold" id="order-subtotal">Rs. {{ number_format($order->subtotal ?? $order->orderItems->sum('total'), 2) }}</div>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Shipping:</small>
                        <div class="fw-semibold" id="order-shipping">Rs. {{ number_format($order->shipping_cost ?? $order->shipping ?? 0, 2) }}</div>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Tax:</small>
                        <div class="fw-semibold" id="order-tax">Rs. {{ number_format($order->tax_amount ?? $order->tax ?? 0, 2) }}</div>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-2">
                        <strong>Total:</strong>
                        <strong class="text-primary" id="order-total">Rs. {{ number_format($order->total, 2) }}</strong>
                    </div>
                </div>
                <div class="row g-2 mt-3">
                    <div class="col-12">
                        <small class="text-muted">Created</small>
                        <div class="fw-semibold">{{ $order->created_at->format('M j, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Customer</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-sm me-3">
                        <div class="avatar-initials">
                            {{ substr($order->receiver_name ?? $order->user->name ?? 'G', 0, 1) }}
                        </div>
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $order->receiver_name ?? $order->user->name ?? 'Guest' }}</div>
                        <small class="text-muted">{{ $order->receiver_phone ?? $order->user->phone ?? 'N/A' }}</small>
                    </div>
                </div>
                @if($order->user)
                <div class="text-muted">
                    <small>Email: {{ $order->user->email ?? 'N/A' }}</small>
                </div>
                @endif
            </div>
        </div>

        <!-- Status History -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Status History</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <div class="fw-semibold">Order Created</div>
                            <small class="text-muted">{{ $order->created_at->format('M j, Y H:i') }}</small>
                        </div>
                    </div>
                    @if($order->status !== 'pending')
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <div class="fw-semibold">Status: {{ ucfirst($order->status) }}</div>
                            <small class="text-muted">{{ $order->updated_at->format('M j, Y H:i') }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Avatar */
.avatar-sm {
    width: 32px;
    height: 32px;
}

.avatar-initials {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: rgba(99, 102, 241, 0.2);
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0.25rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid var(--bg-surface);
}

.timeline-content {
    padding-left: 0.5rem;
}

/* Form Enhancements */
.form-label {
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 0.5rem;
}

.form-label i {
    color: var(--primary);
}

/* Loading State */
.btn.loading {
    position: relative;
    color: transparent;
}

.btn.loading::after {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    top: 50%;
    left: 50%;
    margin-left: -8px;
    margin-top: -8px;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
    .timeline {
        padding-left: 1.5rem;
    }
    
    .timeline::before {
        left: 0.5rem;
    }
    
    .timeline-marker {
        left: -1.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
let productCounter = {{ $order->orderItems->count() }};

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('order-edit-form');
    const saveBtn = document.getElementById('save-btn');
    
    // Initialize product calculations
    calculateTotal();
    updateAllPrices();
    
    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        const productRows = document.querySelectorAll('.product-row');
        if (productRows.length === 0) {
            e.preventDefault();
            alert('Please add at least one product.');
            return false;
        }
        
        saveBtn.classList.add('loading');
        saveBtn.disabled = true;
    });
    
    // Status change confirmation
    const statusSelect = document.getElementById('status');
    const originalStatus = statusSelect.value;
    
    statusSelect.addEventListener('change', function() {
        if (this.value !== originalStatus) {
            const statusLabels = {
                'pending': 'Pending',
                'confirmed': 'Confirmed',
                'processing': 'Processing',
                'shipped': 'Shipped',
                'completed': 'Completed',
                'cancelled': 'Cancelled'
            };
            
            if (confirm(`Are you sure you want to change the order status to "${statusLabels[this.value]}"?`)) {
                // Status change confirmed
            } else {
                this.value = originalStatus;
            }
        }
    });
    
    // Payment status change confirmation
    const paymentStatusSelect = document.getElementById('payment_status');
    const originalPaymentStatus = paymentStatusSelect.value;
    
    paymentStatusSelect.addEventListener('change', function() {
        if (this.value !== originalPaymentStatus) {
            const paymentLabels = {
                'unpaid': 'Unpaid',
                'paid': 'Paid',
                'refunded': 'Refunded'
            };
            
            if (confirm(`Are you sure you want to change the payment status to "${paymentLabels[this.value]}"?`)) {
                // Payment status change confirmed
            } else {
                this.value = originalPaymentStatus;
            }
        }
    });
    
    // Auto-save draft functionality
    let autoSaveTimeout;
    const formInputs = form.querySelectorAll('input, select, textarea');
    
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                // Auto-save implementation could go here
                console.log('Auto-saving draft...');
            }, 2000);
        });
    });
});

function addProductRow() {
    const container = document.getElementById('productsContainer');
    const productRow = document.createElement('div');
    productRow.className = 'product-row mb-3 p-3 border rounded';
    productRow.innerHTML = `
        <div class="row align-items-end">
            <div class="col-md-5">
                <label class="form-label">Product *</label>
                <select class="form-select" name="product_ids[]" required onchange="updatePrice(this)">
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->sale_price ?? $product->price }}">
                            {{ $product->name }} - Rs. {{ number_format($product->sale_price ?? $product->price, 2) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Quantity *</label>
                <input type="number" class="form-control" name="quantities[]" value="1" min="1" required onchange="calculateTotal()">
            </div>
            <div class="col-md-3">
                <label class="form-label">Price</label>
                <div class="form-control-plaintext fw-semibold" id="price-${productCounter}">Rs. 0.00</div>
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeProductRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(productRow);
    productCounter++;
}

function removeProductRow(button) {
    const productRows = document.querySelectorAll('.product-row');
    if (productRows.length > 1) {
        button.closest('.product-row').remove();
        calculateTotal();
    } else {
        alert('At least one product is required.');
    }
}

function updatePrice(select) {
    const price = select.options[select.selectedIndex]?.dataset.price || 0;
    const row = select.closest('.product-row');
    const priceDisplay = row.querySelector('[id^="price-"]');
    const quantity = row.querySelector('input[name="quantities[]"]').value || 1;
    const total = parseFloat(price) * parseInt(quantity);
    
    if (priceDisplay) {
        priceDisplay.textContent = `Rs. ${total.toFixed(2)}`;
    }
    calculateTotal();
}

function updateAllPrices() {
    document.querySelectorAll('.product-row').forEach(row => {
        const select = row.querySelector('select[name="product_ids[]"]');
        if (select && select.value) {
            updatePrice(select);
        }
    });
}

function calculateTotal() {
    let subtotal = 0;
    
    document.querySelectorAll('.product-row').forEach(row => {
        const select = row.querySelector('select[name="product_ids[]"]');
        const quantity = row.querySelector('input[name="quantities[]"]').value || 1;
        const price = select.options[select.selectedIndex]?.dataset.price || 0;
        
        subtotal += parseFloat(price) * parseInt(quantity);
    });
    
    // Get shipping and tax amounts
    const shippingCost = parseFloat(document.getElementById('shipping_cost').value) || 0;
    const taxAmount = parseFloat(document.getElementById('tax_amount').value) || 0;
    const total = subtotal + shippingCost + taxAmount;
    
    // Update order summary in sidebar if elements exist
    const subtotalEl = document.getElementById('order-subtotal');
    const shippingEl = document.getElementById('order-shipping');
    const taxEl = document.getElementById('order-tax');
    const totalEl = document.getElementById('order-total');
    
    if (subtotalEl) subtotalEl.textContent = `Rs. ${subtotal.toFixed(2)}`;
    if (shippingEl) shippingEl.textContent = `Rs. ${shippingCost.toFixed(2)}`;
    if (taxEl) taxEl.textContent = `Rs. ${taxAmount.toFixed(2)}`;
    if (totalEl) totalEl.textContent = `Rs. ${total.toFixed(2)}`;
}
</script>

<script src="{{ asset('js/orders.js') }}"></script>
@endpush