import {pool} from '../config/db.js';

// sql to add new employee



export const addEmployee = async (employee) => {
    try {
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

        const [result] = await pool.execute(sql, [
            employee.first_name,
            employee.last_name,
            employee.contact_no,
            employee.email,
            employee.address,
            employee.id,
            employee.is_admin,
            employee.employment_status,
            employee.date_hired,
            employee.supervisor_name,
            employee.leave_balance,
            employee.username,
            employee.password,
            employee.classification_id
        ]);

        console.log(`Successfully added employee: ${employee.first_name} ${employee.last_name}`);
        return { id: result.insertId, ...employee };

    } catch (error) {
        console.error('Error adding employee:', error);
        throw error;
    }
};

// sql query to remove/delete employee 

export const deleteEmployee = async (em_id) => { 
    try {
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


