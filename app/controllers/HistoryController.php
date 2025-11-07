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
            error_log("HistoryController::getAllRecords - Starting");
            
            $records = ClockInOutModel::getAllRecords();
            error_log("Records retrieved from model: " . ($records ? 'yes' : 'no'));
            error_log("Records count: " . count($records));
            
            if (!empty($records)) {
                error_log("Sample record: " . print_r($records[0], true));
            } else {
                error_log("No records found in database");
            }
            
            $response = json_encode(['records' => $records]);
            error_log("Response JSON: " . $response);
            
            echo $response;
        } catch (Exception $e) {
            error_log("HistoryController Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
