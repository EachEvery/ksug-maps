/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

import Vue from "vue";
import KSUGMap from "./KSUGMap.vue";
import VueMeta from "vue-meta";
import ClickOutside from "v-click-outside";

import router from "./router";
import store from "./store";

/**
 * Next, we will create a fresh Vue application instance and attach it top
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueMeta, {
    refreshOnceOnNavigation: true
});

Vue.use(ClickOutside);

new Vue({
    store,
    router,
    el: "#app",
    render: h => h(KSUGMap)
});
