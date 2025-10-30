import express from 'express';
import { getQRStorage } from '../controllers/qrController.js';

const router = express.Router();

router.get('/', getQRStorage);

export default router;
