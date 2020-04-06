export default {
    data() {
        return {
            showLandmarks: true,
        };
    },

    watch: {
        mapLoaded() {
            this.setLandmarkOpacity();
        },

        showLandmarks() {
            this.setLandmarkOpacity();
        },
    },
    methods: {
        setLandmarkOpacity() {
            let layerIds = [
                "ksu-campus-label",
                "ksu-campus",
                "historic-landmark-label",
                "historic-landmark",
                "students-killed",
                "students-wounded",
            ];

            layerIds.forEach((id) => {
                this.map.setLayoutProperty(
                    id,
                    "visibility",
                    this.showLandmarks && !this.isTour ? "visible" : "none"
                );
            });
        },
    },
};
