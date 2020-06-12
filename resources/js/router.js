import Vue from "vue";
import VueRouter from "vue-router";

import Location from "./components/Location.vue";
import Story from "./components/Story.vue";
import About from "./components/About.vue";
import Tour from "./components/Tour.vue";
import LearningResources from "./components/LearningResources";

Vue.use(VueRouter);

export default new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/resources",
            component: LearningResources,
            name: "resources"
        },

        { path: "/about", component: About, name: "about" },

        { path: "/explore", name: "explore" },

        { path: "/places/:location", component: Location, name: "location" },

        {
            path: "/places/:location/preview",
            component: Location,
            name: "preview"
        },

        { path: "/stories/:story", component: Story, name: "story" },

        { path: "/tours/:tour", component: Tour, name: "tour" }
    ]
});
