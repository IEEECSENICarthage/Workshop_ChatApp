<template>
  <div class="home">
    <HeaderComponent></HeaderComponent>
    <Message></Message>
  </div>
</template>

<script>
import Message from "@/components/Message.vue";
import HeaderComponent from "@/components/header.vue";
export default {
  name: "HomeView",
  components: { HeaderComponent, Message },
};
</script>
