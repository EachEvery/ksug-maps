<template>
    <div
        class="p-6 bg-black relative text-white w-60vw mt-55vh min-h-20rem max-w-sm ||| lg:mt-0 lg:w-full"
    >
        <clickable
            class="absolute top-0 right-0 m-4 lg:m-8"
            @click="$emit('close')"
        >
            <close-icon class="w-5 h-5 text-white" />
        </clickable>

        <h1
            class="font-display text-3xl lg:text-5xl font-bold uppercase leading-none"
        >
            {{ tour.name }}
        </h1>
        <h3 class="font-display text-xl font-bold uppercase">
            {{ tour.duration }}
        </h3>

        <clickable
            class="h-12 flex items-center justify-center bg-white my-5 w-full"
            @click="handleStartButtonClick"
        >
            <span class="text-black font-bold font-mono uppercase">{{
                startButtonText
            }}</span>
        </clickable>

        <div class="flex my-6">
            <img
                src="/images/map.png"
                alt="A map folded at its creases"
                class="w-5 h-5 mr-4"
            />

            <p class="font-mono uppercase">
                Tour Beings at
                <br />
                <a href="#" class="underline">{{ firstPlace.name }}</a>
            </p>
        </div>

        <div class="leading-normal" v-html="tour.description"></div>

        <img :src="tour.photos[0].url" class="w-full h-48 object-cover mt-8" />
    </div>
</template>

<script>
import { mapState } from "vuex";

import clickable from "./Clickable";
import closeIcon from "./CloseIcon";
import handleBack from "../mixins/handleBack";

export default {
    mixins: [handleBack],

    components: {
        clickable,
        closeIcon
    },
    props: {
        tour: Object
    },
    computed: {
        ...mapState(["directions", "tourActive", "places"]),

        defaultBackRoute() {
            return "/explore";
        },

        firstPlace({ tour }) {
            return tour.stories[0].place;
        },

        startButtonText({ tourActive }) {
            return tourActive ? "Stop Tour" : "Start Tour";
        }
    },
    methods: {
        handleStartButtonClick() {
            this.$store.commit(
                "setTourIsActive",
                this.tourActive ? false : true
            );
        }
    }
};
</script>
