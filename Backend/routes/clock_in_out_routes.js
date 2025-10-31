import express from 'express'

import { getClockInOutDataCon, getHoursWorkedCon, HoursController } from '../controllers/clock_in_out_con.js'

const router = express.Router()

router.get("/clockInOut", getClockInOutDataCon);
router.get('/getHoursWorked/', getHoursWorkedCon);
router.post('/createRecord', HoursController.createRecord);
router.get('/hours/:employee_id', HoursController.getEmployeeHours);


export default router
