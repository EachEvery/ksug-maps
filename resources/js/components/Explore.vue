<template>
    <div
        class="fixed inset-x-0 md:inset-x-auto md:max-w-40rem md:right-0 top-0 bg-white pt-4 pb-48 md:pt-0 transition md:min-w-md overflow-y-scroll md:overflow-y-visible overflow-x-hidden md:overflow-x-visible"
        style="min-height: 100vh;  height: 100vh;"
        v-click-outside="handleClickOutside"
        :style="containerStyle"
    >
        <!-- Mobile Explore Heading -->
        <mobile-explore-toggle @click="$emit('toggle')">
            <h3 class="text-base uppercase font-light font-mono">
                Explore Tours &amp; Stories
            </h3>
            <up-arrow class="w-6 w-6 text-black" :style="arrowStyle" />
        </mobile-explore-toggle>

        <!-- Desktop Explore Heading -->
        <desktop-explore-toggle
            @click="$emit('toggle')"
        ></desktop-explore-toggle>

        <div class="px-8 md:overflow-y-scroll md:h-full">
            <h1
                class="text-5xl md:text-8xl font-display uppercase cursor-pointer"
                @click="$emit('toggle')"
            >
                Explore
            </h1>

            <div class="mt-12">
                <explore-heading portal-name="featured-tours-heading"
                    >Featured Tours</explore-heading
                >

                <scroll-container
                    class="-mx-10 md:-mx-8 px-5 md:px-8"
                    buttons-portal="featured-tours-heading"
                >
                    <tour-card
                        v-for="tour in tours"
                        :key="tour.id"
                        :tour="tour"
                        style="height: 24rem"
                        class="mr-4 w-64 flex-retain"
                    />
                </scroll-container>

                <explore-heading
                    portal-name="featured-stories-heading"
                    class="mt-12"
                    >Featured Stories</explore-heading
                >

                <scroll-container
                    class="-mx-10 md:-mx-8 px-5 md:px-8"
                    buttons-portal="featured-stories-heading"
                >
                    <story-card
                        v-for="story in randomFeaturedStories"
                        :key="story.id"
                        :story="story"
                        class="mr-4 w-72 h-48vh flex-retain"
                        :style="{ color: story.color }"
                        style="max-height: 25rem"
                    />
                </scroll-container>

                <div v-if="userLocation">
                    <explore-heading
                        portal-name="nearest-places-heading"
                        class="mt-12"
                        >Nearest Places</explore-heading
                    >

                    <scroll-container
                        class="-mx-10 md:-mx-8 px-5 md:px-8"
                        buttons-portal="nearest-places-heading"
                    >
                        <location-card
                            v-for="place in [...closestPlacesFirst].slice(0, 4)"
                            :key="place.id"
                            :place="place"
                            class="mr-4 w-72 h-48vh flex-retain"
                            style="max-height: 25rem"
                        />
                    </scroll-container>
                </div>

                <div v-if="comments.length">
                    <explore-heading
                        portal-name="recent-comments-heading"
                        class="mt-12"
                        >Recent Comments</explore-heading
                    >

                    <scroll-container
                        class="-mx-10 md:-mx-8 px-5 md:px-8"
                        buttons-portal="recent-comments-heading"
                    >
                        <comment-card
                            v-for="comment in [...comments].slice(0, 9)"
                            :key="comment.id"
                            :comment="comment"
                            class="mr-4 w-72 h-48vh flex-retain"
                            style="max-height: 25rem"
                        />
                    </scroll-container>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import upArrow from "./UpArrow";
import desktopExploreToggle from "./DesktopExploreToggle";
import mobileExploreToggle from "./MobileExploreToggle";
import windowDimensions from "../mixins/windowDimensions";
import scrollContainer from "./ScrollContainer";
import draggable from "vuedraggable";
import tourCard from "./TourCard";
import storyCard from "./StoryCard";
import commentCard from "./CommentCard";
import exploreHeading from "./ExploreSubheading";
import locationCard from "./LocationCard";

import _ from "lodash";
import { mapState, mapGetters } from "vuex";
import distance from "../mixins/distance";

export default {
    props: {
        open: Boolean,
        hide: Boolean
    },

    components: {
        locationCard,
        commentCard,
        upArrow,
        desktopExploreToggle,
        mobileExploreToggle,
        scrollContainer,
        draggable,
        tourCard,
        exploreHeading,
        storyCard
    },

    mixins: [windowDimensions, distance],

    watch: {
        open() {
            this.setCanClickOutside();
        }
    },
    data() {
        return {
            canClickOutside: false,
            randomFeaturedStories: _.shuffle(this.featuredStories)
        };
    },

    mounted() {
        this.setCanClickOutside();
    },
    methods: {
        shuffle: _.shuffle,

        handleClickOutside() {
            if (this.canClickOutside) {
                this.$emit("toggle");
            }
        },

        setCanClickOutside() {
            setTimeout(() => {
                this.canClickOutside = this.open;
            }, 300);
        }
    },

    computed: {
        ...mapState(["tours", "stories", "places"]),

        ...mapGetters(["featuredStories", "comments", "userLocation"]),

        closestPlacesFirst({ places }) {
            let withDistance = places.map(p => {
                return {
                    ...p,
                    distance: this.getLocationDistance(+p.lat, +p.long)
                };
            });

            return withDistance.sort((a, b) => a.distance - b.distance);
        },

        containerStyle({ open, hide, md }) {
            if (hide) {
                return {
                    transform: md ? `translateX(120%)` : `translateY(100%)`
                };
            }

            return {
                transform: md
                    ? `translateX(${open ? "0" : `100%`})`
                    : `translateY(${open ? "0" : "75vh"})`
            };
        },
        arrowStyle({ open }) {
            return {
                transform: `rotate(${open ? "180deg" : 0})`
            };
        }
    }
};
</script>
