import express from "express"
import { EditEmpCon } from "../controllers/EditEmployeeCon.js"

const router = express.Router()

router.put("employee/edit/:employee_id", EditEmpCon)

export default router