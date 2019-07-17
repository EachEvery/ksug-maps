<template>
  <button @click="emitClickEvent" class="outline-none focus:shadow-outline shrink-when-active">
    <slot />
  </button>
</template>
<script>
export default {
  methods: {
    emitClickEvent(e) {
      this.$emit("click", e);
    }
  }
};
</script>
