<?php $__env->startSection('title', 'Financial Command Center'); ?>
<?php $__env->startSection('page-title', 'Financial Command Center'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Accounting Dashboard Specific Styles */
    .financial-command-center {
        background: linear-gradient(135deg, #10B981, #34D399);
        border-radius: 1.5rem;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .financial-command-center::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(50%, -50%);
    }

    .command-center-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 1rem;
    }

    .command-center-icon {
        width: 3rem;
        height: 3rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .command-center-content h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        margin-bottom: 0.5rem;
    }

    .command-center-content p {
        font-size: 1rem;
        opacity: 0.9;
        margin: 0;
    }

    .command-center-actions {
        display: flex;
        gap: 1rem;
        margin-left: auto;
    }

    .export-btn, .quick-entry-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .export-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .export-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }

    .quick-entry-btn {
        background: rgba(255, 255, 255, 0.9);
        color: #10B981;
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .quick-entry-btn:hover {
        background: white;
        color: #10B981;
    }

    .sub-nav-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        background: rgba(255, 255, 255, 0.8);
        padding: 0.5rem;
        border-radius: 1rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        overflow-x: auto;
    }

    .sub-nav-tab {
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

    .sub-nav-tab:hover {
        color: #10B981;
        background: rgba(16, 185, 129, 0.05);
    }

    .sub-nav-tab.active {
        color: white;
        background: linear-gradient(135deg, #10B981, #34D399);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .financial-metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .metric-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.15);
    }

    .metric-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .metric-title {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .metric-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
    }

    .metric-icon.revenue { background: linear-gradient(135deg, #10B981, #34D399); }
    .metric-icon.expenses { background: linear-gradient(135deg, #EF4444, #F87171); }
    .metric-icon.income { background: linear-gradient(135deg, #3B82F6, #60A5FA); }
    .metric-icon.balance { background: linear-gradient(135deg, #8B5CF6, #A855F7); }

    .metric-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        margin-bottom: 0.5rem;
    }

    .metric-subtitle {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .metric-trend {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }

    .trend-up { background: rgba(16, 185, 129, 0.1); color: #10B981; }
    .trend-down { background: rgba(239, 68, 68, 0.1); color: #EF4444; }

    .sync-status-section {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        margin-bottom: 2rem;
    }

    .sync-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .sync-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #3B82F6, #60A5FA);
        color: white;
        font-size: 1rem;
    }

    .sync-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .sync-content {
        background: rgba(255, 255, 255, 0.5);
        border-radius: 0.75rem;
        padding: 1.25rem;
        border: 1px solid rgba(16, 185, 129, 0.05);
    }

    .sync-description {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
        line-height: 1.5;
    }

    .sync-checkbox {
        width: 1rem;
        height: 1rem;
        background: linear-gradient(135deg, #10B981, #34D399);
        border-radius: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.5rem;
        flex-shrink: 0;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .financial-metrics-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .financial-metrics-grid {
            grid-template-columns: 1fr;
        }
        
        .command-center-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .command-center-actions {
            margin-left: 0;
            width: 100%;
        }
        
        .sub-nav-tabs {
            flex-wrap: wrap;
        }
    }

    @media (max-width: 480px) {
        .financial-command-center {
            padding: 1.5rem;
        }
        
        .command-center-content h1 {
            font-size: 1.5rem;
        }
        
        .export-btn, .quick-entry-btn {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="financial-command-center">
    <div class="command-center-header">
        <div class="command-center-icon">
            <i class="fas fa-calculator"></i>
        </div>
        <div class="command-center-content">
            <h1>Financial Command Center</h1>
            <p>Complete accounting & financial management</p>
        </div>
        <div class="command-center-actions">
            <a href="<?php echo e(route('admin.accounting.export-reports')); ?>" class="export-btn">
                <i class="fas fa-download"></i>
                <span>Export Reports</span>
            </a>
            <button type="button" class="quick-entry-btn" data-bs-toggle="modal" data-bs-target="#quickEntryModal">
                <i class="fas fa-plus"></i>
                <span>Quick Entry</span>
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
    </div>
</div>

<div class="sub-nav-tabs">
    <a href="<?php echo e(route('admin.accounting.index', ['tab' => 'dashboard'])); ?>" class="sub-nav-tab <?php echo e($activeTab == 'dashboard' ? 'active' : ''); ?>">
        <i class="fas fa-tachometer-alt icon"></i>
        <span>Dashboard</span>
    </a>
    <a href="<?php echo e(route('admin.accounting.accounts')); ?>" class="sub-nav-tab">
        <i class="fas fa-wallet icon"></i>
        <span>Accounts</span>
    </a>
    <a href="<?php echo e(route('admin.accounting.sales')); ?>" class="sub-nav-tab">
        <i class="fas fa-chart-line icon"></i>
        <span>Sales</span>
    </a>
    <a href="<?php echo e(route('admin.accounting.purchases')); ?>" class="sub-nav-tab">
        <i class="fas fa-shopping-cart icon"></i>
        <span>Purchases</span>
    </a>
    <a href="<?php echo e(route('admin.accounting.expenses')); ?>" class="sub-nav-tab">
        <i class="fas fa-receipt icon"></i>
        <span>Expenses</span>
    </a>
    <a href="<?php echo e(route('admin.accounting.ledger')); ?>" class="sub-nav-tab">
        <i class="fas fa-book icon"></i>
        <span>Ledger</span>
    </a>
    <a href="<?php echo e(route('admin.accounting.payments')); ?>" class="sub-nav-tab">
        <i class="fas fa-credit-card icon"></i>
        <span>Payments</span>
    </a>
    <a href="<?php echo e(route('admin.accounting.reports')); ?>" class="sub-nav-tab">
        <i class="fas fa-chart-bar icon"></i>
        <span>Reports</span>
    </a>
</div>

<div class="financial-metrics-grid">
    <div class="metric-card">
        <div class="metric-header">
            <div class="metric-title">Total Revenue</div>
            <div class="metric-icon revenue">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="metric-value">Rs. <?php echo e(number_format($metrics['total_revenue'], 2)); ?></div>
        <div class="metric-subtitle">This month</div>
        <div class="metric-trend trend-up">
            <i class="fas fa-arrow-up"></i>
        </div>
    </div>
    
    <div class="metric-card">
        <div class="metric-header">
            <div class="metric-title">Total Expenses</div>
            <div class="metric-icon expenses">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="metric-value">Rs. <?php echo e(number_format($metrics['total_expenses'], 2)); ?></div>
        <div class="metric-subtitle">This month</div>
        <div class="metric-trend trend-down">
            <i class="fas fa-arrow-down"></i>
        </div>
    </div>
    
    <div class="metric-card">
        <div class="metric-header">
            <div class="metric-title">Net Income</div>
            <div class="metric-icon income">
                <i class="fas fa-chart-bar"></i>
            </div>
        </div>
        <div class="metric-value">Rs. <?php echo e(number_format($metrics['net_income'], 2)); ?></div>
        <div class="metric-subtitle">Profit margin</div>
        <div class="metric-trend <?php echo e($metrics['net_income'] >= 0 ? 'trend-up' : 'trend-down'); ?>">
            <i class="fas fa-<?php echo e($metrics['net_income'] >= 0 ? 'arrow-up' : 'arrow-down'); ?>"></i>
        </div>
    </div>
    
    <div class="metric-card">
        <div class="metric-header">
            <div class="metric-title">Cash Balance</div>
            <div class="metric-icon balance">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
        <div class="metric-value">Rs. <?php echo e(number_format($metrics['cash_balance'], 2)); ?></div>
        <div class="metric-subtitle">Available funds</div>
        <div class="metric-trend trend-up">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>
</div>

<div class="sync-status-section">
    <div class="sync-header">
        <div class="sync-icon">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <h3 class="sync-title">Order-Sales Sync Status</h3>
    </div>
    
    <div class="sync-content">
        <div class="sync-description">
            <div class="sync-checkbox">
                <i class="fas fa-check"></i>
            </div>
            <div>
                <strong>Automatic Sync Active:</strong> All orders marked as 'delivered' are automatically synced to the accounting system as paid invoices.
            </div>
        </div>
    </div>
</div>

<!-- Quick Entry Modal -->
<div class="modal fade" id="quickEntryModal" tabindex="-1" aria-labelledby="quickEntryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickEntryModalLabel">Quick Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('admin.accounting.quick-entry')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="type" class="form-label">Transaction Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                            <option value="purchase">Purchase</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="account_id" class="form-label">Account</label>
                        <select class="form-select" id="account_id" name="account_id" required>
                            <option value="">Select Account</option>
                            <?php $__currentLoopData = \App\Models\Account::where('is_active', true)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($account->id); ?>"><?php echo e($account->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="reference" class="form-label">Reference</label>
                        <input type="text" class="form-control" id="reference" name="reference">
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?php echo e(date('Y-m-d')); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Transaction</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Utility functions for accounting pages
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
        console.log('Financial Command Center loaded');
        
        // Add form validation for quick entry modal
        const quickEntryForm = document.querySelector('#quickEntryModal form');
        if (quickEntryForm) {
            quickEntryForm.addEventListener('submit', function(e) {
                const amount = document.getElementById('amount').value;
                const description = document.getElementById('description').value;
                
                if (!amount || parseFloat(amount) <= 0) {
                    e.preventDefault();
                    showNotification('Please enter a valid amount', 'error');
                    return false;
                }
                
                if (!description.trim()) {
                    e.preventDefault();
                    showNotification('Please enter a description', 'error');
                    return false;
                }
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>








<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/accounting/index.blade.php ENDPATH**/ ?>