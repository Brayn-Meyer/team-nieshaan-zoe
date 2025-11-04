<?php
/**
 * ClockInOut Controller
 * Handles attendance and time log operations
 */

require_once __DIR__ . '/../models/ClockInOutModel.php';

class ClockInOutController {
    
    public static function handleRequest($action, $method) {
        switch ($action) {
            case 'clockInOut':
                if ($method === 'GET') {
                    self::getClockInOutData();
                } else {
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                }
                break;
                
            case 'getTimeLogData':
                if ($method === 'GET') {
                    self::getTimeLogData();
                } else {
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
                }
                break;
                
            case 'createRecord':
                if ($method === 'POST') {
                    self::createHoursRecord();
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
    
    private static function getClockInOutData() {
        try {
            $rawEmployees = ClockInOutModel::getClockInOutData();
            
            // Map to frontend expected format
            $employees = array_map(function($emp) {
                return [
                    'id' => $emp['employee_id'],
                    'name' => trim($emp['first_name'] . ' ' . $emp['last_name']),
                    'employeeId' => $emp['employee_id'],
                    'classificationId' => $emp['classification_id'],
                    'firstName' => $emp['first_name'],
                    'lastName' => $emp['last_name'],
                    'contactNo' => $emp['contact_no'],
                    'email' => $emp['email'],
                    'address' => $emp['address'],
                    'idNumber' => $emp['id'],
                    'userType' => $emp['is_admin'] ? 'Admin' : 'Employee',
                    'dateHired' => $emp['date_hired'],
                    'supervisorName' => $emp['supervisor_name'],
                    'leaveBalance' => $emp['leave_balance'],
                    'username' => $emp['username'],
                    'roles' => $emp['role'] ?? 'Employee',
                    'department' => $emp['department'] ?? 'General',
                    'status' => $emp['employment_status'] ?? 'Active'
                ];
            }, $rawEmployees);
            
            echo json_encode([
                'clock_in_out_data' => $rawEmployees,
                'employees' => $employees
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    private static function getTimeLogData() {
        try {
            $week = $_GET['week'] ?? null;
            
            if (!$week) {
                // Get current week Monday
                $week = date('Y-m-d', strtotime('monday this week'));
            }
            
            // Get hours worked for the specified week
            $hoursWorkedData = ClockInOutModel::getHoursWorked($week);
            
            if (empty($hoursWorkedData)) {
                echo json_encode(['timeLogData' => []]);
                return;
            }
            
            $weekStart = $hoursWorkedData[0]['week_start'];
            
            // Batch fetch existing hours_management records
            $existingRecords = HoursManagement::getByWeek($weekStart);
            
            // Create map for O(1) lookup
            $existingRecordsMap = [];
            foreach ($existingRecords as $record) {
                $existingRecordsMap[$record['employee_id']] = $record;
            }
            
            $expectedHours = 40;
            
            // Process each employee's weekly data
            $timeLogData = array_map(function($record) use ($existingRecordsMap, $expectedHours) {
                $employeeId = $record['employee_id'];
                $employeeName = $record['employee_name'];
                $weekStart = $record['week_start'];
                $weekEnd = $record['week_end'];
                $totalHours = floatval($record['total_hours']);
                
                $existing = $existingRecordsMap[$employeeId] ?? null;
                
                $hoursOwed = 0;
                $overtime = 0;
                $indicator = 'green';
                $isSaved = false;
                
                if ($existing) {
                    // Use saved data
                    $hoursOwed = floatval($existing['hours_owed']);
                    $overtime = floatval($existing['overtime']);
                    // Set indicator based on hours owed
                    $indicator = ($hoursOwed > 0) ? 'red' : 'green';
                    $isSaved = true;
                } else {
                    // Calculate on the fly
                    if ($totalHours < $expectedHours) {
                        $hoursOwed = $expectedHours - $totalHours;
                        $indicator = 'red';
                    } elseif ($totalHours > $expectedHours) {
                        $overtime = $totalHours - $expectedHours;
                        $indicator = 'green';
                    } else {
                        // Exactly 40 hours
                        $indicator = 'green';
                    }
                }
                
                return [
                    'id' => $employeeId,
                    'name' => $employeeName,
                    'hoursWorked' => round($totalHours, 2),
                    'hoursOwed' => round($hoursOwed, 2),
                    'overtime' => round($overtime, 2),
                    'indicator' => $indicator,
                    'week_start' => $weekStart,
                    'week_end' => $weekEnd,
                    'expected_hours' => $expectedHours,
                    'is_saved' => $isSaved
                ];
            }, $hoursWorkedData);
            
            echo json_encode(['timeLogData' => $timeLogData]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    private static function createHoursRecord() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $employeeId = $data['employee_id'] ?? null;
            $weekStart = $data['week_start'] ?? null;
            $weekEnd = $data['week_end'] ?? null;
            $expectedHours = $data['expected_hours'] ?? 40;
            $totalWorkedHours = $data['total_worked_hours'] ?? 0;
            
            if (!$employeeId || !$weekStart || !$weekEnd) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing required fields']);
                return;
            }
            
            // Check if record exists
            if (HoursManagement::recordExists($employeeId, $weekStart)) {
                http_response_code(409);
                echo json_encode(['error' => 'Record already exists for this week']);
                return;
            }
            
            $result = HoursManagement::createRecord(
                $employeeId,
                $weekStart,
                $weekEnd,
                $expectedHours,
                $totalWorkedHours
            );
            
            echo json_encode(['success' => true, 'data' => $result]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
