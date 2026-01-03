@extends('admin.layouts.app')

@section('title', 'Create Expense')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Record Expense</h1>
            <p class="text-muted">Add a new expense transaction</p>
        </div>
        <a href="{{ route('admin.accounting.expenses') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Expenses
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Expense Details</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.accounting.store-expense') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="account_id" class="form-label">Expense Account *</label>
                                <select class="form-select @error('account_id') is-invalid @enderror" 
                                        id="account_id" name="account_id" required>
                                    <option value="">Select Account</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->name }} @if($account->code)({{ $account->code }})@endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('account_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="transaction_date" class="form-label">Date *</label>
                                <input type="date" class="form-control @error('transaction_date') is-invalid @enderror" 
                                       id="transaction_date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                                @error('transaction_date')
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
                                       placeholder="Receipt/Voucher Number">
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
                            <a href="{{ route('admin.accounting.expenses') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Expense
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">Expense Categories</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Common Expense Types:</strong></p>
                    <ul class="mb-0">
                        <li>Office Supplies</li>
                        <li>Utilities (Electricity, Water, Internet)</li>
                        <li>Rent & Lease</li>
                        <li>Salaries & Wages</li>
                        <li>Marketing & Advertising</li>
                        <li>Transportation</li>
                        <li>Maintenance & Repairs</li>
                        <li>Professional Fees</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection





