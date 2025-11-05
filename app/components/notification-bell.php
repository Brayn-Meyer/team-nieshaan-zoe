<?php
// components/notification-bell.php
function renderNotificationBell() {
    ?>
    <!-- Notification Bell Component -->
    <div class="notification-container">
        <div class="notification-bell" onclick="toggleNotificationDropdown()">
            <i class="fas fa-bell"></i>
            <span class="notification-badge" id="notificationCount">0</span>
        </div>
        
        <div class="notification-dropdown" id="notificationDropdown">
            <div class="notification-header">
                <h6>Notifications</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                    Mark all read
                </button>
            </div>
            <div class="notification-list" id="notificationList">
                <div class="text-center text-muted py-4">
                    <i class="fas fa-spinner fa-spin"></i> Loading notifications...
                </div>
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

    .notification-item {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e9ecef;
        cursor: pointer;
        transition: background-color 0.15s ease-in-out;
        position: relative;
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

    .notification-item.unread::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 50%;
        transform: translateY(-50%);
        width: 6px;
        height: 6px;
        background: #007bff;
        border-radius: 50%;
    }

    .notification-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: #495057;
        font-size: 0.9rem;
    }

    .notification-message {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .notification-time {
        font-size: 0.75rem;
        color: #adb5bd;
        font-weight: 500;
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

    /* Loading states */
    .text-muted {
        color: #6c757d !important;
    }

    .text-center {
        text-align: center !important;
    }

    .py-4 {
        padding-top: 1.5rem !important;
        padding-bottom: 1.5rem !important;
    }
    </style>
    <?php
}
?>