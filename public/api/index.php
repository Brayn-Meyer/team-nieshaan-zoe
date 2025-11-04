<?php
/**
 * API Router
 * Routes API requests to appropriate controllers
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';

// Set headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get request URI and method
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Parse the URI
$uri = parse_url($requestUri, PHP_URL_PATH);
$uri = str_replace(BASE_URL . '/api', '', $uri);
$uriParts = explode('/', trim($uri, '/'));

// Route the request
try {
    $controller = $uriParts[0] ?? '';
    $action = $uriParts[1] ?? '';
    
    switch ($controller) {
        case 'admin':
            require_once __DIR__ . '/../../app/controllers/AdminCardsController.php';
            AdminCardsController::handleRequest($action, $requestMethod);
            break;
            
        case 'employees':
            require_once __DIR__ . '/../../app/controllers/EmployeeController.php';
            EmployeeController::handleRequest($action, $requestMethod);
            break;
            
        case 'clock-in-out':
            require_once __DIR__ . '/../../app/controllers/ClockInOutController.php';
            ClockInOutController::handleRequest($action, $requestMethod);
            break;
            
        case 'history':
            require_once __DIR__ . '/../../app/controllers/HistoryController.php';
            HistoryController::handleRequest($action, $requestMethod);
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal server error',
        'message' => $e->getMessage()
    ]);
}
