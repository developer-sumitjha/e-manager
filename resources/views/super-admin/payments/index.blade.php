@extends('super-admin.layout')

@section('title', 'Payments Management')
@section('page-title', 'Payments')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="text-muted">Total Revenue</h6>
                    <h3 class="text-success">Rs. {{ number_format($stats['total_revenue'], 0) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="text-muted">This Month</h6>
                    <h3 class="text-primary">Rs. {{ number_format($stats['this_month'], 0) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <h6 class="text-muted">Today</h6>
                    <h3 class="text-info">Rs. {{ number_format($stats['today'], 0) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="text-muted">Pending</h6>
                    <h3 class="text-warning">Rs. {{ number_format($stats['pending_amount'], 0) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-2">
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="method" class="form-select">
                        <option value="">All Methods</option>
                        <option value="esewa" {{ request('method') == 'esewa' ? 'selected' : '' }}>eSewa</option>
                        <option value="khalti" {{ request('method') == 'khalti' ? 'selected' : '' }}>Khalti</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Tenant</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Transaction ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td><code>{{ $payment->payment_id }}</code></td>
                            <td>
                                <strong>{{ $payment->tenant->business_name }}</strong><br>
                                <small class="text-muted">{{ $payment->tenant->subdomain }}.emanager.com</small>
                            </td>
                            <td><strong>Rs. {{ number_format($payment->amount, 0) }}</strong></td>
                            <td>
                                @if($payment->payment_method == 'esewa')
                                    <span class="badge bg-success">eSewa</span>
                                @elseif($payment->payment_method == 'khalti')
                                    <span class="badge bg-purple">Khalti</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($payment->payment_method) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($payment->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($payment->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($payment->status == 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $payment->paid_at ? $payment->paid_at->format('M d, Y H:i') : $payment->created_at->format('M d, Y H:i') }}</td>
                            <td><small>{{ $payment->transaction_id ?? '-' }}</small></td>
                            <td>
                                <a href="{{ route('super.payments.show', $payment) }}" class="btn btn-sm btn-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No payments found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
            <div class="mt-4">
                {{ $payments->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.bg-purple { background-color: #6f42c1 !important; }
</style>
@endsection







