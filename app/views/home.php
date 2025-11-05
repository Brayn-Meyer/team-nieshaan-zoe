<?php
require_once __DIR__ . '/../../includes/config.php';

$pageTitle = 'Dashboard - Clock It';
$currentPage = 'home';
$additionalJS = ['/assets/js/dashboard.js'];

require_once __DIR__ . '/../../includes/header.php';

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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
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
    }
    // Redirect to avoid form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
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

<!-- Help Button -->
<button onclick="showUserGuide()" class="help-btn">
    <i class="fa-solid fa-circle-question"></i>
    Help Guide
</button>

<!-- Dashboard Container -->
<div class="dashboard-container">
    <main class="dashboard-main" id="kpiCards">
        <!-- KPI Cards will be loaded here dynamically -->
        <div class="card">
            <div class="card-content">
                <i class="fa-solid fa-users card-icon"></i>
                <div>
                    <div class="card-title">Total Employees</div>
                    <div class="card-value" id="totalEmployees">0</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-content">
                <i class="fa-solid fa-door-open card-icon"></i>
                <div>
                    <div class="card-title">Clock In</div>
                    <div class="card-value" id="checkedIn">0</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-content">
                <i class="fa-solid fa-door-closed card-icon"></i>
                <div>
                    <div class="card-title">Clock Out</div>
                    <div class="card-value" id="checkedOut">0</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-content">
                <i class="fa-solid fa-user-xmark card-icon"></i>
                <div>
                    <div class="card-title">Absent</div>
                    <div class="card-value" id="absent">0</div>
                </div>
            </div>
        </div>
    </main>

    <br><br>

    <!-- Search and Actions Container -->
    <div class="search-add-container">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <div style="display: flex; gap: 10px;">
                    <button class="btn btn-primary small-btn" onclick="window.location.href='history.php'">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        History
                    </button>
                    <button class="btn btn-primary small-btn" onclick="window.location.href='timeLog.php'">
                        <i class="fa-solid fa-calendar"></i>
                        Time Log
                    </button>
                    
                    <!-- Notification Button -->
                    <div class="d-inline-block">
                        <button class="btn btn-light position-relative" data-bs-toggle="modal" data-bs-target="#notificationsModal" 
                            aria-label="Open notifications" title="View notifications">
                            <i class="fa-regular fa-bell fs-5"></i>
                            <?php if ($unreadCount > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" 
                                      style="background-color: #2eb28a; color: #fff; padding: 0.3em 0.45em; font-size: 0.75rem;">
                                    <?php echo $unreadCount; ?>
                                </span>
                            <?php endif; ?>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="search-container">
                    <input 
                        type="text" 
                        class="form-control search-input" 
                        placeholder="Search employees..." 
                        id="searchInput"
                        oninput="filterEmployees()"
                    >
                </div>
            </div>
            
            <div class="col-auto">
                <button class="btn btn-primary small-btn" onclick="openAddEmployeeModal()">
                    <i class="fa-solid fa-plus"></i>
                    Add Employee
                </button>
            </div>
        </div>
    </div>

    <!-- Desktop Table -->
    <div class="d-none d-md-block">
        <div class="table-responsive">
            <table class="employee-table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Employee ID</th>
                        <th scope="col">Department</th>
                        <th scope="col">Roles</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="employeeTableBody">
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            <i class="fa-solid fa-spinner fa-spin"></i> Loading employees...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards -->
    <div class="d-md-none mobile-employees-container" id="mobileEmployees">
        <div class="text-center text-muted py-4">
            <i class="fa-solid fa-spinner fa-spin"></i> Loading employees...
        </div>
    </div>

    <!-- Notifications Modal -->
    <div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-3">
                <div class="modal-header d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #f1f2f3">
                    <h5 class="modal-title mb-0 fw-semibold" id="notificationsModalLabel">Notifications</h5>
                    <div class="d-flex align-items-center gap-2 ms-auto">
                        <button class="btn btn-sm d-flex align-items-center justify-content-center" 
                            style="border: 1px solid #f1f2f3; background: transparent;" 
                            data-bs-toggle="modal" data-bs-target="#groupChatModal"
                            aria-label="Open group chat" title="Open group chat">
                            <i class="fa-solid fa-comments"></i>
                        </button>
                        <button type="button" class="btn d-flex align-items-center justify-content-center" 
                            style="border: none; background: transparent; color: #000;" 
                            data-bs-dismiss="modal" aria-label="Close" title="Close modal">
                            <i class="fa-solid fa-xmark fs-5"></i>
                        </button>
                    </div>
                </div>

                <div class="modal-body p-0">
                    <!-- Section: Today -->
                    <div class="px-4 py-3" style="border-bottom: 1px solid #f1f2f3">
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
                    <div class="px-4 py-3" style="border-top: 1px solid #f1f2f3; border-bottom: 1px solid #f1f2f3">
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
</div>

<!-- Add Employee Modal -->
<div class="modal" id="addEmployeeModal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Employee</h5>
                <button type="button" class="btn-close" onclick="closeAddEmployeeModal()"></button>
            </div>
            <div class="modal-body">
                <form id="addEmployeeForm" onsubmit="addEmployee(event)">
                    <div class="row g-3">
                        <!-- Personal Information -->
                        <div class="col-12">
                            <h6 class="section-title">Personal Information</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="firstName" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="lastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lastName" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="idNumber" class="form-label">ID Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="idNumber" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="contactNo" class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="contactNo" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address" required>
                        </div>
                        
                        <!-- Employment Information -->
                        <div class="col-12">
                            <h6 class="section-title">Employment Information</h6>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="department" class="form-label">Department</label>
                            <select class="form-select" id="department"></select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role"></select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="userType" class="form-label">User Type</label>
                            <select class="form-select" id="userType">
                                <option value="Employee">Employee</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="dateHired" class="form-label">Date Hired</label>
                            <input type="date" class="form-control" id="dateHired">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="supervisorName" class="form-label">Supervisor Name</label>
                            <input type="text" class="form-control" id="supervisorName">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="leaveBalance" class="form-label">Leave Balance</label>
                            <input type="number" class="form-control" id="leaveBalance" value="0" min="0">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="OnLeave">On Leave</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeAddEmployeeModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="addEmployeeBtn">Add Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal" id="editEmployeeModal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Employee</h5>
                <button type="button" class="btn-close" onclick="closeEditEmployeeModal()"></button>
            </div>
            <div class="modal-body">
                <form id="editEmployeeForm" onsubmit="updateEmployee(event)">
                    <input type="hidden" id="editEmployeeId">
                    <!-- Same fields as add form -->
                    <div class="row g-3">
                        <div class="col-12"><h6 class="section-title">Personal Information</h6></div>
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editFirstName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editLastName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ID Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editIdNumber" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="editContactNo" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="editEmail" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editAddress" required>
                        </div>
                        
                        <div class="col-12"><h6 class="section-title">Employment Information</h6></div>
                        <div class="col-md-4">
                            <label class="form-label">Department</label>
                            <select class="form-select" id="editDepartment"></select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Role</label>
                            <select class="form-select" id="editRole"></select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">User Type</label>
                            <select class="form-select" id="editUserType">
                                <option value="Employee">Employee</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date Hired</label>
                            <input type="date" class="form-control" id="editDateHired">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Supervisor Name</label>
                            <input type="text" class="form-control" id="editSupervisorName">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Leave Balance</label>
                            <input type="number" class="form-control" id="editLeaveBalance" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="editStatus">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="OnLeave">On Leave</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeEditEmployeeModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updateEmployeeBtn">Update Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    margin-top: 30px;
    background: #f8fafc;
    min-height: 100vh;
    padding: 120px 60px 50px;
}

