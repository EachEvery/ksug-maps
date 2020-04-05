<template>
  <router-link
    class="bg-current p-5 relative shrink-when-active mb-8"
    :to="`/stories/${story.id}`"
    :style="{
            '--transparentColor': transparentColor,
            '--currentColor': story.color
        }"
  >
    <div class="text-black h-full overflow-hidden">
      <quote-icon class="text-black w-5 h-5" />

      <p class="leading-loose mt-5 text-sm xl:text-base xl:leading-normal">{{ story.content }}</p>

      <div
        class="inset-x-0 bottom-0 w-full h-64 absolute gradient flex justify-between font-mono text-black px-5 pb-5 uppercase"
      >
        <h3 class="self-end text-xs xl:text-2xs font-bold">{{ story.role }}</h3>

        <h3 class="self-end text-xs xl:text-2xs font-bold">{{ story.day }}</h3>
      </div>
    </div>

    <div
      class="absolute font-mono text-black opacity-50 left-0 text-xs mt-2 tracking-wide"
      style="top: 100%;"
      v-if="distance"
    >{{ distance }}</div>
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
import distance from "../mixins/distance";
import { mapGetters, mapState } from "vuex";

export default {
  mixins: [distance],

  components: {
    quoteIcon
  },

  props: {
    story: Object
  },

  computed: {
    ...mapState(["places"]),
    place({ story, places }) {
      return places.find(p => +p.id === +story.place_id);
    },

    lat({ place }) {
      return +place.lat;
    },

    lng({ place }) {
      return +place.long;
    },

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
