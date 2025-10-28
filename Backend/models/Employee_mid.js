import {pool} from "../config/db.js"

export const UpdateEmp = async (employeeData) => {
  try {
    const [result] = await pool.query(
      `UPDATE tracker_db.employees 
       SET last_name = ?, 
           contact_no = ?, 
           email = ?, 
           address = ?, 
           id = ?, 
           employment_status = ?, 
           leave_balance = ? 
       WHERE employee_id = ?`,
      [
        employeeData.last_name,
        employeeData.contact_no,
        employeeData.email,
        employeeData.address,
        employeeData.id,
        employeeData.employment_status,
        employeeData.leave_balance,
        employeeData.employee_id
      ]
    );
    return { success: true };
  } catch (error) {
    console.error("Database error:", error);
    throw error;
  }
};