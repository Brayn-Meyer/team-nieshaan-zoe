import express from 'express';
import { getClassifications } from '../controllers/classificationController.js';

const router = express.Router();

router.get('/', getClassifications);

export default router;
