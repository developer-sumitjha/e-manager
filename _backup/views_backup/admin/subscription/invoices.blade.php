@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="m-0 fw-bold"><i class="fas fa-file-invoice"></i> Invoices</h2>
            <div class="text-muted">All your subscription payments</div>
        </div>
        <a href="{{ route('admin.subscription.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Subscription
        </a>
    </div>

    <div class="card border-0 shadow-lg">
        <div class="card-body p-0">
            @if($payments->count())
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $idx => $p)
                                <tr>
                                    <td>{{ $payments->firstItem() + $idx }}</td>
                                    <td>{{ optional($p->paid_at)->format('d M Y H:i') }}</td>
                                    <td class="fw-semibold">NPR {{ number_format($p->amount, 0) }}</td>
                                    <td>
                                        <span class="badge {{ $p->status === 'completed' ? 'bg-success' : ($p->status === 'pending' ? 'bg-warning text-dark' : ($p->status === 'failed' ? 'bg-danger' : 'bg-secondary')) }}">{{ ucfirst($p->status) }}</span>
                                    </td>
                                    <td class="text-muted">{{ strtoupper($p->provider ?? '—') }}</td>
                                    <td class="text-muted">{{ $p->transaction_id ?? '—' }}</td>
                                    <td class="text-end">
                                        @if(!empty($p->invoice_url))
                                            <a href="{{ $p->invoice_url }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $payments->links() }}
                </div>
            @else
                <div class="p-5 text-center text-muted">
                    <i class="fas fa-receipt fa-2x mb-2"></i>
                    <div>No invoices found.</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection





