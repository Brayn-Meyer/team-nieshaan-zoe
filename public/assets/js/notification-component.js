// assets/js/notification-component.js

class NotificationComponent {
    constructor() {
        this.isDropdownOpen = false;
        this.notifications = [];
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadNotifications();
        // Poll for new notifications every 60 seconds
        setInterval(() => this.loadNotifications(), 60000);
    }

    setupEventListeners() {
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const notificationContainer = document.querySelector('.notification-container');
            if (notificationContainer && !notificationContainer.contains(e.target)) {
                this.closeDropdown();
            }
        });

        // Escape key to close dropdown
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isDropdownOpen) {
                this.closeDropdown();
            }
        });
    }

    async loadNotifications() {
        try {
            // Show loading state
            this.showLoading();
            
            const response = await fetch('/api/notifications?unread_only=true&limit=10');
            
            if (!response.ok) {
                throw new Error('Failed to fetch notifications');
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.notifications = data.notifications || [];
                this.updateNotificationUI();
            } else {
                throw new Error(data.error || 'Failed to load notifications');
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
            this.showError('Failed to load notifications');
        }
    }

    showLoading() {
        const notificationList = document.getElementById('notificationList');
        if (notificationList) {
            notificationList.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-spinner fa-spin"></i> Loading notifications...
                </div>
            `;
        }
    }

    showError(message) {
        const notificationList = document.getElementById('notificationList');
        if (notificationList) {
            notificationList.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-exclamation-triangle"></i><br>
                    <small>${this.escapeHtml(message)}</small>
                </div>
            `;
        }
    }

    updateNotificationUI() {
        const notificationCount = document.getElementById('notificationCount');
        const notificationList = document.getElementById('notificationList');
        
        if (!notificationCount || !notificationList) return;

        // Update badge count
        const unreadCount = this.notifications.filter(n => !n.is_read).length;
        notificationCount.textContent = unreadCount > 99 ? '99+' : unreadCount;
        notificationCount.style.display = unreadCount > 0 ? 'flex' : 'none';

        // Update notification list
        if (this.notifications.length === 0) {
            notificationList.innerHTML = `
                <div class="notification-empty">
                    <i class="fas fa-bell-slash"></i>
                    <p>No new notifications</p>
                </div>
            `;
        } else {
            notificationList.innerHTML = this.notifications.map(notification => `
                <div class="notification-item ${notification.is_read ? '' : 'unread'}" 
                     onclick="notificationComponent.handleNotificationClick(${notification.notification_id})">
                    <div class="notification-title">${this.escapeHtml(notification.title)}</div>
                    <div class="notification-message">${this.escapeHtml(notification.message)}</div>
                    <div class="notification-time">${this.formatTime(notification.date_created)}</div>
                </div>
            `).join('');
        }
    }

    async handleNotificationClick(notificationId) {
        try {
            // Mark as read
            await this.markAsRead(notificationId);
            
            // Update UI immediately
            const notification = this.notifications.find(n => n.notification_id === notificationId);
            if (notification) {
                notification.is_read = true;
            }
            this.updateNotificationUI();
            
        } catch (error) {
            console.error('Error handling notification click:', error);
        }
    }

    async markAsRead(notificationId) {
        try {
            const response = await fetch(`/api/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            
            if (!response.ok) {
                throw new Error('Failed to mark notification as read');
            }
            
            return await response.json();
        } catch (error) {
            console.error('Error marking notification as read:', error);
            throw error;
        }
    }

    async markAllAsRead() {
        try {
            const unreadNotifications = this.notifications.filter(n => !n.is_read);
            
            if (unreadNotifications.length === 0) return;
            
            // Mark all as read in UI immediately
            this.notifications.forEach(notification => {
                notification.is_read = true;
            });
            this.updateNotificationUI();
            
            // Send API request
            const response = await fetch('/api/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error('Failed to mark all notifications as read');
            }
            
            // Show success feedback
            this.showToast('All notifications marked as read', 'success');
            
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
            this.showToast('Failed to mark notifications as read', 'error');
            // Reload to sync with server
            this.loadNotifications();
        }
    }

    toggleDropdown() {
        if (this.isDropdownOpen) {
            this.closeDropdown();
        } else {
            this.openDropdown();
        }
    }

    openDropdown() {
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown) {
            dropdown.classList.add('show');
            this.isDropdownOpen = true;
            // Load notifications when opening dropdown
            this.loadNotifications();
        }
    }

    closeDropdown() {
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown) {
            dropdown.classList.remove('show');
            this.isDropdownOpen = false;
        }
    }

    formatTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);

        if (diffMins < 1) return 'Just now';
        if (diffMins < 60) return `${diffMins} min ago`;
        if (diffHours < 24) return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
        if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
        if (diffDays < 30) return `${Math.floor(diffDays / 7)} week${Math.floor(diffDays / 7) > 1 ? 's' : ''} ago`;
        
        return date.toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric',
            year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined
        });
    }

    showToast(message, type = 'info') {
        // Use your existing toast system or create a simple one
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} alert-dismissible fade show`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 250px;
        `;
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 3000);
    }

    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.notificationComponent = new NotificationComponent();
});

// Global functions for HTML onclick events
function toggleNotificationDropdown() {
    if (window.notificationComponent) {
        window.notificationComponent.toggleDropdown();
    }
}

function markAllAsRead() {
    if (window.notificationComponent) {
        window.notificationComponent.markAllAsRead();
    }
}