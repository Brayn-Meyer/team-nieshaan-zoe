<?php
function renderNotificationBell() {
    // Calculate unread count
    $unreadCount = 0;
    if (isset($_SESSION['notifications'])) {
        foreach ($_SESSION['notifications'] as $notification) {
            if (!$notification['read']) {
                $unreadCount++;
            }
        }
    }
    ?>
    
    <!-- Notification Bell Component -->
    <div class="notification-container">
        <div class="notification-bell" onclick="toggleNotificationDropdown()">
            <i class="fas fa-bell"></i>
            <?php if ($unreadCount > 0): ?>
                <span class="notification-badge" id="notificationCount">
                    <?php echo $unreadCount > 99 ? '99+' : $unreadCount; ?>
                </span>
            <?php endif; ?>
        </div>
        
        <div class="notification-dropdown" id="notificationDropdown">
            <div class="notification-header">
                <h6>Notifications</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                    Mark all read
                </button>
            </div>
            <div class="notification-list" id="notificationList">
                <?php if (empty($_SESSION['notifications'])): ?>
                    <div class="notification-empty">
                        <i class="fas fa-bell-slash"></i>
                        <p>No new notifications</p>
                    </div>
                <?php else: ?>
                    <?php 
                    $todayNotifications = array_filter($_SESSION['notifications'], fn($n) => $n['section'] === 'today');
                    $earlierNotifications = array_filter($_SESSION['notifications'], fn($n) => $n['section'] === 'earlier');
                    ?>
                    
                    <?php if (!empty($todayNotifications)): ?>
                        <div class="notification-section">
                            <div class="notification-section-title">Today</div>
                            <?php foreach ($todayNotifications as $notification): ?>
                                <div class="notification-item <?php echo !$notification['read'] ? 'unread' : ''; ?>" 
                                     data-id="<?php echo $notification['id']; ?>">
                                    <div class="notification-content">
                                        <div class="notification-employee"><?php echo htmlspecialchars($notification['employee']); ?></div>
                                        <div class="notification-message"><?php echo htmlspecialchars($notification['message']); ?></div>
                                        <div class="notification-time"><?php echo htmlspecialchars($notification['time']); ?></div>
                                    </div>
                                    <div class="notification-actions">
                                        <button class="btn btn-sm <?php echo $notification['read'] ? 'btn-outline-secondary' : 'btn-outline-success'; ?>" 
                                                onclick="toggleRead(<?php echo $notification['id']; ?>)"
                                                title="<?php echo $notification['read'] ? 'Mark as unread' : 'Mark as read'; ?>">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" 
                                                onclick="openReplyModal('<?php echo addslashes($notification['employee']); ?>', '<?php echo addslashes($notification['message']); ?>')"
                                                title="Reply to employee">
                                            <i class="fa-solid fa-message"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($earlierNotifications)): ?>
                        <div class="notification-section">
                            <div class="notification-section-title">Earlier</div>
                            <?php foreach ($earlierNotifications as $notification): ?>
                                <div class="notification-item <?php echo !$notification['read'] ? 'unread' : ''; ?>" 
                                     data-id="<?php echo $notification['id']; ?>">
                                    <div class="notification-content">
                                        <div class="notification-employee"><?php echo htmlspecialchars($notification['employee']); ?></div>
                                        <div class="notification-message"><?php echo htmlspecialchars($notification['message']); ?></div>
                                        <div class="notification-time"><?php echo htmlspecialchars($notification['time']); ?></div>
                                    </div>
                                    <div class="notification-actions">
                                        <button class="btn btn-sm <?php echo $notification['read'] ? 'btn-outline-secondary' : 'btn-outline-success'; ?>" 
                                                onclick="toggleRead(<?php echo $notification['id']; ?>)"
                                                title="<?php echo $notification['read'] ? 'Mark as unread' : 'Mark as read'; ?>">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" 
                                                onclick="openReplyModal('<?php echo addslashes($notification['employee']); ?>', '<?php echo addslashes($notification['message']); ?>')"
                                                title="Reply to employee">
                                            <i class="fa-solid fa-message"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="notification-footer">
                <a href="notifications.php" class="btn btn-sm btn-link w-100">View All Notifications</a>
            </div>
        </div>
    </div>

    <style>
    .notification-container {
        position: relative;
        margin-right: 15px;
    }

    .notification-bell {
        position: relative;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-size: 1.1rem;
        color: #6c757d;
        background: transparent;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .notification-bell:hover {
        background-color: #e9ecef;
        color: #495057;
    }

    .notification-badge {
        position: absolute;
        top: 0;
        right: 0;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        border: 2px solid #fff;
    }

    .notification-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        width: 380px;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        z-index: 1000;
        display: none;
        margin-top: 8px;
    }

    .notification-dropdown.show {
        display: block;
    }

    .notification-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f9fa;
        border-radius: 8px 8px 0 0;
    }

    .notification-header h6 {
        margin: 0;
        font-weight: 600;
        color: #495057;
        font-size: 1rem;
    }

    .notification-list {
        max-height: 400px;
        overflow-y: auto;
        background: white;
    }

    .notification-section {
        border-bottom: 1px solid #e9ecef;
    }

    .notification-section-title {
        padding: 0.5rem 1.25rem;
        background: #f8f9fa;
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
    }

    .notification-item {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        transition: background-color 0.15s ease-in-out;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item:hover {
        background-color: #f8f9fa;
    }

    .notification-item.unread {
        background-color: #f0f9ff;
        border-left: 3px solid #007bff;
    }

    .notification-content {
        flex: 1;
    }

    .notification-employee {
        font-weight: bold;
        color: #2eb28a;
        margin-bottom: 0.25rem;
    }

    .notification-message {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .notification-time {
        font-size: 0.75rem;
        color: #adb5bd;
    }

    .notification-actions {
        display: flex;
        gap: 0.5rem;
        margin-left: 1rem;
    }

    .notification-empty {
        padding: 2rem 1rem;
        text-align: center;
        color: #6c757d;
    }

    .notification-empty i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
        color: #6c757d;
    }

    .notification-empty p {
        margin: 0;
        font-size: 0.9rem;
    }

    .notification-footer {
        padding: 0.75rem 1.25rem;
        border-top: 1px solid #dee2e6;
        background: #f8f9fa;
        border-radius: 0 0 8px 8px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .notification-dropdown {
            width: 320px;
            right: -10px;
        }
        
        .notification-container {
            margin-right: 10px;
        }
    }

    @media (max-width: 480px) {
        .notification-dropdown {
            width: 280px;
            right: -20px;
        }
    }
    </style>

    <script>
    // Notification functionality
    function toggleNotificationDropdown() {
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    }

    function toggleRead(notificationId) {
        // Send AJAX request to toggle read status
        fetch('/api/notifications/toggle-read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ notification_id: notificationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI
                const notificationItem = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
                if (notificationItem) {
                    notificationItem.classList.toggle('unread');
                    const button = notificationItem.querySelector('.btn-outline-success, .btn-outline-secondary');
                    if (button) {
                        if (notificationItem.classList.contains('unread')) {
                            button.classList.remove('btn-outline-secondary');
                            button.classList.add('btn-outline-success');
                            button.title = 'Mark as read';
                        } else {
                            button.classList.remove('btn-outline-success');
                            button.classList.add('btn-outline-secondary');
                            button.title = 'Mark as unread';
                        }
                    }
                    
                    // Update badge count
                    updateNotificationBadge();
                }
            } else {
                console.error('Error toggling read status:', data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function markAllAsRead() {
        // Send AJAX request to mark all as read
        fetch('/api/notifications/mark-all-read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI
                document.querySelectorAll('.notification-item.unread').forEach(item => {
                    item.classList.remove('unread');
                    const button = item.querySelector('.btn-outline-success');
                    if (button) {
                        button.classList.remove('btn-outline-success');
                        button.classList.add('btn-outline-secondary');
                        button.title = 'Mark as unread';
                    }
                });
                
                // Update badge count
                updateNotificationBadge();
                
                showToast('All notifications marked as read', 'success');
            } else {
                showToast('Failed to mark notifications as read', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to mark notifications as read', 'error');
        });
    }

    function updateNotificationBadge() {
        const unreadItems = document.querySelectorAll('.notification-item.unread');
        const badge = document.getElementById('notificationCount');
        
        if (unreadItems.length === 0) {
            if (badge) badge.style.display = 'none';
        } else {
            if (badge) {
                badge.textContent = unreadItems.length > 99 ? '99+' : unreadItems.length;
                badge.style.display = 'flex';
            }
        }
    }

    function openReplyModal(employee, message) {
        // Set the employee and message in the reply modal
        document.getElementById('replyEmployeeName').textContent = employee;
        document.getElementById('replyEmployee').value = employee;
        document.getElementById('originalMessage').textContent = message;

        // Open the reply modal (using Bootstrap)
        const replyModal = new bootstrap.Modal(document.getElementById('replyModal'));
        replyModal.show();
        
        // Close notification dropdown
        toggleNotificationDropdown();
    }

    function showToast(message, type = 'info') {
        // Create toast element
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

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const notificationContainer = document.querySelector('.notification-container');
        const dropdown = document.getElementById('notificationDropdown');
        
        if (notificationContainer && !notificationContainer.contains(e.target) && dropdown) {
            dropdown.classList.remove('show');
        }
    });

    // Close dropdown with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const dropdown = document.getElementById('notificationDropdown');
            if (dropdown) {
                dropdown.classList.remove('show');
            }
        }
    });
    </script>
    <?php
}
?>