<?php
/**
 * Employee Controller
 * Handles employee CRUD operations
 */

require_once __DIR__ . '/../models/EmployeeModel.php';

class EmployeeController {
    
    public static function handleRequest($action, $method) {
        switch ($action) {
            case 'getEmployees':
                if ($method === 'GET') {
                    self::getEmployees();
                } else {
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                }
                break;
                
            case 'addEmployee':
                if ($method === 'POST') {
                    self::addEmployee();
                } else {
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                }
                break;
                
            case 'updateEmployee':
                if ($method === 'PUT') {
                    self::updateEmployee();
                } else {
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                }
                break;
                
            case 'deleteEmployee':
                if ($method === 'DELETE') {
                    self::deleteEmployee();
                } else {
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                }
                break;
                
            case 'getRoles':
                if ($method === 'GET') {
                    self::getRoles();
                } else {
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                }
                break;
                
            case 'getDepartments':
                if ($method === 'GET') {
                    self::getDepartments();
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
    
    private static function getEmployees() {
        try {
            // Get pagination parameters
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $limit = isset($_GET['limit']) ? max(1, min(100, intval($_GET['limit']))) : 10;
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            
            $result = EmployeeModel::getAllEmployees($page, $limit, $search);
            $employees = $result['employees'];
            $totalEmployees = $result['total'];
            $totalPages = ceil($totalEmployees / $limit);
            
            // Map to frontend expected format
            $formattedEmployees = array_map(function($emp) {
                // The getAllEmployees query returns a summary per employee with latest clock-in/out
                return [
                    'id' => $emp['employee_id'],
                    'name' => trim(($emp['first_name'] ?? '') . ' ' . ($emp['last_name'] ?? '')),
                    'employeeId' => $emp['employee_id'],
                    // 'firstName' => $emp['first_name'] ?? '',
                    // 'lastName' => $emp['last_name'] ?? '',
                    // role/department come from emp_classification
                    'roles' => $emp['role'] ?? 'Employee',
                    'department' => $emp['department'] ?? 'General',
                    // Time log summary fields
                    'workDate' => $emp['work_date'] ?? null,
                    'lastClockIn' => $emp['last_clockin_time'] ?? null,
                    'lastClockOut' => $emp['last_clockout_time'] ?? null,
                ];
            }, $employees);
            
            echo json_encode([
                'employees' => $formattedEmployees,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'total_employees' => $totalEmployees,
                    'per_page' => $limit
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    private static function addEmployee() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = EmployeeModel::addEmployee($data);
            echo json_encode($result);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    private static function updateEmployee() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $employeeId = $data['employee_id'] ?? null;
            
            if (!$employeeId) {
                http_response_code(400);
                echo json_encode(['error' => 'Employee ID is required']);
                return;
            }
            
            $result = EmployeeModel::updateEmployee($employeeId, $data);
            echo json_encode($result);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    private static function deleteEmployee() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $employeeId = $data['employee_id'] ?? $_GET['employee_id'] ?? null;
            
            if (!$employeeId) {
                http_response_code(400);
                echo json_encode(['error' => 'Employee ID is required']);
                return;
            }
            
            $result = EmployeeModel::deleteEmployee($employeeId);
            echo json_encode($result);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    private static function getRoles() {
        try {
            $roles = EmployeeModel::getRoles();
            echo json_encode(['roles' => $roles]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    private static function getDepartments() {
        try {
            $departments = EmployeeModel::getDepartments();
            echo json_encode(['departments' => $departments]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
