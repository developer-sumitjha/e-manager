@extends('super-admin.layout')

@section('title', 'System Monitor')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="dashboard-title">
                    <i class="fas fa-heartbeat"></i>
                    System Monitor
                </h1>
                <p class="dashboard-subtitle">Monitor system health, performance, and resources</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-primary btn-lg me-2" onclick="refreshSystemStatus()">
                    <i class="fas fa-sync-alt"></i>
                    Refresh Status
                </button>
                <button class="btn btn-success btn-lg" onclick="clearSystemCache()">
                    <i class="fas fa-broom"></i>
                    Clear Cache
                </button>
            </div>
        </div>
    </div>

    <!-- Overall Health Score -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <div class="health-score-circle">
                        <div class="score-circle {{ $systemHealth['overall']['status'] }}">
                            <span class="score-number">{{ $systemHealth['overall']['score'] }}</span>
                            <span class="score-label">Health Score</span>
                        </div>
                    </div>
                    <h4 class="mt-3 text-{{ $systemHealth['overall']['status'] === 'healthy' ? 'success' : ($systemHealth['overall']['status'] === 'warning' ? 'warning' : 'danger') }}">
                        {{ ucfirst($systemHealth['overall']['status']) }}
                    </h4>
                    <p class="text-muted">{{ $systemHealth['overall']['message'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health Components -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="health-card {{ $systemHealth['database']['status'] }}">
                <div class="health-icon">
                    <i class="fas fa-database"></i>
                </div>
                <h6>Database</h6>
                <p class="health-status">{{ ucfirst($systemHealth['database']['status']) }}</p>
                <small class="health-details">{{ $systemHealth['database']['size_mb'] }}MB</small>
            </div>
        </div>
        <div class="col-md-2">
            <div class="health-card {{ $systemHealth['cache']['status'] }}">
                <div class="health-icon">
                    <i class="fas fa-memory"></i>
                </div>
                <h6>Cache</h6>
                <p class="health-status">{{ ucfirst($systemHealth['cache']['status']) }}</p>
                <small class="health-details">{{ $systemHealth['cache']['driver'] }}</small>
            </div>
        </div>
        <div class="col-md-2">
            <div class="health-card {{ $systemHealth['storage']['status'] }}">
                <div class="health-icon">
                    <i class="fas fa-hdd"></i>
                </div>
                <h6>Storage</h6>
                <p class="health-status">{{ ucfirst($systemHealth['storage']['status']) }}</p>
                <small class="health-details">{{ $systemHealth['storage']['usage_percentage'] }}% used</small>
            </div>
        </div>
        <div class="col-md-2">
            <div class="health-card {{ $systemHealth['queue']['status'] }}">
                <div class="health-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <h6>Queue</h6>
                <p class="health-status">{{ ucfirst($systemHealth['queue']['status']) }}</p>
                <small class="health-details">{{ $systemHealth['queue']['pending_jobs'] }} pending</small>
            </div>
        </div>
        <div class="col-md-2">
            <div class="health-card {{ $systemHealth['api']['status'] }}">
                <div class="health-icon">
                    <i class="fas fa-plug"></i>
                </div>
                <h6>API</h6>
                <p class="health-status">{{ ucfirst($systemHealth['api']['status']) }}</p>
                <small class="health-details">{{ $systemHealth['api']['response_time'] }}</small>
            </div>
        </div>
        <div class="col-md-2">
            <div class="health-card healthy">
                <div class="health-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h6>Security</h6>
                <p class="health-status">Secure</p>
                <small class="health-details">No threats</small>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $performanceMetrics['response_time'] }}ms</h3>
                    <p>Response Time</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-memory"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $performanceMetrics['memory_usage']['percentage'] }}%</h3>
                    <p>Memory Usage</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="fas fa-microchip"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $performanceMetrics['cpu_usage']['usage'] }}</h3>
                    <p>CPU Usage</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                    <i class="fas fa-link"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $performanceMetrics['database_connections']['current'] }}</h3>
                    <p>DB Connections</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts and Activities -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        System Alerts
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($alerts as $alert)
                    <div class="alert alert-{{ $alert['severity'] === 'high' ? 'danger' : 'warning' }} alert-dismissible fade show">
                        <h6 class="alert-heading">{{ $alert['title'] }}</h6>
                        <p class="mb-2">{{ $alert['message'] }}</p>
                        <small class="text-muted">{{ $alert['timestamp']->diffForHumans() }}</small>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="text-muted">No Alerts</h5>
                        <p class="text-muted">All systems are running smoothly!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-history"></i>
                        Recent Activities
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        @forelse($recentActivities as $activity)
                        <div class="activity-item">
                            <div class="activity-icon bg-{{ $activity['type'] === 'error' ? 'danger' : ($activity['type'] === 'success' ? 'success' : 'info') }}">
                                <i class="fas fa-{{ $activity['type'] === 'error' ? 'exclamation-triangle' : ($activity['type'] === 'success' ? 'check-circle' : 'info-circle') }}"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="activity-title">{{ $activity['title'] }}</h6>
                                <p class="activity-message">{{ $activity['message'] }}</p>
                                <small class="activity-time">{{ $activity['timestamp']->diffForHumans() }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Recent Activities</h5>
                            <p class="text-muted">System is quiet</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Metrics -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-database"></i>
                        Database Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Size:</strong> {{ $systemHealth['database']['size_mb'] }}MB</p>
                            <p><strong>Connections:</strong> {{ $systemHealth['database']['connections'] }}</p>
                            <p><strong>Uptime:</strong> {{ $systemHealth['database']['uptime_formatted'] }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $systemHealth['database']['status'] === 'healthy' ? 'success' : ($systemHealth['database']['status'] === 'warning' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($systemHealth['database']['status']) }}
                                </span>
                            </p>
                            <p><strong>Message:</strong> {{ $systemHealth['database']['message'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-hdd"></i>
                        Storage Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Total Space:</strong> {{ $systemHealth['storage']['total_space'] }}</p>
                            <p><strong>Used Space:</strong> {{ $systemHealth['storage']['used_space'] }}</p>
                            <p><strong>Free Space:</strong> {{ $systemHealth['storage']['free_space'] }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Usage:</strong> {{ $systemHealth['storage']['usage_percentage'] }}%</p>
                            <div class="progress mt-2">
                                <div class="progress-bar bg-{{ $systemHealth['storage']['usage_percentage'] > 90 ? 'danger' : ($systemHealth['storage']['usage_percentage'] > 80 ? 'warning' : 'success') }}" 
                                     style="width: {{ $systemHealth['storage']['usage_percentage'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-tools"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('super.system.logs') }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-file-alt"></i>
                                View Logs
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('super.system.database') }}" class="btn btn-outline-info w-100 mb-2">
                                <i class="fas fa-database"></i>
                                Database Info
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('super.system.queue') }}" class="btn btn-outline-warning w-100 mb-2">
                                <i class="fas fa-tasks"></i>
                                Queue Status
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('super.system.cache') }}" class="btn btn-outline-success w-100 mb-2">
                                <i class="fas fa-memory"></i>
                                Cache Info
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.health-score-circle {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
}

