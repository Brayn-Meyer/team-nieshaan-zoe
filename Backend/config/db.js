import mysql from 'mysql2/promise';
import {config} from 'dotenv';

config();


export const pool = mysql.createPool({
    host:process.env.host ,
    database:process.env.database,
    user:process.env.user ,
    password:process.env.password,

}) ;