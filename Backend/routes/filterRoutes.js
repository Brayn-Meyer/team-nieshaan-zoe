import {pool} from '../config/db.js'
// const getfilter = async(fullname,employee_id,status,date,clock_in,tea_out,tea_in,lunch_out,lunch_in,clock_out) => {
//     let [row] = await pool.query('SELECT * FROM record_backups WHERE fullname,employee_id,status,date,clock_in,clock_out,tea_out,tea_in,lunch_out,lunch_in = ? ', [fullname,employee_id,status,date,clock_in,tea_out,tea_in,lunch_out,lunch_in,clock_out]);
//     return row;
// }
// const getfilterfull_name = async(full_name) => {
//     let [row] = await pool.query('SELECT * FROM record_backups WHERE full_name = ? ', [full_name]);
//     return row;
// }
const getfilterstatus = async(status)=> {
    let [row] = await pool.query('SELECT * FROM record_backups WHERE status = ? ', [status]);
    return row;
}
const getfilterdate = async(date) => {
    let [row] = await pool.query('SELECT * FROM record_backups WHERE date = ? ', [date]);
    return row;
}
const getfilterworkclock_in = async() => {
    let [row] = await pool.query("SELECT * FROM record_backups WHERE clockin_in IS NOT NULL AND type = 'Work'");   // Select column clockin_time and the type of clockin - Work, Tea, Lunch+
    return row;
}
const getfilterworkclock_out = async(workclockout_time) => {
    let [row] = await pool.query("SELECT * FROM record_backups WHERE clockout_time IS NOT NULL AND type = 'Work , '");
    return row;
}
const getfiltertea_out = async() => {
    let [row] = await pool.query("SELECT * FROM record_backups WHERE clockout_time IS NOT NULL AND type = 'Tea';");
    return row;
}
const getfiltertea_in= async() => {
    let [row] = await pool.query("SELECT * FROM record_backups WHERE clockin_time IS NOT NULL AND type = 'Tea';");
    return row;
}
const getfilterlunch_in = async() => {
    let [row] = await pool.query("SELECT * FROM record_backups WHERE clockin_time IS NOT NULL AND type = 'Lunch' ; ");
    return row;
}
const getfilterlunch_out = async(lunch_out) => {
    let [row] = await pool.query("SELECT * FROM record_backups WHERE clockout_time IS NOT NULL AND type = 'Lunch' ; ");
    return row;
}
export {getfilter, getfilterstatus, getfilterfull_name, getfilterlunch_out, getfilterlunch_in, getfiltertea_in, getfiltertea_out, getfilterworkclock_out, getfilterworkclock_in, getfilterdate};