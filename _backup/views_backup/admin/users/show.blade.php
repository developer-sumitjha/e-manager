@extends('admin.layouts.app')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">User Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Role:</strong> 
                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'primary' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </p>
                <p><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                <p><strong>Total Orders:</strong> {{ $user->orders->count() }}</p>
                <p><strong>Total Spent:</strong> ${{ number_format($user->orders->where('payment_status', 'paid')->sum('total'), 2) }}</p>
            </div>
        </div>
        @if($user->role === 'employee')
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Allotted Access</h5>
            </div>
            <div class="card-body">
                @php
                    $perms = is_array($user->permissions) ? $user->permissions : (json_decode($user->permissions, true) ?: []);
                @endphp
                @if(empty($perms))
                    <p class="text-muted mb-0">No specific access granted.</p>
                @else
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($perms as $perm)
                            <span class="badge bg-success text-uppercase">{{ str_replace('_', ' ', $perm) }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->orders()->latest()->take(5)->get() as $order)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $order) }}">#{{ $order->order_number }}</a></td>
                                <td>${{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'info') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No orders yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to Users</a>
    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">Edit User</a>
</div>
@endsection




