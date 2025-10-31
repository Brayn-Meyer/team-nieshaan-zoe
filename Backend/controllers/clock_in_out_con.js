import { getClockInOutData, getHoursWorked, HoursManagement } from "../middleware/clock_in_out_mid.js"

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

export const getHoursWorkedCon = async (req, res) => {
    try{
        const hoursWorked = await getHoursWorked();
        res.json({ hoursWorked });
    } catch (error) {
        console.error('Error in getHoursWorkedCon:', error);
        res.status(500).json({ error: 'Failed to fetch hours worked' });
    }
};

// Get time log data with hours_owed and overtime calculated
export const getTimeLogData = async (req, res) => {
    try {
        const { week } = req.query; // e.g., '2025-10-28' (Monday)
        
        // Get hours worked for specific week (or latest if not specified)
        const hoursWorkedData = await getHoursWorked(week);
        
        if (hoursWorkedData.length === 0) {
            return res.json({ timeLogData: [] });
        }
        
        // Get the week_start from first record (they'll all be the same week)
        const weekStart = hoursWorkedData[0].week_start;
        
        // Batch fetch all hours_management records for this week (single query!)
        const existingRecords = await HoursManagement.getByWeek(weekStart);
        
        // Create a map for O(1) lookup
        const existingRecordsMap = new Map(
            existingRecords.map(r => [r.employee_id, r])
        );
        
        const EXPECTED_HOURS = 40;
        
        // Process each employee's weekly data
        const timeLogData = hoursWorkedData.map((record) => {
            const { employee_id, employee_name, week_start, week_end, total_hours } = record;
            
            const existing = existingRecordsMap.get(employee_id);
            
            let hours_owed = 0;
            let overtime = 0;
            let indicator = 'green';
            let is_saved = false;
            
            if (existing) {
                // Use existing record from hours_management
                hours_owed = existing.hours_owed || 0;
                overtime = existing.overtime || 0;
                indicator = 'green'; // Already saved/balanced
                is_saved = true;
            } else {
                // Calculate on the fly (will be saved when admin confirms)
                if (total_hours < EXPECTED_HOURS) {
                    hours_owed = EXPECTED_HOURS - total_hours;
                    indicator = 'red';
                } else if (total_hours > EXPECTED_HOURS) {
                    overtime = total_hours - EXPECTED_HOURS;
                    indicator = 'green';
                }
            }
            
            return {
                id: employee_id,
                name: employee_name,
                hoursWorked: Math.round(total_hours * 10) / 10, // Round to 1 decimal
                hoursOwed: Math.round(hours_owed * 10) / 10,
                overtime: Math.round(overtime * 10) / 10,
                indicator: indicator,
                week_start: week_start,
                week_end: week_end,
                expected_hours: EXPECTED_HOURS,
                is_saved: is_saved
            };
        });
        
        res.json({ timeLogData });
    } catch (error) {
        console.error('Error in getTimeLogData:', error);
        res.status(500).json({ error: 'Failed to fetch time log data' });
    }
};

export class HoursController {
    // Save/confirm hours record (when admin clicks red indicator)
    static async createRecord(req, res) {
        try {
            const { employee_id, week_start, week_end, expected_hours, total_worked_hours } = req.body;
            
            // Validate required fields
            if (!employee_id || !week_start || !week_end || expected_hours === undefined || total_worked_hours === undefined) {
                return res.status(400).json({ 
                    success: false, 
                    error: 'Missing required fields' 
                });
            }
            
            const result = await HoursManagement.createRecord(
                employee_id, 
                week_start, 
                week_end, 
                expected_hours, 
                total_worked_hours
            );
            
            res.json({ success: true, data: result });
        } catch (error) {
            // Check if it's a duplicate record error
            if (error.message === 'Record already exists for this week') {
                return res.status(409).json({ 
                    success: false, 
                    error: 'Record already exists for this week' 
                });
            }
            
            console.error('Error creating hours record:', error);
            res.status(500).json({ success: false, error: error.message });
        }
    }

    // Get employee hours
    static async getEmployeeHours(req, res) {
        try {
            const { employee_id } = req.params;
            const records = await HoursManagement.getByEmployee(employee_id);
            res.json({ success: true, data: records });
        } catch (error) {
            res.status(500).json({ success: false, error: error.message });
        }
    }
}