<template>
  <div :class="containerClass" :style="containerStyle" class="fixed inset-0 p-5">
    <h4
      class="text-shadow-darker font-display font-black text-lg uppercase tracking-wide"
    >{{storyCount}}</h4>
    <h1
      class="text-shadow font-display font-black text-5xl uppercase tracking-loose leading-none"
      v-html="locationHtml"
    >{{location.name}}</h1>
  </div>
</template>
<script>
export default {
  props: {
    locations: Array
  },
  data() {
    return {
      state: "default"
    };
  },
  methods: {
    open() {
      this.state = "open";
    }
  },
  computed: {
    location({ locations }) {
      return locations.find(item => item.slug === this.$route.params.location);
    },
    isPreview() {
      return this.$route.name === "preview";
    },
    containerClass({ isOpen }) {},
    containerStyle({ isPreview }) {
      return {
        transform: "translateY(75%)"
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

      if (words.length === 2) {
        return `${words[0]}<br />${words[1]}`;
      }

      return location.name;
    }
  },
  mounted() {}
};
</script>

