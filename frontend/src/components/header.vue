<template>
  <div class="flex flex-col items-center py-8 space-y-4">
    <img :src="img" alt="CS Logo" class="w-24" />
    <p class="font-extrabold text-orange-500 text-2xl tracking-wide">IEEE COMMUNITY</p>
    <div
      class="bg-white bg-opacity-50 rounded-lg p-2 flex justify-around items-center w-40 shadow-sm"
    >
      <button
        @click="animate"
        class="flex items-center space-x-1 text-blue-600 hover:text-blue-800 transition pr-4 border-r-2"
      >
        <span class="font-semibold">{{ likes }}</span>
        <HandThumbUpIcon
          ref="likeBtn"
          :class="isClicked ? 'animate-bounce-up h-6 w-6 ' : 'border-white h-6 w-6 '"
        />
      </button>

      <button
        class="flex items-center space-x-1 text-red-600 hover:text-red-800 transition"
      >
        <HandThumbDownIcon class="h-6 w-6" />
      </button>
    </div>
  </div>
</template>

<script>
import { HandThumbUpIcon, HandThumbDownIcon } from "@heroicons/vue/24/outline";
import confetti from "canvas-confetti";
import chat_service from "@/services/chat";
export default {
  name: "Header",
  data() {
    return {
      likes: 0,
      img: require("../assets/images/cs.png"),
      isClicked: false,
    };
  },
  mounted() {
    this.AllLikes();
  },
  methods: {
    AllLikes() {
      chat_service.Likes().then((res) => {
        console.log(res.data);
        this.likes = res.data.data;
      });
    },
    AddLikes() {
      chat_service.AddLike().then((res) => {
        this.AllLikes();
      });
    },
    animate() {
      this.AddLikes();
      if (this.isClicked) return;
      this.isClicked = true;
      this.launchConfetti();
      setTimeout(() => {
        this.isClicked = false;
      }, 400);
    },
    launchConfetti() {
      const btn = this.$refs.likeBtn;
      if (!btn) return;

      const rect = btn.getBoundingClientRect();
      const x = (rect.left + rect.right) / 2 / window.innerWidth;
      const y = rect.top / window.innerHeight;

      confetti({
        particleCount: 15,
        spread: 25,
        origin: { x, y: y + 0.08 },
        colors: ["#3b82f6", "#2563eb", "#1e40af"],
      });
    },
  },
  components: {
    HandThumbUpIcon,
    HandThumbDownIcon,
  },
};
</script>

<style scoped>
@keyframes bounceUpDown {
  0% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(-10px);
  }

  100% {
    transform: translateY(0);
  }
}

.animate-bounce-up {
  animation: bounceUpDown 0.4s ease;
}
</style>
