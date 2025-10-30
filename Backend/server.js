import express from 'express';
import { config } from 'dotenv';
import cors from 'cors';
import { createServer } from 'http';
import { Server } from 'socket.io';

// Import all routes instead
import admin_cards_routes from './routes/admin_cards_routes.js';
import clock_in_out_routes from './routes/clock_in_out_routes.js';
import employeesRoutes from './routes/employeesRoutes.js';
// import filterRoutes from './routes/filterRoutes.js';
import filterAllRoutes from './routes/filterAllRoutes.js';
import EditEmployeeRoutes from './routes/EditEmployee.js';

import employeeRoutes from './routes/employeeRoutes.js';
import classificationRoutes from './routes/classificationRoutes.js';
import notificationRoutes from './routes/notificationRoutes.js';
import qrRoutes from './routes/qrRoutes.js';

config();

const app = express();
const PORT = process.env.PORT || 9090;

const httpServer = createServer(app);

const io = new Server(httpServer, {
  cors: {origin: '*'}
});

app.use(cors());
app.use(express.json());

// Make io available to all routes
app.set('io', io);

// Use routes instead of direct controller calls
app.use('/api/admin/cards', admin_cards_routes);
app.use('/api/clock-in-out', clock_in_out_routes);
app.use('/api/employees', employeesRoutes);
// app.use('/api/filter', filterRoutes);
app.use('/api/filter-all', filterAllRoutes);
app.use('/api/edit-employee', EditEmployeeRoutes);

app.use('/api/employees', employeeRoutes);
app.use('/api/classifications', classificationRoutes);
app.use('/api/notifications', notificationRoutes);
app.use('/api/qr-storage', qrRoutes);

io.on('connection', (socket) => {
  console.log('User connected:', socket.id);

  socket.on('disconnect', () => {
    console.log('User disconnected:', socket.id);
  });
});

httpServer.listen(PORT, () => {
  console.log(`http://localhost:${PORT}`);
});
