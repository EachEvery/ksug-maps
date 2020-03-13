<template>
  <div>asdfasdf</div>
</template>
<script>
import mapboxClient from "@mapbox/mapbox-sdk";
import mapboxDirections from "@mapbox/mapbox-sdk/services/directions";

import routeHelpers from "../mixins/routeHelpers";

import { getMapboxToken } from "../functions/helpers";
import { mapState } from "vuex";

export default {
  mixins: [routeHelpers],

  methods: {
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
    }
  },

  computed: {
    ...mapState(["directions"]),

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
  }
};
</script>
