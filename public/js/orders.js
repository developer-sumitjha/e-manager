// Order Module JavaScript Functions
// This file contains all the JavaScript functions needed for the Order module

document.addEventListener('DOMContentLoaded', function() {
    initOrderModule();
});

function initOrderModule() {
    // Initialize bulk selection
    initBulkSelection();
    
    // Initialize search functionality
    initOrderSearch();
    
    // Initialize status change handlers
    initStatusChangeHandlers();
    
    // Initialize view toggle
    initViewToggle();
}

// Bulk Selection Functions
function initBulkSelection() {
    const selectAllHeader = document.getElementById('select-all-orders-header');
    const selectAll = document.getElementById('select-all-orders');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const bulkActionsContainer = document.getElementById('bulk-actions-container');
    const selectedCount = document.querySelector('.selected-count');

    if (selectAllHeader) {
        selectAllHeader.addEventListener('change', function() {
            orderCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            orderCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }

    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActions();
        });
    });

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (selectedCount) {
            selectedCount.textContent = `${count} selected`;
        }
        
        if (bulkActionsContainer) {
            bulkActionsContainer.style.display = count > 0 ? 'block' : 'none';
        }
        
        // Update select all checkbox state
        if (selectAllHeader) {
            selectAllHeader.checked = count === orderCheckboxes.length;
            selectAllHeader.indeterminate = count > 0 && count < orderCheckboxes.length;
        }
    }
}

// Search Functionality - Optimized with debouncing
function initOrderSearch() {
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        let searchTimeout;
        let lastSearchValue = '';
        
        searchInput.addEventListener('input', function() {
            const value = this.value.trim();
            
            // Avoid unnecessary searches
            if (value === lastSearchValue) return;
            
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                lastSearchValue = value;
                performOrderSearch(value);
            }, 300); // Reduced from 500ms to 300ms
        });
    }
}

function performOrderSearch(query) {
    // Get current URL parameters
    const url = new URL(window.location);
    const params = new URLSearchParams(url.search);
    
    if (query.trim()) {
        params.set('search', query);
    } else {
        params.delete('search');
    }
    
    // Update URL and reload
    url.search = params.toString();
    window.location.href = url.toString();
}

// Filter Functions
function applyFilters() {
    const searchTerm = document.getElementById('order-search').value;
    const paymentStatus = document.getElementById('payment-status-filter').value;
    const dateFrom = document.getElementById('date-from').value;
    
    const url = new URL(window.location);
    const params = new URLSearchParams(url.search);
    
    // Clear existing filter params
    params.delete('search');
    params.delete('payment_status');
    params.delete('date_from');
    params.delete('date_to');
    
    // Add new filter params
    if (searchTerm.trim()) {
        params.set('search', searchTerm);
    }
    if (paymentStatus) {
        params.set('payment_status', paymentStatus);
    }
    if (dateFrom) {
        params.set('date_from', dateFrom);
    }
    
    // Update URL and reload
    url.search = params.toString();
    window.location.href = url.toString();
}

function clearFilters() {
    const url = new URL(window.location);
    const params = new URLSearchParams(url.search);
    
    // Clear all filter params
    params.delete('search');
    params.delete('payment_status');
    params.delete('date_from');
    params.delete('date_to');
    params.delete('status');
    
    // Update URL and reload
    url.search = params.toString();
    window.location.href = url.toString();
}

// Status Change Functions
function initStatusChangeHandlers() {
    // Add event listeners for status change buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('[onclick*="changeStatus"]')) {
            e.preventDefault();
            const onclick = e.target.closest('[onclick*="changeStatus"]').getAttribute('onclick');
            const matches = onclick.match(/changeStatus\((\d+),\s*'([^']+)'\)/);
            if (matches) {
                const orderId = matches[1];
                const status = matches[2];
                changeStatus(orderId, status);
            }
        }
    });
}

