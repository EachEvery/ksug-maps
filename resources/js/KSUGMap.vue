<template>
  <div v-if="ready">
    <img
      src="https://nhmisc.s3.us-east-1.amazonaws.com/ksug/map-small.png"
      :class="mapImageClass"
      @load="handleImageLoad"
      class="transition fixed inset-0 h-screen object-cover select-none"
    />

    <global-header />

    <router-view :locations="locations" />
  </div>
</template>

<script>
import globalHeader from "./components/GlobalHeader";
import getSlug from "slugify";
import Axios from "axios";

export default {
  components: {
    globalHeader
  },
  data() {
    return {
      state: "default",
      stories: []
    };
  },
  mounted() {
    this.ensureStories();
  },
  methods: {
    handleImageLoad() {
      this.state = "imageLoaded";
    },
    async ensureStories() {
      let { data } = await Axios.get("/stories");

      this.stories = data;

      console.log(this.locations, "locations");
    }
  },
  computed: {
    locations({ stories }) {
      return [...new Set(stories.map(story => story.location))].map(
        location => {
          let locationStories = stories.filter(
            item => item.location === location
          );
          return {
            name: location,
            stories: locationStories,
            lat: stories[0].lat,
            long: stories[0].long,
            slug: getSlug(location, { remove: /['()]/g }).toLowerCase()
          };
        }
      );
    },
    ready({ stories }) {
      return stories.length > 0;
    },
    imageLoaded({ state }) {
      return state === "imageLoaded";
    },
    mapImageClass({ imageLoaded }) {
      return {
        invisible: !imageLoaded,
        "opacity-0": !imageLoaded
      };
    }
  }
};
</script>

