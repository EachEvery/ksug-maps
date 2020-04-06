import { mapState } from "vuex";

export default {
    props: {
        showOverlayButton: Boolean,
    },

    data() {
        return {
            showAerialPhoto: true,
        };
    },
    computed: {
        ...mapState(["tourIsActive"]),
    },

    methods: {
        setOverlayOpacity() {
            this.map.setPaintProperty(
                "arial-photo",
                "raster-opacity",
                this.showAerialPhoto && !this.isTour ? 1 : 0
            );
        },
    },
    watch: {
        mapLoaded() {
            this.setOverlayOpacity();
        },

        showAerialPhoto() {
            this.setOverlayOpacity();
        },
    },
};
