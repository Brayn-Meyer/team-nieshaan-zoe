<?php
// NotificationsComponent.php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize group messages if not exists
if (!isset($_SESSION['group_messages'])) {
    $_SESSION['group_messages'] = [
        ['sender' => 'Admin', 'text' => 'Morning team! Please review the schedule.'],
        ['sender' => 'Employee Name', 'text' => 'Will do!']
    ];
}

// Helper: format time as "X mins/hours/days ago"
function formatTimeAgo($dateString) {
    if (!$dateString) return '';
    
    $date = new DateTime($dateString);
    $now = new DateTime();
    $diff = $now->diff($date);
    
    if ($diff->days > 1) {
        return $diff->days . ' days ago';
    } elseif ($diff->days == 1) {
        return 'Yesterday';
    } elseif ($diff->h > 0) {
        return $diff->h . ' hours ago';
    } else {
        return max(1, $diff->i) . ' mins ago';
    }
}

// Helper: determine if notification is "Today" or "Earlier"
function determineSection($dateString) {
    if (!$dateString) return 'earlier';
    
    $date = new DateTime($dateString);
    $today = new DateTime();
    
    return ($date->format('Y-m-d') === $today->format('Y-m-d')) ? 'today' : 'earlier';
}

// Fetch notifications
$todayNotifications = [];
$earlierNotifications = [];

try {
    // Use existing mysqli connection and model
    require_once __DIR__ . '/../../includes/db.php';
    require_once __DIR__ . '/../models/NotificationsModel.php';

    // Get database connection
    $database = Database::getInstance();
    $conn = $database->getConnection();

    $dbNotifications = getNotificationsWithEmployees($conn);

    if (is_array($dbNotifications) && isset($dbNotifications['error'])) {
        error_log('NotificationsComponent: ' . $dbNotifications['error']);
        $dbNotifications = [];
    }

    // Debug: Check what we're getting
    error_log('Notifications found: ' . count($dbNotifications));

    foreach ($dbNotifications as $dbNote) {
        $id = $dbNote['notification_id'] ?? $dbNote['id'] ?? null;
        $date = $dbNote['date_created'] ?? null;

      $notification = [
    'id' => $id,
    'employee' => $dbNote['employee_name'] ?? 'Unknown',
    'message' => $dbNote['message'] ?? '',
    'time' => $date ? formatTimeAgo($date) : '',
    'section' => $date ? determineSection($date) : 'earlier'
];
        if ($notification['section'] === 'today') {
            $todayNotifications[] = $notification;
        } else {
            $earlierNotifications[] = $notification;
        }
    }
} catch (Throwable $e) {
    error_log("Error fetching notifications: " . $e->getMessage());
    $todayNotifications = [];
    $earlierNotifications = [];
}

// Handle form submissions for notifications
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
       
            
        case 'send_group_message':
            $message = trim($_POST['group_message'] ?? '');
            if ($message) {
                $_SESSION['group_messages'][] = ['sender' => 'Admin', 'text' => $message];
            }
            break;
            
        case 'send_reply':
            $replyMessage = trim($_POST['reply_message'] ?? '');
            $employee = $_POST['employee'] ?? '';
            if ($replyMessage && $employee) {
                $_SESSION['group_messages'][] = [
                    'sender' => 'Admin (reply)', 
                    'text' => "Reply to $employee: $replyMessage"
                ];
                $_SESSION['reply_success'] = "Reply sent to $employee";
            }
            break;
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}


?>

<!-- Notification Button -->
<button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#notificationsModal" 
    aria-label="Open notifications" title="View notifications">
    <i class="fa-regular fa-bell fs-5"></i>
</button>

