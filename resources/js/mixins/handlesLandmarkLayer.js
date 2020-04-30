export default {
    data() {
        return {
            showLandmarks: true
        };
    },

    watch: {
        mapLoaded() {
            this.setLandmarkOpacity();
        },

        showLandmarks() {
            this.setLandmarkOpacity();
        }
    },
    methods: {
        setLandmarkOpacity() {
            let layerIds = [
                "nhlnameonly",
                "ksu-campus-label",
                "ksu-campus",
                "historic-landmark-label",
                "historic-landmark",
                "students-killed",
                "students-wounded",
                "national-historic-landmark-site",
                "landmarks"
            ];

            layerIds.forEach(id => {
                this.map.setLayoutProperty(
                    id,
                    "visibility",
                    this.showLandmarks && !this.isTour ? "visible" : "none"
                );
            });
        }
    }
};
