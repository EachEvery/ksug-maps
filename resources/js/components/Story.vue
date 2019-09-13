<template>
  <div
    v-click-outside="handleClickOutside"
    class="fixed inset-0 md:right-0 md:left-auto bg-white transition pt-8 md:pt-0 md:w-84 xl:w-5/12 overflow-auto shadow-lg flex-grow-0 story"
    style="max-width: 45rem"
    :style="{'background-color': story.color}"
  >
    <div class="xl:px-24 px-8 pb-24">
      <div class="pt-5 mb-12 md:mb-24 lg:pt-12">
        <h3
          class="font-mono text-base tracking-tight mb-4 font-bold uppercase md:text-2xs"
        >{{story.role}} &middot; {{story.day}}</h3>
        <h1
          class="font-display font-black text-5xl lg:text-5xl uppercase tracking-loose leading-none flex-grow pr-24 mb-5 md:mb-3"
        >{{story.place.name}}</h1>
        <h2 class="font-sans text-black text-lg md:text-md">{{story.subject}}</h2>
      </div>

      <quote-icon class="h-6 w-6 mb-6 text-black" />

      <p v-html="story.content" class="leading-loose"></p>

      <a href="#" class="font-bold w-full h-16 flex justify-center bg-white mt-16" target="_blank">
        <span class="self-center text-sm xl:text-base">VISIT FULL ORAL HISTORY →</span>
      </a>

      <clickable @click="closeStory" class="fixed top-0 right-0 rounded-full mr-5 mt-5 z-10">
        <close-icon class="w-8 h-8 lg:w-5 lg:h-5 text-black" />
      </clickable>
    </div>

    <div class="xl:px-24 px-8 py-24 relative bg-tan-100" v-if="story.approved_comments.length > 0">
      <h3 class="font-display uppercase text-2xl mb-12">Stories &amp; Comments</h3>

      <div v-for="comment in story.approved_comments" :key="comment.id" class="my-10">
        <h4 class="text-base font-medium mb-3">{{comment.author}}</h4>
        <span class="whitespace-pre-line leading-normal" v-html="comment.text"></span>
        <span class="block mt-4 opacity-75 font-mono text-xs">{{comment.frontend_date}}</span>
      </div>
    </div>
    <div class="xl:px-24 px-8 pt-24 pb-48 relative bg-white">
      <h3 class="font-display uppercase text-2xl mb-8" style="font-weight: 500;">Share Your Story</h3>

      <comment-form @comment-created="handleCommentCreated" />

      <div
        class="absolute inset-0 bg-white transition xl:px-24 pt-24 px-8 flex flex-col"
        :style="confirmationStyle"
      >
        <h3 class="font-display uppercase text-2xl mb-8">Thanks for Your Story</h3>

        <p
          class="leading-normal"
        >Your submission is under review and will show up under the comments for this story once it is approved.</p>

        <clickable
          class="w-full mt-10 text-center py-3 px-2 border border-black uppercase"
          @click="addAnotherComment"
        >Add Another Comment</clickable>
      </div>
    </div>
    <audio-player
      ref="audioPlayer"
      class="fixed bottom-0 right-0 md:w-84 xl:w-5/12 transition"
      style="max-width: 45rem"
      :style="audioPlayerStyle"
      :src="story.audio"
      :label="story.subject"
    />
  </div>
</template>
<script>
import audioPlayer from "./AudioPlayer";
import closeIcon from "./CloseIcon";
import clickable from "./Clickable";
import quoteIcon from "./QuoteIcon";
import commentForm from "./CommentForm";

import { mapState, mapGetters } from "vuex";

import $ from "jquery";

export default {
  metaInfo() {
    return {
      title: this.story.subject,
      titleTemplate: "%s | Mapping May 4"
    };
  },

  data() {
    return {
      state: "default"
    };
  },

  components: {
    audioPlayer,
    closeIcon,
    clickable,
    quoteIcon,
    commentForm
  },

  methods: {
    addAnotherComment() {
      this.state = "default";
    },
    handleClickOutside(e) {
      console.log(e.target.tagName);
      if (e.target.tagName !== "svg" && e.target.tagName !== "path") {
        this.closeStory();
      }
    },
    closeStory() {
      this.state = "default";

      setTimeout(() => {
        this.$refs.audioPlayer.controlAudio("pause");
        this.$router.push(`/places/${this.location.slug}`);
      }, 310);
    },
    handleCommentCreated(comment) {
      this.state = "showCommentConfirmation";
    }
  },

  computed: {
    ...mapState(["stories"]),
    ...mapGetters(["locations"]),
    confirmationStyle({ state, story }) {
      let showIt = state === "showCommentConfirmation";

      return {
        visibility: showIt ? "visible" : "hidden",
        opacity: showIt ? 1 : 0
      };
    },
    audioPlayerStyle({ story, state }) {
      let showPlayer = state === "showPlayer";

      return {
        transform: `translateY(${showPlayer ? "0" : "100%"})`,
        opacity: showPlayer ? "1" : "0"
      };
    },
    location({ story }) {
      return story.place;
    },
    story({ stories }) {
      return stories.find(s => +s.id === +this.$route.params.story);
    },
    locationHtml({ story }) {
      let words = story.place.name.split(" ");

      if (words.length === 2) {
        return `${words[0]}<br />${words[1]}`;
      }

      return story.place.name;
    }
  },
  mounted() {
    setTimeout(() => {
      this.state = "showPlayer";
    }, 500);
  }
};
</script>
