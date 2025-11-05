<?php
/**
 * ClockInOut Model
 * Handles attendance records and time log calculations
 */

require_once __DIR__ . '/../../includes/db.php';

class ClockInOutModel {

    /**
     * Get all clock in/out data with employee classification
     */
    public static function getClockInOutData() {
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
            ";
            return db()->query($query);
        } catch (Exception $e) {
            error_log("Error in getClockInOutData: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all attendance records
     */
    public static function getAllRecords() {
        try {
            $query = "SELECT 
                        e.employee_id,
                        e.first_name,
                        e.last_name,
                        c.role,
                        c.department,
                        DATE(rb.clockin_time) AS work_date,
                        rb.clockin_time AS last_clockin_time,
                        rb.clockout_time AS last_clockout_time
                    FROM employees e
                    JOIN emp_classification c ON e.classification_id = c.classification_id
                    JOIN record_backups rb 
                        ON rb.employee_id = e.employee_id
                    WHERE rb.clockin_time = (
                        SELECT MAX(rb2.clockin_time)
                        FROM record_backups rb2
                        WHERE rb2.employee_id = e.employee_id
                    );
                ";
            return db()->query($query);
        } catch (Exception $e) {
            error_log("Error in getAllRecords: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get hours worked for a specific week or all weeks
     */
    public static function getHoursWorked($weekStart = null) {
        try {
            // record_backups table stores clockin_time/clockout_time as datetimes.
            // Some deployments don't have separate `type`, `status`, or `date` columns
            // so use the datetime fields and DATE()/TIME() helpers instead.
            $query = "
                SELECT
                    e.employee_id,
                    CONCAT(e.first_name, ' ', e.last_name) AS employee_name,
                    ROUND(SUM(
                        GREATEST(
                            0,
                            TIMESTAMPDIFF(
                                MINUTE,
                                CASE
                                    WHEN TIME(r.clockin_time) < '08:30:00' THEN TIMESTAMP(DATE(r.clockin_time), '08:30:00')
                                    ELSE r.clockin_time
                                END,
                                r.clockout_time
                            )
                        )
                    ) / 60.0) AS total_hours
                FROM record_backups r
                JOIN employees e ON e.employee_id = r.employee_id
                WHERE r.clockin_time IS NOT NULL
                AND r.clockout_time IS NOT NULL
                AND DAYOFWEEK(r.clockin_time) NOT IN (1, 7)
            ";

            $params = [];
            $types = '';
            
            if ($weekStart) {
                // Filter by the date portion of clockin_time
                $query .= " AND DATE(r.clockin_time) >= ? AND DATE(r.clockin_time) <= DATE_ADD(?, INTERVAL 6 DAY)";
                $params = [$weekStart, $weekStart];
                $types = 'ss';
            }

            $query .= "
                GROUP BY e.employee_id, e.first_name, e.last_name
                ORDER BY e.first_name, e.last_name
            ";

            $result = db()->query($query, $params, $types);
            
            // Add week_start and week_end to each row
            if ($weekStart) {
                $weekEnd = date('Y-m-d', strtotime($weekStart . ' +6 days'));
                foreach ($result as &$row) {
                    $row['week_start'] = $weekStart;
                    $row['week_end'] = $weekEnd;
                }
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Error in getHoursWorked: " . $e->getMessage());
            throw $e;
        }
    }
}

/**
 * Hours Management Class
 * Handles hours_management table operations
 */
class HoursManagement {
    
    /**
     * Check if record exists for employee and week
     */
    public static function recordExists($employeeId, $weekStart) {
        try {
            $query = "SELECT hrs_id FROM hours_management 
                      WHERE employee_id = ? AND week_start = ?";
            $result = db()->query($query, [$employeeId, $weekStart], 'is');
            return !empty($result);
        } catch (Exception $e) {
            error_log("Error in recordExists: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all records for a specific week
     */
    public static function getByWeek($weekStart) {
        try {
            $query = "
                SELECT 
                    hm.*,
                    CONCAT(e.first_name, ' ', e.last_name) AS employee_name
                FROM hours_management hm
                JOIN employees e ON hm.employee_id = e.employee_id
                WHERE hm.week_start = ?
                ORDER BY e.first_name, e.last_name
            ";
            return db()->query($query, [$weekStart], 's');
        } catch (Exception $e) {
            error_log("Error in getByWeek: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all processed weeks
     */
    public static function getAllProcessedWeeks() {
        try {
            $query = "SELECT DISTINCT week_start, week_end 
                      FROM hours_management 
                      ORDER BY week_start DESC";
            return db()->query($query);
        } catch (Exception $e) {
            error_log("Error in getAllProcessedWeeks: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Create hours record
     */
    public static function createRecord($employeeId, $weekStart, $weekEnd, $expectedHours, $totalWorkedHours) {
        try {
            // Prevent duplicates
            if (self::recordExists($employeeId, $weekStart)) {
                throw new Exception('Record already exists for this week');
            }

            // Calculate hours owed and overtime
            $hoursOwed = 0;
            $overtime = 0;
            
            if ($totalWorkedHours < $expectedHours) {
                $hoursOwed = $expectedHours - $totalWorkedHours;
            } elseif ($totalWorkedHours > $expectedHours) {
                $overtime = $totalWorkedHours - $expectedHours;
            }

            // Get next ID
            $hrsId = self::getNextId();

            $query = "INSERT INTO hours_management 
                     (hrs_id, employee_id, week_start, week_end, expected_hours, 
                      total_worked_hours, hours_owed, overtime)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $params = [
                $hrsId,
                $employeeId,
                $weekStart,
                $weekEnd,
                $expectedHours,
                $totalWorkedHours,
                $hoursOwed,
                $overtime
            ];
            
            db()->execute($query, $params, 'iissddd d');
            
            return [
                'hrs_id' => $hrsId,
                'hours_owed' => $hoursOwed,
                'overtime' => $overtime,
                'success' => true
            ];
        } catch (Exception $e) {
            error_log("Error in createRecord: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get employee hours
     */
    public static function getByEmployee($employeeId) {
        try {
            $query = "SELECT * FROM hours_management 
                      WHERE employee_id = ? 
                      ORDER BY week_start DESC";
            return db()->query($query, [$employeeId], 'i');
        } catch (Exception $e) {
            error_log("Error in getByEmployee: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get next ID
     */
    private static function getNextId() {
        try {
            $query = "SELECT MAX(hrs_id) as max_id FROM hours_management";
            $result = db()->query($query);
            return ($result[0]['max_id'] ?? 0) + 1;
        } catch (Exception $e) {
            error_log("Error in getNextId: " . $e->getMessage());
            return 1;
        }
    }
}
