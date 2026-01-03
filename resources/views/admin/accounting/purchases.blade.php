@extends('admin.layouts.app')

@section('title', 'Purchases')
@section('page-title', 'Purchases')

@push('styles')
<style>
    /* Purchases Page Specific Styles */
    .purchases-header {
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

    .purchases-summary {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .summary-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.15);
    }

    .summary-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .summary-title {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .summary-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
    }

    .summary-icon.total { background: linear-gradient(135deg, #EF4444, #F87171); }
    .summary-icon.count { background: linear-gradient(135deg, #3B82F6, #60A5FA); }
    .summary-icon.average { background: linear-gradient(135deg, #8B5CF6, #A855F7); }
    .summary-icon.pending { background: linear-gradient(135deg, #F59E0B, #FBBF24); }

    .summary-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .summary-subtitle {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-top: 0.25rem;
    }

    .filters-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 250px;
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

    .date-filter {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .date-input {
        padding: 0.75rem;
        border-radius: 0.75rem;
        border: 1px solid rgba(16, 185, 129, 0.2);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .date-input:focus {
        border-color: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        background: white;
        outline: none;
    }

    .filter-btn, .add-purchase-btn {
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #10B981, #34D399);
        color: white;
        border: none;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-btn:hover, .add-purchase-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .purchases-table {
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

    .purchase-id {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: var(--primary-color);
    }

    .amount-value {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1.125rem;
    }

    .account-name {
        font-weight: 500;
        color: var(--text-primary);
    }

    .reference-number {
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .purchase-date {
        font-size: 0.875rem;
        color: var(--text-secondary);
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
    }

    .action-btn.edit {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
        border-color: rgba(59, 130, 246, 0.2);
    }

    .action-btn.edit:hover {
        background: rgba(59, 130, 246, 0.2);
        color: #3B82F6;
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

    .description-text {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: var(--text-primary);
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .purchases-summary {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .purchases-summary {
            grid-template-columns: 1fr;
        }
        
        .filters-bar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-box {
            min-width: auto;
        }
        
        .date-filter {
            justify-content: space-between;
        }
    }
</style>
@endpush

@section('content')
<div class="purchases-header">
    <div class="page-title-section">
        <h1>Purchases</h1>
        <p class="page-subtitle">Track business purchases and expenses</p>
    </div>
    <a href="{{ route('admin.accounting.purchases.create') }}" class="add-purchase-btn">
        <i class="fas fa-plus"></i> Add Purchase
    </a>
</div>

<div class="purchases-summary">
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">Total Purchases</div>
            <div class="summary-icon total">
                <i class="fas fa-rupee-sign"></i>
            </div>
        </div>
        <div class="summary-value">₹{{ number_format($purchases->sum('amount'), 2) }}</div>
        <div class="summary-subtitle">Total amount spent</div>
    </div>
    
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">Purchase Count</div>
            <div class="summary-icon count">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
        <div class="summary-value">{{ $purchases->count() }}</div>
        <div class="summary-subtitle">Total purchases</div>
    </div>
    
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">Average Purchase</div>
            <div class="summary-icon average">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="summary-value">₹{{ $purchases->count() > 0 ? number_format($purchases->avg('amount'), 2) : '0.00' }}</div>
        <div class="summary-subtitle">Per purchase value</div>
    </div>
    
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">This Month</div>
            <div class="summary-icon pending">
                <i class="fas fa-calendar-month"></i>
            </div>
        </div>
        <div class="summary-value">₹{{ number_format($purchases->where('created_at', '>=', now()->startOfMonth())->sum('amount'), 2) }}</div>
        <div class="summary-subtitle">Monthly purchases</div>
    </div>
</div>

<div class="filters-bar">
    <div class="search-box">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="purchases-search" class="form-control" placeholder="Search purchases..." value="{{ request('search') }}">
    </div>
    <div class="date-filter">
        <input type="date" class="date-input" id="date-from" value="{{ request('date_from') }}" placeholder="From Date">
        <span class="text-muted">to</span>
        <input type="date" class="date-input" id="date-to" value="{{ request('date_to') }}" placeholder="To Date">
        <button type="button" class="filter-btn" onclick="applyFilters()">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>
</div>

<div class="purchases-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Account</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Reference</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                <tr>
                    <td>
                        <div class="purchase-id">#{{ $purchase->id }}</div>
                    </td>
                    <td>
                        <div class="account-name">{{ $purchase->account->name }}</div>
                    </td>
                    <td>
                        <div class="description-text" title="{{ $purchase->description }}">
                            {{ $purchase->description }}
                        </div>
                    </td>
                    <td>
                        <div class="amount-value">₹{{ number_format($purchase->amount, 2) }}</div>
                    </td>
                    <td>
                        <div class="reference-number">{{ $purchase->reference ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div class="purchase-date">{{ $purchase->transaction_date->format('M d, Y') }}</div>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.accounting.purchases.edit', $purchase->id) }}" class="action-btn edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button" class="action-btn delete" onclick="deletePurchase({{ $purchase->id }})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-shopping-cart text-muted" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <h5 class="text-muted">No purchases found</h5>
                        <p class="text-muted">Add your first purchase to get started.</p>
                        <a href="{{ route('admin.accounting.purchases.create') }}" class="add-purchase-btn">
                            <i class="fas fa-plus"></i> Add Purchase
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($purchases->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $purchases->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const purchasesSearch = document.getElementById('purchases-search');

        // Live Search with Debounce
        purchasesSearch.addEventListener('keyup', debounce(function() {
            applyFilters();
        }, 300));
    });

    function applyFilters() {
        const search = document.getElementById('purchases-search').value;
        const dateFrom = document.getElementById('date-from').value;
        const dateTo = document.getElementById('date-to').value;
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);
        
        window.location.href = `{{ route('admin.accounting.purchases') }}?${params.toString()}`;
    }

    function deletePurchase(purchaseId) {
        if (confirm('Are you sure you want to delete this purchase? This action cannot be undone.')) {
            fetch(`/admin/accounting/purchases/${purchaseId}`, {
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
                    showNotification('Failed to delete purchase.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while deleting purchase.', 'error');
            });
        }
    }
</script>
@endpush







