import { pool } from '../config/db.js'

export const getClockInOutData = async () => {
  try {
    let [row] = await pool.query("SELECT * FROM tracker_db.employees;")
    return row
  } catch (error) {
    throw new Error('Database error: ' + error.message);
  }
}