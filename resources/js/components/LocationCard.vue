<template>
  <router-link
    class="bg-tan-100 relative shrink-when-active mb-8 flex flex-col"
    :to="`/places/${place.slug}`"
  >
    <div class="p-4" style="height: 45%">
      <h3 class="uppercase font-display text-xl font-normal leading-none">{{ place.name }}</h3>

      <span class="font-mono mt-2 inline-block uppercase">{{ storyCount }}</span>
    </div>

    <img class="flex-grow object-cover" :src="place.photos[0].url" v-if="place.photos.length" />
    <div class="flex-grow" v-else></div>

    <div
      class="absolute font-mono text-black opacity-50 left-0 text-xs mt-2 tracking-wide"
      style="top: 100%;"
      v-if="distance"
    >{{ distance }}</div>
  </router-link>
</template>
<script>
import distance from "../mixins/distance";
import { mapGetters, mapState } from "vuex";

export default {
  mixins: [distance],

  props: {
    place: Object
  },

  computed: {
    location({ place }) {
      return place;
    },

    lat({ place }) {
      return +place.lat;
    },

    lng({ place }) {
      return +place.long;
    },

    storyCount({ location }) {
      if (location.stories.length > 1) {
        return `${location.stories.length} stories`;
      }

      return `1 story`;
    }
  }
};
</script>
