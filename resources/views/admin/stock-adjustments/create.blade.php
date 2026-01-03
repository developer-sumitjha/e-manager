@extends('admin.layouts.app')

@section('title', 'Create Stock Adjustment')
@section('page-title', 'Create Stock Adjustment')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('admin.stock-adjustments.store') }}" method="POST" id="adjustmentForm">
            @csrf
            
            {{-- Product Selection --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Product Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Select Product *</label>
                        <select class="form-select @error('product_id') is-invalid @enderror" 
                                id="product_id" name="product_id" required onchange="fetchProductStock()">
                            <option value="">-- Select a Product --</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} (SKU: {{ $product->sku }}) - Current Stock: {{ $product->stock }}
                            </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div id="productInfo" class="alert alert-info" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Product:</strong> <span id="productName"></span>
                            </div>
                            <div class="col-md-6">
                                <strong>SKU:</strong> <span id="productSku"></span>
                            </div>
                            <div class="col-md-12 mt-2">
                                <strong>Current Stock:</strong> 
                                <span id="currentStock" class="badge bg-primary fs-5"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Adjustment Details --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-sliders-h me-2"></i>Adjustment Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Adjustment Type *</label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" name="type" required onchange="updateCalculation()">
                                <option value="">-- Select Type --</option>
                                <option value="increase" {{ old('type') == 'increase' ? 'selected' : '' }}>
                                    <i class="fas fa-arrow-up"></i> Increase Stock
                                </option>
                                <option value="decrease" {{ old('type') == 'decrease' ? 'selected' : '' }}>
                                    <i class="fas fa-arrow-down"></i> Decrease Stock
                                </option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">Quantity *</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" value="{{ old('quantity', 1) }}" 
                                   min="1" required onchange="updateCalculation()">
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Number of units to adjust</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="reason" class="form-label">Reason *</label>
                            <select class="form-select @error('reason') is-invalid @enderror" 
                                    id="reason" name="reason" required>
                                <option value="">-- Select Reason --</option>
                                @foreach($reasons as $key => $label)
                                <option value="{{ $key }}" {{ old('reason') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="adjustment_date" class="form-label">Adjustment Date *</label>
                            <input type="date" class="form-control @error('adjustment_date') is-invalid @enderror" 
                                   id="adjustment_date" name="adjustment_date" 
                                   value="{{ old('adjustment_date', date('Y-m-d')) }}" required>
                            @error('adjustment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reference_number" class="form-label">Reference Number</label>
                        <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                               id="reference_number" name="reference_number" value="{{ old('reference_number') }}" 
                               placeholder="e.g., RET-001, DMG-202501">
                        @error('reference_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Optional: Link to related document or transaction</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="4" 
                                  placeholder="Add any additional details about this adjustment...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            {{-- Submit Buttons --}}
            <div class="d-flex gap-2 mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check me-2"></i>Create Adjustment
                </button>
                <a href="{{ route('admin.stock-adjustments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
    
    {{-- Preview Sidebar --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Adjustment Preview</h5>
            </div>
            <div class="card-body">
                <div id="previewContent" class="text-center text-muted py-4">
                    <i class="fas fa-info-circle fa-3x mb-3 d-block"></i>
                    <p>Select a product and fill in the details to see the preview</p>
                </div>
                
                <div id="calculationPreview" style="display: none;">
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Current Stock:</span>
                            <strong id="previewCurrentStock">0</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Adjustment:</span>
                            <strong id="previewAdjustment" class="text-primary">±0</strong>
                        </div>
                    </div>
                    
                    <div class="text-center py-3">
                        <div class="text-muted mb-1">New Stock Level</div>
                        <div id="previewNewStock" class="display-4 fw-bold text-primary">0</div>
                        <div id="stockWarning" class="alert alert-warning mt-3" style="display: none;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Stock will be negative!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentProductStock = 0;

function fetchProductStock() {
    const productId = document.getElementById('product_id').value;
    
    if (!productId) {
        document.getElementById('productInfo').style.display = 'none';
        document.getElementById('calculationPreview').style.display = 'none';
        document.getElementById('previewContent').style.display = 'block';
        return;
    }
    
    fetch(`/admin/stock-adjustments/product/${productId}/stock`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentProductStock = data.stock;
                
                // Update product info
                document.getElementById('productName').textContent = data.name;
                document.getElementById('productSku').textContent = data.sku;
                document.getElementById('currentStock').textContent = data.stock;
                document.getElementById('productInfo').style.display = 'block';
                
                // Update calculation
                updateCalculation();
            }
        })
        .catch(error => {
            console.error('Error fetching product stock:', error);
        });
}

function updateCalculation() {
    const productId = document.getElementById('product_id').value;
    const type = document.getElementById('type').value;
    const quantity = parseInt(document.getElementById('quantity').value) || 0;
    
    if (!productId || !type || quantity === 0) {
        return;
    }
    
    // Show calculation preview
    document.getElementById('previewContent').style.display = 'none';
    document.getElementById('calculationPreview').style.display = 'block';
    
    // Calculate new stock
    let newStock;
    let adjustmentText;
    
    if (type === 'increase') {
        newStock = currentProductStock + quantity;
        adjustmentText = `+${quantity}`;
        document.getElementById('previewAdjustment').className = 'text-success fw-bold';
        document.getElementById('previewNewStock').className = 'display-4 fw-bold text-success';
    } else {
        newStock = currentProductStock - quantity;
        adjustmentText = `-${quantity}`;
        document.getElementById('previewAdjustment').className = 'text-danger fw-bold';
        document.getElementById('previewNewStock').className = 'display-4 fw-bold ' + (newStock < 0 ? 'text-danger' : 'text-primary');
    }
    
    // Update preview
    document.getElementById('previewCurrentStock').textContent = currentProductStock;
    document.getElementById('previewAdjustment').textContent = adjustmentText;
    document.getElementById('previewNewStock').textContent = newStock;
    
    // Show warning if stock will be negative
    const warningDiv = document.getElementById('stockWarning');
    if (newStock < 0) {
        warningDiv.style.display = 'block';
        warningDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Warning: Stock will be negative! (' + newStock + ')';
    } else {
        warningDiv.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    if (productSelect.value) {
        fetchProductStock();
    }
});
</script>
@endpush
@endsection






