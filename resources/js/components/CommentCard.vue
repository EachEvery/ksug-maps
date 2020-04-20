<template>
    <router-link
        class="bg-tan-100 relative shrink-when-active mb-8 flex flex-col overflow-hidden"
        :to="`/places/${place.slug}#comment-${comment.id}`"
        :style="{ '--transparentColor': transparentColor }"
    >
        <div
            class="text-black overflow-hidden relative p-5 flex-1 flex-shrink-0"
            :class="{
                'h-full': !comment.media_is_image,
                'h-48': comment.media_is_image
            }"
        >
            <div class="flex font-display font-bold text-base uppercase">
                {{ truncate(place.name, { length: 35 }) }}
            </div>

            <div class="self-end font-mono text-sm xl:text-2xs mt-3">
                {{ comment.frontend_date }}
            </div>

            <p
                class="leading-normal mt-5 text-base xl:text-base xl:leading-normal"
            >
                <quote-icon class="text-black w-4 pb-1 h-4 inline-block mr-2" />
                {{ comment.text }}
            </p>

            <div
                class="inset-x-0 bottom-0 w-full h-12 absolute gradient font-mono"
            ></div>
        </div>

        <img
            :src="comment.media_url"
            style="max-height: 10rem;"
            class="object-cover flex-grow flex-1"
            v-if="comment.media_is_image"
        />
    </router-link>
</template>

<style scoped lang="scss">
.gradient {
    background: linear-gradient(
        to bottom,
        var(--transparentColor),
        #f0ebe6 75%
    );
}
</style>
<script>
import _ from "lodash";
import quoteIcon from "./QuoteIcon";

import { mapGetters, mapState } from "vuex";

export default {
    components: {
        quoteIcon
    },

    props: {
        comment: Object
    },
    methods: {
        truncate: _.truncate
    },
    computed: {
        ...mapState(["places"]),
        place({ comment, places }) {
            return places.find(p => +p.id === +comment.place_id);
        },

        lat({ place }) {
            return +place.lat;
        },

        lng({ place }) {
            return +place.long;
        },

        transparentColor() {
            let hex = "#F0EBE6";
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
