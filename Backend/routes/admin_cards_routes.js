import express from 'express'

import { getTotalEmployeesDataCon, getTotalCheckedInDataCon, getTotalCheckedOutDataCon, getTotalAbsentDataCon, getAllKpiDataCon } from '../controllers/admin_cards_con.js'

const router = express.Router()


router.get("/totalEmployees", getTotalEmployeesDataCon)
router.get("/checkedIn", getTotalCheckedInDataCon)
router.get("/checkedOut", getTotalCheckedOutDataCon)
router.get("/absent", getTotalAbsentDataCon)
router.get("/allKpiData", getAllKpiDataCon)

export default router
