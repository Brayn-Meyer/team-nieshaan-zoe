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
YEARWEEK(r.date, 1) AS week_number,
MIN(STR_TO_DATE(CONCAT(YEARWEEK(r.date, 1), ' Monday'), '%X%V %W')) AS week_start,
MAX(DATE_ADD(STR_TO_DATE(CONCAT(YEARWEEK(r.date, 1), ' Monday'), '%X%V %W'), INTERVAL 6 DAY)) AS week_end,
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
    
    // Add week filter if provided
    if (weekStart) {
      query += ` AND r.date >= ? AND r.date < DATE_ADD(?, INTERVAL 7 DAY)`;
      params.push(weekStart, weekStart);
    }

    query += `
GROUP BY e.employee_id, YEARWEEK(r.date, 1)
ORDER BY week_number DESC`;

    const [rows] = await pool.query(query, params);
    return rows;
  } catch (err) {
    console.error(err);
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
        const query = `SELECT * FROM hours_management WHERE week_start = ?`;
        const [rows] = await pool.execute(query, [week_start]);
        return rows;
    }

    // Create hours record
    static async createRecord(employee_id, week_start, week_end, expected_hours, total_worked_hours) {
        // Prevent duplicates
        if (await this.recordExists(employee_id, week_start)) {
            throw new Error('Record already exists for this week');
        }

        // Calculate both hours owed AND overtime
        let hour_owed = 0;
        let overtime = 0;
        
        if (total_worked_hours < expected_hours) {
            // Employee owes hours
            hour_owed = expected_hours - total_worked_hours;
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
        
        await pool.execute(query, [hrs_id, employee_id, week_start, week_end, expected_hours, total_worked_hours, hour_owed, overtime]);
        return { hrs_id, hour_owed, overtime };
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