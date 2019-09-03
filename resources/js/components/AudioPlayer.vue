<template>
  <div class="fixed bottom-0 w-full inset-x-0 bg-white">
    <audio :src="src" ref="audio" @timeupdate="updateTime" />
  </div>
</template>
<script>
export default {
  props: {
    src: String,
    label: String
  },
  data() {
    return {
      currentTime: 0
    };
  },
  methods: {
    updateTime(e) {
      this.currentTime = e.currentTime;
    }
  }
};
</script>

