import express from 'express';
import { config } from 'dotenv';
import cors from 'cors';

import { addEmployeeCon, deleteEmployeeCon } from './controllers/employeesCon.js';
import { getfilterAllCon } from './controllers/filterAllCon.js';
import { getfilterCon } from './controllers/filterCon.js';

config();

const app = express();
const PORT = process.env.PORT || 9090;

app.use(cors());
app.use(express.json());

app.get('/filter', getfilterCon);
app.get('/filterAll', getfilterAllCon);

import { getTotalEmployeesData, getTotalCheckedInData, getTotalCheckedOutDataCon, getTotalAbsentDataCon } from '../controllers/admin_cards_con'
import { config } from 'dotenv'

app.get("/totalEmployees", getTotalEmployeesData)
app.get("/checkedIn", getTotalCheckedInData)
app.get("/checkedOut", getTotalCheckedOutDataCon)
app.get("/absent", getTotalAbsentDataCon)

import { getClockInOutDataCon } from '../controllers/clock_in_out_con'

app.get("/clockInOut", getClockInOutDataCon)

import { addEmployeeCon } from '../controllers/employeesCon.js';
import { deleteEmployeeCon } from '../controllers/employeesCon.js';

// route to add a new employee
router.post('/addEmployee', addEmployeeCon);
router.delete('/removeEmployee/:id', deleteEmployeeCon);

app.listen(PORT, () => {
  console.log(`http://localhost:${PORT}`);
});