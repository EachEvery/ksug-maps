import Vue from "vue";
import Axios from "axios";
import Vuex from "vuex";

Vue.use(Vuex);

import { unique, filled } from "./functions/helpers";
import { mapPlaces, mapStories } from "./functions/ksug";

export default new Vuex.Store({
    state: {
        stories: [],
        filters: [],
        comments: [],
        places: [],
        tours: [],
        directions: undefined
    },
    actions: {
        ensureData(context) {
            return new Promise(async resolve => {
                let { data: stories } = await Axios.get("/stories");

                context.commit("setStories", stories);

                let { data: places } = await Axios.get("/places");

                context.commit("setPlaces", places);

                let { data: tours } = await Axios.get("/tours");

                context.commit("setTours", tours);

                resolve();
            });
        }
    },
    mutations: {
        setDirections(state, directions) {
            state.directions = directions;
        },
        setPlaces(state, places) {
            state.places = mapPlaces(places, state.stories);
        },
        setStories(state, stories) {
            state.stories = mapStories(stories);
        },
        setTours(state, tours) {
            state.tours = tours;
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
        }
    }
});
