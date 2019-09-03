<template>
  <div class="fixed top-0 w-full transition" :style="containerStyle" :class="containerClass">
    <div
      class="h-screen w-full absolute transition pt-24 bg-white flex flex-col justify-between pb-10"
      :class="innerClass"
    >
      <transition
        mode="out-in"
        enter-class="opacity-0"
        enter-active-class="transition"
        leave-active-class="opacity-0 transition"
      >
        <main-menu v-if="menuOpen" class="flex-grow overflow-hidden" />
        <search v-if="searchOpen" class="flex-grow overflow-hidden" />
      </transition>

      <p
        class="font-mono self-center text-center text-sm opacity-50 leading-normal flex-shrink-0"
        style="width: 21rem"
      >All content used with permission of Special Collections at Kent State University.</p>
    </div>
    <div
      class="bg-white text-black px-5 z-50 top-0 justify-between flex w-full h-16 relative transition"
      :class="{'shadow-lg': isClosed}"
    >
      <h1 class="uppercase font-display text-2xl leading-none self-center select-none">Mapping May 4</h1>

      <div class="flex text-black self-center">
        <clickable
          class="mr-5 w-6 h-6 self-center transition"
          @click="() => toggleState('search')"
          :class="{'opacity-25': searchOpen}"
        >
          <search-icon class="w-full h-full" />
        </clickable>

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
import search from "./Search";

export default {
  components: {
    clickable,
    searchIcon,
    menuIcon,
    mainMenu,
    search
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
    isClosed({ isOpen }) {
      return !isOpen;
    },
    containerClass({ isOpen, isLocation }) {
      return {
        "md:max-w-xs": !isOpen,
        "md:max-w-lg": isOpen,
        "md:-translate-y-5": isLocation
      };
    },
    innerClass({ isOpen }) {
      return {
        "opacity-0": !isOpen,
        invisible: !isOpen,
        "shadow-lg": isOpen
      };
    },
    containerStyle({ isOpen, isLocation }) {
      return {
        transform: isOpen ? "" : "scale(.94) translateY(.6rem)"
      };
    }
  }
};
</script>
