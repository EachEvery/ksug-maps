<template>
    <div
        :style="{ 'pointer-events': blockClicks ? 'none' : 'auto' }"
        class="relative"
    >
        <div class="relative w-full h-screen bg-gray-dark" id="map"></div>

        <portal to="end-of-document">
            <div
                class="absolute bottom-0 left-0 w-full w-screen md:w-25rem p-3 md:p-6 bg-white transition"
                :class="{ 'translate-y-full': !showingLayersMenu }"
                v-click-outside="handleLayersClickOutside"
            >
                <h1 class="uppercase font-display text-xl md:text-3xl">
                    Toggle Map Layers
                </h1>

                <em class="opacity-75 font-mono text-xs" v-if="isTour"
                    >Map layers are disabled during tours...</em
                >

                <div
                    class="flex justify-between mt-5"
                    :class="{ 'opacity-25': isTour }"
                >
                    <span class="font-mono text-sm md:text-md font-bold"
                        >1970 Aerial Photo</span
                    >

                    <div class="flex items-center">
                        <on-off-switch v-model="showAerialPhoto" />
                        <span
                            class="w-8 font-mono text-sm md:text-md ml-3 font-bold uppercase"
                            >{{ showAerialPhoto ? "On" : "Off" }}</span
                        >
                    </div>
                </div>

                <div
                    class="flex justify-between mt-5"
                    :class="{ 'opacity-25': isTour }"
                >
                    <span class="font-mono text-sm md:text-md font-bold"
                        >1970 Landmarks</span
                    >

                    <div class="flex items-center">
                        <on-off-switch v-model="showLandmarks" />
                        <span
                            class="w-8 font-mono text-sm md:text-md ml-3 font-bold uppercase"
                            >{{ showLandmarks ? "On" : "Off" }}</span
                        >
                    </div>
                </div>
            </div>
        </portal>
        <portal to="end-of-document">
            <div
                class="flex flex-col fixed bottom-0 left-0 mb-24 md:mb-5 ml-5 transition"
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
import handlesLandmarkLayer from "../mixins/handlesLandmarkLayer";
import handlesMapSync from "../mixins/handlesMapSync";

import clickable from "./Clickable";
import plusIcon from "./PlusIcon";
import minusIcon from "./MinusIcon";
import layerIcon from "./LayerIcon";

import onOffSwitch from "./OnOffSwitch";

import { getMapboxToken } from "../functions/helpers";
import { mapMutations, mapState } from "vuex";

mapboxgl.accessToken = getMapboxToken();

export default {
    components: { plusIcon, minusIcon, layerIcon, clickable, onOffSwitch },

    mixins: [
        routeHelpers,
        handlesMapboxZoom,
        handlesArialOverlay,
        handlesMapboxMarkers,
        handlesMapboxDirections,
        handlesLandmarkLayer,
        handlesMapSync
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
            state: "default"
        };
    },

    computed: {
        showingLayersMenu({ state }) {
            return state === "showingLayersMenu";
        },

        showControls({ showingLayersMenu, showOverlayButton, isTour }) {
            return showOverlayButton && !showingLayersMenu && !isTour;
        }
    },

    watch: {
        state() {
            clearTimeout(this.clickOutsideGate);

            setTimeout(() => {
                this.canClickOutside = this.showingLayersMenu;
            }, 300);
        }
    },
    methods: {
        ...mapMutations(["addGeolocation"]),

        addWoundedKilledPopover(e) {
            this.removeWoundedKilledPopover();

            this.studentPopover = new mapboxgl.Popup()
                .setLngLat(e.lngLat)
                .setHTML(e.features[0].properties.Name)
                .addTo(this.map);
        },

        removeWoundedKilledPopover() {
            if (this.studentPopover) {
                this.studentPopover.remove();
            }
        },

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
                center = [
                    +this.currentLocation.long,
                    +this.currentLocation.lat
                ];
            }

            this.map = new mapboxgl.Map({
                container: "map",
                style: "mapbox://styles/natehobi/ck9myc8jk0jqh1io77cjzbikc",

                center: center,

                maxZoom: this.zoomSteps[this.zoomSteps.length - 1],
                minZoom: this.zoomSteps[0],
                zoom: this.isLocation ? 16 : this.currentZoom,

                maxBounds: [
                    [-81.404828, 41.1119],
                    [-81.309106, 41.186267]
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

                let popup = new mapboxgl.Popup({
                    closeButton: false,
                    closeOnClick: true
                });

                let storyCount = marker.place.stories.length;

                popup
                    .setLngLat(marker.geometry.coordinates)
                    .setHTML(
                        `<div class="flex flex-col"><span class="font-display uppercase text-lg leading-none">${
                            marker.place.name
                        }</span><span class="font-mono text-sm uppercase mt-2">${storyCount} ${
                            storyCount === 1 ? "Story" : "Stories"
                        }</span></div>`
                    );

                el.addEventListener("click", e => {
                    this.handleMarkerClick(marker, e);
                });

                el.addEventListener("mouseenter", e => {
                    popup.addTo(this.map);
                });

                el.addEventListener("mouseleave", e => {
                    popup.remove();
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

            this.map.on(
                "click",
                "students-killed",
                this.addWoundedKilledPopover
            );
            this.map.on(
                "mouseover",
                "students-killed",
                this.addWoundedKilledPopover
            );

            this.map.on(
                "mouseover",
                "students-wounded",
                this.addWoundedKilledPopover
            );

            this.map.on(
                "click",
                "students-wounded",
                this.addWoundedKilledPopover
            );

            this.map.on("mouseenter", "students-killed", () => {
                this.map.getCanvas().style.cursor = "pointer";
            });

            // Change it back to a pointer when it leaves.
            this.map.on("mouseleave", "students-killed", e => {
                this.map.getCanvas().style.cursor = "";
                this.removeWoundedKilledPopover();
            });

            this.map.on("mouseenter", "students-wounded", () => {
                this.map.getCanvas().style.cursor = "pointer";
            });

            // Change it back to a pointer when it leaves.
            this.map.on("mouseleave", "students-wounded", e => {
                this.map.getCanvas().style.cursor = "";
                this.removeWoundedKilledPopover();
            });

            if (this.isLocation) {
                this.setInitialActiveMarker();
            }

            $(".mapboxgl-ctrl-geolocate").click();
        }
    }
};
</script>
