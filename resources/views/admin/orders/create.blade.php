@extends('admin.layouts.app')

@section('title', 'Create Order')
@section('page-title', 'Create New Order')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('admin.orders.store') }}" method="POST" id="createOrderForm">
            @csrf
            
            {{-- Customer Information --}}
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="receiver_name" class="form-label">Customer Name *</label>
                            <input type="text" class="form-control @error('receiver_name') is-invalid @enderror" 
                                   id="receiver_name" name="receiver_name" value="{{ old('receiver_name') }}" required>
                            @error('receiver_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="receiver_phone" class="form-label">Phone Number *</label>
                            <input type="text" class="form-control @error('receiver_phone') is-invalid @enderror" 
                                   id="receiver_phone" name="receiver_phone" value="{{ old('receiver_phone') }}" required>
                            @error('receiver_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="receiver_city" class="form-label">City *</label>
                            <input type="text" class="form-control @error('receiver_city') is-invalid @enderror" 
                                   id="receiver_city" name="receiver_city" value="{{ old('receiver_city') }}" required>
                            @error('receiver_city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="receiver_area" class="form-label">Area</label>
                            <input type="text" class="form-control @error('receiver_area') is-invalid @enderror" 
                                   id="receiver_area" name="receiver_area" value="{{ old('receiver_area') }}">
                            @error('receiver_area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="receiver_full_address" class="form-label">Full Address *</label>
                        <textarea class="form-control @error('receiver_full_address') is-invalid @enderror" 
                                  id="receiver_full_address" name="receiver_full_address" rows="3" required>{{ old('receiver_full_address') }}</textarea>
                        @error('receiver_full_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            {{-- Order Items --}}
            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Order Items</h5>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addOrderItem()">
                        <i class="fas fa-plus me-1"></i> Add Item
                    </button>
                </div>
                <div class="card-body">
                    <div id="orderItemsContainer">
                        <div class="order-item-row mb-3 p-3 border rounded" data-index="0">
                            <div class="row align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label">Product *</label>
                                    <select class="form-select product-select" name="items[0][product_id]" 
                                            data-index="0" onchange="updateProductPrice(this)" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                                data-price="{{ $product->sale_price ?? $product->price }}"
                                                data-name="{{ $product->name }}">
                                            {{ $product->name }} (SKU: {{ $product->sku ?? 'N/A' }}) - Rs. {{ number_format($product->sale_price ?? $product->price, 2) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="items[0][product_name]" class="product-name-input">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Quantity *</label>
                                    <input type="number" class="form-control quantity-input" name="items[0][quantity]" 
                                           value="1" min="1" required onchange="calculateTotal()">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Price *</label>
                                    <input type="number" class="form-control price-input" name="items[0][price]" 
                                           value="0" min="0" step="0.01" required onchange="calculateTotal()">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Subtotal</label>
                                    <input type="text" class="form-control item-subtotal" readonly value="0.00">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeOrderItem(this)" style="display: none;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Order Details --}}
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="payment_method" class="form-label">Payment Method *</label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" name="payment_method" required>
                                <option value="">Select Payment Method</option>
                                <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
                                <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online Payment</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="payment_status" class="form-label">Payment Status *</label>
                            <select class="form-select @error('payment_status') is-invalid @enderror" 
                                    id="payment_status" name="payment_status" required>
                                <option value="unpaid" {{ old('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                            @error('payment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Order Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ old('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="delivery_type" class="form-label">Delivery Type</label>
                            <select class="form-select @error('delivery_type') is-invalid @enderror" 
                                    id="delivery_type" name="delivery_type">
                                <option value="standard" {{ old('delivery_type') == 'standard' ? 'selected' : '' }}>Standard</option>
                                <option value="express" {{ old('delivery_type') == 'express' ? 'selected' : '' }}>Express</option>
                            </select>
                            @error('delivery_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Order Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="delivery_instructions" class="form-label">Delivery Instructions</label>
                        <textarea class="form-control @error('delivery_instructions') is-invalid @enderror" 
                                  id="delivery_instructions" name="delivery_instructions" rows="2">{{ old('delivery_instructions') }}</textarea>
                        @error('delivery_instructions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            {{-- Submit Buttons --}}
            <div class="d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Create Order
                </button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
    
    {{-- Order Summary Sidebar --}}
    <div class="col-lg-4">
        <div class="card sticky-top" style="top: 100px;">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <strong id="summarySubtotal">Rs. 0.00</strong>
                </div>
                <div class="mb-3">
                    <label for="shipping_amount" class="form-label">Shipping:</label>
                    <input type="number" class="form-control" id="shipping_amount" name="shipping" 
                           value="0" min="0" step="0.01" onchange="calculateTotal()" form="createOrderForm">
                </div>
                <div class="mb-3">
                    <label for="tax_amount" class="form-label">Tax:</label>
                    <input type="number" class="form-control" id="tax_amount" name="tax" 
                           value="0" min="0" step="0.01" onchange="calculateTotal()" form="createOrderForm">
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <strong class="text-primary fs-4" id="summaryTotal">Rs. 0.00</strong>
                </div>
                <input type="hidden" name="subtotal" id="hiddenSubtotal" value="0" form="createOrderForm">
                <input type="hidden" name="total" id="hiddenTotal" value="0" form="createOrderForm">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let itemIndex = 1;

// Store products data for JavaScript
@php
$productsArray = $products->map(function($product) {
    return [
        'id' => $product->id,
        'name' => $product->name,
        'sku' => $product->sku ?? 'N/A',
        'price' => $product->sale_price ?? $product->price,
    ];
})->values()->toArray();
@endphp
const productsData = @json($productsArray);

function addOrderItem() {
    const container = document.getElementById('orderItemsContainer');
    const productOptions = productsData.map(product => 
        `<option value="${product.id}" data-price="${product.price}" data-name="${product.name}">
            ${product.name} (SKU: ${product.sku}) - Rs. ${parseFloat(product.price).toFixed(2)}
        </option>`
    ).join('');
    
    const newItem = `
        <div class="order-item-row mb-3 p-3 border rounded" data-index="${itemIndex}">
            <div class="row align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Product *</label>
                    <select class="form-select product-select" name="items[${itemIndex}][product_id]" 
                            data-index="${itemIndex}" onchange="updateProductPrice(this)" required>
                        <option value="">-- Select Product --</option>
                        ${productOptions}
                    </select>
                    <input type="hidden" name="items[${itemIndex}][product_name]" class="product-name-input">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity *</label>
                    <input type="number" class="form-control quantity-input" name="items[${itemIndex}][quantity]" 
                           value="1" min="1" required onchange="calculateTotal()">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Price *</label>
                    <input type="number" class="form-control price-input" name="items[${itemIndex}][price]" 
                           value="0" min="0" step="0.01" required onchange="calculateTotal()">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Subtotal</label>
                    <input type="text" class="form-control item-subtotal" readonly value="0.00">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeOrderItem(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newItem);
    itemIndex++;
    updateRemoveButtons();
}

function updateProductPrice(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const price = selectedOption.getAttribute('data-price') || 0;
    const productName = selectedOption.getAttribute('data-name') || '';
    const row = selectElement.closest('.order-item-row');
    
    // Update price input
    const priceInput = row.querySelector('.price-input');
    if (priceInput) {
        priceInput.value = parseFloat(price).toFixed(2);
    }
    
    // Update product name hidden input
    const nameInput = row.querySelector('.product-name-input');
    if (nameInput) {
        nameInput.value = productName;
    }
    
    // Recalculate total
    calculateTotal();
}

function removeOrderItem(button) {
    button.closest('.order-item-row').remove();
    calculateTotal();
    updateRemoveButtons();
}

function updateRemoveButtons() {
    const items = document.querySelectorAll('.order-item-row');
    items.forEach((item, index) => {
        const removeBtn = item.querySelector('.btn-danger');
        if (items.length > 1) {
            removeBtn.style.display = 'block';
        } else {
            removeBtn.style.display = 'none';
        }
    });
}

function calculateTotal() {
    let subtotal = 0;
    
    // Calculate each item's subtotal
    document.querySelectorAll('.order-item-row').forEach(row => {
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const itemSubtotal = quantity * price;
        
        row.querySelector('.item-subtotal').value = itemSubtotal.toFixed(2);
        subtotal += itemSubtotal;
    });
    
    const shipping = parseFloat(document.getElementById('shipping_amount').value) || 0;
    const tax = parseFloat(document.getElementById('tax_amount').value) || 0;
    const total = subtotal + shipping + tax;
    
    // Update summary
    document.getElementById('summarySubtotal').textContent = 'Rs. ' + subtotal.toFixed(2);
    document.getElementById('summaryTotal').textContent = 'Rs. ' + total.toFixed(2);
    
    // Update hidden fields
    document.getElementById('hiddenSubtotal').value = subtotal.toFixed(2);
    document.getElementById('hiddenTotal').value = total.toFixed(2);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
    updateRemoveButtons();
    
    // Initialize product name for first row if product is selected
    const firstProductSelect = document.querySelector('[name="items[0][product_id]"]');
    if (firstProductSelect && firstProductSelect.value) {
        updateProductPrice(firstProductSelect);
    }
});
</script>
@endpush

@endsection






