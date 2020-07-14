<template>
    <div class="fixed inset-0 flex justify-center items-center">
        <h1
            style="transform: translateY(-100%)"
            :class="{ 'opacity-0': state !== 'loaded' }"
            class="text-shadow font-display uppercase text-white text-md transition"
        >
            {{ location.name }}
        </h1>
    </div>
</template>
<script>
import { mapState } from "vuex";

export default {
    computed: {
        ...mapState(["places"]),

        location({ places }) {
            return places.find(
                item => item.slug === this.$route.params.location
            );
        }
    },
    data() {
        return {
            state: "default"
        };
    },
    mounted() {
        this.$store.commit("setMapCenter", [
            +this.location.lat,
            +this.location.long,
            16
        ]);

        setTimeout(() => {
            this.state = "loaded";
        }, 1000);
    }
};
</script>
