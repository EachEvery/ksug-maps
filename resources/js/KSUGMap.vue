<template>
    <div v-if="ready" class="relative bg-black">
        <div
            class="w-full h-full relative overflow-hidden bg-black transition"
            :style="{ transform: exploreOpen ? 'scale(1.2)' : 'none' }"
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
                    'md:-translate-x-10': isLocation,
                }"
                class="transition"
                ref="mapComponent"
            />
        </div>

        <a
            href="/admin"
            v-if="isAdmin"
            style="
                border-bottom-left-radius: 5px;
                color: rgba(0, 0, 0, 0.8);
                right: 3.2rem;
            "
            class="fixed top-0 right-0 bg-orange text-black font-mono text-sm py-2 px-4 rounded-b-left shadow-lg hidden md:inline-block"
            >Admin Mode</a
        >

        <explore
            :open="exploreOpen"
            @toggle="handleExploreToggle"
            :hide="isLocation || isAbout"
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
            :should-hide-menu="isLocation || exploreOpen"
            @state-changed="(menuIsOpen) => (menuOpen = menuIsOpen)"
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

import { mapActions, mapState, mapGetters } from "vuex";

export default {
    mixins: [routeHelpers],

    metaInfo() {
        return {
            title: "Home",
            titleTemplate: "%s | Mapping May 4",
        };
    },

    components: {
        globalHeader,
        mapComponent,
        explore,
    },

    data() {
        return {
            state: "default",
            pullMapLeft: false,
            menuOpen: false,
        };
    },

    async mounted() {
        await this.ensureData();
        this.preloadImages();

        if (this.$route.path === "/") {
            // this.$router.push("/about");
        }
    },

    methods: {
        ...mapActions(["ensureData"]),

        handleExploreToggle() {
            console.log("wow", this.exploreOpen);

            let newRoute = this.exploreOpen ? "/" : "/explore";

            this.$router.push(newRoute);
        },

        focusMap() {
            this.menuOpen = false;
        },

        preloadImages() {
            [...this.places].forEach((item) => {
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
        },
    },
    computed: {
        ...mapState(["stories", "filters", "places", "tours"]),

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
                "zoom-map": isLocation,
            };
        },
    },
};
</script>
