import {pool} from '../config/db.js';

// Helper function to create or find classification
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
        console.error('Error creating/finding classification:', error);
        throw error;
    }
};

// sql to add new employee

export const addEmployee = async (employee) => {
    try {
        console.log('Received employee data:', JSON.stringify(employee, null, 2));
        
        // Create or find classification if roles and department are provided
        let classificationId = employee.classification_id;
        
        if (employee.department && employee.roles && !classificationId) {
            console.log('Creating classification for:', employee.department, employee.roles);
            classificationId = await createOrFindClassification(
                employee.department, 
                employee.roles, 
                employee.user_type || 'Employee'
            );
            console.log('Classification ID:', classificationId);
        }

        // Map frontend fields to database fields
        const isAdmin = employee.user_type === 'Admin' ? 1 : 0;
        
        // Map frontend status to database employment_status enum
        const statusMap = {
            'on-site': 'Active',
            'home': 'Active', 
            'Active': 'Active',
            'Inactive': 'Inactive',
            'OnLeave': 'OnLeave',
            'Terminated': 'Terminated'
        };
        const employmentStatus = statusMap[employee.status] || 'Active';

        // Validate and truncate contact number to fit database limit (10 chars)
        const contactNo = employee.contact_no ? employee.contact_no.toString().slice(0, 10) : null;

        // Format date for MySQL DATE column (YYYY-MM-DD)
        let formattedDateHired = employee.date_hired;
        if (employee.date_hired) {
            const date = new Date(employee.date_hired);
            if (!isNaN(date.getTime())) {
                formattedDateHired = date.toISOString().split('T')[0];
            }
        }

        const mappedData = [
            employee.first_name,
            employee.last_name,
            contactNo,
            employee.email,
            employee.address,
            employee.id, // This maps to the 'id' field in DB (ID number like national ID)
            isAdmin,
            employmentStatus,
            formattedDateHired,
            employee.supervisor_name,
            employee.leave_balance,
            employee.username,
            employee.password,
            classificationId || 1  // Default to classification_id 1 if none provided
        ];

        console.log('Mapped data for SQL:', mappedData);

        const sql = `
            INSERT INTO employees (
                first_name, 
                last_name, 
                contact_no, 
                email, 
                address, 
                id, 
                is_admin, 
                employment_status, 
                date_hired, 
                supervisor_name, 
                leave_balance, 
                username, 
                password,
                classification_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        `;

        const [result] = await pool.execute(sql, mappedData);

        console.log(`Successfully added employee: ${employee.first_name} ${employee.last_name}`);
        return { employee_id: result.insertId, ...employee };

    } catch (error) {
        console.error('Error adding employee:', error);
        console.error('Error details:', {
            message: error.message,
            code: error.code,
            sqlState: error.sqlState,
            sqlMessage: error.sqlMessage
        });
        throw error;
    }
};

// sql query to remove/delete employee 

export const deleteEmployee = async (em_id) => { 
    try {
        // Use employee_id as the primary key for deletion
        const sql = 'DELETE FROM employees WHERE employee_id = ?';
        const [result] = await pool.execute(sql, [em_id]);

        if (result.affectedRows > 0) {
            console.log(`Successfully removed employee with id ${em_id}`);
        } else {
            console.log(`No employee found with id: ${em_id}`);
        }
        return result.affectedRows;

    } catch (error) {
        console.error('Error deleting employee:', error);
        throw error;
    }
}

export const getRoles = async () => {
    try {
        const sql = 'SELECT DISTINCT role FROM tracker_db.emp_classification';
        const [rows] = await pool.execute(sql);
        return rows.map(row => row.role);
    } catch (error) {
        console.error('Error fetching roles:', error);
        throw error;
    }
}

export const getDepartments = async () => {
    try {
        const sql = 'SELECT DISTINCT department FROM tracker_db.emp_classification';
        const [rows] = await pool.execute(sql);
        return rows.map(row => row.department);
    } catch (error) {
        console.error('Error fetching departments:', error);
        throw error;
    }
}
