@extends('admin.layouts.app')

@section('title', 'Bulk Order Creation')
@section('page-title', 'Bulk Order Creation')

@push('styles')
<style>
    .bulk-order-container {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .excel-table-container {
        overflow-x: auto;
        margin-top: 2rem;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }

    .excel-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1200px;
    }

    .excel-table thead {
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        color: white;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .excel-table th {
        padding: 1rem 0.75rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.9rem;
        white-space: nowrap;
        border-right: 1px solid rgba(255, 255, 255, 0.2);
    }

    .excel-table th:last-child {
        border-right: none;
    }

    .excel-table tbody tr {
        border-bottom: 1px solid #e5e7eb;
        transition: background 0.2s ease;
    }

    .excel-table tbody tr:hover {
        background: #f9fafb;
    }

    .excel-table td {
        padding: 0.5rem 0.75rem;
        border-right: 1px solid #e5e7eb;
        vertical-align: middle;
    }

    .excel-table td:last-child {
        border-right: none;
    }

    .excel-input {
        width: 100%;
        border: 1px solid transparent;
        padding: 0.5rem;
        font-size: 0.9rem;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .excel-input:focus {
        border-color: #8B5CF6;
        outline: none;
        background: #f3f4f6;
    }

    .excel-input.required {
        background: rgba(139, 92, 246, 0.05);
    }

    .excel-select {
        width: 100%;
        border: 1px solid #e5e7eb;
        padding: 0.5rem;
        font-size: 0.9rem;
        border-radius: 4px;
        background: white;
    }

    .excel-select:focus {
        border-color: #8B5CF6;
        outline: none;
    }

    .required-badge {
        color: #ef4444;
        font-weight: bold;
        font-size: 0.8rem;
    }

    .optional-badge {
        color: #6b7280;
        font-size: 0.75rem;
        font-style: italic;
    }

    .row-number {
        background: #f3f4f6;
        font-weight: 600;
        color: #6b7280;
        text-align: center;
        min-width: 50px;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        margin-top: 2rem;
        justify-content: space-between;
        align-items: center;
    }

    .btn-add-row {
        background: #10b981;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-add-row:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    .btn-remove-row {
        background: #ef4444;
        color: white;
        border: none;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.85rem;
        cursor: pointer;
    }

    .btn-create-orders {
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-create-orders:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.4);
    }

    .order-count {
        background: rgba(139, 92, 246, 0.1);
        color: #8B5CF6;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
    }

    .product-row {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .product-row:last-child {
        margin-bottom: 0;
    }

    .btn-add-product {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        cursor: pointer;
    }

    .btn-remove-product {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        cursor: pointer;
    }

    .help-text {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 1rem;
        padding: 1rem;
        background: #f9fafb;
        border-left: 4px solid #8B5CF6;
        border-radius: 4px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="bulk-order-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title mb-2">
                    <i class="fas fa-table"></i> Bulk Order Creation
                </h1>
                <p class="text-muted">Create multiple orders quickly with Excel-like interface</p>
            </div>
            <a href="{{ route('admin.pending-orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>

        <div class="help-text">
            <strong><i class="fas fa-info-circle"></i> Quick Guide:</strong><br>
            • <span class="required-badge">*</span> Required fields: Customer Name, Phone, Product & Quantity<br>
            • Optional fields can be filled now or edited later before confirming<br>
            • Click <strong>"+ Add Row"</strong> to add more orders<br>
            • Click <strong>"Create Orders"</strong> when done - orders will be saved as pending
        </div>

        <form id="bulkOrderForm">
            @csrf
            
            <div class="excel-table-container">
                <table class="excel-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 200px;">Customer Name <span class="required-badge">*</span></th>
                            <th style="width: 150px;">Phone <span class="required-badge">*</span></th>
                            <th style="width: 250px;">Product <span class="required-badge">*</span></th>
                            <th style="width: 100px;">Quantity <span class="required-badge">*</span></th>
                            <th style="width: 200px;">Address <span class="optional-badge">(optional)</span></th>
                            <th style="width: 150px;">Payment Method <span class="optional-badge">(optional)</span></th>
                            <th style="width: 150px;">Notes <span class="optional-badge">(optional)</span></th>
                            <th style="width: 80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody">
                        <!-- Initial row -->
                        <tr class="order-row" data-row="1">
                            <td class="row-number">1</td>
                            <td>
                                <input type="text" 
                                       name="orders[1][customer_name]" 
                                       class="excel-input required" 
                                       placeholder="John Doe" 
                                       required>
                            </td>
                            <td>
                                <input type="text" 
                                       name="orders[1][customer_phone]" 
                                       class="excel-input required" 
                                       placeholder="9800000000" 
                                       required>
                            </td>
                            <td>
                                <div class="products-container" data-row="1">
                                    <div class="product-row">
                                        <select name="orders[1][products][0][id]" 
                                                class="excel-select" 
                                                style="flex: 1;" 
                                                required>
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }} - Rs. {{ number_format($product->sale_price ?? $product->price, 0) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button type="button" class="btn-add-product mt-1" onclick="addProduct(1)">
                                    <i class="fas fa-plus"></i> Add Product
                                </button>
                            </td>
                            <td>
                                <div class="quantities-container" data-row="1">
                                    <input type="number" 
                                           name="orders[1][products][0][quantity]" 
                                           class="excel-input required" 
                                           placeholder="1" 
                                           min="1" 
                                           value="1" 
                                           required>
                                </div>
                            </td>
                            <td>
                                <input type="text" 
                                       name="orders[1][shipping_address]" 
                                       class="excel-input" 
                                       placeholder="Kathmandu, Nepal">
                            </td>
                            <td>
                                <select name="orders[1][payment_method]" class="excel-select">
                                    <option value="cash_on_delivery">COD</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="khalti">Khalti</option>
                                    <option value="esewa">eSewa</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" 
                                       name="orders[1][notes]" 
                                       class="excel-input" 
                                       placeholder="Optional notes">
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn-remove-row" onclick="removeRow(1)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="action-buttons">
                <div class="d-flex gap-2">
                    <button type="button" class="btn-add-row" onclick="addRow()">
                        <i class="fas fa-plus"></i> Add Row
                    </button>
                    <span class="order-count">
                        <i class="fas fa-list"></i> <span id="rowCount">1</span> Order(s)
                    </span>
                </div>
                <button type="submit" class="btn-create-orders">
                    <i class="fas fa-check"></i> Create Orders
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let rowCounter = 1;

function addRow() {
    rowCounter++;
    const tbody = document.getElementById('ordersTableBody');
    
    const newRow = document.createElement('tr');
    newRow.className = 'order-row';
    newRow.setAttribute('data-row', rowCounter);
    
    newRow.innerHTML = `
        <td class="row-number">${rowCounter}</td>
        <td>
            <input type="text" 
                   name="orders[${rowCounter}][customer_name]" 
                   class="excel-input required" 
                   placeholder="John Doe" 
                   required>
        </td>
        <td>
            <input type="text" 
                   name="orders[${rowCounter}][customer_phone]" 
                   class="excel-input required" 
                   placeholder="9800000000" 
                   required>
        </td>
        <td>
            <div class="products-container" data-row="${rowCounter}">
                <div class="product-row">
                    <select name="orders[${rowCounter}][products][0][id]" 
                            class="excel-select" 
                            style="flex: 1;" 
                            required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} - Rs. {{ number_format($product->sale_price ?? $product->price, 0) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="button" class="btn-add-product mt-1" onclick="addProduct(${rowCounter})">
                <i class="fas fa-plus"></i> Add Product
            </button>
        </td>
        <td>
            <div class="quantities-container" data-row="${rowCounter}">
                <input type="number" 
                       name="orders[${rowCounter}][products][0][quantity]" 
                       class="excel-input required" 
                       placeholder="1" 
                       min="1" 
                       value="1" 
                       required>
            </div>
        </td>
        <td>
            <input type="text" 
                   name="orders[${rowCounter}][shipping_address]" 
                   class="excel-input" 
                   placeholder="Kathmandu, Nepal">
        </td>
        <td>
            <select name="orders[${rowCounter}][payment_method]" class="excel-select">
                <option value="cash_on_delivery">COD</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="khalti">Khalti</option>
                <option value="esewa">eSewa</option>
            </select>
        </td>
        <td>
            <input type="text" 
                   name="orders[${rowCounter}][notes]" 
                   class="excel-input" 
                   placeholder="Optional notes">
        </td>
        <td style="text-align: center;">
            <button type="button" class="btn-remove-row" onclick="removeRow(${rowCounter})">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(newRow);
    updateRowCount();
}

function removeRow(rowNum) {
    const row = document.querySelector(`tr[data-row="${rowNum}"]`);
    if (row) {
        if (document.querySelectorAll('.order-row').length > 1) {
            row.remove();
            updateRowCount();
        } else {
            alert('At least one order row is required!');
        }
    }
}

function addProduct(rowNum) {
    const container = document.querySelector(`.products-container[data-row="${rowNum}"]`);
    const quantitiesContainer = document.querySelector(`.quantities-container[data-row="${rowNum}"]`);
    const productCount = container.querySelectorAll('.product-row').length;
    
    // Add product select
    const productDiv = document.createElement('div');
    productDiv.className = 'product-row';
    productDiv.innerHTML = `
        <select name="orders[${rowNum}][products][${productCount}][id]" 
                class="excel-select" 
                style="flex: 1;" 
                required>
            <option value="">Select Product</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }} - Rs. {{ number_format($product->sale_price ?? $product->price, 0) }}</option>
            @endforeach
        </select>
        <button type="button" class="btn-remove-product" onclick="this.parentElement.remove(); removeProductQuantity(${rowNum}, ${productCount})">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(productDiv);
    
    // Add quantity input
    const quantityInput = document.createElement('input');
    quantityInput.type = 'number';
    quantityInput.name = `orders[${rowNum}][products][${productCount}][quantity]`;
    quantityInput.className = 'excel-input required';
    quantityInput.placeholder = '1';
    quantityInput.min = '1';
    quantityInput.value = '1';
    quantityInput.required = true;
    quantityInput.style.marginBottom = '0.5rem';
    quantitiesContainer.appendChild(quantityInput);
}

function removeProductQuantity(rowNum, productIndex) {
    const quantitiesContainer = document.querySelector(`.quantities-container[data-row="${rowNum}"]`);
    const inputs = quantitiesContainer.querySelectorAll('input');
    if (inputs[productIndex]) {
        inputs[productIndex].remove();
    }
}

function updateRowCount() {
    const count = document.querySelectorAll('.order-row').length;
    document.getElementById('rowCount').textContent = count;
}

// Form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bulkOrderForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        console.log('Submitting bulk orders...');
        
        const formData = new FormData(this);
        
        // Show loading
        const submitBtn = this.querySelector('.btn-create-orders');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
        submitBtn.disabled = true;
        
        fetch('{{ route("admin.pending-orders.store-bulk") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response:', data);
            
            if (data.success) {
                alert(`✅ Success! Created ${data.count} orders.\n\nRedirecting to pending orders...`);
                window.location.href = '{{ route("admin.pending-orders.index") }}';
            } else {
                alert('❌ Error: ' + (data.message || 'An error occurred'));
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ An error occurred. Please check all required fields.');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + Enter to submit
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        document.getElementById('bulkOrderForm').dispatchEvent(new Event('submit'));
    }
    
    // Ctrl/Cmd + N to add new row
    if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
        e.preventDefault();
        addRow();
    }
});
</script>
@endpush
