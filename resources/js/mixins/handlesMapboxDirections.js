import { mapState } from "vuex";

export default {
    computed: {
        ...mapState(["directions"])
    },
    methods: {
        remove(prop, id) {
            let get = `get${_.capitalize(prop)}`;
            let remove = `remove${_.capitalize(prop)}`;

            if (this.map[get](id) !== undefined) {
                console.log(prop, id, "exists, removing...");
                this.map[remove](id);

                return;
            }

            console.log(prop, id, "does not exist, ignorning...");
        },

        removeRouteLayer() {
            this.remove("source", "route");
            this.remove("layer", "route");
        },

        addRouteLayer() {
            this.map.addSource("route", {
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
                id: "route",
                type: "line",
                source: "route",
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
                this.removeRouteLayer();
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
