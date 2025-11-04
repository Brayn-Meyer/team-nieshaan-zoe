<?php
/**
 * History Controller
 * Handles attendance history operations
 */

require_once __DIR__ . '/../models/ClockInOutModel.php';

class HistoryController {
    
    public static function handleRequest($action, $method) {
        switch ($action) {
            case 'getAllRecords':
                if ($method === 'GET') {
                    self::getAllRecords();
                } else {
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                }
                break;
                
            default:
                http_response_code(404);
                echo json_encode(['error' => 'Action not found']);
                break;
        }
    }
    
    private static function getAllRecords() {
        try {
            $records = ClockInOutModel::getAllRecords();
            echo json_encode(['records' => $records]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
