import express from 'express';
import { config } from 'dotenv';
import cors from 'cors';

import { addEmployeeCon, deleteEmployeeCon } from './controllers/employeesCon.js';
// import { getfilterAll } from './controller/filterAllCon.js'
// import { getfilter } from './controller/filterCon.js'

config();

const app = express();
const PORT = process.env.PORT || 9090;

app.use(cors());
app.use(express.json());

// routes
app.post('/employees', addEmployeeCon);
app.delete('/employees/:id', deleteEmployeeCon);

// example placeholders for your filter routes
// app.get('/filter', getfilterCon);
// app.get('/filterAll', getfilterAllCon);

app.listen(PORT, () => {
    console.log(`✅ Server running on http://localhost:${PORT}`);
});
