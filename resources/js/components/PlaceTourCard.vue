<template>
    <div
        :data-place-id="place.id"
        :data-step-geo="JSON.stringify([+place.lat, +place.long])"
        class="bg-white max-w-sm p-4 w-80vw mt-60vh min-h-20rem || lg:mt-0 lg:w-full lg:max-w-none lg:mt-12"
    >
        <div
            class="flex items-center h-8 w-8 justify-center font-mono rounded-full bg-black text-white"
        >
            <span class="text-sm">{{ order }}</span>
        </div>

        <span class="font-mono uppercase block font-bold mt-4"
            >{{ order === 1 ? "Start" : "Arrive" }} At</span
        >
        <h1
            class="font-display text-xl md:text-3xl uppercase font-bold leading-none mb-3 mt-1"
        >
            {{ place.name }}
        </h1>

        <span
            v-if="place.photos.length"
            class="font-mono text-2xs mb-1 mt-4 block"
            >{{ place.photos[0].custom_properties.photo_caption }}</span
        >
        <img
            :src="place.photos[0].url"
            class="h-32 w-full object-cover"
            v-if="place.photos.length"
        />

        <tour-story-card
            class="mt-3"
            v-for="(story, s) in stories"
            :story="story"
            :key="`story-${s}`"
            :style="{ color: story.color }"
        />

        <tour-card-directions
            v-if="step !== undefined"
            :init-custom-directions="customDirections"
            :place="place"
            :step="step"
            :tour="tour"
        />

        <slot />
    </div>
</template>
<script>
import tourStoryCard from "./TourStoryCard";
import tourCardDirections from "./TourCardDirections";

export default {
    components: {
        tourStoryCard,
        tourCardDirections
    },
    props: {
        place: Object,
        step: Object,
        stories: Array,
        order: Number,
        tour: Object
    },
    computed: {
        customDirections({ place, tour }) {
            return tour.custom_directions.find(d => +d.place_id === +place.id);
        }
    }
};
</script>
