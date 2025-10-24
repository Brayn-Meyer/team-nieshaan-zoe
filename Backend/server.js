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

app.listen(PORT, () => {
  console.log(`http://localhost:${PORT}`);
});