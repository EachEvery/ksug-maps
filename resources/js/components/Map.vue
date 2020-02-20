<template>
    <div class="relative w-full h-screen bg-gray-dark" id="map"></div>
</template>
<script>
import mapbox from "mapbox-gl";

export default {
    data() {
        return {};
    },
    mounted() {
        mapboxgl.accessToken =
            "pk.eyJ1IjoibmF0ZWhvYmkiLCJhIjoiY2s1NDlrdDBoMGJwZjNqcWg2a2I4b2E4dyJ9.OTWHd3GaM7SfJvRuFUnpdg";

        this.map = new mapboxgl.Map({
            container: "map",
            style: "mapbox://styles/natehobi/ck6v53o4u08m31it2o4mtf1b2",
            center: [-81.348852, 41.15002],
            zoom: 15,
            maxBounds: [
                [-81.404828, 41.1119], // Southwest coordinates
                [-81.309106, 41.186267] //NE
            ]
        });
    }
};
</script>
