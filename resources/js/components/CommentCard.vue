<template>
  <router-link
    class="bg-tan-100 relative shrink-when-active mb-8 flex flex-col overflow-hidden"
    :to="`/places/${place.slug}#comment-${comment.id}`"
    :style="{'--transparentColor': transparentColor }"
  >
    <div
      class="text-black overflow-hidden relative p-5 flex-1 flex-shrink-0"
      :class="{'h-full': !comment.media_is_image, 'h-48': comment.media_is_image}"
    >
      <div class="flex justify-between font-mono font-bold">
        <h3 class="self-end text-xs xl:text-2xs">{{ truncate(place.name, {length: 20}) }}</h3>
        <h3 class="self-end text-xs xl:text-2xs">{{comment.frontend_date}}</h3>
      </div>

      <quote-icon class="text-black w-6 pb-1 h-6 mt-6" />

      <p class="leading-loose mt-5 text-sm xl:text-base xl:leading-normal">{{ comment.text }}</p>

      <div class="inset-x-0 bottom-0 w-full h-12 absolute gradient font-mono"></div>
    </div>

    <img
      :src="comment.media_url"
      style="max-height: 10rem;"
      class="object-cover flex-grow flex-1"
      v-if="comment.media_is_image"
    />
  </router-link>
</template>

<style scoped lang="scss">
.gradient {
  background: linear-gradient(to bottom, var(--transparentColor), #f0ebe6 75%);
}
</style>
<script>
import _ from "lodash";
import quoteIcon from "./QuoteIcon";

import { mapGetters, mapState } from "vuex";

export default {
  components: {
    quoteIcon
  },

  props: {
    comment: Object
  },
  methods: {
    truncate: _.truncate
  },
  computed: {
    ...mapState(["places"]),
    place({ comment, places }) {
      return places.find(p => +p.id === +comment.place_id);
    },

    lat({ place }) {
      return +place.lat;
    },

    lng({ place }) {
      return +place.long;
    },

    transparentColor() {
      let hex = "#F0EBE6";
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
