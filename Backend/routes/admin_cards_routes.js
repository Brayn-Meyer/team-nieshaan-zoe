import express from 'express'
import cors from 'cors'

import { getTotalEmployeesDataCon, getTotalCheckedInDataCon, getTotalCheckedOutDataCon, getTotalAbsentDataCon, getAllKpiDataCon } from '../controllers/admin_cards_con.js'
import { config } from 'dotenv'

const router = express.Router()


router.get("/totalEmployees", getTotalEmployeesDataCon)
router.get("/checkedIn", getTotalCheckedInDataCon)
router.get("/checkedOut", getTotalCheckedOutDataCon)
router.get("/absent", getTotalAbsentDataCon)
router.get("/allKpiData", getAllKpiDataCon)

export default router
