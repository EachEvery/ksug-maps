  <template>
  <div class="relative w-full h-screen bg-gray-dark">
    <div id="map" ref="mapElement" class="w-full h-screen relative transition bg-gray-darkest hide"></div>

    <div class="absolute inset-0 bg-black transition" :class="overlayClass"></div>

    <div
      class="absolute inset-0 flex justify-center transition select-none"
      style="margin-top: -3.5rem"
      :class="activeMarkerIndicatorClass"
    >
      <div
        class="w-24 h-24 rounded-full border-2 border-dotted border-white self-center flex justify-center"
      >
        <img src="/img/marker.png" style="width: 41.25px; height: 55px;" class="self-center" />
      </div>
    </div>

    <clickable
      class="fixed bottom-0 left-0 h-16 w-16 flex justify-center rounded-full m-5 transition mb-5"
      :class="{'bg-black': overlayShowing, 'bg-white': !overlayShowing, 'opacity-0': !showOverlayButton}"
      @click="handleClick"
    >
      <map-icon
        v-if="!isLoading"
        class="w-8 h-8 self-center transition"
        :class="{'text-white': overlayShowing, 'text-black': !overlayShowing, }"
      />

      <spinner class="w-16 h-16" v-if="isLoading" />
    </clickable>

    <div class="flex fixed bottom-0 right-0 mb-5 mr-5" :class="{'opacity-0': !showOverlayButton}">
      <clickable
        class="transition w-12 h-12 bg-white flex justify-center text-black rounded-full shadow mr-3"
        :class="{'opacity-25':prevZoom === currentZoom}"
        @click="() => zoom(prevZoom)"
      >
        <minus-icon class="w-5 h-5 self-center" />
      </clickable>

      <clickable
        class="transition w-12 h-12 bg-white flex justify-center text-black rounded-full shadow"
        :class="{'opacity-25':nextZoom === currentZoom}"
        @click="() => zoom(nextZoom)"
      >
        <plus-icon class="w-5 h-5 self-center" />
      </clickable>
    </div>
  </div>
</template>
<script>
import loadGoogleMapsApi from "load-google-maps-api";
import overlayFactory from "../functions/overlayFactory";
import plusIcon from "./PlusIcon";
import minusIcon from "./MinusIcon";

import { mapTheme } from "../functions/ksug";

import clickable from "./Clickable";
import mapIcon from "./MapIcon";

import spinner from "./Spinner";
import MarkerCluster from "@google/markerclusterer";
import { setTimeout } from "timers";

