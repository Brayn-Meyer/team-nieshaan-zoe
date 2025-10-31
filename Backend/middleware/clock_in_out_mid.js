import { pool } from '../config/db.js'

export const getClockInOutData = async () => {
  try {
    let [row] = await pool.query(`
      SELECT 
        e.employee_id,
        e.first_name,
        e.last_name,
        e.employment_status,
        DATE(rb.clockin_time) AS work_date,
        MIN(CASE WHEN rb.type = 'Work'  THEN rb.clockin_time END) AS work_clockin,
        MAX(CASE WHEN rb.type = 'Work'  THEN rb.clockout_time END) AS work_clockout,
        MIN(CASE WHEN rb.type = 'Tea'   THEN rb.clockin_time END) AS tea_clockin,
        MAX(CASE WHEN rb.type = 'Tea'   THEN rb.clockout_time END) AS tea_clockout,
        MIN(CASE WHEN rb.type = 'Lunch' THEN rb.clockin_time END) AS lunch_clockin,
        MAX(CASE WHEN rb.type = 'Lunch' THEN rb.clockout_time END) AS lunch_clockout
      FROM employees e
      LEFT JOIN record_backups rb 
          ON rb.employee_id = e.employee_id
        AND DATE(rb.clockin_time) = (
          -- lock everything to the single most recent date
          SELECT MAX(DATE(clockin_time))
          FROM record_backups
        )
      GROUP BY e.employee_id, e.first_name, e.last_name, DATE(rb.clockin_time)
      ORDER BY e.employee_id;
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