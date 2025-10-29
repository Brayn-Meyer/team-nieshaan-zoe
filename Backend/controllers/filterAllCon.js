import { getfilterAll } from "../middleware/clock_in_out_mid.js";

const getfilterAllCon = async (req, res) => {
  try {
    const filterAll = await getfilterAll();
    res.json({ filterAll });
  } catch (err) {
    console.error("Error fetching data:", err);
    res.status(500).json({ error: "Internal server error" });
  }
};






export { getfilterAllCon };
