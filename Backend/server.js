import express from 'express';
import { config } from 'dotenv';        

import cors from 'cors';

config();

const app = express();
const PORT = process.env.PORT || 9090;

app.use(cors());
app.use(express.json());


import { getfilterAllCon } from "./controllers/filterAllCon.js";
import {getfilterCon} from './controllers/filterCon.js';


app.get('/filter', getfilterCon );
app.get('/filterAll', getfilterAllCon );

app.listen(PORT, () => {
    console.log(`http://localhost:${PORT}`);
});