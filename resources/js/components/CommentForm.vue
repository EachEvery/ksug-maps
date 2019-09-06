<template>
  <div>
    <input
      v-model="author"
      type="text"
      class="border-b border-black py-2 font-mono text-black"
      placeholder="Your Name"
    />
    <input
      v-model="email"
      type="email"
      class="border-b border-black py-2 font-mono text-black"
      placeholder="Your Email Address"
    />
    <textarea
      v-model="text"
      class="border-b border-black p-2 font-mono text-black"
      placeholder="Message Text"
    ></textarea>

    <clickable
      class="w-full text-center py-3 px-2 border border-black uppercase"
    >Submit Your Comment</clickable>
  </div>
</template>
<script>
export default {
  data() {
    return {
      author: "",
      email: "",
      text: ""
    };
  }
};
</script>