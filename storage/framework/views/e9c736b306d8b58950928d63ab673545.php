<?php $__env->startSection('title', 'Manual Delivery System'); ?>
<?php $__env->startSection('page-title', 'Manual Delivery System'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Manual Delivery System Specific Styles */
    .manual-delivery-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
    }

    .page-title-section h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
        background: linear-gradient(135deg, #8B5CF6, #A855F7);
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

    .summary-badges {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .summary-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 0.75rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
        backdrop-filter: blur(10px);
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-primary);
    }

    .summary-badge .icon {
        width: 1.25rem;
        height: 1.25rem;
        color: var(--primary-color);
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .metric-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(139, 92, 246, 0.15);
    }

    .metric-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.25rem;
        color: white;
    }

    .metric-icon.total-deliveries { background: linear-gradient(135deg, #8B5CF6, #A855F7); }
    .metric-icon.active-riders { background: linear-gradient(135deg, #10B981, #34D399); }
    .metric-icon.pending { background: linear-gradient(135deg, #F59E0B, #FBBF24); }
    .metric-icon.today { background: linear-gradient(135deg, #8B5CF6, #A855F7); }
    .metric-icon.success-rate { background: linear-gradient(135deg, #10B981, #34D399); }
    .metric-icon.revenue { background: linear-gradient(135deg, #3B82F6, #60A5FA); }

    .metric-title {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .metric-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .sub-nav-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        background: rgba(255, 255, 255, 0.8);
        padding: 0.5rem;
        border-radius: 1rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
        backdrop-filter: blur(10px);
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
    }

    .sub-nav-tab:hover {
        color: var(--primary-color);
        background: rgba(139, 92, 246, 0.05);
    }

    .sub-nav-tab.active {
        color: white;
        background: linear-gradient(135deg, #8B5CF6, #A855F7);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .sub-nav-tab .icon {
        width: 1rem;
        height: 1rem;
    }

    .content-sections {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .section-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
        backdrop-filter: blur(10px);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .section-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #8B5CF6, #A855F7);
        color: white;
        font-size: 1rem;
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .delivery-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 0.75rem;
        margin-bottom: 0.75rem;
        border: 1px solid rgba(139, 92, 246, 0.05);
        transition: all 0.3s ease;
    }

    .delivery-item:hover {
        background: rgba(255, 255, 255, 0.8);
        transform: translateX(4px);
    }

    .delivery-info {
        flex: 1;
    }

    .delivery-customer {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .delivery-phone {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .delivery-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .status-badge.delivered {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .status-badge.pending {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .delivery-amount {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 0.875rem;
    }

    .delivery-boy-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 0.75rem;
        margin-bottom: 0.75rem;
        border: 1px solid rgba(139, 92, 246, 0.05);
        transition: all 0.3s ease;
    }

    .delivery-boy-item:hover {
        background: rgba(255, 255, 255, 0.8);
        transform: translateX(4px);
    }

    .delivery-boy-info {
        flex: 1;
    }

    .delivery-boy-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .delivery-boy-phone {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .success-rate {
        font-size: 0.875rem;
        font-weight: 600;
        color: #10B981;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .metrics-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .metrics-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .content-sections {
            grid-template-columns: 1fr;
        }
        
        .manual-delivery-header {
            flex-direction: column;
            gap: 1rem;
        }
        
        .summary-badges {
            flex-wrap: wrap;
        }
    }

    @media (max-width: 480px) {
        .metrics-grid {
            grid-template-columns: 1fr;
        }
        
        .sub-nav-tabs {
            flex-wrap: wrap;
        }
        
        .sub-nav-tab {
            flex: 1;
            justify-content: center;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="manual-delivery-header">
    <div class="page-title-section">
        <h1>Manual Delivery System</h1>
        <p class="page-subtitle">Complete management of in-house delivery operations</p>
    </div>
    <div class="summary-badges">
        <div class="summary-badge">
            <i class="fas fa-chart-line icon"></i>
            <span><?php echo e($metrics['active_riders']); ?> Active Riders</span>
        </div>
        <div class="summary-badge">
            <i class="fas fa-eye icon"></i>
            <span><?php echo e($metrics['success_rate']); ?>% Success Rate</span>
        </div>
    </div>
</div>

<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-icon total-deliveries">
            <i class="fas fa-box"></i>
        </div>
        <div class="metric-title">Total Deliveries</div>
        <div class="metric-value"><?php echo e($metrics['total_deliveries']); ?></div>
    </div>
    
    <div class="metric-card">
        <div class="metric-icon active-riders">
            <i class="fas fa-user"></i>
        </div>
        <div class="metric-title">Active Riders</div>
        <div class="metric-value"><?php echo e($metrics['active_riders']); ?></div>
    </div>
    
    <div class="metric-card">
        <div class="metric-icon pending">
            <i class="fas fa-clock"></i>
        </div>
        <div class="metric-title">Pending</div>
        <div class="metric-value"><?php echo e($metrics['pending']); ?></div>
    </div>
    
    <div class="metric-card">
        <div class="metric-icon today">
            <i class="fas fa-calendar"></i>
        </div>
        <div class="metric-title">Today</div>
        <div class="metric-value"><?php echo e($metrics['today']); ?></div>
    </div>
    
    <div class="metric-card">
        <div class="metric-icon success-rate">
            <i class="fas fa-bullseye"></i>
        </div>
        <div class="metric-title">Success Rate</div>
        <div class="metric-value"><?php echo e($metrics['success_rate']); ?>%</div>
    </div>
    
    <div class="metric-card">
        <div class="metric-icon revenue">
            <i class="fas fa-rupee-sign"></i>
        </div>
        <div class="metric-title">Revenue</div>
        <div class="metric-value">₹<?php echo e(number_format($metrics['revenue'], 2)); ?></div>
    </div>
</div>

<div class="sub-nav-tabs">
    <a href="<?php echo e(route('admin.manual-delivery.index', ['tab' => 'overview'])); ?>" class="sub-nav-tab <?php echo e($activeTab == 'overview' ? 'active' : ''); ?>">
        <i class="fas fa-box icon"></i>
        <span>Overview</span>
    </a>
    <a href="<?php echo e(route('admin.manual-delivery.deliveries')); ?>" class="sub-nav-tab">
        <i class="fas fa-map-marker-alt icon"></i>
        <span>Manual Deliveries</span>
    </a>
    <a href="<?php echo e(route('admin.manual-delivery.activities')); ?>" class="sub-nav-tab">
        <i class="fas fa-chart-line icon"></i>
        <span>Activities & Reports</span>
    </a>
    <a href="<?php echo e(route('admin.manual-delivery.delivery-boys')); ?>" class="sub-nav-tab">
        <i class="fas fa-user icon"></i>
        <span>Delivery Boys</span>
    </a>
    <a href="<?php echo e(route('admin.manual-delivery.performance')); ?>" class="sub-nav-tab">
        <i class="fas fa-chart-line icon"></i>
        <span>Performance</span>
    </a>
    <a href="<?php echo e(route('admin.manual-delivery.cod-settlements')); ?>" class="sub-nav-tab">
        <i class="fas fa-hand-holding-usd icon"></i>
        <span>COD Settlements</span>
    </a>
</div>

<div class="content-sections">
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="fas fa-box"></i>
            </div>
            <h3 class="section-title">Recent Manual Deliveries</h3>
        </div>
        
        <?php $__empty_1 = true; $__currentLoopData = $recentDeliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="delivery-item">
            <div class="delivery-info">
                <div class="delivery-customer"><?php echo e($delivery->order->user->name ?? 'N/A'); ?></div>
                <div class="delivery-phone"><?php echo e($delivery->order->user->email ?? 'N/A'); ?></div>
            </div>
            <div class="delivery-status">
                <span class="status-badge <?php echo e($delivery->status); ?>"><?php echo e(ucfirst($delivery->status)); ?></span>
                <span class="delivery-amount">₹<?php echo e(number_format($delivery->delivery_fee, 2)); ?></span>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-4">
            <i class="fas fa-box text-muted" style="font-size: 2rem; margin-bottom: 1rem;"></i>
            <p class="text-muted">No recent deliveries found.</p>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="fas fa-user"></i>
            </div>
            <h3 class="section-title">Active Delivery Boys</h3>
        </div>
        
        <?php $__empty_1 = true; $__currentLoopData = $activeDeliveryBoys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $boy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="delivery-boy-item">
            <div class="delivery-boy-info">
                <div class="delivery-boy-name"><?php echo e($boy->name); ?></div>
                <div class="delivery-boy-phone"><?php echo e($boy->phone); ?></div>
            </div>
            <div class="success-rate"><?php echo e($boy->calculated_success_rate ?? 100); ?>% Success Rate</div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-4">
            <i class="fas fa-user text-muted" style="font-size: 2rem; margin-bottom: 1rem;"></i>
            <p class="text-muted">No active delivery boys found.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add any specific JavaScript for the manual delivery dashboard here
        console.log('Manual Delivery System Dashboard loaded');
    });
</script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/manual-delivery/index.blade.php ENDPATH**/ ?>