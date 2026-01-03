@extends('super-admin.layout')

@section('title', 'Subscriptions Management')
@section('page-title', 'Subscriptions')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Subscriptions</h6>
                    <h3>{{ $subscriptions->total() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="text-muted">Active</h6>
                    <h3 class="text-success">{{ \App\Models\Subscription::where('status', 'active')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="text-muted">On Trial</h6>
                    <h3 class="text-warning">{{ \App\Models\Subscription::where('status', 'trial')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body">
                    <h6 class="text-muted">Cancelled</h6>
                    <h3 class="text-danger">{{ \App\Models\Subscription::where('status', 'cancelled')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search tenant..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="trial" {{ request('status') == 'trial' ? 'selected' : '' }}>Trial</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="past_due" {{ request('status') == 'past_due' ? 'selected' : '' }}>Past Due</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="plan" class="form-select">
                        <option value="">All Plans</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ request('plan') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Subscriptions Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tenant</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Billing Cycle</th>
                            <th>Started</th>
                            <th>Ends</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $subscription)
                        <tr>
                            <td>{{ $subscription->subscription_id }}</td>
                            <td>
                                <strong>{{ $subscription->tenant->business_name }}</strong><br>
                                <small class="text-muted">{{ $subscription->tenant->subdomain }}.emanager.com</small>
                            </td>
                            <td><span class="badge bg-info">{{ $subscription->plan->name }}</span></td>
                            <td>
                                @if($subscription->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($subscription->status == 'trial')
                                    <span class="badge bg-warning">Trial</span>
                                @elseif($subscription->status == 'past_due')
                                    <span class="badge bg-danger">Past Due</span>
                                @elseif($subscription->status == 'cancelled')
                                    <span class="badge bg-secondary">Cancelled</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($subscription->billing_cycle) }}</td>
                            <td>{{ $subscription->starts_at ? $subscription->starts_at->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $subscription->ends_at ? $subscription->ends_at->format('M d, Y') : 'N/A' }}</td>
                            <td>Rs. {{ number_format($subscription->amount, 0) }}</td>
                            <td>
                                <a href="{{ route('super.subscriptions.show', $subscription) }}" class="btn btn-sm btn-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No subscriptions found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($subscriptions->hasPages())
            <div class="mt-4">
                {{ $subscriptions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection





