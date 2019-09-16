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
      <search-icon class="w-5 h-5 self-end mb-2" />
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

    <h1 class="font-display text-lg font-black mb-5 mt-16">FILTER STORIES BY</h1>

    <div class="flex border-b-2 border-black pb-2 justify-between flex-shrink-0">
      <div class="flex">
        <filter-button
          collection-name="days"
          filter="day"
          :filters="filters"
          :active-filter="activeFilter"
          @click="setActiveFilter"
        >Date</filter-button>

        <filter-button
          collection-name="names"
          filter="subject"
          :filters="filters"
          :active-filter="activeFilter"
          @click="setActiveFilter"
        >Name</filter-button>

        <filter-button
          collection-name="roles"
          filter="role"
          :filters="filters"
          :active-filter="activeFilter"
          @click="setActiveFilter"
        >Role</filter-button>
      </div>

      <clickable
        class="text-xs opacity-25"
        v-if="filters.length > 0"
        @click="clearFilters"
      >&times; Clear All Filters</clickable>
    </div>

    <ul class="flex-grow overflow-auto mb-5 pt-5">
      <li v-for="(item, i) in filterItems" :key="i" class="flex mb-4">
        <div
          class="w-5 h-5 rounded border transition mr-2 flex-shrink-0"
          :class="{'bg-orange': itemSelected(item), 'border-orange border-b-black ': itemSelected(item), 'border-black': !itemSelected(item)}"
        ></div>
        <clickable
          @click="() => toggleFilter({key: activeFilter, value: item})"
          class="text-left"
          style="line-height: 1.4"
        >{{item}}</clickable>
      </li>
    </ul>
  </div>
</template>
<script>
import clickable from "./Clickable";
import filterButton from "./FilterButton";
import searchIcon from "./SearchIcon";
import spinner from "./Spinner";
import axios from "axios";

import { getStoryColor } from "../functions/ksug";

import { mapGetters, mapMutations, mapState } from "vuex";

export default {
  components: {
    clickable,
    filterButton,
    searchIcon,
    spinner
  },
  data() {
    return {
      activeFilter: "day",
      activeCollection: "days",
      results: [],
      q: ""
    };
  },
  computed: {
    ...mapState(["filters"]),
    ...mapGetters(["roles", "names", "days"]),
    filterItems({ activeCollection }) {
      let items = this[activeCollection].map(item => {
        return item.trim();
      });

      return [...new Set(items)].sort();
    }
  },
  watch: {
    q: function() {
      clearTimeout(this.searchTimeout);

      this.searchTimeout = setTimeout(this.updateResults, 300);
    }
  },
  methods: {
    ...mapMutations(["toggleFilter", "clearFilters"]),
    getStoryColor,
    async updateResults() {
      let { data } = await axios.get("/search?q=" + this.q);

      this.results = data;
    },
    itemSelected(item) {
      return (
        this.filters.find(
          f => f.key === this.activeFilter && f.value === item
        ) !== undefined
      );
    },
    setActiveFilter(e, filter, collection) {
      this.activeFilter = filter;
      this.activeCollection = collection;
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