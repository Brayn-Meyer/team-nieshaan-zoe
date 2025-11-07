<?php
/**
 * AdminCards Model
 * Handles KPI card data (Total Employees, Check-in, Check-out, Absent)
 */

require_once __DIR__ . '/../../includes/db.php';

class AdminCardsModel {
    
    /**
     * Get total number of employees
     */
    public static function getTotalEmployees() {
        try {
            $query = "SELECT COUNT(employee_id) AS total FROM employees";
            $result = db()->query($query);
            return $result[0]['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Error in getTotalEmployees: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get total checked in employees for today
     */
    public static function getTotalCheckedIn() {
        try {
            $today = date('Y-m-d');
            $query = "SELECT 
                        COUNT(DISTINCT e.employee_id) AS total_clocked_in
                    FROM employees e
                    LEFT JOIN record_backups rb 
                        ON rb.employee_id = e.employee_id
                    WHERE DATE(rb.clockin_time) = ?
                        AND rb.clockin_time IS NOT NULL
                        AND rb.clockout_time IS NULL;
                    ";
            $result = db()->query($query, [$today], 's');
            return $result[0]['total_clocked_in'] ?? 0;
        } catch (Exception $e) {
            error_log("Error in getTotalCheckedIn: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get total checked out employees for today
     */
    public static function getTotalCheckedOut() {
        try {
            $today = date('Y-m-d');
            $query = "SELECT 
                        COUNT(DISTINCT e.employee_id) AS total_clocked_out
                    FROM employees e
                    LEFT JOIN record_backups rb 
                        ON rb.employee_id = e.employee_id
                    WHERE DATE(rb.clockin_time) = ?
                        AND rb.clockin_time IS NOT NULL
                        AND rb.clockout_time IS NOT NULL;
                    ";
            $result = db()->query($query, [$today], 's');
            return $result[0]['total_clocked_out'] ?? 0;
        } catch (Exception $e) {
            error_log("Error in getTotalCheckedOut: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get total absent employees for today
     */
    public static function getTotalAbsent() {
        try {
            $today = date('Y-m-d');
            $query = "SELECT 
                        COUNT(DISTINCT e.employee_id) AS total_absent
                    FROM employees e
                    LEFT JOIN record_backups rb 
                        ON rb.employee_id = e.employee_id
                    WHERE DATE(rb.clockin_time) = ?
                        AND rb.clockin_time IS NULL
                        AND rb.clockout_time IS NULL;
                    ";
            $result = db()->query($query, [$today], 's');
            return $result[0]['total_absent'] ?? 0;
        } catch (Exception $e) {
            error_log("Error in getTotalAbsent: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get all KPI data at once
     */
    public static function getAllKpiData() {
        return [
            'total' => self::getTotalEmployees(),
            'checkedIn' => self::getTotalCheckedIn(),
            'checkedOut' => self::getTotalCheckedOut(),
            'absent' => self::getTotalAbsent()
        ];
    }
}
