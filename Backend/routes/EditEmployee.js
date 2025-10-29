import express from "express"
import { EditEmpCon } from "../controllers/EditEmployeeCon.js"

const router = express.Router()

router.patch("/employee/edit/:id", EditEmpCon)

export default router