<!-- Notifications Modal -->
<div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-3">
            <div class="modal-header d-flex justify-content-between align-items-center modal-header-custom">
                <h5 class="modal-title mb-0 fw-semibold" id="notificationsModalLabel">Notifications</h5>
                <div class="d-flex align-items-center gap-2 ms-auto">
                    <button class="btn btn-sm btn-icon" 
                        data-bs-toggle="modal" data-bs-target="#groupChatModal"
                        aria-label="Open group chat" title="Open group chat">
                        <i class="fa-solid fa-comments"></i>
                    </button>
                    <button type="button" class="btn btn-close-icon" data-bs-dismiss="modal" aria-label="Close" title="Close modal">
                        <i class="fa-solid fa-xmark fs-5"></i>
                    </button>
                </div>
            </div>

            <div class="modal-body p-0">
                <!-- Today -->
                <?php if (!empty($todayNotifications)): ?>
                <div class="px-4 py-3 section-divider">
                    <div class="small text-muted">Today</div>
                </div>

                <div>
                    <div class="list-group list-group-flush">
                       <?php foreach ($todayNotifications as $note): ?>
    <div class="list-group-item d-flex align-items-start gap-3 py-3 <?php echo !$note['read'] ? 'bg-light-unread unread-hover' : ''; ?>">
    <div class="avatar-bell flex-shrink-0">
        <i class="fa-solid fa-bell"></i>
    </div>
    
    <div class="notification-content flex-grow-1">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <span class="<?php echo !$note['read'] ? 'unread-indicator' : 'read-indicator'; ?>"></span>
                <span class="employee-name"><?php echo htmlspecialchars($note['employee']); ?></span>
                <?php if (!empty($note['title'])): ?>
                    <span class="notification-title ms-2"><?php echo htmlspecialchars($note['title']); ?></span>
                <?php endif; ?>
            </div>
            <small class="notification-time"><?php echo htmlspecialchars($note['time']); ?></small>
        </div>
        
        <p class="notification-message mb-0"><?php echo htmlspecialchars($note['message']); ?></p>
    </div>
    
    <div class="notification-actions flex-shrink-0">
        <form method="POST" class="d-inline">
            <input type="hidden" name="action" value="toggle_read">
            <input type="hidden" name="notification_id" value="<?php echo $note['id']; ?>">
            <button class="btn btn-action <?php echo $note['read'] ? 'btn-outline-secondary' : 'btn-outline-success'; ?>" 
                    title="<?php echo $note['read'] ? 'Mark as unread' : 'Mark as read'; ?>">
                <i class="fa-solid fa-<?php echo $note['read'] ? 'envelope' : 'envelope-open'; ?>"></i>
            </button>
        </form>

        <button class="btn btn-action btn-outline-secondary" 
                data-bs-toggle="modal" data-bs-target="#replyModal"
                onclick="setReplyEmployee('<?php echo addslashes($note['employee']); ?>', '<?php echo addslashes($note['message']); ?>')"
                title="Reply to employee">
            <i class="fa-solid fa-reply"></i>
        </button>
    </div>
</div>
<?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Earlier -->
                <?php if (!empty($earlierNotifications)): ?>
                <div class="px-4 py-3 section-divider earlier-section">
                    <div class="small text-muted">Earlier</div>
                </div>

                <div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($earlierNotifications as $note): ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between py-3 >">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="avatar-bell flex-shrink-0">
                                        <i class="fa-solid fa-bell"></i>
                                    </div>
                                    <div class="d-flex flex-column gap-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="fw-bold text-success"><?php echo htmlspecialchars($note['employee']); ?>:</div>
                                            <div class="text-muted small"><?php echo htmlspecialchars($note['message']); ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-column align-items-end gap-2">
                                    <div class="d-flex gap-2 align-items-center">
                                        

                                        <button class="btn btn-sm btn-outline-secondary" 
                                                data-bs-toggle="modal" data-bs-target="#replyModal"
                                                onclick="setReplyEmployee('<?php echo addslashes($note['employee']); ?>', '<?php echo addslashes($note['message']); ?>')"
                                                title="Reply to employee">
                                            <i class="fa-solid fa-message"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted"><?php echo htmlspecialchars($note['time']); ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Empty State -->
                <?php if (empty($todayNotifications) && empty($earlierNotifications)): ?>
                <div class="text-center py-5">
                    <i class="fa-regular fa-bell fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No notifications yet</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Group Chat Modal -->
