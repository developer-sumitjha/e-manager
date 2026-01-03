@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="p-4 rounded-3 mb-4" style="background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%); color: #fff;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h2 class="m-0 fw-bold">
                    <i class="fas fa-credit-card"></i> Subscription
                </h2>
                <div class="opacity-75">Manage your plan, billing and invoices</div>
            </div>
            @if($current)
            <div class="d-flex align-items-center gap-2">
                <span class="badge {{ $current->status === 'active' ? 'bg-success' : ($current->status === 'trial' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                    {{ strtoupper($current->status) }}
                </span>
                @if($current->ends_at)
                    <span class="badge bg-dark">Renews {{ $current->ends_at->format('M d, Y') }}</span>
                @endif
            </div>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                        <div>
                            <div class="text-muted">Current Plan</div>
                            <h4 class="fw-bold m-0">{{ optional($current->plan)->name ?? 'No Plan' }}</h4>
                        </div>
                        <div class="text-end">
                            <div class="text-muted">Billing</div>
                            <div class="fs-5 fw-bold">NPR {{ number_format($current->amount ?? 0, 0) }} / {{ $current->billing_cycle ?? 'monthly' }}</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background: #F8FAFC; border: 1px solid #E5E7EB;">
                                <div class="small text-muted">Period</div>
                                <div class="fw-semibold">{{ optional($current->starts_at)->format('d M Y') }} → {{ optional($current->ends_at)->format('d M Y') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3" style="background: #F8FAFC; border: 1px solid #E5E7EB;">
                                <div class="small text-muted">Status</div>
                                <div>
                                    <span class="badge {{ $current && $current->status === 'active' ? 'bg-success' : ($current && $current->status === 'trial' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                        {{ strtoupper($current->status ?? 'N/A') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <form method="POST" action="{{ route('admin.subscription.cancel') }}">@csrf
                            <button class="btn btn-outline-danger {{ $current && $current->status === 'cancelled' ? 'disabled' : '' }}">
                                <i class="fas fa-ban"></i> Cancel
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.subscription.resume') }}">@csrf
                            <button class="btn btn-outline-primary {{ !$current || $current->status !== 'cancelled' ? 'disabled' : '' }}">
                                <i class="fas fa-play"></i> Resume
                            </button>
                        </form>
                    </div>

                    <hr class="my-4">

                    <div class="mb-2 fw-semibold">Recent Payments</div>
                    @if($payments->count())
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Reference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $p)
                                        <tr>
                                            <td>{{ optional($p->paid_at)->format('d M Y H:i') }}</td>
                                            <td class="fw-semibold">NPR {{ number_format($p->amount, 0) }}</td>
                                            <td>
                                                <span class="badge {{ $p->status === 'completed' ? 'bg-success' : ($p->status === 'pending' ? 'bg-warning text-dark' : ($p->status === 'failed' ? 'bg-danger' : 'bg-secondary')) }}">{{ ucfirst($p->status) }}</span>
                                            </td>
                                            <td class="text-muted">{{ $p->transaction_id ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted" style="background: #F8FAFC; border: 1px solid #E5E7EB; border-radius: .75rem;">
                            <i class="fas fa-receipt fa-2x mb-2"></i>
                            <div>No payments found yet.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="m-0 fw-bold"><i class="fas fa-layer-group"></i> Change Plan</h5>
                        <span class="badge bg-primary">Self-serve</span>
                    </div>

                    <form method="POST" action="{{ route('admin.subscription.change-plan') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted">Select Plan</label>
                            <select class="form-select" name="plan_id" required>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" @selected($tenant->current_plan_id == $plan->id)>
                                        {{ $plan->name }} — NPR {{ number_format($plan->price_monthly, 0) }}/mo
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary w-100">
                            <i class="fas fa-sync"></i> Update Subscription
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="d-grid gap-2">
                        <a class="btn btn-outline-secondary" href="{{ route('admin.subscription.invoices') }}">
                            <i class="fas fa-file-invoice"></i> View All Invoices
                        </a>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.subscription.payment-method') }}">
                            <i class="fas fa-wallet"></i> Payment Method
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


