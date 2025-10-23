import mysql from "mysql2/promise"
import {config} from 'dotenv'

config()

export const pool = mysql.createPool({
    database: process.env.DATABASE,
    user: process.env.USER,
    password: process.env.PASSWORD,
    host: process.env.HOST
})
