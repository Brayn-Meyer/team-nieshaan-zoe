import express from 'express'
import cors from 'cors'

import { getTotalEmployeesData, getTotalCheckedInData, getTotalCheckedOutDataCon, getTotalAbsentDataCon } from '../controllers/admin_cards_con'
import { config } from 'dotenv'

app.get("/totalEmployees", getTotalEmployeesData)
app.get("/checkedIn", getTotalCheckedInData)
app.get("/checkedOut", getTotalCheckedOutDataCon)
app.get("/absent", getTotalAbsentDataCon)
