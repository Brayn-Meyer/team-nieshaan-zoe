import express from 'express'
import cors from 'cors'

import { getClockInOutDataCon } from '../controllers/clock_in_out_con.js'

const router = express.Router()

router.get("/clockInOut", getClockInOutDataCon)

export default router
