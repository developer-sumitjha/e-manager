@extends('admin.layouts.app')

@section('title', 'Inventory')
@section('page-title', 'Inventory')

@push('styles')
<style>
    /* Inventory Page Specific Styles */
    .inventory-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title-section h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
        background: linear-gradient(135deg, #10B981, #34D399);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-top: 0.5rem;
        font-weight: 400;
    }

    .search-add-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        gap: 1rem;
    }

    .search-box {
        position: relative;
        flex: 1;
        max-width: 400px;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        z-index: 2;
    }

    .search-box input {
        padding-left: 3rem;
        border-radius: 0.75rem;
        border: 1px solid rgba(16, 185, 129, 0.2);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        border-color: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        background: white;
    }

    .add-product-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #10B981, #34D399);
        color: white;
        text-decoration: none;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .add-product-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        background: rgba(255, 255, 255, 0.8);
        padding: 0.5rem;
        border-radius: 1rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        overflow-x: auto;
    }

    .filter-tab {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 0.75rem;
        text-decoration: none;
        color: var(--text-secondary);
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        white-space: nowrap;
        position: relative;
    }

    .filter-tab:hover {
        color: #10B981;
        background: rgba(16, 185, 129, 0.05);
    }

    .filter-tab.active {
        color: white;
        background: linear-gradient(135deg, #10B981, #34D399);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .filter-tab .count-badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.125rem 0.375rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        min-width: 1.5rem;
        text-align: center;
    }

    .filter-tab:not(.active) .count-badge {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .bulk-actions-bar {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(-10px);
    }

    .bulk-actions-bar.show {
        opacity: 1;
        transform: translateY(0);
    }

    .selected-count {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    .bulk-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .bulk-action-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .bulk-action-btn.update-stock {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
        border-color: rgba(59, 130, 246, 0.2);
    }

    .bulk-action-btn.update-stock:hover {
        background: rgba(59, 130, 246, 0.2);
        color: #3B82F6;
    }

    .bulk-action-btn.delete {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
        border-color: rgba(239, 68, 68, 0.2);
    }

    .bulk-action-btn.delete:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #EF4444;
    }

    .inventory-table {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        overflow-x: auto;
    }

    .table {
        margin: 0;
    }

    .table th {
        border: none;
        background: rgba(16, 185, 129, 0.05);
        color: var(--text-primary);
        font-weight: 600;
        padding: 1rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table td {
        border: none;
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(16, 185, 129, 0.05);
    }

    .table tbody tr:hover {
        background: rgba(16, 185, 129, 0.02);
    }

    .table tbody tr.selected {
        background: rgba(16, 185, 129, 0.05);
    }

    .product-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 0.25rem;
        border: 2px solid rgba(16, 185, 129, 0.3);
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .product-checkbox:checked {
        background: #10B981;
        border-color: #10B981;
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .product-image {
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        object-fit: cover;
        border: 1px solid rgba(16, 185, 129, 0.1);
        background: rgba(16, 185, 129, 0.05);
    }

    .product-details h6 {
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 0.25rem 0;
        font-size: 0.875rem;
    }

    .product-sku {
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-family: 'Courier New', monospace;
    }

    .stock-quantity {
        font-weight: 600;
        font-size: 1rem;
        color: var(--text-primary);
    }

    .stock-status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .stock-status-in-stock {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .stock-status-low-stock {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }

    .stock-status-out-of-stock {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .price-value {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .category-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 500;
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        margin: 0.125rem;
    }

    .action-btn.view {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
        border-color: rgba(59, 130, 246, 0.2);
    }

    .action-btn.view:hover {
        background: rgba(59, 130, 246, 0.2);
        color: #3B82F6;
    }

    .action-btn.edit {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
        border-color: rgba(245, 158, 11, 0.2);
    }

    .action-btn.edit:hover {
        background: rgba(245, 158, 11, 0.2);
        color: #F59E0B;
    }

    .action-btn.delete {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
        border-color: rgba(239, 68, 68, 0.2);
    }

    .action-btn.delete:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #EF4444;
    }

    .select-all-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 0.25rem;
        border: 2px solid rgba(16, 185, 129, 0.3);
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .select-all-checkbox:checked {
        background: #10B981;
        border-color: #10B981;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .search-add-bar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-box {
            max-width: none;
        }
        
        .filter-tabs {
            flex-wrap: wrap;
        }
        
        .bulk-actions-bar {
            flex-direction: column;
            align-items: stretch;
            gap: 0.75rem;
        }
        
        .bulk-actions {
            justify-content: center;
        }
        
        .product-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="inventory-header">
    <div class="page-title-section">
        <h1>Inventory</h1>
        <p class="page-subtitle">Manage your product inventory</p>
    </div>
</div>

<div class="search-add-bar">
    <div class="search-box">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="inventory-search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
    </div>
    <a href="{{ route('admin.products.create') }}" class="add-product-btn">
        <i class="fas fa-plus"></i> Add Product
    </a>
</div>

<div class="filter-tabs">
    <a href="{{ route('admin.inventory.index', ['filter' => '', 'search' => request('search')]) }}" class="filter-tab {{ request('filter') == '' ? 'active' : '' }}">
        <span>All</span>
        <span class="count-badge">{{ $allCount }}</span>
    </a>
    <a href="{{ route('admin.inventory.index', ['filter' => 'low_stock', 'search' => request('search')]) }}" class="filter-tab {{ request('filter') == 'low_stock' ? 'active' : '' }}">
        <span>Low Stock</span>
        <span class="count-badge">{{ $lowStockCount }}</span>
    </a>
    <a href="{{ route('admin.inventory.index', ['filter' => 'out_of_stock', 'search' => request('search')]) }}" class="filter-tab {{ request('filter') == 'out_of_stock' ? 'active' : '' }}">
        <span>Out of Stock</span>
        <span class="count-badge">{{ $outOfStockCount }}</span>
    </a>
</div>

<div class="bulk-actions-bar" id="bulk-actions-bar">
    <div class="selected-count">
        <span id="selected-count">0</span> products selected
    </div>
    <div class="bulk-actions">
        <button type="button" class="bulk-action-btn update-stock" onclick="bulkUpdateStock()">
            <i class="fas fa-edit"></i> Update Stock
        </button>
        <button type="button" class="bulk-action-btn delete" onclick="bulkDelete()">
            <i class="fas fa-trash"></i> Delete
        </button>
    </div>
</div>

<div class="inventory-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width: 50px;">
                        <input type="checkbox" class="select-all-checkbox" id="select-all">
                    </th>
                    <th style="width: 300px;">Product</th>
                    <th style="width: 100px;">Stock</th>
                    <th style="width: 120px;">Price</th>
                    <th style="width: 150px;">Category</th>
                    <th style="width: 200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr data-product-id="{{ $product->id }}">
                    <td>
                        <input type="checkbox" class="product-checkbox" value="{{ $product->id }}">
                    </td>
                    <td>
                        <div class="product-info">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/placeholder-product.jpg') }}" 
                                 alt="{{ $product->name }}" class="product-image">
                            <div class="product-details">
                                <h6>{{ $product->name }}</h6>
                                <div class="product-sku">{{ $product->sku }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="stock-quantity">{{ $product->stock }}</div>
                        <div class="stock-status-badge {{ $product->stock == 0 ? 'stock-status-out-of-stock' : ($product->stock <= 20 ? 'stock-status-low-stock' : 'stock-status-in-stock') }}">
                            {{ $product->stock == 0 ? 'Out of Stock' : ($product->stock <= 20 ? 'Low Stock' : 'In Stock') }}
                        </div>
                    </td>
                    <td>
                        <div class="price-value">₹{{ number_format($product->sale_price ?? $product->price, 2) }}</div>
                    </td>
                    <td>
                        <span class="category-badge">{{ $product->category->name }}</span>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap">
                            <a href="{{ route('admin.products.show', $product->id) }}" class="action-btn view">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="action-btn edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button" class="action-btn delete" onclick="deleteProduct({{ $product->id }})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <i class="fas fa-boxes text-muted" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <h5 class="text-muted">No products found</h5>
                        <p class="text-muted">Add your first product to get started.</p>
                        <a href="{{ route('admin.products.create') }}" class="add-product-btn">
                            <i class="fas fa-plus"></i> Add Product
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($products->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
    @endif
</div>

<!-- Update Stock Modal -->
<div class="modal fade" id="updateStockModal" tabindex="-1" aria-labelledby="updateStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStockModalLabel">Update Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateStockForm">
                    <div class="mb-3">
                        <label for="stockQuantity" class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control" id="stockQuantity" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="stockNotes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="stockNotes" rows="3" placeholder="Add any notes about this stock update..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmUpdateStock()">Update Stock</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let selectedProducts = [];

    document.addEventListener('DOMContentLoaded', function() {
        const inventorySearch = document.getElementById('inventory-search');
        const selectAllCheckbox = document.getElementById('select-all');
        const productCheckboxes = document.querySelectorAll('.product-checkbox');

        // Live Search with Debounce
        inventorySearch.addEventListener('keyup', debounce(function() {
            const searchValue = this.value;
            const currentFilter = new URLSearchParams(window.location.search).get('filter') || '';
            window.location.href = `{{ route('admin.inventory.index') }}?search=${searchValue}&filter=${currentFilter}`;
        }, 300));

        // Select All functionality
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                updateProductSelection(checkbox.value, isChecked);
            });
            updateBulkActionsBar();
        });

        // Individual checkbox functionality
        productCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateProductSelection(this.value, this.checked);
                updateBulkActionsBar();
                updateSelectAllCheckbox();
            });
        });
    });

    function updateProductSelection(productId, isSelected) {
        if (isSelected) {
            if (!selectedProducts.includes(productId)) {
                selectedProducts.push(productId);
            }
        } else {
            selectedProducts = selectedProducts.filter(id => id !== productId);
        }
    }

    function updateBulkActionsBar() {
        const bulkActionsBar = document.getElementById('bulk-actions-bar');
        const selectedCount = document.getElementById('selected-count');
        
        selectedCount.textContent = selectedProducts.length;
        
        if (selectedProducts.length > 0) {
            bulkActionsBar.classList.add('show');
        } else {
            bulkActionsBar.classList.remove('show');
        }
    }

    function updateSelectAllCheckbox() {
        const selectAllCheckbox = document.getElementById('select-all');
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.product-checkbox:checked');
        
        if (checkedCheckboxes.length === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedCheckboxes.length === productCheckboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }

    function bulkUpdateStock() {
        if (selectedProducts.length === 0) {
            showNotification('Please select products to update stock.', 'error');
            return;
        }
        
        const updateStockModal = new bootstrap.Modal(document.getElementById('updateStockModal'));
        updateStockModal.show();
    }

    function confirmUpdateStock() {
        const stockQuantity = document.getElementById('stockQuantity').value;
        const stockNotes = document.getElementById('stockNotes').value;
        
        if (!stockQuantity) {
            showNotification('Please enter stock quantity.', 'error');
            return;
        }
        
        fetch('{{ route('admin.inventory.bulk-update-stock') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_ids: selectedProducts,
                stock_quantity: stockQuantity,
                notes: stockNotes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                const updateStockModal = bootstrap.Modal.getInstance(document.getElementById('updateStockModal'));
                updateStockModal.hide();
                document.getElementById('updateStockForm').reset();
                selectedProducts = [];
                updateBulkActionsBar();
                location.reload();
            } else {
                showNotification(data.message || 'Failed to update stock.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while updating stock.', 'error');
        });
    }

    function bulkDelete() {
        if (selectedProducts.length === 0) {
            showNotification('Please select products to delete.', 'error');
            return;
        }
        
        if (confirm(`Are you sure you want to delete ${selectedProducts.length} products? This action cannot be undone.`)) {
            fetch('{{ route('admin.inventory.bulk-delete') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_ids: selectedProducts
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    selectedProducts = [];
                    updateBulkActionsBar();
                    location.reload();
                } else {
                    showNotification(data.message || 'Failed to delete products.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while deleting products.', 'error');
            });
        }
    }

    function deleteProduct(productId) {
        if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            fetch(`/admin/products/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    showNotification('Failed to delete product.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while deleting product.', 'error');
            });
        }
    }
</script>
@endpush





