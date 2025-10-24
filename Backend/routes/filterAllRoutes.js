import {pool} from '../config/db.js'

const getfilterAll = async() => {
    let [row] = await pool.query('SELECT * FROM record_backups');
    return row;
}

export {getfilterAll};