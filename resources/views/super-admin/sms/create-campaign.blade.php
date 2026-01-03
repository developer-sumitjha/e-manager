<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Campaign - Super Admin</title>
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
        .form-card { background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); max-width: 900px; margin: 0 auto; }
        .btn-create { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 0.75rem 2rem; border-radius: 12px; border: none; width: 100%; }
        .sms-submenu { padding-left: 2.5rem !important; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h3><i class="fas fa-crown"></i> SUPER ADMIN</h3></div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('super.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="{{ route('super.sms.index') }}"><i class="fas fa-sms"></i> SMS System</a></li>
            <li><a href="{{ route('super.sms.campaigns') }}" class="sms-submenu active"><i class="fas fa-bullhorn"></i> Campaigns</a></li>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 1.5rem;">
            <li><a href="{{ route('super.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <form id="logout-form" action="{{ route('super.logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>

    <div class="main-content">
        <div class="form-card">
            <h2><i class="fas fa-plus"></i> Create SMS Campaign</h2>
            <p class="text-muted">Send bulk SMS to your vendors</p>

            <form action="{{ route('super.sms.store-campaign') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Campaign Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Recipients</label>
                        <select name="recipient_type" id="recipientType" class="form-select" required>
                            <option value="all">All Admins</option>
                            <option value="active">Active Admins</option>
                            <option value="trial">Trial Admins</option>
                            <option value="pending">Pending Admins</option>
                            <option value="custom">Custom Phone Numbers</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3" id="customPhones" style="display: none;">
                    <label class="form-label">Phone Numbers (comma separated)</label>
                    <textarea name="custom_phones" class="form-control" rows="3" placeholder="+977-9800000000, +977-9800000001"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Message (160 characters max)</label>
                    <textarea name="message" class="form-control" rows="4" maxlength="160" required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Schedule</label>
                        <select name="schedule" id="scheduleType" class="form-select" required>
                            <option value="now">Send Now</option>
                            <option value="later">Schedule for Later</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" id="scheduleTime" style="display: none;">
                        <label class="form-label">Scheduled Time</label>
                        <input type="datetime-local" name="scheduled_at" class="form-control">
                    </div>
                </div>

                <button type="submit" class="btn-create mt-3">
                    <i class="fas fa-paper-plane"></i> Create Campaign
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('recipientType').addEventListener('change', function() {
            document.getElementById('customPhones').style.display = this.value === 'custom' ? 'block' : 'none';
        });

        document.getElementById('scheduleType').addEventListener('change', function() {
            document.getElementById('scheduleTime').style.display = this.value === 'later' ? 'block' : 'none';
        });
    </script>
</body>
</html>
