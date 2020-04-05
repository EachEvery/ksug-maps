<template>
  <dropdown align-content="left">
    <clickable
      slot="trigger"
      class="font-mono border-2 border-black text-black flex justify-between p-4 items-center font-bold w-full"
    >
      <span v-if="!value">
        <slot />
      </span>
      <span v-else>{{ selectedLabel }}</span>
      <span class="inline-block ml-4">▼</span>
    </clickable>

    <div slot="content" class="flex flex-col border-2 border-t-0 border-black">
      <clickable
        class="p-4 font-mono text-left font-bold"
        @click="$emit('input', undefined)"
        :class="getButtonClass(undefined)"
      >{{ emptyLabel }}</clickable>
      <clickable
        class="p-4 font-mono text-left font-bold"
        v-for="(option, i) in options"
        :key="i"
        @click="handleClick(option)"
        :class="getButtonClass(option)"
      >{{ getOptionLabel(option) }}</clickable>
    </div>
  </dropdown>
</template>
<script>
import dropdown from "./Dropdown";
import clickable from "./Clickable";
export default {
  components: {
    dropdown,
    clickable
  },
  props: {
    value: String,
    emptyLabel: {
      default: "All"
    },
    options: {
      required: true
    }
  },
  computed: {
    selectedLabel({ value: selectedOption }) {
      return selectedOption;
    }
  },
  watch: {
    value(val) {}
  },
  methods: {
    handleClick(option) {
      this.$emit("input", this.getOptionValue(option));
    },
    getButtonClass(option) {
      let active = option && this.optionIsSelected(option);

      return {
        "bg-black": active,
        "text-white": active,
        "text-black": !active
      };
    },
    optionIsSelected(option) {
      //TODO: support key/value pairs
      return option === this.value;
    },
    getOptionValue(option) {
      //TODO: support key/value pairs
      return option;
    },
    getOptionLabel(option) {
      //TODO: support key/value pairs
      return option;
    }
  }
};
</script>
