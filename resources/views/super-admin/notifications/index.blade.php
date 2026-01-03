@extends('super-admin.layout')

@section('title', 'Notification Center')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="dashboard-title">
                    <i class="fas fa-bell"></i>
                    Notification Center
                    @if($unreadCount > 0)
                        <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
                    @endif
                </h1>
                <p class="dashboard-subtitle">Manage system alerts, notifications, and communications</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-primary btn-lg me-2" data-bs-toggle="modal" data-bs-target="#createNotificationModal">
                    <i class="fas fa-plus"></i>
                    Create Notification
                </button>
                <button class="btn btn-outline-secondary btn-lg" onclick="markAllAsRead()">
                    <i class="fas fa-check-double"></i>
                    Mark All Read
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills nav-fill" id="notificationTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab">
                                <i class="fas fa-list"></i> All Notifications
                                <span class="badge bg-secondary ms-2">{{ $notifications->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="unread-tab" data-bs-toggle="pill" data-bs-target="#unread" type="button" role="tab">
                                <i class="fas fa-envelope"></i> Unread
                                <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="urgent-tab" data-bs-toggle="pill" data-bs-target="#urgent" type="button" role="tab">
                                <i class="fas fa-exclamation-triangle"></i> Urgent
                                <span class="badge bg-danger ms-2">{{ $notifications->where('priority', 'urgent')->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="system-tab" data-bs-toggle="pill" data-bs-target="#system" type="button" role="tab">
                                <i class="fas fa-cog"></i> System
                                <span class="badge bg-info ms-2">{{ $notifications->where('type', 'info')->count() }}</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="tab-content" id="notificationTabsContent">
                        <!-- All Notifications -->
                        <div class="tab-pane fade show active" id="all" role="tabpanel">
                            <div id="all-notifications">
                                @forelse($notifications as $notification)
                                    @include('super-admin.notifications.partials.notification-item', ['notification' => $notification])
                                @empty
                                    <div class="text-center py-5">
                                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No notifications found</h5>
                                        <p class="text-muted">You're all caught up!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Unread Notifications -->
                        <div class="tab-pane fade" id="unread" role="tabpanel">
                            <div id="unread-notifications">
                                @forelse($notifications->where('read', false) as $notification)
                                    @include('super-admin.notifications.partials.notification-item', ['notification' => $notification])
                                @empty
                                    <div class="text-center py-5">
                                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                        <h5 class="text-muted">No unread notifications</h5>
                                        <p class="text-muted">All notifications have been read!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Urgent Notifications -->
                        <div class="tab-pane fade" id="urgent" role="tabpanel">
                            <div id="urgent-notifications">
                                @forelse($notifications->where('priority', 'urgent') as $notification)
                                    @include('super-admin.notifications.partials.notification-item', ['notification' => $notification])
                                @empty
                                    <div class="text-center py-5">
                                        <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                                        <h5 class="text-muted">No urgent notifications</h5>
                                        <p class="text-muted">Everything is running smoothly!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- System Notifications -->
                        <div class="tab-pane fade" id="system" role="tabpanel">
                            <div id="system-notifications">
                                @forelse($notifications->where('type', 'info') as $notification)
                                    @include('super-admin.notifications.partials.notification-item', ['notification' => $notification])
                                @empty
                                    <div class="text-center py-5">
                                        <i class="fas fa-info-circle fa-3x text-info mb-3"></i>
                                        <h5 class="text-muted">No system notifications</h5>
                                        <p class="text-muted">System is operating normally!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Notification Modal -->
<div class="modal fade" id="createNotificationModal" tabindex="-1" aria-labelledby="createNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createNotificationModalLabel">
                    <i class="fas fa-plus"></i>
                    Create New Notification
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createNotificationForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="notificationTitle" class="form-label">Title</label>
                                <input type="text" class="form-control" id="notificationTitle" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="notificationType" class="form-label">Type</label>
                                <select class="form-select" id="notificationType" name="type" required>
                                    <option value="info">Info</option>
                                    <option value="warning">Warning</option>
                                    <option value="error">Error</option>
                                    <option value="success">Success</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="notificationPriority" class="form-label">Priority</label>
                                <select class="form-select" id="notificationPriority" name="priority" required>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="targetTenants" class="form-label">Target Tenants</label>
                                <select class="form-select" id="targetTenants" name="target_tenants[]" multiple>
                                    @foreach(\App\Models\Tenant::all() as $tenant)
                                        <option value="{{ $tenant->id }}">{{ $tenant->business_name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Leave empty to send to all tenants</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notificationMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="notificationMessage" name="message" rows="4" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sendEmail" name="send_email">
                                <label class="form-check-label" for="sendEmail">
                                    <i class="fas fa-envelope"></i> Send Email
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sendSms" name="send_sms">
                                <label class="form-check-label" for="sendSms">
                                    <i class="fas fa-sms"></i> Send SMS
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Send Notification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.notification-item {
    border-bottom: 1px solid #e5e7eb;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item:hover {
    background-color: #f8fafc;
}

.notification-item.unread {
    background-color: #fef3cd;
    border-left: 4px solid #f59e0b;
}

.notification-item.urgent {
    background-color: #fef2f2;
    border-left: 4px solid #ef4444;
}

.notification-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.25rem;
    color: white;
}

.notification-icon.info {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
}

.notification-icon.warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.notification-icon.error {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.notification-icon.success {
    background: linear-gradient(135deg, #10b981, #059669);
}

.notification-content {
    flex: 1;
}

.notification-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.notification-message {
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.notification-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 0.875rem;
    color: #9ca3af;
}

.priority-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
}

.priority-badge.urgent {
    background-color: #fef2f2;
    color: #dc2626;
}

.priority-badge.high {
    background-color: #fef3cd;
    color: #d97706;
}

.priority-badge.medium {
    background-color: #dbeafe;
    color: #1d4ed8;
}

.priority-badge.low {
    background-color: #f0fdf4;
    color: #059669;
}

.notification-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.notification-actions .btn {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}
</style>

<script>
function markAsRead(notificationId) {
    fetch('{{ route("super.notifications.mark-read") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ id: notificationId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.classList.remove('unread');
                notificationElement.querySelector('.unread-indicator').style.display = 'none';
            }
            
            // Update counters
            updateNotificationCounters();
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
    });
}

function markAllAsRead() {
    fetch('{{ route("super.notifications.mark-read") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
                item.querySelector('.unread-indicator').style.display = 'none';
            });
            
            // Update counters
            updateNotificationCounters();
            
            showNotification('All notifications marked as read', 'success');
        }
    })
    .catch(error => {
        console.error('Error marking all notifications as read:', error);
    });
}

