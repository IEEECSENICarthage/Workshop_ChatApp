import api from "./index"

export default {
    getChats() {
        return api.get("chats");
    },
    AddReactChat(data) {
        return api.post("AddReactToChat", data);
    },
    deleteMessage(id) {
        return api.delete("DeleteChat/" + id);
    },
    AddMessage(data) {
        return api.post("AddMessage", data);
    },
    AddLike() {
        return api.post("AddLike");
    },
    Likes() {
        return api.get("Likes");
    }
}