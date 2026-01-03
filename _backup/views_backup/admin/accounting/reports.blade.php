@extends('admin.layouts.app')

@section('title', 'Financial Reports')
@section('page-title', 'Financial Reports')

@push('styles')
<style>
    /* Reports Page Specific Styles */
    .reports-header {
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

    .export-btn {
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

    .export-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .financial-overview {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .overview-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .overview-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.15);
    }

    .overview-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .overview-title {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .overview-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
    }

    .overview-icon.revenue { background: linear-gradient(135deg, #10B981, #34D399); }
    .overview-icon.expenses { background: linear-gradient(135deg, #EF4444, #F87171); }
    .overview-icon.purchases { background: linear-gradient(135deg, #F59E0B, #FBBF24); }
    .overview-icon.net { background: linear-gradient(135deg, #8B5CF6, #A855F7); }

    .overview-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .overview-subtitle {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-top: 0.25rem;
    }

    .charts-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
    }

    .chart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .chart-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .chart-container {
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(16, 185, 129, 0.02);
        border-radius: 0.75rem;
        border: 1px dashed rgba(16, 185, 129, 0.2);
    }

    .chart-placeholder {
        text-align: center;
        color: var(--text-secondary);
    }

    .chart-placeholder i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .date-range-filter {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        align-items: center;
        background: rgba(255, 255, 255, 0.9);
        padding: 1rem;
        border-radius: 1rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
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

    .generate-btn {
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

    .generate-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }

    .analytics-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .analytics-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
    }

    .analytics-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .analytics-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .analytics-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .analytics-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(16, 185, 129, 0.05);
    }

    .analytics-item:last-child {
        border-bottom: none;
    }

    .item-name {
        font-weight: 500;
        color: var(--text-primary);
    }

    .item-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    .item-value.positive {
        color: #10B981;
    }

    .item-value.negative {
        color: #EF4444;
    }

    .report-types {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .report-type-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }

    .report-type-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(16, 185, 129, 0.15);
        color: inherit;
    }

    .report-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #10B981, #34D399);
    }

    .report-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .report-description {
        font-size: 0.875rem;
        color: var(--text-secondary);
        line-height: 1.5;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .financial-overview {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .charts-section {
            grid-template-columns: 1fr;
        }
        
        .analytics-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .financial-overview {
            grid-template-columns: 1fr;
        }
        
        .date-range-filter {
            flex-direction: column;
            align-items: stretch;
        }
        
        .report-types {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="reports-header">
    <div class="page-title-section">
        <h1>Financial Reports</h1>
        <p class="page-subtitle">Comprehensive financial analytics and insights</p>
    </div>
    <a href="{{ route('admin.accounting.export-reports', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="export-btn">
        <i class="fas fa-download"></i> Export Reports
    </a>
</div>

<div class="date-range-filter">
    <div>
        <label class="form-label">From Date</label>
        <input type="date" class="date-input" id="start-date" value="{{ $startDate }}">
    </div>
    <div>
        <label class="form-label">To Date</label>
        <input type="date" class="date-input" id="end-date" value="{{ $endDate }}">
    </div>
    <div style="align-self: end;">
        <button type="button" class="generate-btn" onclick="generateReport()">
            <i class="fas fa-chart-line"></i> Generate Report
        </button>
    </div>
</div>

<div class="financial-overview">
    <div class="overview-card">
        <div class="overview-header">
            <div class="overview-title">Total Revenue</div>
            <div class="overview-icon revenue">
                <i class="fas fa-rupee-sign"></i>
            </div>
        </div>
        <div class="overview-value">₹{{ number_format($totalRevenue, 2) }}</div>
        <div class="overview-subtitle">Sales revenue</div>
    </div>
    
    <div class="overview-card">
        <div class="overview-header">
            <div class="overview-title">Total Expenses</div>
            <div class="overview-icon expenses">
                <i class="fas fa-arrow-down"></i>
            </div>
        </div>
        <div class="overview-value">₹{{ number_format($totalExpenses, 2) }}</div>
        <div class="overview-subtitle">Business expenses</div>
    </div>
    
    <div class="overview-card">
        <div class="overview-header">
            <div class="overview-title">Total Purchases</div>
            <div class="overview-icon purchases">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
        <div class="overview-value">₹{{ number_format($totalPurchases, 2) }}</div>
        <div class="overview-subtitle">Purchase costs</div>
    </div>
    
    <div class="overview-card">
        <div class="overview-header">
            <div class="overview-title">Net Income</div>
            <div class="overview-icon net">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="overview-value {{ $netIncome >= 0 ? 'positive' : 'negative' }}">₹{{ number_format($netIncome, 2) }}</div>
        <div class="overview-subtitle">Profit/Loss</div>
    </div>
</div>

<div class="charts-section">
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Daily Revenue Trend</h3>
        </div>
        <div class="chart-container">
            <div class="chart-placeholder">
                <i class="fas fa-chart-line"></i>
                <h5>Revenue Chart</h5>
                <p>Daily revenue trend for the selected period</p>
                <small>Chart visualization would be implemented here</small>
            </div>
        </div>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Top Selling Products</h3>
        </div>
        <div class="chart-container">
            <div class="analytics-list">
                @forelse($topProducts->take(5) as $product)
                <div class="analytics-item">
                    <div class="item-name">{{ $product->name }}</div>
                    <div class="item-value positive">₹{{ number_format($product->total_revenue, 2) }}</div>
                </div>
                @empty
                <div class="text-center text-muted py-3">
                    <i class="fas fa-box"></i>
                    <p>No products sold</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="analytics-grid">
    <div class="analytics-card">
        <div class="analytics-header">
            <h3 class="analytics-title">Financial Summary</h3>
        </div>
        <ul class="analytics-list">
            <li class="analytics-item">
                <span class="item-name">Gross Revenue</span>
                <span class="item-value positive">₹{{ number_format($totalRevenue, 2) }}</span>
            </li>
            <li class="analytics-item">
                <span class="item-name">Total Expenses</span>
                <span class="item-value negative">₹{{ number_format($totalExpenses, 2) }}</span>
            </li>
            <li class="analytics-item">
                <span class="item-name">Total Purchases</span>
                <span class="item-value negative">₹{{ number_format($totalPurchases, 2) }}</span>
            </li>
            <li class="analytics-item">
                <span class="item-name">Net Income</span>
                <span class="item-value {{ $netIncome >= 0 ? 'positive' : 'negative' }}">₹{{ number_format($netIncome, 2) }}</span>
            </li>
            <li class="analytics-item">
                <span class="item-name">Profit Margin</span>
                <span class="item-value {{ $totalRevenue > 0 ? ($netIncome / $totalRevenue * 100 >= 0 ? 'positive' : 'negative') : 'negative' }}">
                    {{ $totalRevenue > 0 ? number_format($netIncome / $totalRevenue * 100, 2) : '0.00' }}%
                </span>
            </li>
        </ul>
    </div>
    
    <div class="analytics-card">
        <div class="analytics-header">
            <h3 class="analytics-title">Period Analysis</h3>
        </div>
        <ul class="analytics-list">
            <li class="analytics-item">
                <span class="item-name">Report Period</span>
                <span class="item-value">{{ \Carbon\Carbon::parse($startDate)->format('M d') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</span>
            </li>
            <li class="analytics-item">
                <span class="item-name">Days Covered</span>
                <span class="item-value">{{ \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1 }} days</span>
            </li>
            <li class="analytics-item">
                <span class="item-name">Avg Daily Revenue</span>
                <span class="item-value positive">₹{{ number_format($totalRevenue / max(\Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1, 1), 2) }}</span>
            </li>
            <li class="analytics-item">
                <span class="item-name">Avg Daily Expenses</span>
                <span class="item-value negative">₹{{ number_format($totalExpenses / max(\Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1, 1), 2) }}</span>
            </li>
            <li class="analytics-item">
                <span class="item-name">Daily Net Income</span>
                <span class="item-value {{ $netIncome >= 0 ? 'positive' : 'negative' }}">₹{{ number_format($netIncome / max(\Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1, 1), 2) }}</span>
            </li>
        </ul>
    </div>
</div>

<div class="report-types">
    <a href="{{ route('admin.accounting.reports', ['type' => 'sales']) }}" class="report-type-card">
        <div class="report-icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <h4 class="report-title">Sales Report</h4>
        <p class="report-description">Detailed analysis of sales performance, revenue trends, and customer insights.</p>
    </a>
    
    <a href="{{ route('admin.accounting.reports', ['type' => 'expenses']) }}" class="report-type-card">
        <div class="report-icon">
            <i class="fas fa-receipt"></i>
        </div>
        <h4 class="report-title">Expense Report</h4>
        <p class="report-description">Comprehensive breakdown of business expenses and cost analysis.</p>
    </a>
    
    <a href="{{ route('admin.accounting.reports', ['type' => 'profit_loss']) }}" class="report-type-card">
        <div class="report-icon">
            <i class="fas fa-balance-scale"></i>
        </div>
        <h4 class="report-title">Profit & Loss</h4>
        <p class="report-description">Complete P&L statement with revenue, expenses, and profitability analysis.</p>
    </a>
    
    <a href="{{ route('admin.accounting.reports', ['type' => 'cash_flow']) }}" class="report-type-card">
        <div class="report-icon">
            <i class="fas fa-coins"></i>
        </div>
        <h4 class="report-title">Cash Flow</h4>
        <p class="report-description">Track cash inflows and outflows for better financial planning.</p>
    </a>
    
    <a href="{{ route('admin.accounting.reports', ['type' => 'tax']) }}" class="report-type-card">
        <div class="report-icon">
            <i class="fas fa-calculator"></i>
        </div>
        <h4 class="report-title">Tax Report</h4>
        <p class="report-description">Tax-related transactions and calculations for compliance.</p>
    </a>
    
    <a href="{{ route('admin.accounting.reports', ['type' => 'inventory']) }}" class="report-type-card">
        <div class="report-icon">
            <i class="fas fa-boxes"></i>
        </div>
        <h4 class="report-title">Inventory Report</h4>
        <p class="report-description">Stock levels, inventory valuation, and movement analysis.</p>
    </a>
</div>
@endsection

@push('scripts')
<script>
    function generateReport() {
        const startDate = document.getElementById('start-date').value;
        const endDate = document.getElementById('end-date').value;
        
        if (!startDate || !endDate) {
            showNotification('Please select both start and end dates.', 'error');
            return;
        }
        
        if (new Date(startDate) > new Date(endDate)) {
            showNotification('Start date cannot be after end date.', 'error');
            return;
        }
        
        window.location.href = `{{ route('admin.accounting.reports') }}?start_date=${startDate}&end_date=${endDate}`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh functionality could be added here
        console.log('Financial Reports loaded');
    });
</script>
@endpush





