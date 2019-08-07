<template>
  <div class="leading-tight text-left px-5 h-full flex flex-col">
    <h1 class="font-display text-lg font-black mb-5">BROWSE BY</h1>

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
import { mapGetters, mapMutations, mapState } from "vuex";

export default {
  components: {
    clickable,
    filterButton
  },
  data() {
    return {
      activeFilter: "day",
      activeCollection: "days"
    };
  },
  computed: {
    ...mapState(["filters"]),
    ...mapGetters(["roles", "names", "days"]),
    filterItems({ activeCollection }) {
      return this[activeCollection];
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

