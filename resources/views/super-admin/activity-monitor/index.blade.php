@extends('super-admin.layout')

@section('title', 'Activity Monitor')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="dashboard-title">
                    <i class="fas fa-activity"></i>
                    Activity Monitor
                </h1>
                <p class="dashboard-subtitle">Real-time system monitoring and data synchronization</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-primary btn-lg" onclick="triggerSync('all')">
                    <i class="fas fa-sync-alt"></i>
                    Sync All Data
                </button>
            </div>
        </div>
    </div>

    <!-- Sync Status Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-sync-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $syncStatus['last_sync'] }}</h3>
                    <p>Last Sync</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $syncStatus['sync_frequency'] }}</h3>
                    <p>Sync Frequency</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $syncStatus['pending_syncs'] }}</h3>
                    <p>Pending Syncs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $syncStatus['failed_syncs'] }}</h3>
                    <p>Failed Syncs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-heartbeat"></i>
                        System Health
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="health-metric">
                                <div class="health-icon {{ $systemMetrics['database']['status'] === 'healthy' ? 'healthy' : 'unhealthy' }}">
                                    <i class="fas fa-database"></i>
                                </div>
                                <h6>Database</h6>
                                <small>{{ $systemMetrics['database']['size'] }}MB</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="health-metric">
                                <div class="health-icon {{ $systemMetrics['cache']['status'] === 'healthy' ? 'healthy' : 'unhealthy' }}">
                                    <i class="fas fa-memory"></i>
                                </div>
                                <h6>Cache</h6>
                                <small>{{ $systemMetrics['cache']['hit_rate'] }}</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="health-metric">
                                <div class="health-icon {{ $systemMetrics['queue']['status'] === 'healthy' ? 'healthy' : 'unhealthy' }}">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <h6>Queue</h6>
                                <small>{{ $systemMetrics['queue']['pending_jobs'] }} pending</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="health-metric">
                                <div class="health-icon {{ $systemMetrics['storage']['status'] === 'healthy' ? 'healthy' : 'unhealthy' }}">
                                    <i class="fas fa-hdd"></i>
                                </div>
                                <h6>Storage</h6>
                                <small>{{ $systemMetrics['storage']['free_space'] }} free</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="health-metric">
                                <div class="health-icon {{ $systemMetrics['api']['status'] === 'healthy' ? 'healthy' : 'unhealthy' }}">
                                    <i class="fas fa-plug"></i>
                                </div>
                                <h6>API</h6>
                                <small>{{ $systemMetrics['api']['response_time'] }}</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="health-metric">
                                <div class="health-icon healthy">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h6>Overall</h6>
                                <small>Healthy</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Sync Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bolt"></i>
                        Quick Sync Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <button class="btn btn-outline-primary w-100 mb-2" onclick="triggerSync('tenants')">
                                <i class="fas fa-store"></i>
                                Sync Tenants
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-success w-100 mb-2" onclick="triggerSync('payments')">
                                <i class="fas fa-dollar-sign"></i>
                                Sync Payments
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-info w-100 mb-2" onclick="triggerSync('orders')">
                                <i class="fas fa-shopping-cart"></i>
                                Sync Orders
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-warning w-100 mb-2" onclick="triggerSync('users')">
                                <i class="fas fa-users"></i>
                                Sync Users
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        <i class="fas fa-history"></i>
                        Recent Activities
                    </h5>
                    <button class="btn btn-sm btn-outline-primary" onclick="refreshActivities()">
                        <i class="fas fa-refresh"></i>
                        Refresh
                    </button>
                </div>
                <div class="card-body">
                    <div id="activities-container">
                        @forelse($activities as $activity)
                        <div class="activity-item">
                            <div class="activity-icon bg-{{ $activity['color'] }}">
                                <i class="{{ $activity['icon'] }}"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="activity-title">{{ $activity['title'] }}</h6>
                                <p class="activity-description">{{ $activity['description'] }}</p>
                                <small class="activity-time">{{ $activity['timestamp']->diffForHumans() }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent activities found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.health-metric {
    text-align: center;
    padding: 1rem;
}

.health-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: white;
}

.health-icon.healthy {
    background: linear-gradient(135deg, #10b981, #059669);
}

.health-icon.unhealthy {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.activity-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: white;
    font-size: 1rem;
}

.activity-content {
    flex: 1;
}

.activity-title {
    margin: 0 0 0.25rem 0;
    font-weight: 600;
}

.activity-description {
    margin: 0 0 0.25rem 0;
    color: #6b7280;
}

.activity-time {
    color: #9ca3af;
}

#sync-status {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
}
</style>

<script>
let syncInProgress = false;

function triggerSync(type) {
    if (syncInProgress) {
        showNotification('Sync already in progress', 'warning');
        return;
    }
    
    syncInProgress = true;
    showSyncStatus('Syncing data...', 'info');
    
    fetch(`{{ route('super.activity-monitor.sync') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ type: type })
    })
    .then(response => response.json())
    .then(data => {
        syncInProgress = false;
        hideSyncStatus();
        
        if (data.success) {
            showNotification('Sync completed successfully', 'success');
            refreshActivities();
        } else {
            showNotification('Sync failed: ' + data.message, 'error');
        }
    })
    .catch(error => {
        syncInProgress = false;
        hideSyncStatus();
        showNotification('Sync failed: ' + error.message, 'error');
    });
}

function refreshActivities() {
    fetch(`{{ route('super.activity-monitor.activities') }}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateActivitiesList(data.activities);
        }
    })
    .catch(error => {
        console.error('Error refreshing activities:', error);
    });
}

function updateActivitiesList(activities) {
    const container = document.getElementById('activities-container');
    
    if (activities.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">No recent activities found</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = activities.map(activity => `
        <div class="activity-item">
            <div class="activity-icon bg-${activity.color}">
                <i class="${activity.icon}"></i>
            </div>
            <div class="activity-content">
                <h6 class="activity-title">${activity.title}</h6>
                <p class="activity-description">${activity.description}</p>
                <small class="activity-time">${formatTime(activity.timestamp)}</small>
            </div>
        </div>
    `).join('');
}

function formatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;
    
    if (diff < 60000) return 'Just now';
    if (diff < 3600000) return Math.floor(diff / 60000) + ' minutes ago';
    if (diff < 86400000) return Math.floor(diff / 3600000) + ' hours ago';
    return Math.floor(diff / 86400000) + ' days ago';
}

function showSyncStatus(message, type) {
    const statusDiv = document.createElement('div');
    statusDiv.id = 'sync-status';
    statusDiv.className = `alert alert-${type} alert-dismissible fade show`;
    statusDiv.innerHTML = `
        <i class="fas fa-spinner fa-spin"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(statusDiv);
}

function hideSyncStatus() {
    const statusDiv = document.getElementById('sync-status');
    if (statusDiv) {
        statusDiv.remove();
    }
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Auto-refresh activities every 30 seconds
setInterval(refreshActivities, 30000);

// Auto-refresh system health every 60 seconds
setInterval(() => {
    fetch(`{{ route('super.activity-monitor.health') }}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateSystemHealth(data.health);
        }
    })
    .catch(error => {
        console.error('Error refreshing system health:', error);
    });
}, 60000);

function updateSystemHealth(health) {
    // Update health metrics in the UI
    // This would require updating the health metric elements
    console.log('System health updated:', health);
}
</script>
@endsection


