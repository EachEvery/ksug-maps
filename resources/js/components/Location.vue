<template>
  <div
    v-click-outside="goBack"
    :class="containerClass"
    class="fixed inset-0 md:right-0 md:left-auto bg-white transition pt-8 md:pt-0 md:w-84 xl:w-5/12 md:overflow-auto"
    style="max-width: 45rem"
  >
    <div
      v-if="location.photo !== null"
      class="h-64 absolute md:relative inset-x-0 w-full bottom-full md:bottom-auto overflow-hidden md:pt-0 pt-10"
    >
      <img
        :src="location.photo"
        @click="handleImageClick"
        style="cursor: zoom-in"
        @load="handleImageLoad"
        :class="{'translate-y-full': !isPreview, 'md:translate-y-0': !isPreview}"
        class="h-full object-cover w-full transition bg-black"
        :style="{transform: state === 'loaded' ? 'scale(1.05)' : 'none', 'object-position': location.photo_position}"
      />

      <span
        class="absolute font-mono text-2xs font-bold right-0 left-0 bottom-0 text-center text-white font-light px-4 pb-2 pt-5 px-5 xl:px-24"
        style="background: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0);"
      >{{location.photo_caption}}</span>
    </div>

    <div class="px-5 xl:px-8 md:mt-5">
      <h4 class="font-mono text-md font-bold uppercase mb-3">{{storyCount}}</h4>

      <div class="mb-12">
        <div class="flex">
          <h1
            class="font-display font-black lg:text-5xl xl:text-8xl uppercase tracking-loose leading-none flex-grow pr-10 h-24 md:h-auto"
            :class="{'text-2xl': location.name.length > 21, 'text-4xl': location.name.length < 21}"
          >{{location.name}}</h1>

          <router-link :to="chevronLink" class="md:hidden">
            <chevron-up-icon class="w-8 h-8 mt-1 text-black transition" :style="chevronStyle" />
          </router-link>
        </div>
        <a
          v-if="isAdmin"
          :href="location.admin_url"
          target="_blank"
          class="underline inline-block mt-3"
        >Edit Place</a>
      </div>
    </div>

    <div
      class="flex overflow-auto md:overflow-hidden md:pb-48 flex-no-wrap md:flex-wrap hide-scrollbars xl:px-8 xl:grid md:px-5 grid-columns-2 grid-gap grid-gap-4"
    >
      <div class="w-5 md:hidden" style="flex: 0 0 auto;"></div>

      <story-card
        :story="story"
        v-for="(story, i) in location.stories"
        :key="story.id"
        class="mr-4 md:mr-0 w-72 md:w-full h-48vh md:mb-5 xl:mb-2 flex-retain"
        :class="{'xl:translate-y-5': isEven(i)}"
        :style="{color: story.color}"
        style="max-height: 25rem"
      />

      <div class="w-1 md:hidden" style="flex: 0 0 auto;"></div>
    </div>

    <portal to="end-of-document">
      <a :href="location.photo" title ref="lightbox">
        <img :src="location.photo" alt title />
      </a>
    </portal>
  </div>
</template>

<script>
import chevronUpIcon from "./ChevronUpIcon";
import storyCard from "./StoryCard";

import { mapState, mapGetters } from "vuex";

export default {
  metaInfo() {
    return {
      title: this.location.name,
      titleTemplate: "%s | Mapping May 4"
    };
  },

  components: {
    chevronUpIcon,
    storyCard
  },

  data() {
    return {
      state: "default"
    };
  },
  methods: {
    handleImageClick() {
      this.$refs.lightbox.click();
    },
    isEven(index) {
      return index % 2 > 0;
    },
    goBack(e) {
      if (
        $(e.target).hasClass("fluidbox--initialized") ||
        $(e.target).hasClass("fluidbox__overlay")
      ) {
        return;
      }

      this.$router.push("/");
    },
    handleImageLoad() {
      this.state = "loaded";
    }
  },
  mounted() {
    this.$nextTick(() => {
      $(this.$refs.lightbox).fluidbox();
    });
  },
  beforeDestroy() {
    $(this.$refs.lightbox).off();
  },
  computed: {
    ...mapGetters(["locations", "isAdmin"]),
    ...mapState(["stories"]),
    chevronLink({ isPreview }) {
      return isPreview
        ? `/places/${this.location.slug}`
        : `/places/${this.location.slug}/preview`;
    },
    chevronStyle({ isPreview }) {
      return {
        transform: isPreview ? "none" : "rotate(-180deg)"
      };
    },
    location({ locations }) {
      return locations.find(item => item.slug === this.$route.params.location);
    },

    isPreview() {
      return this.$route.name === "preview";
    },
    containerClass({ isPreview }) {
      return {
        "md:translate-y-0": isPreview,
        "translate-location-preview": isPreview
      };
    },
    storyCount({ location }) {
      if (location.stories.length > 1) {
        return `${location.stories.length} stories`;
      }

      return `1 story`;
    },
    locationHtml({ location }) {
      let words = location.name.split(" ");

      if (words.length > 1) {
        return `${words.shift()}<br />${words.join(" ")}`;
      }

      return location.name;
    }
  }
};
</script>

