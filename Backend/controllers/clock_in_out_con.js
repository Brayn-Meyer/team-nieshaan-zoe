import { getClockInOutData, getHoursWorked, HoursManagement } from "../models/clock_in_out_mid.js"

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

// Get time log data - only from hours_management table
export const getTimeLogData = async (req, res) => {
    try {
        const { week } = req.query; // e.g., '2025-10-28' (Monday)
        console.log('=== TIME LOG DATA REQUEST ===');
        console.log('Requested week:', week);
        
        if (!week) {
            // If no week specified, get the latest processed week
            const processedWeeks = await HoursManagement.getAllProcessedWeeks();
            if (processedWeeks.length === 0) {
                return res.json({ 
                    timeLogData: [], 
                    message: 'No processed weeks found. Process weekly hours first.' 
                });
            }
            week = processedWeeks[0].week_start; // Use latest week
        }
        
        // Fetch data only from hours_management table
        const timeLogRecords = await HoursManagement.getByWeek(week);
        console.log('Found records in hours_management:', timeLogRecords.length);
        
        if (timeLogRecords.length === 0) {
            return res.json({ 
                timeLogData: [], 
                message: `No processed data found for week ${week}. Process this week's hours first.` 
            });
        }
        
        // Format data for frontend
        const timeLogData = timeLogRecords.map((record) => {
            const indicator = (record.hours_owed > 0) ? 'red' : 'green';
            
            return {
                id: record.employee_id,
                name: record.employee_name,
                hoursWorked: Math.round(record.total_worked_hours * 10) / 10,
                hoursOwed: Math.round(record.hours_owed * 10) / 10,
                overtime: Math.round(record.overtime * 10) / 10,
                indicator: indicator,
                week_start: record.week_start,
                week_end: record.week_end,
                expected_hours: record.expected_hours,
                is_saved: true // All records in hours_management are saved/processed
            };
        });
        
        console.log('Returning timeLogData:', timeLogData.length, 'records');
        res.json({ timeLogData });
    } catch (error) {
        console.error('Error in getTimeLogData:', error);
        res.status(500).json({ error: 'Failed to fetch time log data' });
    }
};

// Process weekly hours - converts raw data to hours_management records
export const processWeeklyHours = async (req, res) => {
    try {
        const { week_start } = req.body; // e.g., '2024-11-04' (Monday)
        
        if (!week_start) {
            return res.status(400).json({ 
                success: false, 
                error: 'week_start is required' 
            });
        }
        
        console.log('Processing weekly hours for:', week_start);
        
        const results = await HoursManagement.processWeeklyHours(week_start);
        
        res.json({ 
            success: true, 
            message: `Processed ${results.length} employee records for week ${week_start}`,
            data: results 
        });
    } catch (error) {
        console.error('Error processing weekly hours:', error);
        res.status(500).json({ 
            success: false, 
            error: error.message 
        });
    }
};

// Get all processed weeks for dropdown
export const getProcessedWeeks = async (req, res) => {
    try {
        const weeks = await HoursManagement.getAllProcessedWeeks();
        res.json({ success: true, weeks });
    } catch (error) {
        console.error('Error getting processed weeks:', error);
        res.status(500).json({ success: false, error: error.message });
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