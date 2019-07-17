/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

import Vue from "vue";
import KSUGMap from "./KSUGMap.vue";
import VueRouter from "vue-router";
import Location from "./components/Location.vue";

Vue.use(VueRouter);

let router = new VueRouter({
    routes: [
        { path: "/places/:location", component: Location, name: "location" },
        {
            path: "/places/:location/preview",
            component: Location,
            name: "preview"
        }
    ]
});

/**
 * Next, we will create a fresh Vue application instance and attach it top
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    router,
    el: "#app",
    render: h => h(KSUGMap)
});
