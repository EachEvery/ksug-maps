<template>
  <div class="fixed inset-0 lg:left-auto lg:w-full lg:max-w-sm lg:mr-12">
    <tour-scroll-container
      ref="scrollContainer"
      @scroll-element-changed="handleScrollElementChanged"
    >
      <vertical-scroll-container class="mr-5 lg:h-auto lg:pb-0 lg:items-stretch lg:mr-0 lg:mt-12">
        <tour-start-card :tour="tour" />
      </vertical-scroll-container>

      <template v-for="(leg, legIndex) in legs">
        <vertical-scroll-container
          v-for="(step, legStepIndex) in getSteps(leg)"
          :key="getKey(legIndex, legStepIndex)"
          class="lg:h-auto lg:pb-0 lg:w-full lg:mr-0"
        >
          <place-tour-card
            v-if="isFirstStep(legStepIndex)"
            :place="includedPlaces[legIndex]"
            :step="leg.steps[0]"
            :stories="getStories(includedPlaces[legIndex])"
            :order="legIndex + 1"
          />

          <step-card :step="step" v-if="isNotLastOrFirstStep(legStepIndex)" />
        </vertical-scroll-container>
      </template>

      <!-- Last Step -->

      <vertical-scroll-container class="lg:h-auto lg:pb-0 lg:w-full mr-5 lg:mb-10 lg:mr-0">
        <place-tour-card
          :place="lastPlace"
          :step="undefined"
          :stories="getStories(lastPlace)"
          :order="includedPlaces.length"
        >
          <p class="mb-8 mt-4 block">
            You've reached the end of the
            <em>{{tour.name}}</em> tour. Thanks for joining us!
          </p>

          <clickable
            class="h-12 flex items-center justify-center bg-black my-5 w-full"
            @click="handleEndClick"
          >
            <span class="text-white font-bold font-mono uppercase">Back to Start</span>
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
import { getMapboxToken } from "../functions/helpers";
import { mapState } from "vuex";
import { getCenter, computeDestinationPoint } from "geolib";
import { mapStories } from "../functions/ksug";
import windowDimensions from "../mixins/windowDimensions";

import placeTourCard from "./PlaceTourCard";
import tourStartCard from "./TourStartCard";
import stepCard from "./StepCard";
import _ from "lodash";
import $ from "jquery";

export default {
  mixins: [routeHelpers, windowDimensions],

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
    handleScrollElementChanged(el) {
      let latLong = $(el).data("stepGeo");

      if (latLong && this.tourActive) {
        this.$store.commit("setMapCenter", [+latLong[0], +latLong[1], 18]);
      }
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
      return leg.steps.filter(s => s.maneuver.type !== "arrive");
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
      return [
        ...new Set(
          mapStories(this.tour.stories.filter(s => +s.place_id === +place.id))
        )
      ];
    },
    async loadDirections() {
      let client = mapboxClient({ accessToken: getMapboxToken() });

      this.optimizationClient = mapboxOptimizations(client);
      this.directionsClient = mapboxDirections(client);

      let req = this.directionsClient.getDirections({
        profile: "walking",
        waypoints: this.waypoints,
        steps: true,
        geometries: "geojson",
        bannerInstructions: true
      });

      let { body: directions } = await req.send();

      this.$store.commit("setDirections", directions);
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
    setCenter() {
      let points = this.includedPlaces.map(p => ({
        latitude: +p.lat,
        longitude: +p.long
      }));

      let center = getCenter(points);

      /**
       * Add half a mile to the center so its above our controls
       */
      center = computeDestinationPoint(
        center,
        this.lg ? 400 : 1600,
        this.lg ? 90 : 180
      );

      this.$store.commit("setMapCenter", [
        center.latitude,
        center.longitude,
        15
      ]);
    }
  },

  computed: {
    ...mapState(["directions", "tourActive", "places"]),

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

    includedPlaces({ tour, places }) {
      let includedPlacesIds = tour.stories.map(s => +s.place.id);

      return places.filter(p => includedPlacesIds.includes(+p.id));
    },

    waypoints({ includedPlaces }) {
      return includedPlaces.map(p => ({
        coordinates: [+p.long, +p.lat]
      }));
    }
  },
  mounted() {
    this.loadDirections();

    this.setCenter();
  }
};
</script>
