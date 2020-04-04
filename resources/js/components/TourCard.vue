<template>
    <router-link
        class="relative shrink-when-active mb-8 flex flex-col"
        :to="`/tours/${tour.slug}`"
    >
        <div class="p-4 bg-black text-white" style="height: 45%">
            <h3 class="uppercase font-display text-xl font-bold leading-none">
                {{ tour.name }}
            </h3>

            <span class="font-mono mt-2">{{ tour.duration }}</span>
        </div>

        <img class="flex-grow object-cover" :src="tour.photos[0].url" />

        <div
            class="absolute font-mono text-black opacity-50 left-0 text-xs mt-2 tracking-wide"
            style="top: 100%;"
            v-if="distance"
        >
            {{ distance }}
        </div>
    </router-link>
</template>

<style scoped lang="scss">
.gradient {
    background: linear-gradient(
        to bottom,
        var(--transparentColor),
        var(--currentColor) 75%
    );
}
</style>
<script>
import quoteIcon from "./QuoteIcon";
import distance from "../mixins/distance";
import { mapGetters, mapState } from "vuex";

export default {
    mixins: [distance],

    components: {
        quoteIcon
    },

    props: {
        tour: Object
    },

    watch: {
        distance(val) {
            console.log(val, "wow");
        }
    },

    computed: {
        ...mapState(["places"]),

        firstStory({ tour }) {
            return tour.stories[0];
        },

        lat({ firstStory }) {
            return +firstStory.place.lat;
        },

        lng({ firstStory }) {
            return +firstStory.place.long;
        }
    }
};
</script>
