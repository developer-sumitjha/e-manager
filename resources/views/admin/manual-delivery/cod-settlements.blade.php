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

@extends('admin.layouts.app')

@section('title', 'COD Settlements')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">COD Settlements</h1>
            <p class="text-muted">Manage cash on delivery settlements with delivery boys</p>
        </div>
    </div>

    <!-- Pending COD Summary -->
    @if($pendingCod->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-left-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-exclamation-triangle"></i> Pending COD Settlements</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Delivery Boy</th>
                                    <th>Phone</th>
                                    <th>Pending Orders</th>
                                    <th>Pending Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingCod as $boy)
                                <tr>
                                    <td><strong>{{ $boy->name }}</strong> ({{ $boy->delivery_boy_id }})</td>
                                    <td>{{ $boy->phone }}</td>
                                    <td><span class="badge bg-info">{{ $boy->pending_orders }}</span></td>
                                    <td><strong class="text-danger">₨{{ number_format($boy->pending_amount, 2) }}</strong></td>
                                    <td>
                                        <a href="{{ route('admin.manual-delivery.create-settlement', $boy) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-money-bill-wave"></i> Settle
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.manual-delivery.cod-settlements') }}" class="row g-3">
                <div class="col-md-3">
                    <select name="delivery_boy_id" class="form-select">
                        <option value="">All Delivery Boys</option>
                        @foreach($deliveryBoys as $boy)
                            <option value="{{ $boy->id }}" {{ request('delivery_boy_id') == $boy->id ? 'selected' : '' }}>
                                {{ $boy->name }} ({{ $boy->delivery_boy_id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From Date">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To Date">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                    <a href="{{ route('admin.manual-delivery.cod-settlements') }}" class="btn btn-outline-secondary"><i class="fas fa-refresh"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Settlements History -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Settlement History ({{ $settlements->total() }})</h6>
        </div>
        <div class="card-body">
            @if($settlements->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Settlement ID</th>
                            <th>Delivery Boy</th>
                            <th>Total Orders</th>
                            <th>Total Amount</th>
                            <th>Payment Method</th>
                            <th>Settled By</th>
                            <th>Settled At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($settlements as $settlement)
                        <tr>
                            <td><strong>{{ $settlement->settlement_id }}</strong></td>
                            <td>
                                <div>{{ $settlement->deliveryBoy->name }}</div>
                                <small class="text-muted">{{ $settlement->deliveryBoy->delivery_boy_id }}</small>
                            </td>
                            <td><span class="badge bg-info">{{ $settlement->total_orders }}</span></td>
                            <td><strong class="text-success">₨{{ number_format($settlement->total_amount, 2) }}</strong></td>
                            <td><span class="badge bg-primary">{{ strtoupper(str_replace('_', ' ', $settlement->payment_method)) }}</span></td>
                            <td>{{ $settlement->settledBy->name }}</td>
                            <td>{{ $settlement->settled_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewSettlement({{ $settlement->id }})">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $settlements->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-receipt text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3">No Settlement Records</h4>
                <p class="text-muted">Settlements will appear here once created.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewSettlement(settlementId) {
    // This would show settlement details in a modal
    alert('View settlement details - Feature to be implemented');
}
</script>
@endsection


