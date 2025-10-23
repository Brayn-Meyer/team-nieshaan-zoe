import { addEmployee } from '../middleware/employeesDB.js';
// function to add employee

export const addEmployeeCon = async (req, res)=>{
    try {
        const employee = await addEmployee(req.body);
        res.json({ employee });
    } catch (error) {
        res.status(500).json({ error: 'Failed to add employee' });
    }       
}


// function to remove/delete employee

export const deleteEmployeeCon = async (req, res)=>{
    try{
        const id = req.params.id
        res.json({
            message: `Employee with id ${id} removed successfully`
        })

    } catch (error) {
        res.status(500).json({ error: 'Failed to remove employee' });
        res.status(500).json({
        error: 'Failed to delete employee'
        });
    }
}

