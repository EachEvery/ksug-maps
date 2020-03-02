export default {
    methods: {
        isLocationRoute($route) {
            return ["location", "preview", "story"].includes($route.name);
        }
    },
    computed: {
        isLocation({ $route }) {
            return this.isLocationRoute($route);
        },
        currentLocation({ places }) {
            if (places === undefined) return undefined;

            return places.find(
                item => item.slug === this.$route.params.location
            );
        }
    }
};
