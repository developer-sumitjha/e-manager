@extends('admin.layouts.app')

@section('title', 'Gaaubesi Shipment Details')
@section('page-title', 'Gaaubesi Shipment Details')

@push('styles')
<style>
    /* Shipment Details Specific Styles */
    .details-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title-section h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #8B5CF6;
        margin: 0;
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
    }

    .action-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .action-button.back {
        background: rgba(107, 114, 128, 0.1);
        color: #6B7280;
        border: 1px solid rgba(107, 114, 128, 0.2);
    }

    .action-button.back:hover {
        background: rgba(107, 114, 128, 0.2);
        color: #6B7280;
    }

    .action-button.refresh {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .action-button.refresh:hover {
        background: rgba(59, 130, 246, 0.2);
        color: #3B82F6;
    }

    .action-button.download {
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        color: white;
    }

    .action-button.download:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3);
        color: white;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .details-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
        backdrop-filter: blur(10px);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(139, 92, 246, 0.1);
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-title i {
        color: #8B5CF6;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(139, 92, 246, 0.05);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .detail-value {
        font-weight: 500;
        color: var(--text-primary);
        text-align: right;
        max-width: 60%;
    }

    .tracking-number {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: #8B5CF6;
        background: rgba(139, 92, 246, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-created {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
    }

    .status-transit {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }

    .status-delivered {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .status-failed {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .cod-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .cod-badge.paid {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .cod-badge.unpaid {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .timeline {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
        backdrop-filter: blur(10px);
        margin-bottom: 2rem;
    }

    .timeline-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 1.5rem;
        top: 3rem;
        bottom: -1.5rem;
        width: 2px;
        background: rgba(139, 92, 246, 0.2);
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
        flex-shrink: 0;
        z-index: 1;
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-title {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .timeline-description {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .timeline-date {
        color: var(--text-secondary);
        font-size: 0.75rem;
        font-weight: 500;
    }

    .comments-section {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
        backdrop-filter: blur(10px);
    }

    .comment-item {
        background: rgba(139, 92, 246, 0.03);
        border-left: 3px solid #8B5CF6;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .comment-author {
        font-weight: 600;
        color: #8B5CF6;
    }

    .comment-date {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .comment-text {
        color: var(--text-primary);
        line-height: 1.5;
    }

    .comment-form {
        margin-top: 1.5rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .details-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .header-actions {
            flex-direction: column;
            width: 100%;
        }

        .action-button {
            width: 100%;
            justify-content: center;
        }

        .details-grid {
            grid-template-columns: 1fr;
        }

        .details-card {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="details-header">
    <div class="page-title-section">
        <h1>Shipment #{{ $gaaubesiShipment->gaaubesi_order_id ?? 'N/A' }}</h1>
        <p class="page-subtitle">Gaaubesi Logistics Shipment Details</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.gaaubesi.index') }}" class="action-button back">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        @if($gaaubesiShipment->gaaubesi_order_id)
        <button type="button" class="action-button refresh" onclick="refreshStatus()">
            <i class="fas fa-sync"></i> Refresh Status
        </button>
        <a href="{{ route('admin.gaaubesi.download-label', $gaaubesiShipment->id) }}" class="action-button download">
            <i class="fas fa-download"></i> Download Label
        </a>
        @endif
    </div>
</div>

<div class="details-grid">
    <!-- Main Shipment Details -->
    <div class="details-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-shipping-fast"></i>
                Shipment Information
            </h3>
            <span class="status-badge status-{{ $gaaubesiShipment->getStatusColor() }}">
                {{ $gaaubesiShipment->last_delivery_status ?? 'Created' }}
            </span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Gaaubesi Order ID</span>
            <span class="detail-value">
                <span class="tracking-number">{{ $gaaubesiShipment->gaaubesi_order_id ?? 'Pending' }}</span>
            </span>
        </div>

        @if($gaaubesiShipment->track_id)
        <div class="detail-row">
            <span class="detail-label">Tracking ID</span>
            <span class="detail-value">
                <span class="tracking-number">{{ $gaaubesiShipment->track_id }}</span>
            </span>
        </div>
        @endif

        <div class="detail-row">
            <span class="detail-label">Our Order Number</span>
            <span class="detail-value">
                <a href="{{ route('admin.orders.show', $gaaubesiShipment->order_id) }}">
                    {{ $gaaubesiShipment->order->order_number }}
                </a>
            </span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Source Branch</span>
            <span class="detail-value">{{ $gaaubesiShipment->source_branch }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Destination Branch</span>
            <span class="detail-value">{{ $gaaubesiShipment->destination_branch }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Receiver Name</span>
            <span class="detail-value">{{ $gaaubesiShipment->receiver_name }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Receiver Phone</span>
            <span class="detail-value">{{ $gaaubesiShipment->receiver_number }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Receiver Address</span>
            <span class="detail-value">{{ $gaaubesiShipment->receiver_address }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Package Access</span>
            <span class="detail-value">{{ $gaaubesiShipment->package_access }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Delivery Type</span>
            <span class="detail-value">{{ $gaaubesiShipment->delivery_type }}</span>
        </div>

        @if($gaaubesiShipment->package_type)
        <div class="detail-row">
            <span class="detail-label">Package Type</span>
            <span class="detail-value">{{ $gaaubesiShipment->package_type }}</span>
        </div>
        @endif

        @if($gaaubesiShipment->remarks)
        <div class="detail-row">
            <span class="detail-label">Remarks</span>
            <span class="detail-value">{{ $gaaubesiShipment->remarks }}</span>
        </div>
        @endif

        <div class="detail-row">
            <span class="detail-label">Created By</span>
            <span class="detail-value">{{ $gaaubesiShipment->createdBy->name ?? 'System' }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Created At</span>
            <span class="detail-value">{{ $gaaubesiShipment->created_at->format('M d, Y H:i') }}</span>
        </div>
    </div>

    <!-- Payment & Order Summary -->
    <div>
        <div class="details-card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-money-bill-wave"></i>
                    Payment Details
                </h3>
            </div>

            <div class="detail-row">
                <span class="detail-label">COD Charge</span>
                <span class="detail-value">
                    <strong>₹{{ number_format($gaaubesiShipment->cod_charge, 2) }}</strong>
                </span>
            </div>

            @if($gaaubesiShipment->delivery_charge)
            <div class="detail-row">
                <span class="detail-label">Delivery Charge</span>
                <span class="detail-value">₹{{ number_format($gaaubesiShipment->delivery_charge, 2) }}</span>
            </div>
            @endif

            <div class="detail-row">
                <span class="detail-label">COD Payment Status</span>
                <span class="detail-value">
                    <span class="cod-badge {{ $gaaubesiShipment->cod_paid ? 'paid' : 'unpaid' }}">
                        {{ $gaaubesiShipment->cod_paid ? 'Paid' : 'Unpaid' }}
                    </span>
                </span>
            </div>
        </div>

        <div class="details-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-box"></i>
                    Order Summary
                </h3>
            </div>

            <div class="detail-row">
                <span class="detail-label">Order Total</span>
                <span class="detail-value">₹{{ number_format($gaaubesiShipment->order->total, 2) }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Payment Status</span>
                <span class="detail-value">{{ ucfirst($gaaubesiShipment->order->payment_status) }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Items Count</span>
                <span class="detail-value">{{ $gaaubesiShipment->order->orderItems->count() }} items</span>
            </div>
        </div>
    </div>
</div>

<!-- Status Timeline -->
@if($orderStatus && count($orderStatus) > 0)
<div class="timeline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-route"></i>
            Shipping Timeline
        </h3>
    </div>

    @foreach($orderStatus as $status)
    <div class="timeline-item">
        <div class="timeline-icon">
            <i class="fas fa-shipping-fast"></i>
        </div>
        <div class="timeline-content">
            <div class="timeline-title">{{ $status }}</div>
            <div class="timeline-description">Status update from Gaaubesi</div>
        </div>
    </div>
    @endforeach
</div>
@endif

<!-- Comments Section -->
<div class="comments-section">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-comments"></i>
            Comments & Notes
        </h3>
    </div>

    @if($comments && count($comments) > 0)
        @foreach($comments as $comment)
        <div class="comment-item">
            <div class="comment-header">
                <span class="comment-author">{{ $comment['created_by'] ?? 'Gaaubesi Staff' }}</span>
                <span class="comment-date">{{ $comment['created_on'] ?? '' }}</span>
            </div>
            <div class="comment-text">{{ $comment['comments'] ?? '' }}</div>
        </div>
        @endforeach
    @else
        <p class="text-muted">No comments yet.</p>
    @endif

    <!-- Add Comment Form -->
    @if($gaaubesiShipment->gaaubesi_order_id)
    <div class="comment-form">
        <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem;">Add Comment</h4>
        <form id="comment-form">
            @csrf
            <div class="mb-3">
                <textarea class="form-control" id="comment-text" rows="3" placeholder="Enter your comment..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Post Comment
            </button>
        </form>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function refreshStatus() {
        const btn = event.target.closest('button');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';

        fetch('{{ route("admin.gaaubesi.refresh-status", $gaaubesiShipment->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Status refreshed successfully!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.message || 'Failed to refresh status', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-sync"></i> Refresh Status';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while refreshing status', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sync"></i> Refresh Status';
        });
    }

    @if($gaaubesiShipment->gaaubesi_order_id)
    // Comment form submission
    document.getElementById('comment-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const commentText = document.getElementById('comment-text').value;
        const submitBtn = this.querySelector('button[type="submit"]');
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Posting...';

        fetch('{{ route("admin.gaaubesi.post-comment", $gaaubesiShipment->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                comment: commentText
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Comment posted successfully!', 'success');
                document.getElementById('comment-text').value = '';
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.message || 'Failed to post comment', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Post Comment';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while posting comment', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Post Comment';
        });
    });
    @endif
</script>
@endpush






