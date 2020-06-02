<template>
    <div
        class="fixed inset-0 lg:left-auto lg:w-full lg:max-w-sm lg:mr-12"
        v-esc="handleClose"
    >
        <portal to="end-of-document">
            <clickable
                class="fixed top-0 inset-x-0 py-2  p-4 w-full text-black font-bold font-mono text-center w-full  lg:left-auto lg:right-0 lg:max-w-sm lg:mr-12"
                style="background: #F0A38C"
                @click="handleEndClick"
                :style="{
                    transform: `translateY(${tourActive ? '0' : '-100%'})`
                }"
            >
                &times; END TOUR
            </clickable>
        </portal>
        <tour-scroll-container
            ref="scrollContainer"
            @scroll-element-changed="handleScrollElementChanged"
        >
            <vertical-scroll-container
                class="mr-5 lg:h-auto lg:pb-0 lg:items-stretch lg:mr-0 lg:mt-12"
            >
                <tour-start-card :tour="tour" @close="handleClose" />
            </vertical-scroll-container>

            <template v-for="(leg, legIndex) in legs">
                <vertical-scroll-container
                    v-for="(step, legStepIndex) in getSteps(leg)"
                    :key="getKey(legIndex, legStepIndex)"
                    class="mr-5 lg:h-auto lg:pb-0 lg:w-full lg:mr-0"
                >
                    <place-tour-card
                        v-if="isFirstStep(legStepIndex)"
                        :place="placesInOrder[legIndex]"
                        :step="leg.steps[0]"
                        :stories="getStories(placesInOrder[legIndex])"
                        :order="legIndex + 1"
                    />

                    <step-card
                        :step="step"
                        v-if="isNotLastOrFirstStep(legStepIndex)"
                    />
                </vertical-scroll-container>
            </template>

            <!-- Last Step -->

            <vertical-scroll-container
                class="lg:h-auto lg:pb-0 lg:w-full mr-24 lg:mb-10 lg:mr-0"
            >
                <place-tour-card
                    :place="lastPlace"
                    :step="undefined"
                    :stories="getStories(lastPlace)"
                    :order="placesInOrder.length"
                >
                    <p class="mb-8 mt-4 block">
                        You've reached the end of the
                        <em>{{ tour.name }}</em> tour. Thanks for joining us!
                    </p>

                    <clickable
                        class="h-12 flex items-center justify-center bg-black my-5 w-full"
                        @click="handleEndClick"
                    >
                        <span class="text-white font-bold font-mono uppercase"
                            >Back to Start</span
                        >
                    </clickable>
                </place-tour-card>
            </vertical-scroll-container>
        </tour-scroll-container>
    </div>
</template>
<script>
import mapboxClient from "@mapbox/mapbox-sdk";

import mapboxOptimizations from "@mapbox/mapbox-sdk/services/optimization";
import mapboxDirections from "@mapbox/mapbox-sdk/services/directions";
import tourScrollContainer from "./TourScrollContainer";
import verticalScrollContainer from "./VerticalScrollContainer";
import clickable from "./Clickable";
import upArrow from "./UpArrow";
import routeHelpers from "../mixins/routeHelpers";
import tourStoryCard from "./TourStoryCard";
import { getMapboxToken, orderPlaces } from "../functions/helpers";
import { mapState } from "vuex";
import { getCenter, computeDestinationPoint } from "geolib";
import { mapStories } from "../functions/ksug";
import windowDimensions from "../mixins/windowDimensions";

import placeTourCard from "./PlaceTourCard";
import tourStartCard from "./TourStartCard";
import stepCard from "./StepCard";
import _ from "lodash";
import $ from "jquery";
import handleBack from "../mixins/handleBack";
import Axios from "axios";

