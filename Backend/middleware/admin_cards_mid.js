import { pool } from '../config/db.js'

export const getTotalEmployeesData = async () => {
  try {
    let [row] = await pool.query()
    return row
  } catch (error) {
    throw new Error('Database error: ' + err.message);
  }
}

export const getTotalCheckedInData = async () => {
  try {
    let [row] = await pool.query()
    return row
  } catch (error) {
    throw new Error('Database error: ' + err.message);
  }
}

export const getTotalCheckedOutData = async () => {
  try {
    let [row] = await pool.query()
    return row
  } catch (error) {
    throw new Error('Database error: ' + err.message);
  }
}

export const getTotalAbsentData = async () => {
  try {
    let [row] = await pool.query()
    return row
  } catch (error) {
    throw new Error('Database error: ' + err.message);
  }
}
