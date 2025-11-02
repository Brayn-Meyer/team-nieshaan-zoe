import express from 'express';
import { getEmpHistory, searchEmpHistory, getEmployeeById} from '../controllers/HistoryCon.js'

const router = express.Router();

router.get('/getHistory/', getEmpHistory)
router.get('/getEmpId/', getEmployeeById)
router.get('/searchHist/', searchEmpHistory)

export default router;