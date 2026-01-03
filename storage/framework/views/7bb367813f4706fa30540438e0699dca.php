<?php $__env->startSection('title', 'General Ledger'); ?>
<?php $__env->startSection('page-title', 'General Ledger'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Ledger Page Specific Styles */
    .ledger-header {
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

    .ledger-summary {
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
    .summary-icon.income { background: linear-gradient(135deg, #3B82F6, #60A5FA); }
    .summary-icon.expenses { background: linear-gradient(135deg, #EF4444, #F87171); }
    .summary-icon.balance { background: linear-gradient(135deg, #8B5CF6, #A855F7); }

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

    .ledger-table {
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

    .transaction-id {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: var(--primary-color);
    }

    .account-name {
        font-weight: 500;
        color: var(--text-primary);
    }

    .transaction-description {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: var(--text-primary);
    }

    .amount-value {
        font-weight: 600;
        font-size: 1.125rem;
    }

    .amount-debit {
        color: #EF4444;
    }

    .amount-credit {
        color: #10B981;
    }

    .type-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .type-income { background: rgba(16, 185, 129, 0.1); color: #10B981; }
    .type-expense { background: rgba(239, 68, 68, 0.1); color: #EF4444; }
    .type-purchase { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
    .type-sale { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
    .type-transfer { background: rgba(139, 92, 246, 0.1); color: #8B5CF6; }

    .reference-number {
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .transaction-date {
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

    .running-balance {
        font-weight: 600;
        font-size: 1rem;
        padding: 0.5rem;
        background: rgba(16, 185, 129, 0.05);
        border-radius: 0.5rem;
    }

    .balance-positive {
        color: #10B981;
    }

    .balance-negative {
        color: #EF4444;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .ledger-summary {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .ledger-summary {
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="ledger-header">
    <div class="page-title-section">
        <h1>General Ledger</h1>
        <p class="page-subtitle">Complete transaction history and account balances</p>
    </div>
</div>

<?php
$totalTransactions = $transactions->count();
$totalIncome = $transactions->whereIn('type', ['income', 'sale'])->sum('amount');
$totalExpenses = $transactions->whereIn('type', ['expense', 'purchase'])->sum('amount');
$netBalance = $totalIncome - $totalExpenses;
?>

<div class="ledger-summary">
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">Total Transactions</div>
            <div class="summary-icon total">
                <i class="fas fa-list"></i>
            </div>
        </div>
        <div class="summary-value"><?php echo e($totalTransactions); ?></div>
        <div class="summary-subtitle">All transactions</div>
    </div>
    
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">Total Income</div>
            <div class="summary-icon income">
                <i class="fas fa-arrow-up"></i>
            </div>
        </div>
        <div class="summary-value">₹<?php echo e(number_format($totalIncome, 2)); ?></div>
        <div class="summary-subtitle">Revenue & income</div>
    </div>
    
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">Total Expenses</div>
            <div class="summary-icon expenses">
                <i class="fas fa-arrow-down"></i>
            </div>
        </div>
        <div class="summary-value">₹<?php echo e(number_format($totalExpenses, 2)); ?></div>
        <div class="summary-subtitle">Expenses & purchases</div>
    </div>
    
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-title">Net Balance</div>
            <div class="summary-icon balance">
                <i class="fas fa-balance-scale"></i>
            </div>
        </div>
        <div class="summary-value <?php echo e($netBalance >= 0 ? 'balance-positive' : 'balance-negative'); ?>">
            ₹<?php echo e(number_format($netBalance, 2)); ?>

        </div>
        <div class="summary-subtitle">Income - Expenses</div>
    </div>
</div>

<div class="filters-bar">
    <div class="search-box">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="ledger-search" class="form-control" placeholder="Search transactions..." value="<?php echo e(request('search')); ?>">
    </div>
    
    <div class="filter-group">
        <select class="filter-select" id="account-filter">
            <option value="">All Accounts</option>
            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($account->id); ?>" <?php echo e(request('account_id') == $account->id ? 'selected' : ''); ?>>
                    <?php echo e($account->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    
    <div class="date-filter">
        <input type="date" class="date-input" id="date-from" value="<?php echo e(request('date_from')); ?>" placeholder="From Date">
        <span class="text-muted">to</span>
        <input type="date" class="date-input" id="date-to" value="<?php echo e(request('date_to')); ?>" placeholder="To Date">
        <button type="button" class="filter-btn" onclick="applyFilters()">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>
</div>

<div class="ledger-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Account</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Reference</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $runningBalance = 0;
                ?>
                <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                $isIncome = in_array($transaction->type, ['income', 'sale']);
                $runningBalance += $isIncome ? $transaction->amount : -$transaction->amount;
                ?>
                <tr>
                    <td>
                        <div class="transaction-id">#<?php echo e($transaction->id); ?></div>
                    </td>
                    <td>
                        <div class="transaction-date"><?php echo e($transaction->transaction_date->format('M d, Y')); ?></div>
                    </td>
                    <td>
                        <div class="account-name"><?php echo e($transaction->account->name); ?></div>
                    </td>
                    <td>
                        <div class="transaction-description" title="<?php echo e($transaction->description); ?>">
                            <?php echo e($transaction->description); ?>

                        </div>
                    </td>
                    <td>
                        <span class="type-badge type-<?php echo e($transaction->type); ?>">
                            <?php echo e(ucfirst($transaction->type)); ?>

                        </span>
                    </td>
                    <td>
                        <?php if(!$isIncome): ?>
                        <div class="amount-value amount-debit">₹<?php echo e(number_format($transaction->amount, 2)); ?></div>
                        <?php else: ?>
                        <div class="amount-value">-</div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($isIncome): ?>
                        <div class="amount-value amount-credit">₹<?php echo e(number_format($transaction->amount, 2)); ?></div>
                        <?php else: ?>
                        <div class="amount-value">-</div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="reference-number"><?php echo e($transaction->reference ?? 'N/A'); ?></div>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('admin.accounting.transactions.edit', $transaction->id)); ?>" class="action-btn edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button" class="action-btn delete" onclick="deleteTransaction(<?php echo e($transaction->id); ?>)">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <i class="fas fa-book text-muted" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <h5 class="text-muted">No transactions found</h5>
                        <p class="text-muted">Transactions will appear here as they are recorded.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr style="background: rgba(16, 185, 129, 0.05); font-weight: 600;">
                    <td colspan="5" class="text-end">Running Balance:</td>
                    <td colspan="4">
                        <div class="running-balance <?php echo e($runningBalance >= 0 ? 'balance-positive' : 'balance-negative'); ?>">
                            ₹<?php echo e(number_format($runningBalance, 2)); ?>

                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <?php if($transactions->hasPages()): ?>
    <div class="d-flex justify-content-center mt-4">
        <?php echo e($transactions->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ledgerSearch = document.getElementById('ledger-search');
        const accountFilter = document.getElementById('account-filter');

        // Live Search with Debounce
        ledgerSearch.addEventListener('keyup', debounce(function() {
            applyFilters();
        }, 300));

        // Account filter change
        accountFilter.addEventListener('change', function() {
            applyFilters();
        });
    });

    function applyFilters() {
        const search = document.getElementById('ledger-search').value;
        const accountId = document.getElementById('account-filter').value;
        const dateFrom = document.getElementById('date-from').value;
        const dateTo = document.getElementById('date-to').value;
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (accountId) params.append('account_id', accountId);
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);
        
        window.location.href = `<?php echo e(route('admin.accounting.ledger')); ?>?${params.toString()}`;
    }

    function deleteTransaction(transactionId) {
        if (confirm('Are you sure you want to delete this transaction? This action cannot be undone.')) {
            fetch(`/admin/accounting/transactions/${transactionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
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
                    showNotification('Failed to delete transaction.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while deleting transaction.', 'error');
            });
        }
    }
</script>
<?php $__env->stopPush(); ?>








<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/accounting/ledger.blade.php ENDPATH**/ ?>