export default {
  components: {
    mapIcon,
    clickable,
    spinner,
    plusIcon,
    minusIcon
  },
  props: {
    locations: Array,
    showOverlayButton: Boolean,
    isLocation: Boolean,
    filters: Array
  },
  data() {
    return {
      state: "loading",
      animatePan: true,
      googleMaps: undefined,
      map: undefined,
      overlay: undefined,
      bounds: undefined,
      strictBounds: false,
      overlayShowing: false,
      currentZoom: this.isLocation ? 17 : 16,
      zoomSteps: [15, 16, 17, 18],
      zooming: false,
      maxZoom: 18,
      minZoom: 15,
      markerRecentlyClicked: false
    };
  },
  computed: {
    currentLocation() {
      if (!this.isLocation) {
        return undefined;
      }

      if (this.$route.name === "story") {
        return this.$store.state.stories.find(
          item => item.id === +this.$route.params.story
        ).place;
      }

      return this.$store.getters.locations.find(
        loc => loc.slug === this.$route.params.location
      );
    },
    activeMarkerIndicatorClass({ isLocation }) {
      return {
        "opacity-0": !isLocation,
        invisible: !isLocation
      };
    },
    overlayClass({ isLocation }) {
      return {
        "opacity-50": isLocation,
        "opacity-0": !isLocation,
        invisible: !isLocation
      };
    },

    isLoading({ state }) {
      return state === "loading";
    },

    prevZoom({ currentZoom, zoomSteps }) {
      let index = zoomSteps.findIndex(s => currentZoom === s);
      let prev = zoomSteps[index - 1];

      return prev === undefined ? zoomSteps[0] : prev;
    },

    nextZoom({ currentZoom, zoomSteps }) {
      let index = zoomSteps.findIndex(s => currentZoom === s);
      let next = zoomSteps[index + 1];

      return next === undefined ? zoomSteps[zoomSteps.length - 1] : next;
    }
  },
  watch: {
    $route() {
      if (!this.markerRecentlyClicked && this.isLocation) {
        this.panToLocation(this.currentLocation);
      }
    },
    filters(filters) {
      this.markers.forEach(marker => {
        let filteredStories = marker.loc.stories.filter(item => {
          for (let i = 0; i < filters.length; i++) {
            let filter = filters[i];

            if (item[filter.key.trim()] === filter.value.trim()) {
              return true;
            }
          }

          return false;
        });

        marker.setIcon({
          url:
            filteredStories.length === 0 && filters.length > 0
              ? "/img/marker-transparent.png"
              : "/img/marker.png",
          scaledSize: new this.googleMaps.Size(41.25, 55)
        });
      });
    }
  },
  methods: {
    handleClick() {
      if (this.isLoading) {
        return;
      }

      if (this.overlayShowing) {
        this.overlay.hide();
        this.overlayShowing = false;
      } else {
        this.overlay.show();
        this.overlayShowing = true;
      }
    },

    panToLocation(loc) {
      return new Promise(async resolve => {
        let location = new this.googleMaps.LatLng(+loc.lat, +loc.long);

        if (this.map.getZoom() === 15) {
          await this.zoom(this.nextZoom);
        }

        let mapCenter = {
          lat: this.map.getCenter().lat(),
          lng: this.map.getCenter().lng()
        };

        let markerCenter = {
          lat: location.lat(),
          lng: location.lng()
        };

        let diffThreshold = 0.0;
        let latDiff = Math.abs(mapCenter.lat - markerCenter.lat);
        let lngDiff = Math.abs(mapCenter.lng - markerCenter.lng);

        if (latDiff > diffThreshold || lngDiff > diffThreshold) {
          this.pan(location);

          setTimeout(() => {
            resolve();
          }, 450);
        } else {
          resolve();
        }
      });
    },
    zoom(zoom) {
      return new Promise(resolve => {
        if (this.map.getZoom() === zoom || this.zooming) {
          resolve();
        } else {
          this.zooming = true;
          this.map.setZoom(zoom);

          setTimeout(() => {
            this.zooming = false;
            resolve();
          }, 450);
        }
      });
    },
    pan(loc) {
      return new Promise((resolve, reject) => {
        if (this.animatePan) {
          this.map.panTo(loc);

          setTimeout(resolve, 500);
        } else {
          this.map.setCenter(loc);
          setTimeout(resolve, 150);
        }
      });
    },
    async initMap() {
      this.googleMaps = await loadGoogleMapsApi({
        key: process.env.MIX_GOOGLE_MAPS_JS_API_KEY
      });

      let overrideOptionsForLocation = !this.isLocation
        ? {}
        : {
            center: {
              lat: +this.currentLocation.lat,
              lng: +this.currentLocation.long
            }
          };

      this.map = new this.googleMaps.Map(this.$refs.mapElement, {
        center: { lat: 41.15002, lng: -81.348852 },
        backgroundColor: "#000000",
        disableDoubleClickZoom: true,
        disablePanMomentum: true,
        zoom: this.currentZoom,
        minZoom: this.zoomSteps[0],
        maxZoom: this.zoomSteps[this.zoomSteps - 1],
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
        },
        ...overrideOptionsForLocation
      });

      this.map.addListener("zoom_changed", () => {
        this.currentZoom = this.map.getZoom();
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

      this.markers = this.locations.map(loc => {
        let marker = new this.googleMaps.Marker({
          loc,
          position: new this.googleMaps.LatLng(+loc.lat, +loc.long),
          map: this.map,
          icon: {
            url: "/img/marker.png",
            scaledSize: new this.googleMaps.Size(41.25, 55)
          }
        });

        marker.addListener("click", async () => {
          this.markerRecentlyClicked = true;

          clearTimeout(this.markerTimeout);
          this.markerTimeout = setTimeout(() => {
            this.markerRecentlyClicked = false;
          }, 300);

          await this.panToLocation(loc);
          this.$emit("location-clicked", loc);
        });

        return marker;
      });

      /**
       * I'm sorry mom 😭
       */
      this.checkImageLoadedInterval = setInterval(() => {
        clearInterval(this.checkImageLoadedInterval);
        if (this.overlay.imageLoaded) {
          setTimeout(() => {
            this.overlay.show();

            setTimeout(() => {
              this.overlayShowing = true;
              this.state = "default";
            }, 800);
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

