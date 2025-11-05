<?php
// api/notifications.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

header('Content-Type: application/json');

function getNotifications($unreadOnly = false, $limit = 5) {
    global $pdo;
    
    try {
        $sql = "SELECT nr.notification_id, nr.title, nr.message, nr.date_created, nr.is_read,
                       e.first_name, e.last_name
                FROM notifications_records nr
                LEFT JOIN employees e ON nr.employee_id = e.employee_id
                WHERE 1=1";
        
        $params = [];
        
        if ($unreadOnly) {
            $sql .= " AND nr.is_read = 0";
        }
        
        $sql .= " ORDER BY nr.date_created DESC LIMIT ?";
        $params[] = $limit;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'success' => true,
            'notifications' => $notifications
        ];
        
    } catch (PDOException $e) {
        return [
            'success' => false,
            'error' => 'Database error: ' . $e->getMessage()
        ];
    }
}

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $unreadOnly = isset($_GET['unread_only']) && $_GET['unread_only'] === 'true';
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
    
    $result = getNotifications($unreadOnly, $limit);
    echo json_encode($result);
    exit;
}
?>