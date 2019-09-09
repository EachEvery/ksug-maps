<template>
  <div v-if="ready" class="relative">
    <div class="w-full h-full relative overflow-hidden bg-black">
      <map-component
        :locations="locations"
        :show-overlay-button="!isLocation"
        :is-location="isLocation"
        @location-clicked="handleLocationClicked"
        :class="{'-translate-y-35vh': isLocation, 'md:-translate-x-10': isLocation}"
        class="transition"
      />
    </div>

    <global-header :is-location="isLocation" />

    <transition
      enter-class="opacity-0 translate-y-1 md:translate-x-2"
      leave-to-class="opacity-0 translate-y-1 md:translate-x-2"
      enter-active-class="transition"
      leave-active-class="transition"
    >
      <router-view />
    </transition>
  </div>
</template>

<script>
import globalHeader from "./components/GlobalHeader";
import mapComponent from "./components/Map";

import { mapActions, mapState, mapGetters } from "vuex";

export default {
  metaInfo() {
    return {
      title: "Home",
      titleTemplate: "%s | Mapping May 4"
    };
  },
  components: {
    globalHeader,
    mapComponent
  },
  data() {
    return {
      state: "default",
      pullMapLeft: false
    };
  },
  async mounted() {
    await this.ensureStories();
    this.preloadImages();
  },
  methods: {
    ...mapActions(["ensureStories"]),
    preloadImages() {
      [...this.locations].forEach(item => {
        if (item.photo !== null) {
          let image = new Image();

          image.src = item.photo;
        }
      });
    },
    handleImageLoad() {
      this.state = "imageLoaded";
    },
    handleLocationClicked(location) {
      this.$router.push(`/places/${location.slug}/preview`);
    }
  },
  computed: {
    ...mapState(["stories"]),
    ...mapGetters(["locations"]),
    isLocation({ $route }) {
      return ["location", "preview", "story"].includes($route.name);
    },

    ready({ stories }) {
      return stories.length > 0;
    },
    imageLoaded({ state }) {
      return state === "imageLoaded";
    },

    mapClass({ imageLoaded, isLocation }) {
      return {
        invisible: !imageLoaded,
        "opacity-0": !imageLoaded,
        "zoom-map": isLocation
      };
    }
  }
};
</script>

