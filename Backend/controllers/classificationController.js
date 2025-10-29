import { pool } from '../config/db.js';

export const getClassifications = async (req, res) => {
  try {
    const [rows] = await pool.query(`SELECT * FROM emp_classification ORDER BY classification_id;`);
    res.status(200).json(rows);
  } catch (err) {
    res.status(500).json({ error: 'Error fetching classifications' });
  }
};
