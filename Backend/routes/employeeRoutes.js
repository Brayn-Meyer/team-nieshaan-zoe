import express from 'express';
import { getEmployees, getEmployeeById, searchEmployees } from '../controllers/employeeController.js';

const router = express.Router();

// Debug middleware
router.use((req, res, next) => {
  console.log('Employee route accessed:', req.method, req.url);
  next();
});

// Employee routes
router.get('/', getEmployees);                   // GET /api/employees
router.post('/search', searchEmployees);         // POST /api/employees/search
router.get('/:id', getEmployeeById);             // GET /api/employees/:id

// router.post('/test', (req, res) => {            // POST /api/employees/test
//   console.log('Test route hit');
//   res.json({ ok: true, message: 'Employee route test successful' });
// });

export default router;
