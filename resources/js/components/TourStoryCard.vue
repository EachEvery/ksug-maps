<template>
    <router-link
        class="bg-current p-5 relative shrink-when-active block w-full "
        style="height: 10rem"
        :to="`/stories/${story.id}`"
        :style="{
            '--transparentColor': transparentColor,
            '--currentColor': story.color
        }"
    >
        <h3 class="uppercase font-display font-bold text-md mt-1 text-black">
            {{ story.subject }}
        </h3>
        <h3
            class="font-mono text-2xs tracking-tight mb-1 font-bold uppercase text-black"
        >
            {{ story.role }} &middot; {{ story.day }}
        </h3>
        <p class="leading-normal mt-3 text-2xs text-black">
            {{ truncate(story.content, { length: 100 }) }}
        </p>
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
import _ from "lodash";
import { mapGetters, mapState } from "vuex";

export default {
    mixins: [distance],

    methods: {
        truncate: _.truncate
    },
    components: {
        quoteIcon
    },

    props: {
        story: Object
    },

    computed: {
        ...mapState(["places"]),
        place({ story, places }) {
            return places.find(p => +p.id === +story.place_id);
        },

        lat({ place }) {
            return +place.lat;
        },

        lng({ place }) {
            return +place.long;
        },

        transparentColor({ story }) {
            let hex = story.color;
            let c = hex.substring(1).split("");

            if (c.length == 3) {
                c = [c[0], c[0], c[1], c[1], c[2], c[2]];
            }

            c = "0x" + c.join("");

            return (
                "rgba(" +
                [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(",") +
                ",0)"
            );
        }
    }
};
</script>
