<template>
  <div class="fixed inset-0">
    <scroll-container>
      <vertical-scroll-container class="mr-5">
        <div class="p-6 bg-black text-white w-60vw mt-55vh min-h-20rem">
          <h1 class="font-display text-5xl font-bold uppercase leading-none">{{tour.name}}</h1>
          <h3 class="font-display text-xl font-bold uppercase">{{tour.duration}}</h3>

          <clickable
            class="h-12 flex items-center justify-center bg-white my-5 w-full"
            @click="handleStartButtonClick"
          >
            <span class="text-black font-bold font-mono uppercase">{{startButtonText}}</span>
          </clickable>

          <div class="flex my-6">
            <img src="/images/map.png" alt="A map folded at its creases" class="w-5 h-5 mr-4" />

            <p class="font-mono uppercase">
              Tour Beings at
              <br />
              <a href="#" class="underline">{{firstPlace.name}}</a>
            </p>
          </div>

          <div class="leading-normal" v-html="tour.description"></div>

          <img :src="tour.photos[0].url" class="w-full h-48 object-cover mt-8" />
        </div>
      </vertical-scroll-container>

      <template v-for="(leg, i) in legs">
        <vertical-scroll-container
          v-for="(step, j) in leg.steps"
          :key="`${i}-steps-${j}`"
          class="mr-5"
        >
          <div class="bg-white max-w-sm p-4 w-60vw mt-55vh min-h-20rem" v-if="j === 0">
            <div
              class="flex items-center h-8 w-8 justify-center font-mono rounded-full bg-black text-white"
            >
              <span class="text-md">{{i + 1}}</span>
            </div>

            <h1 class="font-display text-3xl uppercase font-bold">{{places[i].name}}</h1>

            <p class="mb-8">Depart from {{places[i].name}} and {{leg.steps[0].maneuver.instruction}}</p>

            <img
              :src="places[i].photos[0].url"
              class="h-32 w-full object-cover"
              v-if="places[i].photos.length"
            />

            <tour-story-card
              class="mt-3"
              v-for="story in getStories(places[i])"
              :story="story"
              :key="`${i}-steps-${j}-story-${story.id}`"
              :style="{ color: story.color }"
            />
          </div>

          <div class="bg-black text-white p-4 w-60vw mt-55vh min-h-20rem" v-if="j !== 0">
            <img src="/images/upArrow.png" class="w-8 h-8" />
            <h3
              class="font-display uppercase text-3xl"
            >{{step.maneuver.type}} {{step.maneuver.modifier}}</h3>
            <p>
              {{step.maneuver.instruction}}.
              <span
                v-if="step.maneuver.type === 'turn'"
              >Continue for {{getFeet(step.distance)}} feet.</span>
            </p>
          </div>
        </vertical-scroll-container>

        <!-- steps go here -->
      </template>
    </scroll-container>
  </div>
</template>
<script>
import mapboxClient from "@mapbox/mapbox-sdk";
import mapboxDirections from "@mapbox/mapbox-sdk/services/directions";
import scrollContainer from "./ScrollContainer";
import verticalScrollContainer from "./VerticalScrollContainer";
import clickable from "./Clickable";
import upArrow from "./UpArrow";
import routeHelpers from "../mixins/routeHelpers";
import tourStoryCard from "./TourStoryCard";
import { getMapboxToken } from "../functions/helpers";
import { mapState } from "vuex";
import { getCenter, computeDestinationPoint } from "geolib";
import { mapStories } from "../functions/ksug";

export default {
  mixins: [routeHelpers],

  components: {
    scrollContainer,
    upArrow,
    verticalScrollContainer,
    clickable,
    tourStoryCard
  },

  methods: {
    handleStartButtonClick() {
      this.$store.commit("setTourIsActive", this.tourActive ? false : true);
    },

    getStories(place) {
      return [
        ...new Set(
          mapStories(this.tour.stories.filter(s => +s.place_id === +place.id))
        )
      ];
    },
    async loadDirections() {
      this.directionsClient = mapboxDirections(
        mapboxClient({ accessToken: getMapboxToken() })
      );

      let req = this.directionsClient.getDirections({
        profile: "walking",
        waypoints: this.waypoints,
        steps: true,
        geometries: "geojson"
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
    setCenter() {
      let points = this.places.map(p => ({
        latitude: +p.lat,
        longitude: +p.long
      }));

      let center = getCenter(points);

      /**
       * Add half a mile to the center so its above our controls
       */
      center = computeDestinationPoint(center, 1600, 180);

      this.$store.commit("setMapCenter", [
        center.latitude,
        center.longitude,
        14
      ]);
    }
  },

  computed: {
    ...mapState(["directions", "tourActive"]),

    startButtonText({ tourActive }) {
      return tourActive ? "Stop Tour" : "Start Tour";
    },
    firstPlace({ tour }) {
      return tour.stories[0].place;
    },
    legs({ directions }) {
      if (!directions) return undefined;

      return directions.routes[0].legs;
    },
    loading({ directions }) {
      return directions === undefined;
    },

    places({ tour }) {
      return [...new Set(tour.stories.map(s => s.place))];
    },

    waypoints({ places }) {
      return places.map(p => ({
        coordinates: [+p.long, +p.lat]
      }));
    }
  },
  mounted() {
    this.loadDirections();

    this.setCenter();

    console.log(this.places);
  }
};
</script>
