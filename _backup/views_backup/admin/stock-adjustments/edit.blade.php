@extends('admin.layouts.app')

@section('title', 'Edit Stock Adjustment')
@section('page-title', 'Edit Stock Adjustment')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Edit Adjustment #{{ $stockAdjustment->adjustment_number }}
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Note:</strong> You can only edit the notes and reference number. The stock quantities cannot be changed after creation.
                </div>
                
                <form action="{{ route('admin.stock-adjustments.update', $stockAdjustment) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Read-only Information --}}
                    <div class="mb-4 p-3 bg-light rounded">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Product:</strong> {{ $stockAdjustment->product->name }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Type:</strong> 
                                <span class="badge bg-{{ $stockAdjustment->type_badge_class }}">
                                    {{ $stockAdjustment->type_display }}
                                </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Quantity:</strong> 
                                <span class="text-{{ $stockAdjustment->type === 'increase' ? 'success' : 'danger' }} fw-bold">
                                    {{ $stockAdjustment->type === 'increase' ? '+' : '-' }}{{ $stockAdjustment->quantity }}
                                </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Reason:</strong> {{ $stockAdjustment->reason_display }}
                            </div>
                            <div class="col-md-6">
                                <strong>Old Stock:</strong> {{ $stockAdjustment->old_stock }}
                            </div>
                            <div class="col-md-6">
                                <strong>New Stock:</strong> {{ $stockAdjustment->new_stock }}
                            </div>
                        </div>
                    </div>
                    
                    {{-- Editable Fields --}}
                    <div class="mb-3">
                        <label for="reference_number" class="form-label">Reference Number</label>
                        <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                               id="reference_number" name="reference_number" 
                               value="{{ old('reference_number', $stockAdjustment->reference_number) }}" 
                               placeholder="e.g., RET-001, DMG-202501">
                        @error('reference_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Optional: Link to related document or transaction</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="5" 
                                  placeholder="Add any additional details about this adjustment...">{{ old('notes', $stockAdjustment->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Adjustment
                        </button>
                        <a href="{{ route('admin.stock-adjustments.show', $stockAdjustment) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection




