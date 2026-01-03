@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="m-0 fw-bold"><i class="fas fa-wallet"></i> Payment Method</h2>
            <div class="text-muted">Update your billing details</div>
        </div>
        <a href="{{ route('admin.subscription.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Subscription
        </a>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.subscription.payment-method.update') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="esewa">eSewa</option>
                                <option value="khalti">Khalti</option>
                                <option value="card">Credit/Debit Card</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Billing Email</label>
                            <input type="email" class="form-control" name="billing_email" value="{{ $tenant->business_email }}">
                        </div>

                        <button class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Save Payment Method
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="p-4 rounded-3 h-100" style="background: #F8FAFC; border: 1px solid #E5E7EB;">
                <h5 class="fw-bold mb-3"><i class="fas fa-shield-alt"></i> Secure Billing</h5>
                <p class="text-muted mb-0">Your payment details are processed securely by our providers. We do not store sensitive card data on our servers.</p>
            </div>
        </div>
    </div>
</div>
@endsection







