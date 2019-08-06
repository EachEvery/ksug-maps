import Vue from "vue";
import VueRouter from "vue-router";

import Location from "./components/Location.vue";

Vue.use(VueRouter);

export default new VueRouter({
    routes: [
        { path: "/places/:location", component: Location, name: "location" },
        {
            path: "/places/:location/preview",
            component: Location,
            name: "preview"
        }
    ]
});
