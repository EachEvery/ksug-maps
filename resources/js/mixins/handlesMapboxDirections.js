import { mapState } from "vuex";

export default {
    computed: {
        ...mapState(["directions"])
    },
    watch: {
        $route() {
            if (!this.isTour) {
                this.map.removeSource("route");
                this.map.removeLayer("route");
                return;
            }
        },

        directions(directions) {
            this.map.addSource("route", {
                type: "geojson",
                data: {
                    type: "Feature",
                    properties: {},
                    geometry: {
                        type: "LineString",
                        coordinates: directions.routes[0].geometry.coordinates
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
        }
    }
};