function deleteNotification(notificationId) {
    if (!confirm('Are you sure you want to delete this notification?')) {
        return;
    }
    
    fetch('{{ route("super.notifications.delete") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ id: notificationId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove from UI
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.remove();
            }
            
            // Update counters
            updateNotificationCounters();
            
            showNotification('Notification deleted successfully', 'success');
        }
    })
    .catch(error => {
        console.error('Error deleting notification:', error);
    });
}

function updateNotificationCounters() {
    // Update unread count
    const unreadCount = document.querySelectorAll('.notification-item.unread').length;
    document.querySelectorAll('.badge').forEach(badge => {
        if (badge.textContent.includes('Unread')) {
            badge.textContent = `Unread ${unreadCount}`;
        }
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Create notification form
document.getElementById('createNotificationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    // Handle multiple select values
    data.target_tenants = Array.from(document.getElementById('targetTenants').selectedOptions).map(option => option.value);
    
    fetch('{{ route("super.notifications.create") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Notification created successfully', 'success');
            document.getElementById('createNotificationModal').querySelector('.btn-close').click();
            this.reset();
            // Refresh notifications
            location.reload();
        } else {
            showNotification('Error creating notification: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error creating notification:', error);
        showNotification('Error creating notification', 'error');
    });
});

// Auto-refresh notifications every 30 seconds
setInterval(() => {
    fetch('{{ route("super.notifications.get") }}')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update notification counts
            document.querySelectorAll('.badge').forEach(badge => {
                if (badge.textContent.includes('All Notifications')) {
                    badge.textContent = `All Notifications ${data.notifications.length}`;
                } else if (badge.textContent.includes('Unread')) {
                    badge.textContent = `Unread ${data.unreadCount}`;
                }
            });
        }
    })
    .catch(error => {
        console.error('Error refreshing notifications:', error);
    });
}, 30000);
</script>
@endsection


