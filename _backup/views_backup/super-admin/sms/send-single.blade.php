<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send SMS - Super Admin</title>
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
        .form-card { background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); max-width: 800px; margin: 2rem auto; }
        .sms-preview { background: #f8fafc; padding: 1.5rem; border-radius: 12px; border: 2px dashed #cbd5e1; margin-top: 1rem; }
        .char-count { float: right; color: #64748b; font-size: 0.9rem; }
        .btn-send { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 0.75rem 2rem; border-radius: 12px; border: none; width: 100%; }
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
            <li><a href="{{ route('super.sms.send-single') }}" class="sms-submenu active"><i class="fas fa-paper-plane"></i> Send SMS</a></li>
            <li><a href="{{ route('super.sms.logs') }}" class="sms-submenu"><i class="fas fa-list"></i> SMS Logs</a></li>
            <li><a href="{{ route('super.sms.credits') }}" class="sms-submenu"><i class="fas fa-coins"></i> Credits</a></li>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 1.5rem;">
            <li><a href="{{ route('super.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <form id="logout-form" action="{{ route('super.logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>

    <div class="main-content">
        <div class="form-card">
            <h2><i class="fas fa-paper-plane"></i> Send Single SMS</h2>
            <p class="text-muted">Send SMS to individual phone number</p>

            @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger"><i class="fas fa-times"></i> {{ session('error') }}</div>
            @endif

            <form action="{{ route('super.sms.send-single-sms') }}" method="POST" id="smsForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" placeholder="+977-9800000000" required>
                    <small class="text-muted">Include country code (e.g., +977 for Nepal)</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Use Template (Optional)</label>
                    <select class="form-select" id="templateSelect">
                        <option value="">-- Custom Message --</option>
                        @foreach($templates as $template)
                        <option value="{{ $template->id }}" data-content="{{ $template->content }}">{{ $template->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Message <span class="char-count"><span id="charCount">0</span>/160</span></label>
                    <textarea name="message" id="messageText" class="form-control" rows="5" maxlength="160" required></textarea>
                </div>

                <div class="sms-preview">
                    <strong>Preview:</strong>
                    <p id="previewText" class="mb-0 mt-2">Your message will appear here...</p>
                </div>

                <button type="submit" class="btn-send mt-3">
                    <i class="fas fa-paper-plane"></i> Send SMS
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const messageText = document.getElementById('messageText');
        const charCount = document.getElementById('charCount');
        const previewText = document.getElementById('previewText');
        const templateSelect = document.getElementById('templateSelect');

        messageText.addEventListener('input', function() {
            charCount.textContent = this.value.length;
            previewText.textContent = this.value || 'Your message will appear here...';
        });

        templateSelect.addEventListener('change', function() {
            if (this.value) {
                const content = this.options[this.selectedIndex].dataset.content;
                messageText.value = content;
                messageText.dispatchEvent(new Event('input'));
            }
        });
    </script>
</body>
</html>
