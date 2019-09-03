  <template>
  <div class="relative w-full h-screen bg-gray-dark">
    <div
      id="map"
      ref="mapElement"
      class="w-full h-screen relative transition bg-gray-darkest hide"
      :class="{'opacity-0': state === 'loading'}"
    ></div>

    <clickable
      class="absolute bottom-0 left-0 h-16 w-16 flex justify-center rounded-full m-5 transition"
      :class="{'bg-black': overlayShowing, 'bg-white': !overlayShowing, 'opacity-0': !showOverlayButton}"
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

import overlayFactory from "../functions/overlayFactory";

import { mapTheme } from "../functions/ksug";
import { clearInterval } from "timers";

import clickable from "./Clickable";
import mapIcon from "./MapIcon";

export default {
  components: {
    mapIcon,
    clickable
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
        maxZoom: 16.5,
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

      // new MarkerClusterer(this.map, this.markers, {
      //   imagePath:
      //     "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m"
      // });

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

