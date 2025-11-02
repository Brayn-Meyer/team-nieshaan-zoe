import express from 'express'

import { getClockInOutDataCon, getHoursWorkedCon, getTimeLogData, processWeeklyHours, getProcessedWeeks, HoursController } from '../controllers/clock_in_out_con.js'

const router = express.Router()

router.get("/clockInOut", getClockInOutDataCon);
router.get('/getHoursWorked/', getHoursWorkedCon);
router.get('/getTimeLogData', getTimeLogData);
router.post('/processWeeklyHours', processWeeklyHours);  // New: Process raw data into hours_management
router.get('/processedWeeks', getProcessedWeeks);        // New: Get available processed weeks
router.post('/createRecord', HoursController.createRecord);
router.get('/hours/:employee_id', HoursController.getEmployeeHours);

export default router
