import { mapState } from "vuex";
import { orderPlaces } from "../functions/helpers";

export default {
    computed: {
        ...mapState(["directions", "tourActive", "tourStories"]),

        isTour({ $route }) {
            return $route.name === "tour" || this.tourActive;
        },

        tourStops({ includedPlaces, tour }) {
            return orderPlaces(includedPlaces, tour).map(p => ({
                place: p,
                type: "Feature",
                properties: {
                    description: p.name
                },
                geometry: {
                    type: "Point",
                    coordinates: [p.long, p.lat]
                }
            }));
        },

        includedPlaces({ tour, places }) {
            let includedPlacesIds = tour.stories.map(s => +s.place.id);

            return places.filter(p => includedPlacesIds.includes(+p.id));
        }
    },

    methods: {
        handleTourStopClick(tourStop, e) {},

        getSortOrder(place) {
            let firstPlaceStory = this.tour.stories.find(
                s => place.id === s.place_id
            );

            return firstPlaceStory.pivot.sort_order;
        },

        createRouteMarkers() {
            this.routeMarkers = this.tourStops.map((tourStop, i) => {
                let el = document.createElement("div");

                el.className = "tour-stop";
                el.id = "place-marker-" + tourStop.place.id;
                el.innerHTML = `<span>${i + 1}</span>`;

                let mapboxMarker = new mapboxgl.Marker(el)
                    .setLngLat(tourStop.geometry.coordinates)
                    .addTo(this.map);

                $(".tour-stop").click(function() {
                    $(".tour-stop").removeClass("active");

                    $(this).addClass("active");
                });

                return {
                    ...tourStop,
                    ...mapboxMarker
                };
            });
        },

        removeRouteFromMap() {
            if (this.tourActive) {
                return;
            }

            return new Promise((resolve, reject) => {
                if (this.map.getLayer("routeLayer") !== undefined) {
                    this.map.removeLayer("routeLayer");
                }

                if (this.map.getSource("routeSource") !== undefined) {
                    this.map.removeSource("routeSource");
                }

                $(".tour-stop").remove();

                setTimeout(() => {
                    resolve();
                }, 400);
            });
        },

        async addRouteLayer() {
            await this.removeRouteFromMap();

            this.createRouteMarkers();

            this.map.addSource("routeSource", {
                type: "geojson",
                data: {
                    type: "Feature",
                    properties: {},
                    geometry: {
                        type: "LineString",
                        coordinates: this.directions.routes[0].geometry
                            .coordinates
                    }
                }
            });

            this.map.addLayer({
                id: "routeLayer",
                type: "line",
                source: "routeSource",
                layout: {
                    "line-join": "round",
                    "line-cap": "round"
                },
                paint: {
                    "line-color": "#fff",
                    "line-width": 4,
                    "line-dasharray": [0.1, 2]
                }
            });
        },
        syncMapState() {
            if (!this.mapLoaded) {
                return;
            }

            if (this.isTour) {
                this.addRouteLayer();
            } else {
                if (!this.tourActive) {
                    this.removeRouteFromMap();
                }
            }
        }
    },

    watch: {
        $route() {
            this.syncMapState();
        },

        directions() {
            this.syncMapState();
        },

        mapLoaded() {
            this.syncMapState();
        }
    }
};
