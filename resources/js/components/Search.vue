<template>
    <div class="leading-tight text-left px-5 h-full flex flex-col relative">
        <h1
            class="font-display text-lg mt-4 md:mt-0 md:text-3xl font-black uppercase mb-3"
        >
            search directory
        </h1>
        <div class="flex border-b-2 border-black">
            <input
                type="text"
                placeholder="To search type name, place, or day here."
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
            <div v-for="(result, i) in results" :key="i">
                <router-link
                    v-if="result.type === 'story'"
                    :to="`/stories/${result.item.id}`"
                    :key="i"
                    class="px-4 md:px-8 py-4 hover:bg-grey-100 block border-white"
                    :class="{ 'border-t-2': i > 0 }"
                    :style="{ background: result.item.color }"
                >
                    <h1 class="text-grey-800 font-bold mb-2">
                        {{ result.item.subject }}
                    </h1>
                    <p
                        class="font-light text-xs md:text-base leading-tight tracking-tight opacity-75"
                    >
                        {{ result.item.day }} &middot;
                        {{ truncate(result.item.content, { length: 150 }) }}
                    </p>
                </router-link>

                <router-link
                    v-if="result.type === 'place'"
                    :to="`/places/${result.item.slug}`"
                    :key="i"
                    class="px-4 md:px-8 py-4 hover:bg-gray-100 bg-gray-200 block border-white pb-24"
                    :class="{ 'border-t-2': i > 0 }"
                >
                    <h1 class="text-gray-800 font-bold mb-2">
                        {{ result.item.name }}
                    </h1>
                    <p
                        class="font-light text-xs md:text-base leading-tight tracking-tight opacity-75"
                    >
                        Click to view location
                    </p>
                </router-link>
            </div>
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
        spinner
    },
    data() {
        return {
            state: "default",
            q: ""
        };
    },

    computed: {
        ...mapState(["stories", "places"]),

        searchable({ stories, places }) {
            let serachableStories = stories.map(s => ({
                item: s,
                sortOn: s.subject,
                searchKeys: ["subject", "role", "day", "content"],
                type: "story"
            }));

            let searchablePlaces = places.map(p => ({
                item: p,
                sortOn: p.name,
                searchKeys: ["name"],
                type: "place"
            }));

            return {
                stories: serachableStories,
                places: searchablePlaces
            };
        },
        results({ q, searchable }) {
            if (q.trim() === "") {
                return this.sort([...searchable.stories, ...searchable.places]);
            }

            let storyResults = this.search(
                searchable.stories,
                searchable.stories[0].searchKeys
            );

            let placeResults = this.search(
                searchable.places,
                searchable.places[0].searchKeys
            );

            return this.sort([...storyResults, ...placeResults]);
        }
    },
    methods: {
        getStoryColor,
        truncate: _.truncate,
        sort(collection, sortKey = "sortOn") {
            return collection.sort((a, b) => {
                if (a[sortKey] > b[sortKey]) {
                    return 1;
                }
                if (b[sortKey] > a[sortKey]) {
                    return -1;
                }
                return 0;
            });
        },
        search(collection, keys, threshold = 0.1, id = "id") {
            let fuse = new Fuse(
                collection.map(c => c.item),
                {
                    keys: keys,
                    threshold: threshold
                }
            );

            let matchingIds = fuse.search(this.q).map(result => {
                return result.item.id;
            });

            // // let matchingIds = fuse.search(this.q);

            // debugger;

            return collection.filter(i => {
                return matchingIds.includes(i.item.id);
            });
        }
    }
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
