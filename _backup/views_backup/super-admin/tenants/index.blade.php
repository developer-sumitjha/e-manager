<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Management - Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        body {
            background: #f8fafc;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, var(--primary), var(--secondary));
            color: white;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 800;
        }

        .sidebar-menu {
            padding: 1.5rem 0;
            list-style: none;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0.5rem 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            border-left-color: white;
            color: white;
        }

        .sidebar-menu i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            padding: 2rem;
        }

        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 0.5rem 0;
        }

        .page-header p {
            color: #64748b;
            margin: 0;
        }

        .action-bar {
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-box {
            flex: 1;
            min-width: 300px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 4px rgba(102,126,234,0.1);
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .filter-select {
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.3s ease;
            min-width: 150px;
        }

        .filter-select:focus {
            border-color: var(--primary);
            outline: none;
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102,126,234,0.3);
        }

        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102,126,234,0.4);
            color: white;
        }

        .btn-success {
            background: var(--success);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-warning {
            background: var(--warning);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
        }

        .table-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .modern-table {
            width: 100%;
            margin: 0;
        }

        .modern-table thead {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        }

        .modern-table thead th {
            padding: 1.25rem 1.5rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border: none;
        }

        .modern-table tbody td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        .modern-table tbody tr {
            transition: all 0.3s ease;
        }

        .modern-table tbody tr:hover {
            background: #f8fafc;
        }

        .tenant-avatar {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-status.active {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-status.trial {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-status.suspended {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-status.pending {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-btn {
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            border: none;
            background: transparent;
            color: #64748b;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-btn:hover {
            background: #f1f5f9;
            color: var(--primary);
        }

        .checkbox-custom {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .bulk-actions {
            display: none;
            background: #fef3c7;
            padding: 1rem 2rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            align-items: center;
            gap: 1rem;
        }

        .bulk-actions.show {
            display: flex;
        }

        .pagination-custom {
            display: flex;
            justify-content: center;
            padding: 2rem 0;
            gap: 0.5rem;
        }

        .pagination-custom .page-link {
            padding: 0.75rem 1.25rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            color: #64748b;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .pagination-custom .page-link:hover,
        .pagination-custom .page-link.active {
            border-color: var(--primary);
            background: var(--primary);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-crown"></i> SUPER ADMIN</h3>
            <p style="margin: 0.5rem 0 0; font-size: 0.9rem; opacity: 0.9;">E-Manager Platform</p>
        </div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('super.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="{{ route('super.tenants.index') }}" class="active"><i class="fas fa-store"></i> Admins</a></li>
            <li><a href="{{ route('super.plans.index') }}"><i class="fas fa-layer-group"></i> Plans</a></li>
            <li><a href="{{ route('super.financial.index') }}"><i class="fas fa-chart-pie"></i> Financial</a></li>
            <li><a href="{{ route('super.system.monitor') }}"><i class="fas fa-heartbeat"></i> System Monitor</a></li>
            <li><a href="{{ route('super.communication.index') }}"><i class="fas fa-bullhorn"></i> Communication</a></li>
            <li><a href="{{ route('super.analytics') }}"><i class="fas fa-chart-line"></i> Analytics</a></li>
            <li><a href="{{ route('super.security.audit-logs') }}"><i class="fas fa-shield-alt"></i> Security</a></li>
            <li><a href="{{ route('super.settings.general') }}"><i class="fas fa-cog"></i> Settings</a></li>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 1rem 1.5rem;">
            <li><a href="{{ route('super.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
        <form id="logout-form" action="{{ route('super.logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-store"></i> Admin Management</h1>
            <p>Manage all vendors, their subscriptions, and monitor their performance</p>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Action Bar -->
        <div class="action-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search vendors..." value="{{ request('search') }}">
            </div>
            
            <select class="filter-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="trial" {{ request('status') == 'trial' ? 'selected' : '' }}>Trial</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>

            <select class="filter-select" id="planFilter">
                <option value="">All Plans</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ request('plan') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                @endforeach
            </select>

            <a href="{{ route('super.tenants.export') }}?{{ http_build_query(request()->all()) }}" class="btn-success">
                <i class="fas fa-download"></i> Export CSV
            </a>

            <a href="{{ route('super.tenants.create') }}" class="btn-primary-gradient">
                <i class="fas fa-plus"></i> Add Admin
            </a>
        </div>

        <!-- Bulk Actions Bar -->
        <div class="bulk-actions" id="bulkActionsBar">
            <strong id="selectedCount">0 selected</strong>
            <select class="filter-select" id="bulkAction">
                <option value="">Choose Action</option>
                <option value="approve">Approve</option>
                <option value="suspend">Suspend</option>
                <option value="activate">Activate</option>
                <option value="delete">Delete</option>
            </select>
            <button class="btn-primary-gradient" onclick="executeBulkAction()">
                <i class="fas fa-check"></i> Apply
            </button>
            <button class="btn-warning" onclick="clearSelection()">
                <i class="fas fa-times"></i> Cancel
            </button>
        </div>

        <!-- Table -->
        <div class="table-card">
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">
                                <input type="checkbox" class="checkbox-custom" id="selectAll">
                            </th>
                            <th>Admin</th>
                            <th>Contact</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenants as $tenant)
                        <tr>
                            <td>
                                <input type="checkbox" class="checkbox-custom tenant-checkbox" value="{{ $tenant->id }}">
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="tenant-avatar">{{ strtoupper(substr($tenant->business_name, 0, 2)) }}</div>
                                    <div>
                                        <strong style="display: block;">{{ $tenant->business_name }}</strong>
                                        <small style="color: #64748b;">{{ $tenant->tenant_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong style="display: block;">{{ $tenant->business_email }}</strong>
                                    <small style="color: #64748b;">{{ $tenant->business_phone }}</small>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $tenant->currentPlan->name ?? 'No Plan' }}</strong>
                            </td>
                            <td>
                                <span class="badge-status {{ $tenant->status }}">{{ ucfirst($tenant->status) }}</span>
                            </td>
                            <td>{{ $tenant->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('super.tenants.show', $tenant) }}" class="action-btn" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('super.tenants.edit', $tenant) }}" class="action-btn" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('super.tenants.analytics', $tenant) }}" class="action-btn" title="Analytics">
                                        <i class="fas fa-chart-line"></i>
                                    </a>
                                    <button onclick="checkHealth({{ $tenant->id }})" class="action-btn" title="Health">
                                        <i class="fas fa-heartbeat"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-inbox" style="font-size: 3rem; color: #cbd5e1;"></i>
                                <p style="margin-top: 1rem; color: #64748b;">No vendors found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($tenants->hasPages())
            <div class="pagination-custom">
                {{ $tenants->links() }}
            </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            if(e.key === 'Enter') {
                applyFilters();
            }
        });

        // Filter functionality
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('planFilter').addEventListener('change', applyFilters);

        function applyFilters() {
            const search = document.getElementById('searchInput').value;
            const status = document.getElementById('statusFilter').value;
            const plan = document.getElementById('planFilter').value;
            
            let url = '{{ route("super.tenants.index") }}?';
            if(search) url += 'search=' + encodeURIComponent(search) + '&';
            if(status) url += 'status=' + status + '&';
            if(plan) url += 'plan=' + plan;
            
            window.location.href = url;
        }

        // Bulk selection
        document.getElementById('selectAll').addEventListener('change', function() {
            document.querySelectorAll('.tenant-checkbox').forEach(cb => {
                cb.checked = this.checked;
            });
            updateBulkActions();
        });

        document.querySelectorAll('.tenant-checkbox').forEach(cb => {
            cb.addEventListener('change', updateBulkActions);
        });

        function updateBulkActions() {
            const checked = document.querySelectorAll('.tenant-checkbox:checked');
            const bar = document.getElementById('bulkActionsBar');
            const count = document.getElementById('selectedCount');
            
            if(checked.length > 0) {
                bar.classList.add('show');
                count.textContent = checked.length + ' selected';
            } else {
                bar.classList.remove('show');
            }
        }

        function clearSelection() {
            document.querySelectorAll('.tenant-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('selectAll').checked = false;
            updateBulkActions();
        }

        function executeBulkAction() {
            const action = document.getElementById('bulkAction').value;
            if(!action) {
                alert('Please select an action');
                return;
            }

            const checked = Array.from(document.querySelectorAll('.tenant-checkbox:checked')).map(cb => cb.value);
            if(checked.length === 0) {
                alert('Please select at least one vendor');
                return;
            }

            if(action === 'delete' && !confirm('Are you sure you want to delete ' + checked.length + ' vendor(s)?')) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("super.tenants.bulk-action") }}';
            form.innerHTML = `
                @csrf
                <input type="hidden" name="action" value="${action}">
                ${checked.map(id => `<input type="hidden" name="tenant_ids[]" value="${id}">`).join('')}
            `;
            document.body.appendChild(form);
            form.submit();
        }

        function checkHealth(tenantId) {
            fetch(`/super/tenants/${tenantId}/health`)
                .then(res => res.json())
                .then(data => {
                    alert(`Health Score: ${data.overall_score}/100\nStatus: ${data.status.toUpperCase()}`);
                })
                .catch(err => alert('Failed to check health'));
        }
    </script>
</body>
</html>
