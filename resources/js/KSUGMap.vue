<template>
    <div
        class="bg-black h-screen w-full flex items-center text-white font-display justify-center"
        v-if="!ready"
    >
        <h1 class="text-5xl opacity-25 uppercase -mt-24 md:mt-0">
            Loading map...
        </h1>
    </div>
    <div v-else class="relative bg-black">
        <div
            class="w-full h-full relative overflow-hidden bg-black transition"
            :style="{ transform: exploreOpen ? 'scale(1.05)' : 'none' }"
        >
            <map-component
                :block-clicks="isLocation || exploreOpen"
                :filters="filters"
                :places="places"
                :show-overlay-button="
                    !isLocation && !isAbout && !menuOpen && !exploreOpen
                "
                :is-location="isLocation"
                @location-clicked="handleLocationClicked"
                :class="{
                    '-translate-y-5vh': isLocation,
                    'md:-translate-x-10': isLocation
                }"
                class="transition"
                ref="mapComponent"
            />
        </div>

        <explore
            :open="exploreOpen"
            @toggle="handleExploreToggle"
            :hide="isLocation || isAbout || isTour"
        />

        <transition
            enter-class="opacity-0 translate-y-1 md:translate-x-2"
            leave-to-class="opacity-0 translate-y-1 md:translate-x-2"
            enter-active-class="transition"
            leave-active-class="transition"
        >
            <router-view />
        </transition>

        <global-header
            :is-location="isLocation"
            :should-hide-menu="isLocation || exploreOpen || isTour"
            @state-changed="menuIsOpen => (menuOpen = menuIsOpen)"
        />

        <portal-target
            name="end-of-document"
            multiple
            class="fixed"
        ></portal-target>
    </div>
</template>

<script>
import globalHeader from "./components/GlobalHeader";
import mapComponent from "./components/Map";
import explore from "./components/Explore";
import routeHelpers from "./mixins/routeHelpers";
import spinner from "./components/Spinner";

import { mapActions, mapState, mapGetters, mapMutations } from "vuex";

export default {
    mixins: [routeHelpers],

    metaInfo() {
        return {
            title: "Home",
            titleTemplate: "%s | Mapping May 4"
        };
    },

    components: {
        globalHeader,
        mapComponent,
        explore,
        spinner
    },

    data() {
        return {
            state: "default",
            pullMapLeft: false,
            menuOpen: false
        };
    },

    watch: {
        $route(route) {
            this.addRouteToStack(route);
        }
    },

    async mounted() {
        await this.ensureData();
        this.preloadImages();

        if (this.$route.path === "/") {
            this.$router.push("/about");
        }
    },

    methods: {
        ...mapActions(["ensureData"]),
        ...mapMutations(["addRouteToStack"]),

        handleExploreToggle() {
            let newRoute = this.exploreOpen ? "/" : "/explore";

            this.$router.push(newRoute);
        },

        focusMap() {
            this.menuOpen = false;
        },

        preloadImages() {
            [...this.places].forEach(item => {
                if (item.photo !== null) {
                    let image = new Image();

                    image.src = item.photo;
                }
            });
        },

        handleImageLoad() {
            this.state = "imageLoaded";
        },

        handleLocationClicked(location) {
            this.$router.push(`/places/${location.slug}/preview`);
        }
    },
    computed: {
        ...mapState(["stories", "filters", "places", "tours"]),

        isTour({ $route }) {
            return $route.name === "tour";
        },

        exploreOpen({ $route }) {
            return $route.name === "explore";
        },

        isAdmin() {
            return window.isAdmin;
        },
        ready({ stories, places, tours }) {
            return (
                stories !== undefined &&
                places !== undefined &&
                tours !== undefined
            );
        },

        imageLoaded({ state }) {
            return state === "imageLoaded";
        },

        mapClass({ imageLoaded, isLocation }) {
            return {
                invisible: !imageLoaded,
                "opacity-0": !imageLoaded,
                "zoom-map": isLocation
            };
        }
    }
};
</script>
