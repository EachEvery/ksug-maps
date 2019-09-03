<template>
  <div v-if="ready" class="relative">
    <div class="w-full h-full relative overflow-hidden">
      <map-component />
      <div
        class="absolute inset-0 bg-black transition"
        :class="overlayClass"
        @click="handleOverlayClick"
      ></div>
    </div>

    <global-header />

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
  components: {
    globalHeader,
    mapComponent
  },
  data() {
    return {
      state: "default"
    };
  },
  mounted() {
    this.ensureStories();
  },
  methods: {
    ...mapActions(["ensureStories"]),
    handleImageLoad() {
      this.state = "imageLoaded";
    },
    handleOverlayClick() {
      this.$router.go(-1);
    }
  },
  computed: {
    ...mapState(["stories"]),
    ...mapGetters(["locations"]),
    shouldFadeMap({ $route }) {
      return ["location", "preview", "story"].includes($route.name);
    },
    overlayClass({ shouldFadeMap }) {
      return {
        "opacity-50": shouldFadeMap,
        "opacity-0": !shouldFadeMap,
        invisible: !shouldFadeMap
      };
    },
    ready({ stories }) {
      return stories.length > 0;
    },
    imageLoaded({ state }) {
      return state === "imageLoaded";
    },
    isLocation({ $route }) {
      return $route.name === "location";
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

