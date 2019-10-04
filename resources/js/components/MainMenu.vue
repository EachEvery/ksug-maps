<template>
  <ul class="flex flex-col text-center font-display text-5xl max-w-xs mx-auto leading-tight mt-24">
    <li class="mb-10 shrink-when-active" :class="{'opacity-25': isMap}">
      <router-link to="/">MAP</router-link>
    </li>
    <li class="mb-10 shrink-when-active">
      <router-link to="/about">ABOUT THE PROJECT</router-link>
    </li>
    <li class="mb-10 shrink-when-active">
      <a href="https://mailchi.mp/531c4843f76c/mappingmay4" target="_blank">SIGN UP FOR UPDATES</a>
    </li>
  </ul>
</template>
<script>
export default {
  props: {
    isLocation: Boolean
  },
  computed: {
    isMap({ isLocation, $route }) {
      return isLocation || $route.path === "/";
    }
  }
};
</script>
