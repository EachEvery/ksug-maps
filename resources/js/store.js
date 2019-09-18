import Vue from "vue";
import Axios from "axios";
import Vuex from "vuex";

Vue.use(Vuex);

import { unique, filled } from "./functions/helpers";
import { mapStoriesToLocations, mapStories } from "./functions/ksug";

export default new Vuex.Store({
    state: {
        stories: [],
        filters: [],
        comments: []
    },
    actions: {
        ensureStories(context) {
            return new Promise(async resolve => {
                let { data } = await Axios.get("/stories");

                context.commit("setStories", data);

                resolve();
            });
        }
    },
    mutations: {
        setStories(state, stories) {
            state.stories = mapStories(stories);
        },
        clearFilters({ filters }) {
            filters.splice(0, filters.length);
        },
        toggleFilter({ filters }, filter) {
            let index = filters.findIndex(
                item => item.key === filter.key && item.value === filter.value
            );

            index === -1 ? filters.push(filter) : filters.splice(index, 1);
        }
    },
    getters: {
        isAdmin() {
            return window.isAdmin;
        },
        roles({ stories }) {
            return filled(unique(stories.map(s => s.role)));
        },
        names({ stories }) {
            return filled(unique(stories.map(s => s.subject)));
        },
        days({ stories }) {
            return filled(unique(stories.map(s => s.day)));
        },
        locations({ stories }) {
            return mapStoriesToLocations(stories);
        }
    }
});
