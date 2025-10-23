import {getfilterAll} from "../routes/filterAll.js"

const getfilterAllCon = async (req , res) => {
    try{
        const filterAll = await getfilterAll();
        res.json({filterAll});
        
    }

}
