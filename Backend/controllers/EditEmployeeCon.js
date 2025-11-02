import { UpdateEmp } from "../models/Employee_mid.js";
import { emitKPIUpdates } from "./admin_cards_con.js";
import { pool } from "../config/db.js";

const validateContactNo = (contactNo) => {
  const re = /^\+?[\d\s-]{10,}$/;
  return re.test(contactNo);
};

// Helper function to create or find classification for edit
const createOrFindClassification = async (department, roles, userType) => {
  try {
    // First check if classification exists
    const [existing] = await pool.query(
      'SELECT classification_id FROM emp_classification WHERE department = ? AND role = ?',
      [department, roles]
    );

    if (existing.length > 0) {
      return existing[0].classification_id;
    }

    // Create new classification
    const [result] = await pool.execute(`
      INSERT INTO emp_classification (department, position, role, employment_type, employee_level)
      VALUES (?, ?, ?, 'Full-time', 'Junior')
    `, [department, roles, roles]);

    return result.insertId;
  } catch (error) {
    console.error('Error creating/finding classification in edit:', error);
    throw error;
  }
};

export const EditEmpCon = async (req, res) => {
  try {
    console.log('=== EDIT EMPLOYEE REQUEST ===');
    console.log('Employee ID:', req.params.employee_id);
    console.log('Request body:', JSON.stringify(req.body, null, 2));
    console.log('============================');

    const employee_id = req.params.employee_id;
    if (!employee_id) {
      return res.status(400).json({ success: false, message: "Missing employee_id in params" });
    }

    const {
      firstName,
      lastName,
      contactNo,
      email,
      address,
      idNumber,
      userType,
      dateHired,
      supervisorName,
      leaveBalance,
      username,
      password,
      roles,
      department,
      status,
      // Legacy fields for backward compatibility
      last_name,
      contact_no,
      id,
      employment_status,
      leave_balance: legacyLeaveBalance
    } = req.body;

    // Validate contact number
    const contactToValidate = contactNo || contact_no;
    if (contactToValidate !== undefined && !validateContactNo(contactToValidate)) {
      return res.status(400).json({ 
        success: false, 
        message: "Invalid contact number format" 
      });
    }

    // Validate leave_balance if provided
    const leaveBalanceToValidate = leaveBalance || legacyLeaveBalance;
    if (leaveBalanceToValidate !== undefined) {
      const numLeaveBalance = Number(leaveBalanceToValidate);
      if (isNaN(numLeaveBalance) || numLeaveBalance < 0) {
        return res.status(400).json({ 
          success: false, 
          message: "Leave balance must be a non-negative number" 
        });
      }
    }

    // Note: employment_status field doesn't exist in employees table, skip validation

    // Validate and truncate contact number to fit database limit (10 chars)
    const finalContactNo = contactNo || contact_no;
    const truncatedContactNo = finalContactNo ? finalContactNo.toString().slice(0, 10) : undefined;

    // Handle date formatting for MySQL DATE column
    let formattedDateHired = dateHired;
    if (dateHired) {
      // Convert ISO date to MySQL DATE format (YYYY-MM-DD)
      const date = new Date(dateHired);
      if (!isNaN(date.getTime())) {
        formattedDateHired = date.toISOString().split('T')[0];
      }
    }

    // Handle classification if roles and department are provided
    let classificationId = req.body.classification_id || req.body.classificationId;
    if (department && roles && !classificationId) {
      console.log('Creating/finding classification for edit:', department, roles);
      classificationId = await createOrFindClassification(
        department, 
        roles, 
        userType || 'Employee'
      );
      console.log('Edit Classification ID:', classificationId);
    }

    // Use new field names or fall back to legacy ones
    const employeeData = {
      first_name: firstName,
      last_name: lastName || last_name,
      contact_no: truncatedContactNo,
      email: email,
      address: address,
      id_number: idNumber || id,
      user_type: userType,
      date_hired: formattedDateHired,
      supervisor_name: supervisorName,
      leave_balance: leaveBalance || legacyLeaveBalance,
      classification_id: classificationId,
      employee_id: employee_id
    };

    console.log('Final employee data for update:', JSON.stringify(employeeData, null, 2));

    const result = await UpdateEmp(employeeData);

    console.log('✓ Employee updated successfully');
    res.json({
      success: true,
      message: "Employee updated successfully",
      employee: employeeData,
      updateInfo: result
    });

    // Emit updated KPI data after successful update
    const io = req.app.get('io');
    emitKPIUpdates(io);

  } catch (error) {
    console.error('Update error:', error);
    
    // Handle specific error cases
    if (error.message === 'Employee not found') {
      return res.status(404).json({
        success: false,
        message: "Employee not found"
      });
    }
    
    if (error.message === 'No fields provided for update') {
      return res.status(400).json({
        success: false,
        message: "No valid fields provided for update"
      });
    }

    res.status(500).json({
      success: false,
      message: "Failed to update employee",
      error: error.message
    });
  }
};