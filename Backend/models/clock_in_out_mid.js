import { pool } from '../config/db.js'

export const getClockInOutData = async () => {
  try {
    let [row] = await pool.query(`
      SELECT 
        e.*,
        ec.department,
        ec.position,
        ec.role,
        ec.employment_type,
        ec.employee_level
      FROM tracker_db.employees e
      LEFT JOIN tracker_db.emp_classification ec ON e.classification_id = ec.classification_id
    `);
    return row
  } catch (error) {
    throw new Error('Database error: ' + error.message);
  }
}

export const getfilterAll = async() => {
    let [row] = await pool.query('SELECT * FROM record_backups');
    return row;
}

export const getHoursWorked = async (weekStart = null) => {
  try {
    let query = `
SELECT 
e.employee_id,
CONCAT(e.first_name, ' ', e.last_name) AS employee_name,
ROUND(SUM(
  GREATEST(
	0,
	TIMESTAMPDIFF(
	  MINUTE,
	  -- effective clock-in (no earlier than 08:30)
	  CASE
		WHEN TIMESTAMP(r.date, TIME(r.clockin_time)) < TIMESTAMP(r.date, '08:30:00')
		  THEN TIMESTAMP(r.date, '08:30:00')
		ELSE TIMESTAMP(r.date, TIME(r.clockin_time))
	  END,
	  -- effective clock-out: use actual clock-out (do not cap to 16:30)
	  TIMESTAMP(r.date, TIME(r.clockout_time))
	)
  )
) / 60.0) AS total_hours
FROM record_backups r
JOIN employees e ON e.employee_id = r.employee_id
WHERE r.type = 'Work'
AND r.status = 'Active'
AND DAYOFWEEK(r.date) NOT IN (1, 7)
AND r.clockin_time IS NOT NULL
AND r.clockout_time IS NOT NULL`;

    const params = [];
    
    if (weekStart) {
      // Filter for specific week (Monday to Friday)
      query += ` AND r.date >= ? AND r.date <= DATE_ADD(?, INTERVAL 6 DAY)`;
      params.push(weekStart, weekStart);
    }

    query += `
GROUP BY e.employee_id, e.first_name, e.last_name
ORDER BY e.first_name, e.last_name`;

    console.log('Query:', query);
    console.log('Params:', params);

    const [rows] = await pool.query(query, params);
    
    // Add week_start and week_end to each row if weekStart was provided
    const results = rows.map(row => ({
      ...row,
      week_start: weekStart || null,
      week_end: weekStart ? new Date(new Date(weekStart).getTime() + 6 * 24 * 60 * 60 * 1000).toISOString().split('T')[0] : null
    }));
    
    console.log('Processed results:', results);
    return results;
  } catch (err) {
    console.error('Error in getHoursWorked:', err);
    throw new Error('Server error');
  }
};

export class HoursManagement {
    // Check if record exists for employee and week
    static async recordExists(employee_id, week_start) {
        const query = `SELECT hrs_id FROM hours_management 
                       WHERE employee_id = ? AND week_start = ?`;
        const [rows] = await pool.execute(query, [employee_id, week_start]);
        return rows.length > 0;
    }

    // Get all records for a specific week (batch fetch)
    static async getByWeek(week_start) {
        const query = `
            SELECT 
                hm.*,
                CONCAT(e.first_name, ' ', e.last_name) AS employee_name
            FROM hours_management hm
            JOIN employees e ON hm.employee_id = e.employee_id
            WHERE hm.week_start = ?
            ORDER BY e.first_name, e.last_name
        `;
        const [rows] = await pool.execute(query, [week_start]);
        return rows;
    }

    // Get all processed weeks (for time log dropdown)
    static async getAllProcessedWeeks() {
        const query = `
            SELECT DISTINCT week_start, week_end 
            FROM hours_management 
            ORDER BY week_start DESC
        `;
        const [rows] = await pool.execute(query);
        return rows;
    }

    // Process and save weekly hours for all employees for a specific week
    static async processWeeklyHours(week_start, expected_hours = 40) {
        console.log('Processing weekly hours for week:', week_start);
        
        // Get raw hours worked data for this specific week
        const hoursWorkedData = await getHoursWorked(week_start);
        
        if (hoursWorkedData.length === 0) {
            throw new Error('No hours worked data found for this week');
        }

        const results = [];
        
        for (const record of hoursWorkedData) {
            const { employee_id, employee_name, week_start: ws, week_end, total_hours } = record;
            
            // Skip if already processed
            if (await this.recordExists(employee_id, ws)) {
                console.log(`Record already exists for employee ${employee_id} week ${ws}`);
                continue;
            }

            // Calculate hours owed and overtime
            let hours_owed = 0;
            let overtime = 0;
            
            if (total_hours < expected_hours) {
                hours_owed = expected_hours - total_hours;
            } else if (total_hours > expected_hours) {
                overtime = total_hours - expected_hours;
            }

            // Create the record
            const result = await this.createRecord(
                employee_id, 
                ws, 
                week_end, 
                expected_hours, 
                total_hours
            );

            results.push({
                employee_id,
                employee_name,
                total_hours,
                hours_owed,
                overtime,
                ...result
            });
        }

        return results;
    }

    // Create hours record
    static async createRecord(employee_id, week_start, week_end, expected_hours, total_worked_hours) {
        // Prevent duplicates
        if (await this.recordExists(employee_id, week_start)) {
            throw new Error('Record already exists for this week');
        }

        // Calculate both hours owed AND overtime
        let hours_owed = 0;
        let overtime = 0;
        
        if (total_worked_hours < expected_hours) {
            // Employee owes hours
            hours_owed = expected_hours - total_worked_hours;
        } else if (total_worked_hours > expected_hours) {
            // Employee has overtime
            overtime = total_worked_hours - expected_hours;
        }
        // If equal, both remain 0
        
        const hrs_id = await this.getNextId();
        
        const query = `
            INSERT INTO hours_management 
            (hrs_id, employee_id, week_start, week_end, expected_hours, total_worked_hours, hours_owed, overtime)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        `;
        
        await pool.execute(query, [hrs_id, employee_id, week_start, week_end, expected_hours, total_worked_hours, hours_owed, overtime]);
        return { hrs_id, hours_owed, overtime };
    }

    // Get employee hours
    static async getByEmployee(employee_id) {
        const query = `SELECT * FROM hours_management WHERE employee_id = ? ORDER BY week_start DESC`;
        const [rows] = await pool.execute(query, [employee_id]);
        return rows;
    }

    // Get next ID
    static async getNextId() {
        const [rows] = await pool.execute('SELECT MAX(hrs_id) as max_id FROM hours_management');
        return (rows[0].max_id || 0) + 1;
    }
}