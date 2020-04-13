<template>
  <div
    :data-location-id="place.id"
    :data-step-geo="JSON.stringify([+place.lat, +place.long])"
    class="bg-white max-w-sm p-4 w-60vw mt-55vh min-h-20rem || lg:mt-0 lg:w-full lg:max-w-none lg:mt-12"
  >
    <div
      class="flex items-center h-8 w-8 justify-center font-mono rounded-full bg-black text-white"
    >
      <span class="text-sm">{{order}}</span>
    </div>

    <span class="font-mono uppercase block font-bold mt-4">{{order === 1? 'Start' : 'Arrive'}} At</span>
    <h1 class="font-display text-3xl uppercase font-bold leading-none mb-3 mt-1">{{place.name}}</h1>

    <img :src="place.photos[0].url" class="h-32 w-full object-cover" v-if="place.photos.length" />

    <tour-story-card
      class="mt-3"
      v-for="(story, s) in stories"
      :story="story"
      :key="`story-${s}`"
      :style="{ color: story.color }"
    />

    <p
      class="mb-8 mt-4 block"
      v-if="step !== undefined"
    >When you're ready, depart from {{place.name}} and {{step.maneuver.instruction}}</p>

    <slot />
  </div>
</template>
<script>
import tourStoryCard from "./TourStoryCard";

export default {
  components: {
    tourStoryCard
  },
  props: {
    place: Object,
    step: Object,
    stories: Array,
    order: Number
  }
};
</script>