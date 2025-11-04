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
            $query = "SELECT COUNT(DISTINCT employee_id) AS checkedIn 
                      FROM record_backups 
                      WHERE clockin_time IS NOT NULL 
                      AND date = ? 
                      AND type = 'Work'";
            $result = db()->query($query, [$today], 's');
            return $result[0]['checkedIn'] ?? 0;
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
            $query = "SELECT COUNT(DISTINCT employee_id) AS checkedOut 
                      FROM record_backups 
                      WHERE clockout_time IS NOT NULL 
                      AND date = ? 
                      AND type = 'Work'";
            $result = db()->query($query, [$today], 's');
            return $result[0]['checkedOut'] ?? 0;
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
            $query = "SELECT COUNT(DISTINCT employee_id) AS absent 
                      FROM record_backups 
                      WHERE status = 'Absent' 
                      AND date = ? 
                      AND type = 'Work'";
            $result = db()->query($query, [$today], 's');
            return $result[0]['absent'] ?? 0;
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
