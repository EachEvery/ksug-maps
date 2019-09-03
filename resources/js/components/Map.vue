  <template>
  <div class="relative w-full h-screen bg-gray-dark">
    <div id="map" ref="mapElement" class="w-full h-screen relative transition bg-gray-darkest hide"></div>

    <clickable
      class="absolute bottom-0 left-0 h-16 w-16 flex justify-center rounded-full m-5 transition mb-32 md:mb-5"
      :class="{'bg-black': overlayShowing, 'bg-white': !overlayShowing, 'opacity-0': !showOverlayButton}"
      @click="handleClick"
    >
      <map-icon
        v-if="!isLoading"
        class="w-8 h-8 self-center transition"
        :class="{'text-white': overlayShowing, 'text-black': !overlayShowing}"
      />

      <spinner class="w-16 h-16" v-if="isLoading" />
    </clickable>
  </div>
</template>
<script>
import loadGoogleMapsApi from "load-google-maps-api";

import overlayFactory from "../functions/overlayFactory";

import { mapTheme } from "../functions/ksug";

import clickable from "./Clickable";
import mapIcon from "./MapIcon";

import spinner from "./Spinner";
import MarkerCluster from "@google/markerclusterer";

export default {
  components: {
    mapIcon,
    clickable,
    spinner
  },
  props: {
    locations: Array,
    showOverlayButton: Boolean
  },
  data() {
    return {
      state: "loading",
      googleMaps: undefined,
      map: undefined,
      overlay: undefined,
      bounds: undefined,
      overlayShowing: false
    };
  },
  computed: {
    isLoading({ state }) {
      return state === "loading";
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

      this.markers = this.locations.map(loc => {
        let marker = new this.googleMaps.Marker({
          position: new this.googleMaps.LatLng(+loc.lat, +loc.long),
          map: this.map,
          icon: {
            url: "/img/marker.png",
            scaledSize: new this.googleMaps.Size(41.25, 55)
          }
        });

        marker.addListener("click", () => {
          this.map.setZoom(17);

          let mapCenter = {
            lat: this.map.getCenter().lat(),
            lng: this.map.getCenter().lng()
          };

          let markerCenter = {
            lat: marker.getPosition().lat(),
            lng: marker.getPosition().lng()
          };
          let diffThreshold = 0.0005;
          let latDiff = Math.abs(mapCenter.lat - markerCenter.lat);
          let lngDiff = Math.abs(mapCenter.lng - markerCenter.lng);

          /**
           * only pan the map if the marker that was clicked
           * is not centered enough, otherwise, just emit the
           * event right away so there's no click delay
           */
          if (latDiff > diffThreshold || lngDiff > diffThreshold) {
            this.map.panTo(marker.getPosition());

            /**
             * Since we can't control the duration of the google maps
             * panning and there is no callback / promise mechanism,
             * we just have to hack it with a good ol fashioned timeout 😔
             */
            setTimeout(() => {
              this.$emit(`location-clicked`, loc);
            }, 600);
          } else {
            this.$emit("location-clicked", loc);
          }
        });

        return marker;
      });

      // this.cluster = new MarkerCluster(this.map, this.markers, {
      //   imagePath:
      //     "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m"
      // });

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

