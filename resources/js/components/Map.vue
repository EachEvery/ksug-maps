<template>
  <div :style="{ 'pointer-events': blockClicks ? 'none' : 'auto' }" class="relative">
    <div class="relative w-full h-screen bg-gray-dark" id="map"></div>

    <portal to="end-of-document">
      <div
        class="absolute bottom-0 left-0 w-full w-screen md:w-25rem p-6 bg-white transition"
        :class="{'translate-y-full': !showingLayersMenu}"
        v-click-outside="handleLayersClickOutside"
      >
        <h1 class="uppercase font-display text-3xl">Toggle Map Layers</h1>

        <div class="flex justify-between mt-5">
          <span class="font-mono text-md font-bold">1970 Aerial Photo</span>

          <div class="flex items-center">
            <on-off-switch v-model="showAerialPhoto" />
            <span
              class="w-8 font-mono text-md ml-3 font-bold uppercase"
            >{{showAerialPhoto ? 'On' : 'Off'}}</span>
          </div>
        </div>

        <div class="flex justify-between mt-5">
          <span class="font-mono text-md font-bold">1970 Landmarks</span>

          <div class="flex items-center">
            <on-off-switch v-model="showLandmarks" />
            <span
              class="w-8 font-mono text-md ml-3 font-bold uppercase"
            >{{showLandmarks ? 'On' : 'Off'}}</span>
          </div>
        </div>
      </div>
    </portal>
    <portal to="end-of-document">
      <div
        class="flex flex-col fixed bottom-0 left-0 mb-48 md:mb-5 ml-5 transition"
        :class="{
                    '-translate-x-20': !showControls,
                    'opacity-0': !showControls
                }"
      >
        <clickable
          class="h-12 w-12 flex justify-center rounded-full transition-faster mb-3"
          :class="{
                        'bg-black': showAerialPhoto,
                        'bg-white': !showAerialPhoto
                    }"
          @click.stop="handleOverlayButtonClick"
        >
          <layer-icon
            class="w-6 h-6 self-center transition"
            :class="{
                            'text-white': showAerialPhoto,
                            'text-black': !showAerialPhoto
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

import onOffSwitch from "./OnOffSwitch";
import { getMapboxToken } from "../functions/helpers";
import { mapMutations } from "vuex";

mapboxgl.accessToken = getMapboxToken();

export default {
  components: { plusIcon, minusIcon, layerIcon, clickable, onOffSwitch },

  mixins: [
    routeHelpers,
    handlesMapboxZoom,
    handlesArialOverlay,
    handlesMapboxMarkers,
    handlesMapboxDirections
  ],

  props: {
    places: Array,
    filters: Array,
    blockClicks: Boolean
  },

  mounted() {
    this.initMap();
  },

  data() {
    return {
      mapLoaded: false,
      state: "default",
      showLandmarks: true,
      showAerialPhoto: true
    };
  },
  computed: {
    showingLayersMenu({ state }) {
      return state === "showingLayersMenu";
    },
    showControls({ showingLayersMenu, showOverlayButton }) {
      return showOverlayButton && !showingLayersMenu;
    }
  },

  watch: {
    state() {
      clearTimeout(this.clickOutsideGate);

      setTimeout(() => {
        this.canClickOutside = this.showingLayersMenu;
      }, 300);
    },
    showLandmarks(val) {
      let layerIds = [
        "ksu-campus-label",
        "ksu-campus",
        "historic-landmark-label",
        "historic-landmark",
        "students-killed",
        "students-wounded"
      ];

      //   this.map.setPaintProperty("historic-landmark", "raster-opacity", 0);

      layerIds.forEach(id => {
        // this.map.setLayoutProperty(clickedLayer, 'visibility', 'none');
        this.map.setLayoutProperty(id, "visibility", val ? "visible" : "none");
      });
    }
  },
  methods: {
    ...mapMutations(["addGeolocation"]),

    handleLayersClickOutside() {
      if (this.canClickOutside) {
        this.state = "default";
      }
    },

    handleOverlayButtonClick() {
      this.state = "showingLayersMenu";
    },

    initMap() {
      let center = [-81.348852, 41.15002];

      if (this.isLocation) {
        center = [+this.currentLocation.long, +this.currentLocation.lat];
      }

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

      this.map.on("load", () => {
        this.mapLoaded = true;
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

      navigator.geolocation.watchPosition(pos => {
        this.addGeolocation(pos);
      });

      this.map.on("click", "students-killed", e => {
        new mapboxgl.Popup()
          .setLngLat(e.lngLat)
          .setHTML(e.features[0].properties.Name)
          .addTo(this.map);
      });

      this.map.on("click", "students-wounded", e => {
        new mapboxgl.Popup()
          .setLngLat(e.lngLat)
          .setHTML(e.features[0].properties.Name)
          .addTo(this.map);
      });

      this.map.on("mouseenter", "students-killed", () => {
        this.map.getCanvas().style.cursor = "pointer";
      });

      // Change it back to a pointer when it leaves.
      this.map.on("mouseleave", "students-killed", () => {
        this.map.getCanvas().style.cursor = "";
      });

      this.map.on("mouseenter", "students-wounded", () => {
        this.map.getCanvas().style.cursor = "pointer";
      });

      // Change it back to a pointer when it leaves.
      this.map.on("mouseleave", "students-wounded", () => {
        this.map.getCanvas().style.cursor = "";
      });

      if (this.isLocation) {
        this.setInitialActiveMarker();
      }

      $(".mapboxgl-ctrl-geolocate").click();
    }
  }
};
</script>