.score-circle {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
}

.score-circle.healthy {
    background: linear-gradient(135deg, #10b981, #059669);
}

.score-circle.warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.score-circle.critical {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.score-number {
    font-size: 2.5rem;
    line-height: 1;
}

.score-label {
    font-size: 0.875rem;
    opacity: 0.9;
}

.health-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.health-card.healthy {
    border-left-color: #10b981;
}

.health-card.warning {
    border-left-color: #f59e0b;
}

.health-card.critical {
    border-left-color: #ef4444;
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

.health-card.healthy .health-icon {
    background: linear-gradient(135deg, #10b981, #059669);
}

.health-card.warning .health-icon {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.health-card.critical .health-icon {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.health-status {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.health-details {
    color: #6b7280;
    font-size: 0.875rem;
}

.activity-list {
    max-height: 400px;
    overflow-y: auto;
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
    font-size: 0.875rem;
}

.activity-message {
    margin: 0 0 0.25rem 0;
    color: #6b7280;
    font-size: 0.8rem;
}

.activity-time {
    color: #9ca3af;
    font-size: 0.75rem;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 1.75rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    color: white;
    font-size: 1.5rem;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #1f2937;
}

.stat-content p {
    color: #6b7280;
    margin-bottom: 0;
    font-weight: 500;
}
</style>

<script>
function refreshSystemStatus() {
    showLoading('Refreshing system status...');
    
    fetch('{{ route("super.system.status") }}')
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification('System status refreshed successfully', 'success');
            location.reload();
        } else {
            showNotification('Failed to refresh system status', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showNotification('Error refreshing system status: ' + error.message, 'error');
    });
}

function clearSystemCache() {
    if (!confirm('Are you sure you want to clear all system cache? This may temporarily slow down the system.')) {
        return;
    }
    
    showLoading('Clearing system cache...');
    
    fetch('{{ route("super.system.clear-cache") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification('System cache cleared successfully', 'success');
            location.reload();
        } else {
            showNotification('Failed to clear system cache', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showNotification('Error clearing system cache: ' + error.message, 'error');
    });
}

function showLoading(message) {
    const loadingDiv = document.createElement('div');
    loadingDiv.id = 'loading-overlay';
    loadingDiv.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center';
    loadingDiv.style.cssText = 'background-color: rgba(0,0,0,0.5); z-index: 9999;';
    loadingDiv.innerHTML = `
        <div class="text-center text-white">
            <div class="spinner-border mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>${message}</p>
        </div>
    `;
    document.body.appendChild(loadingDiv);
}

function hideLoading() {
    const loadingDiv = document.getElementById('loading-overlay');
    if (loadingDiv) {
        loadingDiv.remove();
    }
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

// Auto-refresh every 30 seconds
setInterval(refreshSystemStatus, 30000);
</script>
@endsection