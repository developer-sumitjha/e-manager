@extends('super-admin.layout')

@section('title', 'Subscription Details')
@section('page-title', 'Subscription Details')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Subscription: {{ $subscription->subscription_id }}</h5>
            <a href="{{ route('super.subscriptions.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Tenant Information</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Business Name:</strong></td>
                            <td>{{ $subscription->tenant->business_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Subdomain:</strong></td>
                            <td><a href="https://{{ $subscription->tenant->subdomain }}.emanager.com" target="_blank">
                                {{ $subscription->tenant->subdomain }}.emanager.com
                            </a></td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $subscription->tenant->business_email }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Subscription Details</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Plan:</strong></td>
                            <td><span class="badge bg-info">{{ $subscription->plan->name }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                @if($subscription->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($subscription->status == 'trial')
                                    <span class="badge bg-warning">Trial</span>
                                @elseif($subscription->status == 'cancelled')
                                    <span class="badge bg-secondary">Cancelled</span>
                                @else
                                    <span class="badge bg-danger">{{ ucfirst($subscription->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Billing Cycle:</strong></td>
                            <td>{{ ucfirst($subscription->billing_cycle) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Amount:</strong></td>
                            <td>Rs. {{ number_format($subscription->amount, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <h6>Dates</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Started:</strong></td>
                            <td>{{ $subscription->starts_at ? $subscription->starts_at->format('M d, Y H:i') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Ends:</strong></td>
                            <td>{{ $subscription->ends_at ? $subscription->ends_at->format('M d, Y H:i') : 'N/A' }}</td>
                        </tr>
                        @if($subscription->trial_ends_at)
                        <tr>
                            <td><strong>Trial Ends:</strong></td>
                            <td>{{ $subscription->trial_ends_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @endif
                        @if($subscription->cancelled_at)
                        <tr>
                            <td><strong>Cancelled:</strong></td>
                            <td>{{ $subscription->cancelled_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Cancellation Reason:</strong></td>
                            <td>{{ $subscription->cancellation_reason ?? 'N/A' }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Settings</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Auto Renew:</strong></td>
                            <td>
                                @if($subscription->auto_renew)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                        </tr>
                        @if($subscription->next_billing_date)
                        <tr>
                            <td><strong>Next Billing:</strong></td>
                            <td>{{ $subscription->next_billing_date->format('M d, Y') }}</td>
                        </tr>
                        @endif
                        @if($subscription->last_payment_at)
                        <tr>
                            <td><strong>Last Payment:</strong></td>
                            <td>{{ $subscription->last_payment_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <hr>

            <div class="d-flex gap-2">
                @if($subscription->status == 'active' || $subscription->status == 'trial')
                <form action="{{ route('super.subscriptions.cancel', $subscription) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to cancel this subscription?')">
                    @csrf
                    <button type="submit" class="btn btn-danger">Cancel Subscription</button>
                </form>
                @endif

                @if($subscription->status == 'cancelled')
                <form action="{{ route('super.subscriptions.renew', $subscription) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Renew Subscription</button>
                </form>
                @endif
            </div>
        </div>
    </div>

    @if($subscription->payments->count() > 0)
    <div class="card mt-4">
        <div class="card-header">
            <h5>Payment History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscription->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_id }}</td>
                            <td>Rs. {{ number_format($payment->amount, 2) }}</td>
                            <td>{{ ucfirst($payment->payment_method) }}</td>
                            <td>
                                <span class="badge bg-{{ $payment->status == 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>{{ $payment->paid_at ? $payment->paid_at->format('M d, Y') : $payment->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection





