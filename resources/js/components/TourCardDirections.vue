<template>
    <div class="mb-8">
        <p class=" block mt-2" v-if="state === 'default'">
            <span>{{ customDirections.text }}</span>
        </p>

        <div v-if="isAdmin" class="mt-2">
            <div v-if="state === 'editing'">
                <textarea
                    v-model="customDirections.text"
                    class="font-sans text-xs w-full p-2 border rounded"
                />

                <clickable
                    @click="state = 'default'"
                    class="bg-black text-white font-mono p-2 w-full text-center uppercase font-bold mt-2"
                    >Save</clickable
                >
            </div>

            <clickable
                @click="state = 'editing'"
                v-if="state !== 'editing'"
                class="bg-black text-white font-mono p-2 w-full text-center uppercase font-bold mt-1"
                >Edit Directions</clickable
            >
        </div>
    </div>
</template>
<script>
import clickable from "./Clickable";
import Axios from "axios";

export default {
    components: {
        clickable
    },

    props: {
        tour: Object,
        place: Object,
        step: Object,
        initCustomDirections: Object
    },

    data() {
        return {
            customDirections: {
                text: "",
                ...this.initCustomDirections
            },
            state: "default"
        };
    },

    watch: {
        state(state) {
            this.checkForEmptyText();

            if (state === "default") {
                this.$nextTick(() => {
                    this.update();
                });
            }
        }
    },
    methods: {
        checkForEmptyText() {
            if (this.customDirections.text.length === 0) {
                this.customDirections.text = this.defaultDirections;
            }
        },
        async update() {
            if (this.exists) {
                let { data: customDirections } = await Axios.put(
                    `/custom-directions/${this.customDirections.id}`,
                    this.fillable
                );

                this.customDirections = customDirections;
            } else {
                let { data: customDirections } = await Axios.post(
                    `/custom-directions`,
                    this.fillable
                );

                this.customDirections = customDirections;
            }
        }
    },
    computed: {
        fillable({ customDirections }) {
            return {
                custom_directions: {
                    text: customDirections.text,
                    tour_id: this.tour.id,
                    place_id: this.place.id
                }
            };
        },
        exists({ customDirections }) {
            return customDirections.id !== undefined;
        },

        showDefaultDirections({ exists }) {
            return !exists;
        },

        showCustomDirections({ exists, state }) {
            return exists && state !== "editing";
        },
        isAdmin() {
            return window.isAdmin;
        },
        defaultDirections({ place, step }) {
            return `When you're ready, depart from ${place.name} and ${step.maneuver.instruction}`;
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.checkForEmptyText();
        });
    }
};
</script>
