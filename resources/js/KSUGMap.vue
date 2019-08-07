<template>
  <div v-if="ready" class="relative">
    <div class="w-full h-full relative overflow-hidden">
      <img
        src="https://nhmisc.s3.us-east-1.amazonaws.com/ksug/map-small.png"
        :class="mapClass"
        @load="handleImageLoad"
        class="transition fixed inset-0 h-screen object-cover select-none"
      />

      <div class="absolute inset-0 bg-black opacity-50 transition" :class="overlayClass"></div>
    </div>

    <global-header />

    <router-view :locations="locations" />
  </div>
</template>

<script>
import globalHeader from "./components/GlobalHeader";
import { mapActions, mapState, mapGetters } from "vuex";

export default {
  components: {
    globalHeader
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
    }
  },
  computed: {
    ...mapState(["stories"]),
    ...mapGetters(["locations"]),
    shouldFadeMap({ $route }) {
      return $route.name === "location" || $route.name === "preview";
    },
    overlayClass({ shouldFadeMap }) {
      return {
        "opacity-50": shouldFadeMap,
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

