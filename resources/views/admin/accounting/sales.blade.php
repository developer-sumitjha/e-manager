@extends('admin.layouts.app')

@section('title', 'Sales')
@section('page-title', 'Sales')

@push('styles')
<style>
    /* Sales Page Specific Styles */
    .sales-header {
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

    .sales-summary {
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

    .summary-icon.total { background: linear-gradient(135deg, #10B981, #34D399); }
    .summary-icon.orders { background: linear-gradient(135deg, #3B82F6, #60A5FA); }
    .summary-icon.average { background: linear-gradient(135deg, #8B5CF6, #A855F7); }
    .summary-icon.today { background: linear-gradient(135deg, #F59E0B, #FBBF24); }

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

    .filter-btn {
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #10B981, #34D399);
        color: white;
        border: none;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }

    .sales-table {
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

    .order-id {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: var(--primary-color);
    }

    .customer-info {
        display: flex;
        flex-direction: column;
    }

    .customer-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .customer-email {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .amount-value {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1.125rem;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .status-completed { background: rgba(16, 185, 129, 0.1); color: #10B981; }
    .status-processing { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
    .status-shipped { background: rgba(139, 92, 246, 0.1); color: #8B5CF6; }
    .status-cancelled { background: rgba(239, 68, 68, 0.1); color: #EF4444; }

    .payment-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .payment-paid { background: rgba(16, 185, 129, 0.1); color: #10B981; }
    .payment-unpaid { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .payment-refunded { background: rgba(239, 68, 68, 0.1); color: #EF4444; }

    .order-date {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .action-dropdown {
        position: relative;
        display: inline-block;
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

    .action-btn.view {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
        border-color: rgba(59, 130, 246, 0.2);
    }

    .action-btn.view:hover {
        background: rgba(59, 130, 246, 0.2);
        color: #3B82F6;
    }

    .action-btn.invoice {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
        border-color: rgba(16, 185, 129, 0.2);
    }

    .action-btn.invoice:hover {
        background: rgba(16, 185, 129, 0.2);
        color: #10B981;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .sales-summary {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .sales-summary {
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
<div class="sales-header">
    <div class="page-title-section">
        <h1>Sales</h1>
        <p class="page-subtitle">Track revenue from completed orders</p>
    </div>
</div>

<div class="sales-summary">
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">Total Revenue</div>
            <div class="summary-icon total">
                <i class="fas fa-rupee-sign"></i>
            </div>
        </div>
        <div class="summary-value">₹{{ number_format($sales->sum('total'), 2) }}</div>
        <div class="summary-subtitle">From {{ $sales->count() }} orders</div>
    </div>
    
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">Total Orders</div>
            <div class="summary-icon orders">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
        <div class="summary-value">{{ $sales->count() }}</div>
        <div class="summary-subtitle">Completed orders</div>
    </div>
    
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">Average Order</div>
            <div class="summary-icon average">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="summary-value">₹{{ $sales->count() > 0 ? number_format($sales->avg('total'), 2) : '0.00' }}</div>
        <div class="summary-subtitle">Per order value</div>
    </div>
    
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">Today's Sales</div>
            <div class="summary-icon today">
                <i class="fas fa-calendar-day"></i>
            </div>
        </div>
        <div class="summary-value">₹{{ number_format($sales->where('created_at', '>=', today())->sum('total'), 2) }}</div>
        <div class="summary-subtitle">Today's revenue</div>
    </div>
</div>

<div class="filters-bar">
    <div class="search-box">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="sales-search" class="form-control" placeholder="Search orders..." value="{{ request('search') }}">
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

<div class="sales-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr>
                    <td>
                        <div class="order-id">{{ $sale->order_number }}</div>
                    </td>
                    <td>
                        <div class="customer-info">
                            <div class="customer-name">{{ $sale->user->name ?? 'N/A' }}</div>
                            <div class="customer-email">{{ $sale->user->email ?? 'N/A' }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="amount-value">₹{{ number_format($sale->total, 2) }}</div>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $sale->status }}">
                            {{ ucfirst($sale->status) }}
                        </span>
                    </td>
                    <td>
                        <span class="payment-badge payment-{{ $sale->payment_status }}">
                            {{ ucfirst($sale->payment_status) }}
                        </span>
                    </td>
                    <td>
                        <div class="order-date">{{ $sale->created_at->format('M d, Y') }}</div>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.orders.show', $sale->id) }}" class="action-btn view">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if(!$sale->invoice)
                            <a href="{{ route('admin.accounting.create-invoice', $sale->id) }}" class="action-btn invoice">
                                <i class="fas fa-file-invoice"></i> Invoice
                            </a>
                            @else
                            <a href="{{ route('admin.accounting.invoices.show', $sale->invoice->id) }}" class="action-btn invoice">
                                <i class="fas fa-file-invoice"></i> View Invoice
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-chart-line text-muted" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <h5 class="text-muted">No sales found</h5>
                        <p class="text-muted">Completed orders will appear here.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($sales->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $sales->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const salesSearch = document.getElementById('sales-search');

        // Live Search with Debounce
        salesSearch.addEventListener('keyup', debounce(function() {
            applyFilters();
        }, 300));
    });

    function applyFilters() {
        const search = document.getElementById('sales-search').value;
        const dateFrom = document.getElementById('date-from').value;
        const dateTo = document.getElementById('date-to').value;
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);
        
        window.location.href = `{{ route('admin.accounting.sales') }}?${params.toString()}`;
    }
</script>
@endpush







