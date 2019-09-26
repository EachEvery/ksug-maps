<template>
  <div class="leading-tight text-left px-5 h-full flex flex-col relative">
    <h1 class="font-display text-lg font-black mb-5">FILTER STORIES</h1>

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
        <clickable
          @click="() => toggleFilter({key: activeFilter, value: item})"
          class="text-left flex"
          style="line-height: 1.4"
        >
          <div
            class="w-5 h-5 rounded border transition mr-2 flex-shrink-0 flex justify-center"
            :class="{'bg-blue': itemSelected(item), 'border-blue border-b-black ': itemSelected(item), 'border-black': !itemSelected(item)}"
          >
            <span
              class="transition self-center"
              :class="{'opacity-0': !itemSelected(item)}"
              :style="{'transform': `translateY(${!itemSelected(item) ? '.05rem' : 0})`}"
            >&check;</span>
          </div>
          {{item}}
        </clickable>
      </li>
    </ul>
  </div>
</template>
<script>
import clickable from "./Clickable";
import filterButton from "./FilterButton";

import { getStoryColor } from "../functions/ksug";
import { mapGetters, mapMutations, mapState } from "vuex";

export default {
  components: {
    clickable,
    filterButton
  },
  data() {
    return {
      activeFilter: "day",
      activeCollection: "days",
      state: "default"
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

  methods: {
    ...mapMutations(["toggleFilter", "clearFilters"]),

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
