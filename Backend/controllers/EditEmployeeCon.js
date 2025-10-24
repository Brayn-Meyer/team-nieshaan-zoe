import { UpdateEmp } from "../middleware/Employee_mid.js";

const validateContactNo = (contactNo) => {
  const re = /^\+?[\d\s-]{10,}$/;
  return re.test(contactNo);
};

export const EditEmpCon = async (req, res) => {
  try {
    const employee_id = req.params.employee_id;
    if (!employee_id) {
      return res.status(400).json({ success: false, message: "Missing employee id in params" });
    }

    const {
      last_name,
      contact_no,
      email,
      address,
      id,
      employment_status,
      leave_balance
    } = req.body;
    if (contact_no !== undefined && !validateContactNo(contact_no)) {
      return res.status(400).json({ 
        success: false, 
        message: "Invalid contact number format" 
      });
    }

    // Validate leave_balance if provided
    if (leave_balance !== undefined) {
      const numLeaveBalance = Number(leave_balance);
      if (isNaN(numLeaveBalance) || numLeaveBalance < 0) {
        return res.status(400).json({ 
          success: false, 
          message: "Leave balance must be a non-negative number" 
        });
      }
    }

    // Validate employment_status if provided
    if (employment_status !== undefined) {
      const validStatuses = ['active', 'inactive', 'on_leave', 'terminated'];
      if (!validStatuses.includes(employment_status.toLowerCase())) {
        return res.status(400).json({ 
          success: false, 
          message: "Invalid employment status" 
        });
      }
    }

    const employeeData = {
      last_name,
      contact_no,
      email,
      address,
      id,
      employment_status,
      leave_balance,
      employee_id
    };

    const result = await UpdateEmp(employeeData);

    res.json({
      success: true,
      message: "Employee updated successfully",
      employee: employeeData,
      updateInfo: result
    });
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