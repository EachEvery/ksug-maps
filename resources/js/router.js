import Vue from "vue";
import VueRouter from "vue-router";

import Location from "./components/Location.vue";
import Story from "./components/Story.vue";
import About from "./components/About.vue";

Vue.use(VueRouter);

export default new VueRouter({
    mode: "history",
    routes: [
        { path: "/about", component: About, name: "about" },
        { path: "/places/:location", component: Location, name: "location" },
        {
            path: "/places/:location/preview",
            component: Location,
            name: "preview"
        },

        { path: "/stories/:story", component: Story, name: "story" }
    ]
});
