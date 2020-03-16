<template>
  <div
    class="fixed inset-x-0 md:inset-x-auto md:right-0 top-0 bg-white px-8 pt-4 transition"
    style="min-height: 100vh;z-index: 1000"
    :style="containerStyle"
  >
    <!-- Mobile Explore Heading -->
    <mobile-explore-toggle @click="$emit('toggle')">
      <h3 class="text-lg uppercase font-light font-mono">Explore Tours &amp; Stories</h3>
      <up-arrow class="w-8 w-8 text-black" :style="arrowStyle" />
    </mobile-explore-toggle>

    <!-- Desktop Explore Heading -->
    <desktop-explore-toggle @click="$emit('toggle')">
      <h1 class="text-4xl uppercase font-display cursor-pointer">Explore</h1>
    </desktop-explore-toggle>

    <h1>Explore</h1>
  </div>
</template>

<script>
import upArrow from "./UpArrow";
import desktopExploreToggle from "./DesktopExploreToggle";
import mobileExploreToggle from "./MobileExploreToggle";

import windowDimensions from "../mixins/windowDimensions";

export default {
  props: {
    open: Boolean,
    hide: Boolean
  },

  components: {
    upArrow,
    desktopExploreToggle,
    mobileExploreToggle
  },

  mixins: [windowDimensions],

  computed: {
    containerStyle({ open, hide, md }) {
      if (hide) {
        return {
          transform: md ? `translateX(100%)` : `translateY(100%)`
        };
      }

      return {
        transform: md
          ? `translateX(${open ? "0" : `100%`})`
          : `translateY(${open ? "0" : "87%"})`
      };
    },
    arrowStyle({ open }) {
      return {
        transform: `rotate(${open ? "180deg" : 0})`
      };
    }
  }
};
</script>