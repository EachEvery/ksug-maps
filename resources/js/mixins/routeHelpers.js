import { mapState, mapGetters } from "vuex";

export default {
    methods: {
        isLocationRoute($route) {
            return ["location", "preview", "story"].includes($route.name);
        }
    },

    computed: {
        ...mapState(["stories"]),

        isLocation({ $route }) {
            return this.isLocationRoute($route);
        },

        story({ stories }) {
            return stories.find(s => +s.id === +this.$route.params.story);
        },

        currentLocation({ places }) {
            if (this.story) {
                return this.story.place;
            }

            if (places === undefined) return undefined;

            return places.find(
                item => item.slug === this.$route.params.location
            );
        },
        isAbout({ $route }) {
            return $route.name === "about";
        }
    }
};
