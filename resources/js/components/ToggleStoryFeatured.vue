<template>
  <clickable @click="toggle" class="underline" :class="{'oapcity-25': state === 'loading'}">{{text}}</clickable>
</template>
<script>
import clickable from "./Clickable";
import axios from "axios";

import _ from "lodash";

export default {
  components: {
    clickable
  },

  props: {
    initStory: Object
  },

  data() {
    return {
      isFeatured: this.initStory.featured,
      state: "default"
    };
  },
  computed: {
    text({ isFeatured }) {
      return isFeatured ? "Remove from Explore Panel" : "Add to Explore Panel";
    }
  },
  watch: {
    isFeatured() {
      clearTimeout(this.updateTimeout);

      this.updateTimeout = setTimeout(() => {
        axios.put(`/stories/${this.initStory.id}`, {
          story: {
            featured: this.isFeatured ? 1 : 0
          }
        });
      }, 100);
    }
  },
  methods: {
    async toggle() {
      this.isFeatured = !this.isFeatured;
    }
  }
};
</script>