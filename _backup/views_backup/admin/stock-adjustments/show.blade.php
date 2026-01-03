@extends('admin.layouts.app')

@section('title', 'Stock Adjustment Details')
@section('page-title', 'Stock Adjustment Details')

@section('content')
<div class="row">
    <div class="col-lg-8">
        {{-- Adjustment Details --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-file-invoice me-2"></i>
                    Adjustment #{{ $stockAdjustment->adjustment_number }}
                </h5>
                <span class="badge bg-{{ $stockAdjustment->type_badge_class }} fs-6">
                    <i class="fas fa-{{ $stockAdjustment->type_icon }} me-1"></i>
                    {{ $stockAdjustment->type_display }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small">Adjustment Date</label>
                            <div class="fw-bold">{{ $stockAdjustment->adjustment_date->format('F d, Y') }}</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="text-muted small">Created On</label>
                            <div class="fw-bold">{{ $stockAdjustment->created_at->format('M d, Y g:i A') }}</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small">Adjusted By</label>
                            <div class="fw-bold">
                                <i class="fas fa-user me-2"></i>{{ $stockAdjustment->adjustedBy->name }}
                            </div>
                        </div>
                        
                        @if($stockAdjustment->reference_number)
                        <div class="mb-3">
                            <label class="text-muted small">Reference Number</label>
                            <div class="fw-bold">{{ $stockAdjustment->reference_number }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="text-muted small">Product</label>
                        <div class="fw-bold fs-5">{{ $stockAdjustment->product->name }}</div>
                        <div class="text-muted">SKU: {{ $stockAdjustment->product->sku }}</div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="text-muted small">Old Stock</div>
                            <div class="fs-3 fw-bold">{{ $stockAdjustment->old_stock }}</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-{{ $stockAdjustment->type === 'increase' ? 'success' : 'danger' }} bg-opacity-10 rounded">
                            <div class="text-muted small">Adjustment</div>
                            <div class="fs-3 fw-bold text-{{ $stockAdjustment->type === 'increase' ? 'success' : 'danger' }}">
                                {{ $stockAdjustment->type === 'increase' ? '+' : '-' }}{{ $stockAdjustment->quantity }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                            <div class="text-muted small">New Stock</div>
                            <div class="fs-3 fw-bold text-primary">{{ $stockAdjustment->new_stock }}</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                            <div class="text-muted small">Current Stock</div>
                            <div class="fs-3 fw-bold text-info">{{ $stockAdjustment->product->stock }}</div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="text-muted small">Reason</label>
                        <div>
                            <span class="badge bg-secondary fs-6">{{ $stockAdjustment->reason_display }}</span>
                        </div>
                    </div>
                </div>
                
                @if($stockAdjustment->notes)
                <div class="row">
                    <div class="col-md-12">
                        <label class="text-muted small">Notes</label>
                        <div class="p-3 bg-light rounded">{{ $stockAdjustment->notes }}</div>
                    </div>
                </div>
                @endif
            </div>
            <div class="card-footer bg-white">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.stock-adjustments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                    <a href="{{ route('admin.stock-adjustments.edit', $stockAdjustment) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Notes
                    </a>
                    <a href="{{ route('admin.products.edit', $stockAdjustment->product) }}" class="btn btn-outline-primary">
                        <i class="fas fa-box me-2"></i>View Product
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Timeline / Activity --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Activity Timeline</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="fw-bold">Created</div>
                            <div class="text-muted small">{{ $stockAdjustment->created_at->format('M d, Y g:i A') }}</div>
                            <div class="text-muted small">By {{ $stockAdjustment->adjustedBy->name }}</div>
                        </div>
                    </div>
                    
                    @if($stockAdjustment->updated_at != $stockAdjustment->created_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="fw-bold">Last Updated</div>
                            <div class="text-muted small">{{ $stockAdjustment->updated_at->format('M d, Y g:i A') }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- Quick Stats --}}
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Product Stock History</h5>
            </div>
            <div class="card-body">
                @php
                    $productAdjustments = \App\Models\StockAdjustment::where('product_id', $stockAdjustment->product_id)
                        ->orderBy('adjustment_date', 'desc')
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($productAdjustments->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($productAdjustments as $adj)
                    <div class="list-group-item px-0 {{ $adj->id === $stockAdjustment->id ? 'bg-light' : '' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small text-muted">{{ $adj->adjustment_date->format('M d, Y') }}</div>
                                <div class="fw-bold text-{{ $adj->type === 'increase' ? 'success' : 'danger' }}">
                                    {{ $adj->type === 'increase' ? '+' : '-' }}{{ $adj->quantity }}
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="small text-muted">{{ $adj->reason_display }}</div>
                                <div class="fw-bold">Stock: {{ $adj->new_stock }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center text-muted py-3">
                    <i class="fas fa-history fa-2x mb-2 d-block"></i>
                    <p class="mb-0">No previous adjustments</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 40px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: -25px;
    top: 30px;
    bottom: -10px;
    width: 2px;
    background: #e9ecef;
}

.timeline-item:last-child:before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: -36px;
    top: 0;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 10px;
}

.timeline-content {
    padding-top: 2px;
}
</style>
@endsection




