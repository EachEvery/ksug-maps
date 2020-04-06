import { mapState } from "vuex";

export default {
    computed: {
        ...mapState(["mapCenter"]),
    },
    methods: {
        sync() {
            this.setLandmarkOpacity();
            this.updateMarkerElements();
            this.setOverlayOpacity();
        },
    },
    watch: {
        mapLoaded() {
            this.sync();
        },

        $route() {
            this.sync();
        },

        mapCenter(val) {
            this.updateActiveMarker(val);

            let currentZoom = this.map.getZoom();

            let ops = {
                center: [+val[1], +val[0]],
                curve: 0,
                zoom: val.length === 2 ? currentZoom : +val[2],
            };

            if (val.length === 3) {
                ops["zoom"] = +val[2];
            }

            if (val[0] === undefined || val[1] === undefined) {
                ops = {
                    zoom: val[2],
                };
            }

            setTimeout(() => {
                this.map.easeTo(ops);
            }, 400);
        },
    },
};
