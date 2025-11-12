<?php
/**
 * Employee Model
 * Handles all employee CRUD operations
 */

require_once __DIR__ . '/../../includes/db.php';

class EmployeeModel {

    /**
     * Get all employees with their classification data
     */
    public static function getAllEmployees() {
        try {
            $query = "
                SELECT 
                    e.employee_id,
                    e.first_name,
                    e.last_name,
                    c.role,
                    c.department,
                    DATE(rb.clockin_time) AS work_date,
                    MAX(rb.clockin_time) AS last_clockin_time,
                    MAX(rb.clockout_time) AS last_clockout_time
                FROM employees e
                LEFT JOIN emp_classification c 
                    ON e.classification_id = c.classification_id
                LEFT JOIN record_backups rb 
                    ON rb.employee_id = e.employee_id
                GROUP BY 
                    e.employee_id, 
                    e.first_name, 
                    e.last_name, 
                    c.role, 
                    c.department, 
                    DATE(rb.clockin_time)
                ORDER BY e.employee_id;
            ";
            return db()->query($query);
        } catch (Exception $e) {
            error_log("Error in getAllEmployees: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get employee by ID
     */
    public static function getEmployeeById($employeeId) {
        try {
            $query = "
                SELECT 
                    e.*,
                    ec.department,
                    ec.position,
                    ec.role,
                    ec.employment_type,
                    ec.employee_level
                FROM employees e
                LEFT JOIN emp_classification ec ON e.classification_id = ec.classification_id
                WHERE e.employee_id = ?
            ";
            $result = db()->query($query, [$employeeId], 'i');
            return $result[0] ?? null;
        } catch (Exception $e) {
            error_log("Error in getEmployeeById: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create or find classification
     */
    private static function createOrFindClassification($department, $role) {
        try {
            // Check if classification exists
            $query = "SELECT classification_id FROM emp_classification 
                      WHERE department = ? AND role = ?";
            $result = db()->query($query, [$department, $role], 'ss');
            
            if (!empty($result)) {
                return $result[0]['classification_id'];
            }

            // Create new classification
            $insertQuery = "INSERT INTO emp_classification 
                           (department, position, role, employment_type, employee_level)
                           VALUES (?, ?, ?, 'Full-time', 'Junior')";
            $classificationId = db()->execute($insertQuery, [$department, $role, $role], 'sss');
            
            return $classificationId;
        } catch (Exception $e) {
            error_log("Error in createOrFindClassification: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Add new employee
     */
    public static function addEmployee($data) {
        try {
            // Create or find classification
            $classificationId = null;
            if (!empty($data['department']) && !empty($data['roles'])) {
                $classificationId = self::createOrFindClassification($data['department'], $data['roles']);
            }
            
            if (!$classificationId) {
                $classificationId = 1; // Default classification
            }

            // Map status
            $statusMap = [
                'on-site' => 'Active',
                'home' => 'Active',
                'Active' => 'Active',
                'Inactive' => 'Inactive',
                'OnLeave' => 'OnLeave',
                'Terminated' => 'Terminated'
            ];
            $employmentStatus = $statusMap[$data['status']] ?? 'Active';

            // Prepare data
            $isAdmin = ($data['user_type'] ?? 'Employee') === 'Admin' ? 1 : 0;
            $contactNo = !empty($data['contact_no']) ? substr($data['contact_no'], 0, 10) : null;
            $dateHired = !empty($data['date_hired']) ? $data['date_hired'] : date('Y-m-d');
            
            // Validate required fields
            if (empty($data['first_name']) || empty($data['last_name']) || 
                empty($data['email']) || empty($data['address']) || empty($data['id'])) {
                throw new Exception('Missing required fields');
            }

            $query = "INSERT INTO employees (
                first_name, last_name, contact_no, email, address, id, 
                is_admin, date_hired, supervisor_name, leave_balance, classification_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $params = [
                $data['first_name'],
                $data['last_name'],
                $contactNo,
                $data['email'],
                $data['address'],
                $data['id'], // National ID
                $isAdmin,
                $dateHired,
                $data['supervisor_name'] ?? null,
                $data['leave_balance'] ?? 0,
                $classificationId
            ];
            
            $employeeId = db()->execute($query, $params, 'ssssssissii');
            
            return [
                'success' => true,
                'employee_id' => $employeeId,
                'message' => 'Employee added successfully'
            ];
        } catch (Exception $e) {
            error_log("Error in addEmployee: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update employee
     */
    public static function updateEmployee($employeeId, $data) {
        try {
            // Create or find classification if provided
            $classificationId = null;
            if (!empty($data['department']) && !empty($data['roles'])) {
                $classificationId = self::createOrFindClassification($data['department'], $data['roles']);
            }

            // Map status
            $statusMap = [
                'on-site' => 'Active',
                'home' => 'Active',
                'Active' => 'Active',
                'Inactive' => 'Inactive',
                'OnLeave' => 'OnLeave',
                'Terminated' => 'Terminated'
            ];
            $employmentStatus = $statusMap[$data['status']] ?? 'Active';

            $isAdmin = ($data['user_type'] ?? 'Employee') === 'Admin' ? 1 : 0;
            $contactNo = !empty($data['contact_no']) ? substr($data['contact_no'], 0, 10) : null;
            
            $query = "UPDATE employees SET 
                first_name = ?, 
                last_name = ?, 
                contact_no = ?, 
                email = ?, 
                address = ?, 
                id = ?,
                is_admin = ?, 
                date_hired = ?, 
                supervisor_name = ?, 
                leave_balance = ?";
            
            $params = [
                $data['first_name'],
                $data['last_name'],
                $contactNo,
                $data['email'],
                $data['address'],
                $data['id'],
                $isAdmin,
                $data['date_hired'],
                $data['supervisor_name'] ?? null,
                $data['leave_balance'] ?? 0
            ];
            
            $types = 'ssssssissi';
            
            if ($classificationId) {
                $query .= ", classification_id = ?";
                $params[] = $classificationId;
                $types .= 'i';
            }
            
            $query .= " WHERE employee_id = ?";
            $params[] = $employeeId;
            $types .= 'i';
            
            $success = db()->execute($query, $params, $types);
            
            return [
                'success' => $success,
                'message' => $success ? 'Employee updated successfully' : 'No changes made'
            ];
        } catch (Exception $e) {
            error_log("Error in updateEmployee: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete employee
     */
    public static function deleteEmployee($employeeId) {
        try {
            $query = "DELETE e, r, n, no, a, h
                FROM employees e
                LEFT JOIN record_backups r ON e.employee_id = r.employee_id
                LEFT JOIN nfctag_storage n ON e.employee_id = n.employee_id
                LEFT JOIN notifications_records no ON e.employee_id = no.employee_id
                LEFT JOIN account_auth a ON e.employee_id = a.employee_id
                LEFT JOIN hours_management h ON e.employee_id = h.employee_id
                WHERE e.employee_id = ?;";
            $success = db()->execute($query, [$employeeId], 'i');
            
            return [
                'success' => $success,
                'message' => $success ? 'Employee deleted successfully' : 'Employee not found'
            ];
        } catch (Exception $e) {
            error_log("Error in deleteEmployee: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get distinct roles
     */
    public static function getRoles() {
        try {
            $query = "SELECT DISTINCT role FROM emp_classification ORDER BY role";
            $result = db()->query($query);
            return array_column($result, 'role');
        } catch (Exception $e) {
            error_log("Error in getRoles: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get distinct departments
     */
    public static function getDepartments() {
        try {
            $query = "SELECT DISTINCT department FROM emp_classification ORDER BY department";
            $result = db()->query($query);
            return array_column($result, 'department');
        } catch (Exception $e) {
            error_log("Error in getDepartments: " . $e->getMessage());
            return [];
        }
    }
}
