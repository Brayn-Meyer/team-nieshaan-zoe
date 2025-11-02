import { addEmployee, deleteEmployee, getRoles, getDepartments } from '../models/employeesDB.js';
import { emitKPIUpdates } from "./admin_cards_con.js";


// function to add employee
export const addEmployeeCon = async (req, res) => {
    try {
        const employee = await addEmployee(req.body);
        res.json({ employee });

        emitKPIUpdates(req.app.get('io'));

    } catch (error) {
        console.error('Error in addEmployeeCon:', error);
        res.status(500).json({ error: 'Failed to add employee' });
    }

};

export const getRolesCon = async (req, res) => {
    try {
        const roles = await getRoles();
        res.json({ roles });
    } catch (error) {
        console.error('Error in getRolesCon:', error);
        res.status(500).json({ error: 'Failed to fetch roles' });
    }
};

export const getDepartmentsCon = async (req, res) => {
    try {
        const departments = await getDepartments();
        res.json({ departments });
    } catch (error) {
        console.error('Error in getDepartmentsCon:', error);
        res.status(500).json({ error: 'Failed to fetch departments' });
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

        emitKPIUpdates(req.app.get('io'));

    } catch (error) {
        console.error('Error in deleteEmployeeCon:', error);
        res.status(500).json({ error: 'Failed to delete employee' });
    }
};