<template>
  <router-link
    class="bg-current p-5 relative overflow-hidden shrink-when-active"
    :to="`/stories/${story.id}`"
    :style="{'--transparentColor': transparentColor, '--currentColor': story.color}"
  >
    <div class="text-black">
      <quote-icon class="text-black w-5 h-5" />

      <p class="leading-loose mt-5 text-sm xl:text-base xl:leading-normal">{{story.content}}</p>

      <div
        class="inset-x-0 bottom-0 w-full h-64 absolute gradient flex justify-between font-mono text-black px-5 pb-5 uppercase"
      >
        <h3 class="self-end text-xs xl:text-2xs font-bold">{{story.role}}</h3>
        <h3 class="self-end text-xs xl:text-2xs font-bold">{{story.day}}</h3>
      </div>
    </div>
  </router-link>
</template>

<style scoped lang="scss">
.gradient {
  background: linear-gradient(
    to bottom,
    var(--transparentColor),
    var(--currentColor) 75%
  );
}
</style>
<script>
import quoteIcon from "./QuoteIcon";

export default {
  components: {
    quoteIcon
  },
  props: {
    story: Object
  },
  computed: {
    transparentColor({ story }) {
      let hex = story.color;
      let c = hex.substring(1).split("");

      if (c.length == 3) {
        c = [c[0], c[0], c[1], c[1], c[2], c[2]];
      }

      c = "0x" + c.join("");

      return (
        "rgba(" + [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(",") + ",0)"
      );
    }
  }
};
</script>

