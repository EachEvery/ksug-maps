<template>
  <div v-if="ready" class="relative">
    <div class="w-full h-full relative overflow-hidden bg-black">
      <map-component
        :filters="filters"
        :places="places"
        :show-overlay-button="!isLocation && !isAbout && !menuOpen"
        :is-location="isLocation"
        @location-clicked="handleLocationClicked"
        :class="{
                    '-translate-y-35vh': isLocation,
                    'md:-translate-x-10': isLocation
                }"
        class="transition"
        ref="mapComponent"
      />
    </div>

    <transition
      enter-class="opacity-0 translate-y-1 md:translate-x-2"
      leave-to-class="opacity-0 translate-y-1 md:translate-x-2"
      enter-active-class="transition"
      leave-active-class="transition"
    >
      <router-view />
    </transition>

    <global-header
      :is-location="isLocation"
      @state-changed="menuIsOpen => (menuOpen = menuIsOpen)"
    />

    <explore
      :open="exploreOpen"
      @toggle="exploreOpen = !exploreOpen"
      :hide="isLocation || isAbout"
    />

    <a
      href="/admin"
      v-if="isAdmin"
      style="color: rgba(255,255,255,.8);"
      class="fixed top-0 right-0 bg-black text-sm py-2 px-4 rounded-full m-2 shadow-lg"
    >Admin Mode</a>

    <portal-target name="end-of-document" multiple class="fixed"></portal-target>
  </div>
</template>

<script>
import globalHeader from "./components/GlobalHeader";
import mapComponent from "./components/Map";
import explore from "./components/Explore";
import routeHelpers from "./mixins/routeHelpers";

import { mapActions, mapState, mapGetters } from "vuex";

export default {
  mixins: [routeHelpers],

  metaInfo() {
    return {
      title: "Home",
      titleTemplate: "%s | Mapping May 4"
    };
  },

  components: {
    globalHeader,
    mapComponent,
    explore
  },

  data() {
    return {
      state: "default",
      pullMapLeft: false,
      menuOpen: false,
      exploreOpen: false
    };
  },

  async mounted() {
    await this.ensureData();
    this.preloadImages();

    if (this.$route.path === "/") {
      this.$router.push("/about");
    }
  },
  methods: {
    ...mapActions(["ensureData"]),
    preloadImages() {
      [...this.places].forEach(item => {
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
    ...mapState(["stories", "filters", "places", "tours"]),

    isAdmin() {
      return window.isAdmin;
    },
    ready({ stories, places, tours }) {
      return stories.length > 0 && places.length > 0 && tours.length > 0;
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
