<template>
  <div class="leading-tight text-left px-5 h-full flex flex-col relative">
    <h1 class="font-display text-lg font-black mb-3">SEARCH STORIES</h1>
    <div class="flex border-b-2 border-black">
      <input
        type="text"
        placeholder="Search by Name, Location, Role"
        v-model="q"
        class="flex-grow py-2 focus:outline-none"
      />
      <spinner class="w-5 h-5 self-end mb-2" v-if="state === 'loading'" />
      <search-icon class="w-5 h-5 self-end mb-2" v-else />
    </div>

    <div
      class="inset-x-0 bg-white overflow-auto absolute z-50 border-b border-g px-5"
      style="top: 5rem; bottom: 1rem"
      v-if="results.length > 0"
    >
      <router-link
        :to="result.path"
        v-for="(result, i) in results"
        :key="i"
        class="px-8 py-4 hover:bg-grey-100 block border-white"
        :class="{'border-t-2' : i > 0}"
        :style="{'background': getStoryColor(result)}"
      >
        <h1 class="text-grey-800 font-bold mb-2">{{result.title}}</h1>
        <p class="font-light leading-tight tracking-tight opacity-75" v-html="result.subtitle"></p>
      </router-link>
    </div>
  </div>
</template>
<script>
import clickable from "./Clickable";
import searchIcon from "./SearchIcon";
import spinner from "./Spinner";
import axios from "axios";

import { getStoryColor } from "../functions/ksug";
import { mapGetters, mapMutations, mapState } from "vuex";

export default {
  components: {
    clickable,
    searchIcon,
    spinner
  },
  data() {
    return {
      results: [],
      state: "default",
      q: ""
    };
  },

  watch: {
    q: function() {
      this.state = "loading";

      clearTimeout(this.searchTimeout);

      this.searchTimeout = setTimeout(this.updateResults, 300);
    }
  },
  methods: {
    getStoryColor,
    async updateResults() {
      let { data } = await axios.get("/search?q=" + this.q);

      this.results = data;
      this.state = "default";
    }
  }
};
</script>

<style>
input::-webkit-input-placeholder {
  color: #000;
}
input:-moz-placeholder {
  color: #000;
}
input::-moz-placeholder {
  color: #000;
}
input:-ms-input-placeholder {
  color: #000;
}
</style>