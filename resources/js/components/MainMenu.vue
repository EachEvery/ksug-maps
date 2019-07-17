<template>
  <ul class="flex flex-col text-center font-display text-5xl max-w-xs mx-auto leading-tight mt-24">
    <li class="mb-10 shrink-when-active" :class="{'opacity-25': isMap}">
      <a href="#">MAP</a>
    </li>
    <li class="mb-10 shrink-when-active">
      <a href="#">ABOUT THE PROJECT</a>
    </li>
    <li class="mb-10 shrink-when-active">
      <a href="#">MAILING LIST</a>
    </li>
  </ul>
</template>
<script>
export default {
  computed: {
    isMap() {
      return true;
    }
  }
};
</script>
