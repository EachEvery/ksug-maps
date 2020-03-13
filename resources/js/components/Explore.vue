<template>
  <div
    class="fixed inset-x-0 top-0 bg-white px-8 pt-4"
    style="transform: translateY(87%); min-height: 100vh;"
  >
    <div class="flex justify-between items-center">
      <h3 class="text-lg uppercase font-light font-mono">Explore Tours &amp; Stories</h3>
      <up-arrow class="w-8 text-black" />
    </div>

    <h1 class="text-8xl uppercase font-display">Explore</h1>
  </div>
</template>
<script>
import upArrow from "./UpArrow";

export default {
  components: {
    upArrow
  }
};
</script>