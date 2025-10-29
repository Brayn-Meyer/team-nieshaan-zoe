import { pool } from '../config/db.js';

export const getQRStorage = async (req, res) => {
  try {
    const [rows] = await pool.query(`
      SELECT 
        q.qr_id,
        e.employee_id,
        CONCAT(e.first_name, ' ', e.last_name) AS full_name,
        q.token,
        q.scan_url,
        q.created_at,
        q.expires_at
      FROM qr_storage q
      JOIN employees e ON q.employee_id = e.employee_id
      ORDER BY q.qr_id;
    `);
    res.status(200).json(rows);
  } catch (err) {
    res.status(500).json({ error: 'Error fetching QR storage' });
  }
};
