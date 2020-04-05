import { mapState } from "vuex";

export default {
    computed: {
        ...mapState(["routes"]),
    },

    methods: {
        back() {
            if (this.routes.length < 2) {
                this.$router.push(this.defaultBackRoute);
            } else {
                this.$router.go(-1);
            }
        },
    },
};
