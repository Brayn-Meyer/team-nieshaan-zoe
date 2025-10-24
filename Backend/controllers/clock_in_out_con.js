import { getClockInOutData } from "../middleware/clock_in_out_mid.js"

export const getClockInOutDataCon = async (req, res) => {
    try {
        res.json({ clock_in_out_data: await getClockInOutData() })
    } catch (error) {
        console.log(error)
    }
}