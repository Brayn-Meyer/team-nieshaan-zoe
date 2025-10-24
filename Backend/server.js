import express from 'express';
import { config } from 'dotenv';
import cors from 'cors';

import { addEmployeeCon, deleteEmployeeCon } from './controllers/employeesCon.js';
import { getfilterAllCon } from './controllers/filterAllCon.js';
import { getfilterCon } from './controllers/filterCon.js';
import editEmployeeRoutes from './routes/EditEmployee.js';

=======
import { getClockInOutDataCon } from './controllers/clock_in_out_con.js';
import {
  getTotalEmployeesDataCon,
  getTotalCheckedInDataCon,
  getTotalCheckedOutDataCon,
  getTotalAbsentDataCon,
} from './controllers/admin_cards_con.js';
>>>>>>> 926bb8208f08d4bec390de30a8ccf3d0a650df19

config();

const app = express();
const PORT = process.env.PORT || 9090;

app.use(cors());
app.use(express.json());

// ===== Routes =====

// Filters
app.get('/filter', getfilterCon);
app.get('/filterAll', getfilterAllCon);
app.use('/employee', editEmployeeRoutes);



// Admin Cards
app.get('/totalEmployees', getTotalEmployeesDataCon);
app.get('/checkedIn', getTotalCheckedInDataCon);
app.get('/checkedOut', getTotalCheckedOutDataCon);
app.get('/absent', getTotalAbsentDataCon);

// Clock In/Out
app.get('/clockInOut', getClockInOutDataCon);

// Employees
app.post('/addEmployee', addEmployeeCon);
app.delete('/removeEmployee/:id', deleteEmployeeCon);

// ===== Start Server =====
app.listen(PORT, () => {
  console.log(`✅ Server running at http://localhost:${PORT}`);
});