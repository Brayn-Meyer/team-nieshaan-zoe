import express from 'express';

import { addEmployeeCon, deleteEmployeeCon, getRolesCon, getDepartmentsCon, getHoursWorkedCon } from '../controllers/employeesCon.js';

const router = express.Router();

router.post('/addEmployee/', addEmployeeCon);
router.delete('/deleteEmployee/:id', deleteEmployeeCon);
router.get('/getRoles/', getRolesCon);
router.get('/getDepartments/', getDepartmentsCon);
router.get('/getHoursWorked/', getHoursWorkedCon);

export default router;
