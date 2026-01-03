@extends('super-admin.layout')

@section('title', 'Payment Details')
@section('page-title', 'Payment Details')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Payment: {{ $payment->payment_id }}</h5>
            <a href="{{ route('super.payments.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Payment Information</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Payment ID:</strong></td>
                            <td><code>{{ $payment->payment_id }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Amount:</strong></td>
                            <td><h4 class="mb-0">Rs. {{ number_format($payment->amount, 2) }}</h4></td>
                        </tr>
                        <tr>
                            <td><strong>Currency:</strong></td>
                            <td>{{ strtoupper($payment->currency) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
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
                        </tr>
                        <tr>
                            <td><strong>Payment Method:</strong></td>
                            <td>
                                @if($payment->payment_method == 'esewa')
                                    <span class="badge bg-success">eSewa</span>
                                @elseif($payment->payment_method == 'khalti')
                                    <span class="badge bg-purple">Khalti</span>
                                @else
                                    {{ ucfirst($payment->payment_method) }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Tenant Information</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Business Name:</strong></td>
                            <td>{{ $payment->tenant->business_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Subdomain:</strong></td>
                            <td><a href="https://{{ $payment->tenant->subdomain }}.emanager.com" target="_blank">
                                {{ $payment->tenant->subdomain }}.emanager.com
                            </a></td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $payment->tenant->business_email }}</td>
                        </tr>
                        @if($payment->subscription)
                        <tr>
                            <td><strong>Subscription:</strong></td>
                            <td>
                                <a href="{{ route('super.subscriptions.show', $payment->subscription) }}">
                                    {{ $payment->subscription->plan->name }}
                                </a>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <h6>Transaction Details</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Transaction ID:</strong></td>
                            <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Gateway Reference:</strong></td>
                            <td>{{ $payment->gateway_reference ?? 'N/A' }}</td>
                        </tr>
                        @if($payment->gateway_response)
                        <tr>
                            <td><strong>Gateway Response:</strong></td>
                            <td><pre>{{ json_encode(json_decode($payment->gateway_response), JSON_PRETTY_PRINT) }}</pre></td>
                        </tr>
                        @endif
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Dates</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Created:</strong></td>
                            <td>{{ $payment->created_at->format('M d, Y H:i:s') }}</td>
                        </tr>
                        @if($payment->paid_at)
                        <tr>
                            <td><strong>Paid At:</strong></td>
                            <td>{{ $payment->paid_at->format('M d, Y H:i:s') }}</td>
                        </tr>
                        @endif
                        @if($payment->refunded_at)
                        <tr>
                            <td><strong>Refunded At:</strong></td>
                            <td>{{ $payment->refunded_at->format('M d, Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Refund Amount:</strong></td>
                            <td>Rs. {{ number_format($payment->refund_amount, 2) }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            @if($payment->description)
            <hr>
            <h6>Description</h6>
            <p>{{ $payment->description }}</p>
            @endif

            @if($payment->notes)
            <hr>
            <h6>Notes</h6>
            <p>{{ $payment->notes }}</p>
            @endif
        </div>
    </div>

    @if($payment->invoice)
    <div class="card mt-4">
        <div class="card-header">
            <h5>Related Invoice</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <td width="30%"><strong>Invoice ID:</strong></td>
                    <td>{{ $payment->invoice->invoice_id }}</td>
                </tr>
                <tr>
                    <td><strong>Amount:</strong></td>
                    <td>Rs. {{ number_format($payment->invoice->amount, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Due Date:</strong></td>
                    <td>{{ $payment->invoice->due_date ? $payment->invoice->due_date->format('M d, Y') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Status:</strong></td>
                    <td>
                        <span class="badge bg-{{ $payment->invoice->status == 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($payment->invoice->status) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    @endif
</div>

<style>
.bg-purple { background-color: #6f42c1 !important; }
</style>
@endsection





