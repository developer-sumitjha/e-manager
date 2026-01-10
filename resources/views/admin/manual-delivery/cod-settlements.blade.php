@extends('admin.layouts.app')

@section('title', 'COD Settlements')

@section('content')
<div class="p-4 rounded-3 mb-4" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); color:#fff;">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h2 class="m-0 fw-bold"><i class="fas fa-hand-holding-usd"></i> COD Settlements (Manual Delivery)</h2>
            <div class="opacity-75">Settle collected COD from delivery riders</div>
        </div>
        <a href="{{ route('admin.manual-delivery.index') }}" class="btn btn-light"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold">Pending COD by Rider</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Rider</th><th class="text-end">Pending</th><th></th></tr>
                        </thead>
                        <tbody>
                            @forelse($pendingCod as $row)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $row->name }}</div>
                                    <div class="text-muted small">{{ $row->phone }}</div>
                                </td>
                                <td class="text-end">₨ {{ number_format($row->pending_amount,0) }} ({{ $row->pending_orders }} orders)</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.manual-delivery.create-settlement', $row->id) }}" class="btn btn-sm btn-primary">Settle</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted">No pending COD</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Recent Settlements</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Rider</th>
                                <th>Orders</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th>Settled At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($settlements as $s)
                            <tr>
                                <td>{{ $s->settlement_id }}</td>
                                <td>{{ $s->deliveryBoy->name }}</td>
                                <td>{{ $s->total_orders }}</td>
                                <td>₨ {{ number_format($s->total_amount,0) }}</td>
                                <td>{{ ucfirst(str_replace('_',' ', $s->payment_method)) }}</td>
                                <td class="text-muted">{{ $s->transaction_reference ?? '—' }}</td>
                                <td>{{ optional($s->settled_at)->format('d M Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted">No settlements</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $settlements->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
