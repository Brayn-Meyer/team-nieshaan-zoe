<<<<<<< HEAD
import mysql from 'mysql2/promise';
import {config} from 'dotenv';

config();


export const pool = mysql.createPool({
    host:process.env.host ,
    database:process.env.database,
    user:process.env.user ,
    password:process.env.password,

}) ;
=======
import mysql from "mysql2/promise"
import {config} from 'dotenv'

config()

export const pool = mysql.createPool({
    database: process.env.DATABASE,
    user: process.env.USER,
    password: process.env.PASSWORD,
    host: process.env.HOST
})
>>>>>>> 8fcf4ba201ea6d5f4b17a9f8962e8db75f331f53
