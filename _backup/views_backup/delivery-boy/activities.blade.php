@extends('delivery-boy.layouts.app')

@section('title', 'Activities')

@section('content')
<div class="top-bar">
    <div class="page-title">
        <h1>Activity Log</h1>
        <p>Track all your account activities</p>
    </div>
</div>

<div class="table-card">
    <div class="card-header">
        <h5><i class="fas fa-history me-2"></i> Recent Activities ({{ $activities->total() }})</h5>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Description</th>
                    <th>Order</th>
                    <th>Date & Time</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $activity)
                <tr>
                    <td>
                        <span class="badge bg-primary">{{ strtoupper(str_replace('_', ' ', $activity->activity_type)) }}</span>
                    </td>
                    <td>{{ $activity->description }}</td>
                    <td>
                        @if($activity->manualDelivery)
                            <a href="{{ route('delivery-boy.delivery.show', $activity->manualDelivery) }}">
                                {{ $activity->manualDelivery->order->order_number }}
                            </a>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>{{ $activity->created_at->format('M d, Y h:i A') }}</td>
                    <td><small class="text-muted">{{ $activity->ip_address }}</small></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <i class="fas fa-history text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-2 text-muted">No activities recorded yet</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($activities->hasPages())
    <div class="card-body">
        {{ $activities->links() }}
    </div>
    @endif
</div>
@endsection





