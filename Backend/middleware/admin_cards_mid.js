import { pool } from '../config/db.js'

export const getTotalEmployeesData = async () => {
  try {
    let [row] = await pool.query("SELECT COUNT(employee_id) AS 'total' FROM employees;")
    return row
  } catch (error) {
    throw new Error('Database error: ' + error.message);
  }
}

export const getTotalCheckedInData = async () => {
  try {
    let [row] = await pool.query("SELECT COUNT(clockin_time) AS 'checkedIn' FROM record_backups WHERE clockin_time IS NOT NULL;")
    return row
  } catch (error) {
    throw new Error('Database error: ' + error.message);
  }
}

export const getTotalCheckedOutData = async () => {
  try {
    let [row] = await pool.query("SELECT COUNT(clockout_time) AS 'checkedOut' FROM record_backups WHERE clockout_time IS NOT NULL;")
    return row
  } catch (error) {
    throw new Error('Database error: ' + error.message);
  }
}

export const getTotalAbsentData = async () => {
  try {
    let [row] = await pool.query("SELECT COUNT(employment_status) AS 'absent' FROM employees WHERE employment_status NOT IN ('Active', 'Terminated') ;")
    return row
  } catch (error) {
    throw new Error('Database error: ' + error.message);
  }
}


