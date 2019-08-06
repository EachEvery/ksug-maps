<template>
  <clickable class="mr-5 pr-5 relative text-left" @click="emit" :class="{'opacity-25': !isActive}">
    <span>
      <slot />
    </span>
    <span
      class="absolute top-0 right-0 text-xs mt-1 -ml-1"
      v-if="filters.length > 0"
      style="font-size: .7rem"
    >({{count}})</span>
  </clickable>
</template>
<script>
import clickable from "./Clickable";

export default {
  components: {
    clickable
  },
  props: {
    activeFilter: String,
    filter: String,
    collectionName: String,
    filters: Array
  },
  methods: {
    emit(e) {
      this.$emit("click", e, this.filter, this.collectionName);
    }
  },
  computed: {
    isActive({ activeFilter, filter }) {
      return activeFilter === filter;
    },
    selectedFilters({ filters, filter }) {
      return filters.filter(f => f.key === filter);
    },
    count({ selectedFilters }) {
      return selectedFilters.length;
    }
  }
};
</script>

