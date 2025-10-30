import { pool } from '../config/db.js';

// ✅ GET all or filter by employee_id
export const getNotifications = async (req, res) => {
  try {
    const { employee_id } = req.query;
    let sql = `
      SELECT 
        n.notification_id,
        e.employee_id,
        CONCAT(e.first_name, ' ', e.last_name) AS full_name,
        n.title,
        n.message,
        n.date_created
      FROM notifications_records n
      JOIN employees e ON n.employee_id = e.employee_id
      WHERE 1=1
    `;
    const params = [];
    if (employee_id) {
      sql += ' AND n.employee_id = ?';
      params.push(employee_id);
    }
    sql += ' ORDER BY n.date_created DESC;';
    const [rows] = await pool.query(sql, params);
    res.status(200).json(rows);
  } catch (err) {
    res.status(500).json({ error: 'Error fetching notifications' });
  }
};
