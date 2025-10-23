import express from 'express'
import cors from 'cors'

import { getClockInOutDataCon } from '../controllers/clock_in_out_con'

app.get("/clockInOut", getClockInOutDataCon)
