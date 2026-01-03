// Modern Admin Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Initialize all components
        initSidebar();
        initDateTime();
        initSearch();
        initNotifications();
        initSubmenus();
        initAnimations();
        initCharts();
        initDarkMode();
        initCounters();
        initErrorHandling();
    } catch (error) {
        console.error('Error initializing admin dashboard:', error);
        showNotification('Error initializing dashboard. Please refresh the page.', 'danger');
    }
});

// Sidebar functionality
function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
    }
    
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 1024) {
            if (!sidebar.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 1024) {
            sidebar.classList.remove('open');
        }
    });
}

// Real-time date and time
function initDateTime() {
    const dateElement = document.getElementById('current-date');
    const timeElement = document.getElementById('current-time');
    
    if (dateElement && timeElement) {
        function updateDateTime() {
            const now = new Date();
            
            // Update date
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            dateElement.textContent = now.toLocaleDateString('en-US', options);
            
            // Update time
            timeElement.textContent = now.toLocaleTimeString('en-US', { 
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
        
        // Update immediately and then every second
        updateDateTime();
        setInterval(updateDateTime, 1000);
    }
}

// Search functionality
function initSearch() {
    const searchInput = document.querySelector('.search-input');
    
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();
            
            if (query.length > 2) {
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            }
        });
        
        // Add search icon click handler
        const searchIcon = document.querySelector('.search-icon');
        if (searchIcon) {
            searchIcon.addEventListener('click', function() {
                searchInput.focus();
            });
        }
    }
}

// Perform search (placeholder - implement based on your needs)
function performSearch(query) {
    console.log('Searching for:', query);
    // Implement search logic here
    // This could be an AJAX call to search endpoints
}

// Notifications
function initNotifications() {
    const notificationIcon = document.getElementById('notification-icon');
    
    if (notificationIcon) {
        notificationIcon.addEventListener('click', function() {
            // Toggle notifications dropdown
            // This would typically show a dropdown with notifications
            console.log('Notifications clicked');
        });
    }
}

// Submenu functionality
function initSubmenus() {
    const submenuToggles = document.querySelectorAll('.submenu-toggle');
    
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.closest('.has-submenu');
            parent.classList.toggle('open');
        });
    });
}

// Animations and effects
function initAnimations() {
    // Intersection Observer for fade-in animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);
    
    // Observe all cards and stat cards
    const animatedElements = document.querySelectorAll('.card, .stat-card, .product-item');
    animatedElements.forEach(el => {
        observer.observe(el);
    });
    
    // Add hover effects to interactive elements
    const interactiveElements = document.querySelectorAll('.nav-link, .btn, .card');
    interactiveElements.forEach(el => {
        el.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        el.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

// Chart initialization
function initCharts() {
    // This function will be called by individual pages that need charts
    // Charts are initialized using Chart.js in the specific views
}

// Utility functions
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const content = document.querySelector('.content');
    if (content) {
        content.insertBefore(notification, content.firstChild);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
}

// AJAX helper function
function makeRequest(url, options = {}) {
    const defaultOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    };
    
    const finalOptions = { ...defaultOptions, ...options };
    
    return fetch(url, finalOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .catch(error => {
            console.error('Request failed:', error);
            showNotification('An error occurred while processing your request.', 'danger');
        });
}

// Form validation helper
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

// Loading state helper
function setLoadingState(element, isLoading) {
    if (isLoading) {
        element.classList.add('loading');
        element.disabled = true;
    } else {
        element.classList.remove('loading');
        element.disabled = false;
    }
}

// Dark Mode functionality
function initDarkMode() {
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const darkModeIcon = document.getElementById('dark-mode-icon');
    const body = document.body;
    
    if (!darkModeToggle || !darkModeIcon) return;
    
    // Get saved theme preference or default to light
    const savedTheme = localStorage.getItem('admin-theme') || 'light';
    setTheme(savedTheme);
    
    // Add click event listener
    darkModeToggle.addEventListener('click', function() {
        const currentTheme = body.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);
        saveTheme(newTheme);
    });
    
    function setTheme(theme) {
        body.setAttribute('data-theme', theme);
        
        // Update icon
        if (theme === 'light') {
            darkModeIcon.className = 'fas fa-sun';
            darkModeToggle.title = 'Switch to Dark Mode';
        } else {
            darkModeIcon.className = 'fas fa-moon';
            darkModeToggle.title = 'Switch to Light Mode';
        }
        
        // Add smooth transition effect
        body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
        
        // Remove transition after animation completes
        setTimeout(() => {
            body.style.transition = '';
        }, 300);
    }
    
    function saveTheme(theme) {
        localStorage.setItem('admin-theme', theme);
    }
}

// Counter animation for dashboard stats
function initCounters() {
    const counters = document.querySelectorAll('[data-count]');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-count'));
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60fps
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            // Format the number based on type
            if (counter.textContent.includes('Rs.')) {
                counter.textContent = 'Rs. ' + Math.floor(current).toLocaleString();
            } else {
                counter.textContent = Math.floor(current).toLocaleString();
            }
        }, 16);
    });
}

