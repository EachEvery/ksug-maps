import { mapState } from "vuex";

export default {
    computed: {
        ...mapState(["routes", "tourActive"])
    },

    methods: {
        back() {
            if (this.$route.name === "preview") {
                this.$router.push(this.defaultBackRoute);
                return;
            }

            if (this.routes.length < 2 && !this.tourActive) {
                this.$router.push(this.defaultBackRoute);
            } else {
                this.$router.go(-1);
            }
        }
    }
};
