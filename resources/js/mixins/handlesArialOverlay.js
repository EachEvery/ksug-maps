export default {
    props: {
        showOverlayButton: Boolean
    },

    watch: {
        showAerialPhoto(val) {
            this.map.setPaintProperty(
                "arial-photo",
                "raster-opacity",
                val ? 1 : 0
            );
        }
    }
};