/* Notification Styles */
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

/* Button adjustments for notification button */
.btn-light {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.btn-light:hover {
    background-color: #e2e6ea;
    border-color: #dae0e5;
}

/* Responsive Utilities */
.d-none {
    display: none !important;
}

.d-md-none {
    display: block !important;
}

.d-md-block {
    display: none !important;
}

@media (min-width: 768px) {
    .d-md-none {
        display: none !important;
    }
    
    .d-md-block {
        display: block !important;
    }
}

.col-auto {
    flex: 0 0 auto;
    width: auto;
}

.col {
    flex: 1 1 0%;
}

.align-items-center {
    align-items: center !important;
}
</style>

<script>
// JavaScript function for notification replies
function setReplyEmployee(employee, message) {
    document.getElementById('replyEmployeeName').textContent = employee;
    document.getElementById('replyEmployee').value = employee;
    document.getElementById('originalMessage').textContent = message;
}

// You can add other existing functions here if needed
function showUserGuide() {
    // Your existing showUserGuide function
    alert('User guide will be shown here');
}

function filterEmployees() {
    // Your existing filterEmployees function
    console.log('Filtering employees...');
}

function openAddEmployeeModal() {
    // Your existing openAddEmployeeModal function
    console.log('Opening add employee modal...');
}

function closeAddEmployeeModal() {
    // Your existing closeAddEmployeeModal function
    console.log('Closing add employee modal...');
}

function addEmployee(event) {
    // Your existing addEmployee function
    event.preventDefault();
    console.log('Adding employee...');
}

function closeEditEmployeeModal() {
    // Your existing closeEditEmployeeModal function
    console.log('Closing edit employee modal...');
}

function updateEmployee(event) {
    // Your existing updateEmployee function
    event.preventDefault();
    console.log('Updating employee...');
}
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>