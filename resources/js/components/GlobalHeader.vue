<template>
  <div class="fixed pin-t w-full transition" :style="containerStyle" :class="containerClass">
    <div class="h-screen w-full absolute transition pt-24 bg-black" :class="innerClass">
      <transition
        enter-class="opacity-0"
        enter-active-class="transition"
        leave-active-class="opacity-0 transition"
      >
        <main-menu v-if="menuOpen" />
      </transition>
    </div>
    <div class="bg-black text-white px-5 z-50 top-0 justify-between flex w-full h-16 relative">
      <h1 class="uppercase font-display text-2xl leading-none self-center select-none">Kent in 1970</h1>

      <div class="flex text-white self-center">
        <!-- <clickable
          class="mr-5 w-6 h-6 self-center transition"
          @click="() => toggleState('search')"
          :class="{'opacity-25': searchOpen}"
        >
          <search-icon class="w-full h-full" />
        </clickable>-->

        <clickable
          class="w-8 h-8 transition"
          @click="() => toggleState('menu')"
          :class="{'opacity-25': menuOpen}"
        >
          <menu-icon class="w-full h-full" />
        </clickable>
      </div>
    </div>
  </div>
</template>

<script>
import clickable from "./Clickable";
import searchIcon from "./SearchIcon";
import menuIcon from "./MenuIcon";
import mainMenu from "./MainMenu";

export default {
  components: {
    clickable,
    searchIcon,
    menuIcon,
    mainMenu
  },
  data() {
    return {
      state: "default",
      lastState: ""
    };
  },
  methods: {
    setState(state) {
      this.lastState = this.state;
      this.state = state;
    },
    toggleState(state) {
      this.setState(this.state !== state ? state : "default");
    }
  },
  mounted() {
    console.log("is location");
  },
  computed: {
    isLocation() {
      return this.$route.name === "location";
    },
    isMap() {
      return true;
    },
    menuOpen({ state }) {
      return state === "menu";
    },
    searchOpen({ state }) {
      return state === "search";
    },
    isOpen({ menuOpen, searchOpen, isLocation }) {
      return (menuOpen || searchOpen) && !isLocation;
    },
    containerClass({ isOpen }) {
      return {
        "md:max-w-xs": !isOpen,
        "md:max-w-lg": isOpen
      };
    },
    innerClass({ isOpen }) {
      return {
        "opacity-0": !isOpen,
        invisible: !isOpen
      };
    },
    containerStyle({ isOpen, isLocation }) {
      return {
        transform: isOpen ? "" : "scale(.94) translateY(.6rem)",
        ...(isLocation ? { transform: "translateY(-5rem)" } : {})
      };
    }
  }
};
</script>
