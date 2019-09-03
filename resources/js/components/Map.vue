<template>
  <div class="relative w-full h-screen bg-gray-dark">
    <div
      id="map"
      ref="mapElement"
      class="w-full h-screen relative transition bg-gray-darkest"
      :class="{'opacity-0': state === 'loading'}"
    ></div>

    <clickable
      class="absolute bottom-0 left-0 h-16 w-16 flex justify-center rounded-full m-5 transition"
      :class="{'bg-black': overlayShowing, 'bg-white': !overlayShowing}"
      @click="handleClick"
    >
      <map-icon
        class="w-8 h-8 self-center transition"
        :class="{'text-white': overlayShowing, 'text-black': !overlayShowing}"
      />
    </clickable>

    <div class="inset-0 bg-black absolute transition" :class="mapTransparentOverlayClass"></div>
  </div>
</template>
<script>
import loadGoogleMapsApi from "load-google-maps-api";
import { mapTheme } from "../functions/ksug";
import overlayFactory from "../functions/overlayFactory";
import { clearInterval } from "timers";
import clickable from "./Clickable";
import mapIcon from "./MapIcon";

export default {
  components: {
    mapIcon,
    clickable
  },
  data() {
    return {
      state: "loading",
      googleMaps: undefined,
      map: undefined,
      overlay: undefined,
      bounds: undefined,
      overlayShowing: true
    };
  },
  computed: {
    mapTransparentOverlayClass({ state }) {
      return {
        "opacity-0": state === "default",
        "opacity-75": state === "loading",
        invisible: state === "default"
      };
    }
  },
  methods: {
    handleClick() {
      if (this.overlayShowing) {
        this.overlay.hide();
        this.overlayShowing = false;
      } else {
        this.overlay.show();
        this.overlayShowing = true;
      }
    },
    async initMap() {
      this.googleMaps = await loadGoogleMapsApi({
        key: process.env.MIX_GOOGLE_MAPS_JS_API_KEY
      });

      this.map = new this.googleMaps.Map(this.$refs.mapElement, {
        center: { lat: 41.15002, lng: -81.348852 },
        backgroundColor: "#000000",
        disablePanMomentum: true,
        zoom: 16,
        minZoom: 16,
        maxZoom: 18,
        styles: mapTheme,
        disableDefaultUI: true,
        bounds: this.bounds,
        restriction: {
          latLngBounds: {
            east: -81.309106,
            north: 41.186267,
            south: 41.1119,
            west: -81.404828
          },
          strictBounds: false
        }
      });

      this.bounds = new this.googleMaps.LatLngBounds(
        new this.googleMaps.LatLng(41.1119, -81.404828), // SW CORNER 🧭
        new this.googleMaps.LatLng(41.186267, -81.309106) // NE CORNER 🧭
      );

      this.overlay = overlayFactory(
        this.googleMaps,
        this.bounds,
        process.env.MIX_OVERLAY_PHOTO,
        this.map
      );

      // this.map.getCenter();

      this.checkImageLoadedInterval = setInterval(() => {
        if (this.overlay.imageLoaded) {
          setTimeout(() => {
            this.state = "default";
          }, 800);
        }
      }, 800);
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.initMap();
    });
  }
};
</script>