function changeStatus(orderId, status, paymentStatus = null) {
    if (!orderId || !status) {
        console.error('Order ID or status is missing', { orderId, status });
        showNotification('Error: Missing order ID or status', 'error');
        return;
    }

    if (!confirm(`Are you sure you want to change this order status to "${status}"?`)) {
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        showNotification('CSRF token not found. Please refresh the page.', 'error');
        return;
    }

    // Get payment status from data attribute or use default
    const paymentStatusValue = paymentStatus || document.querySelector('[data-payment-status]')?.getAttribute('data-payment-status') || 'unpaid';

    // Use POST with method spoofing for better compatibility
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('_token', csrfToken);
    formData.append('status', status);
    formData.append('payment_status', paymentStatusValue);

    fetch(`/admin/orders/${orderId}/status`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('Order status updated successfully', 'success');
            // Reload the page to show updated status
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showNotification(data.message || 'Failed to update order status', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating order status:', error);
        showNotification('An error occurred while updating the order status: ' + error.message, 'error');
    });
}

// Bulk Action Functions
function bulkConfirm() {
    performBulkAction('confirmed', 'Confirm selected orders?');
}

function bulkProcess() {
    performBulkAction('processing', 'Process selected orders?');
}

function bulkShip() {
    performBulkAction('shipped', 'Ship selected orders?');
}

function bulkDeliver() {
    performBulkAction('completed', 'Deliver selected orders?');
}

function bulkCancel() {
    performBulkAction('cancelled', 'Cancel selected orders?');
}

function bulkDelete() {
    if (!confirm('Are you sure you want to delete the selected orders? This action cannot be undone.')) {
        return;
    }
    performBulkAction('delete', 'Delete selected orders?');
}

function performBulkAction(action, confirmMessage) {
    const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
    const orderIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (orderIds.length === 0) {
        showNotification('Please select at least one order', 'warning');
        return;
    }
    
    if (!confirm(confirmMessage)) {
        return;
    }

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('action', action);
    formData.append('order_ids', JSON.stringify(orderIds));

    fetch('/admin/orders/bulk-action', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'Bulk action completed successfully', 'success');
            window.location.reload();
        } else {
            showNotification(data.message || 'Failed to perform bulk action', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while performing the bulk action', 'error');
    });
}

// Delete Order Function
function deleteOrder(orderId) {
    if (!confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        return;
    }

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'DELETE');

    fetch(`/admin/orders/${orderId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Order deleted successfully', 'success');
            // Remove the row from the table
            const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
            if (row) {
                row.remove();
            }
        } else {
            showNotification(data.message || 'Failed to delete order', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while deleting the order', 'error');
    });
}

// Export Functions
function exportOrders() {
    const url = new URL(window.location);
    const params = new URLSearchParams(url.search);
    params.set('export', '1');
    url.search = params.toString();
    window.open(url.toString(), '_blank');
}

function exportOrder(orderId) {
    window.open(`/admin/orders/${orderId}/export`, '_blank');
}

// View Toggle Functions
function initViewToggle() {
    const viewButtons = document.querySelectorAll('[onclick*="toggleView"]');
    viewButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const view = this.getAttribute('onclick').match(/toggleView\('([^']+)'\)/)[1];
            toggleView(view);
        });
    });
}

function toggleView(view) {
    const tableContainer = document.querySelector('.table-responsive');
    const gridContainer = document.getElementById('orders-grid');
    
    if (view === 'grid') {
        if (tableContainer) tableContainer.style.display = 'none';
        if (gridContainer) gridContainer.style.display = 'block';
        
        // Update button states
        document.querySelector('[onclick*="toggleView(\'table\')"]').classList.remove('active');
        document.querySelector('[onclick*="toggleView(\'grid\')"]').classList.add('active');
    } else {
        if (tableContainer) tableContainer.style.display = 'block';
        if (gridContainer) gridContainer.style.display = 'none';
        
        // Update button states
        document.querySelector('[onclick*="toggleView(\'table\')"]').classList.add('active');
        document.querySelector('[onclick*="toggleView(\'grid\')"]').classList.remove('active');
    }
}

// Notification Function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
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

// Utility Functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-NP', {
        style: 'currency',
        currency: 'NPR',
        minimumFractionDigits: 2
    }).format(amount);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-NP', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Export functions for global access
window.OrderModule = {
    changeStatus,
    deleteOrder,
    exportOrders,
    exportOrder,
    toggleView,
    showNotification,
    formatCurrency,
    formatDate,
    applyFilters,
    clearFilters,
    performOrderSearch
};
