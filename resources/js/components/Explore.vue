<template>
  <div
    class="fixed inset-x-0 md:inset-x-auto md:right-0 top-0 bg-white pt-4 transition md:min-w-md overflow-y-scroll overflow-x-hidden"
    style="min-height: 100vh; max-width: 40rem; height: 100vh;"
    v-click-outside="handleClickOutside"
    :style="containerStyle"
  >
    <!-- Mobile Explore Heading -->
    <mobile-explore-toggle @click="$emit('toggle')">
      <h3 class="text-lg uppercase font-light font-mono px-8">Explore Tours &amp; Stories</h3>
      <up-arrow class="w-8 w-8 text-black" :style="arrowStyle" />
    </mobile-explore-toggle>

    <!-- Desktop Explore Heading -->
    <desktop-explore-toggle @click="$emit('toggle')"></desktop-explore-toggle>

    <div class="px-8">
      <h1 class="text-8xl font-display uppercase cursor-pointer" @click="$emit('toggle')">Explore</h1>

      <div class="mt-12">
        <explore-heading portal-name="featured-tours-heading">Featured Tours</explore-heading>

        <scroll-container
          class="-mx-10 md:-mx-8 px-5 md:px-8"
          buttons-portal="featured-tours-heading"
        >
          <draggable v-for="tour in tours" :key="tour.id" class="flex">
            <tour-card :tour="tour" style="height: 24rem" class="mr-4 w-64 flex-retain" />
          </draggable>
        </scroll-container>

        <explore-heading portal-name="featured-stories-heading" class="mt-12">Featured Stories</explore-heading>

        <scroll-container
          class="-mx-10 md:-mx-8 px-5 md:px-8"
          buttons-portal="featured-stories-heading"
        >
          <draggable v-for="story in featuredStories" :key="story.id" class="flex">
            <story-card
              :story="story"
              class="mr-4 w-72 h-48vh flex-retain"
              :style="{ color: story.color }"
              style="max-height: 25rem"
            />
          </draggable>
        </scroll-container>
      </div>
    </div>
  </div>
</template>

<script>
import upArrow from "./UpArrow";
import desktopExploreToggle from "./DesktopExploreToggle";
import mobileExploreToggle from "./MobileExploreToggle";
import windowDimensions from "../mixins/windowDimensions";
import scrollContainer from "./ScrollContainer";
import draggable from "vuedraggable";
import tourCard from "./TourCard";
import storyCard from "./StoryCard";

import exploreHeading from "./ExploreSubheading";

import { mapState, mapGetters } from "vuex";

export default {
  props: {
    open: Boolean,
    hide: Boolean
  },

  components: {
    upArrow,
    desktopExploreToggle,
    mobileExploreToggle,
    scrollContainer,
    draggable,
    tourCard,
    exploreHeading,
    storyCard
  },

  mixins: [windowDimensions],

  watch: {
    open() {
      this.setCanClickOutside();
    }
  },
  data() {
    return {
      canClickOutside: false
    };
  },
  mounted() {
    this.setCanClickOutside();
  },
  methods: {
    handleClickOutside() {
      if (this.canClickOutside) {
        this.$emit("toggle");
      }
    },
    setCanClickOutside() {
      setTimeout(() => {
        this.canClickOutside = this.open;
      }, 300);
    }
  },

  computed: {
    ...mapState(["tours", "stories"]),
    ...mapGetters(["featuredStories"]),

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
