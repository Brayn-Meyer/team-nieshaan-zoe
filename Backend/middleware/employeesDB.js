import db from '../config/db.js'

// sql to add new employee

export const addEmployee = async (employee) =>{
    // sql query still needs to be fixed 
    try{
        const sql = 'INSERT INTO employees (first_name, email) VALUES (?, ?)'
        const [result] = await db.pool.execute(sql, [employee.name, employee.email])
        return {id: result.insertId, ...employee}       
    } catch (error) {
        console.error('Error adding employee:', error)
        throw error 
    }
}

// sql query to remove/delete employee 

export const deleteEmployee = async (em_id) => { 
    try {
        const sql = 'DELETE FROM employees WHERE employee_id = ?';
        const [result] = await db.execute(sql, [em_id]);

        if (result.affectedRows > 0) {
            console.log(`Successfully removed employee with id ${em_id}`);
        } else {
            console.log(`No employee found with id : ${em_id}`);
        }

    } catch (error) {
        console.error('Error deleting employee:', error);
        throw error;
    }
}

// deleteEmployee(11)
