import {pool} from "../config/db.js"

export const UpdateEmp = async (employeeData) => {
  try {
    // Build dynamic query based on provided fields
    const fields = [];
    const values = [];

    if (employeeData.first_name !== undefined) {
      fields.push('first_name = ?');
      values.push(employeeData.first_name);
    }
    if (employeeData.last_name !== undefined) {
      fields.push('last_name = ?');
      values.push(employeeData.last_name);
    }
    if (employeeData.contact_no !== undefined) {
      fields.push('contact_no = ?');
      // Ensure contact number fits database limit (10 chars)
      const truncatedContact = employeeData.contact_no ? employeeData.contact_no.toString().slice(0, 10) : null;
      values.push(truncatedContact);
    }
    if (employeeData.email !== undefined) {
      fields.push('email = ?');
      values.push(employeeData.email);
    }
    if (employeeData.address !== undefined) {
      fields.push('address = ?');
      values.push(employeeData.address);
    }
    if (employeeData.id_number !== undefined) {
      fields.push('id = ?');  // 'id' is the actual column name in DB
      values.push(employeeData.id_number);
    }
    if (employeeData.user_type !== undefined) {
      fields.push('is_admin = ?');  // Map user_type to is_admin
      values.push(employeeData.user_type === 'Admin' ? 1 : 0);
    }
    if (employeeData.date_hired !== undefined) {
      fields.push('date_hired = ?');
      values.push(employeeData.date_hired);
    }
    if (employeeData.supervisor_name !== undefined) {
      fields.push('supervisor_name = ?');
      values.push(employeeData.supervisor_name);
    }
    if (employeeData.leave_balance !== undefined) {
      fields.push('leave_balance = ?');
      values.push(employeeData.leave_balance);
    }
    // Note: username and password columns don't exist in employees table
    // Note: employment_status column doesn't exist in employees table
    // Note: roles and department are in emp_classification table, not employees table
    if (employeeData.classification_id !== undefined) {
      fields.push('classification_id = ?');
      values.push(employeeData.classification_id);
    }

    if (fields.length === 0) {
      throw new Error('No fields provided for update');
    }

    // Add employee_id as the last parameter for WHERE clause
    values.push(employeeData.employee_id);

    const sql = `UPDATE tracker_db.employees SET ${fields.join(', ')} WHERE employee_id = ?`;

    const [result] = await pool.query(sql, values);

    if (result.affectedRows === 0) {
      throw new Error('Employee not found');
    }

    return { success: true, affectedRows: result.affectedRows };
  } catch (error) {
    console.error("Database error:", error);
    throw error;
  }
};