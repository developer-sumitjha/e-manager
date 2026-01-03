@extends('super-admin.layout')

@section('title', 'Tenant Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>{{ $tenant->business_name }}</h1>
            <p class="text-muted">Tenant ID: {{ $tenant->tenant_id }}</p>
        </div>
        <div>
            <a href="{{ route('super.tenants.edit', $tenant) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('super.tenants.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Business Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Business Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Business Name</label>
                            <p class="fw-bold">{{ $tenant->business_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Business Email</label>
                            <p class="fw-bold">{{ $tenant->business_email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Business Phone</label>
                            <p class="fw-bold">{{ $tenant->business_phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Business Type</label>
                            <p class="fw-bold">{{ ucfirst($tenant->business_type ?? 'N/A') }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted">Subdomain</label>
                            <p class="fw-bold">
                                <a href="http://{{ $tenant->subdomain }}.emanager.com" target="_blank">
                                    {{ $tenant->subdomain }}.emanager.com
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Owner Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Owner Name</label>
                            <p class="fw-bold">{{ $tenant->owner_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Owner Email</label>
                            <p class="fw-bold">{{ $tenant->owner_email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Owner Phone</label>
                            <p class="fw-bold">{{ $tenant->owner_phone }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription History -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Subscription History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Subscription ID</th>
                                    <th>Plan</th>
                                    <th>Period</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tenant->subscriptions as $subscription)
                                <tr>
                                    <td>{{ $subscription->subscription_id }}</td>
                                    <td>{{ $subscription->plan->name }}</td>
                                    <td>
                                        {{ $subscription->starts_at->format('M d, Y') }} - 
                                        {{ $subscription->ends_at->format('M d, Y') }}
                                    </td>
                                    <td>Rs. {{ number_format($subscription->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $subscription->status == 'active' ? 'success' : ($subscription->status == 'trial' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No subscriptions yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Payment History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Payment ID</th>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tenant->payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_id }}</td>
                                    <td>{{ $payment->paid_at?->format('M d, Y') ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($payment->payment_method) }}</td>
                                    <td>Rs. {{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status == 'completed' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No payments yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Activity Log -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Activity Log</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($tenant->activities()->latest()->take(20)->get() as $activity)
                        <div class="timeline-item mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-circle text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-0"><strong>{{ $activity->action }}</strong></p>
                                    <p class="text-muted mb-0">{{ $activity->description }}</p>
                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center">No activity logged yet</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted">Current Status</label>
                        <p>
                            @if($tenant->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($tenant->status == 'trial')
                                <span class="badge bg-warning">Trial</span>
                            @elseif($tenant->status == 'suspended')
                                <span class="badge bg-danger">Suspended</span>
                            @elseif($tenant->status == 'pending')
                                <span class="badge bg-secondary">Pending</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Current Plan</label>
                        <p class="fw-bold">{{ $tenant->currentPlan->name ?? 'N/A' }}</p>
                    </div>

                    @if($tenant->isOnTrial())
                    <div class="mb-3">
                        <label class="text-muted">Trial Ends</label>
                        <p class="fw-bold">{{ $tenant->trial_ends_at->format('M d, Y') }}</p>
                        <small class="text-muted">{{ $tenant->getDaysUntilTrialEnd() }} days remaining</small>
                    </div>
                    @endif

                    @if($tenant->subscriptionActive())
                    <div class="mb-3">
                        <label class="text-muted">Subscription Ends</label>
                        <p class="fw-bold">{{ $tenant->subscription_ends_at->format('M d, Y') }}</p>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="text-muted">Registered</label>
                        <p>{{ $tenant->created_at->format('M d, Y') }}</p>
                        <small class="text-muted">{{ $tenant->created_at->diffForHumans() }}</small>
                    </div>

                    <hr>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        @if($tenant->status == 'pending')
                            <form action="{{ route('super.tenants.approve', $tenant) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check"></i> Approve Tenant
                                </button>
                            </form>
                        @elseif($tenant->status == 'active' || $tenant->status == 'trial')
                            <form action="{{ route('super.tenants.suspend', $tenant) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to suspend this tenant?')">
                                    <i class="fas fa-ban"></i> Suspend Tenant
                                </button>
                            </form>
                        @elseif($tenant->status == 'suspended')
                            <form action="{{ route('super.tenants.activate', $tenant) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check-circle"></i> Activate Tenant
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Usage Stats -->
            @if(isset($usageStats))
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Usage Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Orders</span>
                            <span>{{ $usageStats['orders']['used'] }} / {{ $usageStats['orders']['limit'] }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $usageStats['orders']['percentage'] }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Products</span>
                            <span>{{ $usageStats['products']['used'] }} / {{ $usageStats['products']['limit'] }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $usageStats['products']['percentage'] }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Users</span>
                            <span>{{ $usageStats['users']['used'] }} / {{ $usageStats['users']['limit'] }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $usageStats['users']['percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection






