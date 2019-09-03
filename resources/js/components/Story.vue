<template>
  <div
    v-click-outside="closeStory"
    :style="{'background-color': story.color}"
    class="fixed inset-0 md:right-0 md:left-auto bg-white transition pt-8 md:pt-0 md:w-84 xl:w-5/12 overflow-auto px-8 shadow-lg flex-grow-0"
    style="max-width: 45rem"
  >
    <div class="pt-5 mb-12 md:mb-24 lg:pt-12">
      <h3
        class="font-mono text-base tracking-tight mb-4 font-bold uppercase md:text-2xs"
      >{{story.role}} &middot; {{story.day}}</h3>
      <h1
        class="font-display font-black text-5xl lg:text-5xl uppercase tracking-loose leading-none flex-grow pr-24 mb-5 md:mb-3"
      >{{story.location}}</h1>
      <h2 class="font-sans text-black text-lg md:text-md">{{story.subject}}</h2>
    </div>

    <quote-icon class="h-6 w-6 mb-6 text-black" />

    <p v-html="story.content" class="leading-loose"></p>

    <a href="#" class="font-bold w-full h-16 flex justify-center bg-white mt-16" target="_blank">
      <span class="self-center text-base">VISIT FULL ORAL HISTORY →</span>
    </a>

    <clickable @click="closeStory" class="fixed top-0 right-0 shadow-lg rounded-full mr-5 mt-5">
      <close-icon class="w-8 h-8 lg:w-5 lg:h-5 text-white" />
    </clickable>

    <audio-player />
  </div>
</template>
<script>
import audioPlayer from "./AudioPlayer";
import closeIcon from "./CloseIcon";
import clickable from "./Clickable";
import quoteIcon from "./QuoteIcon";

import { mapState, mapGetters } from "vuex";

export default {
  metaInfo() {
    return {
      title: this.story.subject,
      titleTemplate: "%s | Mapping May 4"
    };
  },

  components: {
    audioPlayer,
    closeIcon,
    clickable,
    quoteIcon
  },
  methods: {
    closeStory() {
      this.$router.push(`/places/${this.location.slug}`);
    }
  },
  computed: {
    ...mapState(["stories"]),
    ...mapGetters(["locations"]),
    location({ locations, story }) {
      return locations.find(loc => loc.name === story.location);
    },
    story({ stories }) {
      return stories.find(s => +s.id === +this.$route.params.story);
    },
    locationHtml({ story }) {
      let words = story.location.split(" ");

      if (words.length === 2) {
        return `${words[0]}<br />${words[1]}`;
      }

      return location.name;
    }
  }
};
</script>