<div class="modal fade" id="groupChatModal" tabindex="-1" aria-labelledby="groupChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title" id="groupChatModalLabel">Group Chat</h5>
                <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close" title="Close group chat">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="chat-box mb-3">
                    <?php foreach ($_SESSION['group_messages'] as $message): ?>
                        <div class="mb-2">
                            <strong><?php echo htmlspecialchars($message['sender']); ?>:</strong> 
                            <span><?php echo htmlspecialchars($message['text']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <form method="POST">
                    <input type="hidden" name="action" value="send_group_message">
                    <div class="input-group">
                        <input type="text" name="group_message" class="form-control" placeholder="Type an announcement...">
                        <button class="btn btn-success" type="submit">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title" id="replyModalLabel">Reply to <span id="replyEmployeeName">Employee</span></h5>
                <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close" title="Close reply modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST">
                <input type="hidden" name="action" value="send_reply">
                <input type="hidden" name="employee" id="replyEmployee">
                <div class="modal-body">
                    <p class="small"><strong>Original:</strong> <span id="originalMessage"></span></p>
                    <textarea name="reply_message" class="form-control" rows="4" placeholder="Type your reply..."></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Send Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['reply_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <?php echo $_SESSION['reply_success']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['reply_success']); ?>
<?php endif; ?>

<style>
/* Notification Styles - Green Theme (#2EB28A) */
.notification-badge {
    background: linear-gradient(135deg, #2EB28A, #27a87c);
    color: #fff;
    padding: 0.25em 0.5em;
    font-size: 0.7rem;
    font-weight: 600;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Modal Container */
.modal-content {
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e9ecef;
}

.modal-header-custom {
    background: linear-gradient(135deg, #2EB28A 0%, #27a87c 100%);
    color: white;
    border-bottom: none;
    padding: 1.5rem;
}

.modal-header-custom .modal-title {
    font-weight: 700;
    font-size: 1.4rem;
}

.btn-close-icon {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.3s ease;
}

.btn-close-icon:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

.btn-icon {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 8px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.3s ease;
}

.btn-icon:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

/* Section Headers */
.section-divider {
    background: #f8f9fa;
    border: none;
    padding: 1rem 1.5rem;
    margin: 0;
    border-bottom: 1px solid #e9ecef;
}

.section-divider .small {
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.earlier-section {
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
}

/* Notification Items */
.list-group-item {
    border: none;
    border-bottom: 1px solid #f1f3f4;
    padding: 1.25rem 1.5rem;
    transition: all 0.3s ease;
    background: white;
}

.list-group-item:hover {
    background: #f8f9fa !important;
    transform: translateX(4px);
}

.bg-light-unread {
    background: linear-gradient(135deg, #f8fffc, #f0fff9) !important;
    border-left: 4px solid #2EB28A;
}

.unread-hover:hover {
    background: linear-gradient(135deg, #f0fff9, #e8fff6) !important;
}

.avatar-bell {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #2EB28A, #27a87c);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    box-shadow: 0 4px 12px rgba(46, 178, 138, 0.3);
    flex-shrink: 0;
}

/* Notification Content */
.notification-content {
    flex: 1;
    text-align: center;
}

.employee-name {
    color: #2d3748;
    font-weight: 700;
    font-size: 0.95rem;
}

.notification-message {
    color: #4a5568;
    font-size: 0.9rem;
    line-height: 1.4;
    margin: 0.25rem 0;
    text-align: center;

}

.notification-title {
    color: #2EB28A;
    font-weight: 600;
    font-size: 0.85rem;
    background: #f0fff9;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    display: inline-block;
    border: 1px solid #c6f6e4;
    text-align: center;

}

/* Action Buttons */
.notification-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.btn-action {
    border: none;
    border-radius: 8px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 0.85rem;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.btn-outline-success {
    border: 2px solid #2EB28A;
    color: #2EB28A;
    background: white;
}

.btn-outline-success:hover {
    background: #2EB28A;
    color: white;
}

.btn-outline-secondary {
    border: 2px solid #6c757d;
    color: #6c757d;
    background: white;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
}

/* Time Stamp */
.notification-time {
    color: #718096;
    font-size: 0.8rem;
    font-weight: 500;
    margin-top: 0.5rem;
}

/* Empty State */
.empty-state {
    padding: 3rem 2rem;
    text-align: center;
    color: #718096;
    background: #f8f9fa;
    border-radius: 8px;
    margin: 1rem;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
    color: #a0aec0;
}

.empty-state p {
    font-size: 1.1rem;
    margin: 0;
    color: #718096;
}

/* Modal Body Scroll */
.modal-body {
    max-height: 60vh;
    overflow-y: auto;
    background: #fafafa;
}

.modal-body::-webkit-scrollbar {
    width: 6px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 3px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

/* Button adjustments for notification button */
.btn-light {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    color: #4a5568;
    font-weight: 500;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.btn-light:hover {
    background: #f7fafc;
    border-color: #2EB28A;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 178, 138, 0.15);
    color: #2EB28A;
}

/* Chat Modal Enhancements */
.chat-box {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 1.5rem;
    background: #f7fafc;
    max-height: 300px;
    overflow-y: auto;
}

.chat-message {
    margin-bottom: 1rem;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    background: white;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.chat-message strong {
    color: #2EB28A;
}

/* Reply Modal Enhancements */
#replyModal .modal-content {
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

#replyModal .modal-header {
    background: linear-gradient(135deg, #2EB28A 0%, #27a87c 100%);
    color: white;
    border-bottom: none;
}

#replyModal .btn-close {
    filter: invert(1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 1rem;
    }
    
    .list-group-item {
        padding: 1rem;
        flex-direction: column;
        gap: 1rem;
    }
    
    .avatar-bell {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .notification-actions {
        align-self: flex-end;
    }
    
    .notification-content {
        width: 100%;
    }
}

/* Animation for new notifications */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.list-group-item {
    animation: slideIn 0.3s ease-out;
}

/* Status indicators */
.read-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #2EB28A;
    display: inline-block;
    margin-right: 8px;
}

.unread-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #e53e3e;
    display: inline-block;
    margin-right: 8px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

/* Success messages */
.alert-success {
    background: #f0fff9;
    border: 1px solid #2EB28A;
    color: #2EB28A;
    border-radius: 8px;
}

/* Form elements */
.form-control:focus {
    border-color: #2EB28A;
    box-shadow: 0 0 0 0.2rem rgba(46, 178, 138, 0.25);
}

.btn-success {
    background: #2EB28A;
    border-color: #2EB28A;
}

.btn-success:hover {
    background: #27a87c;
    border-color: #27a87c;
}
</style>
<script>
function setReplyEmployee(employee, message) {
    document.getElementById('replyEmployeeName').textContent = employee;
    document.getElementById('replyEmployee').value = employee;
    document.getElementById('originalMessage').textContent = message;
}

</script>