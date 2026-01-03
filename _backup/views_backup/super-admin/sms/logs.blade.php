<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SMS Logs - Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #667eea; }
        body { background: #f8fafc; font-family: 'Inter', sans-serif; }
        .sidebar { position: fixed; width: 280px; height: 100vh; background: linear-gradient(180deg, #667eea, #764ba2); color: white; overflow-y: auto; }
        .sidebar-header { padding: 2rem 1.5rem; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-menu { padding: 1.5rem 0; list-style: none; margin: 0; }
        .sidebar-menu a { display: flex; align-items: center; padding: 0.875rem 1.5rem; color: rgba(255,255,255,0.9); text-decoration: none; }
        .sidebar-menu a.active { background: rgba(255,255,255,0.1); color: white; }
        .sidebar-menu i { width: 24px; margin-right: 12px; }
        .main-content { margin-left: 280px; padding: 2rem; }
        .content-card { background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .sms-submenu { padding-left: 2.5rem !important; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h3><i class="fas fa-crown"></i> SUPER ADMIN</h3></div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('super.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="{{ route('super.sms.index') }}"><i class="fas fa-sms"></i> SMS System</a></li>
            <li><a href="{{ route('super.sms.templates') }}" class="sms-submenu"><i class="fas fa-file-alt"></i> Templates</a></li>
            <li><a href="{{ route('super.sms.campaigns') }}" class="sms-submenu"><i class="fas fa-bullhorn"></i> Campaigns</a></li>
            <li><a href="{{ route('super.sms.send-single') }}" class="sms-submenu"><i class="fas fa-paper-plane"></i> Send SMS</a></li>
            <li><a href="{{ route('super.sms.logs') }}" class="sms-submenu active"><i class="fas fa-list"></i> SMS Logs</a></li>
            <li><a href="{{ route('super.sms.credits') }}" class="sms-submenu"><i class="fas fa-coins"></i> Credits</a></li>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 1.5rem;">
            <li><a href="{{ route('super.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <form id="logout-form" action="{{ route('super.logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>

    <div class="main-content">
        <div class="content-card">
            <h2><i class="fas fa-list"></i> SMS Logs</h2>
            <p class="text-muted">View all SMS message history</p>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Phone Number</th>
                            <th>Message</th>
                            <th>Type</th>
                            <th>Campaign</th>
                            <th>Status</th>
                            <th>Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $msg)
                        <tr>
                            <td>{{ $msg->created_at->format('M d, Y H:i') }}</td>
                            <td>{{ $msg->phone_number }}</td>
                            <td>{{ Str::limit($msg->message, 50) }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($msg->type) }}</span></td>
                            <td>{{ $msg->campaign->name ?? 'Single SMS' }}</td>
                            <td>
                                @if($msg->status === 'sent' || $msg->status === 'delivered')
                                <span class="badge bg-success">{{ ucfirst($msg->status) }}</span>
                                @else
                                <span class="badge bg-danger">{{ ucfirst($msg->status) }}</span>
                                @endif
                            </td>
                            <td>Rs. {{ number_format($msg->cost, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4">No SMS messages yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $messages->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
