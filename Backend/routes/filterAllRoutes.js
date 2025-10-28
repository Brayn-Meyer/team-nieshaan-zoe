import { getfilterAllCon } from "../controllers/filterAllCon.js";
import express from 'express';
 
const router = express.Router();
router.get("/filterAll", getfilterAllCon);

export default router;