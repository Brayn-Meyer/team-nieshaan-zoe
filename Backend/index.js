import express from 'express'
import {config} from 'dotenv'

import { addEmployeeCon } from './controllers/employeesCon.js'
import { deleteEmployeeCon } from './controllers/employeesCon.js'

import cors from 'cors'


config()


const app = express()
const PORT = process.env.PORT 
app.use(cors())

// path to get add user
app.post('/employee', addEmployeeCon)



// path to remove user 
app.delete('/employee/:id',deleteEmployeeCon)




app.listen(PORT, ()=>{
    console.log(`http://localhost:${PORT}`);
    
})