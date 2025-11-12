<?php
require_once __DIR__ . '/../../includes/db.php';

function getNotificationsWithEmployees($conn) {
    $sql = "SELECT 
                nr.notification_id,
                nr.title,
                nr.message,
                nr.date_created,
                nr.is_read,
                nr.is_broadcast,
                CASE 
                    WHEN nr.is_broadcast = 1 THEN 'All Employees'
                    ELSE CONCAT(e.first_name, ' ', e.last_name)
                END AS employee_name,
                e.email
            FROM notifications_records nr
            LEFT JOIN employees e ON nr.employee_id = e.employee_id
            ORDER BY nr.date_created DESC, nr.notification_id DESC";
    
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        error_log("Database query failed: " . mysqli_error($conn));
        return array('error' => 'Database query failed: ' . mysqli_error($conn));
    }
    
    $notifications = array();
    
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $notifications[] = $row;
        }
    }
    
    return $notifications;
}

function postNotification($conn, $title, $message, $employee_id) {
    $stmt = $conn->prepare("INSERT INTO notifications_records (title, message, employee_id, date_created) VALUES (?, ?, ?, NOW())");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        return array('error' => 'Prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param("ssi", $title, $message, $employee_id);
    
    if ($stmt->execute()) {
        return array('success' => 'Notification posted successfully.');
    } else {
        error_log("Execute failed: " . $stmt->error);
        return array('error' => 'Execute failed: ' . $stmt->error);
    }
}

function postGlobalNotification($conn, $title, $message) {
    $stmt = $conn->prepare("INSERT INTO notifications_records (title, message, employee_id, date_created, is_broadcast) VALUES (?, ?, NULL, NOW(), 1)");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        return array('error' => 'Prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param("ss", $title, $message);
    
    if ($stmt->execute()) {
        return array('success' => 'Global notification posted successfully.');
    } else {
        error_log("Execute failed: " . $stmt->error);
        return array('error' => 'Execute failed: ' . $stmt->error);
    }
}

function toggleNotificationRead($conn, $notification_id) {
    // First get current read status
    $stmt = $conn->prepare("SELECT is_read FROM notifications_records WHERE notification_id = ?");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        return array('error' => 'Prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $notification_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    if (!$row) {
        return array('error' => 'Notification not found');
    }
    
    // Toggle the read status
    $newStatus = $row['is_read'] ? 0 : 1;
    
    $stmt = $conn->prepare("UPDATE notifications_records SET is_read = ? WHERE notification_id = ?");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        return array('error' => 'Prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param("ii", $newStatus, $notification_id);
    
    if ($stmt->execute()) {
        $message = $newStatus ? 'Notification marked as read.' : 'Notification marked as unread.';
        return array('success' => $message);
    } else {
        error_log("Execute failed: " . $stmt->error);
        return array('error' => 'Execute failed: ' . $stmt->error);
    }
}
