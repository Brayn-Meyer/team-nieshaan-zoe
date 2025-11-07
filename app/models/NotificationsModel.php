<?php
require_once __DIR__ . '/../../includes/db.php';

function getNotificationsWithEmployees($conn) {
    $sql = "SELECT 
                nr.notification_id,
                nr.title,
                nr.message,
                nr.date_created,
                CONCAT(e.first_name, ' ', e.last_name) AS employee_name,
                e.email
            FROM notifications_records nr
            JOIN employees e ON nr.employee_id = e.employee_id
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