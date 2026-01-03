<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SMS System - Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --primary: #667eea; --secondary: #764ba2; }
        body { background: #f8fafc; font-family: 'Inter', sans-serif; }
        .sidebar { position: fixed; width: 280px; height: 100vh; background: linear-gradient(180deg, var(--primary), var(--secondary)); color: white; overflow-y: auto; }
        .sidebar-header { padding: 2rem 1.5rem; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-menu { padding: 1.5rem 0; list-style: none; margin: 0; }
        .sidebar-menu a { display: flex; align-items: center; padding: 0.875rem 1.5rem; color: rgba(255,255,255,0.9); text-decoration: none; transition: all 0.3s; border-left: 4px solid transparent; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: rgba(255,255,255,0.1); border-left-color: white; color: white; }
        .sidebar-menu i { width: 24px; margin-right: 12px; }
        .main-content { margin-left: 280px; padding: 2rem; }
        .page-header { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 2rem; border-radius: 20px; margin-bottom: 2rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin: 2rem 0; }
        .stat-card { background: white; padding: 1.75rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); text-align: center; }
        .stat-value { font-size: 2rem; font-weight: 800; color: var(--primary); }
        .stat-label { color: #64748b; margin-top: 0.5rem; font-size: 0.9rem; }
        .chart-card { background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); margin-bottom: 2rem; }
        .action-btn { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 0.75rem 1.5rem; border-radius: 12px; text-decoration: none; display: inline-block; transition: all 0.3s; border: none; }
        .action-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102,126,234,0.4); color: white; }
        .sms-submenu { padding-left: 2.5rem !important; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h3><i class="fas fa-crown"></i> SUPER ADMIN</h3></div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('super.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="{{ route('super.tenants.index') }}"><i class="fas fa-store"></i> Admins</a></li>
            <li><a href="{{ route('super.plans.index') }}"><i class="fas fa-layer-group"></i> Plans</a></li>
            <li><a href="{{ route('super.financial.index') }}"><i class="fas fa-chart-pie"></i> Financial</a></li>
            <li><a href="{{ route('super.system.monitor') }}"><i class="fas fa-heartbeat"></i> System Monitor</a></li>
            <li><a href="{{ route('super.communication.index') }}"><i class="fas fa-bullhorn"></i> Communication</a></li>
            <li><a href="{{ route('super.sms.index') }}" class="active"><i class="fas fa-sms"></i> SMS System</a></li>
            <li><a href="{{ route('super.sms.templates') }}" class="sms-submenu"><i class="fas fa-file-alt"></i> Templates</a></li>
            <li><a href="{{ route('super.sms.campaigns') }}" class="sms-submenu"><i class="fas fa-bullhorn"></i> Campaigns</a></li>
            <li><a href="{{ route('super.sms.send-single') }}" class="sms-submenu"><i class="fas fa-paper-plane"></i> Send SMS</a></li>
            <li><a href="{{ route('super.sms.logs') }}" class="sms-submenu"><i class="fas fa-list"></i> SMS Logs</a></li>
            <li><a href="{{ route('super.sms.credits') }}" class="sms-submenu"><i class="fas fa-coins"></i> Credits</a></li>
            <li><a href="{{ route('super.analytics') }}"><i class="fas fa-chart-line"></i> Analytics</a></li>
            <li><a href="{{ route('super.security.audit-logs') }}"><i class="fas fa-shield-alt"></i> Security</a></li>
            <li><a href="{{ route('super.settings.general') }}"><i class="fas fa-cog"></i> Settings</a></li>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 1.5rem;">
            <li><a href="{{ route('super.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <form id="logout-form" action="{{ route('super.logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>

    <div class="main-content">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-sms"></i> SMS Marketing System</h1>
                    <p class="mb-0">Send marketing messages, notifications and notices via SMS</p>
                </div>
                <div>
                    <a href="{{ route('super.sms.send-single') }}" class="action-btn">
                        <i class="fas fa-paper-plane"></i> Send SMS
                    </a>
                    <a href="{{ route('super.sms.create-campaign') }}" class="action-btn">
                        <i class="fas fa-plus"></i> New Campaign
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stats['total_sent']) }}</div>
                <div class="stat-label"><i class="fas fa-paper-plane"></i> Total Sent</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stats['total_delivered']) }}</div>
                <div class="stat-label"><i class="fas fa-check"></i> Delivered</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stats['total_failed']) }}</div>
                <div class="stat-label"><i class="fas fa-times"></i> Failed</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stats['total_campaigns']) }}</div>
                <div class="stat-label"><i class="fas fa-bullhorn"></i> Campaigns</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stats['credits_balance']) }}</div>
                <div class="stat-label"><i class="fas fa-coins"></i> SMS Credits</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="chart-card">
                    <h4><i class="fas fa-chart-line"></i> SMS Delivery Trends</h4>
                    <canvas id="smsChart" height="100"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="chart-card">
                    <h4><i class="fas fa-chart-pie"></i> SMS Status</h4>
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="chart-card">
            <h4><i class="fas fa-history"></i> Recent SMS Messages</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Campaign</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentMessages as $msg)
                        <tr>
                            <td>{{ $msg->created_at->format('M d, H:i') }}</td>
                            <td>{{ $msg->phone_number }}</td>
                            <td>{{ Str::limit($msg->message, 40) }}</td>
                            <td>{{ $msg->campaign->name ?? 'Single SMS' }}</td>
                            <td>
                                @if($msg->status === 'sent' || $msg->status === 'delivered')
                                <span class="badge bg-success">{{ ucfirst($msg->status) }}</span>
                                @else
                                <span class="badge bg-danger">{{ ucfirst($msg->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">No SMS messages yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // SMS Trend Chart
        new Chart(document.getElementById('smsChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($monthlyStats, 'month')) !!},
                datasets: [{
                    label: 'SMS Sent',
                    data: {!! json_encode(array_column($monthlyStats, 'sent')) !!},
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102,126,234,0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: { responsive: true, maintainAspectRatio: true }
        });

        // Status Pie Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Delivered', 'Failed', 'Pending'],
                datasets: [{
                    data: [{{ $stats['total_delivered'] }}, {{ $stats['total_failed'] }}, {{ $stats['total_sent'] - $stats['total_delivered'] - $stats['total_failed'] }}],
                    backgroundColor: ['#10b981', '#ef4444', '#f59e0b']
                }]
            },
            options: { responsive: true, maintainAspectRatio: true }
        });
    </script>
</body>
</html>
