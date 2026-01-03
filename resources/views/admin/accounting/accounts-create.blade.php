@extends('admin.layouts.app')

@section('title', 'Create Account')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Create Account</h1>
            <p class="text-muted">Add a new account to your chart of accounts</p>
        </div>
        <a href="{{ route('admin.accounting.accounts') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Accounts
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Account Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.accounting.accounts.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="name" class="form-label">Account Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="code" class="form-label">Account Code</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code') }}" placeholder="e.g., 1001">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Account Type *</label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="asset" {{ old('type') == 'asset' ? 'selected' : '' }}>Asset</option>
                                    <option value="liability" {{ old('type') == 'liability' ? 'selected' : '' }}>Liability</option>
                                    <option value="equity" {{ old('type') == 'equity' ? 'selected' : '' }}>Equity</option>
                                    <option value="revenue" {{ old('type') == 'revenue' ? 'selected' : '' }}>Revenue</option>
                                    <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="opening_balance" class="form-label">Opening Balance</label>
                                <div class="input-group">
                                    <span class="input-group-text">₨</span>
                                    <input type="number" step="0.01" class="form-control @error('opening_balance') is-invalid @enderror" 
                                           id="opening_balance" name="opening_balance" value="{{ old('opening_balance', 0) }}">
                                    @error('opening_balance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.accounting.accounts') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">Account Types</h6>
                </div>
                <div class="card-body">
                    <p><strong>Asset:</strong> Resources owned (Cash, Bank, Inventory)</p>
                    <p><strong>Liability:</strong> Amounts owed (Loans, Payables)</p>
                    <p><strong>Equity:</strong> Owner's investment</p>
                    <p><strong>Revenue:</strong> Income from sales</p>
                    <p class="mb-0"><strong>Expense:</strong> Costs incurred</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection







