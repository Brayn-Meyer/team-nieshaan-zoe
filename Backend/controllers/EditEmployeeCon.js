import { UpdateEmp } from "../middleware/Employee_mid.js";
import { updatedEmployeesList } from "../middleware/employeesDB.js";

export const EditEmpCon = async (req, res) => {
  try {
    // Support both :emp_id and :employee_id route parameter names
    const employee_id = req.params.emp_id || req.params.employee_id;
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

    await UpdateEmp(employeeData);

    res.json({
      success: true,
      message: "Employee updated!",
      employee: employeeData
    });

    // Emit Socket.IO event after successful update
    try {
      const io = req.app.get('io');
      if (io) {
        const employeesList = await updatedEmployeesList();
        io.emit('employees:update', employeesList);
        console.log('📡 Socket.IO: Employee update broadcasted');
      }
    } catch (socketError) {
      console.error('Socket.IO emission error:', socketError);
    }
  } catch (error) {
    console.error('Update error:', error);
    res.status(500).json({
      success: false,
      message: "Failed to update employee",
      error: error.message
    });
  }
};