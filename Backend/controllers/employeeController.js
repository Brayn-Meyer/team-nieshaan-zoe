import { pool } from '../config/db.js';

// Search employees by status and name
export const searchEmployees = async (req, res) => {
  try {
    const { status, name } = req.body;
    console.log('Search parameters:', req.body); // Debug log

    let query = `
      SELECT
        e.employee_id,
        e.first_name,
        e.last_name,
        DATE(rb.clockin_time) AS work_date,
        MIN(CASE WHEN rb.type = 'Work'  THEN rb.clockin_time END) AS work_clockin,
        MAX(CASE WHEN rb.type = 'Work'  THEN rb.clockout_time END) AS work_clockout,
        MIN(CASE WHEN rb.type = 'Tea'   THEN rb.clockin_time END) AS tea_clockin,
        MAX(CASE WHEN rb.type = 'Tea'   THEN rb.clockout_time END) AS tea_clockout,
        MIN(CASE WHEN rb.type = 'Lunch' THEN rb.clockin_time END) AS lunch_clockin,
        MAX(CASE WHEN rb.type = 'Lunch' THEN rb.clockout_time END) AS lunch_clockout
      FROM record_backups rb
      INNER JOIN employees e ON e.employee_id = rb.employee_id
      INNER JOIN emp_classification ec ON e.classification_id = ec.classification_id
      WHERE 1=1
    `;

    const params = [];

    // Apply filters

    if (name) {
      query += ` AND (CONCAT(e.first_name, ' ', e.last_name) LIKE ? OR e.first_name LIKE ? OR e.last_name LIKE ?)`;
      const searchName = `%${name}%`;
      params.push(searchName, searchName, searchName);
    }

    // Group and order results like getEmployees
    query += `
      GROUP BY e.employee_id, DATE(rb.clockin_time)
      ORDER BY e.employee_id, work_date
    `;

    const [rows] = await pool.query(query, params);
    res.status(200).json(rows);
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Error searching employees' });
  }
};

// ✅ GET all employees (with classification info)
export const getEmployees = async (req, res) => {
  try {
    const [rows] = await pool.query(`
      SELECT
        employees.employee_id,
        employees.first_name,employees.last_name,
        DATE(clockin_time) AS work_date,
        MIN(CASE WHEN type = 'Work'  THEN clockin_time END) AS work_clockin,
        MAX(CASE WHEN type = 'Work'  THEN clockout_time END) AS work_clockout,
        MIN(CASE WHEN type = 'Tea'   THEN clockin_time END) AS tea_clockin,
        MAX(CASE WHEN type = 'Tea'   THEN clockout_time END) AS tea_clockout,
        MIN(CASE WHEN type = 'Lunch' THEN clockin_time END) AS lunch_clockin,
        MAX(CASE WHEN type = 'Lunch' THEN clockout_time END) AS lunch_clockout
      FROM record_backups INNER JOIN employees ON employees.employee_id = record_backups.employee_id
      GROUP BY employee_id, DATE(clockin_time)
      ORDER BY employee_id, work_date;
    `);
    res.status(200).json(rows);
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Error fetching employees' });
  }
};

// ✅ GET single employee by ID
export const getEmployeeById = async (req, res) => {
  try {
    const { id } = req.params;
    const [rows] = await pool.query(
      `
      SELECT 
        e.employee_id,
        CONCAT(e.first_name, ' ', e.last_name) AS full_name,
        e.email,
        e.contact_no,
        e.date_hired,
        ec.department,
        ec.position,
        ec.role
      FROM employees e
      JOIN emp_classification ec ON e.classification_id = ec.classification_id
      WHERE e.employee_id = ?;
      `,
      [id]
    );
    res.status(200).json(rows[0] || {});
  } catch (err) {
    res.status(500).json({ error: 'Error fetching employee' });
  }
};
