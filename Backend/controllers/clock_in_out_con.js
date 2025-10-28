import { getClockInOutData } from "../middleware/clock_in_out_mid.js"

export const getClockInOutDataCon = async (req, res) => {
    try {
        const rawEmployees = await getClockInOutData();
        
        // Map database fields to frontend expected fields
        const employees = rawEmployees.map(emp => ({
            id: emp.employee_id,  // Use employee_id as the primary identifier
            name: `${emp.first_name || ''} ${emp.last_name || ''}`.trim(),
            employeeId: emp.employee_id,  // Frontend expects this field
            classificationId: emp.classification_id,
            firstName: emp.first_name,
            lastName: emp.last_name,
            contactNo: emp.contact_no,
            email: emp.email,
            address: emp.address,
            idNumber: emp.id,  // This is the ID number field in DB
            userType: emp.is_admin ? 'Admin' : 'Employee',  // Convert is_admin to userType
            dateHired: emp.date_hired,
            supervisorName: emp.supervisor_name,
            leaveBalance: emp.leave_balance,
            username: emp.username,
            roles: emp.role || 'Employee',  // From emp_classification table
            department: emp.department || 'General',  // From emp_classification table
            status: emp.employment_status || 'Active'
        }));

        res.json({ 
            clock_in_out_data: rawEmployees,  // Keep original for backward compatibility
            employees: employees              // New formatted data
        });
    } catch (error) {
        console.error('Error in getClockInOutDataCon:', error);
        res.status(500).json({ error: 'Failed to fetch employee data' });
    }
}