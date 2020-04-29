<template>
    <div
        class="bg-black text-white p-4 w-80vw mt-75vh min-h-20rem max-w-sm || lg:mt-0 lg:w-full lg:mt-12"
        :data-step-geo="JSON.stringify(latLong)"
    >
        <img
            src="/images/upArrow.png"
            class="w-12 h-12"
            :style="getArrowStyle(step.maneuver.modifier)"
        />
        <h3 class="font-display uppercase text-3xl">
            <span>{{ step.maneuver.type }} {{ step.maneuver.modifier }}</span>
        </h3>
        <p>
            {{ step.maneuver.instruction }}.
            <span
                v-if="
                    step.maneuver.type === 'turn' &&
                        getFeet(step.distance) >= 15
                "
                >Continue for {{ getFeet(step.distance) }} feet.</span
            >
        </p>
    </div>
</template>
<script>
export default {
    props: {
        step: Object
    },
    mounted() {
        console.log(this.step);
    },
    computed: {
        latLong({ step }) {
            let longLat = step.geometry.coordinates[0];

            return [longLat[1], longLat[0]];
        }
    },
    methods: {
        getArrowStyle(string) {
            if (string === undefined) {
                return {};
            }

            let deg = 90;

            if (string.toString().includes("slight")) {
                deg = 45;
            }

            if (string.toString().includes("left")) {
                deg = deg * -1;
            }

            return {
                transform: `rotate(${deg}deg)`
            };
        },

        getMiles(meters) {
            return (meters & 0.000621371).toFixed(2);
        },
        getFeet(meters) {
            return 3.28084 & meters;
        },
        getRotation(str) {
            if (str === "right") {
                return "rotate(90deg)";
            }
            if (str === "left") {
                return "rotate(-90deg)";
            }
        }
    }
};
</script>
