import { pool } from '../config/db.js'

export const getClockInOutData = async () => {
  try {
    let [row] = await pool.query(`
      SELECT 
        e.*,
        ec.department,
        ec.position,
        ec.role,
        ec.employment_type,
        ec.employee_level
      FROM tracker_db.employees e
      LEFT JOIN tracker_db.emp_classification ec ON e.classification_id = ec.classification_id
    `);
    return row
  } catch (error) {
    throw new Error('Database error: ' + error.message);
  }
}

export const getfilterAll = async() => {
    let [row] = await pool.query('SELECT * FROM record_backups');
    return row;
}