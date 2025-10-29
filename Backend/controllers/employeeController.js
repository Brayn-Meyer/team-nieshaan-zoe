import { pool } from '../config/db.js';

// Search employees by status and name
export const searchEmployees = async (req, res) => {
  try {
    const { status, name } = req.body;
    console.log('Search parameters:', req.body); // Debug log
    let query = `
      SELECT 
        e.employee_id,
        CONCAT(e.first_name, ' ', e.last_name) AS full_name,
        e.email,
        e.contact_no,
        e.employment_status,
        e.date_hired,
        ec.department,
        ec.position,
        ec.role,
        ec.employment_type,
        ec.employee_level
      FROM employees e
      JOIN emp_classification ec ON e.classification_id = ec.classification_id
      WHERE 1=1
    `;
    const params = [];

    if (status) {
      query += ` AND e.employment_status = ?`;
      params.push(status);
    }

    if (name) {
      query += ` AND (CONCAT(e.first_name, ' ', e.last_name) LIKE ? OR e.first_name LIKE ? OR e.last_name LIKE ?)`;
      const searchName = `%${name}%`;
      params.push(searchName, searchName, searchName);
    }

    query += ` ORDER BY e.employee_id`;

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
        e.employee_id,
        CONCAT(e.first_name, ' ', e.last_name) AS full_name,
        e.email,
        e.contact_no,
        e.employment_status,
        e.date_hired,
        ec.department,
        ec.position,
        ec.role,
        ec.employment_type,
        ec.employee_level
      FROM employees e
      JOIN emp_classification ec ON e.classification_id = ec.classification_id
      ORDER BY e.employee_id;
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
        e.employment_status,
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
