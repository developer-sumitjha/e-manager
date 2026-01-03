<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SMS Templates - Super Admin</title>
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
        .template-card { background: #f8fafc; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem; border: 1px solid #e2e8f0; }
        .btn-create { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 0.75rem 1.5rem; border-radius: 12px; border: none; }
        .sms-submenu { padding-left: 2.5rem !important; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><h3><i class="fas fa-crown"></i> SUPER ADMIN</h3></div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('super.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="{{ route('super.sms.index') }}"><i class="fas fa-sms"></i> SMS System</a></li>
            <li><a href="{{ route('super.sms.templates') }}" class="sms-submenu active"><i class="fas fa-file-alt"></i> Templates</a></li>
            <li><a href="{{ route('super.sms.campaigns') }}" class="sms-submenu"><i class="fas fa-bullhorn"></i> Campaigns</a></li>
            <li><a href="{{ route('super.sms.send-single') }}" class="sms-submenu"><i class="fas fa-paper-plane"></i> Send SMS</a></li>
            <li><a href="{{ route('super.sms.logs') }}" class="sms-submenu"><i class="fas fa-list"></i> SMS Logs</a></li>
            <li><a href="{{ route('super.sms.credits') }}" class="sms-submenu"><i class="fas fa-coins"></i> Credits</a></li>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 1.5rem;">
            <li><a href="{{ route('super.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <form id="logout-form" action="{{ route('super.logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>

    <div class="main-content">
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fas fa-file-alt"></i> SMS Templates</h2>
                    <p class="text-muted mb-0">Create reusable SMS templates</p>
                </div>
                <button type="button" class="btn-create" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus"></i> New Template
                </button>
            </div>

            @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check"></i> {{ session('success') }}</div>
            @endif

            @forelse($templates as $template)
            <div class="template-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>{{ $template->name }}</h5>
                        <span class="badge bg-primary">{{ ucfirst($template->type) }}</span>
                        <p class="mt-2 mb-0">{{ $template->content }}</p>
                    </div>
                    <div>
                        <form action="{{ route('super.sms.delete-template', $template) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center text-muted py-5">No templates created yet</p>
            @endforelse
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('super.sms.store-template') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5>Create SMS Template</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Template Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select" required>
                                <option value="marketing">Marketing</option>
                                <option value="notification">Notification</option>
                                <option value="transactional">Transactional</option>
                                <option value="otp">OTP</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea name="content" class="form-control" rows="4" maxlength="160" required></textarea>
                            <small class="text-muted">Use {name}, {order_id} etc. for variables</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-create">Create Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
