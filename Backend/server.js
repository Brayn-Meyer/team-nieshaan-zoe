import express from 'express';
import { config } from 'dotenv';
import cors from 'cors';
import { createServer } from 'http';
import { Server } from 'socket.io';

import { getClockInOutDataCon } from './controllers/clock_in_out_con.js';
import { getTotalCheckedOutDataCon, getTotalAbsentDataCon, getTotalEmployeesDataCon, getTotalCheckedInDataCon } from './controllers/admin_cards_con.js';
import { addEmployeeCon, deleteEmployeeCon } from './controllers/employeesCon.js';
import { EditEmpCon } from './controllers/EditEmployeeCon.js';
import { getfilterAllCon } from './controllers/filterAllCon.js';
import { getfilterCon } from './controllers/filterCon.js';

config();

const app = express();
const PORT = process.env.PORT || 9090;

const httpServer = createServer(app);

const io = new Server(httpServer, {
  cors: {origin: '*'}
});

app.use(cors());
app.use(express.json());

// Serve static frontend files
app.use(express.static('../Frontend'));

app.set('io', io);

app.get('/filter', getfilterCon);
app.get('/filterAll', getfilterAllCon);



app.get("/totalEmployees", getTotalEmployeesDataCon)
app.get("/checkedIn", getTotalCheckedInDataCon)
app.get("/checkedOut", getTotalCheckedOutDataCon)
app.get("/absent", getTotalAbsentDataCon)


app.get("/clockInOut", getClockInOutDataCon)

// Employee add/delete routes
app.post('/addEmployee', addEmployeeCon);
app.delete('/removeEmployee/:id', deleteEmployeeCon);
app.put('/editEmployee/:employee_id', EditEmpCon);

io.on('connection', (socket) => {

  console.log('User connected:', socket.id);

  socket.on('disconnect', () => {
    console.log('User disconnected:', socket.id);
  });
});

httpServer.listen(PORT, () => {
  console.log(`http://localhost:${PORT}`);
});