export default {
    mixins: [routeHelpers, windowDimensions, handleBack],

    components: {
        stepCard,
        upArrow,
        verticalScrollContainer,
        clickable,
        tourStoryCard,
        placeTourCard,
        tourStartCard,
        tourScrollContainer
    },

    watch: {
        tourActive(val) {
            if (val) {
                this.$refs.scrollContainer.scrollToFirstStep();
            }
        }
    },
    methods: {
        handleClose() {
            this.handleEndClick();

            this.back();
        },
        handleScrollElementChanged(el) {
            if (!this.lg) {
                $(".vertical-scroll-container").animate({
                    scrollTop: 0
                });
            }

            let latLong = $(el).data("stepGeo");

            let placeId = $(el).data("placeId");

            $(".tour-stop").removeClass("active");

            if (!this.tourActive || !latLong) {
                return;
            }

            if (placeId) {
                $(`#place-marker-${placeId}`).addClass("active");
            }

            clearTimeout(this.switchTimeout);

            this.switchTimeout = setTimeout(() => {
                if (latLong && this.tourActive) {
                    if (this.lg) {
                        this.$store.commit("setMapCenter", [
                            +latLong[0],
                            +latLong[1],
                            17.5
                        ]);
                    } else {
                        let centerForScreen = computeDestinationPoint(
                            {
                                latitude: +latLong[0],
                                longitude: +latLong[1]
                            },
                            30,
                            -180
                        );

                        this.$store.commit("setMapCenter", [
                            +centerForScreen.latitude,
                            +centerForScreen.longitude,
                            20
                        ]);
                    }
                }
            }, 100);
        },

        getKey(legIndex, legStepIndex) {
            `${legIndex}-steps-${legStepIndex}`;
        },

        getSteps(leg) {
            /**
             * We filter out the arrive step for each leg
             * since we put that info on the start card
             * of each leg.
             */
            let steps = leg.steps.filter(s => s.maneuver.type !== "arrive");

            return this.tourActive ? steps : [steps[0]];
        },

        isNotLastOrFirstStep(stepIndex) {
            return stepIndex !== 0;
        },

        handleEndClick() {
            this.$store.commit("setTourIsActive", false);
            this.$refs.scrollContainer.scrollToStart();

            this.setCenter();
        },

        getStories(place) {
            let storyIds = this.tour.stories
                .filter(s => +s.place_id === +place.id)
                .map(s => s.id);

            storyIds = [...new Set(storyIds)];

            return storyIds.map(id => this.stories.find(s => s.id === id));
        },

        async loadDirections() {
            let client = mapboxClient({ accessToken: getMapboxToken() });

            this.directionsClient = mapboxDirections(client);

            let waypoints = await this.getWaypoints();

            let req = this.directionsClient.getDirections({
                profile: "walking",
                waypoints: waypoints,
                steps: true,
                geometries: "geojson",
                bannerInstructions: true
            });

            let { body: directions } = await req.send();

            this.$store.commit("setDirections", directions);
        },

        async getWaypoints() {
            return orderPlaces(this.includedPlaces, this.tour).map(p => ({
                coordinates: [+p.long, +p.lat]
            }));
        },

        getMiles(meters) {
            return (meters & 0.000621371).toFixed(2);
        },
        getFeet(meters) {
            return 3.28084 & meters;
        },
        getRotation(str) {
            if (str === "right") {
                return "rotate(90deg)";
            }
            if (str === "left") {
                return "rotate(-90deg)";
            }
        },

        isFirstStep(stepIndex) {
            return stepIndex === 0;
        },

        getCenterForScreen(center) {
            return computeDestinationPoint(
                center,
                this.lg ? 0 : 100,
                this.lg ? 90 : -180
            );
        },

        setCenter() {
            let points = this.includedPlaces.map(p => ({
                latitude: +p.lat,
                longitude: +p.long
            }));

            let center = getCenter(points);

            /**
             * Add half a mile to the center so its above our controls
             */
            center = this.getCenterForScreen(center);

            if (!this.lg) {
                center = computeDestinationPoint(points[0], 50, -180);
            }
            this.$store.commit("setMapCenter", [
                center.latitude,
                center.longitude,
                17
            ]);
        },

        async getTourStories() {
            let { data: stories } = await Axios.get(
                `/tour/${this.tour.id}/stories`
            );

            this.$store.commit("setTourStories", stories);

            return stories;
        }
    },

    computed: {
        ...mapState([
            "directions",
            "tourActive",
            "places",
            "stories",
            "tourStories"
        ]),

        placesInOrder({ includedPlaces, tour }) {
            return orderPlaces(includedPlaces, tour);
        },

        defaultBackRoute() {
            return "/explore";
        },

        startButtonText({ tourActive }) {
            return tourActive ? "Stop Tour" : "Start Tour";
        },

        firstPlace({ includedPlaces }) {
            return includedPlaces[0];
        },

        lastPlace({ includedPlaces }) {
            return _.last(includedPlaces);
        },

        legs({ directions }) {
            if (!directions) return undefined;

            return directions.routes[0].legs;
        },

        loading({ directions }) {
            return directions === undefined;
        },

        includedPlaces({ directions, places, tour }) {
            let includedPlacesIds = tour.stories.map(s => +s.place.id);

            return places.filter(p => includedPlacesIds.includes(+p.id));
        },

        waypoints({ includedPlaces }) {}
    },
    mounted() {
        this.loadDirections();

        if (!this.tourActive) {
            this.setCenter();
        }
    }
};
</script>
