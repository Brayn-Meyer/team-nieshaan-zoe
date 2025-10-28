import express from 'express';

import { addEmployeeCon } from '../controllers/employeesCon.js';
import { deleteEmployeeCon } from '../controllers/employeesCon.js';

const router = express.Router();

// route to add a new employee
router.post('/addEmployee/', addEmployeeCon);
router.delete('/deleteEmployee/:id', deleteEmployeeCon);

// You can add more routes later

export default router;
