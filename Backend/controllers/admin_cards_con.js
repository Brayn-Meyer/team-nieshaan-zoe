import { getTotalEmployeesData, getTotalCheckedInData, getTotalCheckedOutData, getTotalAbsentData } from "../middleware/admin_cards_mid.js"

// These controllers ONLY handle HTTP requests
export const getTotalEmployeesDataCon = async (req, res) => {
    try {
        const total_employees = await getTotalEmployeesData();
        res.json({ total_employees });
    } catch (error) {
        console.log(error);
        res.status(500).json({ error: 'Failed to fetch total employees' });
    }
}

export const getTotalCheckedInDataCon = async (req, res) => {
    try {
        const checked_in = await getTotalCheckedInData();
        res.json({ checked_in });
    } catch (error) {
        console.log(error);
        res.status(500).json({ error: 'Failed to fetch checked in data' });
    }
}

export const getTotalCheckedOutDataCon = async (req, res) => {
    try {
        const checked_out = await getTotalCheckedOutData();
        res.json({ checked_out });
    } catch (error) {
        console.log(error);
        res.status(500).json({ error: 'Failed to fetch checked out data' });
    }
}

export const getTotalAbsentDataCon = async (req, res) => {
    try {
        const absent = await getTotalAbsentData();
        res.json({ absent });
    } catch (error) {
        console.log(error);
        res.status(500).json({ error: 'Failed to fetch absent data' });
    }
}

// Separate function to emit real-time updates (call this when data changes)

export const emitKPIUpdates = async (io) => {
    try {
        const [total_employees, checked_in, checked_out, absent] = await Promise.all([
            getTotalEmployeesData(),
            getTotalCheckedInData(),
            getTotalCheckedOutData(),
            getTotalAbsentData()
        ]);

        io.emit('kpiUpdate', {
            total_employees,
            checked_in,
            checked_out,
            absent
        });
    } catch (error) {
        console.log('Error emitting KPI updates:', error);
    }
}  
