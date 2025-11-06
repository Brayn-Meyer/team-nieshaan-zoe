<?php
// components/NotificationsComponent.php

// Initialize notifications data if not exists
if (!isset($_SESSION['notifications'])) {
    $_SESSION['notifications'] = [
        ['id' => 1, 'employee' => 'Employee Name', 'message' => 'Request for clock-in adjustment.', 'time' => '2 mins ago', 'read' => false, 'section' => 'today'],
        ['id' => 2, 'employee' => 'Employee Name', 'message' => 'Request for clock-in adjustment.', 'time' => '10 mins ago', 'read' => false, 'section' => 'today'],
        ['id' => 3, 'employee' => 'Employee Name', 'message' => 'Request for clock-in adjustment.', 'time' => '45 mins ago', 'read' => true, 'section' => 'today'],
        ['id' => 4, 'employee' => 'Employee Name', 'message' => 'Request for clock-in adjustment.', 'time' => 'Yesterday', 'read' => true, 'section' => 'earlier'],
        ['id' => 5, 'employee' => 'Employee Name', 'message' => 'Request for clock-in adjustment.', 'time' => '2 days ago', 'read' => false, 'section' => 'earlier'],
    ];
}

if (!isset($_SESSION['group_messages'])) {
    $_SESSION['group_messages'] = [
        ['sender' => 'Admin', 'text' => 'Morning team! Please review the schedule.'],
        ['sender' => 'Employee Name', 'text' => 'Will do!']
    ];
}

// Handle form submissions for notifications
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'toggle_read':
            $notificationId = $_POST['notification_id'] ?? null;
            if ($notificationId) {
                foreach ($_SESSION['notifications'] as &$notification) {
                    if ($notification['id'] == $notificationId) {
                        $notification['read'] = !$notification['read'];
                        break;
                    }
                }
            }
            break;
            
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
    
    // Redirect to avoid form resubmission
    if (isset($_POST['action'])) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Calculate unread count
$unreadCount = 0;
foreach ($_SESSION['notifications'] as $notification) {
    if (!$notification['read']) {
        $unreadCount++;
    }
}

// Filter notifications
$todayNotifications = array_filter($_SESSION['notifications'], fn($n) => $n['section'] === 'today');
$earlierNotifications = array_filter($_SESSION['notifications'], fn($n) => $n['section'] === 'earlier');
?>

<!-- Notification Button -->
<div class="d-inline-block">
    <button class="btn btn-light position-relative" data-bs-toggle="modal" data-bs-target="#notificationsModal" 
        aria-label="Open notifications" title="View notifications">
        <i class="fa-regular fa-bell fs-5"></i>
        <?php if ($unreadCount > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill notification-badge">
                <?php echo $unreadCount; ?>
            </span>
        <?php endif; ?>
    </button>
</div>

<!-- Notifications Modal -->
<div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-1g">
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
                <!-- Section: Today -->
                <div class="px-4 py-3 section-divider">
                    <div class="small text-muted">Today</div>
                </div>

                <div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($todayNotifications as $note): ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between py-3 <?php echo !$note['read'] ? 'bg-light-unread unread-hover' : ''; ?>">
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
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="toggle_read">
                                            <input type="hidden" name="notification_id" value="<?php echo $note['id']; ?>">
                                            <button class="btn btn-sm <?php echo $note['read'] ? 'btn-outline-secondary' : 'btn-outline-success'; ?>" 
                                                    title="<?php echo $note['read'] ? 'Mark as unread' : 'Mark as read'; ?>">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>

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

                <!-- Divider -->
                <div class="px-4 py-3 section-divider earlier-section">
                    <div class="small text-muted">Earlier</div>
                </div>

                <!-- Earlier list -->
                <div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($earlierNotifications as $note): ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between py-3 <?php echo !$note['read'] ? 'bg-light-unread unread-hover' : ''; ?>">
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
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="toggle_read">
                                            <input type="hidden" name="notification_id" value="<?php echo $note['id']; ?>">
                                            <button class="btn btn-sm <?php echo $note['read'] ? 'btn-outline-secondary' : 'btn-outline-success'; ?>" 
                                                    title="<?php echo $note['read'] ? 'Mark as unread' : 'Mark as read'; ?>">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>

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
/* Notification Styles */
.notification-badge {
    background-color: #2eb28a; 
    color: #fff; 
    padding: 0.3em 0.45em; 
    font-size: 0.75rem;
}

.bg-light-unread {
    background-color: #f1f2f3 !important;
}

.unread-hover:hover {
    background-color: #dedede !important;
    transition: 0.2s;
}

.avatar-bell {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background-color: #2eb28a;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
}

.chat-box {
    border: 1px solid #f1f2f3;
    border-radius: 8px;
    padding: 1rem;
    background-color: #fafafa;
    max-height: 300px;
    overflow: auto;
}

.modal-header-custom {
    border-bottom: 1px solid #f1f2f3;
}

.section-divider {
    border-bottom: 1px solid #f1f2f3;
}

.earlier-section {
    border-top: 1px solid #f1f2f3;
}

.btn-icon {
    border: 1px solid #f1f2f3; 
    background: transparent;
}

.btn-close-icon {
    border: none; 
    background: transparent; 
    color: #000;
}

/* Button adjustments for notification button */
.btn-light {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.btn-light:hover {
    background-color: #e2e6ea;
    border-color: #dae0e5;
}
</style>

<script>
function setReplyEmployee(employee, message) {
    document.getElementById('replyEmployeeName').textContent = employee;
    document.getElementById('replyEmployee').value = employee;
    document.getElementById('originalMessage').textContent = message;
}
</script>