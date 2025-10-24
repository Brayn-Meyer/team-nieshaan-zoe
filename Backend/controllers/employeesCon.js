import { addEmployee, deleteEmployee } from '../middleware/employeesDB.js';

// function to add employee

export const addEmployeeCon = async (req, res) => {
    try {
        const employee = await addEmployee(req.body);
        res.json({ employee });
    } catch (error) {
        console.error('Error in addEmployeeCon:', error);
        res.status(500).json({ error: 'Failed to add employee' });
    }
};



// function to remove/delete employee

export const deleteEmployeeCon = async (req, res) => {
    try {
        const id = req.params.id;
        const deletedRows = await deleteEmployee(id);

        if (deletedRows > 0) {
            res.json({ message: `Employee with id ${id} removed successfully` });
        } else {
            res.status(404).json({ error: `No employee found with id: ${id}` });
        }

    } catch (error) {
        console.error('Error in deleteEmployeeCon:', error);
        res.status(500).json({ error: 'Failed to delete employee' });
    }
};
