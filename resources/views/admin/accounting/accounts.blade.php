@extends('admin.layouts.app')

@section('title', 'Accounts')
@section('page-title', 'Accounts')

@push('styles')
<style>
    /* Accounts Page Specific Styles */
    .accounts-header {
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

    .add-account-btn {
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

    .add-account-btn:hover {
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

    .accounts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    .account-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .account-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(16, 185, 129, 0.15);
    }

    .account-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .account-info h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 0.25rem 0;
    }

    .account-number {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-family: 'Courier New', monospace;
    }

    .account-type-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .type-asset { background: rgba(16, 185, 129, 0.1); color: #10B981; }
    .type-liability { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .type-equity { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
    .type-income { background: rgba(139, 92, 246, 0.1); color: #8B5CF6; }
    .type-expense { background: rgba(239, 68, 68, 0.1); color: #EF4444; }

    .account-balance {
        text-align: center;
        margin: 1rem 0;
    }

    .balance-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }

    .balance-amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .balance-positive { color: #10B981; }
    .balance-negative { color: #EF4444; }

    .account-description {
        font-size: 0.875rem;
        color: var(--text-secondary);
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .account-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid transparent;
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

    .account-status {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 0.75rem;
        height: 0.75rem;
        border-radius: 50%;
        background: #10B981;
    }

    .account-status.inactive {
        background: #EF4444;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .accounts-grid {
            grid-template-columns: 1fr;
        }
        
        .search-add-bar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-box {
            max-width: none;
        }
    }
</style>
@endpush

@section('content')
<div class="accounts-header">
    <div class="page-title-section">
        <h1>Accounts</h1>
        <p class="page-subtitle">Manage your chart of accounts</p>
    </div>
</div>

<div class="search-add-bar">
    <div class="search-box">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="account-search" class="form-control" placeholder="Search accounts..." value="{{ request('search') }}">
    </div>
    <a href="{{ route('admin.accounting.accounts.create') }}" class="add-account-btn">
        <i class="fas fa-plus"></i> Add Account
    </a>
</div>

<div class="filter-tabs">
    <a href="{{ route('admin.accounting.accounts', ['type' => '', 'search' => request('search')]) }}" class="filter-tab {{ request('type') == '' ? 'active' : '' }}">
        <span>All ({{ \App\Models\Account::count() }})</span>
    </a>
    <a href="{{ route('admin.accounting.accounts', ['type' => 'asset', 'search' => request('search')]) }}" class="filter-tab {{ request('type') == 'asset' ? 'active' : '' }}">
        <span>Assets ({{ \App\Models\Account::where('type', 'asset')->count() }})</span>
    </a>
    <a href="{{ route('admin.accounting.accounts', ['type' => 'liability', 'search' => request('search')]) }}" class="filter-tab {{ request('type') == 'liability' ? 'active' : '' }}">
        <span>Liabilities ({{ \App\Models\Account::where('type', 'liability')->count() }})</span>
    </a>
    <a href="{{ route('admin.accounting.accounts', ['type' => 'equity', 'search' => request('search')]) }}" class="filter-tab {{ request('type') == 'equity' ? 'active' : '' }}">
        <span>Equity ({{ \App\Models\Account::where('type', 'equity')->count() }})</span>
    </a>
    <a href="{{ route('admin.accounting.accounts', ['type' => 'income', 'search' => request('search')]) }}" class="filter-tab {{ request('type') == 'income' ? 'active' : '' }}">
        <span>Income ({{ \App\Models\Account::where('type', 'income')->count() }})</span>
    </a>
    <a href="{{ route('admin.accounting.accounts', ['type' => 'expense', 'search' => request('search')]) }}" class="filter-tab {{ request('type') == 'expense' ? 'active' : '' }}">
        <span>Expenses ({{ \App\Models\Account::where('type', 'expense')->count() }})</span>
    </a>
</div>

<div class="accounts-grid">
    @forelse($accounts as $account)
    <div class="account-card">
        <div class="account-status {{ $account->is_active ? '' : 'inactive' }}"></div>
        
        <div class="account-header">
            <div class="account-info">
                <h3>{{ $account->name }}</h3>
                <div class="account-number">{{ $account->account_number }}</div>
            </div>
            <span class="account-type-badge type-{{ $account->type }}">{{ ucfirst($account->type) }}</span>
        </div>

        <div class="account-balance">
            <div class="balance-label">Current Balance</div>
            <div class="balance-amount {{ $account->current_balance >= 0 ? 'balance-positive' : 'balance-negative' }}">
                {{ $account->formatted_balance }}
            </div>
        </div>

        @if($account->description)
        <div class="account-description">
            {{ $account->description }}
        </div>
        @endif

        <div class="account-actions">
            <a href="{{ route('admin.accounting.accounts.edit', $account->id) }}" class="action-btn edit">
                <i class="fas fa-edit"></i> Edit
            </a>
            <button type="button" class="action-btn delete" onclick="deleteAccount({{ $account->id }})">
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="fas fa-wallet text-muted" style="font-size: 3rem; margin-bottom: 1rem;"></i>
            <h4 class="text-muted">No accounts found</h4>
            <p class="text-muted">Start by creating your first account.</p>
            <a href="{{ route('admin.accounting.accounts.create') }}" class="add-account-btn">
                <i class="fas fa-plus"></i> Add Account
            </a>
        </div>
    </div>
    @endforelse
</div>

@if($accounts->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $accounts->links() }}
</div>
@endif
@endsection

@push('scripts')
<script>
    // Utility functions for accounting pages
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const accountSearch = document.getElementById('account-search');

        // Live Search with Debounce
        if (accountSearch) {
            accountSearch.addEventListener('keyup', debounce(function() {
                const searchValue = this.value;
                const currentType = new URLSearchParams(window.location.search).get('type') || '';
                window.location.href = `{{ route('admin.accounting.accounts') }}?search=${searchValue}&type=${currentType}`;
            }, 300));
        }
    });

    function deleteAccount(accountId) {
        if (confirm('Are you sure you want to delete this account? This action cannot be undone.')) {
            fetch(`{{ url('admin/accounting/accounts') }}/${accountId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification(data.message || 'Account deleted successfully', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Failed to delete account');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification(error.message || 'An error occurred while deleting account', 'error');
            });
        }
    }
</script>
@endpush







