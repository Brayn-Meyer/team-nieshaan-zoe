import { pool } from '../config/db.js'

export const getClockInOutData = async () => {
  try {
    let [row] = await pool.query()
    return row
  } catch (error) {
    throw new Error('Database error: ' + err.message);
  }
}