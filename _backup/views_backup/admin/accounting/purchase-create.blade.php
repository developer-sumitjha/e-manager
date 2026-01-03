@extends('admin.layouts.app')

@section('title', 'Create Purchase')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Create Purchase</h1>
            <p class="text-muted">Record a new purchase transaction</p>
        </div>
        <a href="{{ route('admin.accounting.purchases') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Purchases
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Purchase Details</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.accounting.purchases.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="vendor_name" class="form-label">Vendor Name *</label>
                                <input type="text" class="form-control @error('vendor_name') is-invalid @enderror" 
                                       id="vendor_name" name="vendor_name" value="{{ old('vendor_name') }}" required>
                                @error('vendor_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="purchase_date" class="form-label">Purchase Date *</label>
                                <input type="date" class="form-control @error('purchase_date') is-invalid @enderror" 
                                       id="purchase_date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                                @error('purchase_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="account_id" class="form-label">Expense Account *</label>
                                <select class="form-select @error('account_id') is-invalid @enderror" 
                                        id="account_id" name="account_id" required>
                                    <option value="">Select Account</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->name }} ({{ $account->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('account_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Amount *</label>
                                <div class="input-group">
                                    <span class="input-group-text">₨</span>
                                    <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount') }}" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="reference" class="form-label">Reference Number</label>
                                <input type="text" class="form-control @error('reference') is-invalid @enderror" 
                                       id="reference" name="reference" value="{{ old('reference') }}" 
                                       placeholder="Invoice/Bill Number">
                                @error('reference')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.accounting.purchases') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Purchase
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">Quick Tips</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-2">Select the appropriate expense account for this purchase</li>
                        <li class="mb-2">Include vendor invoice/bill number in the reference field</li>
                        <li class="mb-2">Provide detailed description for better record keeping</li>
                        <li>All purchases will be recorded as expense transactions</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection





