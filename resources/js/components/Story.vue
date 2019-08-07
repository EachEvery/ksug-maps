<template>
  <div :style="{'background-color': story.color}"></div>
</template>
<script>
import { mapState } from "vuex";
export default {
  computed: {
    ...mapState(["stories"]),
    story({ stories }) {
      return stories.find(s => +s.id === +this.$route.params.story);
    }
  }
};
</script>
