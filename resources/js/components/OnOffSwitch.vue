<template>
  <clickable
    class="transition transition border border-black rounded-full w-16 p-1 flex items-center"
    :class="{'bg-black': on}"
    @click="handleClick"
  >
    <div
      class="transition w-6 h-6 rounded-full"
      :style="{background: on ? '#fff' : '#000', transform: on ? 'translateX(1.9rem)' : 'none'}"
    ></div>
  </clickable>
</template>

<script>
import clickable from "./Clickable";
export default {
  components: {
    clickable
  },
  props: {
    value: Boolean
  },
  methods: {
    handleClick() {
      this.$emit("input", !this.value);
    }
  },
  computed: {
    on({ value }) {
      return value;
    }
  }
};
</script>