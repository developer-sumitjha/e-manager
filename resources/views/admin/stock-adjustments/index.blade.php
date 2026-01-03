@extends('admin.layouts.app')

@section('title', 'Stock Adjustments')
@section('page-title', 'Stock Adjustments')

@section('content')
{{-- Statistics Cards --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded">
                            <i class="fas fa-list fa-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Adjustments</h6>
                        <h3 class="mb-0">{{ $stats['total_adjustments'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 text-success p-3 rounded">
                            <i class="fas fa-arrow-up fa-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Increases</h6>
                        <h3 class="mb-0 text-success">+{{ number_format($stats['total_increases']) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-danger bg-opacity-10 text-danger p-3 rounded">
                            <i class="fas fa-arrow-down fa-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Decreases</h6>
                        <h3 class="mb-0 text-danger">-{{ number_format($stats['total_decreases']) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 text-info p-3 rounded">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">This Month</h6>
                        <h3 class="mb-0">{{ $stats['this_month'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filters and Actions --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">
                <i class="fas fa-filter me-2"></i>Filters
            </h5>
            <a href="{{ route('admin.stock-adjustments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Adjustment
            </a>
        </div>
        
        <form method="GET" action="{{ route('admin.stock-adjustments.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="increase" {{ request('type') == 'increase' ? 'selected' : '' }}>Increase</option>
                    <option value="decrease" {{ request('type') == 'decrease' ? 'selected' : '' }}>Decrease</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Reason</label>
                <select name="reason" class="form-select">
                    <option value="">All Reasons</option>
                    <option value="damaged" {{ request('reason') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                    <option value="lost" {{ request('reason') == 'lost' ? 'selected' : '' }}>Lost/Missing</option>
                    <option value="found" {{ request('reason') == 'found' ? 'selected' : '' }}>Found</option>
                    <option value="expired" {{ request('reason') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="returned" {{ request('reason') == 'returned' ? 'selected' : '' }}>Returned</option>
                    <option value="theft" {{ request('reason') == 'theft' ? 'selected' : '' }}>Theft</option>
                    <option value="sample" {{ request('reason') == 'sample' ? 'selected' : '' }}>Sample</option>
                    <option value="manufacturing_defect" {{ request('reason') == 'manufacturing_defect' ? 'selected' : '' }}>Manufacturing Defect</option>
                    <option value="stock_count_correction" {{ request('reason') == 'stock_count_correction' ? 'selected' : '' }}>Stock Count Correction</option>
                    <option value="other" {{ request('reason') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            
            <div class="col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.stock-adjustments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Adjustments Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Stock Adjustment History</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Adjustment #</th>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Old Stock</th>
                        <th>New Stock</th>
                        <th>Reason</th>
                        <th>Adjusted By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adjustments as $adjustment)
                    <tr>
                        <td>
                            <a href="{{ route('admin.stock-adjustments.show', $adjustment) }}" class="text-decoration-none fw-bold">
                                {{ $adjustment->adjustment_number }}
                            </a>
                        </td>
                        <td>{{ $adjustment->adjustment_date->format('M d, Y') }}</td>
                        <td>
                            <div class="fw-bold">{{ $adjustment->product->name }}</div>
                            <small class="text-muted">SKU: {{ $adjustment->product->sku }}</small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $adjustment->type_badge_class }}">
                                <i class="fas fa-{{ $adjustment->type_icon }} me-1"></i>
                                {{ $adjustment->type_display }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-{{ $adjustment->type === 'increase' ? 'success' : 'danger' }}">
                                {{ $adjustment->type === 'increase' ? '+' : '-' }}{{ $adjustment->quantity }}
                            </span>
                        </td>
                        <td>{{ $adjustment->old_stock }}</td>
                        <td>
                            <span class="fw-bold">{{ $adjustment->new_stock }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $adjustment->reason_display }}</span>
                        </td>
                        <td>{{ $adjustment->adjustedBy->name }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.stock-adjustments.show', $adjustment) }}" class="btn btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.stock-adjustments.edit', $adjustment) }}" class="btn btn-outline-secondary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            No stock adjustments found. 
                            <a href="{{ route('admin.stock-adjustments.create') }}">Create your first adjustment</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($adjustments->hasPages())
    <div class="card-footer bg-white">
        {{ $adjustments->links() }}
    </div>
    @endif
</div>
@endsection






