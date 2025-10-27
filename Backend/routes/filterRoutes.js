import {pool} from '../config/db.js'

const getfilter = async(fullname,employee_id,status,date,clock_in,tea_out,tea_in,lunch_out,lunch_in,clock_out) => {
    let [row] = await pool.query('SELECT * FROM record_backups WHERE fullname,employee_id,status,date,clock_in,tea_out,tea_in,lunch_out,lunch_in,clock_out = ? ', [fullname,employee_id,status,date,clock_in,tea_out,tea_in,lunch_out,lunch_in,clock_out]);
    return row;
}

const getfilterstatus = async(status)=> {
    let [row] = await pool.query('SELECT * FROM record_backups WHERE status = ? ', [status]);
    return row;
}

const getfilterdate = async(date) => {
    let [row] = await pool.query('SELECT * FROM record_backups WHERE date = ? ', [date]);
    return row;
}

const getfilterclock_in = async(id) => {
    let [row] = await pool.query('SELECT * FROM record_backups WHERE clock_in = ? ', [clock_in]);
    return row;
}

const getfilterclock_out = async(id) => {
    let [row] = await pool.query('SELECT * FROM record_backups WHERE clock_out = ? ', [clock_out]);
    return row;
}
const getfiltertea_out = async(tea_out) => {
    let [row] = await pool.query('SELECT * FROM record_backups WHERE tea_out = ? ', [tea_out]);
    return row;
}
const getfiltertea_in= async(tea_in) => {
    let [row] = await pool.query('SELECT * FROM record_backups WHERE tea_in = ? ', [tea_in]);
    return row;
}
const getfilterlunch_in = async(lunch_in) => {
    let [row] = await pool.query('SELECT * FROM record_backups WHERE lunch_in = ? ', [lunch_in]);
    return row;
}
const getfilterlunch_out = async(lunch_out) => {
    let [row] = await pool.query('SELECT * FROM record_backups WHERE lunch_out = ? ', [lunch_out]);
    return row;
}
const getfilterfull_name = async(full_name) => {
    let [row] = await pool.query('SELECT * FROM record_backups WHERE full_name = ? ', [full_name]);
    return row;
}






export {getfilter, getfilterstatus, getfilterfull_name, getfilterlunch_out, getfilterlunch_in, getfiltertea_in, getfiltertea_out, getfilterclock_out, getfilterclock_in, getfilterdate};
