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
    const today = new Date().toISOString().split('T')[0];
    let [row] = await pool.query(
      "SELECT COUNT(DISTINCT employee_id) AS 'checkedIn' FROM record_backups WHERE clockin_time IS NOT NULL AND date = ? AND type = 'Work'",
      [today]
    );
    return row
  } catch (error) {
    throw new Error('Database error: ' + error.message);
  }
}

export const getTotalCheckedOutData = async () => {
  try {
    const today = new Date().toISOString().split('T')[0];
    let [row] = await pool.query(
      "SELECT COUNT(DISTINCT employee_id) AS 'checkedOut' FROM record_backups WHERE clockout_time IS NOT NULL AND date = ? AND type = 'Work'",
      [today]
    );
    return row
  } catch (error) {
    throw new Error('Database error: ' + error.message);
  }
}

export const getTotalAbsentData = async () => {
  try {
    const today = new Date().toISOString().split('T')[0];
    let [row] = await pool.query(
      "SELECT COUNT(DISTINCT employee_id) AS 'absent' FROM record_backups WHERE status = 'Absent' AND date = ? AND type = 'Work'",
      [today]
    );
    return row
  } catch (error) {
    throw new Error('Database error: ' + error.message);
  }
}


