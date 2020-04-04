<template>
    <div class="leading-tight text-left px-5 h-full flex flex-col relative">
        <h1 class="font-display text-3xl font-black uppercase mb-3">
            search directory
        </h1>
        <div class="flex border-b-2 border-black">
            <input
                type="text"
                placeholder="Search by Name, Place, or Day"
                v-model="q"
                class="flex-grow py-2 focus:outline-none"
            />
            <spinner class="w-5 h-5 self-end mb-2" v-if="state === 'loading'" />
            <search-icon class="w-5 h-5 self-end mb-2" v-else />
        </div>

        <div
            class="inset-x-0 bg-white overflow-auto absolute z-50 border-b border-g px-5"
            style="top: 6.26rem; bottom: 1rem;"
            v-if="results.length > 0"
        >
            <router-link
                :to="`/stories/${result.id}`"
                v-for="(result, i) in results"
                :key="i"
                class="px-8 py-4 hover:bg-grey-100 block border-white"
                :class="{ 'border-t-2': i > 0 }"
                :style="{ background: result.color }"
            >
                <h1 class="text-grey-800 font-bold mb-2">
                    {{ result.subject }}
                </h1>
                <p class="font-light leading-tight tracking-tight opacity-75">
                    {{ result.day }} &middot;
                    {{ truncate(result.content, { length: 150 }) }}
                </p>
            </router-link>
        </div>
    </div>
</template>
<script>
import clickable from "./Clickable";
import searchIcon from "./SearchIcon";
import spinner from "./Spinner";
import axios from "axios";
import _ from "lodash";
import Fuse from "fuse.js";

import { getStoryColor } from "../functions/ksug";
import { mapGetters, mapMutations, mapState } from "vuex";

export default {
    components: {
        clickable,
        searchIcon,
        spinner,
    },
    data() {
        return {
            state: "default",
            q: "",
        };
    },

    computed: {
        ...mapState(["stories"]),
        results({ q, stories }) {
            if (q.trim() === "") {
                return stories;
            }

            let fuse = new Fuse(stories, {
                keys: ["subject", "role", "place.name", "day", "content"],
                threshold: 0.1,
            });

            let matchingIds = fuse.search(q).map((result) => {
                return result.item.id;
            });

            return stories.filter((s) => {
                return matchingIds.includes(s.id);
            });
        },
    },
    methods: {
        getStoryColor,
        truncate: _.truncate,
    },
};
</script>

<style>
input::-webkit-input-placeholder {
    color: #000;
}
input:-moz-placeholder {
    color: #000;
}
input::-moz-placeholder {
    color: #000;
}
input:-ms-input-placeholder {
    color: #000;
}
</style>
