<template>
  <div>
    <div v-if="Loading" class="flex items-center justify-center px-4">Loading...</div>
    <div
      v-if="messages.length === 0 && !Loading"
      class="flex items-center justify-center px-4 mb-8"
    >
      <div
        class="bg-white bg-opacity-70 rounded-lg p-6 shadow-md max-w-lg text-gray-700 text-justify"
      >
        <p>
          Hello! Nice to see you here. This is your space to introduce yourself, share
          which IEEE chapter or field you’re part of, ask any questions, or connect and
          network with other members. Feel free to start the conversation and make the
          most of this community.
        </p>
      </div>
    </div>
    <div v-else class="p-2 flex justify-center items-center flex-col space-y-2">
      <div
        ref="messagesContainer"
        class="max-w-lg w-full h-80 overflow-y-auto custom-scroll scroll-smooth"
      >
        <div v-for="(chat, index) in messages" :key="index">
          <transition name="fade-collapse">
            <div
              :key="chat.id"
              :class="index % 2 === 0 ? 'mb-1 p-5' : 'text-right p-5 mb-1'"
            >
              <p class="px-2">{{ chat.username }}</p>
              <div
                @click="toggleReaction(index)"
                class="cursor-pointer relative bg-white inline-block hover:rounded-none bg-opacity-70 hover:bg-opacity-60 rounded-lg p-3 shadow-md max-w-lg text-gray-700 transition text-justify"
              >
                {{ chat.message }}
                <button @click="deleteMessage(chat.id)" class="absolute bottom-0 right-1">
                  <TrashIcon class="h-6 w-3 pt-2 text-red-500" />
                </button>
              </div>
              <div v-if="activeIndex === index">
                <button
                  v-for="react in reacts"
                  class="text-xl hover:scale-125 transition"
                  @click="AddReact(react.id, chat.id)"
                >
                  <span class="text-[15px]">
                    {{
                      (() => {
                        const index = chat.reacts.findIndex(
                          (val) => val.pivot.react_id == react.id
                        );
                        return index !== -1 ? chat.reacts[index].pivot.nbr : 0;
                      })()
                    }} </span
                  >{{ react.name == "like" ? "👍" : react.name == "party" ? "🎉" : "🔥" }}
                </button>
              </div>
            </div>
          </transition>
        </div>
      </div>
    </div>
    <div class="p-2 flex justify-center items-center flex-col space-y-2">
      <div class="flex justify-center items-center w-full">
        <input
          type="text"
          v-model="message"
          placeholder="Type your message..."
          class="border w-80 border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400"
        />
        <button
          :disabled="loading_btn"
          @click="AddMessage()"
          class="text-orange-500 hover:text-orange-600 transition"
        >
          <PaperAirplaneIcon v-if="!loading_btn" class="h-6 w-6" />
          <ArrowPathIcon v-if="loading_btn" class="h-6 w-6"></ArrowPathIcon>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { PaperAirplaneIcon, TrashIcon, ArrowPathIcon } from "@heroicons/vue/24/solid";
import chat_service from "@/services/chat";
import react_service from "@/services/react";

export default {
  data() {
    return {
      activeIndex: null,
      messages: [],
      reacts: [],
      message: "",
      Loading: true,
      username: localStorage.getItem("username") ?? null,
      loading_btn: false,
    };
  },
  mounted() {
    this.scrollToBottom();
    this.getMessages();
    this.getReacts();
  },
  methods: {
    deleteMessage(id) {
      chat_service.deleteMessage(id).then((res) => {
        this.getMessages();
      });
    },
    AddMessage() {
      if (this.message === "") {
        alert("Message Required");
        return;
      }

      let secretCode = localStorage.getItem("chatSecretCode");
      if (!secretCode) {
        secretCode = prompt("Please enter the secret code to send messages:");
        if (!secretCode) {
          alert("Secret code is required!");
          return;
        }
        localStorage.setItem("chatSecretCode", secretCode);
      }

      let name = this.username ?? prompt("Enter your Name");
      if (!name) {
        alert("Name is required");
        return;
      }

      this.loading_btn = true;
      chat_service
        .AddMessage({
          message: this.message,
          name: name,
          secret_code: secretCode,
        })
        .then((res) => {
          localStorage.setItem("username", name);
          this.messages = [];
          this.getMessages();
          this.message = "";
          this.loading_btn = false;
        })
        .catch((err) => {
          if (err.response && err.response.status === 403) {
            alert("Invalid secret code! Please enter again.");
            localStorage.removeItem("chatSecretCode");
          } else {
            alert("Error sending message.");
          }
          this.loading_btn = false;
        });
    },
    scrollToBottom() {
      this.$nextTick(() => {
        const el = this.$refs.messagesContainer;
        if (el) {
          el.scrollTop = el.scrollHeight;
        }
      });
    },
    toggleReaction(index) {
      this.activeIndex = index;
    },
    getMessages() {
      this.Loading = true;
      chat_service.getChats().then(
        (res) => {
          this.messages = res.data.data;
          this.scrollToBottom();
          this.Loading = false;
        },
        (error) => {
          this.Loading = false;
        }
      );
    },
    getReacts() {
      react_service.getReacts().then((res) => {
        this.reacts = res.data.data;
      });
    },
    AddReact(react_id, chat_id) {
      chat_service
        .AddReactChat({
          chat_id: chat_id,
          react_id: react_id,
        })
        .then((res) => {
          this.getMessages();
        });
    },
  },
  components: {
    PaperAirplaneIcon,
    TrashIcon,
    ArrowPathIcon,
  },
};
</script>

<style>
::-webkit-scrollbar {
  width: 4px;
}

::-webkit-scrollbar-track {
  background: #f0f0f0;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: orange;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgb(231, 150, 0);
}

.fade-collapse-enter-active,
.fade-collapse-leave-active {
  transition: all 0.3s ease;
}

.fade-collapse-enter-from,
.fade-collapse-leave-to {
  opacity: 0;
  transform: translateY(10px);
  max-height: 0;
  margin: 0;
  padding: 0;
}
</style>
