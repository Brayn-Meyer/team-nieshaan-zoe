import {getfilterAll} from "../routes/filterAll.js"

const getfilterAllCon = async (req , res) => {
    try{
        const filterAll = await getfilterAll();
        res.json({filterAll});

    }catch(error){
        console.error("Error fetching filterAll:", error);
        res.status(500).json({error: "Internal Server Error"});
    }

}
