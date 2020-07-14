<template>
    <div
        class="fixed top-0 w-full transition"
        :style="containerStyle"
        :class="containerClass"
        v-click-outside="handleClickOutside"
    >
        <a
            href="/admin"
            v-if="isAdmin"
            class="absolute top-full bg-black text-white w-full py-1 text-2xs font-mono font-bold uppercase text-center block"
            >ADMIN MODE IS CURRENTLY ACTIVE</a
        >

        <div
            class="h-screen w-full absolute transition pt-16 md:pt-24 bg-white flex flex-col justify-between pb-10"
            :class="innerClass"
        >
            <transition
                mode="out-in"
                enter-class="opacity-0"
                enter-active-class="transition"
                leave-active-class="opacity-0 transition"
            >
                <main-menu
                    v-if="menuOpen"
                    class="flex-grow overflow-hidden"
                    @close="handleClickOutside"
                    :is-location="isLocation"
                />
                <search v-if="searchOpen" class="flex-grow overflow-hidden" />
                <filters v-if="filterOpen" class="flex-grow overflow-hidden" />
            </transition>

            <p
                class="font-mono self-center text-center text-2xs md:text-sm opacity-50 leading-normal flex-shrink-0"
                style="width: 21rem"
            >
                All content used with permission of Special Collections at Kent
                State University.
            </p>
        </div>
        <div
            class="bg-white text-black px-5 z-50 top-0 justify-between flex w-full h-16 relative transition"
            style="box-shadow: 10px -5px 8px rgba(0,0,0,.3)"
            :style="{
                'box-shadow': !isOpen
                    ? 'rgba(0, 0, 0, 0.3) 10px 4px 8px'
                    : 'none'
            }"
        >
            <h1
                class="uppercase font-display text-2xl leading-none self-center select-none cursor-pointer"
                @click="resetMap"
            >
                Mapping May 4
            </h1>

            <div class="flex text-black self-center">
                <clickable
                    class="mr-5 w-6 h-6 self-center transition"
                    @click="() => toggleState('search')"
                    :class="{ 'opacity-25': searchOpen }"
                >
                    <search-icon class="w-full h-full" />
                </clickable>

                <clickable
                    class="mr-5 w-6 h-6 self-center transition relative"
                    @click="() => toggleState('filter')"
                    :class="{ 'opacity-25': filterOpen }"
                >
                    <filter-icon class="w-full h-full transition" />

                    <div
                        class="w-4 h-4 flex justify-center items-center absolute left-full bottom-full text-black bg-orange rounded-full -mb-1 -ml-1"
                        v-if="validFilters.length > 0"
                    >
                        <span class="text-2xs">{{ validFilters.length }}</span>
                    </div>
                </clickable>

                <clickable
                    class="w-8 h-8 transition"
                    @click="() => toggleState('menu')"
                    :class="{ 'opacity-25': menuOpen }"
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
import filters from "./Filter";
import search from "./Search";
import filterIcon from "./FilterIcon";
import { mapState, mapGetters } from "vuex";
import routeHelpers from "../mixins/routeHelpers";
export default {
    mixins: [routeHelpers],
    props: {
        shouldHideMenu: Boolean
    },
    components: {
        filterIcon,
        clickable,
        searchIcon,
        menuIcon,
        mainMenu,
        search,
        filters
    },
    data() {
        return {
            state: "default",
            lastState: ""
        };
    },
    methods: {
        resetMap() {
            this.$store.commit("setMapCenter", [41.15002, -81.348852, 13]);
            this.$router.push("/about");
        },
        handleClickOutside() {
            if (window.dropdownOpen) return;

            this.setState("default");
        },
        setState(state) {
            this.lastState = this.state;
            this.state = state;
        },
        toggleState(state) {
            this.setState(this.state !== state ? state : "default");
        }
    },

    watch: {
        $route: function() {
            this.state = "default";
        },
        isOpen(val) {
            this.$emit("state-changed", val);
        }
    },

    computed: {
        ...mapState(["filters"]),
        ...mapGetters([["validFilters"]]),
        isMap() {
            return true;
        },
        isAdmin() {
            return window.isAdmin && !this.isEmbed;
        },
        menuOpen({ state }) {
            return state === "menu";
        },
        searchOpen({ state }) {
            return state === "search";
        },
        filterOpen({ state }) {
            return state === "filter";
        },
        isOpen({ menuOpen, searchOpen, isLocation, filterOpen }) {
            return (menuOpen || searchOpen || filterOpen) && !isLocation;
        },
        isClosed({ isOpen }) {
            return !isOpen;
        },
        containerClass({ isOpen, shouldHideMenu }) {
            return {
                "md:max-w-md": !isOpen,
                "md:max-w-lg": isOpen,
                "-translate-y-5": shouldHideMenu
            };
        },
        innerClass({ isOpen }) {
            return {
                "opacity-0": !isOpen,
                invisible: !isOpen,
                "shadow-lg": isOpen
            };
        },
        containerStyle({ isOpen, shouldHideMenu }) {
            if (shouldHideMenu) {
                return {};
            }

            return {
                transform: isOpen ? "" : "scale(.94) translateY(.6rem)"
            };
        }
    }
};
</script>
