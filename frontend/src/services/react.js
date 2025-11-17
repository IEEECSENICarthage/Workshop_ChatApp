import api from "./index"

export default {
    getReacts(){
        return api.get("reacts");
    }
}