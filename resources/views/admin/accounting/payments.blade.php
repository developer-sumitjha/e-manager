@extends('admin.layouts.app')

@section('title', 'Payments')
@section('page-title', 'Payments')

@push('styles')
<style>
    /* Payments Page Specific Styles */
    .payments-header {
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

    .payments-summary {
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
    .summary-icon.completed { background: linear-gradient(135deg, #3B82F6, #60A5FA); }
    .summary-icon.pending { background: linear-gradient(135deg, #F59E0B, #FBBF24); }
    .summary-icon.failed { background: linear-gradient(135deg, #EF4444, #F87171); }

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

    .payment-methods-breakdown {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .method-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 0.75rem;
        padding: 1rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .method-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.1);
    }

    .method-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        text-transform: capitalize;
    }

    .method-amount {
        font-size: 1.25rem;
        font-weight: 700;
        color: #10B981;
    }

    .method-count {
        font-size: 0.75rem;
        color: var(--text-secondary);
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

    .filter-group {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .filter-select {
        padding: 0.75rem;
        border-radius: 0.75rem;
        border: 1px solid rgba(16, 185, 129, 0.2);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        min-width: 150px;
    }

    .filter-select:focus {
        border-color: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        background: white;
        outline: none;
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
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }

    .payments-table {
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

    .payment-id {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: var(--primary-color);
    }

    .order-info {
        display: flex;
        flex-direction: column;
    }

    .order-number {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .customer-name {
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
    .status-pending { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .status-failed { background: rgba(239, 68, 68, 0.1); color: #EF4444; }
    .status-refunded { background: rgba(139, 92, 246, 0.1); color: #8B5CF6; }

    .method-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
        letter-spacing: 0.025em;
    }

    .method-cash { background: rgba(16, 185, 129, 0.1); color: #10B981; }
    .method-credit_card { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
    .method-debit_card { background: rgba(139, 92, 246, 0.1); color: #8B5CF6; }
    .method-bank_transfer { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .method-digital_wallet { background: rgba(239, 68, 68, 0.1); color: #EF4444; }
    .method-other { background: rgba(107, 114, 128, 0.1); color: #6B7280; }

    .payment-date {
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

    .action-btn.view {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
        border-color: rgba(59, 130, 246, 0.2);
    }

    .action-btn.view:hover {
        background: rgba(59, 130, 246, 0.2);
        color: #3B82F6;
    }

    .action-btn.refund {
        background: rgba(139, 92, 246, 0.1);
        color: #8B5CF6;
        border-color: rgba(139, 92, 246, 0.2);
    }

    .action-btn.refund:hover {
        background: rgba(139, 92, 246, 0.2);
        color: #8B5CF6;
    }

    .reference-number {
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .payments-summary {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .payments-summary {
            grid-template-columns: 1fr;
        }
        
        .filters-bar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-box {
            min-width: auto;
        }
        
        .filter-group {
            justify-content: space-between;
        }
        
        .date-filter {
            justify-content: space-between;
        }
    }
</style>
@endpush

@section('content')
<div class="payments-header">
    <div class="page-title-section">
        <h1>Payments</h1>
        <p class="page-subtitle">Track and manage payment transactions</p>
    </div>
</div>

@php
$totalPayments = $payments->sum('amount');
$completedPayments = $payments->where('status', 'completed')->sum('amount');
$pendingPayments = $payments->where('status', 'pending')->sum('amount');
$failedPayments = $payments->where('status', 'failed')->sum('amount');
@endphp

<div class="payments-summary">
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">Total Payments</div>
            <div class="summary-icon total">
                <i class="fas fa-rupee-sign"></i>
            </div>
        </div>
        <div class="summary-value">₹{{ number_format($totalPayments, 2) }}</div>
        <div class="summary-subtitle">All payment methods</div>
    </div>
    
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">Completed</div>
            <div class="summary-icon completed">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="summary-value">₹{{ number_format($completedPayments, 2) }}</div>
        <div class="summary-subtitle">Successfully processed</div>
    </div>
    
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">Pending</div>
            <div class="summary-icon pending">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="summary-value">₹{{ number_format($pendingPayments, 2) }}</div>
        <div class="summary-subtitle">Awaiting processing</div>
    </div>
    
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">Failed</div>
            <div class="summary-icon failed">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
        <div class="summary-value">₹{{ number_format($failedPayments, 2) }}</div>
        <div class="summary-subtitle">Failed transactions</div>
    </div>
</div>

@php
$paymentMethods = $payments->groupBy('method')->map(function($group) {
    return [
        'method' => $group->first()->method,
        'amount' => $group->sum('amount'),
        'count' => $group->count()
    ];
})->sortByDesc('amount');
@endphp

@if($paymentMethods->count() > 0)
<div class="payment-methods-breakdown">
    @foreach($paymentMethods as $method)
    <div class="method-card">
        <div class="method-name">{{ str_replace('_', ' ', $method['method']) }}</div>
        <div class="method-amount">₹{{ number_format($method['amount'], 2) }}</div>
        <div class="method-count">{{ $method['count'] }} payments</div>
    </div>
    @endforeach
</div>
@endif

<div class="filters-bar">
    <div class="search-box">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="payments-search" class="form-control" placeholder="Search payments..." value="{{ request('search') }}">
    </div>
    
    <div class="filter-group">
        <select class="filter-select" id="status-filter">
            <option value="">All Status</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
        </select>
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

<div class="payments-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Order</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Reference</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>
                        <div class="payment-id">{{ $payment->payment_number }}</div>
                    </td>
                    <td>
                        <div class="order-info">
                            <div class="order-number">{{ $payment->order->order_number }}</div>
                            <div class="customer-name">{{ $payment->order->user->name ?? 'N/A' }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="amount-value">₹{{ number_format($payment->amount, 2) }}</div>
                    </td>
                    <td>
                        <span class="method-badge method-{{ $payment->method }}">
                            {{ str_replace('_', ' ', $payment->method) }}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $payment->status }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="reference-number">{{ $payment->reference ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div class="payment-date">{{ $payment->created_at->format('M d, Y') }}</div>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.accounting.payments.show', $payment->id) }}" class="action-btn view">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if($payment->status === 'completed')
                            <button type="button" class="action-btn refund" onclick="refundPayment({{ $payment->id }})">
                                <i class="fas fa-undo"></i> Refund
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-credit-card text-muted" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <h5 class="text-muted">No payments found</h5>
                        <p class="text-muted">Payment transactions will appear here.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($payments->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $payments->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentsSearch = document.getElementById('payments-search');
        const statusFilter = document.getElementById('status-filter');

        // Live Search with Debounce
        paymentsSearch.addEventListener('keyup', debounce(function() {
            applyFilters();
        }, 300));

        // Status filter change
        statusFilter.addEventListener('change', function() {
            applyFilters();
        });
    });

    function applyFilters() {
        const search = document.getElementById('payments-search').value;
        const status = document.getElementById('status-filter').value;
        const dateFrom = document.getElementById('date-from').value;
        const dateTo = document.getElementById('date-to').value;
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (status) params.append('status', status);
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);
        
        window.location.href = `{{ route('admin.accounting.payments') }}?${params.toString()}`;
    }

    function refundPayment(paymentId) {
        if (confirm('Are you sure you want to refund this payment? This action cannot be undone.')) {
            fetch(`/admin/accounting/payments/${paymentId}/refund`, {
                method: 'POST',
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
                    showNotification('Failed to refund payment.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while refunding payment.', 'error');
            });
        }
    }
</script>
@endpush







