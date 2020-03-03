export default {
    props: {
        showOverlayButton: Boolean
    },

    data() {
        return {
            overlayShowing: true
        };
    },

    methods: {
        handleOverlayButtonClick() {
            this.overlayShowing = this.toggleArialPhotoLayer();
        },
        toggleArialPhotoLayer() {
            let currentValue = this.map.getPaintProperty(
                "arial-photo",
                "raster-opacity"
            );

            let newValue = currentValue === 1 ? 0 : 1;

            this.map.setPaintProperty(
                "arial-photo",
                "raster-opacity",
                newValue
            );

            return newValue === 1;
        }
    }
};
