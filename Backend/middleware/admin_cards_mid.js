import { pool } from '../config/db.js'

export const getTotalEmployeesData = async () => {
  try {
    let [row] = await pool.query("SELECT COUNT(employee_id) AS 'Total Employees' FROM employees;")
    return row
  } catch (error) {
    throw new Error('Database error: ' + err.message);
  }
}

export const getTotalCheckedInData = async () => {
  try {
    let [row] = await pool.query("SELECT COUNT(clockin_time) AS 'Total Employees Clocked In' FROM record_backups WHERE clockin_time IS NOT NULL;")
    return row
  } catch (error) {
    throw new Error('Database error: ' + err.message);
  }
}

export const getTotalCheckedOutData = async () => {
  try {
    let [row] = await pool.query("SELECT COUNT(clockout_time) AS 'Total Employees Clocked Out' FROM record_backups WHERE clockout_time IS NOT NULL;")
    return row
  } catch (error) {
    throw new Error('Database error: ' + err.message);
  }
}

export const getTotalAbsentData = async () => {
  try {
    let [row] = await pool.query("SELECT COUNT(employment_status) AS 'Absent Employees' FROM employees WHERE employment_status NOT IN ('Active', 'Terminated') ;")
    return row
  } catch (error) {
    throw new Error('Database error: ' + err.message);
  }
}
