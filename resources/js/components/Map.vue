<template>
  <div :style="{ 'pointer-events': isLocation ? 'none' : 'auto' }">
    <div class="relative w-full h-screen bg-gray-dark" id="map"></div>

    <portal to="end-of-document">
      <div
        class="flex flex-col fixed bottom-0 left-0 mb-48 ml-5 transition"
        :class="{
                    '-translate-x-20': !showOverlayButton,
                    'opacity-0': !showOverlayButton
                }"
      >
        <clickable
          class="h-12 w-12 flex justify-center rounded-full transition-faster mb-3"
          :class="{
                    'bg-black': overlayShowing,
                    'bg-white': !overlayShowing,                                        
                }"
          @click="handleOverlayButtonClick"
        >
          <layer-icon
            class="w-6 h-6 self-center transition"
            :class="{
                        'text-white': overlayShowing,
                        'text-black': !overlayShowing
                    }"
          />
        </clickable>

        <clickable
          class="transition w-12 h-12 bg-black text-white flex justify-center rounded-full shadow mb-3"
          :class="{ 'opacity-50': nextZoom < currentZoom }"
          @click="() => zoom(nextZoom)"
        >
          <plus-icon class="w-5 h-5 self-center" />
        </clickable>

        <clickable
          class="transition w-12 h-12 bg-black text-white flex justify-center rounded-full shadow"
          :class="{ 'opacity-50': prevZoom > currentZoom }"
          @click="() => zoom(prevZoom)"
        >
          <minus-icon class="w-5 h-5 self-center" />
        </clickable>
      </div>
    </portal>
  </div>
</template>

<script>
import mapbox from "mapbox-gl";
import jquery from "jquery";

import routeHelpers from "../mixins/routeHelpers";
import handlesMapboxZoom from "../mixins/handlesMapboxZoom";
import handlesArialOverlay from "../mixins/handlesArialOverlay";
import handlesMapboxMarkers from "../mixins/handlesMapboxMarkers";
import handlesMapboxDirections from "../mixins/handlesMapboxDirections";

import clickable from "./Clickable";
import plusIcon from "./PlusIcon";
import minusIcon from "./MinusIcon";
import layerIcon from "./LayerIcon";

import { getMapboxToken } from "../functions/helpers";

export default {
  components: { plusIcon, minusIcon, layerIcon, clickable },

  mixins: [
    routeHelpers,
    handlesMapboxZoom,
    handlesArialOverlay,
    handlesMapboxMarkers,
    handlesMapboxDirections
  ],

  props: {
    places: Array,
    filters: Array
  },

  mounted() {
    this.initMap();
  },

  methods: {
    initMap() {
      let center = [-81.348852, 41.15002];

      if (this.isLocation) {
        center = [+this.currentLocation.long, +this.currentLocation.lat];
      }

      mapboxgl.accessToken = getMapboxToken();

      this.map = new mapboxgl.Map({
        container: "map",
        style: "mapbox://styles/natehobi/ck7p8tsnj044n1iloiqoxe9f9",

        center: center,

        maxZoom: this.zoomSteps[this.zoomSteps.length - 1],
        minZoom: this.zoomSteps[0],
        zoom: this.isLocation ? 16 : this.currentZoom,

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

        return {
          ...marker,
          ...mapboxMarker
        };
      });

      this.map.addControl(
        new mapboxgl.GeolocateControl({
          positionOptions: {
            enableHighAccuracy: true
          },
          trackUserLocation: true
        })
      );

      if (this.isLocation) {
        this.setInitialActiveMarker();
      }

      $(".mapboxgl-ctrl-geolocate").click();
    }
  }
};
</script>
