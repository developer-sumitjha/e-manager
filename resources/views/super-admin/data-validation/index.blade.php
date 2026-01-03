@extends('super-admin.layout')

@section('title', 'Data Validation')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="dashboard-title">
                    <i class="fas fa-shield-alt"></i>
                    Data Validation & Integrity
                </h1>
                <p class="dashboard-subtitle">Monitor and maintain data quality across the platform</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-primary btn-lg me-2" onclick="runAllValidations()">
                    <i class="fas fa-play"></i>
                    Run All Validations
                </button>
                <button class="btn btn-success btn-lg" onclick="fixAllAutoFixable()">
                    <i class="fas fa-wrench"></i>
                    Auto-Fix Issues
                </button>
            </div>
        </div>
    </div>

    <!-- Data Integrity Score -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-shield-check"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $dataIntegrityScore }}%</h3>
                    <p>Data Integrity Score</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ collect($validationResults)->sum('total_issues') }}</h3>
                    <p>Total Issues Found</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="fas fa-tools"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ collect($validationResults)->pluck('issues')->flatten(1)->where('auto_fixable', true)->count() }}</h3>
                    <p>Auto-Fixable Issues</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ collect($validationResults)->where('status', 'healthy')->count() }}</h3>
                    <p>Healthy Categories</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    @if(count($recommendations) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-lightbulb"></i>
                        Recommendations
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($recommendations as $recommendation)
                    <div class="alert alert-{{ $recommendation['priority'] === 'high' ? 'danger' : 'warning' }} alert-dismissible fade show">
                        <h6 class="alert-heading">{{ $recommendation['title'] }}</h6>
                        <p class="mb-2">{{ $recommendation['description'] }}</p>
                        <small class="text-muted">{{ $recommendation['action'] }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Validation Categories -->
    <div class="row">
        @foreach($validationResults as $category => $result)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-{{ $this->getCategoryIcon($category) }}"></i>
                        {{ ucfirst(str_replace('_', ' ', $category)) }}
                    </h6>
                    <span class="badge bg-{{ $result['status'] === 'healthy' ? 'success' : 'danger' }}">
                        {{ $result['total_issues'] }} issues
                    </span>
                </div>
                <div class="card-body">
                    @if($result['total_issues'] === 0)
                        <div class="text-center py-3">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <p class="text-muted mb-0">No issues found</p>
                        </div>
                    @else
                        <div class="issues-list">
                            @foreach($result['issues'] as $issue)
                            <div class="issue-item" data-issue-id="{{ $issue['id'] }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="issue-title">
                                            {{ $issue['title'] }}
                                            <span class="badge badge-{{ $issue['severity'] }} ms-2">
                                                {{ ucfirst($issue['severity']) }}
                                            </span>
                                        </h6>
                                        <p class="issue-description">{{ $issue['description'] }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-{{ $this->getIssueIcon($issue['type']) }}"></i>
                                            {{ ucfirst(str_replace('_', ' ', $issue['type'])) }}
                                        </small>
                                    </div>
                                    <div class="issue-actions">
                                        @if($issue['auto_fixable'])
                                            <button class="btn btn-sm btn-outline-success" onclick="fixIssue('{{ $issue['id'] }}')">
                                                <i class="fas fa-wrench"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-sm btn-outline-info" onclick="viewIssueDetails('{{ $issue['id'] }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <button class="btn btn-sm btn-primary w-100" onclick="runValidation('{{ $category }}')">
                        <i class="fas fa-refresh"></i>
                        Re-validate {{ ucfirst(str_replace('_', ' ', $category)) }}
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Issue Details Modal -->
<div class="modal fade" id="issueDetailsModal" tabindex="-1" aria-labelledby="issueDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="issueDetailsModalLabel">
                    <i class="fas fa-info-circle"></i>
                    Issue Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="issueDetailsContent">
                <!-- Issue details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="fixIssueBtn" onclick="fixCurrentIssue()">
                    <i class="fas fa-wrench"></i>
                    Fix Issue
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.issue-item {
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    margin-bottom: 0.75rem;
    background-color: #f9fafb;
}

.issue-item:last-child {
    margin-bottom: 0;
}

.issue-title {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.issue-description {
    font-size: 0.8rem;
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.badge-danger {
    background-color: #ef4444;
}

.badge-warning {
    background-color: #f59e0b;
}

.badge-info {
    background-color: #3b82f6;
}

.badge-success {
    background-color: #10b981;
}

.issue-actions {
    display: flex;
    gap: 0.25rem;
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
let currentIssueId = null;

function runAllValidations() {
    showLoading('Running all validations...');
    
    fetch('{{ route("super.data-validation.run") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ type: 'all' })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification('Validations completed successfully', 'success');
            location.reload();
        } else {
            showNotification('Validation failed: ' + data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showNotification('Validation failed: ' + error.message, 'error');
    });
}

function runValidation(category) {
    showLoading(`Running ${category} validation...`);
    
    fetch('{{ route("super.data-validation.run") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ type: category })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification(`${category} validation completed`, 'success');
            location.reload();
        } else {
            showNotification('Validation failed: ' + data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showNotification('Validation failed: ' + error.message, 'error');
    });
}

function fixIssue(issueId) {
    if (!confirm('Are you sure you want to fix this issue?')) {
        return;
    }
    
    showLoading('Fixing issue...');
    
    fetch('{{ route("super.data-validation.fix") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ 
            issue_ids: [issueId],
            fix_type: 'auto'
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification('Issue fixed successfully', 'success');
            location.reload();
        } else {
            showNotification('Fix failed: ' + data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        showNotification('Fix failed: ' + error.message, 'error');
    });
}

function fixAllAutoFixable() {
    const autoFixableIssues = document.querySelectorAll('[data-issue-id]');
    const issueIds = Array.from(autoFixableIssues).map(item => item.dataset.issueId);
    
    if (issueIds.length === 0) {
        showNotification('No auto-fixable issues found', 'info');
        return;
    }
    
    if (!confirm(`Are you sure you want to fix ${issueIds.length} issues?`)) {
        return;
    }
    
    showLoading('Fixing all auto-fixable issues...');
    
    fetch('{{ route("super.data-validation.fix") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ 
            issue_ids: issueIds,
            fix_type: 'auto'
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification('All issues fixed successfully', 'success');
            location.reload();
        } else {
            showNotification('Some fixes failed: ' + data.message, 'warning');
        }
    })
    .catch(error => {
        hideLoading();
        showNotification('Fix failed: ' + error.message, 'error');
    });
}

function viewIssueDetails(issueId) {
    currentIssueId = issueId;
    
    // Find the issue in the validation results
    const issue = findIssueById(issueId);
    
    if (!issue) {
        showNotification('Issue not found', 'error');
        return;
    }
    
    const modalContent = document.getElementById('issueDetailsContent');
    modalContent.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6>Issue Information</h6>
                <table class="table table-sm">
                    <tr>
                        <td><strong>Title:</strong></td>
                        <td>${issue.title}</td>
                    </tr>
                    <tr>
                        <td><strong>Type:</strong></td>
                        <td>${issue.type}</td>
                    </tr>
                    <tr>
                        <td><strong>Severity:</strong></td>
                        <td><span class="badge badge-${issue.severity}">${issue.severity}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Auto-fixable:</strong></td>
                        <td>${issue.auto_fixable ? 'Yes' : 'No'}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Description</h6>
                <p>${issue.description}</p>
                <h6>Suggested Fix</h6>
                <p>${issue.suggested_fix}</p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <h6>Data</h6>
                <pre class="bg-light p-3 rounded"><code>${JSON.stringify(issue.data, null, 2)}</code></pre>
            </div>
        </div>
    `;
    
    const fixBtn = document.getElementById('fixIssueBtn');
    fixBtn.style.display = issue.auto_fixable ? 'inline-block' : 'none';
    
    new bootstrap.Modal(document.getElementById('issueDetailsModal')).show();
}

function fixCurrentIssue() {
    if (currentIssueId) {
        fixIssue(currentIssueId);
    }
}

function findIssueById(issueId) {
    // This would need to be implemented to find the specific issue
    // For now, return a placeholder
    return {
        title: 'Sample Issue',
        type: 'missing_fields',
        severity: 'high',
        description: 'This is a sample issue description',
        suggested_fix: 'This is a suggested fix',
        auto_fixable: true,
        data: {}
    };
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

// Auto-refresh every 5 minutes
setInterval(() => {
    location.reload();
}, 300000);
</script>
@endsection


