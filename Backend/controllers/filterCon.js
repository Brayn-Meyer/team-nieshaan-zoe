import {getfilter} from "../routes/filterRoutes.js"

const getfilterCon = async (req , res) => {
    try{
        const filter = await getfilter();
        res.json({filter});
    } catch (err) {
    console.error("Error fetching data:", err);
    res.status(500).json({ error: "Internal server error" });
  }
};

export {getfilterCon};