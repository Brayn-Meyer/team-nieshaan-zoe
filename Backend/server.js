import express from 'express';
import { config } from 'dotenv';        

import { } from './server';

import cors from 'cors';

import { addEmployeeCon, deleteEmployeeCon } from './controllers/employeesCon.js';
// import { getfilterAll } from './controller/filterAllCon.js'
// import { getfilter } from './controller/filterCon.js'

config();

const app = express();
const PORT = process.env.PORT || 9090;

app.use(cors());
app.use(express.json());


import {"getfilterAll"} from "./controller/filterAllCon"
import {"getfilter"} from "./controller/filterCon"


app.get('/filter', getfilterCon );
app.get('/filterAll', getfilterAllCon );

app.listen(PORT , () => {
    console.log('http//localhost:${PORT}')
}

)