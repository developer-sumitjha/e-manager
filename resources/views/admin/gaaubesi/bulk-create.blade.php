@extends('admin.layouts.app')

@section('title', 'Bulk Create Gaaubesi Shipments')
@section('page-title', 'Bulk Create Gaaubesi Shipments')

@push('styles')
<style>
    .bulk-create-container {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        backdrop-filter: blur(10px);
    }

    .orders-selection {
        margin-bottom: 2rem;
    }

    .order-selection-card {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        transition: all 0.3s ease;
    }

    .order-selection-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.1);
    }

    .order-selection-card.selected {
        border-color: var(--primary-color);
        background: rgba(139, 92, 246, 0.05);
    }

    .order-checkbox {
        margin-right: 1rem;
        transform: scale(1.2);
    }

    .order-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order-details {
        flex: 1;
    }

    .order-number {
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .customer-info {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .address-info {
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    .order-amount {
        text-align: right;
        font-weight: 700;
        color: var(--success-color);
    }

    .form-section {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control, .form-select {
        border-radius: 0.5rem;
        border: 1px solid rgba(229, 231, 235, 0.8);
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .btn-primary {
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
    }

    .btn-secondary {
        background: rgba(107, 114, 128, 0.1);
        color: var(--text-secondary);
        border: 1px solid rgba(107, 114, 128, 0.3);
        padding: 0.75rem 2rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: rgba(107, 114, 128, 0.2);
    }

    .selected-count {
        background: rgba(139, 92, 246, 0.1);
        color: var(--primary-color);
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: inline-block;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--text-secondary);
    }

    /* Excel-like Table Styles */
    .excel-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-top: 1rem;
    }

    .excel-table th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        padding: 0.75rem 0.5rem;
        text-align: left;
        border-bottom: 2px solid #dee2e6;
        font-size: 0.85rem;
        white-space: nowrap;
    }

    .excel-table td {
        padding: 0.5rem;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .excel-table tr:hover {
        background-color: #f8f9fa;
    }

    .excel-table tr.selected {
        background-color: #e3f2fd;
    }

    .excel-table input,
    .excel-table select {
        width: 100%;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.375rem 0.5rem;
        font-size: 0.85rem;
        background: white;
    }

    .excel-table input:focus,
    .excel-table select:focus {
        border-color: #8B5CF6;
        outline: none;
        box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.25);
    }

    .excel-table .order-info {
        font-weight: 600;
        color: #495057;
    }

    .excel-table .order-customer {
        color: #6c757d;
        font-size: 0.8rem;
    }

    .excel-table .address-cell {
        font-size: 0.75rem;
        line-height: 1.2;
        color: #495057;
        word-wrap: break-word;
        max-width: 250px;
    }

    .excel-table .order-amount {
        font-weight: 600;
        color: #28a745;
    }

    .excel-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .table-container {
        overflow-x: auto;
        border-radius: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="bulk-create-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-plus-circle"></i>
                            Bulk Create Gaaubesi Shipments
                        </h1>
                        <p class="page-subtitle">Create multiple Gaaubesi shipments at once</p>
                    </div>
                    <a href="{{ route('admin.gaaubesi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                @if($orders->count() > 0)
                <form action="{{ route('admin.gaaubesi.bulk-create.store') }}" method="POST" id="bulkCreateForm">
                    @csrf
                    
                    <!-- Orders Selection -->
                    <div class="orders-selection">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3>
                                <i class="fas fa-list-check"></i>
                                Select Orders
                            </h3>
                            <div class="selected-count" id="selectedCount" style="display: none;">
                                <span id="countText">0</span> orders selected
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                                <i class="fas fa-check-square"></i> Select All
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">
                                <i class="fas fa-square"></i> Deselect All
                            </button>
                        </div>

                        <div class="table-container">
                            <table class="excel-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">
                                            <input type="checkbox" class="excel-checkbox" onchange="toggleAllOrders()">
                                        </th>
                                        <th style="width: 120px;">Order #</th>
                                        <th style="width: 150px;">Customer</th>
                                        <th style="width: 250px;">Customer Address</th>
                                        <th style="width: 200px;">Destination Branch *</th>
                                        <th style="width: 100px;">COD Amount</th>
                                        <th style="width: 100px;">Receiver Phone</th>
                                        <th style="width: 150px;">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr data-order-id="{{ $order->id }}">
                                        <td>
                                            <input type="checkbox" 
                                                   name="order_ids[]" 
                                                   value="{{ $order->id }}" 
                                                   class="excel-checkbox order-checkbox"
                                                   onchange="updateSelectedCount()">
                                        </td>
                                        <td class="order-info">{{ $order->order_number }}</td>
                                        <td>
                                            <div class="order-info">{{ $order->user->name ?? 'N/A' }}</div>
                                            <div class="order-customer">{{ $order->user->phone ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <div class="address-cell">
                                                {{ Str::limit($order->shipping_address, 80) }}
                                            </div>
                                        </td>
                                        <td>
                                            <select name="shipments[{{ $order->id }}][destination_branch]" class="form-select" required>
                                                <option value="HEAD OFFICE">HEAD OFFICE</option>
                                                @if(isset($locations) && is_array($locations))
                                                    @foreach($locations as $location)
                                                        <option value="{{ $location }}">{{ $location }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <input type="hidden" name="shipments[{{ $order->id }}][source_branch]" value="HEAD OFFICE">
                                            <input type="hidden" name="shipments[{{ $order->id }}][package_access]" value="Can't Open">
                                            <input type="hidden" name="shipments[{{ $order->id }}][delivery_type]" value="Drop Off">
                                            <input type="hidden" name="shipments[{{ $order->id }}][receiver_name]" value="{{ $order->user->name ?? 'Customer' }}">
                                            <input type="hidden" name="shipments[{{ $order->id }}][receiver_address]" value="{{ $order->shipping_address }}">
                                        </td>
                                        <td>
                                            <input type="number" 
                                                   name="shipments[{{ $order->id }}][cod_charge]" 
                                                   value="{{ $order->total }}" 
                                                   step="0.01" 
                                                   class="order-amount"
                                                   style="border: none; background: none; font-weight: 600; color: #28a745; width: 100%;">
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   name="shipments[{{ $order->id }}][receiver_number]" 
                                                   value="{{ $order->user->phone ?? '9800000000' }}" 
                                                   class="form-control">
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   name="shipments[{{ $order->id }}][remarks]" 
                                                   placeholder="Optional..." 
                                                   class="form-control">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- Submit Buttons -->
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                            <i class="fas fa-plus"></i> Create <span id="submitCount">0</span> Shipments
                        </button>
                        <a href="{{ route('admin.gaaubesi.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
                @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No Pending Orders</h3>
                    <p>There are no orders allocated to logistics that need Gaaubesi shipments.</p>
                    <a href="{{ route('admin.shipments.index') }}" class="btn btn-primary">
                        <i class="fas fa-shipping-fast"></i> Allocate Orders to Logistics
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectAll() {
    const checkboxes = document.querySelectorAll('.order-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
        checkbox.closest('tr').classList.add('selected');
    });
    updateSelectedCount();
}

function deselectAll() {
    const checkboxes = document.querySelectorAll('.order-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
        checkbox.closest('tr').classList.remove('selected');
    });
    updateSelectedCount();
}

function toggleAllOrders() {
    const masterCheckbox = document.querySelector('thead .excel-checkbox');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    
    orderCheckboxes.forEach(checkbox => {
        checkbox.checked = masterCheckbox.checked;
        checkbox.closest('tr').classList.toggle('selected', masterCheckbox.checked);
    });
    
    updateSelectedCount();
}

function updateSelectedCount() {
    const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
    const count = checkedBoxes.length;
    
    const selectedCount = document.getElementById('selectedCount');
    const countText = document.getElementById('countText');
    const submitBtn = document.getElementById('submitBtn');
    const submitCount = document.getElementById('submitCount');
    
    // Update count display
    if (count > 0) {
        selectedCount.style.display = 'inline-block';
        countText.textContent = count;
        submitCount.textContent = count;
        submitBtn.disabled = false;
    } else {
        selectedCount.style.display = 'none';
        submitBtn.disabled = true;
    }
    
    // Update row styles
    document.querySelectorAll('tbody tr').forEach(row => {
        const checkbox = row.querySelector('.order-checkbox');
        if (checkbox && checkbox.checked) {
            row.classList.add('selected');
        } else {
            row.classList.remove('selected');
        }
    });
    
    // Update master checkbox
    const masterCheckbox = document.querySelector('thead .excel-checkbox');
    const totalCheckboxes = document.querySelectorAll('.order-checkbox').length;
    masterCheckbox.checked = count === totalCheckboxes;
    masterCheckbox.indeterminate = count > 0 && count < totalCheckboxes;
}

// Form submission
document.getElementById('bulkCreateForm').addEventListener('submit', function(e) {
    const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
    if (checkedBoxes.length === 0) {
        e.preventDefault();
        alert('Please select at least one order to create shipments.');
        return;
    }
    
    const count = checkedBoxes.length;
    if (!confirm(`Are you sure you want to create ${count} Gaaubesi shipments? This action cannot be undone.`)) {
        e.preventDefault();
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateSelectedCount();
});
</script>
@endpush