// Initialize trend indicators
function initTrends() {
    const trendElements = document.querySelectorAll('[data-trend]');
    
    trendElements.forEach(element => {
        const trend = parseFloat(element.getAttribute('data-trend'));
        const span = element.querySelector('span');
        const upIcon = element.querySelector('.fa-arrow-up');
        const downIcon = element.querySelector('.fa-arrow-down');
        const minusIcon = element.querySelector('.fa-minus');
        
        // Hide all icons first
        [upIcon, downIcon, minusIcon].forEach(icon => {
            if (icon) icon.style.display = 'none';
        });
        
        if (trend > 0) {
            if (upIcon) upIcon.style.display = 'inline';
            element.classList.add('positive');
            span.textContent = '+' + trend + '%';
        } else if (trend < 0) {
            if (downIcon) downIcon.style.display = 'inline';
            element.classList.add('negative');
            span.textContent = trend + '%';
        } else {
            if (minusIcon) minusIcon.style.display = 'inline';
            element.classList.add('neutral');
            span.textContent = '0%';
        }
    });
}

// Error handling and recovery
function initErrorHandling() {
    // Global error handler
    window.addEventListener('error', function(e) {
        console.error('Global error:', e.error);
        showNotification('An unexpected error occurred. Please try again.', 'danger');
    });
    
    // Unhandled promise rejection handler
    window.addEventListener('unhandledrejection', function(e) {
        console.error('Unhandled promise rejection:', e.reason);
        showNotification('A network error occurred. Please check your connection.', 'warning');
    });
    
    // Network status monitoring
    window.addEventListener('online', function() {
        showNotification('Connection restored', 'success');
    });
    
    window.addEventListener('offline', function() {
        showNotification('Connection lost. Some features may not work.', 'warning');
    });
}

// Enhanced form validation
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    let firstInvalid = null;
    
    inputs.forEach(input => {
        const value = input.value.trim();
        const type = input.type;
        
        // Clear previous validation
        input.classList.remove('is-invalid', 'is-valid');
        
        if (!value) {
            input.classList.add('is-invalid');
            isValid = false;
            if (!firstInvalid) firstInvalid = input;
        } else {
            // Additional validation based on type
            if (type === 'email' && !isValidEmail(value)) {
                input.classList.add('is-invalid');
                isValid = false;
                if (!firstInvalid) firstInvalid = input;
            } else if (type === 'tel' && !isValidPhone(value)) {
                input.classList.add('is-invalid');
                isValid = false;
                if (!firstInvalid) firstInvalid = input;
            } else {
                input.classList.add('is-valid');
            }
        }
    });
    
    // Focus first invalid input
    if (firstInvalid) {
        firstInvalid.focus();
        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    return isValid;
}

// Email validation helper
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Phone validation helper
function isValidPhone(phone) {
    const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
}

// Enhanced loading state with progress
function setLoadingState(element, isLoading, progress = null) {
    if (isLoading) {
        element.classList.add('loading');
        element.disabled = true;
        
        if (progress !== null) {
            // Add progress bar if not exists
            let progressBar = element.querySelector('.progress-bar');
            if (!progressBar) {
                progressBar = document.createElement('div');
                progressBar.className = 'progress-bar';
                progressBar.style.cssText = `
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    height: 3px;
                    background: linear-gradient(90deg, #667eea, #764ba2);
                    transition: width 0.3s ease;
                    border-radius: 0 0 4px 4px;
                `;
                element.style.position = 'relative';
                element.appendChild(progressBar);
            }
            progressBar.style.width = progress + '%';
        }
    } else {
        element.classList.remove('loading');
        element.disabled = false;
        
        const progressBar = element.querySelector('.progress-bar');
        if (progressBar) {
            progressBar.remove();
        }
    }
}

// Export functions for use in other scripts
window.AdminDashboard = {
    showNotification,
    makeRequest,
    validateForm,
    setLoadingState,
    initCounters,
    initTrends,
    isValidEmail,
    isValidPhone
};