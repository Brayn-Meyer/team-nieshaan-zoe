import { getTotalEmployeesData, getTotalCheckedInData, getTotalCheckedOutData, getTotalAbsentData } from "../middleware/admin_cards_mid.js"

export const getTotalEmployeesDataCon = async (req, res) => {
    try {
        res.json({ total_employees: await getTotalEmployeesData() })
    } catch (error) {
        console.log(error)
    }
}

export const getTotalCheckedInDataCon = async (req, res) => {
    try {
        res.json({ checked_in: await getTotalCheckedInData() })
    } catch (error) {
        console.log(error)
    }
}

export const getTotalCheckedOutDataCon = async (req, res) => {
    try {
        res.json({ checked_out: await getTotalCheckedOutData() })
    } catch (error) {
        console.log(error)
    }
}

export const getTotalAbsentDataCon = async (req, res) => {
    try {
        res.json({ absent: await getTotalAbsentData() })
    } catch (error) {
        console.log(error)
    }
}
