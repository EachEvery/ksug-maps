<template>
  <div>
    <div class="relative w-full h-screen bg-gray-dark" id="map"></div>
  </div>
</template>

<script>
import mapbox from "mapbox-gl";
import jquery from "jquery";
import routeHelpers from "../mixins/routeHelpers";

export default {
  mixins: [routeHelpers],

  props: {
    places: Array
  },

  data() {
    return {
      mapboxMarkers: []
    };
  },

  computed: {
    markers({ places }) {
      return places.map(p => ({
        place: p,
        type: "Feature",
        geometry: {
          type: "Point",
          coordinates: [p.long, p.lat]
        }
      }));
    }
  },

  mounted() {
    this.initMap();
  },

  watch: {
    $route($newRoute, $oldRoute) {
      this.updateMarkerElements();
    }
  },

  methods: {
    updateMarkerElements() {
      $(".marker").css({ opacity: 1 });

      if (!this.isLocation) {
        $("canvas").css({ opacity: "1" });
        this.resetActiveMarkers();
      } else {
        $("canvas").css({ opacity: "0.5" });
      }

      if (this.isLocation) {
        $(".marker:not(.active)").css({ opacity: "0.1" });
      }
    },
    initMap() {
      let center = [-81.348852, 41.15002];

      if (this.isLocation) {
        center = [+this.currentLocation.long, +this.currentLocation.lat];
      }

      mapboxgl.accessToken =
        "pk.eyJ1IjoibmF0ZWhvYmkiLCJhIjoiY2s3MHg2dTlxMDEzYzNnbnkweWJnbHZzOCJ9.uLkc9fWDpYi6Y_ojutcgWA";

      this.map = new mapboxgl.Map({
        container: "map",
        style: "mapbox://styles/natehobi/ck6v53o4u08m31it2o4mtf1b2",

        center: center,

        maxZoom: 17.5,
        minZoom: 14.5,
        zoom: 16,

        maxBounds: [
          [-81.39301041235215, 41.132502224091496],
          [-81.32319121255, 41.18378981482479]
        ]
      });

      this.mapboxMarkers = this.markers.map(marker => {
        let el = document.createElement("div");

        el.className = "marker";

        let mapboxMarker = new mapboxgl.Marker(el)
          .setLngLat(marker.geometry.coordinates)
          .addTo(this.map);

        el.addEventListener("click", e => {
          this.handleMarkerClick(marker, e);
        });

        return mapboxMarker;
      });

      if (this.isLocation) {
        this.setInitialActiveMarker();
      }
    },

    getMapboxMarker(location) {
      return this.mapboxMarkers.find(
        m =>
          +m._lngLat.lng === +location.long && m._lngLat.lat === +location.lat
      );
    },

    setInitialActiveMarker() {
      let mbm = this.getMapboxMarker(this.currentLocation);

      $(mbm._element).addClass("active");

      this.updateMarkerElements();
    },

    resetActiveMarkers() {
      $(".marker").removeClass("active");
    },

    handleMarkerClick(marker, e) {
      e.preventDefault();

      // this.map.flyTo({
      //   center: [+marker.place.long, +marker.place.lat],
      //   curve: 0
      // });

      /**
       * The active markers are reset whenever the
       * route changes and the new route is not a location
       */
      setTimeout(() => {
        this.$emit("location-clicked", marker.place);
        $(e.target).addClass("active");
      }, 300);
    }
  }
};
</script>