<template>
    <div
        class="flex py-3 px-4 bg-tan-100 text-black absolute top-full left-0 w-full z-10"
        :class="visibilityClass"
    >
        <div class="flex-shrink-0 mt-1" style="color: #988c88;">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
        </div>
        <span
            class="block ml-2 text-sm"
            style="color: #988c88;"
            v-if="notification"
            >Your location services are currently disabled, please enable them
            for the full experience.
        </span>
        <span v-else class="block ml-2 text-sm" style="color: #988c88;">
            {{ output.lat }} / {{ output.long }}
        </span>
        <!-- <button class="flex items-center underline" @click="handleGeolocation">
            Retry
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="block h-3 w-3 ml-1"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M14 5l7 7m0 0l-7 7m7-7H3"
                />
            </svg>
        </button> -->
    </div>
</template>

<script>
import { mapGetters, mapMutations } from "vuex";

export default {
    props: {
        open: Boolean
    },
    data() {
        return {
            output2: ""
        };
    },
    methods: {
        ...mapMutations(["addGeolocation"]),

        handleGeolocation() {
            // navigator.geolocation.getCurrentPosition(
            //     pos => {
            //         this.addGeolocation(pos);
            //         pos;
            //     },
            //     error => {
            //         console.log(error.code);
            //     }
            // );
        }
    },
    computed: {
        ...mapGetters(["userLocation", "userLocationTrial"]),

        notification({ userLocation, userLocationTrial }) {
            if (userLocation === undefined && userLocationTrial != undefined) {
                return 1;
            }
            return 0;
        },

        output({ userLocation }) {
            if (undefined != userLocation) {
                return {
                    lat: userLocation.coords.latitude,
                    long: userLocation.coords.longitude
                };
            }
            return {
                lat: "processing",
                long: "processing"
            };
        },

        visibilityClass() {
            return {
                invisible: this.open
            };
        }
    }
};
</script>
