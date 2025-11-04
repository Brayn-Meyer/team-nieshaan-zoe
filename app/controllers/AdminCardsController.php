<?php
/**
 * Admin Cards Controller
 * Handles KPI card data requests
 */

require_once __DIR__ . '/../models/AdminCardsModel.php';

class AdminCardsController {
    
    public static function handleRequest($action, $method) {
        switch ($action) {
            case 'allKpiData':
                if ($method === 'GET') {
                    self::getAllKpiData();
                } else {
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                }
                break;
                
            case 'total':
                if ($method === 'GET') {
                    self::getTotalEmployees();
                } else {
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                }
                break;
                
            case 'checkedIn':
                if ($method === 'GET') {
                    self::getTotalCheckedIn();
                } else {
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                }
                break;
                
            case 'checkedOut':
                if ($method === 'GET') {
                    self::getTotalCheckedOut();
                } else {
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                }
                break;
                
            case 'absent':
                if ($method === 'GET') {
                    self::getTotalAbsent();
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
    
    private static function getAllKpiData() {
        try {
            $data = AdminCardsModel::getAllKpiData();
            echo json_encode($data);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    private static function getTotalEmployees() {
        try {
            $total = AdminCardsModel::getTotalEmployees();
            echo json_encode(['total' => $total]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    private static function getTotalCheckedIn() {
        try {
            $checkedIn = AdminCardsModel::getTotalCheckedIn();
            echo json_encode(['checkedIn' => $checkedIn]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    private static function getTotalCheckedOut() {
        try {
            $checkedOut = AdminCardsModel::getTotalCheckedOut();
            echo json_encode(['checkedOut' => $checkedOut]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    private static function getTotalAbsent() {
        try {
            $absent = AdminCardsModel::getTotalAbsent();
            echo json_encode(['absent' => $absent]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
