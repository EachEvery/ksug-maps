import Vue from "vue";
import Axios from "axios";
import Vuex from "vuex";
Vue.use(Vuex);

import { unique, filled } from "./functions/helpers";

export default new Vuex.Store({
    state: {
        stories: [],
        filters: [],
        comments: []
    },
    actions: {
        async ensureStories(context) {
            let { data } = await Axios.get("/stories");

            context.commit("setStories", data);
        }
    },
    mutations: {
        setStories(state, stories) {
            state.stories = stories;
        },
        clearFilters({ filters }) {
            filters.splice(0, filters.length);
        },
        toggleFilter({ filters }, filter) {
            let index = filters.findIndex(
                item => item.key === filter.key && item.value === filter.value
            );

            if (index === -1) {
                filters.push(filter);
            } else {
                filters.splice(index, 1);
            }
        }
    },
    getters: {
        roles({ stories }) {
            return filled(unique(stories.map(s => s.subject_title)));
        },
        names({ stories }) {
            return filled(unique(stories.map(s => s.subject_name)));
        },
        days({ stories }) {
            return filled(unique(stories.map(s => s.day)));
        },
        locations({ stories }) {
            return [...new Set(stories.map(story => story.location))].map(
                location => {
                    let locationStories = stories.filter(
                        item => item.location === location
                    );
                    return {
                        name: location,
                        stories: locationStories,
                        lat: stories[0].lat,
                        long: stories[0].long,
                        slug: getSlug(location, {
                            remove: /['()]/g
                        }).toLowerCase()
                    };
                }
            );
        }
    }
});
